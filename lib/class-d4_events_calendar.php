<?php

class d4_events_calendar extends d4_events {
	/*
	----- construct the events object as a calendar
	*/
		public function __construct($atts) {			
			$this->set_store_empty_dates(true);
			$this->set_year($atts['year']);
			$this->set_month($atts['month']);
			parent::__construct($atts);			
		}
	/*
	----- manipulate query for calendar-based output (range between first and last of month, no maximum)
	*/

		public function set_range() {
			//set the date ranges for the events object.
			$first_of_month = strtotime($this->year.'-'.$this->month.'-01');

			$this->range = 'month';
			$this->range_start = new DateTime(date("Y-m-01", $first_of_month));
			$this->range_end = new DateTime(date("Y-m-t", $first_of_month));
			//bump the range into the last second of the last day of the month

			$this->range_end->setTime(23,59,59);		
		}

	/*
	----- render html elements
	*/

		public function render() {
			//outputs the calendar html, wrapper included

			$calendar .= '<div data-month="'.$this->get_month().'" data-year="'.$this->get_year().'" data-terms="'.$this->terms.'" data-taxonomy="'.$this->taxonomy.'" data-tax_field="'.$this->tax_field.'" data-exclude_terms="'.$this->exclude_terms.'" data-links="'.$this->links.'" class="d4-event-calendar"><div class="cal-change-button cal-prev" data-change="cal-prev"></div><div class="cal-change-button cal-next" data-change="cal-next"></div><h2>'.$this->get_month_name().' '.$this->get_year().'</h2><table cellpadding="0" cellspacing="0" class="calendar">';

			/* table headings */
			$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

			/* row for week one */
			$calendar .= '<tr class="calendar-row">';

			/* print "blank" days until the first of the current week */
			$running_day = $this->range_start->format('w');
			//$days_in_this_week = 1;

			for($x = 0; $x < $running_day; $x++):
				$calendar .= '<td class="calendar-day-np"> </td>';
				//$days_in_this_week++;
			endfor;		

			foreach($this->events_data as $single_day_events) {

				$day_number = $single_day_events->get_calendar_day();
				$calendar_timestamp = $single_day_events->get_timestamp();

				$calendar .= '<td class="calendar-day '.$has_events.'"><div class="day-internal">';
				$calendar .= '<div class="day-number">'.$day_number.'</div>';

				foreach($single_day_events->day_events as $single_event) {
					$calendar .= $this->render_single_event($single_event,$calendar_timestamp);
				}

				$calendar .= '</td>';

				//$days_in_this_week++;
				if($single_day_events->date_time->format('w') == 6) {
					$calendar .= '</tr><tr class="calendar-row">';
					//$days_in_this_week = 1;
				}			
			}

			$calendar .= '</tr></table>';

			if(empty($this->events_data)) {
				$calendar .= 'There are no events this month';
			}

			return $calendar;
		}


		public function render_single_event($event,$calendar_timestamp) {
			//outputs the single event html for a calendar

			$link_open = '';
			$link_close = '';
			$readmore = '';
			$ID = $event->ID;
			
			//custom output support, places all event IDs, dates, and files in an array for custom output
			if(has_filter($this->output_filter)) {
				$event_data = array(
					'id'				=> $ID,
					'date'				=> $calendar_timestamp,
					'shortcode_args'	=> $shortcode_args,
				);
				$event_content = apply_filters($shortcode_args['output_filter'], $event_data);
				return $event_content;
			}

			if ($this->links == 'true') {
				$link_open = '<a href="'.get_the_permalink($ID).'?date='.$calendar_timestamp.'">';	
				$link_close = '</a>';
				$readmore = '<a class="events_list-readmore" href="'.get_the_permalink().'?date='.$calendar_timestamp.'">Read More</a>';
			}
					
			$event_content .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
			$event_content .= '<h5 class="cal-event-title">'.$link_open.'<span>'.$event->title.'</span>'.$link_close;
			$event_content .= '<div class="clearfix"></div></div>';

			return $event_content;

		}
}