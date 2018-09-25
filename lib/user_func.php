<?php

function d4events_sort_by_start_time($a,$b) {
	$datetime_array_a = d4events_fetch_datetime($a->ID);
	$datetime_array_b = d4events_fetch_datetime($b->ID);

	if ( strtotime($datetime_array_a['d4events_start_time']) >= strtotime($datetime_array_b['d4events_start_time']) ) {
		return 1;
	}
}

/********************************

Begin RSS Feed Junk

********************************/
add_action('init', 'd4events_rss');
function d4events_rss(){
    add_feed('events_rss', 'd4events_rss_func');
}

function d4events_rss_func() {
    include (dirname( __FILE__ ).'/rss.php');
}


add_filter('d4events_rss', 'd4events_rss_output');
function d4events_rss_output($event_data) {

	$start_date_meta = get_post_meta( $event_data['id'], 'd4events_start', true );
	$end_date_meta = get_post_meta( $event_data['id'], 'd4events_end', true );

	$output .= '
	<item>
		<title>'.get_the_title($event_data['id']).'</title>
		<link>'.get_the_permalink($event_data['id']).'</link>
		<pubDate>'.mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false).'</pubDate>
		<description><![CDATA['.apply_filters( 'the_excerpt_rss', get_the_excerpt($event_data['id'])).']]></description>
		<content:encoded><![CDATA['.apply_filters( 'the_content_feed', apply_filters( 'the_content', get_the_content($event_data['id'])), get_default_feed()).']]></content:encoded>
		<events:dates>
		    <events:date>
		    	<events:date_start>'.date('Y-m-d',$start_date_meta).'</events:date_start>
		    	<events:time_start>'.date('H:i:s',$start_date_meta).'</events:time_start>
		    	<events:date_end>'.date('Y-m-d',$end_date_meta).'</events:date_end>
		    	<events:time_end>'.date('H:i:s',$end_date_meta).'</events:time_end>
		    </events:date>		    
		</events:dates>		
	';

	rss_enclosure();

	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );

	$output .= '</item>';

	return $output;
}


/********************************

Begin Single Template

********************************/

//Load the single event template
function d4events_single_template($single_template) {
     global $post;

     if ($post->post_type == 'd4events') {

     	//check if a single template exists in the theme root, if not load the default one
     	if( file_exists(get_template_directory() . '/template_single-event.php') ) {
     		$single_template = get_template_directory() . '/template_single-event.php';

     	} else {
      		$single_template = dirname( __FILE__ ) . '/template_single-event.php';
      	}
     }  
     return $single_template;
}
add_filter( 'single_template', 'd4events_single_template' );


//Theme wrapper functions

add_action('d4events_theme_wrapper_start', 'd4events_standard_theme_wrapper_start', 10);
add_action('d4events_theme_wrapper_end', 'd4events_standard_theme_wrapper_end', 10);


function d4events_theme_wrapper_start() {
	do_action('d4events_theme_wrapper_start');
}

function d4events_theme_wrapper_end() {
	do_action('d4events_theme_wrapper_end');
}
