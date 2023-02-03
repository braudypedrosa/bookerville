<?php
/*
Plugin Name: Bookerville
Plugin URI: 
Description: Lightweight plugin for getting Bookerville 
Author: Braudy Pedrosa
Version: 1.0
Author URI: https://www.buildupbookings.com/
*/

// avoid direct access
if ( !function_exists('add_filter') ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define('BOOKERVILLE_VERSION', "1.0"); 
define('BOOKERVILLE_DIR', plugin_dir_path( __FILE__ )); 
define('BOOKERVILLE_URL', plugin_dir_url( __FILE__ )); 

define( 'BOOKERVILLE_ACF_PATH', BOOKERVILLE_DIR . '/includes/acf/' );
define( 'BOOKERVILLE_ACF_URL', BOOKERVILLE_URL . '/includes/acf/' );

include_once(BOOKERVILLE_ACF_PATH.'acf.php' );
include_once(BOOKERVILLE_DIR.'/includes/libraries/navz-photo-gallery/navz-photo-gallery.php');

include_once(BOOKERVILLE_DIR.'functions.php');
include_once(BOOKERVILLE_DIR.'shortcodes.php');
include_once(BOOKERVILLE_DIR.'custom-urls.php');



add_filter('acf/settings/url', 'acf_settings_url');

function acf_settings_url( $url ) {
    return BOOKERVILLE_ACF_URL;
}


// add_filter('acf/settings/show_admin', '__return_false');

// save fields as JSON
add_filter('acf/settings/save_json', '_bookerville_acf_json_save_point');
 
function _bookerville_acf_json_save_point( $path ) {
    $path = BOOKERVILLE_DIR . '/acf-json';
    return $path;
}


// load fields as JSON and sync support
add_filter('acf/settings/load_json', '_bookerville_acf_json_load_point');

function _bookerville_acf_json_load_point( $paths ) {
    unset($paths[0]);
    $paths[] = BOOKERVILLE_DIR . '/acf-json';
    return $paths;
}


// initialize sub menu for settings
function _bookerville_register_submenu_page(){

	add_submenu_page(
		'edit.php?post_type=bookerville_listing',
		'Settings',
		'Settings',
		'manage_options',
		'settings',
		'_bookerville_listing_load_template'
	);
}

add_action( 'admin_menu', '_bookerville_register_submenu_page' );


// load template
function _bookerville_listing_load_template(){

	$page = isset($_GET['page']) ? $_GET['page'] : "";

	include_once(BOOKERVILLE_DIR.'/'.$page.'.php');
}


// initialize plugin post type
function _bookerville_post_types() {

	
	register_post_type( 'bookerville_listing',
	
		array('labels' => array(
				'name' => __('Bookerville Listings', 'jointswp'), /* This is the Title of the Group */
				'singular_name' => __('Bookerville Listing', 'jointswp'), /* This is the individual type */
				'all_items' => __('All Bookerville Listings', 'jointswp'), /* the all items menu item */
				'add_new' => __('Add New Bookerville Listing', 'jointswp'), /* The add new menu item */
				'add_new_item' => __('Add New Bookerville Listing', 'jointswp'), /* Add New Display Title */
				'edit' => __( 'Edit', 'jointswp' ), /* Edit Dialog */
				'edit_item' => __('Edit', 'jointswp'), /* Edit Display Title */
				'new_item' => __('New Bookerville Listing', 'jointswp'), /* New Display Title */
				'view_item' => __('View', 'jointswp'), /* View Display Title */
				'search_items' => __('Search', 'jointswp'), /* Search Custom Type Title */
				'not_found' =>  __('Nothing found in the Database.', 'jointswp'), /* This displays if there are no entries yet */
				'not_found_in_trash' => __('Nothing found in Trash', 'jointswp'), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
			), /* end of arrays */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' => 'dashicons-store', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
			'rewrite'	=> array( 'slug' => 'bookerville_listing', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'true', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
			// This is where we add taxonomies to our CPT
			'taxonomies'          => array( 'category' )

		) /* end of options */

	); /* end of register post type */

}
add_action( 'init', '_bookerville_post_types');



register_activation_hook(__FILE__,function(){
	// set defaults
	update_option('listing_count', 0);
	update_option('saved_listings', '');
});


register_deactivation_hook( __FILE__, function(){

});
 
