<?php

function commentAct($db,$user,$id,$type) {
    global $TEXT,$profile,$page_settings;
	
	$user_id = $user['idu'];

	// Is comment exists
    $comment = $db->query(sprintf("SELECT * FROM `post_comments` WHERE `id` = '%s'", $db->real_escape_string($id)));

	if(!empty($comment)) {
		$comment = $comment->fetch_assoc();
		$commenter = $profile->getUserByID($comment['by_id']);   
		
		// Escape variables for SQL Query
		$comment_esc = $db->real_escape_string(fixText(40,$comment['comment_text']));
		$comment_id_esc = $db->real_escape_string($comment['id']);
		$post_id_esc = $db->real_escape_string($comment['post_id']);
		$user_id_esc = $db->real_escape_string($user_id);
		$commenter_id_esc = $db->real_escape_string($commenter['idu']);
	    $verified = $profile->isLoved($comment['id'],$user_id,1); 
	
		// Do love(Like)
		if($type == 1) {
		
			// If comment is not loved
			if(!$verified) {
			
				// Insert new Love(Like)
				$query = $db->query("INSERT INTO `comment_likes` (`id`, `comment_id`, `by_id`,`time`) VALUES (NULL, '$comment_id_esc', '$user_id_esc', CURRENT_TIMESTAMP) ");
			
				// Insert notification
				if($commenter_id_esc !== $user_id_esc) {
					$query = $db->query("INSERT INTO `notifications`(`id`, `not_from`, `not_to`, `not_content_id`,`not_content`,`not_data`,`not_type`,`not_read`, `not_time`) VALUES (NULL, '$user_id_esc', '$commenter_id_esc', '$post_id_esc','0','$comment_esc','16','0', CURRENT_TIMESTAMP) ") ;
				}	

			}
		} elseif($type == 0) {
	 
			// If post is loved
			if($verified) {

				// Delete post love
				$query = $db->query("DELETE FROM `comment_likes` WHERE `comment_id` = '$comment_id_esc' AND `by_id` = '$user_id_esc' ");
 
				// Delete notifications related 
				//$query = $db->query("DELETE FROM `notifications` WHERE `notifications`.`not_from` = '$user_id_esc' AND `notifications`.`not_to` = '$poster_id_esc' AND `notifications`.`not_content_id` = '$post_id_esc' AND `notifications`.`not_type` = '16' ");

			}
		}
	}		
}
	
?>