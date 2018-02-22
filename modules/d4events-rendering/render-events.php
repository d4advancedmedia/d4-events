<?php

function d4events_get_events2($range_start,$range_stop,$shortcode_args) {
	
	if ($shortcode_args['terms'] != '') {
		$event_terms_array = array(
			'taxonomy' 	=> $shortcode_args['taxonomy'],
			'field'    	=> $shortcode_args['tax_field'],
			'terms'    	=> $shortcode_args['terms'],
		);
	}
	if ($shortcode_args['exclude_terms'] != '') {
		$event_exclude_terms_array = array(
			'taxonomy' 	=> $shortcode_args['taxonomy'],
			'field'    	=> $shortcode_args['tax_field'],
			'terms'    	=> $shortcode_args['exclude_terms'],
			'operator'	=> 'NOT IN',
		);
	}

	$tax_query = array(
		'relation' 		=> 'AND',
		$event_terms_array,
		$event_exclude_terms_array,
	);

	if($range_start > $range_stop) {
		$range_array = array($range_stop,$range_start);
	} else {
		$range_array = array($range_start,$range_stop);
	}

	//dont process repeats for all+list shorties, which have the following start/stop values. range not needed either
	if( ($range_start == '01/01/1800') && ($range_stop == '01/01/2100') ) {
		$events_args = array (
			'post_type' 	=> 'd4events',
			'tax_query'		=>  $tax_query,
			'posts_per_page'=>	-1,
			'meta_key'		=> 'd4events_start',
			'orderby'		=> 'meta_value_num',
			'order'			=> 'DESC'
		);
	}

	else {

		$meta_query = array(
			'relation'		=>	'OR',
			'standard'		=>	array(
				'compare'		=>	'BETWEEN',
				'value'			=>	$range_array,
				'type'			=> 'numeric',
				'key'			=> 'd4events_start'
			),
			'repeat'		=>	array(
				'compare'		=>	'!=',
				'value'			=>	'',
				'key'			=> 'd4events_repeating'
			),
		);

		$events_args = array (
			'post_type' 	=> 'd4events',
			'tax_query'		=>  $tax_query,
			'posts_per_page'=>	-1,
			'meta_query'	=> array($meta_query),
			'orderby'		=> 'meta_value_num',
			'order'			=> 'DESC'
		);		
	}

	$events_query = new WP_Query($events_args);

	usort($events_query->posts, 'd4events_sort_by_start_time');

	return $events_query;
}

function d4events_sort_by_start_time($a,$b) {
	$datetime_array_a = d4events_fetch_datetime($a->ID);
	$datetime_array_b = d4events_fetch_datetime($b->ID);

	if ( strtotime($datetime_array_a['d4events_start_time']) <= strtotime($datetime_array_b['d4events_start_time']) ) {
		return 'success';
	}
}

