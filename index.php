<?php
/*
	Plugin Name: D4 Events
	Plugin URI: https://github.com/d4advancedmedia/
	GitHub Theme URI: https://github.com/d4advancedmedia/
	GitHub Branch: master
	Description: Simple events manager plugin
	Version: 3.1.2
	Author: D4 Adv. Media
	License: GPL2
*/

$d4events_version = '3.1.2';

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
}

include ('config.php');
include ('lib/functions.php');
include ('lib/shortcodes.php');

?>