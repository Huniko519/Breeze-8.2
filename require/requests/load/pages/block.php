<?php
session_start();

require_once("../../../../main/config.php");        // Import configuration
require_once('../../../main/database.php');         // Import database connection
require_once('../../../main/classes.php');          // Import all classes
require_once('../../../main/settings.php');         // Import settings
require_once('../../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;

// Check credentials
if((isset($_SESSION['username']) && isset($_SESSION['password'])) || (isset($_COOKIE['username']) && isset($_COOKIE['password']))) {
	
	// Pass properties to fetch logged user if exists
	$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
	$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

	// Try fetching logged user
	$user = $profile->getUser();
	
	// Pass administration settings
	$profile->settings = $page_settings;
	
	// If user doesn't exists
	if(empty($user['idu'])){
		echo showError($TEXT['lang_error_connection2']);
	} else {
	
		// Pass user properties
		$profile->followings = $profile->listFollowings($user['idu']);
		
        if ($_POST['ff'] == '1') {

			$TEXT['TEMP-settings_title'] = $TEXT['_uni-Block_User'];
			$TEXT['TEMP-content'] = '<div class="clear full background-x right-divider settings-content-class bottom-divider">
										<div class="full">
											<div class="full">
												<div class="left clear2 settings-20 full">			
													<div class="block-box-2 trans">			
														<div class="left full">			
															<div class="input-title left h6 b1 dark-font-only">
																'.$TEXT['_uni-Username'].'
															</div>
															<div class="input-container">
																<input id="block_username" class="h5 b1 light-font-only" value="">
																<div class="input-description h4 b1 tin-light-font-only">
																</div>
															</div>
														</div>	
													</div>	 
												</div>			
												<div class="center clear top-divider padding-10 h4 b1 tin-light-font-only">
													<div class="padding-10 text-left">
														'.$TEXT['_uni-Username_Block_ttl'].'
													</div>
													<div class="block clear2 margin-10">
														<span id="settings-content-space-2"></span>
														<div id="settings-content-save-2" onclick="newBlockUser();" class="right btn btn-small btn-small-inline btn-dark"><span>'.$TEXT['_uni-Block_User'].'</span></div>
													</div>			
												</div>			
											</div>
										</div>
										
									</div>';

			$TEXT['content'] = display(templateSrc('/main/settings'));

			$TEXT['posts'] = '<div style="margin-top:10px;"><div class="h7 b1 dark-font-only padding-10 bottom-divider">'.$TEXT['_uni-Blocked_Users'].'</div>'.Blocker::BlockedUsers(0, $user).'</div>';

			echo display(templateSrc('/main/left_large'));

		} elseif($_POST['ff'] == '2') 
		{
			$user_name = $_POST['p'];

			$get_user = $profile->getUserByUsername($user_name);
			
			if($user_name == $user['username'])
			{
				$return = showError($TEXT['_uni-Block_Own']);
			}
			elseif(!isset($get_user['idu']))
			{
				$return = showError($TEXT['_uni-User_exists_0']);
			}
			else
			{
				$return = "<script>profileLoadBlock(".$get_user['idu'].", 0)</script>";
			}

			$TEXT['TEMP-settings_title'] = $TEXT['_uni-Block_User'].$return;
			
			$TEXT['TEMP-content'] = '<div class="clear full background-x right-divider settings-content-class bottom-divider">
										<div class="full">
											<div class="full">
												<div class="left clear2 settings-20 full">			
													<div class="block-box-2 trans">			
														<div class="left full">			
															<div class="input-title left h6 b1 dark-font-only">
																'.$TEXT['_uni-Username'].'
															</div>
															<div class="input-container">
																<input id="block_username" class="h5 b1 light-font-only" value="">
																<div class="input-description h4 b1 tin-light-font-only">
																</div>
															</div>
														</div>	
													</div>	 
												</div>			
												<div class="center clear top-divider padding-10 h4 b1 tin-light-font-only">
													<div class="padding-10 text-left">
														'.$TEXT['_uni-Username_Block_ttl'].'
													</div>
													<div class="block clear2 margin-10">
														<span id="settings-content-space-2"></span>
														<div id="settings-content-save-2" onclick="newBlockUser();" class="right btn btn-small btn-small-inline btn-dark"><span>'.$TEXT['_uni-Block_User'].'</span></div>
													</div>			
												</div>			
											</div>
										</div>
										
									</div>';

			$TEXT['content'] = display(templateSrc('/main/settings'));

			$TEXT['posts'] = '<div style="margin-top:10px;"><div class="h7 b1 dark-font-only">'.$TEXT['_uni-Blocked_Users'].'</div>'.Blocker::BlockedUsers(0, $user).'</div>';

			echo display(templateSrc('/main/left_large'));

        } elseif($_POST['ff'] == '3') {	
			$from = $_POST['f'];
			echo Blocker::BlockedUsers($from, $user);
        } elseif($_POST['ff'] == '12') {	
			$TEXT['temp-user_id'] = $_POST['v1'];

			$block = Blocker::GetBlock($_POST['v1'], $user['idu']);
			
			$TEXT['temp-p_follow'] = $TEXT['temp-p_chat'] = $TEXT['temp-p_search'] = $TEXT['temp-p_profile'] = "0";
			$TEXT['temp-p_groups'] = $TEXT['temp-p_page_invite'] = "0";
			
			if($block)
			{
				if($block['follow']) $TEXT['temp-p_follow'] = "1";
				if($block['chat']) $TEXT['temp-p_chat'] = "1";
				if($block['search']) $TEXT['temp-p_search'] = "1";
				if($block['profile']) $TEXT['temp-p_profile'] = "1";
				if($block['groups']) $TEXT['temp-p_groups'] = "1";
				if($block['page_invite']) $TEXT['temp-p_page_invite'] = "1";
			}

			$TEXT['temp-inner_content'] = display(templateSrc('/block/block_member'));

			echo display(templateSrc('/block/block_member_combine'));

		} elseif($_POST['ff'] == '13') {	
			
			$user_id = $_POST['v1'];
			$save_follow = (isset($_POST['v2']) && $_POST['v2']) ? "1" : 0;
			$save_chat = (isset($_POST['v3']) && $_POST['v3']) ? "1" : 0;
			$save_search = (isset($_POST['v4']) && $_POST['v4']) ? "1" : 0;
			$save_profile = (isset($_POST['v5']) && $_POST['v5']) ? "1" : 0;
			$save_groups = (isset($_POST['v6']) && $_POST['v6']) ? "1" : 0;
			$save_page_invite = (isset($_POST['v7']) && $_POST['v7']) ? "1" : 0;

			if(!$save_chat && !$save_follow && !$save_profile && !$save_search)
			{
				Blocker::UpdateBlock($user_id, $user['idu'], false);
				$return = showError($TEXT['_uni-User_Unblocked']);
			}
			else
			{
				$blocks = array(
					"follow" => $save_follow,
					"chat" => $save_chat,
					"search" => $save_search,
					"profile" => $save_profile,
					"groups" => $save_groups,
					"page_invite" => $save_page_invite
				);
				Blocker::UpdateBlock($user_id, $user['idu'], $blocks);
				$return = showError($TEXT['_uni-Block_Updated']);
			}

			echo $return.'<script>contentLoader(0,2);</script>';			
			
	    } elseif($_POST['ff'] == '9') {	
			
			$user_id = $_POST['v1'];

			Blocker::UpdateBlock($user_id, $user['idu'], false);			
			
	    } else {
		    echo showError($TEXT['lang_error_script1']); 
	    } 
	}
// No credentials	
} else {
	echo showError($TEXT['lang_error_connection2']);
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>