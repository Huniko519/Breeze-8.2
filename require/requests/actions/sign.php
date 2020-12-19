<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// New registration class
$signup = new Register();
	
// Pass properties
$signup->db = $db;
$signup->settings = $page_settings;
	
// If all values are set
if(isset($_POST['v1']) && !empty($_POST['v1']) && isset($_POST['v2']) && !empty($_POST['v2']) && isset($_POST['v3']) && !empty($_POST['v3']) && isset($_POST['v5']) ) {

	// Get username and email availability
	$user_avail = $signup->getAvailabilityUSERNAME($_POST['v1']);
	$mail_avail = $signup->getAvailabilityMAIL($_POST['v2']);

	// Suggest a user name if selected UserName is not available
	if(is_null($user_avail)) {

		// Generate random number
		$new = $_POST['v1'].rand(111,999);

		// Check new availability
		$user_avail2 = $signup->getAvailabilityUSERNAME($new);
  
		// Add if available
		if($user_avail2) {
			$mes_content1 = $TEXT['_uni-like_lang'].' '.$new;
		}

	} else {
		
		// Reset suggestions
		$mes_content1 = '';	
		
	}
	
	if(!empty($_POST['v6']) || !empty($_POST['v7'])) {
		
		// Count lengths
		$fnm = strlen(trim($_POST['v6']));
		$lnm = strlen(trim($_POST['v7']));
		
		$frmn = (empty($_POST['v6'])) ? '' : $_POST['v6'];
		$lastn = (empty($_POST['v7'])) ? '' : $_POST['v7'];
	
		if($fnm > 15) {
			
			// If first name is out no length
			$name_error = $TEXT['_uni-error_firstname_len'];
			
		} elseif($lnm > 15) {
			
			// If last name is out of length
			$name_error = $TEXT['_uni-error_lastname_len'];
			
		} else {
			$names = array($frmn,$lastn);
		}

	} else {
		$names = array('','');
	}
	
	
	// Check whether both passwords match
	if($_POST['v3'] !== $_POST['v4'] ) {
			echo '<script>
			            captcha();	
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-3'].'",0);		
					</script>';
		
    // Check UserName character length	
	} elseif(strlen($_POST['v1']) > $page_settings['username_max_len'] || strlen($_POST['v1']) < $page_settings['username_min_len']) {
			echo '<script>
			            captcha();	
                        returnForms("REGISTERATION_FORM");
						switchMessage("'.sprintf($TEXT['_uni-signup-1'],$page_settings['username_min_len'],$page_settings['username_max_len']).'",0);		
					</script>';	
		
    // Check username characters
	} elseif(!ctype_alnum($_POST['v1']) || is_numeric($_POST['v1'])) {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-9'].'",0);		
					</script>';	
		
	// Check Password length		
	} elseif(strlen($_POST['v3']) > $page_settings['password_max_len'] || strlen($_POST['v3']) < $page_settings['password_min_len']) {
			echo '<script>
			            captcha();	
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.sprintf($TEXT['_uni-signup-2'],$page_settings['password_min_len'],$page_settings['password_max_len']).'",0);		
					</script>';
		
	// Check EMail format	
	} elseif(!filter_var($db->real_escape_string($_POST['v2']), FILTER_VALIDATE_EMAIL) || strlen(trim($_POST['v2'])) < 3 || strlen(trim($_POST['v2'])) > 62) {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-4'].'",0);		
					</script>';	
		
	// UserName availability		
	} elseif(is_null($user_avail))  {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.sprintf($TEXT['_uni-signup-5'],$mes_content1).'",0);			
					</script>';	
		
	// email availability		
	} elseif(is_null($mail_avail)) {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-6'].'",0);		
					</script>';	
				
	// CAPTCHA test			
	} elseif($page_settings['captcha'] == 1 && $_POST['v5'].'7sh' !== $_SESSION['CAPTCHA'].'7sh') {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-8'].'",0);			
					</script>';	
						
	// Gender test			
	} elseif(!in_array($_POST['v8'],array("1","2"))) {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$TEXT['_uni-signup-gen'].'",0);			
					</script>';	
						
	// First name and last name test			
	} elseif(isset($name_error)) {
			echo '<script>
			            captcha();
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$name_error.'",0);			
					</script>';	
						
	} else {

		// Add user to database
		$signed = $signup->addUser($_POST['v1'],$_POST['v2'],$_POST['v3'],$names,$_POST['v8']);

		// If added
		if($signed) {

			// Check for cookie	
	    	$cookie = (isset($_POST['t']) && is_numeric($_POST['t'])  && $_POST['t'] == 1) ? 1 : 0;
		
			// New login class
			$log = new Login();
		
			// Pass properties
	    	$log->db = $db;
			$log->installation = $TEXT['installation'];
	    	$log->username = $_POST['v1'];
	    	$log->password = $_POST['v3'];
	    
			// Add cookie
			$log->cookie = $cookie;		
	    
			// Allow user pass email verification for first time
			$log->new_reg = 1;		
	
			// Pass settings
	    	$log->emails_verification = $page_settings['emails_verification'];
		  
			// login
			$data = $log->start();
		
		    // Check status
			if($data == 1) {
				echo '<script>window.location.href="'.$TEXT['installation'].'/?welcome";</script>'; // If logged
			} else {    // Else show error
				echo '<script>
                        returnForms("REGISTERATION_FORM");
		                switchMessage("'.$data.'",0);		
					</script>';		
			}	
		}		
	}
	
// Requires all fields
} else {
	echo '<script>	
            returnForms("REGISTERATION_FORM");
		    switchMessage("'.$TEXT['_uni-signup-7'].'",2);		
		</script>';
}	
	
if(isset($db) && $db) {
	mysqli_close($db);
}
?>