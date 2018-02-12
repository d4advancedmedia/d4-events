<?php
/*
	Plugin Name: D4 Events
	Plugin URI: https://github.com/d4advancedmedia/Events
	GitHub Plugin URI: https://github.com/d4advancedmedia/Events
	GitHub Branch: master
	Description: Simple events manager plugin
	Version: 3.4.1
	Author: D4 Adv. Media
	License: GPL2
*/

$d4events_version = '3.4.1';

//Register admin style sheets and scripts
add_action('admin_enqueue_scripts', 'd4events_admin_elements');
add_action('login_enqueue_scripts', 'd4events_admin_elements');	
function d4events_admin_elements() {
	global $d4events_apikey;
	global $d4events_version;
    wp_enqueue_style('d4events-admin-theme', plugins_url('css/d4events-admin.css', __FILE__), '', $d4events_version);
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-custom', plugins_url( 'css/jquery-ui-custom.css' , __FILE__ ) );
    wp_register_script( 'd4events-admin', plugins_url( 'js/d4events-admin.js' , __FILE__ ), array( 'jquery' ), $d4events_version, true );
	wp_enqueue_script('d4events-admin');
	wp_register_script( 'd4places-lib', 'https://maps.googleapis.com/maps/api/js?key='.$d4events_apikey.'&libraries=places');
	wp_enqueue_script('d4places-lib');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

// Register front end style sheets and scripts.
add_action( 'wp_enqueue_scripts', 'register_d4events_elements' );
function register_d4events_elements() {
	global $d4events_version;
	wp_register_style( 'd4events', plugins_url( 'css/d4events.css' , __FILE__ ),'', $d4events_version );
	wp_enqueue_style( 'd4events' );
	wp_register_style( 'add-to-calendar', plugins_url( 'css/atc-style-blue.css' , __FILE__ ) );
	wp_enqueue_style( 'add-to-calendar' );
	wp_register_script( 'd4events', plugins_url( 'js/d4events.js' , __FILE__ ), array( 'jquery' ), $d4events_version, true );
	wp_enqueue_script('d4events');
	wp_localize_script( 'd4events', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

include ('config.php');
include ('lib/admin_func.php');
include ('lib/user_func.php');
include ('lib/shortcodes.php');

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
