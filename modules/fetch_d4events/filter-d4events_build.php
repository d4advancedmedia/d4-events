<?php

function filter_d4events_build( $output, $args ) {
	 
	$output['title']       = get_the_title();
	$output['url']         = get_the_permalink();

	if ( has_post_thumbnail() ) {
		$output['thumbnail']  = get_the_post_thumbnail( $output['id'], $args['shortcode_args']['thumbnail_size'] );
	} else {
		$output['thumbnail']  = false;
	}

	$output['start_stamp'] = get_post_meta($event_id, 'd4events_start', true);
	$output['stop_stamp']  = get_post_meta($event_id, 'd4events_end', true);

	return $output;

} add_filter( 'd4events_build', 'filter_d4events_build', 10, 2 );