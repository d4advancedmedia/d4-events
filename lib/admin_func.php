<?php

// Register Events Post Type
function d4events_posttype() {

	global $d4events_posttype_singular;
	global $d4events_posttype_plural;
	global $d4events_icon;

	$labels = array(
		'name'                  => _x( $d4events_posttype_plural, 'Post Type General Name', 'd4events' ),
		'singular_name'         => _x( $d4events_posttype_singular, 'Post Type Singular Name', 'd4events' ),
		'menu_name'             => __( $d4events_posttype_plural, 'd4events' ),
		'name_admin_bar'        => __( $d4events_posttype_singular, 'd4events' ),
		'archives'              => __( $d4events_posttype_singular.' Archives', 'd4events' ),
		'parent_item_colon'     => __( 'Parent '.$d4events_posttype_singular.':', 'd4events' ),
		'all_items'             => __( 'All '.$d4events_posttype_plural, 'd4events' ),
		'add_new_item'          => __( 'Add New '.$d4events_posttype_singular, 'd4events' ),
		'add_new'               => __( 'Add New', 'd4events' ),
		'new_item'              => __( 'New '.$d4events_posttype_singular, 'd4events' ),
		'edit_item'             => __( 'Edit '.$d4events_posttype_singular, 'd4events' ),
		'update_item'           => __( 'Update '.$d4events_posttype_singular, 'd4events' ),
		'view_item'             => __( 'View '.$d4events_posttype_singular, 'd4events' ),
		'search_items'          => __( 'Search '.$d4events_posttype_plural, 'd4events' ),
		'not_found'             => __( 'Not found', 'd4events' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'd4events' ),
		'featured_image'        => __( 'Featured Image', 'd4events' ),
		'set_featured_image'    => __( 'Set featured image', 'd4events' ),
		'remove_featured_image' => __( 'Remove featured image', 'd4events' ),
		'use_featured_image'    => __( 'Use as featured image', 'd4events' ),
		'insert_into_item'      => __( 'Insert into item', 'd4events' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'd4events' ),
		'items_list'            => __( 'Items list', 'd4events' ),
		'items_list_navigation' => __( 'Items list navigation', 'd4events' ),
		'filter_items_list'     => __( 'Filter items list', 'd4events' ),
	);
	$rewrite = array(
		'slug'                  => 'events',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( $d4events_posttype_singular, 'd4events' ),
		'description'           => __( 'd4events', 'd4events' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions','custom-fields' ),
		'taxonomies'            => array( 'd4events_category', 'd4events_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'				=> $d4events_icon,
		'rewrite'				=> $rewrite,
	);
	register_post_type( 'd4events', $args );

}
add_action( 'init', 'd4events_posttype', 0 );

// Register Custom Taxonomy
function d4events_categories() {

	$labels = array(
		'name'                       => _x( 'Event Categories', 'Taxonomy General Name', 'd4events_categories' ),
		'singular_name'              => _x( 'Event Category', 'Taxonomy Singular Name', 'd4events_categories' ),
		'menu_name'                  => __( 'Event Categories', 'd4events_categories' ),
		'all_items'                  => __( 'All Event Categories', 'd4events_categories' ),
		'parent_item'                => __( 'Parent Event Category', 'd4events_categories' ),
		'parent_item_colon'          => __( 'Parent Event Category:', 'd4events_categories' ),
		'new_item_name'              => __( 'Event Category Name', 'd4events_categories' ),
		'add_new_item'               => __( 'Add New Event Category', 'd4events_categories' ),
		'edit_item'                  => __( 'Edit Event Category', 'd4events_categories' ),
		'update_item'                => __( 'Update Event Category', 'd4events_categories' ),
		'view_item'                  => __( 'View Event Category', 'd4events_categories' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'd4events_categories' ),
		'add_or_remove_items'        => __( 'Add or Remove Event Categories', 'd4events_categories' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'd4events_categories' ),
		'popular_items'              => __( 'Popular Event Categories', 'd4events_categories' ),
		'search_items'               => __( 'Search Event Categories', 'd4events_categories' ),
		'not_found'                  => __( 'Not Found', 'd4events_categories' ),
		'no_terms'                   => __( 'No items', 'd4events_categories' ),
		'items_list'                 => __( 'Event Categories list', 'd4events_categories' ),
		'items_list_navigation'      => __( 'Event Categories list navigation', 'd4events_categories' ),
	);
	$rewrite = array(
		'slug'                       => 'events-categories',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'd4events_category', array( 'd4events' ), $args );

}
add_action( 'init', 'd4events_categories', 0 );

// Register Custom Taxonomy
function d4events_tags() {

	$labels = array(
		'name'                       => _x( 'Event Tags', 'Taxonomy General Name', 'd4events_tags' ),
		'singular_name'              => _x( 'Event Tag', 'Taxonomy Singular Name', 'd4events_tags' ),
		'menu_name'                  => __( 'Event Tags', 'd4events_tags' ),
		'all_items'                  => __( 'All Event Tags', 'd4events_tags' ),
		'parent_item'                => __( 'Parent Event Tag', 'd4events_tags' ),
		'parent_item_colon'          => __( 'Parent Event Tag:', 'd4events_tags' ),
		'new_item_name'              => __( 'Event Tag Name', 'd4events_tags' ),
		'add_new_item'               => __( 'Add New Event Tag', 'd4events_tags' ),
		'edit_item'                  => __( 'Edit Event Tag', 'd4events_tags' ),
		'update_item'                => __( 'Update Event Tag', 'd4events_tags' ),
		'view_item'                  => __( 'View Event Tag', 'd4events_tags' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'd4events_tags' ),
		'add_or_remove_items'        => __( 'Add or Remove Event Tags', 'd4events_tags' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'd4events_tags' ),
		'popular_items'              => __( 'Popular Event Tags', 'd4events_tags' ),
		'search_items'               => __( 'Search Event Tags', 'd4events_tags' ),
		'not_found'                  => __( 'Not Found', 'd4events_tags' ),
		'no_terms'                   => __( 'No items', 'd4events_tags' ),
		'items_list'                 => __( 'Event Tags list', 'd4events_tags' ),
		'items_list_navigation'      => __( 'Event Tags list navigation', 'd4events_tags' ),
	);
	$rewrite = array(
		'slug'                       => 'events-tags',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'd4events_tag', array( 'd4events' ), $args );

}
add_action( 'init', 'd4events_tags', 0 );


//Admin columns
add_filter( 'manage_edit-d4events_columns', 'd4_edit_events_columns' ) ;

function d4_edit_events_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event' ),
		'event_date' => __( 'Event Date' ),
		'event_categories' => __( 'Event Categories' ),
		'event_tags' => __( 'Event Tags' ),
	);

	return $columns;
}

