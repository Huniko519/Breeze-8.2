<?php
class paypal {

	function post($method, $params, $mode) {
		global $db,$page_settings,$TEXT;

		// The request URL
		$url = "https://api-3t".$mode.".paypal.com/nvp";
	
		$version = '116.0';
	
		// Construct the query params
		$credentials = array('METHOD' => $method, 'VERSION' => $version, 'USER' => $page_settings['pp_user'], 'PWD' => $page_settings['pp_pass'], 'SIGNATURE' => $page_settings['pp_sign']);
		$params = array_merge($credentials, $params);
		
		// Set the curl parameters.
		if(function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			
			$response = curl_exec($ch);
		}
		
		if(empty($response)) {
			$opts = array('http' =>
				array(
					'protocol_version' => '1.1',
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($params)
				)
			);
			$context = stream_context_create($opts);
			$response = file_get_contents($url, false, $context);
		}
	
		// Parse the response
		parse_str($response, $responseArr);
	
		// If the request fails
		if(empty($responseArr) || !array_key_exists('ACK', $responseArr)) {
			return array('L_SHORTMESSAGE0' => $TEXT['_uni-payment_error'], 'L_LONGMESSAGE0' => $TEXT['_uni-payment_error_0'], 'ACK' => 'REQUEST_FAILED');
		}
		
		return $responseArr;
	}
}
?>
