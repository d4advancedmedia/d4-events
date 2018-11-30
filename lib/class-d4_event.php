<?php

class d4_event {
	/*
	----- construct the single event object using the WP_Post Object, event dates, etc. 
	*/

	public function __construct($post) {
		$this->ID = $post->ID;
		$this->title = $post->post_title;
		$this->content = $post->post_content;

		$start = get_post_meta($this->ID,'d4events_start',true);
		$end = get_post_meta($this->ID,'d4events_end',true);
		$this->set_event_dates($start,$end);
		$this->set_multiday_event();
	}

	public function set_event_dates($start,$end) {
		
		$start_dt = new DateTime();
		$start_dt->setTimestamp($start);

		$end_dt = new DateTime();
		$end_dt->setTimestamp($end);

		$this->event_dates = array(
				'start'		=> $start_dt,
				'end'		=> $end_dt,
		);
	}

	public function set_multiday_event() {
		$interval = $this->event_dates['start']->diff($this->event_dates['end']);
		$this->multiday_event = $interval->format('%a');
	}

	public function update_date($current_loop_dt) {
		$start_dt = new DateTime();
		$start_dt->setTimestamp($start);

		$end_dt = new DateTime();
		$end_dt->setTimestamp($end);

		$this->event_dates['start'] = $current_loop_dt;
		$this->event_dates['end'] = $current_loop_dt;
	}

	public function set_files() {

		$file_array = array();
		
		$multicount = 100;
		
		for ($k = 1 ; $k <= $multicount; $k++) {			
			$meta = get_post_meta($this->ID, 'd4events_file_'.$k, true);
			if ($meta[1] != '') {				
				$file_type = $meta[0];
				if ($meta[2] != '') {
					$file_name = $meta[2];
				} else {
					$file_name = end((explode('/', rtrim($meta[1], '/'))));
				}
				if ( isset($meta[1]['extension']) ) {
					$file_class = 'fileclass_'.$meta[1]['extension'];
				}
				$file_link = '<a href="'.$meta[1].'" class="events_files '.$file_class.'" target="_blank" style="display:block">'.$file_name.'</a>';
				
				$file_array[] = array(
						'type' => $file_type,
						'name' => $file_name,
						'link' => $file_link,
				);
			}
			else {
				//if the meta returns blank, you have added every file and this loop has finished its job
				break;
			}			
		}

		$this->files = $file_array;
	}


	public function output_files($file_list) {
		$files = $file_list;
		$file_array = $this->files;
		//This is the array of file categories listed in the shortcode, so that the files can be sorted by category (ex: Agenda, Meeting, Minutes)
		$shortcode_filecats = explode(',',$files);

		if ( isset($files) && ($files != '') && (!empty($file_array)) ) {

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
}