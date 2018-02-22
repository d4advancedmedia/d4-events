<?php

function d4events_get_events2($range_start,$range_stop,$number,$category,$exclude_category,$range) {

	if ($category != '') {
		$event_cats_array = array(
			'taxonomy' 	=> 'd4events_category',
			'field'    	=> 'name',
			'terms'    	=> $category,
		);
	}
	if ($exclude_category != '') {
		$event_exclude_cats_array = array(
			'taxonomy' 	=> 'd4events_category',
			'field'    	=> 'term_id',
			'terms'    	=> $exclude_category,
			'operator'	=> 'NOT IN',
		);
	}

	$tax_query = array(
		'relation' 		=> 'AND',
		$event_cats_array,
		$event_exclude_cats_array,
	);

	if($range_start > $range_stop) {
		$range_array = array($range_stop,$range_start);
	} else {
		$range_array = array($range_start,$range_stop);
	}

	//dont process repeats for all+list shorties, which have the following start/stop values. range not needed either
	if( ($range_start == '01/01/1800') && ($range_stop == '01/01/2100') ) {
		$meta_query = '';
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

	}

	$events_args = array (
		'post_type' 	=> 'd4events',
		'tax_query'		=>  $tax_query,
		'posts_per_page'=>	-1,
		'meta_query'	=> array($meta_query),
		'orderby'		=> 'meta_value_num',
		'order'			=> 'DESC'
		//'meta_key'		=> 'd4events_start',
	);

	$events_query = new WP_Query($events_args);

	//Re-sort the results by the time of day to ensure order for each day
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

function d4events_render_single($style,$calendar_date,$files,$output_filter) {

	$link_open = '';
	$link_close = '';
	$readmore = '';
	$ID = get_the_ID();

	//custom output support, places all event IDs and dates in an array for custom output
	echo $output_filter;
	if(has_filter($output_filter)) {
		$event_data = array(
			'id'	=> $ID,
			'date'	=> $calendar_date,
		);
		$event_content = apply_filters($output_filter, $event_data);
		return $event_content;
	}

	#if ($links == 'true') {
		$link_open = '<a href="'.get_the_permalink().'?date='.$calendar_date.'">';	
		$link_close = '</a>';
		$readmore = '<a class="events_list-readmore" href="'.get_the_permalink().'?date='.$calendar_date.'">Read More</a>';
	#}

	if($style == 'calendar') {
		//Render output
			
		$event_content .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
		$event_content .= '<h5 class="cal-event-title">'.$link_open.'<span>'.get_the_title().'</span>'.$link_close;
		$event_content .= '<div class="clearfix"></div></div>';
	}

	elseif($style == 'agenda') {	

		$datetime_array = d4events_fetch_datetime($ID);

		$event_content .= '<tr class="agenda-single-event"><td class="agenda-time-column">'.$datetime_array['d4events_start_time'].'</td>';
		$event_content .= '<td class="agenda-title-column">'.$link_open.'<span>'.get_the_title().'</span>'.$link_close.'</td></tr>';
		
	}

	elseif($style == 'list') {

		$datetime_array = d4events_fetch_datetime($ID);

		#$event_content = '<div class="events_list-single>'

		$post_thumbnail = '';
		if (has_post_thumbnail()) {
			$post_thumbnail = '<div class="events_list-thumb">'.$link_open.get_the_post_thumbnail($ID,$thumbnail_size).$link_close.'</div>';
			$has_image = ' event-has-image';
		}

		#$content_length = intval($content_length);
		$file_cluster = d4events_get_files($files);
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
			$file_class = 'fileclass_'.pathinfo($meta[1])['extension'];
			$file_link = '<a href="'.$meta[1].'" class="events_files '.$file_class.'" target="_blank">'.$file_name.'</a>';
			
			$file_array[] = array(
					'type' => $file_type,
					'name' => $file_name,
					'link' => $file_link,
			);
		}
		
	}

	//This is the array of file categories listed in the shortcode, so that the files can be sorted by category (ex: Agenda, Meeting, Minutes)
	$shortcode_filecats = explode(',',$files);

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

//Processes events to see if they fall on a given date, including repeating events
function d4events_process_events($event_date,$category,$events_query,$style,$range,$lastdate,$files,$output_filter) {
	
	$calendar_date = strtotime($event_date);

	$day_of_the_week = date('l', $calendar_date);
	$day_of_the_month = date('j', $calendar_date);
	$nth_weekday_of_every_month = ceil($day_of_the_month / 7);

	$i=0;
	$output['content'] = '';

	while ( $events_query->have_posts() ) { $events_query->the_post();

		$event_id = get_the_id();
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

		if ( ($range == 'past') && ($repeat_starttime > strtotime('now')) ) {
			//event is in the future, range is in the past, exit.
			return;
		} elseif ( ($range == 'future') && ($repeat_starttime < $current_time) ) {
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

		#echo $calendar_date.'</br>';
		#echo $start_timestamp.'</br></br></br>';

		if ( ($range == 'all') && ($style == 'list') ) {}
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
		
		if ($style == 'list') {
			if (	( $calendar_date == strtotime($datetime_array['d4events_start_date']) ) || ($repeating_event == true)	) {

				//Render output
				
				$output['content'] .= d4events_render_single($style,$calendar_date,$files,$output_filter);
				$i++;

			}
		}

		elseif (	( ($calendar_date >= strtotime($datetime_array['d4events_start_date'])) && ($calendar_date <= $end_timestamp)) || ($repeating_event == true)	) {

			//Render output
			
			$output['content'] .= d4events_render_single($style,$calendar_date,$files);
			$i++;

		}
	}
	$output['total'] = $i;
	return $output;
	wp_reset_postdata();
}

//draws a calendar
function d4events_draw_calendar($output_filter,$month,$year,$category,$exclude_category,$style,$range,$files){
	
	if ( ($style == 'list') || ($style == 'agenda') ) {
		$repeat = 12;
	} else {
		$repeat = 1;
	}

	if ($month == '') {
		$month = date("n");
	}
	if ($year == '') {
		$year = date("Y");
	}

	$current_time = strtotime('now');

	if ( ($range != 'future') && ($range !='past') && ($range != 'all') ) {
		//a date was supplied for the range variable, which only occurs when the "loadmore" button is clicked. This value is the last date of the previous agenda/list group
		$lastdate = $range;
		if ($lastdate >= $current_time) {
			$range = 'future';
		} else {
			$range = 'past';
			$current_time = $lastdate;
		}
	}

	

	$prev_count = 0;
	$success_count = 0;

	if ($range == 'all' && $style == 'list') {
		$range_start = '01/01/1800';
		$range_stop = '01/01/2100';

		$events_query = d4events_get_events2($range_start,$range_stop,$number,$category,$exclude_category,$range);
		usort($events_query->posts, 'd4events_sort_by_start_time');

		while ( $events_query->have_posts() ) { $events_query->the_post();
			$calendar_date = get_post_meta(get_the_ID(),'d4events_start',true);
			$list_events .= d4events_render_single($style,$calendar_date,$files);
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
					$month = $nextmonth;
				}

				if ($nextyear != '') {			
					$year = $nextyear;
				}	
			
				if ($range == 'past') {
					$dateObj   = DateTime::createFromFormat('m/d/Y', date('m/d/Y',$current_time));
					$monthName = $dateObj->format('F'); // March

					$range_start = strtotime('01/01/1800');
					$range_stop = $current_time;
				}

				elseif ($range == 'future') {
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

					$dateObj   = DateTime::createFromFormat('m/d/Y', $month.'/1/'.$year);
					$monthName = $dateObj->format('F'); // March

					#$range_start = strtotime($dateObj->format('m/d/Y'));
					$dateObj->modify('next month');
					#$range_stop = strtotime($dateObj->format('m/d/Y'));

					$range_start = strtotime('01/01/1800');
					$range_stop = strtotime('01/01/2100');
				}
				
				/* draw table */
				$calendar .= '<div data-month="'.$month.'" data-year="'.$year.'" data-category="'.$category.'" data-exclude_category="'.$exclude_category.'" class="d4-event-calendar"><div class="cal-change-button cal-prev" data-change="cal-prev"></div><div class="cal-change-button cal-next" data-change="cal-next"></div><h2>'.$monthName.' '.$year.'</h2><table cellpadding="0" cellspacing="0" class="calendar">';

				/* table headings */
				$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

				$agenda_headings = array('Day','Date','Time','Event');
				#$agenda .= '<tr class="agenda-header-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$agenda_headings).'</td></tr>';

				/* days and weeks vars now ... */
				$running_day = date('w',mktime(0,0,0,$month,1,$year));
				$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
				$days_in_this_week = 1;
				$day_counter = 0;
				$dates_array = array();

				# get all events, place in array to send to d4events_get_events()

				$events_query = d4events_get_events2($range_start,$range_stop,$number,$category,$exclude_category,$range);
				#var_dump($events_query);

				#echo 'start:'.date('m/d/Y',$range_start);
				#echo 'stop:'.date('m/d/Y',$range_stop);

				/* row for week one */
				$calendar.= '<tr class="calendar-row">';

				/* print "blank" days until the first of the current week */
				for($x = 0; $x < $running_day; $x++):
					$calendar.= '<td class="calendar-day-np"> </td>';
					$days_in_this_week++;
				endfor;

				$month_has_events = false;

				if($range == 'past') {
				
					/* keep going with days.... */
					for($list_day = 31; $list_day >= 1; $list_day--):
						
						if (strlen($list_day) < 2) {
							$fixed_day = '0'.$list_day;	
						} else {
							$fixed_day = $list_day;
						}
						if (strlen($month) < 2) {
							$fixed_month = '0'.$month;	
						} else {
							$fixed_month = $month;
						}
						$fulldate = $fixed_month.'/'.$fixed_day.'/'.$year;
						#$calendar .= $fulldate;
						$processed_events = d4events_process_events($fulldate,$category,$events_query,$style,$range,$lastdate,$files);

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
			
						if($style == 'calendar') {
							$calendar .= $day_events;
						}

						//return single event content if under the success count limiter
						elseif ( ($success_count <= 15) && ( ($style == 'list') || ($style == 'agenda') ) ) {
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
						if (strlen($month) < 2) {
							$fixed_month = '0'.$month;	
						} else {
							$fixed_month = $month;
						}
						$fulldate = $fixed_month.'/'.$fixed_day.'/'.$year;
						#$calendar .= $fulldate;
						$processed_events = d4events_process_events($fulldate,$category,$events_query,$style,$range,$lastdate,$files,$output_filter);

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
			
						if($style == 'calendar') {
							$calendar .= $day_events;
						}

						//return single event content if under the success count limiter
						elseif ( ($success_count <= 15) && ( ($style == 'list') || ($style == 'agenda') ) ) {
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

				if ( ($style == 'list') || ($style == 'agenda') ) {

					if($range == 'past') {
						$nextmonth = $month-'1';
					} else {
						$nextmonth = $month+'1';
					}
					
					if ($nextmonth == '13') {
							$nextmonth ='1';
							$nextyear = $year+'1';
					}

					elseif ($nextmonth == '0') {
							$nextmonth ='12';
							$nextyear = $year-'1';
					}

					else $nextyear = $year;	
				}
			}
		endfor;
		
		/* all done, return result */
	}

	if ($style == 'calendar') {
		return $calendar;
	}

	elseif ($style == 'agenda') {
		return $agenda;
	}

	elseif ($style == 'list') {
		return $list_events;
	}
	
}


add_action( 'wp_ajax_cal_change', 'd4events_ajax_cal_change' );
add_action( 'wp_ajax_nopriv_cal_change', 'd4events_ajax_cal_change' );


function d4events_ajax_cal_change() {
    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['month']))
		{
		    $month = $_POST['month'];
		}
	if(isset($_POST['year']))
		{
		    $year = $_POST['year'];
		}
	if(isset($_POST['category']))
		{
		    $category = $_POST['category'];
		}
	if(isset($_POST['exclude_category']))
		{
		    $exclude_category = $_POST['exclude_category'];
		}	
	if(isset($_POST['change']))
		{
		    $change = $_POST['change'];		    
		}
	if ($change == "cal-prev") {
		$nextmonth = $month-'1';			
	}
	if ($change == "cal-next") {
		$nextmonth = $month+'1';	
	}
	if ($nextmonth == '13') {
			$nextmonth ='1';
			$nextyear = $year+'1';
	}
	elseif ($nextmonth == '0') {
			$nextmonth ='12';
			$nextyear = $year-'1';
	}
	else $nextyear = $year;		
    echo d4events_draw_calendar($nextmonth,$nextyear,$category,$exclude_category,'calendar','all',$files);
    die();
}

add_action( 'wp_ajax_loadmore', 'd4events_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'd4events_ajax_loadmore' );


function d4events_ajax_loadmore() {
    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['lastdate'])) {
		$lastdate = $_POST['lastdate'];
	}
	if(isset($_POST['category'])) {
		$category = $_POST['category'];
	}
	if(isset($_POST['exclude_category'])) {
		$exclude_category = $_POST['exclude_category'];
	}
	if(isset($_POST['style'])) {
		$style = $_POST['style'];
	}

	echo d4events_draw_calendar($month,$year,$category,$exclude_category,$style,$lastdate,$files);

    die();
}


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