<?php
session_start();

require_once("../../main/config.php");            // Import configuration
require_once('../../require/main/database.php');  // Import database connection
require_once('../../require/main/classes.php');   // Import all classes
require_once('../../require/main/ads.php');       // Import ads functons
require_once('../../require/main/settings.php');  // Import settings
require_once('../../language.php');               // Import language
require_once("./paypal.php");                     // Import PayPal Class

// User class
$profile = new main();
$profile->db = $db;

// Verify user credentials if set
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties and credentials
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();
	
	// Wrong credentials
	if(!empty($user['idu'])) {
		
		$pa_type = array("post" => "1","group" => "2","page" => "3",);
	
		$pa_type2 = array("1" => "Post Boost","3" => "Group Boost","2" => "Page Boost",);

			// Set PayPal data
			$paypalmode = ($page_settings['pp_sandbox']) ? '.sandbox' : '';
			$PayPalApiUsername  = $page_settings['pp_user'];
			$PayPalApiPassword  = $page_settings['pp_pass'];
			$PayPalApiSignature = $page_settings['pp_sign'];
			$PayPalReturnURL    = $TEXT['installation'].'/includes/paypal/process.php'; 
			$PayPalCancelURL    = $TEXT['installation'].'/includes/paypal/process.php'; 
		
			if($_POST) {

				$payment_id = $_POST["payment_id"];

				// Get payment info from database
				$payment = getPayment($payment_id);

				$PayPalCurrencyCode = $payment['currency'];
				$ItemName 		= $pa_type2[$payment['type']];
				$ItemPrice 		= $payment['amount'];
				$ItemNumber 	= $payment['id'];
				$ItemDesc 		= $TEXT['_uni-Ads_desc'];
				$ItemQty 		= 1;
				$ItemTotalPrice = ($ItemPrice * $ItemQty);

					$padata = array (
				        'METHOD' => 'SetExpressCheckout',
				        'RETURNURL' => $PayPalReturnURL,
				        'CANCELURL' => $PayPalCancelURL,
				        'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
				        'L_PAYMENTREQUEST_0_NAME0' => $ItemName,
				        'L_PAYMENTREQUEST_0_NUMBER0' => $ItemNumber,
				        'L_PAYMENTREQUEST_0_DESC0' => $ItemDesc,
				        'L_PAYMENTREQUEST_0_AMT0' => $ItemPrice,
				        'L_PAYMENTREQUEST_0_QTY0' => $ItemQty,
				        'NOSHIPPING' => 1,
				        'PAYMENTREQUEST_0_ITEMAMT' => $ItemTotalPrice,
				        'PAYMENTREQUEST_0_AMT' => $ItemPrice,
				        'PAYMENTREQUEST_0_CURRENCYCODE' => $PayPalCurrencyCode,
				        'PAYMENTREQUEST_0_ALLOWEDPAYMENTMETHOD' => 'InstantPaymentOnly',
				        'LOCALECODE' => 'US',
				        'LOGOIMG' => $TEXT['installation'].'/'.$TEXT['theme'].'/images/logo_black.png',
				        'CARTBORDERCOLOR' => 'FFFFFF',
				        'ALLOWNOTE' => 0
					);
				
					$_SESSION['payment_id'] = $payment['id'];

        			$paypal= new paypal();
        			$httpParsedResponseAr = $paypal->post('SetExpressCheckout', $padata, $paypalmode);
       
        			//Respond according to message we receive from Paypal
        			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

                		$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
                		header('Location: '.$paypalurl);
             
        			} else {
            		    echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
            		    echo '<pre>';
            		    print_r($httpParsedResponseAr);
            		    echo '</pre>';
        		    }
		    }

		    if(isset($_GET["token"]) && isset($_GET["PayerID"])) {

    		    $token = $_GET["token"];
    		    $payer_id = $_GET["PayerID"];
				
				$payment = getPayment($_SESSION['payment_id']);
				
				// Prevent duplicate entry
				if($payment['status']) die('Duplicate entry !');
				
                $PayPalCurrencyCode = $payment['currency'];
				$ItemName 		= $pa_type2[$c_type];
				$ItemPrice 		= $payment['amount'];
				$ItemNumber 	= $payment['id'];
				$ItemDesc 		= '';
				$ItemQty 		= 1;
				$ItemTotalPrice = ($ItemPrice * $ItemQty);
				
				$padata = array(
					'TOKEN' => $token,
					'PAYERID' => $payer_id,
					'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
					'L_PAYMENTREQUEST_0_NAME0' => $ItemName,
					'L_PAYMENTREQUEST_0_NUMBER0' => $ItemNumber,
					'L_PAYMENTREQUEST_0_DESC0' => $ItemDesc,
					'L_PAYMENTREQUEST_0_AMT0' => $ItemPrice,
					'L_PAYMENTREQUEST_0_QTY0' => $ItemQty,
					'PAYMENTREQUEST_0_ITEMAMT' => $ItemTotalPrice,
					'PAYMENTREQUEST_0_AMT' => $ItemPrice,
					'PAYMENTREQUEST_0_CURRENCYCODE' => $PayPalCurrencyCode,
					'PAYMENTREQUEST_0_ALLOWEDPAYMENTMETHOD'	=> 'InstantPaymentOnly'
				);
				
    		    $paypal= new paypal();
    		    $httpParsedResponseAr = $paypal->post('DoExpressCheckoutPayment', $padata, $paypalmode);
   
    		    //Check if everything went ok..
    		    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

                    // Transaction ID
				    $tx_id = $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"];
           
		            /*
                    if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                        echo '<div style="color:green">Payment Received!</div>';
                    } elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                        echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
                        'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
                    }
					*/

                    $padata = array('TOKEN' => $token);
                    $paypal= new paypal();
                    $httpParsedResponseAr = $paypal->post('GetExpressCheckoutDetails', $padata, $paypalmode);

                    if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                   	
						$payment_id = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER0"];
						
						$i_payment = getPayment($payment_id);
						
						// Update payment
						updatePayment($i_payment['id'],$tx_id,1);
						
						// Insert new ad
						publishAd($user,$i_payment);

						echo display(templateSrc('/sponsors/published'));
		
                    } else  {
						
                        echo '<div style="color:red"><b>Get Transaction Details failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                        echo '<pre>';
                        print_r($httpParsedResponseAr);
                        echo '</pre>';

                    }
   
    		    } else {
                    echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
                    echo '<pre>';
                    print_r($httpParsedResponseAr);
                    echo '</pre>';
                }
            }
		
	} else {
		echo showError($TEXT['lang_error_connection2']);
	}
	
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>