<?php ob_start();
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

define('REQUEST_URL_PROPERTY_SUMMARY', 'https://www.bookerville.com/API-PropertySummary?');
define('REQUEST_URL_PROPERTY_DETAILS', 'https://www.bookerville.com/API-PropertyDetails?');
define('REQUEST_URL_PROPERTY_SEARCH', 'https://www.bookerville.com/API-Multi-Property-Availability-Search?');

// get all properties from bookerville
function _bookerville_fetch_all_properties($initialized = false){
    
    $response = _bookerville_get(REQUEST_URL_PROPERTY_SUMMARY);
    $xml_response  = simplexml_load_string($response);

    $properties = array();
    $proterty_ids = array();

    
    foreach($xml_response->children() as $property) {
        $property_id = $property['property_id'];
        
        $property_details = array(
            "property_id" => strval($property['property_id']),
            "account_id" => strval($property['bkvAccountId']),
            "manager_first_name" =>  strval($property['managerFirstName']),
            "manager_last_name" => strval($property['managerLastName']),
            "offline" => strval($property['offLine']),
            "last_update" => strval($property['last_update'])
        );

        array_push($properties, $property_details);
        array_push($proterty_ids, $property['property_id']);
    }

    // update stored value
    update_option('listing_count', count($properties));
    update_option('saved_listings', json_encode($properties));
    
}


// get property details from the API by property ID
function _bookerville_fetch_property_details_from_api($property_id) {

    $parameter = '&bkvPropertyId='.$property_id;
    $response = _bookerville_get(REQUEST_URL_PROPERTY_DETAILS, $parameter);
    $xml_response = simplexml_load_string($response);

    $property_details = array(
        "property_name" => strval($xml_response->PropertyName),
        "address1" => strval($xml_response->Address->Address1),
        "city" => strval($xml_response->Address->City),
        "state" => strval($xml_response->Address->State),
        "zipcode" => strval($xml_response->Address->ZipCode),
        "country" => strval($xml_response->Address->Country),
        "latlong" => strval($xml_response->Address->LatitudeLongitude),
        "maximum_occupancy" => strval($xml_response->Details->MaximumOccupancy),
        "check_in" => strval($xml_response->Details->CheckIn),
        "check_out" => strval($xml_response->Details->CheckOut),
        "cover_photo" => strval($xml_response->Photos->Photo->URL)
    );

    return $property_details;
}

// get property details from custom post type by propery ID
function _bookerville_fetch_property_details($property_id) {
    global $wpdb;

    $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'property_id' AND meta_value='".$property_id."'";

    $result = $wpdb->get_results($sql,ARRAY_A);
    $post_id = ($result[0]['post_id']) ? $result[0]['post_id'] : 'Property not found!';

    return $post_id;
}

// create new bookerville listing entry in wordpress
function _bookerville_insert_new_listing($listing_data){

    global $wpdb;

    $sql = "SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'property_id' AND meta_value='".$listing_data['property_id']."'";

    $result = $wpdb->get_results($sql,ARRAY_A);
    $post_id = $result[0]['post_id'];

    if($post_id == null) {
        $post_id = wp_insert_post(array(
            'post_title'=> $listing_data['property_name'], 
            'post_type'=> 'bookerville_listing',
            'post_status'=> 'publish'
        ));
    } else {
        if(!$listing_data['offline'] == "N") {
            wp_update_post(array(
                'ID' => $post_id,
                'post_status'=> 'draft'
            ));
        }
    }

    update_post_meta($post_id, 'property_id', $listing_data['property_id']);

    $address = $listing_data['address1'] .' '. $listing_data['city'] .', '. $listing_data['state'] .' '. $listing_data['zipcode'];
    // $map_address = explode(',', $listing_data['latlong']);

    $image = media_sideload_image( $listing_data['cover_photo'], $post_id, $address,'id' );
    set_post_thumbnail( $post_id, $image );

    update_field('address', $address, $post_id);
    update_field('maximum_occupancy', $listing_data['maximum_occupancy'], $post_id);
    update_field('check_in', $listing_data['check_in'], $post_id);
    update_field('check_out', $listing_data['check_out'], $post_id);
}

// GET request
function _bookerville_get($request_url, $parameter = '') {
    $key = get_option('_bookerville_secret_key');

    // echo $request_url.'s3cr3tK3y='.$key.''.$parameter;

    $response = wp_remote_get($request_url.'s3cr3tK3y='.$key.''.$parameter, [ 'timeout' => 45 ]);
    $responseBody = wp_remote_retrieve_body( $response );

    return $responseBody;
}

function _bookerville_initialize_listings() {
	$listings = json_decode(get_option('saved_listings', true));
    $count = 0;

    foreach($listings as $listing) {
        // if($count <= 0) {
            $listing_details = array(
                "property_id" => $listing->property_id,
                "offline" => $listing->offline,
            );

            $listing_specifics = _bookerville_fetch_property_details_from_api($listing->property_id);

            $listing_details = array_merge($listing_details, $listing_specifics);

            // var_dump($listing_details);
            _bookerville_insert_new_listing($listing_details);
            $listing_details = array();
        //     $count++;
        // }
    }
}
add_action( 'initialize_listings','_bookerville_initialize_listings' );


function enqueue_required_assets() { 
    wp_enqueue_style( 'bookerville-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css');
    wp_enqueue_style( 'bookerville-style', BOOKERVILLE_URL. 'assets/css/style.css' );
    wp_enqueue_style( 'bookerville-slick-style', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');

    wp_enqueue_script( 'bookerville-jquery' , 'https://code.jquery.com/jquery-3.6.3.slim.min.js');
    wp_enqueue_script( 'bookerville-slick-script', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'));
    wp_enqueue_script( 'bookerville-custom-script', BOOKERVILLE_URL. 'assets/js/custom.js', array('jquery'));
  }
  add_action( 'wp_enqueue_scripts', 'enqueue_required_assets' );

function custom_rewrite_rule() {
    add_rewrite_rule('^nutrition/([^/]*)/([^/]*)/?','url/to/my/script.php?food=$1&variety=$2','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);