<?php
class d4_day_events {
	/*
	----- a single-day object containing dateTime information for that day and the an array of single events that occur on that day. also processes repeating events.
	*/

	public function __construct($date_time) {
		$this->date_time = $date_time;
		$this->day_events = array();
	}

	public function add_event($event) {
		$this->day_events[] = $event;
	}

	public function get_calendar_day() {
		return $this->date_time->format('j');
	}

	public function get_week_day_name() {
		return $this->date_time->format('l');
	}

	public function get_date() {
		return $this->date_time->format('n/j/Y');
	}

	public function get_timestamp() {
		return $this->date_time->getTimeStamp();
	}

	public function process_repeats($event_id,$current_loop_dt) {
		$calendar_date = $current_loop_dt;

		$day_of_the_week = date('l', $calendar_date);
		$day_of_the_month = date('j', $calendar_date);
		$nth_weekday_of_every_month = ceil($day_of_the_month / 7);

		$datetime_array = d4events_fetch_datetime($event_id);
		$start_timestamp = get_post_meta($event_id,'d4events_start',true);
		$end_timestamp = get_post_meta($event_id,'d4events_end',true);				
		$repeating = get_post_meta( $event_id, 'd4events_repeating', true );
		$repeat_end_timestamp = get_post_meta($event_id,'d4events_repeat_end_date',true);

		$repeating_event = false;

		if ( ($repeating != '') && ($calendar_date > $start_timestamp) && ($calendar_date <= $repeat_end_timestamp) ) {

			//create a new dateperiod object for the repeating event
			$datePeriod_begin = new DateTime( date('Y-m-d',$start_timestamp) );
			$datePeriod_end = new DateTime( date('Y-m-d',$end_timestamp) );
			$datePeriod_end = $datePeriod_end->modify( '+1 day' ); 

			$datePeriod_interval = new DateInterval('P1D');
			$datePeriod_daterange = new DatePeriod($datePeriod_begin, $datePeriod_interval ,$datePeriod_end);

			$weekly_repeat_days = get_post_meta( $event_id, 'd4events_repeat_days', true );
			
			if ($weekly_repeat_days) {
				if (in_array($day_of_the_week, $weekly_repeat_days)) {
					$repeating_event = true;
				}
			}

			else {
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

		return $repeating_event;
	}
}