<?php
class d4_events {
	/*
	----- construct the events object
	*/
	
		public function __construct($atts) {
			
			//define settings

				$this->set_style($atts['style']);				
				$this->set_links($atts['links']);
				$this->set_file_list($atts['files']);	
				$this->set_loadmore($atts['loadmore']);
				$this->set_event_limit($atts['number']);
				$this->set_output_filter($atts['output_filter']);								
			
			//define date ranges
				
				$this->range = $atts['range'];
				$this->set_range($atts['range']);

				if($atts['range_start']) {
					//manually define a range start
					$this->set_range_start($atts['range_start']);
				}

				if($atts['range_end']) {
					//manually define a range end
					$this->set_range_end($atts['range_end']);
				}							
			
			//define taxonomies

				//Add legacy support for category/exclude_category atts, assign them to terms/exclude_terms
				if ($atts['category'] != '') {
					$atts['terms'] 		= $atts['category'];
					$atts['taxonomy']	= 'd4events_category';
					$atts['tax_field']	= 'name';				
				}
				if ($atts['exclude_category'] != '') {
					$atts['exclude_terms']	= $atts['exclude_category'];
					$atts['taxonomy']	= 'd4events_category';
					$atts['tax_field']	= 'name';
				}

				$this->set_taxonomy($atts['taxonomy']);
				$this->set_tax_field($atts['tax_field']);
				$this->set_terms($atts['terms']);
				$this->set_excluded_terms($atts['exclude_terms']);
				$this->set_excluded_ids($atts['excluded_ids']);				

			//define events query object

				$this->set_events();
			
			//search html

				$this->set_search_html($atts['search_html']);
						
			//admin debugging, comment the next line out to see some printed object data
				//$this->debug = true;			
		}

	/*
	----- define settings
	*/
		public function set_event_limit($event_limit) {
			//The total number of events that can be added to the object. Useful for preventing the function from running thousands of times if the range is larger than a month.
			$this->event_limit = $event_limit;
		}

		public function set_loop_limit($loop_limit) {
			//The total number of process_events loops that can be run. Useful for preventing the function from running thousands of times if the range is larger than a month.
			$this->loop_limit = $loop_limit;
		}

		public function set_store_empty_dates($bool) {
			//Whether or not to store empty dates in the object. If you want to render each day in the object, even if empty (calendars for example), set this to true. Agendas for example only need to show the dates that actually have events so in that case set it to false.
			$this->store_empty_dates = $bool;
		}

		public function set_file_list($files) {
			//The list of file categories declared in the shortcode
			$this->file_list = $files;
		}

		public function set_loadmore($loadmore) {
			//This will be set if the loadmore button is clicked
			$this->loadmore = $loadmore;
		}

		public function set_excluded_ids($excluded_ids) {
			//Related to "loadmore" functionality. An array of event ids for events that are non-repeating that have already been shown. These events are then omitted from any "loadmore" functionality as they only need to be displayed once.
			$this->excluded_ids = $excluded_ids;
		}

		public function set_output_filter($output_filter) {
			//this is a filter name that is used to customize the events output
			$this->output_filter = $output_filter;
		}

	/*
	----- define target dates
	*/

		public function set_year($year) {
			//set the year for the events object, if its empty (usually will be) use the current year.
			if (!$year) {
				$year = date("Y");
			}		
			$this->year = $year;
		}

		public function set_month($month) {
			//set the month for the events object, if its empty (usually will be) use the current month.
			if (!$month) {
				$month = date("n");
			}		
			$this->month = $month;
		}

		public function set_range() {
			//set the date ranges for the events object.
			$current_datetime = strtotime('now');

			if($this->range == 'all') {
				$this->range_start = new DateTime('1971-01-01');
				$this->range_end = new DateTime('2038-01-01');
			}
			elseif($this->range == 'past') {
				$this->range_start = new DateTime('1971-01-01');
				$this->range_end = new DateTime( date('Y-m-d',$current_datetime) );				
			}
			if($this->range == 'future') {
				$this->range_start = new DateTime( date('Y-m-d',$current_datetime) );
				$this->range_end = new DateTime( '2038-01-01' );
			}
		}

		public function set_range_start($range_start) {
			$dt = new DateTime();		
			$this->range_start = $dt->setTimestamp($range_start);
		}

