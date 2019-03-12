<?php
session_start();
header("Content-Type: text/plain");


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

class AffiliateApi {

	private $api_url, $network_token;
	
	function __construct ($api_url, $network_token) {
		$this->api_url = $api_url;
		$this->network_token = $network_token;
	}
	
	public function query($target,$method,$data,$id="") {
		$query = array
		(
			"NetworkToken" => $this->network_token,
			"Target" => $target,
			"Method" => $method,
			"id" => (isset($id)) ? $id : "",
			"return_object" => 1,
			"data" => $data
		);
		
		$query = http_build_query($query);
		$response = curl($this->api_url, $query);
		return $response;
	}

	public function specialQuery ($target,$method,$additional) {
		$query = array
		(
			"NetworkToken" => $this->network_token,
			"Target" => $target,
			"Method" => $method,
			"return_object" => 1,
		);
		foreach ($additional as $addKey=>$addValue) {
			$query[$addKey] = $addValue;
		}
		$query = http_build_query($query);
		$response = curl($this->api_url, $query);
		return $response;
	}
}

// Get hidden POST values from the form
function valueOrZero($fieldname) {
	if (isset($_POST[$fieldname]) && $_POST[$fieldname] != "")
		return $_POST[$fieldname];
	else
		return "0";
}

$signup_ip = valueOrZero('signup_ip');
$account_manager_id = valueOrZero('account_manager_id');
$referral = valueOrZero('referral');
$network = valueOrZero('network');

$dbhost = 'sql.rational-host.com:3306';
$dbuser = 'ho_sync';
$dbpass = 'mIq28NOX9';
$dbname = 'ho_sync';

function logError ($method, $response) {
	$err = date ("d/m/y H:i:s:") . 
		   'Error in '.$method."\r\n" .
		   $response."\r\n";

	error_log($err, 3, "register-errors.log");
}

function registerCake ($api_url, $api_key) {

	global $referral;
	global $signup_ip;
	global $account_manager_id;
	
	$data = array
	(
		"api_key"=> $api_key,
		"affiliate_name"=> $_POST['company'],
		"contact_first_name"=> $_POST['fname'],
		"contact_last_name"=> $_POST['lname'],
		"contact_email_address"=> $_POST['email'],
		"contact_im_name"=> $_POST['skype'],
		"contact_password"=> $_POST['password'],
		"address_country"=> $_POST['country'],
		"media"=> $_POST['media_type'],
		"vertical"=> $_POST['vertical'],
		"date_added"=> date('m-d-Y'),
		"referral_affiliate_id"=> $referral,
		"signup_ip_address"=>$signup_ip,
		"account_status_id"=> "3",
		"affiliate_tier_id"=> "1",
		"hide_offers"=> "FALSE",
		"website"=> "http://getCAKE.com",
		"tax_class"=> "Other",
		"ssn_tax_id"=> "565579584",
		"vat_tax_required"=> "FALSE",
		"swift_iban"=> "1234567890",
		"payment_type_id"=> "1",		
		"payment_to"=> "1",
		"payment_fee"=> "3.00",
		"payment_min_threshold"=> "15.00",
		"currency_id"=> "1",
		"payment_setting_id"=> "1",
		"billing_cycle_id"=> "1",
		"payment_type_info"=> "ROUTINGNUMBER+46236346",
		"address_street"=> "2244+West+Coast+Highway",
		"address_street2"=> "STE+250",
		"address_city"=> "Newport+Beach",
		"address_state"=>"CA",
		"address_zip_code"=>"92663",
		"contact_middle_name"=> "CAKE",
		"contact_title"=> "0",
		"contact_phone_work"=> "0",
		"contact_phone_cell"=> "0",
		"contact_phone_fax"=> "0", 
		"contact_im_service"=> "1",
		"contact_im_name"=>"cake",
		"contact_timezone"=>"PST",
		"contact_language_id"=>"1",
		"media_type_ids"=>"15,7,6",
		"price_format_ids"=>"1,2,4",
		"vertical_category_ids"=>"1,2,20",
		"country_codes"=>"US,CA,AF",
		"tag_ids"=>"1,3,4",
		"referral_notes"=>"notes",
		"terms_and_conditions_agreed"=> "TRUE",
		"notes"=>"notes"
	);

	$query = http_build_query($data);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url.'?'.$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$response = curl_exec($ch);
	$xml = new SimpleXMLElement($response);
	curl_close($ch);
	$success = $xml->success;
	
	file_put_contents ("log.txt","register step 1");
	if ($success == "true") {
		$aff_id = $xml->affiliate_id;
		$localdata = array
		(
			"signup_ip" => $signup_ip,
			"account_manager_id" => $account_manager_id,
			"company" => $_POST['company'],
			"status" => "pending",
			"country" => $_POST['country'],
			"affiliate_id" => $aff_id,
			"first_name" => $_POST['fname'],
			"last_name" => $_POST['lname'],
			"phone" => "",
			"email" => $_POST['email'],
			"media_type"=> $_POST['media_type'],
			"vertical"=> $_POST['vertical'],
			"password" => $_POST['password'],
			"skype" => $_POST['skype']
		);
		if ( isset($_GET['reqid']) ) {
			//$response = http_get("http://roitrack.net/p.ashx?o=1287&e=8&f=pb&r=".$_GET['reqid'], array("timeout"=>20) );
			$response = file_get_contents("http://roitrack.net/p.ashx?o=1287&e=8&f=pb&r=".$_GET['reqid']);
		}
		$r = registerLocal($localdata);
		if ($r == -1) return "-1";
		return "1";
	}
	else return "-4";
}

