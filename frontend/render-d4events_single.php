<?php


function d4events_render_single( $shortcode_args, $calendar_date ) {

	$link_open = '';
	$link_close = '';
	$readmore = '';
	$ID = get_the_ID();
	
	//custom output support, places all event IDs, dates, and files in an array for custom output
		if ( has_filter($shortcode_args['output_filter']) ) {
			$event_data = array(
				'id'				=> $ID,
				'date'				=> $calendar_date,
				'shortcode_args'	=> $shortcode_args,
			);

			$event_content = apply_filters($shortcode_args['output_filter'], $event_data);

			return $event_content;
		}

	#if ($links == 'true') {
		$link_open = '<a href="'.get_the_permalink().'?date='.$calendar_date.'">';	
		$link_close = '</a>';
		$readmore = '<a class="events_list-readmore" href="'.get_the_permalink().'?date='.$calendar_date.'">Read More</a>';
	#}

	// Render Output
		switch ($shortcode_args['style']) {

			case 'calendar':
				$event_content .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
					$event_content .= '<h5 class="cal-event-title">';
						$event_content .= $link_open;
							$event_content .= '<span>';
								$event_content .= get_the_title();
							$event_content .= '</span>';
						$event_content .= $link_close;
					$event_content .= '<div class="clearfix"></div>';
				$event_content .= '</div>';
				break;


			case 'agenda':
				$datetime_array = d4events_fetch_datetime($ID);

				$event_content .= '<tr class="agenda-single-event">';
					$event_content .= '<td class="agenda-time-column">';
						$event_content .= $datetime_array['d4events_start_time'];
					$event_content .= '</td>';
					$event_content .= '<td class="agenda-title-column">';
						$event_content .= $link_open;
							$event_content .= '<span>';
								$event_content .= get_the_title();
							$event_content .= '</span>';
						$event_content .= $link_close;
					$event_content .= '</td>';
				$event_content .= '</tr>';
				break;


			case 'list':
				$datetime_array = d4events_fetch_datetime($ID);

				$post_thumbnail = '';

				if ( has_post_thumbnail() ) {
					$post_thumbnail = '<div class="events_list-thumb">';
						$post_thumbnail .= $link_open;
							$post_thumbnail .= get_the_post_thumbnail($ID,$thumbnail_size);
						$post_thumbnail .= $link_close;
					$post_thumbnail .= '</div>';
					$has_image = ' event-has-image';
				}

				#$content_length = intval($content_length);
				$file_array = d4events_get_files();
				$file_cluster = d4events_output_files($shortcode_args['files'],$file_array);

				$content_length = 255;
				$post_content = get_the_excerpt();

				if ( strlen($post_content) > $content_length ) {
					$post_content_modified = preg_replace('/\s+?(\S+)?$/', '', substr(wpautop(do_shortcode($post_content)), 0, $content_length)).' […]';
				} else {
					$post_content_modified = $post_content;
				}

				$event_content .= '<div class="events_list-single' . $has_image . '" data-event_date="' . $calendar_date . '" data-event_id="' . $ID . '">';
					$event_content .= $post_thumbnail;
					$event_content .= '<h5 class="cal-event-title">';
						$event_content .= $link_open;
							$event_content .= '<span>' . get_the_title($ID) . '</span>';
						$event_content .= $link_close;
						$event_content .= '<span class="events_list-datetime">';
							$event_content .= '<span class="events_list-date">' . date('m/d/Y',$calendar_date) . '</span>';
							$event_content .= '<span class="events_list-time">' . $datetime_array['d4events_start_time'] . '</span>';
						$event_content .= '</span>';
					$event_content .= '</h5>';
					$event_content .= '<div class="events_list-content">';
						$event_content .= '<div class="events_list-description">';
							$event_content .= $post_content_modified;
						$event_content .= '</div>';
						$event_content .= $readmore;
						$event_content .= '<div class="clearfix"></div>';
						$event_content .= $file_cluster;
						$event_content .= '<div class="clearfix"></div>';
					$event_content .= '</div>';
				$event_content .= '</div>';
				break;

		}


	return $event_content;

}