function d4events_render_single($shortcode_args,$calendar_date) {

	$link_open = '';
	$link_close = '';
	$readmore = '';
	$ID = get_the_ID();
	
	//custom output support, places all event IDs, dates, and files in an array for custom output
	if(has_filter($shortcode_args['output_filter'])) {
		$event_data = array(
			'id'				=> $ID,
			'date'				=> $calendar_date,
			'shortcode_args'	=> $shortcode_args,
		);
		$event_content = apply_filters($shortcode_args['output_filter'], $event_data);
		return $event_content;
	}

	#if ($links == 'true') {
		$link_open = '<a href="'.get_the_permalink().'?date='.$calendar_date.'">';	
		$link_close = '</a>';
		$readmore = '<a class="events_list-readmore" href="'.get_the_permalink().'?date='.$calendar_date.'">Read More</a>';
	#}

	if($shortcode_args['style'] == 'calendar') {
		//Render output
			
		$event_content .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
		$event_content .= '<h5 class="cal-event-title">'.$link_open.'<span>'.get_the_title().'</span>'.$link_close;
		$event_content .= '<div class="clearfix"></div></div>';
	}

	elseif($shortcode_args['style'] == 'agenda') {	

		$datetime_array = d4events_fetch_datetime($ID);

		$event_content .= '<tr class="agenda-single-event"><td class="agenda-time-column">'.$datetime_array['d4events_start_time'].'</td>';
		$event_content .= '<td class="agenda-title-column">'.$link_open.'<span>'.get_the_title().'</span>'.$link_close.'</td></tr>';
		
	}

	elseif($shortcode_args['style'] == 'list') {

		$datetime_array = d4events_fetch_datetime($ID);

		$post_thumbnail = '';
		if (has_post_thumbnail()) {
			$post_thumbnail = '<div class="events_list-thumb">'.$link_open.get_the_post_thumbnail($ID,$thumbnail_size).$link_close.'</div>';
			$has_image = ' event-has-image';
		}

		#$content_length = intval($content_length);
		$file_array = d4events_get_files();
		$file_cluster = d4events_output_files($shortcode_args['files'],$file_array);

		$content_length = 255;
		$post_content = get_the_excerpt();
		if (strlen($post_content) > $content_length) {
			$post_content_modified = preg_replace('/\s+?(\S+)?$/', '', substr(wpautop(do_shortcode($post_content)), 0, $content_length)).' […]';
		} else {
			$post_content_modified = $post_content;
		}

		$event_content .= '<div class="events_list-single'.$has_image.'" data-event_date="'.$calendar_date.'" data-event_id="'.$ID.'">';
		$event_content .= $post_thumbnail;	
		$event_content .= '<h5 class="cal-event-title">'.$link_open.'<span>'.get_the_title($ID).'</span>'.$link_close.'<span class="events_list-datetime"><span class="events_list-date">'.date('m/d/Y',$calendar_date).'</span><span class="events_list-time">'.$datetime_array['d4events_start_time'].'</span></span></h5>';
		$event_content .= '<div class="events_list-content"><div class="events_list-description">'.$post_content_modified.'</div>';
		$event_content .= $readmore.'<div class="clearfix"></div>';
		$event_content .= $file_cluster;
		$event_content .= '<div class="clearfix"></div></div></div>';
		
	}

	return $event_content;

}

function d4events_output_files($files,$file_array) {
	//This is the array of file categories listed in the shortcode, so that the files can be sorted by category (ex: Agenda, Meeting, Minutes)
	$shortcode_filecats = explode(',',$files);

	$multicount = d4events_multipass_counter();

	if ( isset($files) && ($files != '') && ($multicount != 0) && (!empty($file_array)) ) {

		$file_cluster = '';
		$filetype_title = '';
		
		$file_cluster .= '<div class="files-wrapper">';
		foreach ($shortcode_filecats as $type) {	
			$i = 1;
			$file_cluster .= '<div class="event-filegroup">';					
			foreach ($file_array as $file) {
				if ( ($type == strtolower($file['type'])) && ($file['link'] != '') ) {
					if ($filetype_title == $file['type']) {									
						$file_cluster .= $file['link'];	
					} else {
						$file_cluster .= '<h6 class="event-filetype">'.$file['type'].'</h6>';
						$file_cluster .= $file['link'];
						$filetype_title = $file['type'];
					}
					
				}
				$i++;
			}
			$file_cluster .= '</div>';
		}
		$file_cluster .= '</div>';
	}

	return $file_cluster;

}

function d4events_get_files($files) {

	$file_array = array();
	//determine number of multipass					
	$multicount = d4events_multipass_counter();
	
	for ($k = 1 ; $k <= $multicount; $k++) {			
		$meta = get_post_meta(get_the_ID(), 'd4events_file_'.$k, true);
		if ($meta[1] != '') {				
			$file_type = $meta[0];
			if ($meta[2] != '') {
				$file_name = $meta[2];
			} else {
				$file_name = end((explode('/', rtrim($meta[1], '/'))));
			}
			if ( isset($meta[1]['extension']) ) {
				$file_class = 'fileclass_'.$meta[1]['extension'];
			}
			$file_link = '<a href="'.$meta[1].'" class="events_files '.$file_class.'" target="_blank">'.$file_name.'</a>';
			
			$file_array[] = array(
					'type' => $file_type,
					'name' => $file_name,
					'link' => $file_link,
			);
		}
		
	}

	return $file_array;

}

