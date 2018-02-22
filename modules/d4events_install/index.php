<?php


function d4events_install() {

	/*the following is a query designed to updated the post type for the old "events" post type to the less likely to conflict "d4events" post type.
	The query is commented out because a theme may have a different post type already in place using the "events" post type name. Converting these automatically
	could result in damaging a site database*/

	/*Update the post_type when the post uses the old "events" post type
	global $wpdb;

	$prefix = $wpdb->prefix;

	$data = array('post_type'=>'d4events');
	$where = array('post_type'=>'events');

	$wpdb->update($prefix.'_posts', $data, $where );*/  



	//Get all of the events
	$args = array (
		'post_type'			=>	'd4events',
		'posts_per_page'	=>	-1,
		'meta_key'			=>	'd4events_start_date',
		'meta_compare'		=>	'EXISTS'
	);
	$events_query = new WP_Query($args);

	while ( $events_query->have_posts() ) { $events_query->the_post();

		$postID = get_the_ID();

		$old_start_date = get_post_meta($postID,'d4events_start_date',true);
		$old_start_time = get_post_meta($postID,'d4events_start_time',true);
		$old_end_date = get_post_meta($postID,'d4events_end_date',true);
		$old_end_time = get_post_meta($postID,'d4events_end_time',true);

		if ($old_end_date == '') {
			$old_end_date = $old_start_date;
		}

		if ($old_start_time == '') {
			$old_start_time = '12:00pm';
		}

		if ($old_end_time == '') {
			$old_end_time = '11:59pm';
		}		

		//Remove any entries for "All Day" and replace with valid times
		if ($old_start_time == 'All Day') {
			$old_start_time = '12:00am';
		}
		if ($old_end_time == 'All Day') {
			$old_end_time = '11:59pm';
		}

		//Create new entries with the merged timestamps
		update_post_meta( $postID, 'd4events_start', strtotime("$old_start_date $old_start_time"));
		update_post_meta( $postID, 'd4events_end', strtotime("$old_end_date $old_end_time"));

		//Remove the old meta keys
		delete_post_meta($postID,'d4events_start_date');
		delete_post_meta($postID,'d4events_start_time');
		delete_post_meta($postID,'d4events_end_date');
		delete_post_meta($postID,'d4events_end_time');

	}

	wp_reset_postdata();
}

function d4events_update_db_check() {
    $d4events_version = '3.4.1';
    $d4events_db_version = get_site_option( 'd4events_db_version' );
    if($d4events_db_version == '') {
    	$d4events_db_version = '1.0.0';
    }
    #require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    #update_option( "d4events_db_version", $d4events_version );

    //Only run the updater script for sites that are older than 3.2.0
    if ( version_compare($d4events_db_version, '3.2.0', '<') ) {
	    d4events_install();
	}

	//Only update the db version if it is older than the plugin version 
    if ( version_compare($d4events_db_version, $d4events_version, '<') ) {	    
	    update_option( "d4events_db_version", $d4events_version );
	}
}

#add_action('init','d4events_update_db_check');

register_activation_hook( __FILE__, 'd4events_update_db_check' );