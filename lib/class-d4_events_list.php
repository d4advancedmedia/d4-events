<?php

class d4_events_list extends d4_events {
	/*
	----- construct the events object as an list
	*/

		public function __construct($atts) {			
			$this->set_event_limit(10);
			$this->set_loop_limit(700);
			$this->set_store_empty_dates(false);
			parent::__construct($atts);		
		}
		

	/*
	----- render html elements
	*/

		public function render() {
			//outputs the calendar html, wrapper included

			//multiday hidden events are those events should only be shown once
			$multiday_hidden_events = array();

			foreach($this->events_data as $single_day_events) {

				$day_number = $single_day_events->get_calendar_day();
				$calendar_timestamp = $single_day_events->get_timestamp();

				if (!empty($single_day_events->day_events)) {
					
					foreach($single_day_events->day_events as $single_event) {
						$event_ids[] = $single_event->ID;	
					}
					$i = 0;
					foreach($multiday_hidden_events as $multiday_hidden_event) {
						if(!in_array($multiday_hidden_event,$event_ids)) {
							unset($multiday_hidden_events[$i]);
						}
						$i++;
					}

					foreach($single_day_events->day_events as $single_event) {												

						if(!in_array($single_event->ID,$multiday_hidden_events)) {
							$list .= $this->render_single_event($single_event,$calendar_timestamp);
						}

						//if its a multi-day event, add it to the list of events to skip. we only want to show it once per series.
						if($single_event->multiday_event) {
							$multiday_hidden_events[] = $single_event->ID;
						}
					}
				}		
			}

			$list .= '<a class="d4events-loadmore">Load More</a>';

			return $list;
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
				$readmore = '<a class="events_list-readmore" href="'.get_the_permalink($ID).'?date='.$calendar_timestamp.'">Read More</a>';
			}				

			$datetime_array = d4events_fetch_datetime($ID);

			$post_thumbnail = '';
			if (has_post_thumbnail($ID)) {
				$post_thumbnail = '<div class="events_list-thumb">'.$link_open.get_the_post_thumbnail($ID,$thumbnail_size).$link_close.'</div>';
				$has_image = ' event-has-image';
			}

			#$content_length = intval($content_length);

			$file_cluster = $event->output_files($this->file_list);

			$content_length = 255;
			$post_content = strip_tags($event->content);
			if (strlen($post_content) > $content_length) {
				$post_content_modified = preg_replace('/\s+?(\S+)?$/', '', substr(wpautop(do_shortcode($post_content)), 0, $content_length)).' […]';
			} else {
				$post_content_modified = $post_content;
			}

			$events_date_elements .= '<span class="events_list-date">'.date('m/d/Y',$calendar_timestamp).'</span>';
			$events_date_elements .= '<span class="events_list-time">'.$datetime_array['d4events_start_time'].'</span>';
			if($event->multiday_event) {
				$multiday_end_date = new DateTime(date('m/d/Y',$calendar_timestamp));
				$multiday_end_date->add(new DateInterval('P'.$event->multiday_event.'D'));
				$events_date_elements .= ' - <span class="events_list-date">'.$multiday_end_date->format('m/d/Y').'</span>';
				$events_date_elements .= '<span class="events_list-time">'.$datetime_array['d4events_end_time'].'</span>';
			}

			$repeating = get_post_meta( $ID, 'd4events_repeating', true );

			$event_content .= '<div class="events_list-single'.$has_image.'" data-event_date="'.$calendar_timestamp.'" data-event_id="'.$ID.'" data-event_repeating="'.$repeating.'">';
			$event_content .= $post_thumbnail;	
			$event_content .= '<h5 class="cal-event-title">'.$link_open.'<span>'.get_the_title($ID).'</span>'.$link_close.'<span class="events_list-datetime">'.$events_date_elements.'</span></h5>';
			$event_content .= '<div class="events_list-content"><div class="events_list-description">'.$post_content_modified.'</div>';
			$event_content .= $readmore.'<div class="clearfix"></div>';
			$event_content .= $file_cluster;
			$event_content .= '<div class="clearfix"></div></div></div>';

			return $event_content;

		}
}