<?php
function sizeImage($src, $des, $ty, $mx, $wd, $hi, $qt) {                    // Resize over sized image 

	// Return false if NOT image
	if($wd <= 0 || $hi <= 0) return false; 

	// Save image as it is if its under within width and height
	if($wd <= $mx && $hi <= $mx){
		if(saveImage($src, $des, $ty, $qt)){
			return true;
		}
	}

	// Else Scale image to right size and create a new image
	$image_scale = min($mx/$wd, $mx/$hi);
	$new_width = ceil($image_scale * $wd);
	$new_height = ceil($image_scale * $hi);
	
	// Ready image
	$new_canvas	= imagecreatetruecolor($new_width, $new_height); 

	// Save created image
	if(imagecopyresampled($new_canvas, $src, 0, 0, 0, 0, $new_width, $new_height, $wd, $hi)){
		saveImage($new_canvas, $des, $ty, $qt);	
	}

	return true;
}

function generateReacts($list,$el,$id,$event) {
	global $TEXT;
	foreach($list as $reaction) {
		$content .= '<img onclick="addReact('.$el.','.$id.','.$event.','.$reaction[0].',\''.$reaction[2].'\');" class="img-25 reaction reaction-likes pointer inline-block left" style="margin:6px;" src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/'.$reaction[2].'">';	
	}
	return $content;
}

function getReactions($list) {
 
	$parsed = array();
	
	foreach(explode(';',$list) as $reaction) if(!empty($reaction)) $parsed[] = explode(',',$reaction);
	
	return $parsed;
}

function getCategoryTitle($cid,$db) {
	
	$query_id = $db->query(sprintf("SELECT `cat_name` FROM `categories` WHERE `cid` = '%s'",$db->real_escape_string($cid)));
	
	return ($query_id->num_rows !== 0) ? $query_id->fetch_assoc() : 0;
}	
 
function saveImage($src, $des, $type, $qt) {                                 // Save image
		
	// Select valid mime type and create valid format
	switch(strtolower($type)){
		case 'image/png':
			imagepng($src, $des); return true; 
			break;
		case 'image/gif': 
			imagegif($src, $des); return true; 
			break;          
		case 'image/jpeg': case 'image/pjpeg':  case 'image/jpg': 
			imagejpeg($src, $des, $qt); return true; 
			break;
		default: return false;
	}
}

