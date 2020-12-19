<?php 
// Connect database
$db = new mysqli($SET['database_host'], $SET['database_user'], $SET['database_pass'], $SET['database_name']);

if ($db->connect_errno) {

    die('<br><br><div align="center"><h1 style="color:#666;font-weight:normal;">Failed to connect MySQL <span style="font-size:40px;background:#F65868;color:#fff;border-radius:4px;padding:2px;">'.$db->connect_errno.'</span></h1></div>');
	
} else {
	
	// If connected without any errors set DB CHARSET
    $db->set_charset("utf8");	
}

// Database function
function database($type = 'SINGLE', $todo = 'QUERY',  $returntype = NULL, $string = '', $returnData = '') {
	global $db;$error = 0;
	
	// DO query
	if($todo == 'QUERY') {
		
		if($type == 'SINGLE') {
			$proccess = $db->query($string) or ($error = 1);
		} else {
			$proccess = $db->multi_query($string) or ($error = 1);
		}
		
		if($error) {

			$myfile = fopen(__DIR__."/sql.log", "a") or die("Unable to open file!");

			fwrite($myfile, $db->error . "($string)" . "\n");

			fclose($myfile);
	
		}
		
	} 
	
	// If no error occured and a query
	if(!$error && $todo == 'QUERY') { 
	
		if($returntype == 'NUMROWS') {
			
			$return = $proccess->num_rows;
			
		} elseif($returntype == 'NUMROWS_CHK') {
			
			$return = ($proccess->num_rows) ? 1 : 0;
			
		} elseif($returntype == 'FETCH') { 
		
			$return = ($proccess->num_rows) ? $proccess->fetch_assoc() : 0;
			
		} elseif($returntype == 'COUNT') { 
		
			list($return) = $proccess->fetch_row();
			
		} elseif($returntype == 'LIST') { 

			$return = array();	
		
			if(!empty($proccess) && $proccess->num_rows !== 0) {
			
				while($row = $proccess->fetch_assoc()) {
					$return[] = $row[$returnData];
				}

			}
			
		} elseif($returntype == 'STATUS') { 
		
			$return = ($error) ? FALSE : TRUE;
			
		} 
		
	}
	
	// Clean desk
	if($todo == 'QUERY' || $todo == 'CLEAR') {
		do { 
		
			// Set free
			if ($res = $db->store_result()) $res->free();
	  
		// Check for more results
		} while ($db->more_results() && $db->next_result());	
	}
	
	// Return if requested
	if(!$error && $returntype) return $return;
	
}

