<?php
//--------------------------------------------------------------------------------------//
//                          Breeze Social networking platform                           //
//                                     PHP PUBLIC CLASSES                               //
//--------------------------------------------------------------------------------------//

class access {
	
	// Get template
	function getTemplate($name) {	
		global $TEXT;

		// Get template
		return (file_exists('themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'])) ? 'themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'] : '../../../themes/'.$TEXT['theme'].'/html/public/'.$name.$TEXT['templates_extension'];
	
	}

	// Generate profile avatars (PRIVACY PROTECTED)
	function getImage($id,$privacy,$photo) {
		
		// Privacy check
		if(substr($photo, 0, 7) == "default") return $photo ;
		
		// Else confirm privacy check and return image
		return ($privacy == 1) ? 'private.png' : $photo;
		
	}
	
	// Generate profile navigation
    function genNavigation($user) {
		global $TEXT;
		
		// Set content for theme
        $TEXT['temp-user_id'] = protectXSS($user['idu']);
        $TEXT['temp-image'] = protectXSS($user['image']);
        $TEXT['temp-cover'] = protectXSS(getCoverSrc($user['cover']));
        $TEXT['temp-last_name'] = protectXSS($user['last_name']);	
	    $TEXT['temp-username'] = protectXSS($user['username']);
        $TEXT['temp-first_name'] = protectXSS($user['first_name']);
        $TEXT['temp-Name_navigation_14'] = protectXSS(fixName(14,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_30'] = protectXSS(fixName(30,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        
		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/public/main_header'.$TEXT['templates_extension']);

	}

	// Get recent photos
    function getPhotos($user) { 
	    global $TEXT;
		
		// Reset
		$images = $rows = array();
		
		// Check privacy
		if($user['p_posts']) {
			
			return $TEXT['_uni-Hidden_information'];
		
		} else {
			
			// Select photos
			$photos = $this->db->query(sprintf("SELECT * FROM `user_posts` WHERE `post_type` = '1' AND `post_by_id` = '%s' ORDER BY `user_posts`.`post_id` DESC LIMIT 5",$this->db->real_escape_string($user['idu'])));
		
			// If photos exists
			if(!empty($photos) && $photos->num_rows) {
			
				// Fetch photos
				while($row = $photos->fetch_assoc()) {
			    	$rows[] = $row;
				}
				
				// Parse images
				foreach($rows as $row) {
					
					$get_images = explode(',', $row['post_content']);
					$images[] = $get_images[0];
					
				}
		
		        // Count images and create responsive modal div
				if(count($images) == 1) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=650&h=300" style="margin-top:5px;max-width:100%;max-height:450px;" class="border">
							</div>';	
								
				} elseif(count($images) == 2) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;width: 48.73999%;max-width:100%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 3) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;width:32.2%;max-height:450px;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:5px;max-width:100%;max-height:450px;width:32.2%;" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 4) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=200" style="margin-top:2px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
							</div>';
						
				} elseif(count($images) == 5) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_5" src="'.$TEXT['installation'].'/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
						</div>';
						
				} elseif(count($images) == 6) {
					$modal_data = '<div id="USER_IMG" class="clear center post-image">
		    					<img id="post_view_main_image_1" src="'.$TEXT['installation'].'/thumb.php?src='.$images[0].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_2" src="'.$TEXT['installation'].'/thumb.php?src='.$images[1].'&fol=c&w=300&h=200" style="margin-top:5px;max-width:100%;max-height:450px;width: 48.73999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_3" src="'.$TEXT['installation'].'/thumb.php?src='.$images[2].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_4" src="'.$TEXT['installation'].'/thumb.php?src='.$images[3].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
		    					<img id="post_view_main_image_5" src="'.$TEXT['installation'].'/thumb.php?src='.$images[4].'&fol=c&w=300&h=300" style="margin-top:2px;max-width:100%;max-height:450px;display:inline-block;width: 32.23999%" class="brz-border brz-border-super-grey brz-animate-opacity brz-align-center">
						</div>';
						
				}

				// Add heading and inclose modal
				return '<div id="profile_about_c11" style="margin:0px 10px 15px 0px;" class="brz-dettachable brz-detach-to-threequarter block-container rounded padding-10-0">
				        	
							<div class="h7 b2 padding-10 dark-font-only">'.$TEXT['_uni-Recent_photos'].'</div>
						    '.$modal_data.'
							<div class="clear2 margin-10-0-0-0 center noflow">
								<div onclick="loadModal(1);loadLogin(1,'.$user['idu'].');" class="btn btn-medium btn-light">'.$TEXT['_uni-Sw_mr_phts'].'</div>
							</div>

						</div>';
					
			}
		}
	}
	
	// Get similar users
    function getSimilarUsers($user) { 
		global $TEXT,$profile,$page_settings;
		
		// Select users based on first name
		$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4  AND `users`.`idu` != '%s' ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($user['username']).'%', '%'.$this->db->real_escape_string($user['first_name']).'%',$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));

		// If empty try last name
		if($users->num_rows == 0) {
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`username` LIKE '%s' OR concat_ws(' ', `users`.`first_name`, `users`.`last_name`) LIKE '%s') AND `users`.`state` != 4  AND `users`.`idu` != '%s' ORDER BY `users`.`idu` DESC LIMIT %s", '%'.$this->db->real_escape_string($user['username']).'%', '%'.$this->db->real_escape_string($user['last_name']).'%',$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));
		}
		
		// If empty select random
		if($users->num_rows == 0) {
			$users = $this->db->query(sprintf("SELECT * FROM `users` WHERE (`users`.`state` != 4  AND `users`.`idu` != '%s') ORDER BY `users`.`idu` DESC LIMIT %s",$this->db->real_escape_string($user['idu']),$page_settings['public_profile_similar']));
		}
		
		// Fetch users
		if(!empty($users)) {
			
			while($row = $users->fetch_assoc()) {
				$results[] = $row;
		    }
			
			// Reset
			$people = '';
			
			$usr_tpl = display(templateSrc('SRC',1).'/public/in_list'.$TEXT['templates_extension'],0,1);
			
			foreach($results as $row) {

				// Set data for template
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-user_image'] = $this->getImage($row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_ttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(32,$row['username'],$row['first_name'],$row['last_name']));
				$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_verified_batch'] = $profile->verifiedBatch($row['verified'],1);
				$TEXT['temp-invite_cnt'] = $content[$index];
				
				// Add user to list
				$people .= display('',$usr_tpl);
		
			}
			
			// Add heading and inclose modal
			return '<div id="profile_about_similiar" style="margin:0px 10px 15px 0px;" class="block-container rounded padding-10-0">
							<div class="bottom-divider h7 b2 padding-10 dark-font-only">'.$TEXT['_uni-Similar_users'].'</div>
							'.$people.'
						</div>';
			
		}
	}
	
	// List followers/followings
    function getUsers($type,$user) { 
	    global $TEXT,$profile,$page_settings;
		// TYPE 1 : Followers
		// TYPE 0 : Followings
	
		$people = implode(',', ($type == 0) ? $profile->listFollowings($user['idu']) : $profile->listFollowers($user['idu']));
	    
		// Select users
		$result = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` IN (%s) ORDER BY `users`.`idu` DESC LIMIT %s", $people, $page_settings['public_profile_followers']));

	    // Reset
		$rows = array(); 
	    
		// If users exists
		if(!empty($result) && $result->num_rows) {
			
			// Fetch users
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			// Add Header if available
			$people = '';
			
			// Generate user from each row
		    foreach($rows as $row) {
				$people .= '<a class="padding-5" href="'.$TEXT['installation'].'/'.$row['username'].'">
				                <img src="'.$TEXT['installation'].'/thumb.php?src='.$this->getImage($row['idu'],$row['p_image'],$row['image']).'&fol=a&w=70&h=70" class="circle img-35">
				            </a>';				
			}
			
			return $people;
			
		} else {
			return $TEXT['_uni-No_det_show'];
		}
	}
	
    function getFavourites($row) { 
	    global $TEXT;
		
		// Set name
		$TEXT['temp-Name'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);

		// Get list of followers
		$TEXT['temp-followers'] = ($row['p_followers']) ? $TEXT['_uni-Hidden_information'] : $this->getUsers(1,$row);
		
		// Get list of followings
		$TEXT['temp-followings'] = ($row['p_followings']) ? $TEXT['_uni-Hidden_information'] : $this->getUsers(0,$row);

		// Display favourites
		return display($this->getTemplate('favourites_profile'));
	
	}
	
	// Get user about
    function getAbout($row) { 
	    global $TEXT;
		
		// User name
		$TEXT['temp-Name'] = fixName(32,$row['username'],$row['first_name'],$row['last_name']);
		
		// User bio
		$TEXT['temp-Bio'] = (empty($row['bio'])) ? $TEXT['_uni-No_addt_det_show'] : $row['bio'];
		
		// PRofession
		$TEXT['temp-Work'] = ($row['p_profession'] || empty($row['profession'])) ? $TEXT['_uni-Hidden_information'] : $row['profession'];
		
		// Education
		$TEXT['temp-Education'] = ($row['p_study'] || empty($row['study'])) ? $TEXT['_uni-Hidden_information'] : $row['study'];
		
		// Hometown
		$TEXT['temp-Hometown'] = ($row['p_hometown'] || empty($row['hometown'])) ? $TEXT['_uni-Hidden_information'] : $row['hometown'];
		
        // Current city(lIVING)
		$TEXT['temp-Living'] = ($row['p_location'] || empty($row['living'])) ? $TEXT['_uni-Hidden_information'] : $row['living'];
	
		// Display about section
		return display($this->getTemplate('about_profile'));
	
	}
	
	// Get profile top
    function profileTop($row) {
        global $TEXT,$profile;
		
        $row['cover'] = protectXSS(getCoverSrc($row['cover']));

		// Get owner profile picture
		$TEXT['temp-profile_picture'] = $this->getImage($row['idu'],$row['p_image'],$row['image']);
			
		// Get owner cover photo
		$TEXT['temp-cover_photo'] = $this->getImage($row['idu'],$row['p_cover'],$row['cover']);
			
		// If reposition applied
		if (strpos($TEXT['temp-cover_photo'], 'rep_') !== false || strpos($TEXT['temp-cover_photo'], 'fault') !== false) {
    		// Leave
		} else {
			$TEXT['temp-cover_photo'] = (file_exists((explode('themes',$t_src)[0]).'uploads/profile/covers/rep_'.$TEXT['temp-cover_photo'])) ? $TEXT['temp-cover_photo'] : $TEXT['temp-cover_photo'].'?fault';
		}
			
		$TEXT['temp-user_id'] = $row['idu'];
		$TEXT['temp-verified'] = $profile->verifiedBatch($row['verified']);
		$TEXT['temp-user_name_25'] = fixName(25,$row['username'],$row['first_name'],$row['last_name']);
		$TEXT['temp-username'] = $row['username'];

		$content = display(templateSrc('/public/profile'));

        // Return profile top			
		return $content;
	
	}		
}

// Add global uncions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}
?>