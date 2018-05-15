<?php

function filter_the_content_d4events( $content ) {

	if ( is_singular( 'd4events' ) ) {

		global $d4events_apikey;

		$post_id = get_the_ID();

		$datetime_array = d4events_fetch_datetime($post_id);

		#if ($_GET['date'] != '') {
		#	$start_date = date('m/d/Y',strtotime($_GET['date']));
		#	$end_date = date('m/d/Y',$_GET['date'] + (strtotime($datetime_array['d4events_end_date']) - strtotime($datetime_array['d4events_start_date'])));

		#} else {
			$start_date = $datetime_array['d4events_start_date'];
			$end_date = $datetime_array['d4events_end_date'];
		#}

		$start_time = $datetime_array['d4events_start_time'];
		$end_time = $datetime_array['d4events_end_time'];
		$title = get_the_title();

		$location = get_post_meta( $post_id, 'd4events_location', true );
		$location_desc = get_post_meta( $post_id, 'd4events_location_desc', true );
		$registration_link = get_post_meta( $post_id, 'd4events_registration_link', true );
		$timezone = get_post_meta( $post_id, 'd4events_timezone', true );

		//Create addtocalendar.com readable values
		if (strtotime($start_time) == strtotime('xyz')) {
			$start_time_adjusted = '12:00am';
		} else {
			$start_time_adjusted = $start_time;
		}

		if (strtotime($end_time) == strtotime('xyz')) {
			$end_time_adjusted = '11:59pm';
		} else {
			$end_time_adjusted = $end_time;
		}

		$act_date_start = date('m/d/Y H:i:s', (strtotime($start_date.$start_time_adjusted)));
		$act_date_end = date('m/d/Y H:i:s', (strtotime($end_date.$end_time_adjusted)));

		if($location == '') {
			$atc_location = 'Reno, NV';
		} else {
			$atc_location = $location;
		}

		$timezone = 'America/Los_Angeles';


		//

			$output = '';
			$output .=  '<div id="single-event-wrapper">';	

				//Output the event content
				$output .= '<div class="one_half single-event-left">';
					$output .= '<div class="skivdiv-content">';
						$output .= '<h2>Description</h2>';
						$output .= $content;
					$output .= '</div>';
				$output .= '</div>';


				$output .= '<div class="one_half last single-event-right">';
					$output .= '<div class="skivdiv-content">';

						$output .= '<h2>Details</h2>';
						$output .= '<span class="addtocalendar atc-style-blue">';
							$output .= '<var class="atc_event">';
								$output .= '<var class="atc_date_start">'.$act_date_start.'</var>';
								$output .= '<var class="atc_date_end">'.$act_date_end.'</var>';
								$output .= '<var class="atc_timezone">'.$timezone.'</var>';
								$output .= '<var class="atc_title">'.$title.'</var>';
								$output .= '<var class="atc_description">'.$content.'</var>';
								$output .= '<var class="atc_location">'.$atc_location.'</var>';
							#	$output .= '<var class="atc_organizer">Luke Skywalker</var>';
							#	$output .= '<var class="atc_organizer_email">luke@starwars.com</var>';
							$output .= '</var>';
						$output .= '</span>';
						

						$output .= '<table id="single-event-datetime">';
							$output .= '<tr>';
								$output .= '<td>Event Starts</td>';
								$output .= '<td>Event Ends</td>';
							$output .= '</tr>';
							$output .= '<tr>';
								$output .= '<td>'.$start_date.'</td>';
								$output .= '<td>'.$end_date.'</td>';
							$output .= '</tr>';
							$output .= '<tr>';
								$output .= '<td>'.$start_time.'</td>';
								$output .= '<td>'.$end_time.'</td>';
							$output .= '</tr>';
						$output .= '</table>';

						if ($location != '') {
							$output .=  '<iframe'.
								' width="100%"'.
								' height="300px"'.
								' frameborder="0" style="border:0"'.
								' src="https://www.google.com/maps/embed/v1/search?key='.$d4events_apikey.'&q='.$location.'&zoom=15">'.
								' </iframe>';
						}

						if( $location_desc != '' ) {
							$output .=  '<p class="single-event-loc-desc">'.$location_desc.'</p>';
						}

						if ( $registration_link != '' ) {
							$output .=  '<a class="button registration-link" href="'.$registration_link.'" target="_blank">Register Here</a>';
						}

						if ( get_post_meta( get_the_ID(), 'd4events_files', true ) != '' ) {
							$file_array = 'agenda,minutes,image,other';
							$files = d4events_get_files($file_array);
							$file_output = d4events_output_files($files,$file_array);
							if ( is_array($files) ) {
								$output .= '<h3>Files</h3>';
								$output .= $file_output;
							}
						} 
						//*/
					$output .= '</div>';
				$output .= '</div>';

			$output .=  '</div>';

		return $output;

	} else {

		return $content;

	}

} add_filter( 'the_content', 'filter_the_content_d4events', 10 );