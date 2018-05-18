<?php

	function shortcode_d4events( $atts ) {

		$default_shortcode_atts = array(

			// Order query
				'order'             => 'ASC',

			// Limit Query
				'range'             => 'all',
				'year'              => date("Y"),
				'month'             => '',

			// Taxonomy query
				'category'          => '',
				'exclude_category'  => '',
				'taxonomy'          => 'd4events_category',
				'tax_field'         => 'name',
				'terms'             => '',
				'exclude_terms'     => '',

			// Content Rendering
				'search'            => '',
				'agenda'            => '',
				'style'             => 'calendar',
				'links'             => 'true',
				'thumbnail_size'    => 'thumbnail',
				'output_filter'     => '',
				'content_length'    => '200',
				'class'             => '',

			// Other
				'files'             => '',
				'number'            => '',
				'nowrap'            => false,

		);

		$default_shortcode_atts = apply_filters( 'd4events_shortcode_atts', $default_shortcode_atts );

		$attr = shortcode_atts( $default_shortcode_atts, $atts);




		$month = date("n");
		$year  = $attr['year'];

		if ( $attr['search'] != '' ) {
			$search  = '<form class="search-form" role="search" method="get" action="' . home_url( '/' ) . '">';
				$search .= '<input type="hidden" name="post_type" value="events" />';
				$search .= '<label><span class="screenreader">Search for:</span></label>';
				$search .= '<input class="search-field" type="search" placeholder="Search Events..." value="" name="s" title="Search for:" />';
				$search .= '<input class="search-submit" type="submit" value="Submit" />';
			$search .= '</form>';
		}

		if ($attr['content_length'] != 200) {
			$content_length = intval($attr['content_length']);
		} else {
			$content_length = 200;
		}

		$event_style = ' events-style_'.$attr['style'];

		if ( $attr['agenda'] != '' ) {
			$event_style .= ' agenda-view';
		}	

		$files = $attr['files'];

		$range = $attr['range'];

		if ( $attr['number'] != '' ) {
			$number = intval($attr['number']);
		} else {
			$number = '200';
		}

		if ( $attr['links'] != 'true' ) {
			$showlinks = ' no-event-links';
		}
		
		$order = $attr['order'];

		//Add legacy support for category attribute. New version uses any taxonomy and you can comma separate multiple taxonomies.
			$terms = array();
			if ($attr['category'] != '') {
				$attr['terms'] = $attr['category'];
				$terms = explode(',',$attr['terms']);
			} 

		$exclude_terms = array();
		if ($attr['exclude_category'] != '') {
			$attr['exclude_terms'] = $attr['exclude_category'];
			$exclude_terms = explode(',',$attr['exclude_terms']);
		}


		$full_args = array(
			'range_start'    => '',
			'range_stop'     => '',
			'shortcode_args' => array(
				'month'            => $month,
				'year'             => $year,
				'taxonomy'         => $attr['taxonomy'],
				'tax_field'        => $attr['tax_field'],
				'thumbnail_size'   => $attr['thumbnail_size'],
				'terms'            => $terms,
				'exclude_terms'    => $exclude_terms,	
				'style'            => $attr['style'],
				'links'            => $attr['links'],
				'range'            => $range,
				'files'            => $files,
			//	'last_event_id'    => $last_event_id,
				'content_length'   => $content_length,
				'output_filter'    => $attr['output_filter'],
			),
		);


		$events = fetch_d4events($full_args);

		if ( empty($events) ) {

			$output = __('No events', 'd4events');

		} elseif ( ! empty($attr['output_filter']) ) {

			$output = d4events_draw_calendar($shortcode_args);

		} else {
			
			$output = apply_filters('d4events_output', '', $events, $full_args);

		}


		/*
		if ($attr['output_filter'] != '') {

			$output = d4events_draw_calendar($shortcode_args);

		} else {


			if ($attr['style'] == 'agenda') {


				$event_content = '<div class="d4-cal-inner"><table cellpadding="0" cellspacing="0" class="calendar">';
					$event_content .= d4events_draw_calendar($shortcode_args);
				$event_content .= '</table></div>';
				$event_content .= '<a class="d4events-loadmore">Load More</a>';

			} elseif ($attr['style'] == 'list') {

				$event_content = '<div class="d4-cal-inner">';
					$event_content .= d4events_draw_calendar($shortcode_args);
				$event_content .= '</div>';

				if ($range != 'all') {
					$event_content .= '<a class="d4events-loadmore">Load More</a>';
				}	
				
			} else {

				$range = 'all';
				$style = 'calendar';
				$event_content = '<div class="d4-cal-inner">';
					$event_content .= d4events_draw_calendar($shortcode_args);	
				$event_content .= '</div>';

			}

			
			$buttons = '<div class="cal-change-button cal-prev" data-change="cal-prev">Previous</div><div class="cal-change-button cal-next" data-change="cal-next">Next</div>';

			if ( $attr['nowrap'] ) {

				$output = $event_content;

			} else {

				$output = '';
				$output .= '<div class="d4-cal-wrapper '. $event_style . $showlinks . ' ' . $attr['class'] . '">';
					$output .= $search;
					$output .= $event_content;
				$output .= '</div>';

			}

		}			

		//*/
		return $output;

	} add_shortcode( 'd4events', 'shortcode_d4events' );
