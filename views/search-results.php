<?php

$startDate = (isset($_GET['startDate'])) ? $_GET['startDate'] : '';
$endDate = (isset($_GET['endDate'])) ? $_GET['endDate'] : '';
$numAdults = (isset($_GET['numAdults'])) ? $_GET['numAdults'] : null;
$numChildren = (isset($_GET['numChildren'])) ? $_GET['numChildren'] : null;

$accountID = get_option('_bookerville_account_id');
$key = get_option('_bookerville_secret_key');


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
$xml_response  = simplexml_load_string($responseBody);

foreach($xml_response->children() as $result) {
    
   
    
}

print_r($xml_response);