<?php

$error = 0;

if(!isset($_URL)) require_once(__DIR__ .'/presets.php');

// Check CURL and URL
if(filter_var($_URL, FILTER_VALIDATE_URL) == false) {

	echo addLog($TEXT['_uni-Please_check_url'],1);$error = 1;

} elseif(function_exists('curl_version')) {
	
	$ch = curl_init($_URL);
	$fp = fopen($_FILE, 'w');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	
} elseif(ini_get('allow_url_fopen')) {

	file_put_contents($_FILE,fopen($_URL, 'r'));

} else {
	echo addLog($TEXT['_uni-Please_addCUR_OPEN'],1);$error = 1;
}

// Parse update history
if(!$error) {
	$update_file = fopen($_FILE, "r");
	$update = fread($update_file,filesize($_FILE));
	fclose($update_file);
	
	$versions = (array)json_decode($update);
	
	if (!is_array($versions)) {
		echo addLog($TEXT['_uni-Update_file_errr'],1);$error = 1;
	}
}

?>