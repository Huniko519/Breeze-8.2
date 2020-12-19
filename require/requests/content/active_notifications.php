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

	if(!empty($user['idu']) && $page_settings['poll_notifications']) {
		
		define('MESSAGE_POLL_MICROSECONDS', 500000);
 
		define('MESSAGE_TIMEOUT_SECONDS', 30);
		
		define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);

		session_write_close();

		set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);
 
		$counter = MESSAGE_TIMEOUT_SECONDS;

		while($counter > 0) {
    		
			$ndata = $profile->checkNotiications($user['idu']);
			
			// Check for new data (not illustrated)
    		if('n'.$_POST['pre'] !== 'n'.$ndata) {
        	
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
		if(isset($ndata)) {	
	
    		// Send data
    		echo $ndata;

		}
		
	} elseif(!empty($user['idu'])) {
		
		echo $profile->checkNotiications($user['idu']);
		
	}
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>