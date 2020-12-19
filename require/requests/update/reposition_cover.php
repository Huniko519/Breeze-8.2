<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Import user class
$profile = new main();
$profile->db = $db;
$error = false;

if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {

	// Pass properties
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
	
	// Get currently logged user
	$user = $profile->getUser();
	
	// If user exists
	if(!empty($user['idu'])) {

        $groupid = ($_POST['group'] && !empty($_POST['group']))	? $_POST['group'] : false;
        $pageid = ($_POST['page'] && !empty($_POST['page']))	? $_POST['page'] : false;
		
		if($groupid) {
			
			// Fetch group and role
			$group = $profile->getGroup($groupid); 
			$group_user = $profile->getGroupUser($user['idu'],$groupid);
	
			// Is allowed
			$joined = ($group_user['group_status'] == 1 && ($group_user['group_role'] == 2 || $group_user['p_cover'] == 1)) ? 1 : 0;
			
			if($joined) {
				
	    		$nrm = getCoverSrc(str_replace('rep_','',$group['group_cover']));
		
				$cover = $page_settings['folder_group_cover_photos'].$nrm;
				$rep_cover = $page_settings['folder_group_cover_photos'].'rep_'.$nrm;		
				
			} else {
				$error = 1;
				echo '<script>returnRepositon(0,"'.$TEXT['_uni-cvr_rep_bnt_available2'].'");</script>';
			}
			
		} elseif($pageid) {
			
			// Fetch page
			$page = $profile->getPage($pageid); 
			
			// Fetch page role
			$page_role = $profile->getPageRole($user['idu'],$pageid);
	
			if($page_role['page_role'] > 3) {
				
	    		$nrm = getCoverSrc(str_replace('rep_','',$page['page_cover']));
		
				$cover = $page_settings['folder_page_cover_photos'].$nrm;
				$rep_cover = $page_settings['folder_page_cover_photos'].'rep_'.$nrm;		
				
			} else {
				$error = 1;
				echo '<script>returnRepositon(0,"'.$TEXT['_uni-cvr_rep_bnt_available3'].'");</script>';
			}
			
		} else {
	
	    	$nrm = getCoverSrc(str_replace('rep_','',$user['cover']));
		
			$cover = $page_settings['folder_cover_photos'].$nrm;
			$rep_cover = $page_settings['folder_cover_photos'].'rep_'.$nrm;
		
		}

		if(!file_exists($rep_cover)) {
			echo '<script>returnRepositon(0,"'.$TEXT['_uni-cvr_rep_bnt_available'].'");</script>';
		} else {
			
			// Get image info
			list($width, $height) = getimagesize($cover);

			// Add offset
			$parm = str_replace('-','',$_POST['yexs']);
			$offset_x = 0;
			$offset_y = (is_numeric($parm) && $parm && $parm < 400) ? $parm : 0;

			// Fix height of the image(Prevent black lines)
			$new_height = $height - $offset_y;
			$new_width = $width;

			// Get extension
			$ext_split = explode('.',$nrm);
			$extension = mime_content_type($cover);

			// Create image from available format
			switch($extension){
				case 'image/png':
			        $image_res = imagecreatefrompng($cover); break;
				case 'image/gif':
				    $image_res = imagecreatefromgif($cover); break;			
				case 'image/jpeg': case 'image/pjpeg':  case 'image/jpg':
				    $image_res = imagecreatefromjpeg($cover); break;
				default:
				    $image_res = false;
			}
			
			// Quality check
			if($height < 500 || $new_height < 380) {
				$image_res = false;
				$bad_quality = true;
			} else {
				$bad_quality = false;
			}
			
			// If image created
			if($image_res && !$bad_quality) {

				$new_image = imagecreatetruecolor($new_width, $new_height);
			
				imagecopy($new_image, $image_res, 0, 0, $offset_x, $offset_y, $width, $height);

				unlink($rep_cover);
				
				saveImage($new_image, $rep_cover, $extension, 100);
				
				$refresh_name = 'rep_'.$nrm.'?'.mt_rand(0,100);
				
				if($groupid) {
					$db->query(sprintf("UPDATE `groups` SET `group_cover` = '%s' WHERE `groups`.`group_id` = %s",$db->real_escape_string($refresh_name),$db->real_escape_string($group['group_id'])));
				} elseif($pageid) {
					$db->query(sprintf("UPDATE `pages` SET `page_cover` = '%s' WHERE `pages`.`page_id` = %s",$db->real_escape_string($refresh_name),$db->real_escape_string($page['page_id'])));
				} else {
				    $db->query(sprintf("UPDATE `users` SET `cover` = '%s' WHERE `users`.`idu` = %s",$db->real_escape_string($refresh_name),$db->real_escape_string($user['idu'])));
				}
				
	            // Return success
				echo '<script>coverRepositioned("'.$db->real_escape_string($refresh_name).'");</script>';
				
			} else {
				echo ($bad_quality) ? '<script>returnRepositon(0,"'.$TEXT['_uni-upl_hgh_q_t_cnt'].'");</script>' : '<script>returnRepositon(0,"'.$TEXT['_uni-cvr_rep_bnt_available'].'");</script>';
			}	
	
		}
		
	} else {
		// User logged out
		$return = showError($TEXT['lang_error_connection2']);
	}
} else {
	// No credentials set
	$return = showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>