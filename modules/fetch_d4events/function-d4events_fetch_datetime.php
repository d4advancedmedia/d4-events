<?php


// Fetch and unmerge separate dates and times from a single string composed of a single unix timestamp  (start-end)
	function d4events_fetch_datetime($postid) {

		$start = get_post_meta($postid,'d4events_start',true);
		$end = get_post_meta($postid,'d4events_end',true);

		if ( ($start == '') || ($end == '') ) {
			#$meta = date("m/d/Y");
			$meta = 'not available';
		}  else {

			$meta_array['d4events_start_date'] = date('m/d/Y',$start);
			$meta_array['d4events_end_date'] = date('m/d/Y',$end);

			$meta_array['d4events_start_time'] = date('g:ia',$start);
			$meta_array['d4events_end_time'] = date('g:ia',$end);

		}

		return $meta_array;
	}