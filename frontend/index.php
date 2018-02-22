<?php


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