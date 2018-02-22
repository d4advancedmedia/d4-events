<?php


function d4events_rss(){
    add_feed('events_rss', 'd4events_rss_func');
} add_action('init', 'd4events_rss');



function d4events_rss_func() {
    include (dirname( __FILE__ ).'/rss.php');
}


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
} add_filter('d4events_rss', 'd4events_rss_output');