		public function set_range_end($range_end) {
			$dt = new DateTime();		
			$this->range_end = $dt->setTimestamp($range_end);
		}

		public function get_year() {
			return $this->range_start->format('Y');
		}

		public function get_month() {
			return $this->range_start->format('n');
		}

		public function get_month_name() {
			return $this->range_start->format('F');
		}

	/*
	----- define taxonomies
	*/

		public function set_taxonomy($taxonomy) {
			//set taxonomy to be used for a custom tax query.	
			$this->taxonomy = $taxonomy;
		}

		public function set_tax_field($tax_field) {
			//Select taxonomy term by. Possible values are 'term_id', 'name', 'slug' or 'term_taxonomy_id'. Default value is 'term_id'.	
			$this->tax_field = $tax_field;
		}

		public function set_terms($terms) {
			//Taxonomy term(s).	Comma separated and converted to an array.
			if($terms) {
				$term_array = explode(',',$terms);
			}
			$this->terms = $term_array;
		}

		public function set_excluded_terms($excluded_terms) {
			//Excluded taxonomy term(s). Comma separated and converted to an array.
			if($excluded_terms) {
				$excluded_term_array = explode(',',$excluded_terms);
			}
			$this->excluded_terms = $excluded_term_array;
		}

	/*
	----- define options
	*/

		public function set_style($style) {
			//set whether links should be included in the html
			
			if($style == 'agenda') {
				$this->style = 'agenda';
			}

			elseif($style == 'list') {
				$this->style = 'list';
			}

			else {	
				$this->style = 'calendar';
			}
		}

		public function set_links($links) {
			//set whether links should be included in the html
			$this->links = $links;
		}

	/*
	----- define events query object
	*/

