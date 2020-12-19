<?php
header('Content-Type: text/plain; charset=utf-8;');

require_once("main/config.php");                // Import configuration
require_once('require/main/database.php');      // Import database connection
require_once('require/main/classes.php');       // Import all classes
require_once('require/main/settings.php');      // Import settings
require_once('language.php');                   // Import language

// Send headings
echo '{"apiVersion":"1.0.0", "data": ';

// Verifiy content
if(isset($_GET['get']) && in_array($_GET['get'], array("user","posts","special"))) {
	
	// Create class
	$profile = new main();
	$profile->db = $db;
	
	// API mode to check special features
	if($_GET['get'] == "special") {
		
		// Check whether user is verified
		if(isset($_GET['verify'])) {
			
			// Fetch user
			$get_user = (is_numeric($_GET['verify'])) ? $profile->getUserByID($_GET['verify']) : $profile->getUserByUsername($_GET['verify']);
		
		    $data['verified'] = ($get_user['verified']) ? '1' : '0';
			
			echo ($get_user['idu']) ? json_encode($data) : json_encode(array('ERROR' => 'User does\'t exists'));
			
	    // Check whether username exists
		} elseif(isset($_GET['isusername'])) {
			
			// Fetch user
			$get_user = $profile->isUsernameExists($_GET['isusername']);
		
		    $data['in_use'] = ($get_user) ? '1' : '0';
			
			echo json_encode($data);
			
		// Check whether username exists
		} elseif(isset($_GET['ismail'])) {
			
			// Fetch user
			$get_user = $profile->isEmailExists($_GET['ismail']);
		
		    $data['in_use'] = ($get_user) ? '1' : '0';
			
			echo json_encode($data);
			
		}
		
	// API mode to get posts
	} elseif($_GET['get'] == "posts") {
	
		// Fetch user
		$get_user = (is_numeric($_GET['id'])) ? $profile->getUserByID($_GET['id']) : $profile->getUserByUsername($_GET['id']);
		
		// Confirm post privacy set on user
	    if($get_user['p_posts']) {
			
			json_encode(array('ERROR' => 'This user has set privacy on posts.'));
			
		} else {
			
			
			// Add limits if requested
			if(isset($_GET['limit']) && in_array($_GET['limit'],array(1,2,5,10,12,15,20))) {
				$limit = $db->real_escape_string($_GET['limit']);
			} else {
				$limit = 12;
			}
			
		    // Select photos
			if(isset($_GET['pt']) && $_GET['pt'] == "photos") {
			
			    $posts = $db->query(sprintf("SELECT `post_id`, `post_by_id`, `post_text`, `post_time`, `post_content` as `post_photo`, `post_loves` as `post_likes`, `post_comments` FROM `user_posts` WHERE `post_by_id` = '%s' AND `post_type` = '1' AND `post_deleted` = '0' ORDER BY `post_id` DESC LIMIT 0, %s", $db->real_escape_string($get_user['idu']),$limit));
	
	        // Select videos shared
			} elseif(isset($_GET['pt']) && $_GET['pt'] == "videos") {
			
			    $posts = $db->query(sprintf("SELECT `post_id`, `post_by_id`, `post_text`, `post_time`, `post_content` as `post_video_link`, `post_loves` as `post_likes`, `post_comments` FROM `user_posts` WHERE `post_by_id` = '%s' AND `post_type` = '4' AND `post_deleted` = '0' ORDER BY `post_id` DESC LIMIT 0, %s", $db->real_escape_string($get_user['idu']),$limit));
	
	        // Else select status updates
			} else {
			
			    $posts = $db->query(sprintf("SELECT `post_id`, `post_by_id`, `post_text`, `post_time`, `post_loves` as `post_likes`, `post_comments` FROM `user_posts` WHERE `post_by_id` = '%s' AND `post_type` = '0' AND `post_deleted` = '0' ORDER BY `post_id` DESC LIMIT 0, %s", $db->real_escape_string($get_user['idu']),$limit));
	
			}
		
			$rows = array();
			
			while($row = $posts->fetch_assoc()) {
				$rows[]	= $row; 
			}
			
			// Output data
			echo (!empty($rows)) ? json_encode($rows) : json_encode(array('ERROR' => 'No messages available'));
			
		}

	// API mode to get user data
	} elseif($_GET['get'] == "user") {
				
		// Fetch user
		$get_user = (is_numeric($_GET['id'])) ? $profile->getUserByID($_GET['id']) : $profile->getUserByUsername($_GET['id']);
		
		if($get_user['idu']) {
			
            // Set user Network id
            $data['id'] = $get_user['idu'];
	
			// Set user Network username
			$data['username'] = $get_user['username'];
	
			// Set user firstname
			$data['first_name'] = $get_user['first_name'];
	 
			// Set user lastname
			$data['last_name'] = $get_user['last_name'];
	
			// Set user image
			$data['image'] = ($get_user['p_image']) ? $TEXT['installation'].'/index.php?thumb=1&src=private.png&fol=a&w=245&h=245&q=100' : $TEXT['installation'].'/index.php?thumb=1&src='.$get_user['image'].'&fol=a&w=245&h=245&q=100' ;
		
			// Set user cover
			$data['cover'] = ($get_user['p_cover']) ? $TEXT['installation'].'/index.php?thumb=1&src=private.png&fol=b&w=1093&h=381&q=100' : $TEXT['installation'].'/index.php?thumb=1&src='.$get_user['cover'].'&fol=b&w=1093&h=381&q=100' ;
	
			// Set user verification
			$data['verified'] = $get_user['verified'];
		
			// Set user profession
			$data['profession'] = ($get_user['p_profession']) ? '' : $get_user['profession'];
		
			// Set user education
			$data['study'] = ($get_user['p_study']) ? '' : $get_user['study'];
		
			// Set user gender
			$data['gender'] = ($get_user['p_gender']) ? '' : $get_user['gender'];
		
			// Set user hometown
			$data['hometown'] = ($get_user['p_hometown']) ? '' : $get_user['from'];

			// Set user current living
			$data['living'] = ($get_user['p_location']) ? '' : $get_user['living'];

			// Set user relationship
			$data['relationship'] = ($get_user['p_relationship']) ? '' : $get_user['relationship'];
		
			// Set user website
			$data['website'] = ($get_user['p_web']) ? '' : $get_user['website'];
		
			// Set user bio
			$data['bio'] = $get_user['bio'];
		
			// Output data
			echo json_encode($data);
		
		// Error user not found
		} else {
			echo json_encode(array('ERROR' => 'No data available for the selected user.'));
		}	
	}
} else {
	echo json_encode(array('ERROR' => 'Required parameters not set.'));
}

// Close request
echo '}';
?>