//Processes events to see if they fall on a given date, including repeating events
function d4events_process_events($event_date,$events_query,$lastdate,$shortcode_args) {
	
	$calendar_date = strtotime($event_date);

	$day_of_the_week = date('l', $calendar_date);
	$day_of_the_month = date('j', $calendar_date);
	$nth_weekday_of_every_month = ceil($day_of_the_month / 7);

	$i=0;
	$output['content'] = '';

	while ( $events_query->have_posts() ) { $events_query->the_post();

		$event_id = get_the_id();

		$blackoutdates = get_post_meta($event_id,'d4events_blackout_dates',true);

		if ($blackoutdates == '') {
			$blackoutdates = array();
		}		

		if (!in_array(date('m/d/Y',$calendar_date), $blackoutdates)) {

			$datetime_array = d4events_fetch_datetime($event_id);
			$start_timestamp = get_post_meta($event_id,'d4events_start',true);
			$end_timestamp = get_post_meta($event_id,'d4events_end',true);
			$repeat_end_timestamp = get_post_meta($event_id,'d4events_repeat_end_date',true);

			
			$current_time = strtotime('now');

			//if the last date is greater than the current date, then you are loading more events and the cuttoff date needs to be moved into the future
			if ($lastdate >= $current_time) {
				$current_time = $lastdate;
			}

			//determine the true start/end times of the current repeating event
			$repeat_starttime = $calendar_date + ($start_timestamp - strtotime($datetime_array['d4events_start_date']));
			$repeat_endtime = $calendar_date + ($end_timestamp - $start_timestamp);

			if ( ($shortcode_args['range'] == 'past') && ($repeat_starttime > strtotime('now')) ) {
				//event is in the future, range is in the past, exit.
				return;
			} elseif ( ($shortcode_args['range'] == 'future') && ($repeat_starttime < $current_time) ) {
				//event is in the future, range is in the past, exit.
				return;
			}
				
			$posttitle = '<h5 class="cal-event-title">'.get_the_title().'</h5>';

			$remove_link = get_post_meta( $event_id, 'd4events_remove_link', true);
			if ($remove_link != 'on') {
				$linkopen = '<a href="'.get_the_permalink().'">';
				$linkclose = "</a>";
			} else {
				$linkopen = '';
				$linkclose = '';
			}			

			$event_duration = date('j', $end_timestamp) - date('j', $start_timestamp);

			$repeating = get_post_meta( $event_id, 'd4events_repeating', true );
			$repeating_event = false;

			if ( ($shortcode_args['range'] == 'all') && ($shortcode_args['style'] == 'list') ) {}
			else {

				if ( ($repeating != '') && ($calendar_date > $start_timestamp) && ($calendar_date <= $repeat_end_timestamp) ) {

					//create a new dateperiod object for the repeating event
					$datePeriod_begin = new DateTime( date('Y-m-d',$start_timestamp) );
					$datePeriod_end = new DateTime( date('Y-m-d',$end_timestamp) );
					$datePeriod_end = $datePeriod_end->modify( '+1 day' ); 

					$datePeriod_interval = new DateInterval('P1D');
					$datePeriod_daterange = new DatePeriod($datePeriod_begin, $datePeriod_interval ,$datePeriod_end);

					$weekly_repeat_days = get_post_meta( $event_id, 'd4events_repeat_days', true );
					if ($weekly_repeat_days != '') {
						if (in_array($day_of_the_week, $weekly_repeat_days)) {
							$repeating_event = true;
						}
					}

					$repeat_interval = get_post_meta( $event_id, 'd4events_repeat_interval', true );
					$end_day_of_the_month = $event_duration + $repeat_interval;

					$month_repeat_by = get_post_meta( $event_id, 'd4events_monthly_repeat_by', true );

					if ($month_repeat_by == 'day_of_the_week') {
						#$month_weekday_repeat = get_post_meta( $event_id, 'd4events_month_weekday_repeat', true );

						$month_weekday_repeat = array();
						foreach($datePeriod_daterange as $datePeriod_date){
						    $month_weekday_repeat[] = $datePeriod_date->format('l');
						}

						if (in_array($day_of_the_week, $month_weekday_repeat) && ($repeat_interval == $nth_weekday_of_every_month)) {
							$repeating_event = true;
						}
					}
					else {
						if (($repeat_interval <= $day_of_the_month) && ($day_of_the_month <= $end_day_of_the_month)) {
							$repeating_event = true;
						}
					}
				}
			}
			
			if ($shortcode_args['style'] == 'list') {

				if (	( $calendar_date == strtotime($datetime_array['d4events_start_date']) ) || ($repeating_event == true)	) {

					//Render output				
					$output['content'] .= d4events_render_single($shortcode_args,$calendar_date);
					$i++;

				}
			}

			elseif (	( ($calendar_date >= strtotime($datetime_array['d4events_start_date'])) && ($calendar_date <= $end_timestamp)) || ($repeating_event == true)	) {

				//Render output			
				$output['content'] .= d4events_render_single($shortcode_args,$calendar_date);
				$i++;

			}
		}
	}
	$output['total'] = $i;
	return $output;
	wp_reset_postdata();
}

