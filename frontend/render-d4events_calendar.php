<?php



function d4events_draw_calendar( $shortcode_args ){
	
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

	if ( $shortcode_args['range'] != 'future' && $shortcode_args['range'] != 'past' && $shortcode_args['range'] != 'all' ) {
		//a date was supplied for the range variable, which only occurs when the "loadmore" button is clicked. This value is the last date of the previous agenda/list group
		$lastdate = $shortcode_args['range'];

		if ( $lastdate >= $current_time ) {
			$shortcode_args['range'] = 'future';
		} else {
			$shortcode_args['range'] = 'past';
			$current_time = $lastdate;
		}

	}

	if ( $shortcode_args['range'] == 'all' && $shortcode_args['style'] == 'list' ) {

		$list_events = '';

		$fetch_args = array(
			'range_start'    => '01/01/1800',
			'range_stop'     => '01/01/2100',
			'shortcode_args' => $shortcode_args,
		);
		$events_query = fetch_d4events($fetch_args);
		# $events_query = d4events_get_events2($range_start,$range_stop,$shortcode_args);
		while ( $events_query->have_posts() ) {

			$events_query->the_post();

			$calendar_date = get_post_meta(get_the_ID(),'d4events_start',true);
			$list_events .= d4events_render_single($shortcode_args, $calendar_date);
		
		} wp_reset_postdata();

	} else {

		$prev_count = 0;
		$success_count = 0;

		// Repeat the calendar function: up to 12 times for an agenda/list or until 15 dates are returned including repeats, or once for a calendar to show a single month.
			// This makes the tool very resource intensive and will be replaced with a more efficient funciton in a later release.
		
		for( $c = 0; $c < $repeat; $c++ ):

			//this variable only increments when a date is matched, capping the total number of returned events in the case of an agenda/list (needed for repeats)
			if ($success_count <= 15) {

			
				//when the loop completes in the case of an agenda/list, the next month will be set allowing the loop to run again for the next calendar month
				if ($nextmonth != '') {			
					$shortcode_args['month'] = $nextmonth;
				}

				if ($nextyear != '') {			
					$shortcode_args['year'] = $nextyear;
				}	
			
				if ( $shortcode_args['range'] == 'past' ) {

					$dateObj   = DateTime::createFromFormat('m/d/Y', date('m/d/Y',strtotime($current_time)));
					$monthName = $dateObj->format('F'); // March

					$range_start = strtotime('01/01/1800');
					$range_stop = $current_time;

				} elseif ( $shortcode_args['range'] == 'future' ) {

					if ($lastdate != '') {
						$list_start_date = $lastdate;
					} else {
						$list_start_date = $current_time;
					}

					$dateObj   = DateTime::createFromFormat('m/d/Y', date('m/d/Y',strtotime($list_start_date)));
					
					if ($lastdate != '') {
						$dateObj->modify('+1 day');
						$current_time = strtotime($dateObj->format('m/d/Y'));
					}
					$monthName = $dateObj->format('F'); // March

					$range_start = $current_time;
					$range_stop = strtotime('01/01/2100');

				} else {

					$dateObj   = date_create_from_format('m/d/Y', $shortcode_args['month'].'/1/'.$shortcode_args['year']);
					$monthName = date_format($dateObj, 'F' ); // March

					#$range_start = strtotime($dateObj->format('m/d/Y'));
					$dateObj->modify('next month');
					#$range_stop = strtotime($dateObj->format('m/d/Y'));

					$range_start = strtotime('01/01/1800');
					$range_stop = strtotime('01/01/2100');
				}
				
				// draw table
					$calendar .= '<div class="d4-event-calendar"';
						calendar .= ' data-month="'.$shortcode_args['month'].'"';
						calendar .= ' data-year="'.$shortcode_args['year'].'"';
						calendar .= ' data-terms="'.$shortcode_args['terms'].'"';
						calendar .= ' data-taxonomy="'.$shortcode_args['taxonomy'].'"';
						calendar .= ' data-tax_field="'.$shortcode_args['tax_field'].'"';
						calendar .= ' data-exclude_terms="'.$shortcode_args['exclude_terms'].'"';
					calendar .= '>';
					calendar .= '<div class="cal-change-button cal-prev" data-change="cal-prev"></div>';
					calendar .= '<div class="cal-change-button cal-next" data-change="cal-next"></div>';
					calendar .= '<h2>';
						calendar .= $monthName;
						calendar .= ' ';
						calendar .= $shortcode_args['year'];
					calendar .= '</h2>';
					calendar .= '<table cellpadding="0" cellspacing="0" class="calendar">';

				// table headings
					$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

					$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">';
						calendar .= implode('</td><td class="calendar-day-head">',$headings);
						calendar .= '</td>';
					calendar .= '</tr>';

					$agenda_headings = array('Day','Date','Time','Event');
					
				// days and weeks vars now ...
					$running_day = date('w',mktime(0,0,0,$shortcode_args['month'],1,$shortcode_args['year']));
					$days_in_month = date('t',mktime(0,0,0,$shortcode_args['month'],1,$shortcode_args['year']));
					$days_in_this_week = 1;
					$day_counter = 0;
					$dates_array = array();

				// get all events, place in array to send to d4events_get_events()
					$fetch_args = array(
						'range_start'    => $range_start,
						'range_stop'     => $range_stop,
						'shortcode_args' => $shortcode_args,
					);

					$events_query = fetch_d4events($fetch_args); 

				// row for week one
					$calendar.= '<tr class="calendar-row">';

				// print "blank" days until the first of the current week
					for( $x = 0; $x < $running_day; $x++ ) {
						$calendar.= '<td class="calendar-day-np"> </td>';
						$days_in_this_week++;
					}

				$month_has_events = false;

				if ( $shortcode_args['range'] == 'past' ) {
				
					/* keep going with days.... */
					for($list_day = 31; $list_day >= 1; $list_day--):
						
						if ( strlen($list_day) < 2 ) {
							$fixed_day = '0'.$list_day;	
						} else {
							$fixed_day = $list_day;
						}

						if ( strlen($shortcode_args['month']) < 2 ) {
							$fixed_month = '0'.$shortcode_args['month'];	
						} else {
							$fixed_month = $shortcode_args['month'];
						}

						$fulldate = $fixed_month.'/'.$fixed_day.'/'.$shortcode_args['year'];

						$processed_events = d4events_process_events($fulldate,$events_query,$lastdate,$shortcode_args);

						$day_events = $processed_events['content'];

						if ( ! empty($day_events) ) {
							$has_events = ' has-events';
							$month_has_events = true;

							$success_count += intval($processed_events['total']);
						}

						$calendar.= '<td class="calendar-day ' . $has_events . '">';
						$calendar.= '<div class="day-internal">';

						unset($has_events);
						
						// add in the day number
							$calendar.= '<div class="day-number">'.$list_day.'</div>';
				
							if ( $shortcode_args['style'] == 'calendar' ) {

								$calendar .= $day_events;

						//return single event content if under the success count limiter
							} elseif ( ($success_count <= 15) && ( ($shortcode_args['style'] == 'list') || ($shortcode_args['style'] == 'agenda') ) ) {
								$list_events .= $day_events;
							}

						$calendar.= '</div></td>';

						if ( $running_day == 6 ) {

							$calendar.= '</tr>';

							if ( ($day_counter + 1) != $days_in_month ) {
								$calendar.= '<tr class="calendar-row">';
							}
							$running_day = -1;
							$days_in_this_week = 0;
						}

						$days_in_this_week++;
						$running_day++;
						$day_counter++;

					endfor;

				} else {

					for ( $list_day = 1; $list_day <= $days_in_month; $list_day++ ) :
						
						if ( strlen($list_day) < 2 ) {
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

						if ( ! empty($day_events) ) {
							$has_events = ' has-events';
							$month_has_events = true;
							$agenda .= '<tr class="agenda-day-row '.$has_events.'" data-event_date="'.strtotime($fulldate).'"><td class="agenda-day-column">'.date('l',strtotime($fulldate)).'</td><td class="agenda-date-column">'.date('M d',strtotime($fulldate)).'</td>';
							$agenda .= '<td><table>'.$day_events.'</table></td>';
							$success_count += intval($processed_events['total']);
						}

						$calendar.= '<td class="calendar-day '.$has_events.'"><div class="day-internal">';
						unset($has_events);

						// add in the day number */
							$calendar.= '<div class="day-number">' . $list_day . '</div>';
			
						//return single event content if under the success count limiter
							if ( $shortcode_args['style'] == 'calendar' ) {
								$calendar .= $day_events;
							} elseif ( $success_count <= 15 && ( $shortcode_args['style'] == 'list' || $shortcode_args['style'] == 'agenda' ) ) {
								$list_events .= $day_events;
							}

						$calendar.= '</div></td>';

						if ( $running_day == 6 ) {

							$calendar.= '</tr>';
							if ( ($day_counter + 1) != $days_in_month ) {
								$calendar.= '<tr class="calendar-row">';
							}
							$running_day = -1;
							$days_in_this_week = 0;
						}
						$days_in_this_week++; $running_day++; $day_counter++;
					endfor;

					//close the week row, agenda view
					#$agenda .= '</tr>';
				}

				// finish the rest of the days in the week
					if ( $days_in_this_week < 8 ) {

						for ( $x = 1; $x <= (8 - $days_in_this_week); $x++ ) {
							$calendar.= '<td class="calendar-day-np"> </td>';
						}

					}

			// Close Calendar
				$calendar.= '</tr>';
				$calendar.= '</table>';
				$calendar.= '</div>';

			// Display No Meeting Events Notice
				if ( $month_has_events == false ) {
					$calendar .= '<div class="no-events">';
						$calendar .= __('There are no scheduled events for this month', 'd4events')
					$calendar .= '</div>';
				}

			// 
				if ( $shortcode_args['style'] == 'list' || $shortcode_args['style'] == 'agenda' ) {

					if ( $shortcode_args['range'] == 'past' ) {
						$nextmonth = $shortcode_args['month'] - '1';
					} else {
						$nextmonth = $shortcode_args['month'] + '1';
					}
					
					if ( $nextmonth == '13' ) {
						$nextmonth ='1';
						$nextyear = $shortcode_args['year'] + '1';
					} elseif ( $nextmonth == '0' ) {
						$nextmonth ='12';
						$nextyear = $shortcode_args['year'] - '1';
					} else {
						$nextyear = $shortcode_args['year'];
					}

				}
			}

		endfor;
		
		// all done, return result
	}


	switch ( $shortcode_args['style'] ) {

		case 'calendar':
			return $calendar;
			break;

		case 'agenda':
			return $agenda;
			break;

		case 'list':
			return $list_events;
			break;
		
	}

}
