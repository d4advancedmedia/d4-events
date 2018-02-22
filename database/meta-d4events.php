<?php



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
        'placeholder' => '00:00am',
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
        'placeholder' => '00:00am',
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
        'label'=> 'Location Description',
        'desc'  => 'e.g., Enter through the narrow gate',
        'id'    => $prefix.'location_desc',
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

    //convert all of the posted dates to timestamps for verification
    $start_date_stamp = strtotime($_POST['d4events_start_date']);
	$start_time_stamp = strtotime($_POST['d4events_start_time']);
	$end_date_stamp = strtotime($_POST['d4events_end_date']);
	$end_time_stamp = strtotime($_POST['d4events_end_time']);

	/*print('<pre>');
	print_r($_POST);
	print('</pre>');*/

	//verify that all of the timestamps are valid timestamps. if not, set them to the current date at noon
	if(!$start_date_stamp) {
		$start_date = date("m/d/Y");
	} else {
		$start_date = $_POST['d4events_start_date'];
	}

	if(!$start_time_stamp) {
		$start_time = '12:00';
	} else {
		$start_time = $_POST['d4events_start_time'];
	}

	if(!$end_date_stamp) {
		$end_date = date("m/d/Y");
	} else {
		$end_date = $_POST['d4events_end_date'];
	}

	if(!$end_time_stamp) {
		$end_time = '12:00';
	} else {
		$end_time = $_POST['d4events_end_time'];
	}


	//now make sure that the event start is before the event end. If its earlier, set it to the start time.
	$event_start = strtotime("$start_date $start_time");
	$event_end = strtotime("$end_date $end_time");

	if($event_end < $event_start) {
		$event_end = $event_start;
	}


	//make sure that if repeating or frequency are empty, that no repeating data is saved.
	if ( ($_POST['d4events_repeating'] == '') || ($_POST['d4events_frequency'] == '') ) {

		$_POST['d4events_repeating'] = '';
		$_POST['d4events_repeat_end_date'] = '';
		$_POST['d4events_frequency'] = '';
		$_POST['d4events_repeat_days'] = '';
		$_POST['d4events_monthly_repeat_by'] = '';
		$_POST['d4events_blackout_dates'] = '';
	}

	//reset monthly repeat by if using repeat days (can only be one or the other)
	if ($_POST['d4events_repeat_days'] != '') {
		$_POST['d4events_monthly_repeat_by'] = '';
	}

	//reset repeat days if monthly repeat by is set (can only be one or the other)
	if ($_POST['d4events_monthly_repeat_by'] != '') {
		$_POST['d4events_repeat_days'] = '';
	}

	//set a "never" value if repeat end date is blank
	if ( ($_POST['d4events_repeating'] != '') && ($_POST['d4events_repeat_end_date'] == '') ) {
		$_POST['d4events_repeat_end_date'] = 'Never';
	}


	//End Validation Section//


	update_post_meta( $post_id, 'd4events_start', $event_start);
	update_post_meta( $post_id, 'd4events_end', $event_end);
	update_post_meta($post_id,'d4events_repeat_end_date',strtotime($_POST['d4events_repeat_end_date']));

	//Don't try to save the event dates or times, those were already saved before the loop
    $dont_save = array('d4events_start_date','d4events_start_time','d4events_end_time','d4events_end_date','d4events_repeat_end_date');    

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