//draws a calendar
function d4events_draw_calendar($shortcode_args){
	
	if ( ($shortcode_args['style'] == 'list') || ($shortcode_args['style'] == 'agenda') ) {
		$repeat = 12;
	} else {
		$repeat = 1;
	}

	if ($shortcode_args['month'] == '') {
		$shortcode_args['month'] = date("n");
	}
	if ($shortcode_args['year'] == '') {
		$shortcode_args['year'] = date("Y");
	}

	$current_time = strtotime('now');

	if ( ($shortcode_args['range'] != 'future') && ($shortcode_args['range'] !='past') && ($shortcode_args['range'] != 'all') ) {
		//a date was supplied for the range variable, which only occurs when the "loadmore" button is clicked. This value is the last date of the previous agenda/list group
		$lastdate = $shortcode_args['range'];
		if ($lastdate >= $current_time) {
			$shortcode_args['range'] = 'future';
		} else {
			$shortcode_args['range'] = 'past';
			$current_time = $lastdate;
		}
	}

	

	$prev_count = 0;
	$success_count = 0;

	if ($shortcode_args['range'] == 'all' && $shortcode_args['style'] == 'list') {
		$range_start = '01/01/1800';
		$range_stop = '01/01/2100';

		$events_query = d4events_get_events2($range_start,$range_stop,$shortcode_args);

		while ( $events_query->have_posts() ) { $events_query->the_post();
			$calendar_date = get_post_meta(get_the_ID(),'d4events_start',true);
			$list_events .= d4events_render_single($shortcode_args,$calendar_date);
		}
		wp_reset_postdata();
	}

	else {

		//Repeat the calendar function: up to 12 times for an agenda/list or until 15 dates are returned including repeats, or once for a calendar to show a single month. This makes the tool very resource intensive and will be replaced with a more efficient funciton in a later release.
		
		for($c = 0; $c < $repeat; $c++):

			//this variable only increments when a date is matched, capping the total number of returned events in the case of an agenda/list (needed for repeats)
			if ($success_count <= 15) {

			
				//when the loop completes in the case of an agenda/list, the next month will be set allowing the loop to run again for the next calendar month
				if ($nextmonth != '') {			
					$shortcode_args['month'] = $nextmonth;
				}

				if ($nextyear != '') {			
					$shortcode_args['year'] = $nextyear;
				}	
			
				if ($shortcode_args['range'] == 'past') {
					$dateObj   = DateTime::createFromFormat('m/d/Y', date('m/d/Y',$current_time));
					$monthName = $dateObj->format('F'); // March

					$range_start = strtotime('01/01/1800');
					$range_stop = $current_time;
				}

				elseif ($shortcode_args['range'] == 'future') {
					if ($lastdate != '') {
						$list_start_date = $lastdate;
					} else {
						$list_start_date = $current_time;
					}

					$dateObj   = DateTime::createFromFormat('m/d/Y', date('m/d/Y',$list_start_date));
					
					if ($lastdate != '') {
						$dateObj->modify('+1 day');
						$current_time = strtotime($dateObj->format('m/d/Y'));
					}
					$monthName = $dateObj->format('F'); // March

					$range_start = $current_time;
					$range_stop = strtotime('01/01/2100');
				}

				else {

					$dateObj   = DateTime::createFromFormat('m/d/Y', $shortcode_args['month'].'/1/'.$shortcode_args['year']);
					$monthName = $dateObj->format('F'); // March

					#$range_start = strtotime($dateObj->format('m/d/Y'));
					$dateObj->modify('next month');
					#$range_stop = strtotime($dateObj->format('m/d/Y'));

					$range_start = strtotime('01/01/1800');
					$range_stop = strtotime('01/01/2100');
				}
				
				/* draw table */
				$calendar .= '<div data-month="'.$shortcode_args['month'].'" data-year="'.$shortcode_args['year'].'" data-terms="'.$shortcode_args['terms'].'" data-taxonomy="'.$shortcode_args['taxonomy'].'" data-tax_field="'.$shortcode_args['tax_field'].'" data-exclude_terms="'.$shortcode_args['exclude_terms'].'" class="d4-event-calendar"><div class="cal-change-button cal-prev" data-change="cal-prev"></div><div class="cal-change-button cal-next" data-change="cal-next"></div><h2>'.$monthName.' '.$shortcode_args['year'].'</h2><table cellpadding="0" cellspacing="0" class="calendar">';

				/* table headings */
				$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

				$agenda_headings = array('Day','Date','Time','Event');
				#$agenda .= '<tr class="agenda-header-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$agenda_headings).'</td></tr>';

				/* days and weeks vars now ... */
				$running_day = date('w',mktime(0,0,0,$shortcode_args['month'],1,$shortcode_args['year']));
				$days_in_month = date('t',mktime(0,0,0,$shortcode_args['month'],1,$shortcode_args['year']));
				$days_in_this_week = 1;
				$day_counter = 0;
				$dates_array = array();

				# get all events, place in array to send to d4events_get_events()

				$events_query = d4events_get_events2($range_start,$range_stop,$shortcode_args);

				/* row for week one */
				$calendar.= '<tr class="calendar-row">';

				/* print "blank" days until the first of the current week */
				for($x = 0; $x < $running_day; $x++):
					$calendar.= '<td class="calendar-day-np"> </td>';
					$days_in_this_week++;
				endfor;

				$month_has_events = false;

				if($shortcode_args['range'] == 'past') {
				
					/* keep going with days.... */
					for($list_day = 31; $list_day >= 1; $list_day--):
						
						if (strlen($list_day) < 2) {
							$fixed_day = '0'.$list_day;	
						} else {
							$fixed_day = $list_day;
						}
						if (strlen($shortcode_args['month']) < 2) {
							$fixed_month = '0'.$shortcode_args['month'];	
						} else {
							$fixed_month = $shortcode_args['month'];
						}
						$fulldate = $fixed_month.'/'.$fixed_day.'/'.$shortcode_args['year'];

						$processed_events = d4events_process_events($fulldate,$events_query,$lastdate,$shortcode_args);

						$day_events = $processed_events['content'];

						if (!empty($day_events)) {
							$has_events = ' has-events';
							$month_has_events = true;

							$success_count += intval($processed_events['total']);
						}

						$calendar.= '<td class="calendar-day '.$has_events.'"><div class="day-internal">';
						unset($has_events);
						/* add in the day number */
						$calendar.= '<div class="day-number">'.$list_day.'</div>';
			
						if($shortcode_args['style'] == 'calendar') {
							$calendar .= $day_events;
						}

						//return single event content if under the success count limiter
						elseif ( ($success_count <= 15) && ( ($shortcode_args['style'] == 'list') || ($shortcode_args['style'] == 'agenda') ) ) {
							$list_events .= $day_events;
						}

						$calendar.= '</div></td>';

						if($running_day == 6):
							$calendar.= '</tr>';
							if(($day_counter+1) != $days_in_month):
								$calendar.= '<tr class="calendar-row">';
							endif;
							$running_day = -1;
							$days_in_this_week = 0;
						endif;
						$days_in_this_week++; $running_day++; $day_counter++;
					endfor;

				} else {

					for($list_day = 1; $list_day <= $days_in_month; $list_day++):
						
						if (strlen($list_day) < 2) {
							$fixed_day = '0'.$list_day;	
						} else {
							$fixed_day = $list_day;
						}
						if (strlen($shortcode_args['month']) < 2) {
							$fixed_month = '0'.$shortcode_args['month'];	
						} else {
							$fixed_month = $shortcode_args['month'];
						}
						$fulldate = $fixed_month.'/'.$fixed_day.'/'.$shortcode_args['year'];

						$processed_events = d4events_process_events($fulldate,$events_query,$lastdate,$shortcode_args);

						$day_events = $processed_events['content'];

						if (!empty($day_events)) {
							$has_events = ' has-events';
							$month_has_events = true;
							$agenda .= '<tr class="agenda-day-row '.$has_events.'" data-event_date="'.strtotime($fulldate).'"><td class="agenda-day-column">'.date('l',strtotime($fulldate)).'</td><td class="agenda-date-column">'.date('M d',strtotime($fulldate)).'</td>';
							$agenda .= '<td><table>'.$day_events.'</table></td>';
							$success_count += intval($processed_events['total']);
						}

						$calendar.= '<td class="calendar-day '.$has_events.'"><div class="day-internal">';
						unset($has_events);
						/* add in the day number */
						$calendar.= '<div class="day-number">'.$list_day.'</div>';
			
						if($shortcode_args['style'] == 'calendar') {
							$calendar .= $day_events;
						}

						//return single event content if under the success count limiter
						elseif ( ($success_count <= 15) && ( ($shortcode_args['style'] == 'list') || ($shortcode_args['style'] == 'agenda') ) ) {
							$list_events .= $day_events;
						}

						$calendar.= '</div></td>';

						if($running_day == 6):
							$calendar.= '</tr>';
							if(($day_counter+1) != $days_in_month):
								$calendar.= '<tr class="calendar-row">';
							endif;
							$running_day = -1;
							$days_in_this_week = 0;
						endif;
						$days_in_this_week++; $running_day++; $day_counter++;
					endfor;

					//close the week row, agenda view
					#$agenda .= '</tr>';
				}

				/* finish the rest of the days in the week */
				if($days_in_this_week < 8):
					for($x = 1; $x <= (8 - $days_in_this_week); $x++):
						$calendar.= '<td class="calendar-day-np"> </td>';
					endfor;
				endif;

				/* final row */
				$calendar.= '</tr>';

				/* end the table */
				$calendar.= '</table></div>';

				/* Display No Meeting Events Notice */
				if ($month_has_events == false) {
					$calendar .= '<div class="no-events">There are no scheduled events for this month</div>';
				}

				if ( ($shortcode_args['style'] == 'list') || ($shortcode_args['style'] == 'agenda') ) {

					if($shortcode_args['range'] == 'past') {
						$nextmonth = $shortcode_args['month']-'1';
					} else {
						$nextmonth = $shortcode_args['month']+'1';
					}
					
					if ($nextmonth == '13') {
							$nextmonth ='1';
							$nextyear = $shortcode_args['year']+'1';
					}

					elseif ($nextmonth == '0') {
							$nextmonth ='12';
							$nextyear = $shortcode_args['year']-'1';
					}

					else $nextyear = $shortcode_args['year'];	
				}
			}
		endfor;
		
		/* all done, return result */
	}

	if ($shortcode_args['style'] == 'calendar') {
		return $calendar;
	}

	elseif ($shortcode_args['style'] == 'agenda') {
		return $agenda;
	}

	elseif ($shortcode_args['style'] == 'list') {
		return $list_events;
	}
	
}


