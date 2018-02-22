<?php






function d4events_ajax_cal_change() {

	$shortcode_args['style'] = 'calendar';

	// Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['month']))
		{
			$month = $_POST['month'];
		}
	if(isset($_POST['year']))
		{
			$year = $_POST['year'];
		}
	if(isset($_POST['terms']))
		{
			$terms = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
			$terms = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
			$terms = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
			$exclude_terms = $_POST['exclude_terms'];
		}	
	if(isset($_POST['change']))
		{
			$change = $_POST['change'];		    
		}
	if ($change == "cal-prev") {
		$shortcode_args['month'] = $month-'1';			
	}
	if ($change == "cal-next") {
		$shortcode_args['month'] = $month+'1';	
	}
	if ($shortcode_args['month'] == '13') {
			$shortcode_args['month'] ='1';
			$shortcode_args['year'] = $year+'1';
	}
	elseif ($shortcode_args['month'] == '0') {
			$shortcode_args['month'] ='12';
			$shortcode_args['year'] = $year-'1';
	}
	else $shortcode_args['year'] = $year;	
	$shortcode_args['range'] = 'all';

	echo d4events_draw_calendar($shortcode_args);
	die();
}
add_action( 'wp_ajax_cal_change', 'd4events_ajax_cal_change' );
add_action( 'wp_ajax_nopriv_cal_change', 'd4events_ajax_cal_change' );



function d4events_ajax_loadmore() {
	// Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['lastdate'])) {
		$shortcode_args['lastdate'] = $_POST['lastdate'];
	}
	if(isset($_POST['terms']))
		{
			$shortcode_args['terms'] = $_POST['terms'];
		}
	if(isset($_POST['taxonomy']))
		{
			$shortcode_args['taxonomy'] = $_POST['taxonomy'];
		}
	if(isset($_POST['tax_field']))
		{
			$shortcode_args['tax_field'] = $_POST['tax_field'];
		}
	if(isset($_POST['exclude_terms']))
		{
			$shortcode_args['exclude_terms'] = $_POST['exclude_terms'];
		}
	if(isset($_POST['style'])) {
		$shortcode_args['style'] = $_POST['style'];
	}

	echo d4events_draw_calendar($shortcode_args);

	die();
}
add_action( 'wp_ajax_loadmore', 'd4events_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'd4events_ajax_loadmore' );




// Load the single event template
	function d4events_single_template($single_template) {
		 global $post;

		 if ($post->post_type == 'd4events') {

			//check if a single template exists in the theme root, if not load the default one
			if( file_exists(get_template_directory() . '/single-event.php') ) {
				$single_template = get_template_directory() . '/single-event.php';

			} else {
				$single_template = dirname( __FILE__ ) . '/single-event.php';
			}
		 }  
		 return $single_template;
	}
	add_filter( 'single_template', 'd4events_single_template' );


//Theme wrapper functions

add_action('d4events_theme_wrapper_start', 'd4events_standard_theme_wrapper_start', 10);
add_action('d4events_theme_wrapper_end', 'd4events_standard_theme_wrapper_end', 10);


function d4events_theme_wrapper_start() {
	do_action('d4events_theme_wrapper_start');
}

function d4events_theme_wrapper_end() {
	do_action('d4events_theme_wrapper_end');
}



/*Default theme wrapper, matches Skivvy. Replace with your own using the following in your theme functions file:

remove_action('d4events_theme_wrapper_start', 'd4events_standard_theme_wrapper_start', 10);
remove_action('d4events_theme_wrapper_end', 'd4events_standard_theme_wrapper_end', 10);

add_action('d4events_theme_wrapper_start', 'my_theme_wrapper_start', 10);
add_action('d4events_theme_wrapper_end', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<section id="main">';
}

function my_theme_wrapper_end() {
  echo '</section>';
}

*/

function d4events_standard_theme_wrapper_start($wrapper_start) {
	get_template_part( 'inc/chunk' , 'title' );
	echo '<section id="content"><div class="page-wrapper"><main id="main-content" class="clearfix" role="main">';
}

function d4events_standard_theme_wrapper_end($wrapper_end) {
	echo '</div></div></section>';
}