<?php




function d4events_query_args_defaults( $events_args, $fetch_args = null ) {


	$tax_query = array();

	if ($fetch_args['shortcode_args']['terms'] != '') {

		$tax_query['relation'] = 'AND';

		$tax_query['event_terms_array'] = array(
			'taxonomy' 	=> $fetch_args['shortcode_args']['taxonomy'],
			'field'    	=> $fetch_args['shortcode_args']['tax_field'],
			'terms'    	=> $shortcode_args['terms'],
		);

	}

	if ($fetch_args['shortcode_args']['exclude_terms'] != '') {

		$tax_query['relation'] = 'AND';

		$tax_query['event_exclude_terms_array'] = array(
			'taxonomy' 	=> $fetch_args['shortcode_args']['taxonomy'],
			'field'    	=> $fetch_args['shortcode_args']['tax_field'],
			'terms'    	=> $fetch_args['shortcode_args']['exclude_terms'],
			'operator'	=> 'NOT IN',
		);

	}

	//dont process repeats for all+list shorties, which have the following start/stop values. range not needed either
	if ( $fetch_args['range_start'] == '01/01/1800' && $fetch_args['range_stop'] == '01/01/2100' ) {

		$events_args = array (
			'post_type' 	=> 'd4events',
			'tax_query'		=>  $tax_query,
			'posts_per_page'=>	-1,
			'meta_key'		=> 'd4events_start',
			'orderby'		=> 'meta_value_num',
			'order'			=> 'DESC'
		);

	} else {

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

		$events_args = array (
			'post_type'      => 'd4events',
			'tax_query'      => $tax_query,
			'posts_per_page' => -1,
			'meta_query'     => array($meta_query),
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC'
		);

	}

	return $events_args;

} add_filter('d4events_query_args', 'd4events_query_args_defaults', 10, 2 );