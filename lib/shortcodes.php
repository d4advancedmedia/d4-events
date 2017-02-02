<?php

// Use: [events year="" month="" category=""]
	function shortcode_events( $atts ) {
		$attr=shortcode_atts(array(
			'year' => '',
			'month'=>'',
			'search' => '',
			'category' => '',
			'exclude_category' => '',
			'agenda' => '',
			'style' => 'calendar',
			'links' => 'true',
			'files' => '',
			'range' => 'all',
			'number' => '',
			'thumbnail_size' => 'thumbnail',
			'order' => 'ASC',
		), $atts);

		$month = date("n");
		if ($attr['year'] != '') {
			$year = date("Y");
		}
		if ($attr['search'] != '') {
			$search = '<form class="search-form" role="search" method="get"action="';
			$search .= home_url( '/' );
			$search .= '">';
			$search .= '<input type="hidden" name="post_type" value="events" />';
			$search .= '<label><span class="screenreader">Search for:</span><input class="search-field" type="search" placeholder="Search Events..." value="" name="s" title="Search for:" /></label><input class="search-submit" type="submit" value="Submit" /></form>';
		}
		
		$category = $attr['category'];
		if ($category != '') {
			$category = $attr['category'];
			$event_cats_array = array(
									'taxonomy' => 'd4events_category',
									'field'    => 'name',
									'terms'    => $category,
								);
		}

		$exclude_category = $attr['exclude_category'];
		if ($exclude_category != '') {			
			$event_exclude_cats_array = array(
											'taxonomy' => 'd4events_category',
											'field'    => 'term_id',
											'terms'    => $exclude_category,
											'operator' => 'NOT IN',
										);
		}

		$tax_query = array(
						'relation' => 'AND',
						$event_cats_array,
						$event_exclude_cats_array,
					);	

		$event_style = ' events-style_'.$attr['style'];

		if ($attr['agenda'] != '') {
			$event_style .= ' agenda-view';
		}
		
		if ($attr['files'] != '') {
			$files = explode(',' , $attr['files']);
		}

		$range = $attr['range'];

		if ($attr['number'] != '') {
			$number = intval($attr['number']);
		} else {
			$number = '200';
		}

		
		$order = $attr['order'];

		$thumbnail_size = $attr['thumbnail_size'];
		
		if ($attr['style'] == 'agenda') {
			$event_content = d4events_draw_agenda($month,$year,$category,$exclude_category);
		}						
		
		elseif ($attr['style'] == 'list') {			
			$events_args = array (
				'post_type' => 'd4events',
				//'category_name'	=> $category,
				//'category__not_in' => $exclude_category,
				//'post_status' => array( 'pending', 'future', 'publish')	,
				'posts_per_page'	=> -1,				
				'meta_key'			=> 'd4events_start_date',
				'orderby'			=> 'meta_value',				
				'order'				=> $order,
				'tax_query'			=>  $tax_query,
			);
			$events_query = new WP_Query($events_args);

			$i=1;

			while ( $events_query->have_posts() ) { $events_query->the_post();	

				if ($i <= $number) {					

					$end_date = strtotime(get_post_meta( get_the_ID(), 'd4events_end_date', true ));

					if ($end_date == '') {
						$end_date = strtotime(get_post_meta( get_the_ID(), 'd4events_start_date', true ));
					}

					if ($range == 'past') {
						if ($end_date < strtotime('now')) {
							$event_content .= get_list_events($attr['links'],$attr['files'],$thumbnail_size);
							
							//Only increment the post count when the post meets all of the criteria.
							$i++;	
						}						
					} 

					elseif ($range == 'future') {
						if ($end_date >= strtotime('now')) {
							$event_content .= get_list_events($attr['links'],$attr['files'],$thumbnail_size);
							$i++;
						}				
					}

					else {

						$event_content .= get_list_events($attr['links'],$attr['files'],$thumbnail_size);	
					}
				}			

			}
			wp_reset_query();
			
		} else {
			$event_content = d4events_draw_calendar($month,$year,$category,$exclude_category);
		}

		
		$buttons = '<div class="cal-change-button cal-prev" data-change="cal-prev">Previous</div><div class="cal-change-button cal-next" data-change="cal-next">Next</div>';

		$output = '';
		$output .= '<div id="d4-cal-wrapper" class="'.$event_style.'">';
		$output .= $search;
		$output .= '<div id="d4-cal-inner">';
		$output .= $event_content;
		$output .= '</div></div>';	

		return $output;
	} add_shortcode( 'events', 'shortcode_events' );