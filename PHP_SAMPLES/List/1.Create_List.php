<?php

define('API_KEY', 'your api key');

function cake_api_call($class, $method, $data) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.wbsrvc.com/' . $class . '/' . $method);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('apikey: ' . API_KEY));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	if ($result === false) {
		unset($result);
		echo 'Curl error: ' . curl_error($ch);
		die;
	}
	
	curl_close($ch);
		
	$json_object = json_decode($result, true);
	
	if ($json_object['status'] == 'success') {
		return $json_object['data'];
	} else {
		echo $json_object['data'];
		die;
	}
}

$user = cake_api_call('User', 'login', array(
	'email' 	=> 'your email',
	'password' 	=> 'your password'
));

$listId = cake_api_call('List', 'create', array(
	'user_key'		=> $user['user_key'],
	'name'			=> 'My Test List',
	'sender_name'	=> 'Vincent Lamanna',
	'sender_email'	=> 'v@cakemail.com'
));

cake_api_call('List', 'setInfo', array(
	'user_key'	=> $user['user_key'],
	'list_id'	=> $listId,
	'policy'	=> 'accepted'
));

echo 'Created list: ' . $listId . "\n";

?>
