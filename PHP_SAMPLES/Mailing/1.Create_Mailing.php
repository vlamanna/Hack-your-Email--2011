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

$listId = '555555';

$mailingId = cake_api_call('Mailing', 'create', array(
	'user_key'	=> $user['user_key'],
	'name'		=> 'Test Mailing'
));

cake_api_call('Mailing', 'setInfo', array(
	'user_key'			=> $user['user_key'],
	'list_id'			=> $listId,
	'mailing_id'		=> $mailingId,
	'clickthru_html'	=> 'true', // track link clicks
	'opening_stats'		=> 'true', // track opens
	'unsub_bottom_link'	=> 'true', // include unsubscribe link
	'subject'			=> 'Test Mailing',
	'html_message'		=> "Hello [first name]!\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec consectetur nibh ut mauris consectetur in dignissim lorem rutrum. Donec vulputate lobortis dolor, rutrum semper elit volutpat a. Nullam interdum ultrices nibh, nec ullamcorper lacus tempus in. Quisque eros ante, euismod at fermentum quis, egestas nec nulla."
));

cake_api_call('Mailing', 'Schedule', array(
	'user_key'		=> $user['user_key'],
	'mailing_id'	=> $mailingId
));

echo 'Mailing scheduled:' . $mailingId . "\n";

?>