add_action( 'manage_d4events_posts_custom_column', 'd4events_manage_columns', 10, 2 );

function d4events_manage_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'duration' column. */
		case 'event_date' :

			/* Get the post meta. */
			$datetime_array = d4events_fetch_datetime($post_id);
			$event_date = $datetime_array['d4events_start_date'].'  -  '. $datetime_array['d4events_end_date'];

			/* If no event_date is found, output a default message. */
			if ( empty( $event_date ) )
				echo __( '-' );
			else
				echo $event_date;

			break;

		/* If displaying the 'category' column. */
		case 'event_categories' :

			// Get the categories.
			$event_terms = wp_get_object_terms( $post_id,  'd4events_category' );
 
			if ( ! empty( $event_terms ) ) {
			    if ( ! is_wp_error( $event_terms ) ) {
			            foreach( $event_terms as $term ) {
			            	#echo get_term_link($term->slug, 'd4events_category');
			                echo '<a href="'.get_term_link($term->slug, 'd4events_category').'">'.$term->name.'</a><br/>'; 
			            }
			    }
			}

			break;

		/* If displaying the 'category' column. */
		case 'event_tags' :

			// Get the categories.
			$event_terms = wp_get_object_terms( $post_id,  'd4events_tag' );
 
			if ( ! empty( $event_terms ) ) {
			    if ( ! is_wp_error( $event_terms ) ) {
			            foreach( $event_terms as $term ) {
			            	#echo get_term_link($term->slug, 'd4events_category');
			                echo '<a href="'.get_term_link($term->slug, 'd4events_tag').'">'.$term->name.'</a><br/>'; 
			            }
			    }
			}

			break;	
	}
}

add_filter( 'manage_edit-d4events_sortable_columns', 'd4events_sortable_columns' );

function d4events_sortable_columns( $columns ) {

	$columns['event_date'] = 'event_date';

	return $columns;
}

add_action( 'pre_get_posts', 'd4events_custom_orderby' );
function d4events_custom_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'event_date' == $orderby ) {
        $query->set('meta_key','d4events_start');
        $query->set('orderby','meta_value_num');
    }
}

