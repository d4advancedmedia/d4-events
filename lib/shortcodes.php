<?php

// Use: [events year="" month="" category=""]
	function shortcode_events( $atts ) {
		$attr=shortcode_atts(array(
			'year' => '',
			'month'=>'',
			'search' => '',
			'category' => '',
			'agenda' => '',
		), $atts);

		$month = date("n");
		if ($attr['year'] != '') {
			$year = date("Y");
		}
		if ($attr['search'] != '') {
			$search = '<form class="search-form" role="search" method="get"action="';
			$search .= home_url( '/' );
			$search .= '">';
			$search .= '<input type="hidden" name="post_type" value="events" />';
			$search .= '<label><span class="screenreader">Search for:</span><input class="search-field" type="search" placeholder="Search Events..." value="" name="s" title="Search for:" /></label><input class="search-submit" type="submit" value="Submit" /></form>';
		}
		if ($attr['category'] != '') {
			$category = $attr['category'];
		}
		if ($attr['agenda'] != '') {
			$agenda = 'class="agenda-view"';
		}

		$event_calendar = d4events_draw_calendar($month,$year,$category);
		$buttons = '<div class="cal-change-button cal-prev" data-change="cal-prev">Previous</div><div class="cal-change-button cal-next" data-change="cal-next">Next</div>';

		$output = '';
		$output .= '<div id="d4-cal-wrapper"'.$agenda.'>';
		$output .= $search;
		$output .= '<div id="d4-cal-inner">';
		$output .= $event_calendar;
		$output .= '</div></div>';	

		return $output;
	} add_shortcode( 'events', 'shortcode_events' );