add_action( 'wp_ajax_cal_change', 'd4events_ajax_cal_change' );
add_action( 'wp_ajax_nopriv_cal_change', 'd4events_ajax_cal_change' );


function d4events_ajax_cal_change() {

	$shortcode_args['style'] = 'calendar';

    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['month']))
		{
		    $month = $_POST['month'];
		}
	if(isset($_POST['year']))
		{
		    $year = $_POST['year'];
		}
	if(isset($_POST['terms']))
		{
		    $terms = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
		    $terms = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
		    $terms = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
		    $exclude_terms = $_POST['exclude_terms'];
		}	
	if(isset($_POST['change']))
		{
		    $change = $_POST['change'];		    
		}
	if ($change == "cal-prev") {
		$shortcode_args['month'] = $month-'1';			
	}
	if ($change == "cal-next") {
		$shortcode_args['month'] = $month+'1';	
	}
	if ($shortcode_args['month'] == '13') {
			$shortcode_args['month'] ='1';
			$shortcode_args['year'] = $year+'1';
	}
	elseif ($shortcode_args['month'] == '0') {
			$shortcode_args['month'] ='12';
			$shortcode_args['year'] = $year-'1';
	}
	else $shortcode_args['year'] = $year;	
	$shortcode_args['range'] = 'all';

    echo d4events_draw_calendar($shortcode_args);
    die();
}

