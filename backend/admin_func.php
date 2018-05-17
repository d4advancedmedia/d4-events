<?php


function d4events_timezone_list($postid) {
	$current_offset = get_option('gmt_offset');
	$tzstring = get_post_meta( $postid, 'd4events_timezone', true );
	if ($tzstring == '') {
		$tzstring = get_option('timezone_string');
		$check_zone_info = true;

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( false !== strpos($tzstring,'Etc/GMT') )
			$tzstring = '';

		if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
			$check_zone_info = false;
			if ( 0 == $current_offset )
				$tzstring = 'UTC+0';
			elseif ($current_offset < 0)
				$tzstring = 'UTC' . $current_offset;
			else
				$tzstring = 'UTC+' . $current_offset;
		}
	}

	$output = '<select data-id="'.$postid.'" data-selected="'.$tzstring.'" id="d4events_timezone" name="d4events_timezone" aria-describedby="timezone-description">';
	$output .= wp_timezone_choice($tzstring);
	$output .= '</select>';

	return $output;
}

