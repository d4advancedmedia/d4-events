<?php

include ('admin_func.php');
include ('columns-events.php');


//Register admin style sheets and scripts
function enqueue_d4events_admin() {
	global $d4events_apikey;
	global $d4events_version;
    wp_enqueue_style('d4events-admin-theme', plugins_url('css/d4events-admin.css', __FILE__), '', $d4events_version);
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-custom', plugins_url( 'css/jquery-ui-custom.css' , __FILE__ ) );

	wp_register_script('d4places-lib', 'https://maps.googleapis.com/maps/api/js?key='.$d4events_apikey.'&libraries=places');

    wp_register_script('d4events-admin', plugins_url( 'js/d4events-admin.js' , __FILE__ ), array( 'jquery', 'd4places-lib' ), $d4events_version, true );
	wp_enqueue_script('d4events-admin');

#	wp_enqueue_script('d4places-lib');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}
add_action('admin_enqueue_scripts', 'enqueue_d4events_admin', 10);
add_action('login_enqueue_scripts', 'enqueue_d4events_admin', 10);	
