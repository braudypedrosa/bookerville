<?php 
require BOOKERVILLE_DIR . '/includes/class-gamajo-template-loader.php';
require BOOKERVILLE_DIR . '/includes/class-bookerville-template-loader.php';



// Add Shortcode
function custom_shortcode() {
    
    // _bookerville_fetch_property_details(9972);
    // _bookerville_fetch_all_properties();

    echo 'Listing Count: '. get_option('listing_count');
    echo '<br>';
    echo '<br>';
    echo 'Saved Listings: ' . get_option('saved_listings');

    // _bookerville_fetch_all_properties();
    // _bookerville_initialize_listings();


    
}
add_shortcode( 'test', 'custom_shortcode' );


// Search Widget Shortcode
function _bookerville_search_widget_function() {

    $templates = new BV_Template_Loader;
    
    ob_start();
    $templates->get_template_part( 'content', 'form' );
    return ob_get_clean();
    
}
add_shortcode( 'search_widget', '_bookerville_search_widget_function' );


?>