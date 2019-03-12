<?php
//Post Data handler
if(isset($_POST['signup_ip']))
{
    $signup_ip = $_POST['signup_ip'];
}else{
    $signup_ip = "";
}

if(isset($_POST['account_manager_id']))
{
    $account_manager_id = $_POST['account_manager_id'];
}else{
    $account_manager_id = "";
}

if(isset($_POST['refferal']))
{
    $refferal = $_POST['refferal'];
}else{
    $refferal = "";
}

// Initialize cURL and make the request
function curl($api_url, $query)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url.$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Decode the response into a PHP associative array
    $response = json_decode($response, true);
    
    // Make sure that there wasn't a problem decoding the repsonse
    if(json_last_error()!==JSON_ERROR_NONE){
    	throw new RuntimeException(
    		'API response not well-formed (json error code: '.json_last_error().')'
    	);
    }
    
    return $response;
}


//prepare first query
$api_url = "https://mediaroi.api.hasoffers.com/Apiv3/json?";

$first_query = array
(
    "NetworkToken" => "NET4Kk4jW7sKjrVhia3oV1yA5IvPvx",
    "Target" => "Affiliate",
    "Method" => "create",
    "data" => array
        (
            "signup_ip" => $signup_ip,
            "account_manager_id" => $account_manager_id,
            "company" => $_POST['company'],
            "phone" => $_POST['phone'],
            "status" => "pending",
            "address1" => $_POST['address1'],
            "address2" => $_POST['address2'],
            "city" => $_POST['city'],
            "country" => $_POST['country'],
            "region" => $_POST['region'],
            "zipcode" => $_POST['zipcode'],
        )
);
    
$first_query_get = http_build_query($first_query);

//Execute first query
$firstQueryResponce = curl($api_url, $first_query_get);

// Print out the response details or, any error messages
if(isset($firstQueryResponce['response']['status']) && $firstQueryResponce['response']['status']===1){
	
}else{
	//echo 'Error - Somthing went wrong with the account details provided';
	//echo '<pre>'.print_r($firstQueryResponce['response']['errors'][0]['err_msg'], true).'';
	header('Location: ../?status=Somthing went wrong with the account details provided');
	die();
}

//Prepare second query
$aff_id = $firstQueryResponce['response']['data']['Affiliate']['id'];
$aff_phone = $firstQueryResponce['response']['data']['Affiliate']['phone'];

$second_query = array
(
    "NetworkToken" => "NET4Kk4jW7sKjrVhia3oV1yA5IvPvx",
    "Target" => "AffiliateUser",
    "Method" => "create",
    "data" => array
        (
            "affiliate_id" => $aff_id,
            "title" => $_POST['title'],
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "phone" => $aff_phone,
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "password_confirmation" => $_POST['password_confirmation'],
        ),
    "return_object" => 1
);
    
$second_query_get = http_build_query($second_query);

//Execute second query
$secondQueryResponce = curl($api_url, $second_query_get);

// Print out the response details or, any error messages
if(isset($secondQueryResponce['response']['status']) && $secondQueryResponce['response']['status']===1){
	
}else{
	//echo 'Account Created - Somthing went wrong with the user details provided, could not create user. Contact support';
	//echo '<pre>'.print_r($secondQueryResponce['response']['errors'][0]['err_msg'], true).'';
	header('Location: ../?status=Account Created - Somthing went wrong with the user details provided, could not create user. Contact support');
	die();
}

//Third Query, Security Question
$third_query = array
    (
        "NetworkToken" => "NET4Kk4jW7sKjrVhia3oV1yA5IvPvx",
        "Target" => "Affiliate",
        "Method" => "createSignupQuestionAnswer",
        "id" => $aff_id,
        "data" => array
            (
                "question_id" => "2",
                "answer" => $_POST['skype'],
            ),
        "return_object" => 1
    );
    
$third_query_get = http_build_query($third_query);

//Execute third query
$thirdQueryResponce = curl($api_url, $third_query_get);

// Print out the response details or, any error messages
if(isset($thirdQueryResponce['response']['status']) && $thirdQueryResponce['response']['status']===1){
	if($refferal == ""){
	    //echo "success";
	    header('Location: ../?status=Account created, An account manager will review your account and will contact you shortly');
	}
}else{
	//echo 'Account created, there was an issue saving your skype account';
	header('Location: ../?status=Account created, there was an issue saving your skype account');
	//echo '<pre>'.print_r($thirdQueryResponce['response']['errors'][0]['err_msg'], true).'';
}

//fourth Query, Security Question
if(!$refferal == ""){
    $fourth_query = array
        (
            "NetworkToken" => "NET4Kk4jW7sKjrVhia3oV1yA5IvPvx",
            "Target" => "Affiliate",
            "Method" => "createSignupQuestionAnswer",
            "id" => $aff_id,
            "data" => array
                (
                    "question_id" => "4",
                    "answer" => $refferal,
                ),
            "return_object" => 1
        );
        
    $fourth_query_get = http_build_query($fourth_query);
    
    //Execute fourth query
    $fourthQueryResponce = curl($api_url, $fourth_query_get);
    
    // Print out the response details or, any error messages
    if(isset($fourthQueryResponce['response']['status']) && $fourthQueryResponce['response']['status']===1){
    	//echo "success";
    	header('Location: ../?status=Account created, An account manager will review your account and will contact you shortly');
    }else{
    	//echo 'Account created, there was an issue saving your refferal account information';
    	header('Location: ../?status=Account created, there was an issue saving your refferal account');
    	//echo '<pre>'.print_r($fourthQueryResponce['response']['errors'][0]['err_msg'], true).'';
    }
}
