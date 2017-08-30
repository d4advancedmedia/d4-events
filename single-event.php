<?php
//Create Variables

$datetime_array = d4events_fetch_datetime($post->ID);

if ($_GET['date'] != '') {
	$start_date = date('m/d/Y',$_GET['date']);
	$end_date = date('m/d/Y',$_GET['date'] + (strtotime($datetime_array['d4events_end_date']) - strtotime($datetime_array['d4events_start_date'])));

} else {
	$start_date = $datetime_array['d4events_start_date'];
	$end_date = $datetime_array['d4events_end_date'];
}

$start_time = $datetime_array['d4events_start_time'];
$end_time = $datetime_array['d4events_end_time'];
$title = get_the_title();
$description = get_the_content();
$location = get_post_meta( $post->ID, 'd4events_location', true );
$registration_link = get_post_meta( $post->ID, 'd4events_registration_link', true );
$timezone = get_post_meta( $post->ID, 'd4events_timezone', true );

//Create addtocalendar.com readable values
if (strtotime($start_time) == strtotime('xyz')) {
	$start_time_adjusted = '12:00am';
} else {$start_time_adjusted = $start_time;}
if (strtotime($end_time) == strtotime('xyz')) {
	$end_time_adjusted = '11:59pm';
} else {$end_time_adjusted = $end_time;}
$act_date_start = date('Y-m-d H:i:s', (strtotime($start_date.$start_time_adjusted)));
$act_date_end = date('Y-m-d H:i:s', (strtotime($end_date.$end_time_adjusted)));

get_header();
d4events_before_main_content();

	echo '<div id="single-event-wrapper">TEST';	

		//Output the event content
		echo '<div class="one_half single-event-left"><div class="skivdiv-content">'.
			'<h2>Description</h2>'.
			the_post();
			the_content();
		echo '</div></div>'.
		'<div class="one_half last single-event-right"><div class="skivdiv-content">'.
			'<h2>Details</h2>'.
			'<span class="addtocalendar atc-style-blue">'.
	        	'<var class="atc_event">'.
		            '<var class="atc_date_start">'.$act_date_start.'</var>'.
		            '<var class="atc_date_end">'.$act_date_end.'</var>'.
		            '<var class="atc_timezone">'.$timezone.'</var>'.
		            '<var class="atc_title">'.$title.'</var>'.
		            '<var class="atc_description">'.$description.'</var>'.
		            '<var class="atc_location">'.$location.'</var>'.
		            #'<var class="atc_organizer">Luke Skywalker</var>'.
		            #'<var class="atc_organizer_email">luke@starwars.com</var>'.
	        	'</var>'.
	    	'</span>'.
			'<table id="single-event-datetime">'.
				'<tr>'.
					'<td>Event Starts</td>'.
					'<td>Event Ends</td>'.
				'</tr>'.
				'<tr>'.
					'<td>'.$start_date.'</td>'.
					'<td>'.$end_date.'</td>'.
				'</tr>'.
				'<tr>'.
					'<td>'.$start_time.'</td>'.
					'<td>'.$end_time.'</td>'.
				'</tr>'.
			'</table>';
			if ($location != '') {
			echo '<iframe'.
				' width="100%"'.
				' height="300px"'.
				' frameborder="0" style="border:0"'.
				' src="https://www.google.com/maps/embed/v1/search?key='.$d4events_apikey.'&q='.$location.'&zoom=15">'.
				' </iframe>';
			}
			if ($registration_link != '') {
				echo '<a class="button registration-link" href="'.$registration_link.'" target="_blank">Register Here</a>';
			}

			$files = d4events_get_files('agenda,minutes,image,other');
			if ($files != '') {
				echo '<h3>Files</h3>'.
				$files;
			}
			
			echo '</div></div>';

	echo '</div>';

d4events_after_main_content();
get_footer();
?>