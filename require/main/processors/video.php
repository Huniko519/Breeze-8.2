<?php

function getVideoByID($id,$db) {
	
	$result = $db->query(sprintf("SELECT * FROM `user_posts`, `users` WHERE `user_posts`.`post_type` = '2' AND `users`.`idu` = `user_posts`.`post_by_id` AND `user_posts`.`post_id` = '%s' ",$db->real_escape_string($id)));
		
	return (!empty($result) && $result->num_rows !== 0) ? $result->fetch_assoc() : 0;
		
}
 
function updateView($id) { 
    global $db;
	$db->query(sprintf("UPDATE `user_posts` SET `post_views` = `post_views` + 1 WHERE `post_id` = '%s'",$db->real_escape_String($id)));
}
   
function addVideo($user,$post,$db) {    
    global $TEXT;

	// Escape variable for MySQl Query 
	$user_esc = $db->real_escape_string($user['idu']);
	$description_esc = $db->real_escape_string(protectInput($post['description']));
	$title_esc = $db->real_escape_string(protectInput($post['title']));
	$img_esc = $db->real_escape_string(protectInput($post['img']));
	$tags_esc = $db->real_escape_string(protectInput($post['tags']));
	$content_esc = $db->real_escape_string(protectInput($post['name']));

    $query = "INSERT INTO `user_posts` (`post_id`, `post_by_id`, `post_content`, `post_text`, `post_type`, `post_tags`, `post_time`, `post_extras`, `link_title`, `link`, `link_description`, `link_img`, `post_deleted`) VALUES (NULL, '$user_esc', '$content_esc', '', '2', '$tags_esc', CURRENT_TIMESTAMP, '0,0,0', '$title_esc', '', '$description_esc', '$img_esc', '0');";

	// If post inserted successfully 
	if($db->query($query) === TRUE) {
		
		$post_id = $db->insert_id;

		// Update user posts and photos count
		$query = $db->query("UPDATE `users` SET `posts` = `posts` + '1' WHERE `idu` = '$user_esc' ; ");
		
		return array($TEXT['installation'].'/view/'.$post_id,0);
	
	} else {
		return array(0,$TEXT['_uni-Error_mysql'].$db->error);
	}		
}
	
?>