add_action( 'wp_ajax_loadmore', 'd4events_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'd4events_ajax_loadmore' );


function d4events_ajax_loadmore() {
    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['lastdate'])) {
		$shortcode_args['lastdate'] = $_POST['lastdate'];
	}
	if(isset($_POST['terms']))
		{
		    $shortcode_args['terms'] = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
		    $shortcode_args['taxonomy'] = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
		    $shortcode_args['tax_field'] = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
		    $shortcode_args['exclude_terms'] = $_POST['exclude_terms'];
		}
	if(isset($_POST['style'])) {
		$shortcode_args['style'] = $_POST['style'];
	}

	echo d4events_draw_calendar($shortcode_args);

    die();
}


/********************************

Begin RSS Feed Junk

********************************/
add_action('init', 'd4events_rss');
function d4events_rss(){
    add_feed('events_rss', 'd4events_rss_func');
}

function d4events_rss_func() {
    include (dirname( __FILE__ ).'/rss.php');
}


add_filter('d4events_rss', 'd4events_rss_output');
function d4events_rss_output($event_data) {

	$start_date_meta = get_post_meta( $event_data['id'], 'd4events_start', true );
	$end_date_meta = get_post_meta( $event_data['id'], 'd4events_end', true );

	$output .= '
	<item>
		<title>'.get_the_title($event_data['id']).'</title>
		<link>'.get_the_permalink($event_data['id']).'</link>
		<pubDate>'.mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false).'</pubDate>
		<description><![CDATA['.apply_filters( 'the_excerpt_rss', get_the_excerpt($event_data['id'])).']]></description>
		<content:encoded><![CDATA['.apply_filters( 'the_content_feed', apply_filters( 'the_content', get_the_content($event_data['id'])), get_default_feed()).']]></content:encoded>
		<events:dates>
		    <events:date>
		    	<events:date_start>'.date('Y-m-d',$start_date_meta).'</events:date_start>
		    	<events:time_start>'.date('H:i:s',$start_date_meta).'</events:time_start>
		    	<events:date_end>'.date('Y-m-d',$end_date_meta).'</events:date_end>
		    	<events:time_end>'.date('H:i:s',$end_date_meta).'</events:time_end>
		    </events:date>		    
		</events:dates>		
	';

	rss_enclosure();

	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );

	$output .= '</item>';

	return $output;
}


