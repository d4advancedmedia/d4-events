<?php


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

		$events = apply_filters( 'd4events_post_build', $events_query );

		return $events;

	} else {

		return false;

	}

}
