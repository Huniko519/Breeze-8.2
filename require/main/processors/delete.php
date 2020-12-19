<?php
function deleteUser($db,$id) {
	
	$user_id_esc = $db->real_escape_string($id);
	
	$db->query("DELETE FROM `users` WHERE `users`.`idu` = '$user_id_esc' ");
	
	if($db->affected_rows) {

		// Select posts
		$user_posts = $db->query("SELECT * FROM `user_posts` WHERE `user_posts`.`post_by_id` = '$user_id_esc' ");

		// Fetch posts if exists
		if(!empty($user_posts)) {
		
			$rows = array();
			
			// Create ARRAYs 
			while($row = $user_posts->fetch_assoc()) {
			
				$rows[] = $row['post_id'];
    
	
				// Delete linked content
				if($post['post_type'] == 1) { 

                    $images = explode(',', $row['post_content']);
					
					// Delete Post photo
					foreach($images as $image) {
						unlink("../../../uploads/posts/photos/".$image);
					}
				
			    } elseif($row['post_type'] == 2) {
					unlink("../../../uploads/posts/videos/".$row['post_content']);               // Delete Post video
				} elseif($row['post_type'] == 3) {
					unlink("../../../uploads/profile/main/".$row['post_content']);               // DEL-Profile photo if it's not in use
				} elseif($row['post_type'] == 5) {
					unlink("../../../uploads/profile/covers/".$row['post_content']);             // DEL-Cover photo if it's not in use
				}
				
		    }
				
			$user_posts->close();
				
			// Implode results make usable in MySQL IN Clause
			$user_posts_ids = implode(',', $user_posts_is);
		
		    // Delete user's post loves and post's comments
			$db->query("DELETE FROM `post_loves` WHERE `post_id` IN ({$user_posts_ids})");
		    $db->query("DELETE FROM `post_comments` WHERE `post_id` IN ({$user_posts_ids})");
	
			}

		// Update loves of posts that user has loved
		$db->query("UPDATE `user_posts` SET `user_posts`.`post_loves` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' )");
		
		// Update comments 
		$db->query("UPDATE `user_posts` SET `user_posts`.`post_comments` = `user_posts`.`post_loves`-1 WHERE `post_id` IN (SELECT `post_id` FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' )");
		
		// Delete all the comments
		$db->query("DELETE FROM `post_comments` WHERE `post_comments`.`by_id` = '$user_id_esc' ");
		
		// Delete the loves
		$db->query("DELETE FROM `post_loves` WHERE `post_loves`.`by_id` = '$user_id_esc' ");
		
		// Delete the reports
		$db->query("DELETE FROM `reports` WHERE `by` = '$user_id_esc' ");
		
		// Delete all the friendships
		$db->query("DELETE FROM `friendships` WHERE `user1` = '$user_id_esc' OR `user2` = '$user_id_esc' ");
		
		// Delete chat messages
		$db->query("DELETE FROM `chat_messages` WHERE `by` = '$user_id_esc' ");
		
		// Left chat forms
		$db->query("DELETE FROM `chat_users` WHERE `uid` = '$user_id_esc' ");
		
		// Delete all the notifications
		$db->query("DELETE FROM `notifications` WHERE `not_from` = '$user_id_esc' OR `not_to` = '$user_id_esc' ");
	}
}
?>