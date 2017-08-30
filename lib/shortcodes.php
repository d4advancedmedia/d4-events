<?php

// Use: [d4events year="" month="" category=""]
	function shortcode_d4events( $atts ) {
		$attr=shortcode_atts(array(
			'year' 				=> 	'',
			'month'				=>	'',
			'search' 			=> 	'',
			'category' 			=> 	'',
			'exclude_category' 	=> 	'',
			'agenda' 			=> 	'',
			'style' 			=> 	'calendar',
			'links' 			=> 	'true',
			'files' 			=> 	'',
			'range' 			=> 	'all',
			'number' 			=> 	'',
			'thumbnail_size' 	=> 	'thumbnail',
			'order' 			=> 	'ASC',
			'content_length' 	=> 	'200'
		), $atts);

		$month = date("n");
		if ($attr['year'] != '') {
			$year = date("Y");
		}
		if ($attr['search'] != '') {
			$search = '<form class="search-form" role="search" method="get"action="'.home_url( '/' ).'">
					<input type="hidden" name="post_type" value="events" />
					<label><span class="screenreader">Search for:</span></label>
					<input class="search-field" type="search" placeholder="Search Events..." value="" name="s" title="Search for:" />
					<input class="search-submit" type="submit" value="Submit" />
				</form>';
		}

		if ($attr['content_length'] != 200) {
			$content_length = intval($attr['content_length']);
		} else {
			$content_length = 200;
		}
				
		if ($attr['category'] != '') {
			$category = explode(',', $attr['category']);
			$event_cats_array = array(
									'taxonomy' => 'd4events_category',
									'field'    => 'name',
									'terms'    => $category,
								);
		}
		
		if ($attr['exclude_category'] != '') {
			$exclude_category = explode(',', $attr['exclude_category']);			
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

		$files = $attr['files'];

		$range = $attr['range'];

		if ($attr['number'] != '') {
			$number = intval($attr['number']);
		} else {
			$number = '200';
		}

		
		$order = $attr['order'];

		$thumbnail_size = $attr['thumbnail_size'];
		
		if ($attr['style'] == 'agenda') {

			$event_content = '<div class="d4-cal-inner">';
			$event_content .= d4events_draw_calendar($month,$year,$category,$exclude_category,$attr['style'],'future',$files,$last_event_id,$content_length);			
			$event_content .= '</div>';
			$event_content .= '<a class="d4events-loadmore">Load More</a>';	
		}						
		
		elseif ($attr['style'] == 'list') {

			$event_content = '<div class="d4-cal-inner">';
			$event_content .= d4events_draw_calendar($month,$year,$category,$exclude_category,$attr['style'],$range,$files,$last_event_id,$content_length);			
			$event_content .= '</div>';
			if ($range != 'all') {	
				$event_content .= '<a class="d4events-loadmore">Load More</a>';	
			}	
			
		} else {
			$range = 'all';
			$style = 'calendar';
			$event_content = '<div class="d4-cal-inner">';
			$event_content .= d4events_draw_calendar($month,$year,$category,$exclude_category,$style,$range,$files,$last_event_id,$content_length);
			$event_content .= '</div>';
		}

		
		$buttons = '<div class="cal-change-button cal-prev" data-change="cal-prev">Previous</div><div class="cal-change-button cal-next" data-change="cal-next">Next</div>';

		$output = '';
		$output .= '<div class="d4-cal-wrapper '.$event_style.'">';
		$output .= $search;
		$output .= $event_content;
		$output .= '</div>';	

		return $output;
	} add_shortcode( 'd4events', 'shortcode_d4events' );