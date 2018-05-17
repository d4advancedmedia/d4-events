<?php


function d4events_output_agenda_default( $output, $events = array(), $args = array() ) {


	if ( $args['style'] == 'agenda' ) {

		$classes = array(
			'd4-cal-wrapper',
			'agenda',
			//showlinks,
		);
		
		if ( isset($args['class']) ) {
			$classes[] = $args['class'];
		}

		$output .= '<div class=" ' . implode(' ', $classes) . '">';
			$output .= '<div class="d4-cal-inner">';
				$output .= 'AGENDA: ';
				//$output .= d4events_draw_calendar($shortcode_args);
				$output .= '<pre>' . print_r($events, true) . '</pre>';
				$output .= '<a class="d4events-loadmore">Load More</a>';
			$output .= '</div>';
		$output .= '</div>';

	}

	return $output;

} add_filter('d4events_output', 'd4events_output_agenda_default', 10, 3);