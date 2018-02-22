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
add_action( 'init', 'd4events_posttype', 0 );
