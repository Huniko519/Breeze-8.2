<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');            // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../main/platform.php');         // Import platform details
require_once('../../../language.php');           // Import language

// New administration class
$profile = new manage();
$profile->db = $db;	

// Verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged display sponsor settings
	if(!empty($admin['id'])) {
	
		// Load home
		if($_POST['ff'] == 4) {
	
	        // Add JS loader
			$TEXT['posts'] =  '<div class="block-container-2"><div id="graphs_top"></div></div><script>loadRegChart(1);</script>';
			$left_large = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			$right_small = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);
		
			// Admin dashboard
			echo $left_large.$right_small;
	
		// Add category
		} elseif($_POST['ff'] == 17) {
	
		    $TEXT['content'] = $profile->addCategory($_POST['v1'],$_POST['t']);
	        echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		// Load charts
		} elseif($_POST['ff'] == "graphs_top") {
	
			echo $profile->getRegChart($admin,$_POST['v1']);
	
		// List updates
		} elseif($_POST['ff'] == "list-updates") {
			
			require_once('../../../require/requests/admin/get-update.php');
			
			// Get packets details
			if(!$error) {
				$count = 0;
				$tsize = 400;
				foreach ($versions as $version => $size) {
					if(version_compare($current_version,  $version) < 0) {
						$count++;
						$tsize += $size;
					}
				}
			}
			
			if($count) {
				$TEXT['temp-avaialbe_counts'] = sprintf($TEXT['_uni-Available_up_counts'], $count);
				$TEXT['temp-avaialbe_size'] = readAbleBytes($tsize*1000);
				echo  display(templateSrc('/admin/updates/available'));
			} else {
				$TEXT['temp-version'] = $current_version;
				echo  display(templateSrc('/admin/updates/up'));
			}

		}
		
	// If nothing set redirect to home
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
	}	
	
// Go to homepage as administrations credentials not found
} else {
	echo '<script>window.location.href = \''.$TEXT['installation'].'\'</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>