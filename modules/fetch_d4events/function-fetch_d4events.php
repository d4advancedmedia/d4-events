<?php

// Deprecated - Gonna keep her for now
	function d4events_get_events2($range_start,$range_stop,$shortcode_args) {
		
		if ($shortcode_args['terms'] != '') {
			$event_terms_array = array(
				'taxonomy' 	=> $shortcode_args['taxonomy'],
				'field'    	=> $shortcode_args['tax_field'],
				'terms'    	=> $shortcode_args['terms'],
			);
		}

		if ($shortcode_args['exclude_terms'] != '') {
			$event_exclude_terms_array = array(
				'taxonomy' 	=> $shortcode_args['taxonomy'],
				'field'    	=> $shortcode_args['tax_field'],
				'terms'    	=> $shortcode_args['exclude_terms'],
				'operator'	=> 'NOT IN',
			);
		}

		$tax_query = array(
			'relation' 		=> 'AND',
			$event_terms_array,
			$event_exclude_terms_array,
		);

		if( $range_start > $range_stop ) {
			$range_array = array($range_stop,$range_start);
		} else {
			$range_array = array($range_start,$range_stop);
		}

		//dont process repeats for all+list shorties, which have the following start/stop values. range not needed either
		if ( ($range_start == '01/01/1800') && ($range_stop == '01/01/2100') ) {
			$events_args = array (
				'post_type' 	=> 'd4events',
				'tax_query'		=>  $tax_query,
				'posts_per_page'=>	-1,
				'meta_key'		=> 'd4events_start',
				'orderby'		=> 'meta_value_num',
				'order'			=> 'DESC'
			);
		} else {

			$meta_query = array(
				'relation'		=>	'OR',
				'standard'		=>	array(
					'compare'		=>	'BETWEEN',
					'value'			=>	$range_array,
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
				'orderby'		=> 'meta_value_num',
				'order'			=> 'DESC'
			);		
		}

		$events_query = new WP_Query($events_args);

		usort( $events_query->posts, 'd4events_sort_by_start_time' );

		return $events_query;

	}
//*/

function fetch_d4events( $fetch_args ) {

	$events_args = apply_filters( 'd4events_query_args', $events_args, $fetch_args );

	$events_query = new WP_Query( $events_args );

	if ( $events_query->have_posts() ) {

		$events_query = apply_filters( 'd4events_pre_build', $events_query );

		$events = array();

		while ( $events_query->have_posts() ) {
			$events_query->the_post();

			$id = get_the_id();

			$event = array(
				'id' => $id
			);

			$events[$id] = apply_filters( 'd4events_build', $event );

		} wp_reset_postdata();

		$events = apply_filters( 'd4events_post_build', $events );

		return $events;

	} else {

		return false;

	}

}