		public function set_events() {
		//creates the query to be used and sets a wp query object as a property in this object.
			
			//set tax query elements
			if ($this->terms) {
				$event_terms_array = array(
					'taxonomy' 	=> $this->taxonomy,
					'field'    	=> $this->tax_field,
					'terms'    	=> $this->terms,
				);
			}
			if ($this->excluded_terms) {
				$event_exclude_terms_array = array(
					'taxonomy' 	=> $this->taxonomy,
					'field'    	=> $this->tax_field,
					'terms'    	=> $this->excluded_terms,
					'operator'	=> 'NOT IN',
				);
			}
			if($this->terms || $this->excluded_terms) {
				$tax_query = array(
					'relation' 		=> 'AND',
					$event_terms_array,
					$event_exclude_terms_array,
				);
			}

			//set meta query elements
			$meta_query = array(
				'relation'		=>	'OR',
				'standard'		=>	array(
					'compare'		=>	'BETWEEN',
					'value'			=>	array($this->range_start->getTimestamp(),$this->range_end->getTimestamp()),
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
				'meta_key'		=> 'd4events_start',
				'orderby'		=> 'meta_value_num',
				'order'			=> 'ASC',
				'post__not_in'	=> $this->excluded_ids,
			);		

			$events_query = get_posts($events_args);

			//usort($events_query->posts, 'sort_by_start_time');

			$this->events_query = $events_query;

			if($this->debug) {
				print('<pre>');
				print_r($events_args);
				print('</pre>');

				print('<pre>');
				print_r($events_query);
				print('</pre>');
			}

		}

	/*
	----- process events and create an array of d4_events objects, sorting the data by date
	*/

		public function process_events() {			

			$interval = new DateInterval('P1D');

			if($this->range == 'all') {
				//if the range is set to all, limit the size of the date range to be scanned by getting the date of the last event date in the query and using that to define the start point rather than the original range set by the query
				$last_event = end($this->events_query);
				$last_datetime_array = d4events_fetch_datetime($last_event->ID);
				$last_event_start_date = strtotime($last_datetime_array['d4events_start_date']) + 9000/*add 9000s or 25 hours to make sure that the last event makes the cut*/;

				$this->set_range_end($last_event_start_date);					
			}

			$period = new DatePeriod($this->range_start, $interval, $this->range_end);		

			if( ($this->range == 'past') || ($this->range == 'all') ) {
				foreach ($period as $single_period) {
					$sorted_array[] = $single_period;
				}
				$sorted_array = array_reverse($sorted_array);
			}			

			else {
				$sorted_array = $period;
			}					

			$loop_count = 0;
			$event_count = 0;

			foreach($this->events_query as $single_event) {
				$this->need_to_be_added_ids[$single_event->ID] = $single_event->ID;
			}

			foreach ($sorted_array as $current_loop_dt) {

				if(empty($this->need_to_be_added_ids)) {
				//Use the list of "need_to_be_added_ids" to stop this loop once that list is empty (all events added no need to keep checking)										
					return;
				}

				$current_loop_timestamp = $current_loop_dt->getTimestamp();

				if($this->debug) {
					print('<pre>');
					print_r($current_loop_dt);
					print('</pre>');
				}

				//loop through each date in the date period (defined by range start and stop), adding events to that day if they are either explicitly occuring on the loop date or would occur on the date given their repeat pattern.

				if(($this->loadmore) && ($loop_count == 0)) {
					//When "loadmore" is clicked, the first item returned will also be the last item from the previous group of events. Skip it.
					$loop_count++;
					continue;
				}
			
				/*
				This was from the old version, doesn't seem to be in use so commented it out
				$next_loop_dt = new DateTime();
				$next_loop_dt->setTimestamp($current_loop_dt->getTimestamp());
				$next_loop_dt->add(new DateInterval('P1D'));
				*/

				$day_events = new d4_day_events($current_loop_dt);

				foreach($this->events_query as $single_event) {

					//if this loop iteration is an already added event, skip checking it again.
					if(in_array($single_event->ID,$this->already_added_ids)) {						
						continue;
					}			
					
					$datetime_array = d4events_fetch_datetime($single_event->ID);
					$event_start_date = strtotime($datetime_array['d4events_start_date']);
					$event_end_date = strtotime($datetime_array['d4events_end_date']);

					$repeating = get_post_meta( $single_event->ID, 'd4events_repeating', true );
					$repeat_date_match = false;
					//note - repeating events currently do not function with the "all" range. The reason why, is that the "all" range has no upper and lower limit, so a repeating event with no end date could theoretically appear endlessly until the page crashes
					if( ($repeating) && ($range != 'all') ) {
						$repeat_date_match = $day_events->process_repeats($single_event->ID,$current_loop_timestamp);
					}

					if (	( ($current_loop_timestamp >= $event_start_date) && ($current_loop_timestamp <= $event_end_date) ) || $repeat_date_match	) {
						//if event explicitly occurs on the loop date, or has a matching repeat date, create a new event object and add it to the day events object.
						$single_event_obj = new d4_event($single_event);										
						$single_event_obj->set_files($this->file_list);
						$day_events->add_event($single_event_obj);
						$last_event_date = $current_loop_dt;	
						$last_event_id = $single_event_obj->ID;						
						$event_count++;

						//array of already_added_ids - used to reduce the number of events to check in the current foreach loop
						if(!$repeating) {
							unset($this->need_to_be_added_ids[$single_event_obj->ID]);
							$this->already_added_ids[] = $single_event_obj->ID;
						}	
					}
				}

				if($this->store_empty_dates || !empty($day_events->day_events)) {
					//only add the d4_day_events object to the events data if it has events or if store_empty_dates is set to true.
					$this->events_data[] = $day_events;
					$this->last_event['event_id'] = $last_event_id;
					$this->last_event['date'] = $last_event_date;
				}

				$loop_count++;

				if($this->loop_limit) {
					//if the loop has run too many times, end the foreach.
					if($loop_count >= $this->loop_limit) {
						break;
					}
				}

				if($this->event_limit) {
					//set a hard limit on the total number of event dates that will be added to the object
					if($event_count >= $this->event_limit) {
						break;
					}
				}
			}	
		}
	

	/*
	----- generate html elements
	*/

		public function set_search_html($search_html) {
			//generate the search form html, can be replaced by defining $atts['search_html'] during the initial object creation or later with $object_var->set_search_html($your_custom_html)
			if(!$search_html) {
				$search_html =
					'<form class="search-form" role="search" method="get"action="'.home_url( '/' ).'">
						<input type="hidden" name="post_type" value="events" />
						<label><span class="screenreader">Search for:</span></label>
						<input class="search-field" type="search" placeholder="Search Events..." value="" name="s" title="Search for:" />
						<input class="search-submit" type="submit" value="Submit" />
					</form>';
			}

			$this->search_html = $search_html;
		}		
}