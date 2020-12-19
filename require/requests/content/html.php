<?php
require_once('../../../main/config.php');                  // Import configuration
require_once('../../../require/main/database.php');        // Import configuration
require_once('../../../require/main/settings.php');        // Import configuration
require_once('../../../require/main/presets/preset_emojis.php');   // Import emoji index

// load emoji chooser
if($_POST['t'] == 'emoji-selecter') {
	
	// Create Emoji container
	$emojis_a = '<div id="content-emoji-all" class="content-emoji-all">
		            <div id="content-emoji-all-scroll" class="content-emoji-all" style="max-height:300px;">';

	// For each emoji create a clickable button
	foreach($emoji_ids as $key=>$emj) {	
		$emojis_a .= '<span onclick="addEmoji(\'#update-status-emoji-target\',\''.str_replace(array("{","}"),array("",""),$emoji_ids[$key]).'\');" data-emoji="'.str_replace(array("{","}"),array("",""),$emoji_ids[$key]).'" class="col s2 left noflow pointer center padding-6-0 round hover-greyscale"  ><img src="'.$TEXT['installation'].'/themes/'.$TEXT['theme'].'/img/emojis/'.$emoji_files[$key].'.png" width="30"></span>';
	}
	
	// Echo emoji chooser inner html
	echo $emojis_a.'</div></div><script>
		                $("#get-post-form-emoji-selecter").addClass("addedContent");
		                $("#content-emoji-all-scroll").niceScroll();
		            </script>' ;
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>