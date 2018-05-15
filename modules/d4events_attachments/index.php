<?php

// Temporary solutions, to move to add on later


function d4events_output_files($files,$file_array) {
	//This is the array of file categories listed in the shortcode, so that the files can be sorted by category (ex: Agenda, Meeting, Minutes)
	$shortcode_filecats = explode(',',$files);

	$multicount = d4events_multipass_counter();

	if ( isset($files) && ($files != '') && ($multicount != 0) && (!empty($file_array)) ) {

		$file_cluster = '';
		$filetype_title = '';
		
		$file_cluster .= '<div class="files-wrapper">';
		foreach ($shortcode_filecats as $type) {	
			$i = 1;
			$file_cluster .= '<div class="event-filegroup">';					
			foreach ($file_array as $file) {
				if ( ($type == strtolower($file['type'])) && ($file['link'] != '') ) {
					if ($filetype_title == $file['type']) {									
						$file_cluster .= $file['link'];	
					} else {
						$file_cluster .= '<h6 class="event-filetype">'.$file['type'].'</h6>';
						$file_cluster .= $file['link'];
						$filetype_title = $file['type'];
					}
					
				}
				$i++;
			}
			$file_cluster .= '</div>';
		}
		$file_cluster .= '</div>';
	}

	return $file_cluster;

}

function d4events_get_files($files) {

	$file_array = array();
	//determine number of multipass					
	$multicount = d4events_multipass_counter();
	
	for ($k = 1 ; $k <= $multicount; $k++) {			
		$meta = get_post_meta(get_the_ID(), 'd4events_file_'.$k, true);
		if ($meta[1] != '') {				
			$file_type = $meta[0];
			if ($meta[2] != '') {
				$file_name = $meta[2];
			} else {
				$file_name = end((explode('/', rtrim($meta[1], '/'))));
			}
			if ( isset($meta[1]['extension']) ) {
				$file_class = 'fileclass_'.$meta[1]['extension'];
			}
			$file_link = '<a href="'.$meta[1].'" class="events_files '.$file_class.'" target="_blank">'.$file_name.'</a>';
			
			$file_array[] = array(
					'type' => $file_type,
					'name' => $file_name,
					'link' => $file_link,
			);
		}
		
	}

	return $file_array;

}
