<?php


function d4_edit_events_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event' ),
		'event_date' => __( 'Event Date' ),
		'event_categories' => __( 'Event Categories' ),
		'event_tags' => __( 'Event Tags' ),
	);

	return $columns;

} add_filter( 'manage_edit-d4events_columns', 'd4_edit_events_columns' ) ;



function d4events_manage_columns( $column, $post_id ) {

	global $post;

	switch( $column ) {

		/* If displaying the 'duration' column. */
		case 'event_date' :

			/* Get the post meta. */
			$datetime_array = d4events_fetch_datetime($post_id);
			$event_date = $datetime_array['d4events_start_date'].'  -  '. $datetime_array['d4events_end_date'];

			/* If no event_date is found, output a default message. */
			if ( empty( $event_date ) )
				echo __( '-' );
			else
				echo $event_date;

			break;

		/* If displaying the 'category' column. */
		case 'event_categories' :

			// Get the categories.
			$event_terms = wp_get_object_terms( $post_id,  'd4events_category' );
 
			if ( ! empty( $event_terms ) ) {
			    if ( ! is_wp_error( $event_terms ) ) {
			            foreach( $event_terms as $term ) {
			            	#echo get_term_link($term->slug, 'd4events_category');
			                echo '<a href="'.get_term_link($term->slug, 'd4events_category').'">'.$term->name.'</a><br/>'; 
			            }
			    }
			}

			break;

		/* If displaying the 'category' column. */
		case 'event_tags' :

			// Get the categories.
			$event_terms = wp_get_object_terms( $post_id,  'd4events_tag' );
 
			if ( ! empty( $event_terms ) ) {
			    if ( ! is_wp_error( $event_terms ) ) {
			            foreach( $event_terms as $term ) {
			            	#echo get_term_link($term->slug, 'd4events_category');
			                echo '<a href="'.get_term_link($term->slug, 'd4events_tag').'">'.$term->name.'</a><br/>'; 
			            }
			    }
			}

			break;	
	}
} add_action( 'manage_d4events_posts_custom_column', 'd4events_manage_columns', 10, 2 );





function d4events_sortable_columns( $columns ) {

	$columns['event_date'] = 'event_date';

	return $columns;
} add_filter( 'manage_edit-d4events_sortable_columns', 'd4events_sortable_columns' );






function d4events_custom_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'event_date' == $orderby ) {
        $query->set('meta_key','d4events_start');
        $query->set('orderby','meta_value_num');
    }
} add_action( 'pre_get_posts', 'd4events_custom_orderby' );