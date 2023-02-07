<?php
get_header();

include_once(BOOKERVILLE_DIR.'functions.php');


$startDate = (isset($_GET['startDate'])) ? $_GET['startDate'] : '';
$endDate = (isset($_GET['endDate'])) ? $_GET['endDate'] : '';
$numAdults = (isset($_GET['numAdults'])) ? $_GET['numAdults'] : 0;
$numChildren = (isset($_GET['numChildren'])) ? $_GET['numChildren'] : 0;

$accountID = get_option('_bookerville_account_id');
$key = get_option('_bookerville_secret_key');

$xml_response = array();
$error_msg = '';


$startDateReformat = date('Y-m-d', strtotime($startDate));
$endDateReformat = date('Y-m-d', strtotime($endDate));

$request_url = REQUEST_URL_PROPERTY_SEARCH.'s3cr3tK3y='.$key;
$xml = '<xml version="1.0" encoding="utf-8">'.
'<request>'.
'<bkvAccountId>'.$accountID.'</bkvAccountId>'.
'<startDate>'.$startDateReformat.'</startDate>'.
'<endDate>'.$endDateReformat.'</endDate>'.
'<numAdults>'.$numAdults.'</numAdults>'.
'<numChildren>'.$numChildren.'</numChildren>'.
'<sendResultsAs>xml</sendResultsAs>'.
'<photoFullSize>Y</photoFullSize>'.
'<sortField>lastBooked</sortField>'.
'<sortOrder>ASC</sortOrder>'.
'</request>'.
'</xml>';

$response = wp_remote_post( 
    $request_url, 
    array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
		'headers' => array("Content-type" => "application/xml"),
        'body' => $xml,
        'sslverify' => false
    )
);

$responseBody = wp_remote_retrieve_body( $response );
$xml_response = simplexml_load_string($responseBody); 

?>



<div class="bookerville_search_results">

    <form role="search" method="get" id="searchform" class="searchform" action="#">
    <div class="bookerville_search_widget">
        <div class="bookerville_input startDate_input">
            <label for="startDate">Start Date</label>
            <input type="date" id="startDate" name="startDate" value="<?= $startDate; ?>" placeholder="Start Date">
        </div>
        <div class="bookerville_input endDate_input">
            <label for="startDate">End Date</label>
            <input type="date" id="endDate" value="<?= $endDate; ?>" name="endDate">
        </div>
        <div class="bookerville_input numAdults_input">
            <label for="startDate">Number of Adults</label>
            <input type="number" id="numAdults" value="<?= $numAdults; ?>" name="numAdults">
        </div>
        <div class="bookerville_input numChildren_input">
            <label for="startDate">Number of Children</label>
            <input type="number" id="numChildren" value="<?= $numChildren; ?>" name="numChildren">
        </div>
        <div class="bookerville_input numChildren_input">
            <input type="submit" id="searchsubmit" value="Submit">
        </div>
    </div>
    </form>



<?php if(intval($xml_response->children()->count()) > 0) { ?>

<div class="bookerville_results">

<?php 
    foreach($xml_response->children() as $result) {
    
    $propertyName = $result->propertyDisplayName;
    $propertyTotal = $result->bookingPriceFrom;

    $bookingTargetURL = $result->bookingTargetURL;
    $url_components = parse_url($bookingTargetURL);
    parse_str($url_components['query'], $params);

    $property_ID = $params['property'];

    $post_ID = _bookerville_fetch_property_details($property_ID);

    $bed = get_field('bed', $post_ID) ? get_field('bed', $post_ID) : 0;
    $bath = get_field('bath', $post_ID) ? get_field('bath', $post_ID) : 0;
    $sleeps = get_field('maximum_occupancy', $post_ID) ? get_field('maximum_occupancy', $post_ID) : 0;
    $propertyPhoto = get_the_post_thumbnail_url($post_ID, 'full');

    }
    
?>

<div class="bookerville_result_item">
    <img class="property_image" src="<?= ($propertyPhoto != '') ? $propertyPhoto : BOOKERVILLE_DIR.'assets/images/property_placeholder.jpg'; ?>">
    <div class="property_details">
    <a class="bookerville_btn" href="<?= get_the_permalink($post_ID); ?>"><h3 class="property_name"><?= $propertyName; ?></h3></a>
        <p class="property_start_price">Starting at <span><?= $propertyTotal; ?></span></p>
        <div class="property_meta">
            <i class="fa-solid fa-bed"><?= $bed; ?></i>
            <i class="fa-solid fa-bath"><?= $bath; ?></i>
            <i class="fa-solid fa-user-group"><?= $sleeps; ?></i>
        </div>
    </div>
</div>

</div>

<?php } else { 

    if($endDateReformat < $startDateReformat) {
        $error_msg = "Invalid checkout date!";
    } else {
        $error_msg = "No properties are available for those dates.";
    }
    
?> 
    
<div class="bookerville_error_message">
    <h3><?= $error_msg; ?></h3>
</div>


<?php } ?>

</div>


<?php get_footer();