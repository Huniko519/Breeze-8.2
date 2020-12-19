<?php
//--------------------------------------------------------------------------------------//
//                          Breeze Social networking platform                           //
//                                     PHP BLOG CLASSES                                 //
//--------------------------------------------------------------------------------------//

class main {            // Users management
	
	// Properties
	public $db;                   // Database connection
	public $username;             // Username property
	public $password;             // Password property
	public $followings;           // Logged user followers (ARRAY)
	public $followers;            // Logged user followings (ARRAY)
	public $settings;             // Administration settings
	public $admin;                // Administration detection
	
	function logOut() {                                             // Log out user

		// Save login as recent
		if(!empty($_SESSION['username'])) {
			
			$user = (filter_var($_SESSION['username'], FILTER_VALIDATE_EMAIL)) ? $this->getUserByEmail($_SESSION['username']) : $this->getUserByUsername($_SESSION['username']);
			
			if(!empty($user['idu'])) {
				
				// Get login cookie
				$recent_logins = (!empty($_COOKIE['loggedout'])) ? explode(',', preg_replace('/,{2,}/', ',', trim(str_replace('ID_'.$user['idu'].'_ID','',$_COOKIE['loggedout']), ','))) : NULL ;
				
				// Create list
				$login_1 = 'ID_'.$user['idu'].'_ID';
				$login_2 = ($recent_logins[0]) ? ','.$recent_logins[0] : '' ;
				$login_3 = ($recent_logins[1]) ? ','.$recent_logins[1] : '' ;
			
				// Update cookie
				setcookie("loggedout", $login_1.$login_2.$login_3, time() + 30 * 24 * 60 * 60,'/');
			}
		}
		
		// Unset SESSIONs
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	
		// Unset Cookies
		setcookie("username", "", time() + 1 * 1,'/');
		setcookie("password", "", time() + 1 * 1,'/');
		
		return 0;
	}	

	function isLoved($blog,$by_user) {	                            // Return true if blog is loved
		
		// Select love
		$loved = $this->db->query(sprintf("SELECT `id` FROM `blog_loves` WHERE `blog_id` = '%s' AND `by_id` = '%s' ", $this->db->real_escape_string($blog), $this->db->real_escape_string($by_user)));
		
		// Return results
		return ($loved->num_rows) ? 1 : 0;
		
	}	

	function isValue($amount,$tag) {                                // Return iff it's not zero
		return ($amount) ? readAble($amount).' '.$tag : '';
	}	
	
	function getUser() {                                            // Fetch logged user
		
		// Check whether user is using email or username to login
		if(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL)) {	
			$user = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`email` = '%s' AND `users`.`password` = '%s' ", $this->db->real_escape_string(strtolower($this->username)),$this->db->real_escape_string($this->password)));
		} else {
			$user = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`username` = '%s' AND `users`.`password` = '%s' ", $this->db->real_escape_string(strtolower($this->username)), $this->db->real_escape_string($this->password)));
		}
		
