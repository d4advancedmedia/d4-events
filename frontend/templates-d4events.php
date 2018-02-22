<?php







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