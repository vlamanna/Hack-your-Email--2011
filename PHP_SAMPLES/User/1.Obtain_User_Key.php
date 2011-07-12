<?php

define('API_KEY', 'your api key');

$data = array(
	'email' 	=> 'your email',
	'password' 	=> 'your password'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.wbsrvc.com/User/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('apikey: ' . API_KEY));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

if ($result === false) {
	unset($result);
	echo 'Curl error: ' . curl_error($ch);
}

curl_close($ch);

if (isset($result)) {
	$json_object = json_decode($result, true);
	
	if ($json_object['status'] == 'success') {
		echo 'user_key=' . $json_object['data']['user_key'] . "\n";
	} else {
		echo $json_object['data'];
	}
}

?>