function d4events_timezone_list($postid) {
	$current_offset = get_option('gmt_offset');
	$tzstring = get_post_meta( $postid, 'd4events_timezone', true );
	if ($tzstring == '') {
		$tzstring = get_option('timezone_string');
		$check_zone_info = true;

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( false !== strpos($tzstring,'Etc/GMT') )
			$tzstring = '';

		if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
			$check_zone_info = false;
			if ( 0 == $current_offset )
				$tzstring = 'UTC+0';
			elseif ($current_offset < 0)
				$tzstring = 'UTC' . $current_offset;
			else
				$tzstring = 'UTC+' . $current_offset;
		}
	}

	$output = '<select data-id="'.$postid.'" data-selected="'.$tzstring.'" id="d4events_timezone" name="d4events_timezone" aria-describedby="timezone-description">';
	$output .= wp_timezone_choice($tzstring);
	$output .= '</select>';

	return $output;
}

// Add the Meta Box
function d4events_add_metabox() {
    add_meta_box(
        'd4events_meta_box', // $id
        'Event Details', // $title 
        'd4events_show_metabox', // $callback
        'd4events', // $post_type
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'd4events_add_metabox');

// Field Array

$frequency_options = array('Weekly','Monthly');
$frequency_meta_array = array();
foreach ($frequency_options as $frequency) {
           $frequency_meta_array[$frequency] = array (
                'label' => $frequency,
                'value' => $frequency
            );
}

$days_options = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$days_meta_array = array();
foreach ($days_options as $days) {
           $days_meta_array[$days] = array (
                'label' => $days,
                'value' => $days
            );
}

$prefix = 'd4events_';
$d4events_meta_fields = array(
	array(
	    'label' => 'Start Date',
	    'desc'  => 'Start Date',	    
	    'id'    => $prefix.'start_date',
	    'type'  => 'date'
	),
	array(
        'label'=> 'Start Time',
        'desc'  => 'Start Time',
        'placeholder' => '00:00am or All Day',
        'id'    => $prefix.'start_time',
        'type'  => 'time'
    ),
	array(
	    'label' => 'End Date',
	    'desc'  => 'End Date',
	    'id'    => $prefix.'end_date',
	    'type'  => 'date'
	),
    array(
        'label'=> 'End Time',
        'desc'  => 'End Time',
        'placeholder' => '00:00am or All Day',
        'id'    => $prefix.'end_time',
        'type'  => 'time'
    ),
    array(
        'label'=> 'Timezone',
        'id'    => $prefix.'timezone',
        'type'  => 'timezone'
    ),
    array(
        'label'=> 'Location',
        'desc'  => 'Enter a location',
        'id'    => $prefix.'location',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Registration Link',
        'desc'  => 'Copy and paste registration link here',
        'id'    => $prefix.'registration_link',
        'type'  => 'text'
    ),
    array(
        'label'=> '',
        'desc'  => 'Remove link to event details page?',
        'id'    => $prefix.'remove_link',
        'type'  => 'checkbox',
        'options' => array('Remove Link')
    ),
    array(
        'label'=> '',
        'desc'  => 'Repeating...',
        'id'    => $prefix.'repeating',
        'type'  => 'checkbox',
        'options' => array('Repeating')
    ),
    array(
        'label'=> 'Frequency',
        'desc'  => '',
        'id'    => $prefix.'frequency',
        'type'  => 'select',
        'options' => $frequency_meta_array
    ),
    array(
        'label'=> 'Repeat on',
        'desc'  => '',
        'id'    => $prefix.'repeat_days',
        'type'  => 'checkbox_group',
        'options' => $days_meta_array
    ),
    array(
        'label'=> 'Repeat by',
        'desc'  => '',
        'id'    => $prefix.'monthly_repeat_by',
        'type'  => 'radio',
        'options' => array (
	        'day_of_the_month' => array (
	            'label' => 'day of the month',
	            'value' => 'day_of_the_month'
	        ),
	        'day_of_the_week' => array (
	            'label' => 'day of the week',
	            'value' => 'day_of_the_week'
	        ),
    	),
    ),
    array(
	    'label' => 'Ends on',
	    'desc'  => 'The last day of the repeating event. Enter "Never" to run indefinitely.',
	    'id'    => $prefix.'repeat_end_date',
	    'type'  => 'date'
	),
	array(
	    'label' => 'Blackout Dates',
	    'desc'  => 'Each date added here will be excluded from the series of repeating events. Useful for cancellations or holidays.',
	    'id'    => $prefix.'blackout_dates',
	    'type'  => 'multi_date'
	),
    array(
        'label'=> 'Files',
        'desc'  => 'File URL',
        'id'    => $prefix.'file_',
        'number' => 1,
        'type'  => 'multipass',
        'multipass_opts' => array('Agenda','Minutes','Image','Other')
    ),
);

function d4events_multipass_counter() {

	$continue = true;

	for ($k = 1 ; $continue; $k++) {

		unset($multimeta);		
		$multimeta = get_post_meta(get_the_ID(), 'd4events_file_'.$k, true);
		if (empty($multimeta)) {
			$continue = false;
		}
	}

	//The above for statement will run once before it fails, even if there isn't any meta saved, resulting in $k = actual_metacount + 2. Subtracting two will ensure that the counter returns the actual number of meta entries in the db
	$total = $k - 2;

	return $total;
}

// The Callback
function d4events_show_metabox() {
global $d4events_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="d4events_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	#echo '<input type="hidden" name="events_errors" value="" />';
     
    // Begin the field table and loop
    echo '<div class="form-table">';
    #echo '<div class="events-errors">'.$_GET('events_errors').'</div>';	

    foreach ($d4events_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true); 

        $datetime_array = d4events_fetch_datetime($post->ID);         

        // begin a table row with
        echo '<div class="events-meta-row row-'.$field['id'].'">
                <label for="'.$field['id'].'">'.$field['label'].'</label>
                <div class="event-meta-input">';
                switch($field['type']) {
                    // case items will go here
	                    // date
						case 'date':

							if ($field['id'] == 'd4events_repeat_end_date') {
								if ( ($meta != 'Never') && ($meta != '') ) {
									$datetime_array[$field['id']] = date('m/d/Y',$meta);
								} else {
									$datetime_array[$field['id']] = $meta;
								}
							}

							echo '<input type="text" class="datepicker_recurring_start" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$datetime_array[$field['id']].'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
						break;

						// date
						case 'multi_date':

							#echo $meta;

							#print_r($meta);

							//$meta = get_post_meta($post->ID, $field['id'], false);

							if ($meta == '') {
								$meta[0] = '';
							}

							echo '<div id="blackout_dates">';

							foreach ($meta as $blackout_date) { 							
								echo '<input type="text" class="datepicker_recurring_start" name="'.$field['id'].'[]" id="" value="'.$blackout_date.'" size="30" />';	
							}

							echo '<br /><span class="description">'.$field['desc'].'</span><br/><span class="multi-date-add button-secondary"><span>+</span> Add More Dates</span></div>';

						break;

						// time
						case 'time':
							//if not "all day", format the time correctly
							if (strtolower($meta) != 'all day') {
								$meta = date("g:ia", strtotime($meta));
							} else {
								$meta = 'All Day';
							}
						    echo '<input type="text" placeholder="'.$field['placeholder'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$datetime_array[$field['id']].'" size="30" />
						        <br /><span class="description">'.$field['desc'].'</span>';
						break;

						// timezone
						case 'timezone':
							$postid = $post->ID;
						    echo d4events_timezone_list($postid);
						break;

						// select
						case 'select':
						    echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						    foreach ($field['options'] as $option) {
						        echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
						    }
						    echo '</select><br /><span class="description">'.$field['desc'].'</span>';
						break;

						// text
						case 'text':
						    echo '<input type="text" placeholder="'.$field['placeholder'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
						        <br /><span class="description">'.$field['desc'].'</span>';
						break;

						// checkbox
						case 'checkbox':
						    echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
						        <label for="'.$field['id'].'">'.$field['desc'].'</label>';
						break;

						// checkbox_group
						case 'checkbox_group':
						    foreach ($field['options'] as $option) {
						        echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' /> 
						                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						    }
						    echo '<span class="description">'.$field['desc'].'</span>';
						break;

						// radio
						case 'radio':
						    foreach ( $field['options'] as $option ) {
						        echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
						                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						    }
						break;

						// multipass
						case 'multipass':

							//determine number of multipass					
    						$multicount = d4events_multipass_counter();

    						//make sure that there is always one field
    						if ($multicount == 0) {
    							$multicount = 1;
    						}
    						echo '<div class="multipass-wrap" total='.$multicount.'>';
							for ($k = 1 ; $k <= $multicount; $k++) {	

								$meta = get_post_meta($post->ID, $field['id'].$k, true);

								
									echo '<div class="singlepass">'.
										'<div class="multipass-select">'.									
											'<select name="'.$field['id'].$k.'[0]" id="type_'.$field['id'].$k.'">';
										    foreach ($field['multipass_opts'] as $option) {
										        echo '<option', $meta[0] == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
										    }
										    echo '</select><br /><span class="description">Category</span>'.
										'</div>'.

										'<div class="multipass-text">'.
										    '<input class="event-filename" type="text" placeholder="" name="'.$field['id'].$k.'[2]" id="name_'.$field['id'].$k.'" value="'.$meta[2].'" size="30" />										   
										        <br /><span class="description">File Name</span>'.
										'</div>'.

										'<div class="multipass-text">'.
										    '<input class="event-fileurl" type="text" placeholder="'.$field['placeholder'].'" name="'.$field['id'].$k.'[1]" id="'.$field['id'].$k.'" value="'.$meta[1].'" size="30" />
										    	<input type="button" class="multipass_upload button-secondary" id="'.$field['id'].$k.'_multipass_upload"	name="'.$field['id'].$k.'_multipass_upload"	value="Upload" >
										        <br /><span class="description">'.$field['desc'].'</span>';
										echo '</div><div class="multi-delete">&#x274c;</div>'.
									'<div class="clearfix"></div></div>';
								
							}
								echo '<span class="multi-add button-secondary"><span>+</span> Add More Files</span>'.
							'</div>';
						break;
                } //end switch

                // Add special timezone selection field

       	#echo 'Repeats the third Monday of every month';
        echo '</div></div>';
    } // end foreach
    echo '</div>'; // end table
}

// Save the Data
function d4events_save_meta($post_id) {
    global $d4events_meta_fields;
     
    // verify nonce
    if (!wp_verify_nonce($_POST['d4events_meta_box_nonce'], basename(__FILE__))) 
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
    
    //Validation Section//
    #$_POST('events_errors') = '';  

    if ( ($_POST['d4events_start_date'] == '') && ($_POST['d4events_end_date'] == '')) {
		$_POST['d4events_start_date'] = date("m/d/Y");
		$_POST['d4events_end_date'] = date("m/d/Y");

		#$_POST('events_errors') .= 'The Start Date and End Date are required fields and both will default to the current date if left blank.';	
	}

	if ($_POST['d4events_end_date'] == '') {
		$_POST['d4events_end_date'] = $_POST['d4events_start_date'];

		#$_POST('events_errors') .= 'The End Date is a required field and will default to the current date if left blank.';
	}

	if ( ($_POST['d4events_start_date'] == '') && ($_POST['d4events_end_date'] != '')) {
		$_POST['d4events_start_date'] = $_POST['d4events_end_date'];

		#$_POST('events_errors') .= 'The Start Date is a required field and will default to the current date if left blank.';
	}

	if ( (strtotime($_POST['d4events_start_date'])) > (strtotime($_POST['d4events_end_date'])) ) {
		$_POST['d4events_end_date'] = $_POST['d4events_start_date'];

		#$_POST('events_errors') .= 'The Start Date cannot be later than the End Date.';
	}

	if ( ($_POST['d4events_repeating'] == '') || ($_POST['d4events_frequency'] == '') ) {

		$_POST['d4events_repeating'] = '';
		$_POST['d4events_repeat_end_date'] = '';
		$_POST['d4events_frequency'] = '';
		$_POST['d4events_repeat_days'] = '';
		$_POST['d4events_monthly_repeat_by'] = '';
		$_POST['d4events_blackout_dates'] = '';


		#$_POST('events_errors') .= 'If the repeating checkbox is unchecked, clear out old repeat data.';
	}

	if ($_POST['d4events_repeat_days'] != '') {

		$_POST['d4events_monthly_repeat_by'] = '';

		#$_POST('events_errors') .= 'If the repeating checkbox is unchecked, clear out old repeat data.';
	}

	if ($_POST['d4events_monthly_repeat_by'] != '') {

		$_POST['d4events_repeat_days'] = '';

		#$_POST('events_errors') .= 'If the repeating checkbox is unchecked, clear out old repeat data.';
	}

	if ( ($_POST['d4events_repeating'] != '') && ($_POST['d4events_repeat_end_date'] == '') ) {

		$_POST['d4events_repeat_end_date'] = 'Never';

		#$_POST('events_errors') .= 'The End Date is a required field and will default to the current date if left blank.';
	}

	if ( (strtolower($_POST['d4events_start_time']) != 'all day') && (strtolower($_POST['d4events_end_time']) != 'all day') ) {
		$_POST['d4events_start_time'] = date("g:ia", strtotime($_POST['d4events_start_time']));
		$_POST['d4events_end_time'] = date("g:ia", strtotime($_POST['d4events_end_time']));

		if ( ($_POST['d4events_start_date'] == $_POST['d4events_end_date']) && (strtotime($_POST['d4events_start_time']) > strtotime($_POST['d4events_end_time'])) ) {

			$_POST['d4events_end_time'] = date("g:ia", strtotime($_POST['d4events_start_time']));
			#$_POST('events_errors') .= 'The Start Time cannot be later than the End Time.';
		}
	} else {
		$_POST['d4events_start_time'] = 'all day';
		$_POST['d4events_end_time'] = 'all day';
	}
	//End Validation Section//

	//Merge the start/end dates and times into two hyphenated timestamps (start-end) and save to the database.
	//This will keep start and end times together, keep them sortable, and re-use the same meta key

	$start_date = $_POST['d4events_start_date'];
	$start_time = $_POST['d4events_start_time'];
	$end_date = $_POST['d4events_end_date'];
	$end_time = $_POST['d4events_end_time'];

	update_post_meta( $post_id, 'd4events_start', strtotime("$start_date $start_time"));
	update_post_meta( $post_id, 'd4events_end', strtotime("$end_date $end_time"));
	update_post_meta($post_id,'d4events_repeat_end_date',strtotime($_POST['d4events_repeat_end_date']));

	//Don't try to save the event dates or times, those were already saved before the loop
    $dont_save = array('d4events_start_date','d4events_start_time','d4events_end_time','d4events_end_date','d4events_repeat_end_date',);    

    // loop through fields and save the data
    foreach ($d4events_meta_fields as $field) {   

        if (!in_array($field['id'],$dont_save)) {

	        $old = get_post_meta($post_id, $field['id'], true);
	        $new = $_POST[$field['id']];     

	        if ($field['type'] == 'multipass') {
	        	//clear out all existing metadata to ensure that keys increment by 1, starting with 1
	        	$multicount = d4events_multipass_counter();
				for ($i = 1 ; $i <= $multicount; $i++) {
					delete_post_meta($post_id, 'd4events_file_'.$i, $_POST[$field['id'].$i]);
				}	
				//save in all fields
	        	$k = 0;
	        	foreach($_POST as $key => $value) {
				    if (strpos($key, 'd4events_file_') === 0) {			    	
			    		$k++;
			        	update_post_meta($post_id, 'd4events_file_'.$k, $_POST[$field['id'].$k]);
				    }
				}
	        } else {
		        if ($_POST[$field['id']] == 'day_of_the_week') {
		        	$repeat_interval = ceil(date('j', strtotime($_POST['d4events_start_date'])) / 7);
		        	update_post_meta($post_id, 'd4events_repeat_interval', $repeat_interval);
		        	$month_weekday_repeat = date('l', strtotime($_POST['d4events_start_date']));
		        	update_post_meta($post_id, 'd4events_month_weekday_repeat', $month_weekday_repeat);
		        }
		        if ($_POST[$field['id']] == 'day_of_the_month') {
		        	$repeat_interval = date('j', strtotime($_POST['d4events_start_date']));
		        	update_post_meta($post_id, 'd4events_repeat_interval', $repeat_interval);
		        	delete_post_meta($post_id, 'd4events_month_weekday_repeat');
		        }
		        
		        if ($new && $new != $old) {
		            update_post_meta($post_id, $field['id'], $new);
		        } elseif ('' == $new && $old) {
		            delete_post_meta($post_id, $field['id'], $old);
		        }
		    }
		}       
    } // end foreach
}
add_action('save_post', 'd4events_save_meta');

//Fetch and unmerge separate dates and times from a single string composed of a single unix timestamp  (start-end)
function d4events_fetch_datetime($postid) {

	$start = get_post_meta($postid,'d4events_start',true);
	$end = get_post_meta($postid,'d4events_end',true);

	if ( ($start == '') || ($end == '') ) {
		#$meta = date("m/d/Y");
		$meta = 'not available';
	} 

	else {

		$meta_array['d4events_start_date'] = date('m/d/Y',$start);
		$meta_array['d4events_end_date'] = date('m/d/Y',$end);

		$meta_array['d4events_start_time'] = date('g:ia',$start);
		$meta_array['d4events_end_time'] = date('g:ia',$end);

	}

	return $meta_array;
}