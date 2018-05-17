<?php




// Processes events to see if they fall on a given date, including repeating events
function d4events_process_events( $event_date, $events_query, $lastdate, $shortcode_args ) {
	
	$calendar_date = strtotime($event_date);

	$day_of_the_week = date('l', $calendar_date);
	$day_of_the_month = date('j', $calendar_date);
	$nth_weekday_of_every_month = ceil($day_of_the_month / 7);

	$i=0;
	$output['content'] = '';

	while ( $events_query->have_posts() ) {

		$events_query->the_post();

		$event_id = get_the_id();

		$blackoutdates = get_post_meta($event_id,'d4events_blackout_dates',true);

		if ($blackoutdates == '') {
			$blackoutdates = array();
		}		

		if ( ! in_array( date('m/d/Y', $calendar_date) , $blackoutdates ) ) {

			$datetime_array = d4events_fetch_datetime($event_id);
			$start_timestamp = get_post_meta($event_id,'d4events_start',true);
			$end_timestamp = get_post_meta($event_id,'d4events_end',true);
			$repeat_end_timestamp = get_post_meta($event_id,'d4events_repeat_end_date',true);

			
			$current_time = strtotime('now');

			//if the last date is greater than the current date, then you are loading more events and the cuttoff date needs to be moved into the future
			if ( $lastdate >= $current_time ) {
				$current_time = $lastdate;
			}

			//determine the true start/end times of the current repeating event
			$repeat_starttime = $calendar_date + ($start_timestamp - strtotime($datetime_array['d4events_start_date']));
			$repeat_endtime = $calendar_date + ($end_timestamp - $start_timestamp);

			if ( ($shortcode_args['range'] == 'past') && ($repeat_starttime > $current_time) ) {
				//event is in the future, range is in the past, exit.
				return;
			} elseif ( ($shortcode_args['range'] == 'future') && ($repeat_starttime < $current_time) ) {
				//event is in the future, range is in the past, exit.
				return;
			}

			$event_duration = date('j', strtotime($end_timestamp)) - date('j', strtotime($start_timestamp));

			$repeating = get_post_meta( $event_id, 'd4events_repeating', true );
			$repeating_event = false;

			if ( $shortcode_args['range'] == 'all' && $shortcode_args['style'] == 'list' ) {

			} else {

				if ( $repeating != '' && $calendar_date > $start_timestamp && $calendar_date <= $repeat_end_timestamp ) {

					//create a new dateperiod object for the repeating event
					$datePeriod_begin = new DateTime( date('Y-m-d',strtotime($start_timestamp)) );
					$datePeriod_end = new DateTime( date('Y-m-d',strtotime($end_timestamp)) );
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

					} elseif ( ($repeat_interval <= $day_of_the_month) && ($day_of_the_month <= $end_day_of_the_month) ) {
							$repeating_event = true;
					}
				}
			}
			
			if ( $shortcode_args['style'] == 'list' ) {

				if (	( $calendar_date == strtotime($datetime_array['d4events_start_date']) ) || ($repeating_event == true)	) {

					//Render output				
					$output['content'] .= d4events_render_single( $shortcode_args, $calendar_date );
					$i++;

				}

			} elseif (	( ($calendar_date >= strtotime($datetime_array['d4events_start_date'])) && ($calendar_date <= $end_timestamp)) || ($repeating_event == true)	) {

				//Render output			
				$output['content'] .= d4events_render_single( $shortcode_args, $calendar_date );
				$i++;

			}
		}
	} wp_reset_postdata();
	$output['total'] = $i;
	return $output;
	

}

