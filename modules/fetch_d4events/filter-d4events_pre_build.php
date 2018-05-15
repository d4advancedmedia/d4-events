<?php


function d4events_sort_by_start_time( $a, $b ) {

	$datetime_array_a = d4events_fetch_datetime($a->ID);
	$datetime_array_b = d4events_fetch_datetime($b->ID);

	if ( strtotime($datetime_array_a['d4events_start_time']) >= strtotime($datetime_array_b['d4events_start_time']) ) { 
		return 1; 
	} 

}


function filter_sort_events_by_start_time( $events ) {

	usort( $events, 'd4events_sort_by_start_time' );

	return $events;

} add_filter('d4events_pre_build', 'filter_sort_events_by_start_time');