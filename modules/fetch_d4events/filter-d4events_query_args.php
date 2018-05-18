<?php




function d4events_query_args_defaults( $events_args, $fetch_args ) {

	// include/exclude events based on taxonomy
		$tax_query = array();

		if ( ! empty($fetch_args['shortcode_args']['terms']) ) {

			$tax_query['event_terms_array'] = array(
				'taxonomy' 	=> $fetch_args['shortcode_args']['taxonomy'],
				'field'    	=> $fetch_args['shortcode_args']['tax_field'],
				'terms'    	=> $fetch_args['shortcode_args']['terms'],
			);

		}


		if ( ! empty($fetch_args['shortcode_args']['exclude_terms'])  ) {

			$tax_query['event_exclude_terms_array'] = array(
				'taxonomy' 	=> $fetch_args['shortcode_args']['taxonomy'],
				'field'    	=> $fetch_args['shortcode_args']['tax_field'],
				'terms'    	=> $fetch_args['shortcode_args']['exclude_terms'],
				'operator'	=> 'NOT IN',
			);

		}

		if ( ! empty($tax_query) ) {

			$tax_query['relation'] = 'AND';

			$events_args['tax_query'] = $tax_query;
			$events_args['meta_key']  = 'd4events_start';
			$events_args['orderby']   = 'meta_value_num';
			$events_args['order']     = 'DESC';

		}



	// dont process repeats for all+list shorties, which have the following start/stop values. range not needed either
		if ( $fetch_args['range_start'] != '01/01/1800' && $fetch_args['range_stop'] != '01/01/2100' ) {

			if ( $fetch_args['range_start'] > $fetch_args['range_stop'] ) {

				$range_array = array($fetch_args['range_stop'],$fetch_args['range_start']);

			} else {

				$range_array = array($fetch_args['range_start'],$fetch_args['range_stop']);

			}

			$meta_query = array(
				'relation' => 'OR',
				'standard' => array(
					'compare'    => 'BETWEEN',
					'value'      => $range_array,
					'type'       => 'numeric',
					'key'        => 'd4events_start'
				),
				'repeat'  => array(
					'compare'    => '!=',
					'value'      => '',
					'key'        => 'd4events_repeating'
				),
			);

		#	$events_args['meta_query'] = array($meta_query);

		}

	return $events_args;

} add_filter('d4events_query_args', 'd4events_query_args_defaults', 10, 2 );