function getAds($db,$user,$type,$ti_set) {                                                 // Get ads
	global $TEXT,$page_settings;
	// TYPE 1 : Posts
	// TYPE 1,2 : Pages and posts ...
	
	$tits = array(
	    "feeds" => $page_settings['sp_feeds'],
	);
	
	$limit = $tits[$ti_set];
	
	$main = new main();
	$main->db = $db;
	$main->settings = $page_settings;
	
	// Select boosted content
	$result = $db->query(sprintf("SELECT * FROM `ads` WHERE `ads`.`v_left` > '0' AND `ads`.`by_id` != '%s' AND `ads`.`type` IN(%s) ORDER BY RAND() LIMIT %s", $db->real_escape_string($user['idu']), $db->real_escape_string($type), $db->real_escape_string($limit)));

	// Reset
	$rows = $displayed_ads = array(); $content = '';

	// set conditions
	if(!empty($result) && $result->num_rows !== 0) {

		// Fetch all
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		
		// Load templates
		$page_template = display(templateSrc('/sponsors/page_boosted'),0,1);
		$group_template = display(templateSrc('/sponsors/group_boosted'),0,1);
		$post_template_user = display(templateSrc('/sponsors/user_post_boosted'),0,1);
		$post_template_page = display(templateSrc('/sponsors/page_post_boosted'),0,1);

		foreach($rows as $row) {
			
			$displayed_ads[] = $row['aid'];

			// Group
			if($row['type'] == 3) {
		
			    // Fetch group
		        $get_group = $main->getGroup($row['cid']);

				if($get_group) {

				    // Fetch group user
		            $group_user = $main->getGroupUser($user['idu'],$get_group['group_id']);
		
				    // Is joined
				    if($group_user['group_status'] == 1) {
					    $joined = ($group_user['group_role'] == "2") ? 2 : 1;	
				    } else {
					    $joined = 0 ;
				    }
		
		            // Generate permissions
				    list($available,$following,$private) = $main->getPermissions($user['idu'],$get_group['group_owner'],$get_group['group_privacy'],$joined,$group_user['group_status']);
				
				    $content .=  listMainUser($get_group['group_id'],$get_group['group_name'],'',getCoverSrc(str_replace('rep_','',$get_group['group_cover'])),'','',sprintf($TEXT['_uni-ttl_group_members_ttl'],readable($get_group['group_users'])),$get_group['group_location'],fixText(45,$get_group['group_description']),$main->getGroupButton($following,$get_group['group_id'],$private,1),1,$group_template);

				} else {
					$content .= 'asdsd';
				}
				
		    // Page
			} elseif($row['type'] == 2) {
				
				// Fetch page
		        $get_page = $main->getPage($row['cid']);

				if($get_page) {

				    // Set data for template
				    $TEXT['temp-page_id'] = $get_page['page_id'];
				    $TEXT['temp-page_icon'] = $get_page['page_icon'];
				    $TEXT['temp-page_name_25'] = fixText(25,$get_page['page_name']);
				    $TEXT['temp-verified_batch'] = $main->verifiedBatch($get_page['page_verified']);
				    $TEXT['temp-page_category'] = nameCategory($get_page['page_cat'],$get_page['page_sub_cat'],$main->getCategories(1),$db);
				    $TEXT['temp-page_likes'] = readAble($get_page['page_likes']).' '.$TEXT['_uni-likes_this'];
				    $TEXT['temp-page_description'] = fixText(125,$get_page['page_description']);

				    $content .=  display('',$page_template);

				}
				
        	// Posts		
			} elseif($row['type'] == 1) {

				$result = $db->query(sprintf("SELECT * FROM `user_posts`, `users` WHERE `user_posts`.`post_by_id` = `users`.`idu` AND `user_posts`.`post_id` = '%s' AND `post_type` IN(1,3,4,5,7,8,9) LIMIT 1", $db->real_escape_string($row['cid'])));

				if(!empty($result) && $result->num_rows !== 0) {

					// Fetch post
		            $post_row = $result->fetch_assoc();
				
					$TEXT['temp-user_img'] = $main->getImage($user['idu'],$post_row['idu'],$post_row['p_image'],$post_row['image']);			
		
					// List post buttons and details
					list($TEXT['temp-likebtn'],$TEXT['temp-commentbtn'],$TEXT['temp-liketext'],$TEXT['temp-dropoptions'],$TEXT['temp-user_load_href'],$TEXT['temp-user_img_c_c']) = $main->listFunctions($post_row,$user,1,1);
		
					// Parse post text remove all smiles
					$TEXT['temp-pt_ns'] = protectXSS($main->parseText($post_row['post_text'],1));

					// Get post type and content
					list($TEXT['temp-p_con'],$TEXT['temp-p_t']) = $main->getPostContent($post_row,$post_row['post_id'],1,null,$main->parseText(protectXSS($post_row['post_text'])));	

					// Check paeg post
					if($post_row['posted_as'] == 1) {
				
						$get_page = $main->getPage($post_row['posted_at']);
				
						$TEXT['temp-page_link'] = ($get_page['page_username']) ? $get_page['page_username'] : $get_page['page_id'];
				
						// Post heading
			    		$TEXT['temp-heading'] = '<a class="h6 b2 dark-font" href="'.$TEXT['installation'].'/page/'.$TEXT['temp-page_link'].'" onclick="loadPage('.$get_page['page_id'].',1,1);return false;">'.$get_page['page_name'].'</a>';
				
						// Post id
						$TEXT['temp-id'] = $post_row['post_id'];
						$TEXT['temp-page_id'] = $get_page['page_id'];
						$TEXT['temp-page_icon'] = $get_page['page_icon'];
				
						// Posted time
						$TEXT['temp-time'] = 'Sponsored';
				
						// Add post
						$content .= display('',$post_template_page);
				
					} else {
			
			            if($post_row['posted_as'] == 2) {
							$group = $this->db->query(sprintf("SELECT * FROM `groups` WHERE `group_id` = '%s' ", $db->real_escape_string($post_row['posted_at'])));
                            $group = ($group->num_rows) ? $group->fetch_assoc() : 0;
						}
						
						// Post heading
						list($TEXT['temp-heading'],$TEXT['temp-group_heading']) = $main->getPostHeading($post_row['post_type'],$post_row['posted_as'],$post_row['posted_at'],$post_row['post_extras'],$group['group_name'],$post_row['post_content'],$post_row['gender'],$post_row['post_id']);

						// Post id
						$TEXT['temp-id'] = $post_row['post_id'];

						// Posted time
						$TEXT['temp-time'] = 'Sponsored';
						$TEXT['temp-username_c'] = $user['username'];
						$TEXT['temp-user_img_c'] = $user['image'];
						$TEXT['temp-user_id_c'] = $user['idu'];
						$TEXT['temp-user_id'] = $post_row['idu'];
						$TEXT['temp-username'] = $post_row['username'];
						$TEXT['temp-user_name'] = fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']);
						$TEXT['temp-userttl'] = sprintf($TEXT['_uni-Profile_load_text2'],fixName(25,$post_row['username'],$post_row['first_name'],$post_row['last_name']));
				
						// Add post in list
						$content .= display('',$post_template_user);
			
					}
				}
			}
			
			// Cut views
			if(!empty($displayed_ads)) {
				$db->query(sprintf("UPDATE `ads` SET `v_left` = `v_left` - 1 WHERE `aid` IN(%s)", $db->real_escape_string(implode(',',$displayed_ads))));
			}
		}
		
	}

	return $content;

}
	
