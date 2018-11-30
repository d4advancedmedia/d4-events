<?php

function shortcode_d4events( $atts ) {
		$attr=shortcode_atts(array(
			'year' 				=> 	'',
			'month'				=>	'',
			'search' 			=> 	'',
			'category' 			=> 	'',
			'exclude_category' 	=> 	'',
			'taxonomy'			=>  'd4events_category',
			'tax_field'			=>  'name',
			'terms'				=>  '',
			'exclude_terms'		=>  '',
			'agenda' 			=> 	'',
			'style' 			=> 	'calendar',
			'links' 			=> 	'true',
			'files' 			=> 	'',
			'range' 			=> 	'all',
			'number' 			=> 	'',
			'thumbnail_size' 	=> 	'thumbnail',
			'order' 			=> 	'ASC',
			'content_length' 	=> 	'200',
			'class'				=>  '',
			'output_filter'     =>  '',
			'nowrap'			=>	false,
			'excluded_ids'		=>  '',
			'loop_limit'		=> 	'',
			'disable_loadmore'	=> 	'',
		), $atts);

		//Create a new events object using the style attribute. This will automatically search for your new class if you wish to extend. For example, for a class called d4_events_newcal, set the style attribute to 'newcal'

		$class = 'd4_events_'.$attr['style'];
		$events = new $class($attr);

		$events->process_events();

		foreach($attr as $att_name => $att_value) {
			if($att_value) {
				$data_atts .= ' data-'.$att_name.'="'.$att_value.'"';
			}
		}

		/*

		OLD output. This included the data-last-event-date in the wrapper, which isn't used at all in the pagination anymore, but I left this here just in case it was used somewhere else and needs to be re-instated. The challenge is that the ->getTimestamp function will break if a valid timestamp doesn't exist.

		$output = '<div class="events-data-wrapper events-style_'.$attr['style'].' '.$attr['class'].'" data-last-event-date="'.$events->last_event['date']->getTimestamp().'" data-last-event-id="'.$events->last_event['id'].'"'.$data_atts.'>'.$events->render().'</div>';*/

		$output = '<div class="events-data-wrapper events-style_'.$attr['style'].' '.$attr['class'].'" data-last-event-id="'.$events->last_event['id'].'"'.$data_atts.'>'.$events->render().'</div>';

		return $output;
}

add_shortcode( 'd4events', 'shortcode_d4events' );