function registerROI ($api_url, $network_token) {
	if ( !( isset($_SESSION['sec_token']) && isset($_POST['stoken']) && $_SESSION['sec_token'] === $_POST['stoken'] ) )
		die ('-100');

	global $signup_ip, $account_manager_id, $referral, $network;
	
	$api = new AffiliateApi($api_url, $network_token);
	
	// Affiliate.create
	$data = array
	(
		"signup_ip" => $signup_ip,
		"account_manager_id" => $account_manager_id,
		"company" => $_POST['company'],
		//"phone" => $_POST['phone'],
		"status" => "pending",
		//"address1" => $_POST['address1'],
		//"address2" => $_POST['address2'],
		//"city" => $_POST['city'],
		"country" => $_POST['country'],
		//"region" => $_POST['region'],
		//"zipcode" => $_POST['zipcode'],
	);
	$response = $api->query ("Affiliate","create", $data);
	if (! (isset($response['response']['status']) && $response['response']['status']===1 ) )  {
		logError ("Affiliate.create",var_export($response,true));
		return -1;
	}

	$aff_id = $response['response']['data']['Affiliate']['id'];
	$aff_phone = $response['response']['data']['Affiliate']['phone'];
	$data2 = array 
	(
		"affiliate_id" => $aff_id,
		//"title" => $_POST['title'],
		"first_name" => $_POST['fname'],
		"last_name" => $_POST['lname'],
		"phone" => $aff_phone,
		"email" => $_POST['email'],
		"media"=> $_POST['media_type'],
		"vertical"=> $_POST['vertical'],
		"password" => $_POST['password'],
		"password_confirmation" => $_POST['password_confirmation'],
	);
	$response = $api->query ("AffiliateUser","create",$data2);
	if (! (isset($response['response']['status']) && $response['response']['status']===1 ) ) {
		logError ("AffiliateUser.create",var_export($response,true));
		if ( $response['response']['errors'][0]['err_code'] == 4 )
			return -4;
		else
			return -1;
	}
	unset ($data2["password_confirmation"]);
	$localData = array_merge($data,$data2);
	$localData["skype"] = $_POST['skype'];
	$r = registerLocal($localData);
	if ($r == -1) return -1;

	$data = array
	(
		"question_id" => "2",
		"answer" => $_POST['skype'],
	);
	$response = $api->query ("Affiliate","createSignupQuestionAnswer",$data,$aff_id);
	if (! (isset($response['response']['status']) && $response['response']['status']===1 ) ) {
		logError ("Affiliate.createSignupQuestionAnswer:skype",var_export($response,true));
		return -1;
	}


	// Affiliate.createSignupQuestionAnswer - 4:referral
	if(!$referral == "") {
		$data = array
		(
			"question_id" => "4",
			"answer" => $referral,
		);
		$response = $api->query ("Affiliate","createSignupQuestionAnswer",$data,$aff_id);
		if (! (isset($response['response']['status']) && $response['response']['status']===1 ) ) {
			logError ("Affiliate.createSignupQuestionAnswer:referral",var_export($response,true));
			return -1;
		}
	}

	if(!$network == "") {
		$data = array
		(
			"question_id" => "6",
			"answer" => $network,
		);
		$response = $api->query ("Affiliate","createSignupQuestionAnswer",$data,$aff_id);
		if (! (isset($response['response']['status']) && $response['response']['status']===1 ) ) {
			logError ("Affiliate.createSignupQuestionAnswer:network",var_export($response,true));
			return -1;
		}
	}
	return 1;
}

function registerLocal($data) {
	   global $dbhost, $dbuser, $dbpass, $dbname;

	   //$db = new mysqli($dbhost, $dbuser, $dbpass,$dbname);
	   $db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass, array( PDO::ATTR_PERSISTENT => true ));
 	   if(! $db ) {
		   return -1;
	   }
	   //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT);

	   $fields = '';
	   $values = array();
	   foreach ($data as $key=>$value) {
		   $fields .= ':'.$key.', ';
		   $values[':'.$key] = $value;
	   }
	   $fields = rtrim($fields,', ');

	   $prepare = 'INSERT INTO temp_registrations ('.implode(',',array_keys($data)).') VALUES ('.$fields.')';
	   $insert = $db->prepare($prepare);
	   //if (!$insert) echo 'PREPARE ERROR: '.$db->error;
	   
	   if (!$insert->execute($values)) {
		   return -1;
	   }
	   return 1;
}

$apis = array(

	'roi'=> array(
		'url'=>"https://roiaff.api.hasoffers.com/Apiv3/json?",
		'token'=>"NETXUr6yWXrSHWf8cQy5KofRHAiyUt"
	),

	'cake'=> array(
		'url'=> "https://go.roi.boutique/api/4/signup.asmx/Affiliate",
		'api_key'=> "mYjvhFThVaCGfLhHiIdNw"
	)
);

function checkEmail($email) {
	global $dbhost, $dbuser, $dbpass, $dbname;
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass, array( PDO::ATTR_PERSISTENT => true ));
	if(! $db ) {
		return -1;
	}
	$stmt = $db->prepare("SELECT email FROM temp_registrations WHERE email='".$email."'");
	if (!$stmt->execute() || $stmt->rowCount() > 0 ) return -1;
	return 1;
}

if (isset($_GET['checkemail'])) {
	echo checkEmail ( $_GET['checkemail'] );
}
else {
	if (!isset($_GET['api'])) $api = "cake";
	else $api = $_GET['api'];

	if ($api == 'roi') {
		$api = $apis[$api];
		echo registerROI( $api['url'], $api['token'] );
	}
	else if ($api == 'cake') {
		$api = $apis[$api];
		echo registerCake( $api['url'], $api['api_key'] );
	}
}
