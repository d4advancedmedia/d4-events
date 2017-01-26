<?php

/*
--- Add API credentials at https://console.developers.google.com/apis/credentials ---

1. Select "D4 Clients" project
2. Select Browser Key
3. Paste the URL of the site into the referrers list. A live URL will need to be added at site launch as well.
4. API Key should remain the same as this app is used on multiple sites with the same API key.

*/

$d4events_apikey = 'AIzaSyCyYncL5imWnSmhF1PXk5NckeM4dObSZ4k';

//Set the posttype labels and icon. Change these if the plugin is conflicting with another events plugin
$d4events_posttype_singular = "Event";
$d4events_posttype_plural = "Events";
$d4events_icon = 'dashicons-calendar-alt';


/* --- Set the theme wrapper --- */
function d4_events_wrapper_start() {

echo '<section id="content">';
get_template_part( 'inc/chunk' , 'title' );

  echo '<div class="page-wrapper"><main id="main-content" class="clearfix" role="main">';
}

function d4_events_wrapper_end() {
  echo '</main></div></section>';
}