function templateSrc($file='',$get_src=0) {                                               // Get template src
    global $TEXT;
	
	// Find file
	if(file_exists('themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '';
	} elseif(file_exists('../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../';
	} elseif(file_exists('../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../';
	} elseif(file_exists('../../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../../';
	} elseif(file_exists('../../../../themes/'.$TEXT['theme'].'/theme.php')) {
		$src = '../../../../';
	}
	
	// Return SRC
	return ($get_src) ? $src.'themes/'.$TEXT['theme'].'/html' : $src.'themes/'.$TEXT['theme'].'/html'.$file.$TEXT['templates_extension'];
	
}

function getCoverSrc($cover) {                                         // Return cover
    $nrm = explode('?',$cover);
    return $nrm[0];
}

function emailVerification($db,$settings,$idu,$salt = NULL) {          // Send new response || or verify email
	global $TEXT;
	
	if(is_null($salt)) {

		// Select user
		$user = $db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' ", $db->real_escape_string($idu)));
		
		// Fetch user
		$result = $user->fetch_assoc();
	  
		// Generate secure random code
		$salted = md5(secureRand(10,TRUE));
		
		// Set activation response
		$db->query(sprintf("UPDATE `users` SET `salt` = '%s' WHERE `users`.`idu` = '%s' AND `users`.`state` = 2 ", $salted, $db->real_escape_string($idu)));

		// Send activation mail
		mailSender($settings, $result['email'], $TEXT['_uni-Activate_account'], sprintf($TEXT['_uni-Activation_mail'], $TEXT['title'], $TEXT['installation'].'/index.php?respond='.$salted.'&type=activation&for='.$result['idu']), $TEXT['web_mail']);
		
		return $TEXT['_uni-login-2'];

	} else {
		
		// Select user
		$user = $db->query(sprintf("SELECT * FROM `users` WHERE `users`.`idu` = '%s' AND `users`.`salt` = '%s' AND `users`.`state` = 2 ", $db->real_escape_string($idu), $db->real_escape_string($salt)));	
		
		// Fetch user
		$result = $user->fetch_assoc();

		// If code MATCHED
		if($user->num_rows) {
	
			// Activate the account
			$db->query(sprintf("UPDATE `users` SET `users`.`salt` = '', `users`.`state` = 1 WHERE `users`.`idu` = '%s' ", $db->real_escape_string($idu)));

			// Delete any pending accounts
			$db->query(sprintf("UPDATE `users` SET `users`.`email` = 'NULL', `users`.`state` = 4 WHERE `users`.`email` = '%s' AND `users`.`state` != 1 ", $db->real_escape_string($result['email'])));

			return 'ACTIVATED';
			
		} else {
			return $TEXT['_uni-E-Mail_verification1'];
		}
	}
}

function parseBackgrounds($img_set) {
	global $TEXT;
	
	$backgrounds = explode(',',$img_set);
	
	// Save images in global index
	$TEXT['BACK_1'] = $backgrounds[0];
	$TEXT['BACK_2'] = $backgrounds[1];
	$TEXT['BACK_3'] = $backgrounds[2];
	$TEXT['BACK_4'] = $backgrounds[3];
	$TEXT['BACK_5'] = $backgrounds[4];
	$TEXT['BACK_6'] = $backgrounds[5];
	$TEXT['BACK_7'] = $backgrounds[6];
	$TEXT['BACK_8'] = $backgrounds[7];
	$TEXT['BACK_9'] = $backgrounds[8];
	$TEXT['BACK_10'] = $backgrounds[9];	
}

function mailSender($settings, $to, $subject, $message, $from) {       // Mail sender SMTP + Basic mail function
	
	// Global ARRAY
	global $TEXT;	
	
	// If the SMTP emails option is enabled in the Admin Panel
	if($settings['smtp_email']) {
		
		// Import PHPMailer
		require_once(__DIR__ .'/phpmailer/PHPMailerAutoload.php');

		// Create PHPMailer class 
		$mail = new PHPMailer;
		
		// SMTP settings
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		
		// Set content settings
		$mail->CharSet = 'UTF-8';
		$mail->Debugoutput = 'html';
		
		// Set the HOST of the mail server
		$mail->Host = $settings['smtp_host'];
		
		// SMTP port number
		$mail->Port = $settings['smtp_port'];
		
		// Whether to use SMTP authentication
		$mail->SMTPAuth = ($settings['smtp_auth']) ? true : false;
		
		// USERNAME
		$mail->Username = $settings['smtp_username'];
		
		// PASSWORD
		$mail->Password = $settings['smtp_password'];
		
		// Mail sender
		$mail->setFrom($from, $settings['title']);
		$mail->addReplyTo($from, $settings['title']);
		
		// Send mail to
		$mail->addAddress($to);

		// Subject
		$mail->Subject = $subject;
		
		// HTMLise body
		$mail->msgHTML($message);

		// If sent
		return (!$mail->send()) ? 0 : 1;
		
	} else {
		
		// Use basic mail sending function if SMTP is not enabled
		
		// Set MIME version
		$set  = 'MIME-Version: 1.0' . "\r\n";
		
		// Set content type
		$set .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		// Add content
		$set .= 'From: '.$from.'' . "\r\n" .
			    'Reply-To: '.$from . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
		
		// Send MAIL
		@mail($to, $subject, $message, $set);
	}
}

function bannerIt($img,$t1,$t2,$id = 4513,$fb_icon=NULL) {                           // Fix text length
    global $TEXT;
	
	$TEXT['temp-img'] = $img;
	$TEXT['temp-t1'] = $t1;
	$TEXT['temp-t2'] = $t2;
	$TEXT['temp-id'] = $id;

	return display(templateSrc('/elements/banners/main'));

}

function fixText($length,$a) {                                         // Fix text length

	// Count number of characters
	$len_a = mb_strlen($a, 'utf-8');
	
	// If length is less than or equal to allowed length return	
	return ($len_a <= $length) ? protectXSS($a) : mb_substr(protectXSS($a),0,$length-3, 'utf-8').'...';

}

function fixName($length,$v1,$v2,$v3) {                                // Profile naming

	global $TEXT;

	// Counting lengths
	$len_a = mb_strlen(trim($v1), 'utf-8');
	$len_b = mb_strlen(trim($v2), 'utf-8');
	$len_c = mb_strlen(trim($v3), 'utf-8');
	
	// Add protection
    $a = protectXSS($v1);
    $b = protectXSS($v2);
    $c = protectXSS($v3);

	// If $c or $b is empty use only $c
	if($len_b == 0 || $len_c == 0) {
		if($len_a  > 0) {
			
			return ($len_a <= $length) ? $a : mb_substr($a,0,$length-2, 'utf-8').'..';

		} else {

			// Else validate $b
			if($len_b > 0) {
				
				return($len_a <= $length) ? $b : mb_substr($b,0,$length-2, 'utf-8').'..';

            // Further validate $c					
			} elseif($len_c > 0) {
				
				return ($len_c <= $length) ? $c : mb_substr($c,0,$length-2, 'utf-8').'..';

            // No-Name				
			} else {
				return $TEXT['lang_error_noname'];					
			}	
		}

	// Else fetch $c or $b
	} elseif($len_b + $len_c > 3) {
		
		return ($len_b + $len_c <= $length) ? $b.' '.$c : mb_substr($b.' '.$c,0,$length-2, 'utf-8').'..';
		
	// Else return No-name
	} else {
		return $TEXT['lang_error_noname'];
	}		
}	
	
function readAble($value) {                                            // Convert large numbers to 9.9k...

	// Already readable
	if($value == 0 || $value < 0) return 0;

	// If less than 10k return as it is
	elseif($value < 10000) return $value;
  
	// Covert to thousands
	elseif($value < 1000000) return mb_substr(($value / 1000),0,5, 'utf-8').' k';
   
	// Covert to millions
	elseif($value < 10000000) return mb_substr(($value / 1000000),0,5, 'utf-8').' m';
 
	// Covert to billions
	else return mb_substr(($value / 10000000),0,5, 'utf-8').' b';
    
}

function readableBytes($bytes) {                                       // Convert large bytes to 9.9MB...
   
   // Already readable
   if ($bytes < 1024) return $bytes.' Bytes';
   
   // Covert to KBs
   elseif ($bytes < 1048576) return mb_substr(round($bytes / 1024, 2),0,5, 'utf-8').' KB';
   
   // Covert to MBs
   elseif ($bytes < 1073741824) return mb_substr(round($bytes / 1048576, 2),0,5, 'utf-8').' MB';
   
   // Covert to GBs
   elseif ($bytes < 1099511627776) return mb_substr(round($bytes / 1073741824, 2),0,5, 'utf-8').' GB';
   
   // Covert to TBs
   else return mb_substr(round($bytes / 1099511627776, 2),0,5, 'utf-8').' TB';
}

function getBible() {                                                  // RETURN Bible widget
    global $TEXT;
	
	$colors = array('orange','theme','red','violet','x-theme','blue-grey','blue-grey');
	$TEXT['temp-widget_class'] = 'dimensions hidden bible-verse';
	$TEXT['temp-widget_color'] = $colors[array_rand($colors)];
	$TEXT['temp-js'] = 'readBible();';
	$TEXT['temp-href'] = '#';
	$TEXT['temp-btn'] = $TEXT['_uni-Read'];
	
	// Generate widget from template 
	return display(templateSrc('/modals/widget'));
	
}

function getUserIP() {                                                 // RETURN user remote address
	
	// return address if available
	return ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	
}

function reorderArray($a, $b) {                                        // Reorder array
	return ($a['time'] > $b['time'] && $a['time'] !== $b['time']) ? -1 : ($a['time'] == $b['time']) ? 0 : 1;  
}
	
function protectInput($input) {                                        // Security protection while saving data
	
	return htmlspecialchars(trim(preg_replace("/(\r?\n){2,}/", "\n\n", $input)));
	
}

function htmlConversion($input) {                                      // Security protection while saving html
	
	return trim(htmlspecialchars($input, ENT_QUOTES));
	
}	

function protectXSS($input) {                                          // Security protection while output data 
	
	return strip_tags($input);	

}
	
function isXSSED($input) {                                             // Custom input blocker

    /*
	All user inputs cross through this check you can add custom validation here.
	Function must return 0 if input is safe else return 1 to terminate saving 
	process
	
	// Example
	if(customValidate($input) == 'PASS') {
		return 0 ;       // Input is safe
	} else {
		return 1;        // Input is not safe, this will prevent adding input to database
	}
	
	*/
	
	return 0;
	
}
	
function showError($text, $ttl = NULL, $ground_set = NULL,$onclick='') {          // Notifier - (Modal)
    
	global $TEXT;
	
	// Add title
	$title = ($ttl) ? $ttl : ($ground_set) ? $ground_set : $TEXT['_uni-c_d_this'];
	
	return '<div id="modal-info-on" class="notification hide background-0">
				<div id="modal-text" style="width:85%;" class="h6 b1 left text-left padding-10 white-font-only">
			  		'.$text.'
				</div>
				<div onclick="$(\'.notification\').remove();'.$onclick.'" style="width:15%;" class="right pointer h5 b2 padding-10 theme-x-font noselect">X</div>	    
	        </div>';

}
	
function showSuccess($text,$ttl = NULL,$onclick='') {                              // Notifier - SUCCESS
    global $TEXT;

	// Create success box
	return showError($text, $ttl = NULL, $TEXT['_uni-done_msg'],$onclick);
	
}
	
function showNotification($text) {                                     // Notifier - INFORM
	global $TEXT;

	// Create success box
	return showError($text, $ttl = NULL, $TEXT['_uni-No_changes']);
}	

function showBox($text) {                                              // Notifier - Standard
	return '<div class="center noselect block-container-3 h4 b2 tin-light-font-only margin-0 padding-10">'.$text.'</div>';
}

function closeBox($text) {                                             // Close notifier - Standard box
	return '<div style="width:100%;" class="center noselect block-container-3 h4 b2 tin-light-font-only margin-0 padding-10">
		     '.$text.'
			 </div>';
}

function closeBody($text,$white = NULL) {                              // Ultimate Close notifier 
    $color = ($white) ? 'white-color' : 'body-color';                                     
	return '<div align="center" style="margin-top:40px!important;"><span class="padding-10 noselect center h4 b1 tin-light-font-only '.$color.'">'.$text.'</span><hr style="border-color:#d7d7d74d !important;margin:-10px 20px auto 20px ;z-index:0;"></div><br><br>';
}

function chatNotification($text, $id = 24) {                                     // Notifier - For chats
	// Notification template
	return '<div align="center" id="message-'.$id.'" class="full message-bos noflow padding-6-0">
				<div class="center background-0 block message margin padding-5 h3 b1 white-font-only">
		    	'.$text.'
				</div>
			</div>';
}

function addStamp($time,$type=0,$case='_uni-') {
    global $TEXT;
	
	if($type == 3) return date('h:i A', strtotime($time));

	// Explode date
    $seprate = explode('-', date('d-m-Y',strtotime($time))); $today=false;

    if (date('Ymd') == date('Ymd', strtotime($time)))  {
        $stamp = ($type == 2) ? $time : $TEXT[$case.'Today'] ;
        $today = ($type == 4) ? false : true;		
    } else if(date('Ymd', strtotime($time)) == date('Ymd', strtotime('yesterday'))) {
        $stamp = $TEXT[$case.'Yesterday'];	
    } elseif(date('Y', time()) === date('Y', strtotime($time))) {
        $stamp = $TEXT[$case."Month-".intval($seprate[1])].' '.$seprate[0];
    } else {
        $stamp = $TEXT[$case."Month-".intval($seprate[1])].' '.$seprate[0].', '.$seprate[2];
    }
	
	if($type == 2 || $type == 4) {
		return ($today) ? $stamp : $stamp.' '.$TEXT['_uni-at'].' '.date('h:ia', strtotime($time));
	} else {
		return $stamp;
	}
	
}

function addLog($val,$type=0) {
    $style = ($type) ? 'background-x b1 padding-10 dark-font-only' : 'padding-0-10 white-color tin-light-font-only';
    $st2 = ($type) ? ' fa-times' : ' fa-chevron-right';
    return '<div class=" h5 full noflow '.$style.'">
				<div class="padding-5" ><i class="fa'.$st2.'"></i> '.$val.'</div>
			</div> ';
		
}

function secureRand($len,$strong = FALSE) {                            // Secure random string generator	
	
	// If secure random is requested 
	if($strong && function_exists('openssl_random_pseudo_bytes')) {
		
		// Generate secure random string (requires Open SSL)
		return mb_substr(str_shuffle(bin2hex(openssl_random_pseudo_bytes(16))),0,$len, 'utf-8');
		
	} elseif($strong && !function_exists('openssl_random_pseudo_bytes')) {
		
		// If Open SSL functions doesn't exists generate random string as secure as possible
		return mb_substr(str_shuffle(mt_rand(11001,99999).str_shuffle("CeNVAsa3EpYR2").mt_rand(11001,99999).str_shuffle("85mvKqOgcZfy").str_shuffle("9n_Pli4BSUtD6X").str_shuffle("GFJ1kIhxM0HoTzubrQd7Lj")),0,$len, 'utf-8');
		
	} else {
		
		// Medium strength
		return mb_substr(str_shuffle("CeNVAsa3EpYR285mvKqOgcZfy9n_Pli4BSUtD6XGFJ1kIhxM0HoTzubrQd7Lj"),0,$len, 'utf-8').$ssl;
	
	}
}

function listUserCaps($id,$title,$img,$name,$v_batch,$inf,$rel_btn,$small = NULL,$grouped=NULL) {  // List user
    global $TEXT;
	
	$add_container_hoverable = $add_container = $text2 = '';
	
	$class = (!$small) ? 'brz-half': 'brz-full';
	
	if($grouped) {
	    $add_container = 'brz-display-container';
	}
	
	if($small) {
	    $size = '&w=30&h=30';
	    $size_visible = 'width:30px;height:30px;';
		$text1 = 'brz-small';
	} else {
	    $size = '&w=50&h=50';
	    $size_visible = 'width:50px;height:50px;';
	    $text1 = 'brz-medium';
	    $text2 = 'brz-user-padding';
	}
	
	if(empty($inf)) {
	$inf = '<span class="brz-text-white">Nothing to show</span>';}
	
	return '<div class="'.$text2.' '.$add_container.' '.$class.' brz-clear brz-white">
                <img onclick="loadProfile('.$id.')" title="'.$title.'" src="'.$TEXT['installation'].'/thumb.php?src='.$img.'&fol=a'.$size.'" alt="..." class="brz-round brz-border brz-border-super-grey brz-left brz-margin-right-small" style="'.$size_visible.'">
                <div onclick="loadProfile('.$id.')" title="'.$title.'" style="position: relative;bottom: 5px;" class="'.$text1.' brz-right-top brz-cursor-pointer brz-text-bold brz-text-blue-dark brz-underline-hover">
				    '.$name.'
				    '.$v_batch.'
				</div>
                <span style="position: relative;bottom: 12px;left:2px;" class="brz-small brz-opacity brz-text-grey">
				    '.$inf.'
				</span>
                <span class="brz-right nav-item-text-inverse-big">
                    '.$rel_btn.'
                </span>
            </div>';
}

function listVideo($id,$nm,$img,$ab3,$template) {       // List video for search page
    global $TEXT;

	$TEXT['temp-id'] = $id;	
	$TEXT['temp-nm'] = $nm;		
	$TEXT['temp-ab3'] = $ab3;
	$TEXT['temp-img'] = $img;
	
	return display('',$template);
}

function getPageSelects($array) {
	global $TEXT;
	
	$return = '';
	
	foreach($array as $row) {	
		$return .= '<option value="'.$row['cid'].'">'.$TEXT[$row['cat_name']].'</option>';
	}
	
	return $return;
	
}

function listMainUser($id,$nm,$u_nm,$img,$title,$v_bt,$ab1,$ab2,$ab3,$r_btn,$grp=NULL,$template) {       // List user for search page
    global $TEXT;

	$TEXT['temp-id'] = $id;		
	$TEXT['temp-title'] = $title;		
	$TEXT['temp-u_nm'] = $u_nm;	
	$TEXT['temp-nm'] = $nm;	
	$TEXT['temp-r_btn'] = $r_btn;	
	$TEXT['temp-ab3'] = $ab3;	
	$TEXT['temp-ab2'] = $ab2;	
	$TEXT['temp-ab1'] = $ab1;	
	$TEXT['temp-v_bt'] = $v_bt;	
	$TEXT['temp-img_src'] = 'f';
	$TEXT['temp-function_1'] = 'loadGroup('.$id.',1,1);' ;
	$TEXT['temp-user_img'] = $img;

	if(!$grp) {
		$TEXT['temp-img_src'] = 'a' ;
		$TEXT['temp-function_1'] = 'loadProfile('.$id.');' ; 
	}
	
	return display('',$template);
	
}
	
function nameCategory($page_cat,$page_cat_2,$all_cats,$db) {
    global $TEXT;
	
    if($page_cat == 1) {
		return $page_cat_2;
	} else {
			
		if(in_array($page_cat_2,$all_cats)) {
		
			$cat_index = getCategoryTitle($page_cat_2,$db);
	
			// Add Name
		    return $TEXT[$cat_index['cat_name']];

		}
	}
	
}

			
function addLoadmore($auto,$title,$function) {                                       // Add load more function
	global $TEXT;
	// Check whether infinite scrolling is enabled
	$auto_load = (!$auto) ? '' : 'class="AUTO-LOAD"';
	
	return '<div id="last_post_preload" class="load last_post_preload transparent" style="padding:15px 0px 15px 0px;width:70%;left:15%"></div>
			<div '.$auto_load.' id="load-more-data" align="center" >
				<button id="pre-loader-starter" title="'.$title.'" onclick="'.$function.'" class="load-more-data btn btn-small btn-light h3 b2 tin-light-font">'.$TEXT['_uni-Load_more'].'</button>
				<div class="padding-10"></div>
			</div>';
			
}

function getSelVal($value,$text_0,$text_1,$text_3) {                                 // Generate Selects || ACCEPT 3 VALs	
		
	if($value == $text_0) {

		$te1 = $text_0; $va1 = $text_0;
		$te2 = $text_1; $va2 = $text_1;
		$te3 = $text_3; $va3 = $text_3;

	} elseif($value == $text_1) {

		$te1 = $text_1; $va1 = $text_1;
		$te2 = $text_3; $va2 = $text_3;
		$te3 = $text_0; $va3 = $text_0;
	
	} else {
	
		$te1 = $text_3; $va1 = $text_3;
		$te2 = $text_0; $va2 = $text_0;
		$te3 = $text_1; $va3 = $text_1;	

	}
	return '<option value="'.$va1.'">'.$te1.'</option>
			<option value="'.$va2.'">'.$te2.'</option>
			<option value="'.$va3.'">'.$te3.'</option>';	
}
	
function getSelect($value,$text_1,$text_0) {                                         // Generate Selects || ACCEPT 2 VALs

	if($value == 1) {
		$te1 = $text_1 ;
		$va1 = '1';
		$te2 = $text_0 ;
		$va2 = '0';
	} else {
		$te1 = $text_0 ;
		$va1 = '0';
		$te2 = $text_1 ;
		$va2 = '1';
	}
	return '<option value="'.$va1.'">'.$te1.'</option>
		    <option value="'.$va2.'">'.$te2.'</option>';	
}	

function countFollows($page_id,$db) {                                       // Number Followers
	
	// Count likes
	$follows = $db->query(sprintf("SELECT COUNT(*) FROM `page_users` WHERE `page_users`.`page_id` = '%s' ", $db->real_escape_string($page_id)));

	list($numbers) = $follows->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function countLikes($page_id,$db) {                                         // Number likes

	// Count likes
	$likes = $db->query(sprintf("SELECT COUNT(*) FROM `page_likes` WHERE `page_likes`.`page_id` = '%s' ", $db->real_escape_string($page_id)));

	list($numbers) = $likes->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function numberGroupMembers($group_id,$db) {                                         // Number memebers of group

	// Count members
	$members = $db->query(sprintf("SELECT COUNT(*) FROM `group_users` WHERE `group_users`.`group_status` = '1' AND `group_users`.`group_id` = '%s' ", $db->real_escape_string($group_id)));

	list($numbers) = $members->fetch_row();
	
	// Return number of rows
	return $numbers;
	
}

function linkHref($links) {                                                          // Parse URLs            
	
    // Check URL for "http://"
	$link = (substr($links[1], 0, 4) == 'www.') ? 'http://'.$links[1] : $links[1];
	
	return '<a href="'.$link.'" target="_blank" class="h6 b2 theme-font">'.str_replace(array('http://','https://'), '', $link).'</a>';

}

function getBirthday($date) {                                                        // Parse Birthdate
    global $TEXT;
	
	// Explode date
	$seprate = explode('-', $date);

	// Start checking the values
	return (!empty($seprate[1]) && !empty($seprate[0]) && !empty($seprate[2])) ? $TEXT["_uni-Month-".intval($seprate[1])].' '.$seprate[0].', '.$seprate[2]:FALSE;

}

function fuzzyStamp($time,$now = NULL,$type = '') {                                  // Convert INT to fuzzy time stamps
	global $TEXT;
	
	// Time gap
	$ago = time() - $time; 
	
	// IF on same second show active
	$return = ($now) ? $TEXT['_uni-just_now'] : $TEXT['_uni-Online'];
	
	// Time array
	$second_set = array(
	    $TEXT['_uni-Time7'.$type] => 31536000, 
		$TEXT['_uni-Time6'.$type] => 2628000,
		$TEXT['_uni-Time5'.$type] => 604800, 
		$TEXT['_uni-Time4'.$type] => 86400, 
		$TEXT['_uni-Time3'.$type] => 3600, 
		$TEXT['_uni-Time2'.$type] => 60, 
		$TEXT['_uni-Time1'.$type] => 1 );
	
    $ago_it = (empty($type)) ? $TEXT['_uni-ago'] : '';	
	
	foreach($second_set as $val => $seconds) {

	    // If gap unders
		if($seconds <= $ago) {
			
			// Time gap
			$return = floor($ago / $seconds).$val.' '.$ago_it;
			
			break;	
		}
		
		// Else loop
	}
	
	return $return;	
}

function getFolderSize($dir) {                                                       // Get folder size
    
	// Reset
	$size = 0;
	
	// Loope for each file and count size
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : getFolderSize($each);
    }
	
	// Return total Bytes count
    return $size;
}

function display($file,$opened = 0,$return = 0) {                                                // Template Parser
    
	if(!$opened) {
	
        // Open template file	
	    $display_opened = fopen($file, 'r');
	
	    // Read contents
	    $display = @fread($display_opened, filesize($file));
	
		// Close template file
		fclose($display_opened);
	
	} else {
		$display = $file = $opened;
	}
	
	// Return template contents is requested
	if($return) {
		
		return $display;
		
	// Else parse ull template
	} else {

		// $display = preg_replace_callback('/{\$TEXT->(.+?)}/i', create_function('$matches', 'global $TEXT; return $TEXT[$matches[1]];'), $display); (Removed as it's unable to track missing strings)	
		
		// Parse template contents
		$display = preg_replace_callback('/{\$TEXT->(.+?)}/i', 
						
						function ($matches) use($file) {
							global $TEXT;
							return (isset($TEXT[$matches[1]])) ? $TEXT[$matches[1]] : '' ; // handleAll($file, $matches[1], '', '');
        				},
						
						$display);

		// Return parsed display
		return $display;
		
	}	
}
?>