<?php
function getQueries($type=null)  {
	
	// Settings queries
	if($type) {
		return array (
			1 => "UPDATE `users` SET `users`.`first_name` = ?,`users`.`last_name` = ? WHERE `users`.`idu` = ?",	
			2 => "UPDATE `users` SET `users`.`username` = ? WHERE `users`.`idu` = ?",	
			3 => "UPDATE `users` SET `users`.`email` = ? , `users`.`state` = ? WHERE `users`.`idu` = ?",	
			4 => "UPDATE `users` SET `users`.`password` = ? WHERE `users`.`idu` = ?",	
			5 => "UPDATE `users` SET `users`.`profession` = ? WHERE `users`.`idu` = ?",	
			6 => "UPDATE `users` SET `users`.`study` = ? WHERE `users`.`idu` = ?",	
			7 => "UPDATE `users` SET `users`.`from` = ? WHERE `users`.`idu` = ?",	
			8 => "UPDATE `users` SET `users`.`living` = ? WHERE `users`.`idu` = ?",	
			9 => "UPDATE `users` SET `users`.`interest` = ? WHERE `users`.`idu` = ?",	
			10 => "UPDATE `users` SET `users`.`relationship` = ? WHERE `users`.`idu` = ?",	
			11 => "UPDATE `users` SET `users`.`gender` = ? WHERE `users`.`idu` = ?",	
			12 => "UPDATE `users` SET `users`.`website` = ? WHERE `users`.`idu` = ?",	
			13 => "UPDATE `users` SET `users`.`b_day` = ? WHERE `users`.`idu` = ?",	
			14 => "UPDATE `users` SET `users`.`bio` = ? WHERE `users`.`idu` = ?",	
			15 => "UPDATE `users` SET `users`.`p_posts` = ?, `users`.`p_mention` = ? WHERE `users`.`idu` = ?",	
			16 => "UPDATE `users` SET `users`.`p_image` = ?, `users`.`p_cover` = ?, `users`.`p_followers` = ?, `users`.`p_followings` = ?  WHERE `users`.`idu` = ?",	
			17 => "UPDATE `users` SET `users`.`p_private` = ?, `users`.`p_web` = ?  WHERE `users`.`idu` = ?",	
			18 => "UPDATE `users` SET `users`.`p_profession` = ?, `users`.`p_study` = ?, `users`.`p_hometown` = ?, `users`.`p_location` = ?, `users`.`p_interest` = ?, `users`.`p_relationship` = ?, `users`.`p_gender` = ?, `users`.`p_bday` = ?  WHERE `users`.`idu` = ?",	
			19 => "UPDATE `users` SET `users`.`p_moderators` = ? WHERE `users`.`idu` = ?",	
			20 => "UPDATE `users` SET `users`.`n_type` = ?, `users`.`n_per_page` = ?, `users`.`n_accept` = ?, `users`.`n_follower` = ?, `users`.`n_like` = ?, `users`.`n_comment` = ?, `users`.`n_mention` = ? WHERE `users`.`idu` = ?",	
			21 => "UPDATE `users` SET `users`.`e_accept` = ?, `users`.`e_follower` = ?, `users`.`e_like` = ?, `users`.`e_comment` = ?, `users`.`e_mention` = ? WHERE `users`.`idu` = ?"	
		);
		
	// Register user query
	} else {
		
		return "INSERT INTO `users` (`idu`, `username`, `password`, `email`, `salt`, `first_name`, `last_name`, `date`, 
		`active`, `image`, `cover`, `verified`, `ip`,`onfeeds`, `onmessenger`, `b_day`, `profession`, `from`, 
		`living`, `gender`, `study`, `interest`, `relationship`, `website`, `bio`,`posts`, `photos`, `followers`, 
		`group_feeds`, `page_feeds`, `r_posts_per_page`, `r_followers_per_page`, `r_followings_per_page`, 
		`p_moderators`, `n_per_page`, `n_accept`, `n_type`, `n_follower`, `n_like`, `n_comment`, `n_mention`,
 		`e_accept`, `e_follower`, `e_like`, `e_comment`, `e_mention`, `p_posts`, `p_followers`, `p_followings`,
 		`p_profession`, `p_hometown`, `p_location`, `p_image`, `p_cover`, `p_mention`, `p_private`, `p_study`,
 		`p_relationship`, `p_interest`, `p_gender`, `p_bday`, `p_web`, `b_posts`, `b_comments`, `b_users`, 
 		`state`, `safe`, `p_chn`, `blogs`) VALUES
		(NULL, '%s', '%s', '%s', '', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', CURRENT_TIMESTAMP, 
		CURRENT_TIMESTAMP, '0', '', '', '', '%s', '', '0', '0', '', '', '0', '0', '0', '', '', '%s', '%s', '%s', '%s',
 		'%s', '%s', '%s', '%s', '%s', '%s', '0', '0', '0', '0', '0', '0', '%s', '%s', '%s', '%s', '%s', '%s', '0', 
 		'0', '0', '%s', '0', '0', '0', '0', '0', '0', '%s', '%s', '%s', '2', '0', '0', '0');";
	}
	
}

?>