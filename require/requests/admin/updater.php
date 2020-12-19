<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/admin.php');            // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../main/platform.php');         // Import platform details
require_once('../../../language.php');           // Import language

// New administration class
$profile = new manage();
$profile->db = $db;

// If SESSIONS set verify administration
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		
		require_once(__DIR__ .'/presets.php');
		$TEXT['temp-version'] = 'NSET';
		
		// Load wizard
		if($_POST['ff'] == "0") {
			
			$TEXT['temp-wizard_content'] = display(templateSrc('/admin/updates/updater/step1-getdetails'));
			$TEXT['content'] = display(templateSrc('/admin/updates/updater/wizard'));
	
			echo display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);
			
		} 
		
		if($_POST['ff'] == "get-details") {
			
			require_once(__DIR__ .'/get-update.php');
			
			// Get packets details
			if(!$error) {
				$count = 0;
				$tsize = 400;
				foreach ($versions as $version => $size) {
					if(version_compare($current_version,  $version) < 0) {
						$TEXT['temp-version'] = $version;
						$TEXT['temp-size'] = readAbleBytes($size*1000);
						$TEXT['temp-download'] = sprintf($TEXT['_uni-Downloading__'], $TEXT['temp-size']);
						echo display(templateSrc('/admin/updates/updater/step2-giveinfo'));
						break;
					}
				}
			}
	
		} elseif($_POST['ff'] == "get-download") {
			
			$update_file = fopen($_FILE, "r");
			$update = fread($update_file,filesize($_FILE));
			fclose($update_file);
			
			$versions = (array)json_decode($update);
			
			if (!is_array($versions)) {
				echo addLog($TEXT['_uni-Update_file_errr'],1);$error = 1;
			}
			
			// Get packets details
			if(!$error) {
				$count = 0;
				$tsize = 400;
				foreach ($versions as $version => $size) {
					if(version_compare($current_version,  $version) < 0) {
						
						$_TFILE = '../../../updates/temp/'.$version.'.zip';
						$_TURL = $page_settings['UP_ADDRESS'].'/packages/'.$version.'.zip';
						
						if(function_exists('curl_version')) {
	
							$ch = curl_init($_TURL);
							$fp = fopen($_TFILE, 'w');
							curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
							
						} elseif(ini_get('allow_url_fopen')) {

							file_put_contents($_TFILE,fopen($_TURL, 'r'));

						} else {
							echo addLog($TEXT['_uni-Please_addCUR_OPEN'],1);$error = 1;
						}
						
						$TEXT['temp-version'] = $version;
					
						echo display(templateSrc('/admin/updates/updater/step3-installing'));
						
						break;
					}
				}
			}
	
		} elseif($_POST['ff'] == "install-package") {
			
			// Get packets details
			if(!$error) {
				
				$package = '../../../updates/temp/'.$_POST['p'].'.zip';
				
				$name = str_replace(".","",$_POST['p']);
				
				$zip = new ZipArchive();
				
				// Open ZIP
        		$opened_zip = $zip->open($package);  

        		if ($opened_zip === true) {

					$zip->extractTo('../../../updates/'); 
            		
					$zip->close();

					// Get extension info
					require_once('../../../updates/'.$name.'/info.php');
					require_once('../../../updates/'.$name.'/sql.php');

					// Perform SQL updates
					if(!empty($update_sqls)) {
						foreach($update_sqls as $sql) {	
							$db->query($sql);			
						}
					}

					// Add etension to database
					$db->query(sprintf("INSERT INTO `updates` (`uid`, `u_name`, `u_description`, `u_version`, `u_date`) VALUES (NULL, '%s', '%s', '%s', CURRENT_TIMESTAMP);",$db->real_escape_string($name[0]),$db->real_escape_string($up_description),$db->real_escape_string($up_version)));

					// Copy folders and files
					function recurse_copy($src,$dst) { 
                        $dir = opendir($src); 
                        @mkdir($dst); 
                        while(false !== ( $file = readdir($dir)) ) { 
                            if (( $file != '.' ) && ( $file != '..' )) { 
								if ( is_dir($src . '/' . $file) ) { 
                                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                                } else { 
                                    copy($src . '/' . $file,$dst . '/' . $file); 
                                } 
                            } 
                        } 
                        closedir($dir); 
                    } 

					// Copy update files
					recurse_copy ('../../../updates/'.$name.'/Files', '../../../');

        		}
				
				// Remove package
				unlink($package);
				
			}
			
			echo display(templateSrc('/admin/updates/updater/step4-verify'));
		
		} elseif($_POST['ff'] == "verify-package") { 
			
			$update_file = fopen($_FILE, "r");
			$update = fread($update_file,filesize($_FILE));
			fclose($update_file);
			
			$versions = (array)json_decode($update);
			
			if (!is_array($versions)) {
				echo addLog($TEXT['_uni-Update_file_errr'],1);$error = 1;
			}
			
			if(!$error) {
				$latest = 1;
				foreach ($versions as $version => $size) {
					if(version_compare($current_version,  $version) < 0) {
						$latest = 0;break;
					}
				}
				
				if(!$latest) {
					echo "<script>downloadUpdates(0,'NSET');</script>";
				} else {
					echo display(templateSrc('/admin/updates/updater/step5-upgradingcode'));
				}
			}				
		
		} elseif($_POST['ff'] == "install-classes") {
			
			echo '<script>window.location.href = \''.$TEXT['installation'].'/updates/'.str_replace(".","",$PLATFORM['VERSION']).'/KTA.express.php'.'\'';
			
		}
 
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
	}		
// No credentials
} else {
    echo '<script>window.location.href = \''.$TEXT['installation'].'\'';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>