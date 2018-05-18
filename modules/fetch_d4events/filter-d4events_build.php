<?php

function filter_d4events_build( $output, $args ) {

	$thumbnail_size = 'thumbnail';
	 
	$output['title']       = get_the_title();
	$output['url']         = get_the_permalink();
	$output['start_stamp'] = get_the_post_thumbnail( $output['id'], $thumbnail_size );

	return $output;

} add_shortcode( 'd4events_build', 'filter_d4events_build', 10, 2 );