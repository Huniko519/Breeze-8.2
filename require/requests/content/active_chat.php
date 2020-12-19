<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get logged user
	$user = $profile->getUser();

	if(!empty($user['idu']) && $page_settings['poll_messages']) {
		
		// (1,000,000 μs equals 1 s)
		define('MESSAGE_POLL_MICROSECONDS', 500000);
 
		// How long to keep the Long Poll open, in seconds
		define('MESSAGE_TIMEOUT_SECONDS', 30);
		
		// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
		define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
 
		// Close the session prematurely to avoid usleep() from locking other requests
		session_write_close();
 
		// Automatically die after timeout (plus buffer)
		set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);
 
		// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
		$counter = MESSAGE_TIMEOUT_SECONDS;
		
		// Poll for messages and hang if nothing is found, until the timeout is exhausted
		while($counter > 0) {
    		
			// Check for new data (not illustrated)
    		if($data = $profile->getMessages($_POST['v2'],$_POST['v1'],$user,NULL,TRUE)) {
        	
				// Break out of while loop if new data is populated
        		break;
				
    		} else {
        		
				// Otherwise, sleep for the specified time, after which the loop runs again
        		usleep(MESSAGE_POLL_MICROSECONDS);
 
        		// Decrement seconds from counter (the interval was set in μs, see above)
        		$counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
				
    		}
		}

		// Return request
		if(isset($data)) {	
	
    		// Send data
    		echo $data;

		}
		
	} elseif(!empty($user['idu'])) {
		
		echo $profile->getMessages($_POST['v2'],$_POST['v1'],$user,NULL,TRUE);
	
		// Update last activity on form
		$profile->updateFormActivity($_POST['v1'],$user['idu']);
		
	}
}                                                     

if(isset($db) && $db) {
	mysqli_close($db);
}
?>