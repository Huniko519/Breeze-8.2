<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Login class
$log = new Login();
$log->db = $db;

$form_type = ($_POST['v3']) ? 'RECENT_FORM' : 'LOGIN_FORM';

// If values are set
if(isset($_POST['v1']) && !empty($_POST['v1']) && isset($_POST['v2']) && !empty($_POST['v2'])) {

	// Pass properties
	$log->installation = $TEXT['installation'];
	$log->username = $_POST['v1'];
	$log->password = $_POST['v2'];
	$log->cookie = (isset($_POST['t']) && is_numeric($_POST['t'])  && $_POST['t'] == 1) ? 1 : 0;

	// Pass settings
	$log->emails_verification = $page_settings['emails_verification'];
	$log->settings = $page_settings;

	// Perform
	$data = $log->start();
	
	if($data == 1) {

		// Login success
		echo '<script>window.location.href="'.$TEXT['installation'].'/?welcomeback";</script>';

	} else {
		// Login fail - show error
		echo '<script>
				returnForms("'.$form_type.'");
				switchMessage("'.$data.'",0);	
			</script>';		
	}
} else {
	// Empty credentials
	echo '<script>
			returnForms("'.$form_type.'");
			switchMessage("'.$TEXT['_uni-Username_password_incorrect'].'",2);		
		</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>