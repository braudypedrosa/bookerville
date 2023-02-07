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
add_shortcode( 'bookerville_search_widget', '_bookerville_search_widget_function' );


// Properties Slider Widget
function _bookerville_display_properties_function( $atts ) {

    $templates = new BV_Template_Loader;

	// Attributes
	$a = shortcode_atts( array(
        'slidesToShow' => 3,
        'slidesToDisplay' => 6,
        'type' => 'slider'
    ), $atts );

    $args = array(
        'post_type' => 'bookerville_listing', 
        'posts_per_page'   => $a['slidesToDisplay'],
		'order' => 'ASC',
    );

    $generate_id = rand(0000,9999);

    $settings = '{"slidesToShow": "'.$a['slidesToShow'].'"}';

    ob_start();
    if($a['type'] == 'slider') {
        echo "<div id='bookerville_slider_".$generate_id."' data-settings='".json_encode($settings)."' data-target='".$generate_id."' class='bookerville_property_slider'>";

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $templates->get_template_part( 'property', 'slide' );
                
            }
        }

        echo '</div>';
    } else if($a['type'] == 'grid') {
        echo "<div id='bookerville_grid_".$generate_id."' data-target='".$generate_id."' class='bookerville_property_grid_container'>";

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                
                $templates->get_template_part( 'property', 'grid' );
            }
        }

        echo '</div>';
    } 

    wp_reset_postdata();
    return ob_get_clean();

}
add_shortcode( 'bookerville_display_properties', '_bookerville_display_properties_function' );


?>