/********************************

Begin Single Template

********************************/

//Load the single event template
function d4events_single_template($single_template) {
     global $post;

     if ($post->post_type == 'd4events') {

     	//check if a single template exists in the theme root, if not load the default one
     	if( file_exists(get_template_directory() . '/single-event.php') ) {
     		$single_template = get_template_directory() . '/single-event.php';

     	} else {
      		$single_template = dirname( __FILE__ ) . '/single-event.php';
      	}
     }  
     return $single_template;
}
add_filter( 'single_template', 'd4events_single_template' );


//Theme wrapper functions

add_action('d4events_theme_wrapper_start', 'd4events_standard_theme_wrapper_start', 10);
add_action('d4events_theme_wrapper_end', 'd4events_standard_theme_wrapper_end', 10);


function d4events_theme_wrapper_start() {
	do_action('d4events_theme_wrapper_start');
}

function d4events_theme_wrapper_end() {
	do_action('d4events_theme_wrapper_end');
}



/*Default theme wrapper, matches Skivvy. Replace with your own using the following in your theme functions file:

remove_action('d4events_theme_wrapper_start', 'd4events_standard_theme_wrapper_start', 10);
remove_action('d4events_theme_wrapper_end', 'd4events_standard_theme_wrapper_end', 10);

add_action('d4events_theme_wrapper_start', 'my_theme_wrapper_start', 10);
add_action('d4events_theme_wrapper_end', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<section id="main">';
}

function my_theme_wrapper_end() {
  echo '</section>';
}

*/

function d4events_standard_theme_wrapper_start($wrapper_start) {
	get_template_part( 'inc/chunk' , 'title' );
  	echo '<section id="content"><div class="page-wrapper"><main id="main-content" class="clearfix" role="main">';
}

function d4events_standard_theme_wrapper_end($wrapper_end) {
	echo '</div></div></section>';
}