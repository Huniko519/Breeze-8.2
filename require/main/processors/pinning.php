<?php
function getPost($db,$user,$id) { 

	// Select post
	$post = $db->query(sprintf("SELECT * FROM `user_posts` WHERE `post_id`  = '%s'",$db->real_escape_string($id)));
    return ($post->num_rows) ? $post->fetch_assoc() : 0;
	
}
?>