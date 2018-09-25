<?php

class d4_events_agenda extends d4_events {
	/*
	----- construct the events object as an agenda
	*/

		public function __construct($atts) {
			$this->range = 'future';		
			$this->set_event_limit(10);
			$this->set_loop_limit(700);
			$this->set_store_empty_dates(false);
			parent::__construct($atts);
		}


	/*
	----- manipulate query for agenda-based output: All events in the future


		public function set_range() {
			//set the date ranges for the events object.
			$current_datetime = strtotime('now');

			$this->range = 'future';
			$this->range_start = new DateTime( date('Y-m-d',$current_datetime) );
			$this->range_end = new DateTime( '2035-01-01' );	
		}
	*/

	/*
	----- render html elements
	*/

		public function render() {
			//outputs the calendar html, wrapper included

			$agenda = '<table cellpadding="0" cellspacing="0" class="calendar">';			

			/* table headings */
			$headings = array('Day','Date','Time','Event');
			$agenda .= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

			/* row for week one */
			$agenda .= '<tr class="calendar-row">';

			/* print "blank" days until the first of the current week */
			$running_day = $this->range_start->format('w');
			//$days_in_this_week = 1;

			/*for($x = 0; $x < $running_day; $x++):
				$agenda .= '<td class="calendar-day-np"> </td>';
				//$days_in_this_week++;
			endfor;*/

			foreach($this->events_data as $single_day_events) {

				$day_number = $single_day_events->get_calendar_day();
				$calendar_timestamp = $single_day_events->get_timestamp();

				if (!empty($single_day_events->day_events)) {
					$agenda .= '<tr class="agenda-day-row '.$has_events.'" data-event_date="'.$single_day_events->get_timestamp().'"><td class="agenda-day-column">'.$single_day_events->get_week_day_name().'</td><td class="agenda-date-column">'.$single_day_events->get_date().'</td>';
					$agenda .= '<td><table>';

					foreach($single_day_events->day_events as $single_event) {
						$agenda .= $this->render_single_event($single_event,$calendar_timestamp);
					}

					$agenda .='</table></td></tr>';
				}		
			}

			$agenda .= '</table>';
			$agenda .= '<a class="d4events-loadmore">Load More</a>';

			return $agenda;
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

			$datetime_array = d4events_fetch_datetime($ID);

			$event_content .= '<tr class="agenda-single-event"><td class="agenda-time-column">'.$datetime_array['d4events_start_time'].'</td>';
			$event_content .= '<td class="agenda-title-column">'.$link_open.'<span>'.get_the_title($ID).'</span>'.$link_close.'</td></tr>';

			return $event_content;

		}
}