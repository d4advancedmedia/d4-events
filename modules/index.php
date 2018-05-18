<?php

// Query events from the database
	include 'fetch_d4events/index.php';

// Migration script to update old d4events to use modern version
	include 'd4events_install/index.php';

// temporary - event attachment functions
	include 'd4events_attachments/index.php';