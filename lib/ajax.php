<?php
add_action( 'wp_ajax_cal_change', 'd4events_ajax_cal_change' );
add_action( 'wp_ajax_nopriv_cal_change', 'd4events_ajax_cal_change' );


function d4events_ajax_cal_change() {

	$shortcode_args['style'] = 'calendar';

    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['month']))
		{
		    $month = $_POST['month'];
		}
	if(isset($_POST['year']))
		{
		    $year = $_POST['year'];
		}
	if(isset($_POST['terms']))
		{
		    $shortcode_args['terms'] = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
		    $shortcode_args['taxonomy'] = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
		    $shortcode_args['tax_field'] = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
		    $shortcode_args['exclude_terms'] = $_POST['exclude_terms'];
		}	
	if(isset($_POST['change']))
		{
		    $change = $_POST['change'];		    
		}
	if(isset($_POST['links']))
		{
		    $shortcode_args['links'] = $_POST['links'];		    
		}
	if ($change == "cal-prev") {
		$shortcode_args['month'] = $month-'1';			
	}
	if ($change == "cal-next") {
		$shortcode_args['month'] = $month+'1';	
	}
	if ($shortcode_args['month'] == '13') {
			$shortcode_args['month'] ='1';
			$shortcode_args['year'] = $year+'1';
	}
	elseif ($shortcode_args['month'] == '0') {
			$shortcode_args['month'] ='12';
			$shortcode_args['year'] = $year-'1';
	}
	else $shortcode_args['year'] = $year;	
	$shortcode_args['range'] = 'all';


	$events = new d4_events_calendar($shortcode_args);
	$events->set_year($shortcode_args['year']);
	$events->set_month($shortcode_args['month']);
	$events->process_events();
	$output = $events->render();

	echo $output;

    die();
}

add_action( 'wp_ajax_loadmore', 'd4events_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'd4events_ajax_loadmore' );


function d4events_ajax_loadmore() {
    // Handle request then generate response using WP_Ajax_Response

	
	if(isset($_POST['terms']))
		{
		    $shortcode_args['terms'] = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
		    $shortcode_args['taxonomy'] = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
		    $shortcode_args['tax_field'] = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
		    $shortcode_args['exclude_terms'] = $_POST['exclude_terms'];
		}
	if(isset($_POST['style'])) {
		$shortcode_args['style'] = $_POST['style'];
	}

	if(isset($_POST['links'])) {
		$shortcode_args['links'] = $_POST['links'];
	}

	if(isset($_POST['range'])) {
		$shortcode_args['range'] = $_POST['range'];
	}
	
	if(isset($_POST['lastdate'])) {
		if($shortcode_args['range'] == 'future') {
			$shortcode_args['range_start'] = $_POST['lastdate'];
		} else {
			$shortcode_args['range_end'] = $_POST['lastdate'];
		}
	}

	if(isset($_POST['excluded_ids'])) {
		$shortcode_args['excluded_ids'] = $_POST['excluded_ids'];
	}

    $shortcode_args['loadmore'] = true;


	$class = 'd4_events_'.$shortcode_args['style'];
	$events = new $class($shortcode_args);

	$events->process_events();
	$output = $events->render();
	//print(json_encode($events));
	echo $output;

    die();
}