		// Return user if exists
		if($user->num_rows) {
			
			// Fetch user
			$fetched = $user->fetch_assoc() ;
			
			// Update user last activity
			$active = $this->db->query(sprintf("UPDATE `users` SET `users`.`active` = '%s' WHERE `users`.`idu` = '%s' ",time(), $this->db->real_escape_string($fetched['idu'])));
		
		    return $fetched;
			
		} else {
			
			// Else unset credentials
			return $this->logOut();
		}	
	}
	
	function getUserByID($id) {                                     // Fetch user using IDU
		
		$user = $this->db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $this->db->real_escape_string($id)));	

		// Fetch user if exists
		return ($user->num_rows !== 0) ? $user->fetch_assoc() : 0;

	}
	
	function getBlogByID($blog_id) {                                // Fetch blog using blog id
		
		// Select blog using blog id
		$blog = $this->db->query(sprintf("SELECT * FROM `blogs` WHERE `blogs`.`blog_id` = '%s' ", $this->db->real_escape_string($blog_id)));

		// Fetch if posts exists
		return ($blog->num_rows) ? $blog->fetch_assoc() : 0;
			
	}

	function getCommentByID($comment_id) {                          // Fetch comment using comment id
		
		// Select comment using comment id
		$comment = $this->db->query(sprintf("SELECT * FROM `blog_comments` WHERE `blog_comments`.`id` = '%s' ", $this->db->real_escape_string($comment_id)));
		
		// Fetch comment exists
		return ($comment->num_rows) ? $comment->fetch_assoc() : 0;
		
	}

	function numberComments($blog_id) {                             // Number comments of blog id

		// Count comments
		$comments = $this->db->query(sprintf("SELECT COUNT(*) FROM `blog_comments` WHERE `blog_comments`.`blog_id` = '%s' ", $this->db->real_escape_string($blog_id)));
		
		list($numbers) = $comments->fetch_row();
		
		// Return number of rows
		return $numbers;
		
	}
	
	function numberBlogs($user_id) {                                // Number blogs of user id
        
		// Select all blogs
		$blogs = $this->db->query(sprintf("SELECT COUNT(*) FROM `blogs` WHERE `blogs`.`blog_by_id` = '%s' ", $this->db->real_escape_string($user_id)));
		
		list($numbers) = $blogs->fetch_row();
		
		// Return number of rows
		return $numbers;
		
	}
	
	function numberFollowers($user_id) {                            // Number followers of user id
		
		// Select all followers
		$followers = $this->db->query(sprintf("SELECT COUNT(*) FROM `friendships` WHERE `friendships`.`user2` = '%s' AND `friendships`.`status` = '1' ", $this->db->real_escape_string($user_id)));
		
		list($numbers) = $followers->fetch_row();
		
		// Return number of followers
		return $numbers;
	
	}
	
	function numberFollowings($user_id) {                           // Number followings of user id
		
		// Select all followings
		$followings = $this->db->query(sprintf("SELECT COUNT(*) FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`status` = '1' ", $this->db->real_escape_string($user_id)));
		
		list($numbers) = $followings->fetch_row();
		
		// Return number of followings
		return $numbers;
	
	}
	
	function listFollowings($user_id) {                             // Return user followings(array)
		
		// Select users followed
		$result = $this->db->query(sprintf("SELECT `user2` FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`status` = '1' ", $this->db->real_escape_string($user_id)));	

		$list = array();	
		
		if(!empty($result) && $result->num_rows !== 0) {
			
			// return array of user IDs if users exists
			while($row = $result->fetch_assoc()) {
			    $list[] = $row['user2'];
		    }
			
			// Return ARRAY
			return $list;
			
		} else {
			return '';
		}	
	}

    function listLikers($blog_id) {                                 // Return blog likers
		
		// Select followers
		$result = $this->db->query(sprintf("SELECT `by_id` FROM `blog_loves` WHERE `blog_loves`.`blog_id` = '%s' ", $this->db->real_escape_string($blog_id)));	
		
		$list = array("0");
		
		if(!empty($result) && $result->num_rows !== 0) {
			
			// return array of user IDs if users exists
			while($row = $result->fetch_assoc()) {
			    $list[] = $row['by_id'];
		    }	
	
		} 

		return $list;
	
	}
	
    function getCategories($type=null) {                            // Return blog categories
	    // TYPE 1 : Only `cid` Col
	    // TYPE NULL : Entire row
		
		$typed = ($type) ? '`cid`' : '*';
		
		// Select categories
		$cats = $this->db->query(sprintf("SELECT %s FROM `blog_categories` ORDER BY `cat_name` ",$typed));

		// Reset
		$cids = $categoreis = array();

		// Fetch categories
		while($row = $cats->fetch_assoc()) {

		    if($type) {
				$cids[] = $row['cid'];
			} else {
				$categoreis[] = $row;
			}
		}
		
		// Return categories
		return ($type) ? $cids : $categoreis;
	
	}
	
	function listFollowers($user_id) {                              // Return user followers(array)
		
		// Select followers
		$result = $this->db->query(sprintf("SELECT `user1` FROM `friendships` WHERE `friendships`.`user2` = '%s' AND `friendships`.`status` = '1'", $this->db->real_escape_string($user_id)));	
		
		$list = array();
		
		if(!empty($result) && $result->num_rows !== 0) {
			
			// return array of user IDs if users exists
			while($row = $result->fetch_assoc()) {
			    $list[] = $row['user1'];
		    }	
			
			// return ARRAY
			return $list;	
		} else {
			return '';
		}	
	}	

	function parseAdd($add,$fixed = FALSE) {                        // Parse adds(sponsor)
		global $TEXT; 
		
		// Set add
		$TEXT['temp-ad1'] = $add;
		
		// Parse fixed ads
		if($fixed && !empty($add)) {
			
			return display(templateSrc('/ads/fixed_ad'));
		
		// Parse Pop up ads
	    } elseif(!empty($add)) {
			
			$TEXT['temp-id'] = mt_rand(100, 9999);
			
			return display(templateSrc('/ads/popup_ad'));
			
		} else {
			return '';
		}		
	}	
	
    function genNavigation($user,$type=null) {                      // Generate user navigation
		global $TEXT;
		
		// Set content for theme
        $TEXT['temp-user_id'] = protectXSS($user['idu']);
        $TEXT['temp-image'] = protectXSS($user['image']);
        $TEXT['temp-cover'] = protectXSS($user['cover']);
        $TEXT['temp-last_name'] = protectXSS($user['last_name']);	
	    $TEXT['temp-username'] = protectXSS($user['username']);
        $TEXT['temp-first_name'] = protectXSS($user['first_name']);
        $TEXT['temp-Search_placeholder'] = sprintf($TEXT['_uni-Search_placeholder_2'],$TEXT['web_name']);			
        $TEXT['temp-Name_navigation_14'] = protectXSS(fixName(14,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_30'] = protectXSS(fixName(30,$TEXT['temp-username'],$TEXT['temp-first_name'],$TEXT['temp-last_name']));			
        $TEXT['temp-Name_navigation_title'] = protectXSS(sprintf($TEXT['_uni-Profile_load_text'],$TEXT['temp-Name_navigation_14']));			
		
		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/navigations/main_blogs'.$TEXT['templates_extension']);

	}

    function genPubNavigation() {                                   // Generate Public navigation
		global $TEXT;
		
		$TEXT['temp-Search_placeholder'] = sprintf($TEXT['_uni-Search_placeholder_2'],$TEXT['web_name']);			
        
		// Generate navigation from template 
		return display('themes/'.$TEXT['theme'].'/html/navigations/main_blogs_public'.$TEXT['templates_extension']);

	}	
	
	function verifiedBatch($x,$type = 0) {                          // Return verified batch if profile is verified
		global $TEXT;
		
		// If small icon is requested
		$size = ($type) ? 'width="14px"': 'width="18px"';
		
		// Set responsiveness
		$responsive = ($type) ? '' : 'responsive-medium';
		
		// Return verified image if profile is verified
		return ($x) ? '<img class="'.$responsive.'" title="'.$TEXT['_uni-Profile_verified'].'" alt="Image" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/icons/others/verified.svg" '.$size.'></img>' : '';	
	
	}

	function getRelationButton($type,$id,$p = NULL) {               // Generate user relation button
	    global $TEXT;
		
		// Unique HTML element id 
		$co = md5(mt_rand(100,99999).time());
		
		if($type == 0 && $p == 0) {    // Follow button
			return '<button id="fo32'.$co .'" class="btn btn-medium btn-theme" onclick="follow('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Follow'].'\',\''.$TEXT['_uni-Following'].'\');">'.$TEXT['_uni-Follow'].'</button>';
		} elseif($type == 0 && $p == 1) {   // Request button 
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="request('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Request'].'\',\''.$TEXT['_uni-Requested'].'\');">'.$TEXT['_uni-Request'].'</button>';
		} elseif($type == 1) {         // Following button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-theme btn-active" onclick="unfollow('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Following'].'\',\''.$TEXT['_uni-Follow'].'\');">'.$TEXT['_uni-Following'].'</button>';
		} elseif($type == 2) {         // Undo request button
			return '<button id="fo32'.$co.'" class="btn btn-medium btn-light" onclick="unrequest('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Requested'].'\',\''.$TEXT['_uni-Request'].'\');">'.$TEXT['_uni-Requested'].'</button>';
		} else {                       // Edit profile button
			return '<a id="fo32'.$co.'" class="btn btn-medium btn-light" href="'.$TEXT['installation'].'/?new=blog">'.$TEXT['_uni-Create_a_blog'].'</a>';
		}	
    }	

	function getLikeBtn($id,$type) {                                // Generate blog like btn
	    global $TEXT;
		
		// Unique HTML element id 
		$co = md5(mt_rand(100,99999).time());
		
		if($type == 0) {    // Like button
			return '<button id="fo32'.$co .'" class="btn btn-small btn-small-inline btn-theme" onclick="likeBlog('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Like'].'\',\''.$TEXT['_uni-Liked'].'\');">'.$TEXT['_uni-Like'].'</button>';
		} elseif($type == 1) {         // Liked button
			return '<button id="fo32'.$co.'" class="btn btn-small btn-small-inline btn-theme btn-active" onclick="unlikeBlog('.protectXSS($id).',\'fo32'.$co.'\',\''.$TEXT['_uni-Liked'].'\',\''.$TEXT['_uni-Like'].'\');">'.$TEXT['_uni-Liked'].'</button>';
		}
		
    }

	function getImage($current_id,$id,$privacy,$photo) {	                     // Generate profile avatars (PRIVACY PROTECTED)
		
		// Privacy check
		if($this->admin || $id == $current_id || in_array($id,$this->followings) || substr($photo, 0, 7) == "default") return $photo ;
		
		// Else confirm privacy check and return image
		return ($privacy == 1) ? 'private.png' : $photo;
		
	}
	
	function blogAct($blog_id,$user,$type = 0) {                                 // Blog liker
        // USER_ID : Logged user	
		// TYPE 0  : Remove love (UNLIKE)		
        // TYPE 1  : Do love (LIKE)
		
		global $TEXT;
		
		if(!$user['idu']) {
		    $user = $this->getUserById($user);
		}
		
		$user_id = $user['idu'];
		
		// Is blog exists
        $blog = $this->db->query(sprintf("SELECT * FROM `blogs` WHERE `blog_id` = '%s'", $this->db->real_escape_string($blog_id)));

	    // If blog exists
		if(!empty($blog)) {
			$blog = $blog->fetch_assoc();
            $verified = $this->isLoved($blog['blog_id'],$user_id);           // Check whether blog is already loved

			// Escape variables for SQL Query
			$blog_id_esc = $this->db->real_escape_string($blog['blog_id']);
			$user_id_esc = $this->db->real_escape_string($user_id);

			// Do love(Like)
			if($type == 1) {
				
				// If blog is not loved
				if(!$verified) {
					
					// Insert new Love(Like)
					$query = $this->db->query("INSERT INTO `blog_loves` (`id`, `blog_id`, `by_id`,`time`) VALUES (NULL, '$blog_id_esc', '$user_id_esc', CURRENT_TIMESTAMP) ");
					
					// Update blog loves +1
					$query = $this->db->query("UPDATE blogs SET blogs.blog_loves = blogs.blog_loves + 1 WHERE blogs.blog_id = $blog_id_esc ");

				}
			}
			
			// Remove love
			if($type == 0) {
				
				// If blog is loved
				if($this->isLoved($blog['blog_id'],$user_id)) {

					// Delete blog love
					$query = $this->db->query("DELETE FROM `blog_loves` WHERE `blog_id` = '$blog_id_esc' AND `by_id` = '$user_id_esc' ");
					
					// Update blog loves -1
					$query = $this->db->query("UPDATE blogs SET blogs.blog_loves = blogs.blog_loves - 1 WHERE blogs.blog_id = '$blog_id_esc' ");

				}
			}				
		}
	}

	function createBlog($user,$heading,$image,$description,$content,$type,$tags,$share) {
        global $TEXT;
		
		require_once(__DIR__.'/HTMLPurifier/HTMLPurifier.auto.php');

		// Clean html data
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$content = $purifier->purify($content);

		$query = sprintf("INSERT INTO `blogs` (`blog_id`, `blog_by_id`, `blog_heading`, `blog_description`, `blog_image`, `blog_text`, `blog_tags`, `blog_type`, `posted_as`, `posted_at`, `blog_loves`, `blog_comments`, `blog_views`, `blog_time`) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '0', '0', '0', '0', '0', CURRENT_TIMESTAMP);",  $this->db->real_escape_string($user['idu']),$this->db->real_escape_string($heading),$this->db->real_escape_string($description),$this->db->real_escape_string($image),$this->db->real_escape_string($content),$this->db->real_escape_string($tags),$this->db->real_escape_string($type)) ;

		// Return results  
		if ($this->db->query($query) === TRUE) {
		
		    $id = $this->db->insert_id ;
			
		    // Here is the new line
		    $this->db->query(sprintf("UPDATE `users` SET `blogs` = `blogs` + 1 WHERE `idu` = '%s'",$this->db->real_escape_string($user['idu'])));
		    
			$this->db->query(sprintf("UPDATE `blog_categories` SET `cat_img` = '%s' WHERE `cid` = '%s'",$this->db->real_escape_string($image),$this->db->real_escape_string($type)));
 
            // Wrote on profile timeline
            if($share) {
				$this->db->query(sprintf("INSERT INTO `user_posts`(`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_tags`, `post_time`, `post_extras`, `link_title`, `link`, `link_description`, `link_img`, `post_deleted`) VALUES (NULL, '%s', '%s', '', '10', '', CURRENT_TIMESTAMP, '0,0,0', '', '', '', '', '0');", $this->db->real_escape_string($user['idu']), $this->db->real_escape_string($id)));
			}
			
            return array($TEXT['installation'].'/blogs/'.$id,0);
		} else {
			return array(0,$TEXT['_uni-Error_mysql']);
		}
	}


	function followUser($follow,$by) {                                           // Follow user
	    global $TEXT,$page_settings;
		
		// Fetch target user
		$target = $this->getUserByID($follow);
		
		if(Blocker::IsTypeBlocked(Blocker::GetBlock($by['idu'], $follow), 'follow'))
			return false;
			
		// If target exists
		if(!empty($target['idu']) && $target['idu'] !== $by['idu']) {

		    // Number target user followers
		    $followers = $this->db->real_escape_string($this->numberFollowers($target['idu']));	
			
			// Whether target user is private
			if($target['p_private'] == 0) {	
				
				// Insert follow and increase target users followers count
				$status = 1;
				$followers = $followers + 1;
				
			} else {	
				
				// Insert request to target user
				$status = 2;
			}
			
			// Escaping variable or MySQL query
			$followed_esc = $this->db->real_escape_string($target['idu']);     // User going to target
			$by_user_esc = $this->db->real_escape_string($by['idu']);            // User going to follow

            // Delete previous relations
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user2` = '$followed_esc' AND `friendships`.`user1` = '$by_user_esc' ;" ;	
		    
			// Insert new relationship
			$query.= "INSERT INTO `friendships`(`id`, `user2`, `user1`, `status`, `time`) VALUES (NULL, '$followed_esc', '$by_user_esc', '$status', CURRENT_TIMESTAMP) ;" ;
		    
			// If target followed and target user has enabled notifications on new followers
			if($status == 1 && $target['n_follower'] == 1) {
				
				// Insert new follower notification to target user
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$by_user_esc', '$followed_esc', '0','0','1','0', CURRENT_TIMESTAMP) ;" ;
			
			} elseif($status == 2){
				
				// If target user is request Insert a new request notification to target user
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$by_user_esc', '$followed_esc', '0','0','2','0', CURRENT_TIMESTAMP) ;" ;
			
			}
			
			// Always Updating target user followers count helps repairing previous jerks
		    $query.= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$followed_esc' ;" ;
		   
		    // Send new follower email if user has enable email notifications
			if($target['e_follower']) {
				mailSender($page_settings, $target['email'], $TEXT['_uni-New_follower_a_ttl_sml'], sprintf($TEXT['_uni-New_follower_a_ttl'], fixName(35,$target['username'],$target['first_name'],$target['last_name']), $TEXT['installation'].'/'.$by['username'], fixName(35,$by['username'],$by['first_name'],$by['last_name'])), $TEXT['web_mail']);
			}
		   
            // Perform MySQL multi_query
		    return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
	function unFollowUser($followed,$by) {                                       // UN-follow user
		
		// Fetch target users
		$following = $this->getUserByID($followed);
		
		// If target exists
		if(!empty($following['idu'])) {
			
			// Count target followers
			$followers = $this->db->real_escape_string($this->numberFollowers($following['idu']));	
			
			// If performer has requested the target 
			if($this->isRequested($by['idu'],$following['idu']) == 0) {
				$followers = $followers - 1;
			}
			
			// Escapin variables for MySQL Query
			$followed_esc = $this->db->real_escape_string($following['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete relationship
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user2` = '$followed_esc' AND `friendships`.`user1` = '$by_user_esc' ;" ;
		    
			// Delete notifications related to this relationship
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$by_user_esc' AND `notifications`.`not_to` = '$followed_esc' AND `notifications`.`not_type` IN(1,2) ;" ;
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$followed_esc' AND `notifications`.`not_to` = '$by_user_esc' AND `notifications`.`not_type` = '3' ;" ;
			
			// Update target followers
			$query .= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$followed_esc' ;" ;
			
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target doesn't exists
			return 0;
		}
	}
	
	function deleteRequest($delete,$by) {                                        // Delete follow request
		
		// Fetch target user
		$deleted = $this->getUserByID($delete);
		
		// If target exists
		if(!empty($deleted['idu'])) {
			
			// Escape variables for MySQL Query
			$deleted_esc = $this->db->real_escape_string($deleted['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete request
			$query = "DELETE FROM `friendships` WHERE `friendships`.`user1` = '$deleted_esc' AND `friendships`.`user2` = '$by_user_esc' AND `friendships`.`status` = 2 ;" ;
		    
			// Delete notifications related to this request
			$query.= "DELETE FROM `notifications` WHERE `notifications`.`not_to` = $by_user_esc' AND `notifications`.`not_from` = '$deleted_esc' AND `notifications`.`not_type` = '2'  ;" ;
	
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
	function allowUser($allowed_id,$by) {                                        // Allow follow request	
		global $TEXT,$page_settings;
		
		// Fetch target user
		$allow = $this->getUserByID($allowed_id);
		
		// If target user exists
		if(!empty($allow['idu'])) {	
		    
			// Count current user followers
			$followers = $this->db->real_escape_string($this->numberFollowers($by['idu'])) + 1;	
			
			//  Escape variables for MySQL Query
			$allowed_esc = $this->db->real_escape_string($allow['idu']);
			$by_user_esc = $this->db->real_escape_string($by['idu']);
			
			// Delete all notifications related 
			$query = "DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$allowed_esc' AND `notifications`.`not_to` = '$by_user_esc' AND `not_type` =  '2';" ;
		    
			// Update relationship as accepted
			$query .= "UPDATE `friendships` SET `friendships`.`status` = '1' , `friendships`.`time` = CURRENT_TIMESTAMP WHERE `friendships`.`user1` = '$allowed_esc' AND `friendships`.`user2` = '$by_user_esc' ;" ;	
			
			// Update current user followers count (+1)
			$query .= "UPDATE `users` SET `followers` = '$followers' WHERE `users`.`idu` = '$by_user_esc' ;" ;
			
			// If target user has enabled notifications on request accepts insert it
			if($allow['n_accept'] == 1) { 
				$query.= "INSERT INTO `notifications`(`id`, `not_to`, `not_from`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$allowed_esc', '$by_user_esc', '0','0','3','0', CURRENT_TIMESTAMP) ;" ;
			}
			
			// Send request accepted email if user has enable email notifications
			if($allow['e_accept']) {
				mailSender($page_settings, $allow['email'], $TEXT['_uni-New_request_ttl'], sprintf($TEXT['_uni-request_accep_f'], fixName(35,$allow['username'],$allow['first_name'],$allow['last_name']), $TEXT['installation'].'/'.$by['username'], fixName(35,$by['username'],$by['first_name'],$by['last_name'])), $TEXT['web_mail']);
			}
			
			// If current user has enabled notifications on new followers insert it
			if($by['n_follower'] == 1) {
				$query.= "INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$allowed_esc', '$by_user_esc', '0','0','1','0', CURRENT_TIMESTAMP) ;" ;
			}
			
			// Send new follower email if user has enable email notifications
			if($by['e_follower']) {
				mailSender($page_settings, $by['email'], $TEXT['_uni-New_follower_a_ttl_sml'], sprintf($TEXT['_uni-New_follower_a_ttl'], fixName(35,$by['username'],$by['first_name'],$by['last_name']), $TEXT['installation'].'/'.$user['username'], fixName(35,$user['username'],$user['first_name'],$user['last_name'])), $TEXT['web_mail']);
			}
			
			// Perform MySQL multi_query
			return ($this->db->multi_query($query) === TRUE) ? 1 : 0;
			
		} else {
			
			// If target user doesn't exists
			return 0;
		}
	}
	
    function parseText($text,$type=NULL) {                          // Parse URLs,@mentions,#hashtags for output
		global $TEXT,$emoji_ids,$emoji_files;
		
		// Include emojis list
		require_once(__DIR__ . '/presets/preset_emojis.php');	
		
		// Type 1 remove emojis
		if($type) {  
			foreach($emoji_ids as $key=>$emj) {
				$text = str_replace($emj,'', $text);
			}
			return $text;
		}
		
		// Parse links
		$parsed_urls = preg_replace_callback('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', "linkHref", $text);
		
		// Get mentions type
		$type = ($this->settings['mentions_type']) ? '$2 !' : '@$2';
		
		// Parse mentions and hashtags
		$parsedMessage = preg_replace(array('/(^|[^a-z0-9_])([a-z0-9_]+)!/i','/(^|[^a-z0-9_])#(\w+)/u'), array('$1<a class="h6 b2 theme-font" href="'.$TEXT['installation'].'/$2">'.$type.'</a>','$1<a class="h6 b2 theme-font" href="'.$TEXT['installation'].'/search/tag/$2">#$2</a>'), preg_replace('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '$1<a class="h6 b2 theme-font" href="'.$TEXT['installation'].'/$2">'.$type.'</a>', $parsed_urls));
		
		// Parse emojis
		foreach($emoji_ids as $key=>$emj) {
			$parsedMessage = str_replace($emj,'<img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/emojis/'.$emoji_files[$key].'.png" height="18" width="18" data-emoji="'.str_replace(array("}","{"),array("",""),$emoji_ids[$key]).'">', $parsedMessage);
		}
		
		return $parsedMessage;
	}	

	function getCategoryById($id) {                                 // Return blog category

		// Select category
		$cat = $this->db->query(sprintf("SELECT * FROM `blog_categories` WHERE `cid` = '%s' ",$this->db->real_escape_string($id)));

		// Return categories
		return ($cat->num_rows !== 0) ? $cat->fetch_assoc() : 0;
	
	}
	
	function nameCategory($cat_id) {                                // Return blog type name
        global $TEXT;
		
	    if($cat_id) {
			
			// Select name index
			$cats = $this->db->query(sprintf("SELECT `cat_name` FROM `blog_categories` WHERE `cid` = '%s' ",$this->db->real_escape_string($cat_id)));

			$name = $cats->fetch_assoc();

			// Return name
			$name_hre = (isset($TEXT[$name['cat_name']])) ? $TEXT[$name['cat_name']] : $TEXT['_uni-Others'] ;
			
		} else {
			$name_hre = $TEXT['_uni-Others'];
		}
		
		return '<a href="'.$TEXT['installation'].'/blogs/category/'.$cat_id.'" >'.$name_hre.'</a>';
	
	}
	
    function parseBlogText($text,$type=null) {                      // Parse blog text
	    global $TEXT;
		// TYPE 1 : RETURN only text

		// Get custom tags
		preg_match_all('#<h1>(.*?)</h1>#', $text, $h1s);
		preg_match_all('#<h2>(.*?)</h2>#', $text, $h2s);
		preg_match_all('#<h3>(.*?)</h3>#', $text, $h3s);
		preg_match_all('#code="(.*?)"#', $text, $codes);
		
		if($h1s){
			foreach($h1s as $h1) {
			    $text = str_replace('<h1>'.$h1[0].'</h1>',($type) ? '' : '<div class="h12 b3 dark-font-only padding-10-0">'.$h1[0].'</div>',$text);
		    }
		}
		
		if($h2s){
			foreach($h2s as $h2) {
			    $text = str_replace('<h2>'.$h2[0].'</h2>',($type) ? '' : '<div class="h9 b2 dark-font-only padding-10-0">'.$h2[0].'</div>',$text);
		    }
		}
		
		if($h3s){
			foreach($h3s as $h3) {
			    $text = str_replace('<h3>'.$h3[0].'</h3>',($type) ? '' : '<div class="h7 b2 dark-font-only padding-10-0">'.$h3[0].'</div>',$text);
		    }
		}
		
		if($codes) {
			foreach($codes as $code) {
			    $text = str_replace('code="'.$code[0].'"',($type) ? '' : '<div class="h6 padding-10 light-font-only border rounded b1 background-x dark-font-only"><pre>'.htmlspecialchars($code[0]).'</pre></div>',$text);
		    }
		}
		
		return str_replace('src="uploads/blogs', 'src="'.$TEXT['installation'].'/uploads/blogs', $text);
	}
	
    function getParTags($tags,$type=0) {                            // All tags
	    global $TEXT;
		
		if(!empty($tags)) {

			$rows = explode(',',$tags);
			
			$tag_template = display(templateSrc('/blogs/article/tag'),0,1);
			
			$TEXT['temp-add_categories'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-tag'] = $row;
				$TEXT['temp-add_tags'] .= display('',$tag_template);
			}
			
			return display(templateSrc(($type) ? '/blogs/article/tags_box_popular': '/blogs/article/tags_box'));
			
		}
		
	}
	
    function getAllCategories() {                                   // All categoreis
	    global $TEXT;
		
		$rows = array();
		
		$result = $this->db->query(sprintf('SELECT * FROM `blog_categories` ORDER BY `cat_name` ASC'));

		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$cat_template = display(templateSrc('/blogs/home/category_link'),0,1);
			
			$TEXT['temp-add_categories'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-cat_id'] = $row['cid'];
				$TEXT['temp-cat_heading'] = $TEXT[$row['cat_name']];
				$TEXT['temp-add_categories'] .= display('',$cat_template);
			}
			
			return display(templateSrc('/blogs/home/category_box'));
			
		}
		
	}
	
    function getPopularPosts($id=null) {                            // Generate popular blogs
		global $TEXT;
	    
		$rows = array();

		$a_type = ($id) ?  'AND `blogs`.`blog_type` = \''.$this->db->real_escape_string($id).'\'' : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs`, `users` WHERE `blogs`.`blog_by_id` = `users`.`idu` $a_type ORDER BY `blog_views` DESC LIMIT 5"));

		if(!empty($result) && $result->num_rows) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$blog_template = display(templateSrc('/blogs/home/popular'),0,1);
			
			$TEXT['temp-add_popular'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-user_name'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-loop'] = ($count > 4) ? 'true' : 'false';
				$TEXT['temp-add_popular'] .= display('',$blog_template);
			}
			
			return display(templateSrc('/blogs/home/popular_box')) ;
			
		}
	}
	
    function getRecentBlogs($id=null,$type=null) {                  // Generate recent blogs
	    global $TEXT;
	    
		$rows = array();
		
		$a_type = ($type) ?  'WHERE `blog_by_id` = \''.$this->db->real_escape_string($id).'\'' : 'WHERE `blog_type` = \''.$this->db->real_escape_string($id).'\'';
		$add_type = ($id) ? $a_type : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs` $add_type ORDER BY `blog_id` DESC LIMIT 15"));

		$count = $result->num_rows;
		
		if(!empty($result) && $count !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$blog_template = display(templateSrc('/blogs/home/recent'),0,1);
			
			$TEXT['temp-add_recents'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-blog_category'] = $this->nameCategory($row['blog_type']);
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-loop'] = ($count > 4) ? 'true' : 'false';
				$TEXT['temp-add_recents'] .= display('',$blog_template);
			}
			
			return display(templateSrc(($type) ? '/blogs/home/recent_box_beta' : '/blogs/home/recent_box')) ;
			
		}
	}
	
	function addComment($comment,$blog_id,$user) {                  // Blog Comment
	    global $TEXT;
		
		// Is blog exists
        $blog = $this->getBlogByID($blog_id);
		
	    // If blog exists
		if($blog) {
			
			$bloger = $this->getUserByID($blog['blog_by_id']);                   // Fetch the user who posted this blog

			// Escape variables for SQL Query
			$blog_id_esc = $this->db->real_escape_string($blog['blog_id']);
			$user_id_esc = $this->db->real_escape_string($user['idu']);
			$bloger_id_esc = $this->db->real_escape_string($bloger['idu']);
			
			// Parse comment
			$comment_esc = $this->db->real_escape_string(protectInput($comment));
		
			// Insert new Comment
			$query = $this->db->query("INSERT INTO `blog_comments` (`id`, `blog_id`, `by_id`, `comment_text`, `time`) VALUES (NULL, '$blog_id_esc', '$user_id_esc', '$comment_esc', CURRENT_TIMESTAMP) ");

			return $this->getComments($user,$blog_id,0,1).'<script>
							resetForm("form-add-comment-'.protectXSS($blog_id).'");
							$("#form-add-comment-'.protectXSS($blog_id).'").css(\'pointer-events\',\'auto\');
						</script>';

		} else {
			return showError($TEXT['_uni-doesnt-exists-blog']).'<script>
							resetForm("form-add-comment-'.protectXSS($blog_id).'");
							$("#form-add-comment-'.protectXSS($blog_id).'").css(\'pointer-events\',\'auto\');
						</script>';
		}
	}	
	
    function getTrendingBlogs($id=null) {                           // Generate trending blogs
	    global $TEXT;
	    
		$rows = array();
		
		$add_type = ($id) ? 'AND `blog_type` = \''.$this->db->real_escape_string($id).'\'' : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs` WHERE date(`blog_time`) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() $add_type ORDER BY `blog_loves` DESC LIMIT 15"));
		
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$blog_template = display(templateSrc('/blogs/home/trending'),0,1);
			
			$TEXT['temp-add_trending'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-blog_category'] = $this->nameCategory($row['blog_type']);
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-add_trending'] .= display('',$blog_template);
			}
			
			return display(templateSrc('/blogs/home/trending_box'));
			
		}
	}
	
	function isRequested($user_id,$to_id) {                         // Return true if request exists
		
		// Select row which states $user_id as requested to $to_id
		$request = $this->db->query(sprintf("SELECT `id` FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`user2` = '%s' AND `friendships`.`status` = '2' ", $this->db->real_escape_string($user_id),$this->db->real_escape_string($to_id)));
		
		// Return 1 if row exists else return 0
		return ($request->num_rows) ? 1 : 0;
		
	}
	
	function genAccordData($a,$row,$small = NULL) {                 // Generate accordion data of user (PRIVACY PROTECTED)
		global $TEXT;
		
		// Reset 
		$prof = $home = $liv = '';
		
		// Profession details check
		if((!empty($row['profession']) && $row['p_profession'] == 0) || (!empty($row['profession']) && $a == 1)) {
			$prof = '<span title="'.$TEXT['_uni-Profession'].'">'.protectXSS($row['profession']).'</span>';
		}
		
		// Hometown details check
		if(!empty($row['blogs'])) {
			$home = '<span>'.number_format($row['blogs']).' '.$TEXT['_uni-blog_posts'].'</span>';
		}
		
		
		// Current location details check
		if(!empty($row['followers'])) {
			$liv = '<span>'.number_format($row['followers']).' '.$TEXT['_uni-Followers'].'</span>';
		}	
			
		// Return data
        return array($prof,$liv,$home);

	}
	
	function getPermissions($c_id,$u_id,$data,$grouped=NULL,$g_req=NULL) { // Return privacy protection
		
		// Check user privacy
		$private = ($data && is_null($grouped) || (!is_null($grouped) && in_array($data,array("2","3")))) ? 1 : 0 ;

		// If target user is logged user
		if($u_id == $c_id) {
			
			return array($available = 1,$following = 3,$private);
		
		// Else check permissions
		} else {

		    // Check following status
		    if(is_null($grouped)) {
			    $following = (in_array($u_id,$this->followings)) ? 1 : 0 ;
			} else {
			    $following = ($grouped) ? ($grouped == 2) ? 3 : 1 : 0;
			}
			
			// If following
            if($following) return array($available = 1,$following,$private);
			
		    // Else check is requested
		    if(is_null($grouped)) {
			    $requested = (!$following && $this->isRequested($c_id,$u_id)) ? 2 : NULL;
			} else {
			    $requested = ($g_req == 2) ? 2 : NULL;
			}
			
			// Cheeck further group request in public mode
			$available = (!is_null($requested) && $private) ? 0 : 2;
			
			// If requested
            if(!is_null($requested)) return array($available,$requested,$private);
			
			// Else allow if target user is not private
			return ($private) ? array($available = 0,$following = 0,$private) : array($available = 1,$following = 0,$private);

		}
	}	

	function getArticle($user,$id) {
		global $TEXT;
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs`, `users` WHERE `blogs`.`blog_id` = '%s' AND `users`.`idu` = `blogs`.`blog_by_id` LIMIT 1",$this->db->real_escape_string($id)));
		
		if(!empty($result) && $result->num_rows !== 0) {
		    
			$this->db->query(sprintf("UPDATE `blogs` SET `blogs`.`blog_views` = `blog_views` + 1 WHERE `blogs`.`blog_id` = '%s'",$this->db->real_escape_string($id)));
		
			$article = $result->fetch_assoc();
			$user_id = ($user) ? $user['idu'] : '';
			$TEXT['temp-blog_id'] = $article['blog_id'];
			$TEXT['temp-blog_views'] = number_format($article['blog_views']);
			$TEXT['temp-blog_likes'] = number_format($article['blog_loves']);
			$TEXT['temp-like_btn'] = (!$user_id) ? '' : $this->getLikeBtn($article['blog_id'],$this->isLoved($article['blog_id'],$user_id));
			$TEXT['temp-blog_image'] = $article['blog_image'];
			$TEXT['temp-blog_heading'] = $article['blog_heading'];
			$TEXT['temp-blog_category'] = $this->nameCategory($article['blog_type']);
			$TEXT['temp-user_id'] = $article['idu'];
			$TEXT['temp-blog_href'] = $this->sluggify($article['blog_heading']);
			$TEXT['temp-time'] = addStamp($article['blog_time'],2);
			list($available,$following,$pri) = $this->getPermissions($user_id,$article['idu'],$article['p_private']);
			list($TEXT['temp-ab1'],$TEXT['temp-ab2'],$TEXT['temp-ab3']) = $this->genAccordData($following,$article);
			$TEXT['temp-v_bt'] = $this->verifiedBatch($article['verified']);
			$TEXT['temp-r_btn'] = $user_id ? $this->getRelationButton($following,$article['idu'],$pri) :' ';
			$TEXT['temp-user_image'] = $this->getImage($user_id,$article['idu'],$article['p_image'],$article['image']);
			$TEXT['temp-user_name'] = fixName(35,$article['username'],$article['first_name'],$article['last_name']);
			$TEXT['temp-cat_id'] = $article['blog_type'];
			$TEXT['temp-blog_text'] = $this->parseBlogText($article['blog_text']);

			return array($article,display(templateSrc('/blogs/article/main')));

		} else {
			return array(0,bannerIt('no-results',$TEXT['_uni-No_articles'],$TEXT['_uni-No_articles_found2']));
		}
		
	}
	
	function getComments($user,$id,$start=0,$latest=0) {
	    global $TEXT;
	    
		$rows = array();$comments='';
		
		$add_from = ($start && !$latest) ? 'AND `id` < \''.$this->db->real_escape_string($start).'\'' : '';
		
		$add_latest = ($latest) ? 'AND `by_id` = \''.$this->db->real_escape_string($user['idu']).'\'' : '';
		
		$add_limit = ($latest) ? 'LIMIT 1' : 'LIMIT 11';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blog_comments`, `users` WHERE `users`.`idu` = `blog_comments`.`by_id` AND `blog_comments`.`blog_id` = '%s' $add_from $add_latest ORDER BY `id` DESC $add_limit",$this->db->real_escape_string($id)));
		
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$loadmore = (array_key_exists(10, $rows)) ? array_pop($rows) : NULL;
			
			$comment_template = display(templateSrc('/blogs/article/comment'),0,1);
			
			$user_id = ($user) ? $user['idu'] : '';
			
			foreach($rows as $row) {
				$TEXT['temp-comment_id'] = $from = $row['id'];
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-time'] = addStamp($row['time'],2);
				$TEXT['temp-comment_btns'] = (!$user_id) ? '' :'<span class="h5 b2 pointer tin-light-font" onclick="deleteBlog('.$row['id'].',1);" >'.$TEXT['_uni-Delete'].'</span>';
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name'] = fixName(45,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-comment_text'] = $this->parseText($row['comment_text']);
				$comments .= display('',$comment_template);
			}
			
			return ($loadmore) ? $comments.addLoadmore($this->settings['inf_scroll'],'','blogComments('.$id.','.$from.',6);') : $comments;
			
		}
	}
	
	function getCommentBox($id,$user) {
		global $TEXT;
		
		if(!$user) {
			return closeBox($TEXT['_uni-Blog_login']);
		} else {
			$TEXT['temp-user_id'] = $user['idu'];
			$TEXT['temp-blog_id'] = $id;
			$TEXT['temp-user_image'] = $user['image'];
			return display(templateSrc('/blogs/forms/comment'));
		}
		
	}
	
	function deleteComment($id,$user) {
		global $TEXT;
		
		$comment = $this->getCommentByID($id);
		$blog = $this->getBlogByID($comment['blog_id']);
		
		if($comment['by_id'] == $user['idu']) {
            //  Delete Own Comment
			$query = sprintf("DELETE FROM `blog_comments` WHERE `blog_comments`.`id` = '%s' AND `blog_comments`.`by_id` = '%s' ",$this->db->real_escape_string($id),$this->db->real_escape_string($user['idu']));	
		} elseif($blog['blog_by_id'] == $comment['by_id']) {
            //  Delete others Comment on own blog
			$query = sprintf("DELETE FROM `blog_comments` WHERE `blog_comments`.`id` = '%s' ",$this->db->real_escape_string($id));	
		}
		
		return ($this->db->query($query) === TRUE) ? $TEXT['_uni-deleted-comment'] : $TEXT['lang_error_connection'];
		
	}
	
	function getPopularTags() {

		$blogs = $this->db->query("SELECT * FROM `blogs` WHERE `blogs`.`blog_tags` != '' ORDER BY `blogs`.`blog_id` DESC LIMIT 15");
		$tags = '';
		
		if(!empty($blogs)) {
			
			while($row = $blogs->fetch_assoc()) {
				$tags .= ','.$row['blog_tags'];
			}
		    
			$all_tags = array();
		
			$split = explode(',',$tags);
			
		    foreach($split as $tag) {
			    if(!empty($tag)) {
				    $all_tags[] = fixText(15,$tag);
			    }
		    }
		
		    return implode(',',array_unique($all_tags));
		}
		
	}
	
	function getSearch($user,$search,$from) {
	    global $TEXT;
	    
		$rows = array();
		
		$add_from = ($start) ? 'AND `blog_id` < \''.$this->db->real_escape_string($start).'\'' : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM blogs, users WHERE users.idu = blogs.blog_by_id AND (blogs.blog_heading LIKE '%s' OR blogs.blog_tags REGEXP '[[:<:]]%s[[:>:]]') $add_from ORDER BY blogs.blog_id DESC LIMIT 11",'%'.$this->db->real_escape_string($search).'%',$this->db->real_escape_string($search)));
		
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$loadmore = (array_key_exists(10, $rows)) ? array_pop($rows) : NULL;	

			$blog_template = display(templateSrc('/blogs/article/in_list'),0,1);
			
			$user_id = ($user) ? $user['idu'] : '';
			
			$TEXT['temp-add_trending'] = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $from = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_category'] = $this->nameCategory($row['blog_type']);
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-time'] = addStamp($row['blog_time'],2);
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-blog_text'] = fixText(150,$row['blog_description']);
				$articles .= display('',$blog_template);
			}
			
			$articles .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','searchArticles('.$from.',6);') : '';
			
			return $articles;
			
		} else {
			return bannerIt('no-results',$TEXT['_uni-No_articles'],$TEXT['_uni-No_articles_found3']);      
		}
	}
	
	function deleteBlog($user,$id) {
		global $TEXT;
		
		$query = "DELETE FROM `blogs` WHERE `blog_id` = '%s' AND `blog_by_id` = '%s' ";
		
		if($this->db->query(sprintf($query,$this->db->real_escape_string($id),$this->db->real_escape_string($user['idu']))) === TRUE) {
		
		    // Delete comments
		    $this->db->query(sprintf("DELETE FROM `blog_comments` WHERE `blog_id` = '%' '",$this->db->real_escape_string($id)));
		    
			// Delete loves
		    $this->db->query(sprintf("DELETE FROM `blog_loves` WHERE `blog_id` = '%' '",$this->db->real_escape_string($id)));
		
		    // Update user blog counts
		    $this->db->query(sprintf("UPDATE `users` SET `blogs` = `blogs` - 1 WHERE `idu` = '%' '",$this->db->real_escape_string($user['idu'])));
		
		    return closeBox($TEXT['_uni-Blog_del_suc']);
			
		} else {
			return closeBox($TEXT['_uni-Error_mysql'].$this->db->error);
		}
		
	}
	
	function getUserArticles($user,$start=0) {
	    global $TEXT;
	    
		$rows = array();
		
		$add_from = ($start) ? 'AND `blog_id` < \''.$this->db->real_escape_string($start).'\'' : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs`, `users` WHERE `users`.`idu` = `blogs`.`blog_by_id` AND `blogs`.`blog_by_id` = '%s' $add_from ORDER BY `blog_id` DESC LIMIT 11",$this->db->real_escape_string($user['idu'])));
		
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$loadmore = (array_key_exists(10, $rows)) ? array_pop($rows) : NULL;	

			$blog_template = display(templateSrc('/blogs/article/user_blog'),0,1);

			$articles = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $from = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_category'] = $this->nameCategory($row['blog_type']);
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-time'] = addStamp($row['blog_time'],2);
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-blog_text'] = fixText(50,$row['blog_description']);
				$articles .= display('',$blog_template);
			}
			
			$articles .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','loadBlogData('.$from.',8,6);') : '';
			
			return ($start) ? $articles : display(templateSrc('/blogs/article/user_heading')).$articles;
			
		} else {
			$add_banner = bannerIt('no-results',$TEXT['_uni-No_articles'],$TEXT['_uni-No_articles_found4']);
			return  ($start) ? $add_banner : display(templateSrc('/blogs/article/user_heading')).$add_banner;      
		}		
	}
	
	function getArticles($user,$id=null,$start=0) {
	    global $TEXT;
	    
		$rows = array();
		
		$add_type = ($id) ? 'AND `blog_type` = \''.$this->db->real_escape_string($id).'\'' : '';
		$add_type2 = ($id) ? $id: '0';
		$add_from = ($start) ? 'AND `blog_id` < \''.$this->db->real_escape_string($start).'\'' : '';
		
		$result = $this->db->query(sprintf("SELECT * FROM `blogs`, `users` WHERE `users`.`idu` = `blogs`.`blog_by_id` $add_type $add_from ORDER BY `blog_id` DESC LIMIT 11"));
		
		if(!empty($result) && $result->num_rows !== 0) {

			// Fetch all posts
			while($row = $result->fetch_assoc()) {
			    $rows[] = $row;
			}
			
			$loadmore = (array_key_exists(10, $rows)) ? array_pop($rows) : NULL;	

			$blog_template = display(templateSrc('/blogs/article/in_list'),0,1);
			
			$user_id = ($user) ? $user['idu'] : '';
			
			$articles = '';
			
			foreach($rows as $row) {
				$TEXT['temp-blog_id'] = $from = $row['blog_id'];
				$TEXT['temp-blog_image'] = $row['blog_image'];
				$TEXT['temp-blog_heading'] = $row['blog_heading'];
				$TEXT['temp-blog_category'] = $this->nameCategory($row['blog_type']);
				$TEXT['temp-user_id'] = $row['idu'];
				$TEXT['temp-blog_href'] = $this->sluggify($row['blog_heading']);
				$TEXT['temp-time'] = addStamp($row['blog_time'],2);
				$TEXT['temp-user_image'] = $this->getImage($user_id,$row['idu'],$row['p_image'],$row['image']);
				$TEXT['temp-user_name'] = fixName(35,$row['username'],$row['first_name'],$row['last_name']);
				$TEXT['temp-cat_id'] = $row['blog_type'];
				$TEXT['temp-blog_text'] = fixText(150,$row['blog_description']);
				$articles .= display('',$blog_template);
			}
			
			$articles .= ($loadmore) ? addLoadmore($this->settings['inf_scroll'],'','blogAricles('.$add_type2.','.$from.',6);') : '';
			
			return $articles;
			
		} else {
			return bannerIt('no-results',$TEXT['_uni-No_articles'],$TEXT['_uni-No_articles_found']);      
		}
	}
	
	function sluggify($url) {
        // Prep string with some basic normalization
        $url = strtolower($url);
        $url = strip_tags($url);
        $url = stripslashes($url);
        $url = html_entity_decode($url);

        // Remove quotes (can't, etc.)
        $url = str_replace('\'', '', $url);

        // Replace non-alpha numeric with hyphens
        $match = '/[^a-z0-9]+/';
        $replace = '-';
        $url = preg_replace($match, $replace, $url);

        $url = trim($url, '-');

        return $url;
    }

}

class Login {	        // Login | Logout user	
	
	// Properties
	public $db;                         // DATABASE
	public $username;	                // USERNAME || IDU
	public $password;	                // PASSWORD || MD5(PASSWORD)
	public $cookie;	                    // 1 || 0
	public $emails_verification;	    // 1 || 0	
	public $mail;                       // Website email	
	public $settings;	                // Administration settings
	public $new_reg;	                // New registration property
	
	function start() {                                        // Full login (Verify for blocks,non verified emails or suspended)
		
		// Unset everything
		$this->logOut();
		
		// Verify profile
		$profile = $this->checkProfile();
		
		// If login success
		if($profile == 1) {
			
			// Check for cookie || Default is enabled 
			if($this->cookie) {

                // Set session and cookies			
				setcookie("username", $this->username, time() + 30 * 24 * 60 * 60,'/'); 
				setcookie("password", md5($this->password), time() + 30 * 24 * 60 * 60,'/'); 	
				$_SESSION['username'] = $this->username;
				$_SESSION['password'] = md5($this->password);
			    return 1;
				
			} else {
				
				// Set sessions only
				$_SESSION['username'] = $this->username;
				$_SESSION['password'] = md5($this->password);
				return 1;
				
			}
			
			// Unset logged out identifier
            unset($_SESSION['loggedout']);
			
		} else {
			
			// Else return error while login
			return $profile;
			
		}	
	}
	
	function log() {                                          // Direct login
		
		// Select user
		if(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL)) {
			$profile = $this->db->query(sprintf("SELECT * FROM `users` WHERE `email` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)),$this->db->real_escape_string($this->password)));
		} else {
			$profile = $this->db->query(sprintf("SELECT * FROM `users` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)), $this->db->real_escape_string($this->password)));
		}	
        
        // return profile if exists		
		return ($profile->num_rows) ? $profile->fetch_assoc() : $this->logOut();	
	}
	
	function checkProfile() {                                 // Check profile for Full login
		global $TEXT;
		
		// Check whether input is email and select user
		if(filter_var($this->db->real_escape_string($this->username), FILTER_VALIDATE_EMAIL)) {
			$result = $this->db->query(sprintf("SELECT `idu`,`state` FROM `users` WHERE `email` = '%s' AND `password` = '%s'", $this->db->real_escape_string($this->username), md5($this->db->real_escape_string($this->password))));
		} else {
			$result = $this->db->query(sprintf("SELECT `idu`,`state` FROM `users` WHERE `username` = '%s' AND `password` = '%s'", $this->db->real_escape_string(strtolower($this->username)), md5($this->db->real_escape_string($this->password))));
		}
		
		// if user exists
		if($result->num_rows) {
			
			// Fetch user
			$profile = $result->fetch_assoc();
			
			// Check user status
			if($profile['state'] == 3) {
				
				// Suspended by Admin temporary
			    return $TEXT['_uni-login-1'];
				
	        } elseif($profile['state'] == 2 && $this->emails_verification == 1 && !isset($this->new_reg)) {
				
				// Email not verified send verification
			    return emailVerification($this->db,$this->settings,$profile['idu'],NULL);
				
	        } elseif($profile['state'] == 4) {
				
				// Permanent Suspended for using other's emails 
			    return $TEXT['_uni-login-3'];
				
	        } else {
				return 1;
			}
			
        // Wrong credentials			
		} else {
			return $TEXT['_uni-Username_password_incorrect'];
		}	
	}
	
	function activateProfile($respond) {                      // Activate email
		global $TEXT;
		
		$result = $this->db->query(sprintf("SELECT `idu`,`username` FROM `users` WHERE `idu` = '%s' ", $this->db->real_escape_string(strtolower($this->username))));

		// if user exists
		if($result->num_rows && !is_null($this->username)) {
			
			// Fetch user row from database
			$result = $result->fetch_assoc();
			
			// Email not verified send verification token
			return emailVerification($this->db,$this->settings,$result['idu'],$respond);

        // Wrong credentials			
		} else {
			return $TEXT['_uni-error-activation-1'];
		}	
	}
	
	function logOut() {                                       // Throw SESSIONS and COOKIES RETURN 0
		
		// unset sessions
		unset($_SESSION['username']);
		unset($_SESSION['password']);

		// unset cookies
		setcookie("username", '', time() + 1*1,'/'); 
        setcookie("password", '', time() + 1*1,'/'); 
		
		return 0;
	}

}

// Add global funcions if don' exists
if(!function_exists('emailVerification')) {
	require_once(__DIR__ . '/functions.php');
}


// load blocking library, from now we had changed the scheme of adding new features 
require_once(__DIR__ . "/processors/blocker.php");


?>