// Return query context
function contextQuery($address = NULL) {
	
	$query_set = array(
	
		"PASSWORDMATCHES"  => "SELECT `username` FROM `users` WHERE `users`.`idu` = '%s' AND `users`.`password` = '%s'",
		
		"SALTMATCHES"      => "SELECT `username` FROM `users` WHERE `users`.`idu` = '%s' AND `users`.`salt` = '%s'",
		
		"PAGE_UNAME"       => "SELECT `page_username` FROM `pages` WHERE `pages`.`page_username` = '%s'",
		
		"GROUP_UNAME"      => "SELECT `group_name` FROM `groups` WHERE `groups`.`group_username` = '%s'",
		
		"U_IDU_UNAME"      => "SELECT `idu` FROM `users` WHERE `users`.`username` = '%s'",
		
		"EMAIL_EXISTS"     => "SELECT `idu` FROM `users` WHERE `users`.`email` = '%s'",
		
		"ISREQUESTED"      => "SELECT `id` FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`user2` = '%s' AND `friendships`.`status` = '2'",
		
		"COMMENT_LIKE"     => "SELECT * FROM `comment_likes` WHERE `comment_id` = '%s' AND `by_id` = '%s'",
		
		"POST_LIKE"        => "SELECT * FROM `post_loves` WHERE `post_id` = '%s' AND `by_id` = '%s'",
		
		"PAGE_LIKE"        => "SELECT `id` FROM `page_likes` WHERE `page_id` = '%s' AND `by_id` = '%s'",
		
		"COMMENT_LIKE"     => "SELECT * FROM `comment_likes` WHERE `comment_id` = '%s' AND `by_id` = '%s'",
		
		"ISGROUP_MEMBER"   => "SELECT `user_id` FROM `group_users` WHERE `user_id` = '%s' AND `group_id` = '%s'",
		
		"ISCHAT_MEMBER"    => "SELECT `cid` FROM `chat_users` WHERE `uid` = '%s' AND `form_id` = '%s'",
		
		"PAGE_ROLE_PID"    => "SELECT * FROM `page_roles` WHERE `pid` = '%s' AND `page_id` = '%s'",
		
		"PAGE_ROLE_IDU"    => "SELECT * FROM `page_roles` WHERE `user_id` = '%s' AND `page_id` = '%s'",
		
		"PAGE_USER_PID"    => "SELECT * FROM `page_users` WHERE `pid` = '%s' AND `page_id` = '%s'",
		
		"PAGE_USER_IDU"    => "SELECT * FROM `page_users` WHERE `user_id` = '%s' AND `page_id` = '%s'",
		
		"GROUP_USER_GID"   => "SELECT * FROM `group_users` WHERE `gid` = '%s'",
		
		"GROUP_USER_IDU"   => "SELECT * FROM `group_users` WHERE `user_id` = '%s' AND `group_id` = '%s'",
		
		"PAGE_BY_NAME"     => "SELECT * FROM `pages` WHERE `page_username` = '%s'",
		
		"PAGE_BY_ID"       => "SELECT * FROM `pages` WHERE `page_id` = '%s'",
		
		"GROUP_BY_NAME"    => "SELECT * FROM `groups` WHERE `group_username` = '%s'",
		
		"GROUP_BY_ID"      => "SELECT * FROM `groups` WHERE `group_id` = '%s'",
		
		"ARTICLE_BY_ID"    => "SELECT * FROM `blogs`, `users` WHERE `blogs`.`blog_id` = '%s' AND `users`.`idu` = `blogs`.`blog_by_id` LIMIT 1",
		
		"USR_BY_MAIL_PASS" => "SELECT * FROM `users` WHERE `users`.`email` = '%s' AND `users`.`password` = '%s'",
		
		"USR_BY_NAME_PASS" => "SELECT * FROM `users` WHERE `users`.`username` = '%s' AND `users`.`password` = '%s'",
		
		"USR_BY_IDU"       => "SELECT * FROM `users` WHERE `users`.`idu` = '%s'",
		
		"USR_BY_UNAME"     => "SELECT * FROM `users` WHERE `users`.`username` = '%s'",
		
		"USR_BY_EMAIL"     => "SELECT * FROM `users` WHERE `users`.`email` = '%s'",
		
		"USR_NAME_BY_IDU"  => "SELECT `idu`, `username`, `first_name`, `last_name` FROM `users` WHERE `users`.`idu` = '%s'",
		
		"FORM_N_USR_BY_FID" => "SELECT * FROM `chat_forms`, `users` WHERE `chat_forms`.`form_id` = '%s' AND `users`.`idu` = `chat_forms`.`form_by`",
		
		"POST_BY_ID"       => "SELECT * FROM `user_posts` WHERE `user_posts`.`post_id` = '%s'",
		
		"COMMENT_BY_ID"    => "SELECT * FROM `post_comments` WHERE `post_comments`.`id` = '%s'",
		
		"CN_POST_COMMENTS" => "SELECT COUNT(*) FROM `post_comments` WHERE `post_comments`.`post_id` = '%s'",
		
		"CN_USER_POSTS"    => "SELECT COUNT(*) FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s'",
		
		"NEW_USER_POSTS"   => "SELECT COUNT(*) FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s' AND `user_posts`.`post_time` > '%s'",
		
		"CN_USER_PHOTOS"   => "SELECT COUNT(*) FROM `user_posts` WHERE `user_posts`.`post_by_id` = '%s' AND `user_posts`.`post_type` = '1'",
		
		"CN_FOLLOWERS"     => "SELECT COUNT(*) FROM `friendships` WHERE `friendships`.`user2` = '%s' AND `friendships`.`status` = '1'",
		
		"CN_FOLLOWINGS"    => "SELECT COUNT(*) FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`status` = '1'",
		
		"USER_FOLLOWINGS"  => "SELECT `user2` FROM `friendships` WHERE `friendships`.`user1` = '%s' AND `friendships`.`status` = '1'",
		
		"USER_FOLLOWERS"   => "SELECT `user1` FROM `friendships` WHERE `friendships`.`user2` = '%s' AND `friendships`.`status` = '1'",
		
		"PAGE_LIKERS"      => "SELECT `by_id` FROM `page_likes` WHERE `page_likes`.`page_id` = '%s'",
		
		"GROUP_MEMBERS"    => "SELECT `user_id` FROM `group_users` WHERE `group_users`.`group_id` = '%s'",
		
		"CHAT_MEMBERS"     => "SELECT `uid` FROM `chat_users` WHERE `chat_users`.`form_id` = '%s'",
		
		"CN_NEW_MSGS"      => "SELECT COUNT(*) FROM `chat_messages`, `chat_users` WHERE `chat_messages`.`form_id` = `chat_users`.`form_id` AND `chat_users`.`uid` = '%s' AND `chat_messages`.`posted_on` > `chat_users`.`on_form` AND `chat_messages`.`by` != '%s'",
		
		"CN_NEW_NOTIS"     => "SELECT COUNT(*) FROM `notifications` WHERE `notifications`.`not_to` = '%s' AND `notifications`.`not_type` IN(1,2,3,4,5,6,7,8,9,10,11,12,13,14)  AND `notifications`.`not_read` = '0'",
		
		"UPDATE_USR_ACT"   => "UPDATE `users` SET `users`.`active` = '%s' WHERE `users`.`idu` = '%s'"
	
	);
	
	if($address && isset($query_set[$address])) {
		return $query_set[$address];
	}
}
?>