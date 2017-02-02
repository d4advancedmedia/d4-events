<?php

// Register Meeting Events Post Type
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
	$args = array(
		'label'                 => __( $d4events_posttype_singular, 'd4events' ),
		'description'           => __( 'd4events', 'd4events' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions','custom-fields' ),
		'taxonomies'            => array( 'category', 'post_tag', 'd4events_category', 'd4events_tag' ),
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

/*------------------------------------------------------
----------------------Admin Elements--------------------
------------------------------------------------------*/

function d4_events_timezone_list($postid) {
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
function add_d4events_meta_box() {
    add_meta_box(
        'd4events_meta_box', // $id
        'Event Details', // $title 
        'show_d4events_meta_box', // $callback
        'd4events', // $post_type
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_d4events_meta_box');

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
        'label'=> 'Files',
        'desc'  => 'File URL',
        'id'    => $prefix.'file_',
        'number' => 1,
        'type'  => 'multipass',
        'multipass_opts' => array('Agenda','Minutes','Image','Other')
    ),
);

function multipass_counter() {

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
function show_d4events_meta_box() {
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

        // begin a table row with
        echo '<div class="events-meta-row row-'.$field['id'].'">
                <label for="'.$field['id'].'">'.$field['label'].'</label>
                <div class="event-meta-input">';
                switch($field['type']) {
                    // case items will go here
	                    // date
						case 'date':
							if ($meta == '') {
								$meta = date("m/d/Y");
							}
							echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.date("M d, Y", strtotime($meta)).'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
						break;

						// time
						case 'time':
							//if not "all day", format the time correctly
							if (strtolower($meta) != 'all day') {
								$meta = date("g:ia", strtotime($meta));
							} else {
								$meta = 'All Day';
							}
						    echo '<input type="text" placeholder="'.$field['placeholder'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
						        <br /><span class="description">'.$field['desc'].'</span>';
						break;

						// timezone
						case 'timezone':
							$postid = $post->ID;
						    echo d4_events_timezone_list($postid);
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
    						$multicount = multipass_counter();

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
function save_d4events_meta($post_id) {
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

	if ( ($_POST['d4events_start_date'] == '') && ($_POST['d4events_end_date'] != '')) {
		$_POST['d4events_start_date'] = $_POST['d4events_end_date'];

		#$_POST('events_errors') .= 'The End Date is a required field and will default to the current date if left blank.';
	}

	if ( ($_POST['d4events_start_date'] != '') && ($_POST['d4events_end_date'] == '')) {
		$_POST['d4events_end_date'] = $_POST['d4events_start_date'];

		#$_POST('events_errors') .= 'The Start Date is a required field and will default to the current date if left blank.';
	}

	if ( (strtotime($_POST['d4events_start_date'])) > (strtotime($_POST['d4events_end_date'])) ) {
		$_POST['d4events_end_date'] = $_POST['d4events_start_date'];

		#$_POST('events_errors') .= 'The Start Date cannot be later than the End Date.';
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

    // loop through fields and save the data
    foreach ($d4events_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];       

        if ($field['id'] == 'd4events_start_date') {
        	update_post_meta( $post_id, 'd4events_start_date', date("Y-m-d", strtotime($_POST['d4events_start_date'])) );
        }
        elseif ($field['id'] == 'd4events_end_date') {
        	update_post_meta( $post_id, 'd4events_end_date', date("Y-m-d", strtotime($_POST['d4events_end_date'])) );
        }       
        elseif ($field['type'] == 'multipass') {

        	//clear out all existing metadata to ensure that keys increment by 1, starting with 1
        	$multicount = multipass_counter();
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
        }

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
    } // end foreach
}
add_action('save_post', 'save_d4events_meta');


/*------------------------------------------------------
----------------------Front End-------------------------
------------------------------------------------------*/

function event_output() {
	$posttitle = '<h5 class="cal-event-title">'.get_the_title().'</h5>';

	if ($attr['link'] == '') {
		$linkopen = '<a href="'.get_the_permalink().'">';
	} else {
		$linkopen = $attr['link'];
	}
	$linkclose = "</a>";

	//Render output
	
	$output .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
	$output .= $linkopen;
	$output .= $posttitle;
	$output .= $linkclose;
	$output .= '<div class="clearfix"></div></div>';
}

function get_events($event_date,$category,$events_query) {

	$day_of_the_week = date('l', strtotime($event_date));
	$day_of_the_month = date('j', strtotime($event_date));
	$nth_weekday_of_every_month = ceil($day_of_the_month / 7);


	while ( $events_query->have_posts() ) { $events_query->the_post();		
			

		$posttitle = '<h5 class="cal-event-title">'.get_the_title().'</h5>';

		$remove_link = get_post_meta( $event_id, 'd4events_remove_link', true);
		if ($remove_link != 'on') {
			$linkopen = '<a href="'.get_the_permalink().'">';
			$linkclose = "</a>";
		} else {
			$linkopen = '';
			$linkclose = '';
		}

		$event_id = get_the_id();

		$start_date = strtotime(get_post_meta( $event_id, 'd4events_start_date', true));
		$end_date = strtotime(get_post_meta( $event_id, 'd4events_end_date', true ));

		$event_duration = date('j', $end_date) - date('j', $start_date);
		
		$event_date2 = strtotime($event_date);	

		$repeating = get_post_meta( $event_id, 'd4events_repeating', true );
		$repeating_event = false;

		if ($repeating != '') {
			$weekly_repeat_days = get_post_meta( $event_id, 'd4events_repeat_days', true );
			if ($weekly_repeat_days != '') {
				if (in_array($day_of_the_week, $weekly_repeat_days)) {
					$repeating_event = true;
				}
			}

			$repeat_interval = get_post_meta( $event_id, 'd4events_repeat_interval', true );
			$end_day_of_the_month = $event_duration + $repeat_interval;

			$month_repeat_by = get_post_meta( $event_id, 'd4events_monthly_repeat_by', true );

			if ($month_repeat_by == 'day_of_the_week') {
				$month_weekday_repeat = get_post_meta( $event_id, 'd4events_month_weekday_repeat', true );
				if (($month_weekday_repeat == $day_of_the_week) && ($repeat_interval == $nth_weekday_of_every_month)) {
					$repeating_event = true;
				}
			}
			else {
				if (($repeat_interval <= $day_of_the_month) && ($day_of_the_month <= $end_day_of_the_month)) {
					$repeating_event = true;
				}
			}
		}
		
		if ((($event_date2 >= $start_date) && ($event_date2 <= $end_date)) || ($repeating_event == true)) {

			//Render output
			
			$output .= '<div class="cal-event-wrapper '.$wrapperclass.'">';
			$output .= $linkopen;
			$output .= $posttitle;
			$output .= $linkclose;
			$output .= '<div class="clearfix"></div></div>';		

		}
	}
	return $output;
	wp_reset_postdata();
}

/* draws a calendar */
function d4events_draw_calendar($month,$year,$category,$exclude_category){
	if ($month == '') {
		$month = date("n");
	}
	if ($year == '') {
		$year = date("Y");
	}
	
	$dateObj   = DateTime::createFromFormat('!m', $month);
	$monthName = $dateObj->format('F'); // March
	
	/* draw table */
	$calendar = '<div data-month="'.$month.'" data-year="'.$year.'" data-category="'.$category.'" data-exclude_category="'.$exclude_category.'" id="d4-event-calendar"><div class="cal-change-button cal-prev" data-change="cal-prev"></div><div class="cal-change-button cal-next" data-change="cal-next"></div><h2>'.$monthName.' '.$year.'</h2><table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	# get all events, place in array to send to get_events()

	if ($category != '') {
		$event_cats_array = array(
								'taxonomy' => 'd4events_category',
								'field'    => 'name',
								'terms'    => $category,
							);
	}
	if ($exclude_category != '') {
		$event_exclude_cats_array = array(
										'taxonomy' => 'd4events_category',
										'field'    => 'term_id',
										'terms'    => $exclude_category,
										'operator' => 'NOT IN',
									);
	}
	$tax_query = array(
						'relation' => 'AND',
						$event_cats_array,
						$event_exclude_cats_array,
	);

	$events_args = array (
		'post_type' => 'd4events',
		//'category_name'	=> $category,
		//'category__not_in' => $exclude_category,
		'tax_query'			=>  $tax_query,
	);
	$events_query = new WP_Query($events_args);

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	$month_has_events = false;
	
	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		
		if (strlen($list_day) < 2) {
			$fixed_day = '0'.$list_day;	
		} else {
			$fixed_day = $list_day;
		}
		if (strlen($month) < 2) {
			$fixed_month = '0'.$month;	
		} else {
			$fixed_month = $month;
		}
		$fulldate = $fixed_month.'/'.$fixed_day.'/'.$year;
		//$calendar .= $fulldate;
		$day_events = get_events($fulldate,$category,$events_query);

		if (!empty($day_events)) {
			$has_events = ' has-events';
			$month_has_events = true;
		}

		$calendar.= '<td class="calendar-day '.$has_events.'"><div class="day-internal">';
		unset($has_events);
		/* add in the day number */
		$calendar.= '<div class="day-number">'.$list_day.'</div>';
		#calendar.= str_repeat('<p> </p>',2);		
		$calendar .= $day_events;			
		$calendar.= '</div></td>';

		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table></div>';

	/* Display No Meeting Events Notice */
	if ($month_has_events == false) {
		$calendar .= '<div class="no-events">There are no scheduled events for this month</div>';
	}
	
	/* all done, return result */
	return $calendar;
}


/* draws an annual agenda */
function d4events_draw_agenda($month,$year,$category,$exclude_category){
	$year = date("Y");
	for ($i = 1; $i <= 12; $i++) {
		$month = date('n', mktime(0, 0, 0, $i, 1, $year));
		$month_events .= d4events_draw_calendar($month,$year,$category,$exclude_category);
	}
	return $month_events;
}

function get_list_events($links,$files,$thumbnail_size) {

	$link_open = '';
	$link_close = '';
	$readmore = '';

	if ($links == 'true') {
		$link_open = '<a href="'.get_the_permalink().'">';	
		$link_close = '</a>';
		$readmore = '<a class="events_list-readmore" href="'.get_the_permalink().'">Read More</a>';
	}

	$file_cluster = d4events_get_files($files);

	$post_thumbnail = '';
	if (has_post_thumbnail()) {
		$post_thumbnail = '<div class="events_list-thumb">'.$link_open.get_the_post_thumbnail(get_the_ID(),$thumbnail_size).$link_close.'</div>';
		$has_image = ' event-has-image';
	}

	$event_content .= '<div class="events_list-single'.$has_image.'">';
	$event_content .= $post_thumbnail;	
	$event_content .= '<h5 class="cal-event-title">'.$link_open.get_the_title().$link_close.'<span class="events_list-datetime"><span class="events_list-date">'.date("m/d/Y", strtotime(get_post_meta( get_the_ID(), 'd4events_start_date', true ))).'</span><span class="events_list-time">'.get_post_meta( get_the_ID(), 'd4events_start_time', true ).'</span></span></h5>';
	$event_content .= '<div class="events_list-content"><p class="events_list-description">'.get_the_excerpt().'</p>';
	$event_content .= $readmore.'<div class="clearfix"></div>';
	$event_content .= $file_cluster;
	$event_content .= '<div class="clearfix"></div></div></div>';

	return $event_content;
}

function d4events_get_files($files) {


	$file_array = array();
	//determine number of multipass					
	$multicount = multipass_counter();
	
	for ($k = 1 ; $k <= $multicount; $k++) {			
		$meta = get_post_meta(get_the_ID(), 'd4events_file_'.$k, true);
		if ($meta[1] != '') {				
			$file_type = $meta[0];
			if ($meta[2] != '') {
				$file_name = $meta[2];
			} else {
				$file_name = end((explode('/', rtrim($meta[1], '/'))));
			}
			$file_class = 'fileclass_'.pathinfo($meta[1])['extension'];
			$file_link = '<a href="'.$meta[1].'" class="events_files '.$file_class.'" target="_blank">'.$file_name.'</a>';
			
			$file_array[] = array(
					'type' => $file_type,
					'name' => $file_name,
					'link' => $file_link,
			);
		}
		
	}

	//This is the array of file categories listed in the shortcode, so that the files can be sorted by category (ex: Agenda, Meeting, Minutes)
	$shortcode_filecats = explode(',',$files);

	if ( isset($files) && ($files != '') && ($multicount != 0) && (!empty($file_array)) ) {

		$file_cluster = '';
		$filetype_title = '';
		
		$file_cluster .= '<div class="files-wrapper">';
		foreach ($shortcode_filecats as $type) {	
			$i = 1;
			$file_cluster .= '<div class="event-filegroup">';					
			foreach ($file_array as $file) {
				if ( ($type == strtolower($file['type'])) && ($file['link'] != '') ) {
					if ($filetype_title == $file['type']) {									
						$file_cluster .= $file['link'];	
					} else {
						$file_cluster .= '<h6 class="event-filetype">'.$file['type'].'</h6>';
						$file_cluster .= $file['link'];
						$filetype_title = $file['type'];
					}
					
				}
				$i++;
			}
			$file_cluster .= '</div>';
		}
		$file_cluster .= '</div>';
	}

	return $file_cluster;

}



add_action( 'wp_ajax_cal_change', 'd4_ajax_cal_change' );
add_action( 'wp_ajax_nopriv_cal_change', 'd4_ajax_cal_change' );


function d4_ajax_cal_change() {
    // Handle request then generate response using WP_Ajax_Response
	if(isset($_POST['month']))
		{
		    $month = $_POST['month'];
		}
	if(isset($_POST['year']))
		{
		    $year = $_POST['year'];
		}
	if(isset($_POST['category']))
		{
		    $category = $_POST['category'];
		}
	if(isset($_POST['exclude_category']))
		{
		    $exclude_category = $_POST['exclude_category'];
		}	
	if(isset($_POST['change']))
		{
		    $change = $_POST['change'];		    
		}
	if ($change == "cal-prev") {
		$nextmonth = $month-'1';			
	}
	if ($change == "cal-next") {
		$nextmonth = $month+'1';	
	}
	if ($nextmonth == '13') {
			$nextmonth ='1';
			$nextyear = $year+'1';
	}
	elseif ($nextmonth == '0') {
			$nextmonth ='12';
			$nextyear = $year-'1';
	}
	else $nextyear = $year;		
    echo d4events_draw_calendar($nextmonth,$nextyear,$category,$exclude_category);
    die();
}

//Load the single event template
function get_d4events_template($single_template) {
     global $post;

     if ($post->post_type == 'd4events') {
      	$single_template = dirname( __FILE__ ) . '/single-event.php';
     }  
     return $single_template;
}
add_filter( 'single_template', 'get_d4events_template' );

function d4events_before_main_content() {
	do_action('d4events_before_main_content');
}

function d4events_after_main_content() {
	do_action('d4events_after_main_content');
}

add_action('d4events_before_main_content', 'd4_events_wrapper_start', 10);
add_action('d4events_after_main_content', 'd4_events_wrapper_end', 10);