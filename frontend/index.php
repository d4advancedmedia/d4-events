<?php



include('func-parse_events.php');


include('shortcode-d4events.php');
include('ajax-d4events.php');

include('filter-the_content-d4events-single.php');
#include('templates-d4events.php');

include('rssfeed-d4events.php');

// Register front end style sheets and scripts.
function register_d4events_elements() {

	global $d4events_version;

	wp_register_style( 'd4events', plugins_url( 'css/d4events.css' , __FILE__ ),'', $d4events_version );
	wp_register_style( 'add-to-calendar', plugins_url( 'css/atc-style-blue.css' , __FILE__ ) );
	wp_register_script( 'd4events', plugins_url( 'js/d4events.js' , __FILE__ ), array( 'jquery' ), $d4events_version, true );

	wp_localize_script( 'd4events', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_style( 'd4events' );
	wp_enqueue_style( 'add-to-calendar' );
	wp_enqueue_script('d4events');

} add_action( 'wp_enqueue_scripts', 'register_d4events_elements' );