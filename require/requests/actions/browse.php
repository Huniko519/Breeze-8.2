<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/classes.php');          // Import all classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// Global ARRAY
global $TEXT;

$i_pages = $db->query("SELECT * FROM `info_pages` WHERE `published` = '1'");

if($i_pages->num_rows) {
	
	// Reset imports
	$i_all_pages = $i_all_pages_ids = array();
	
	// Fetch settings
	while($i_page = $i_pages->fetch_assoc()) {
		$i_all_pages[] = $i_page;
		$i_all_pages_ids[] = $i_page['id'];
	}

}

// Clear login from recents
if(in_array($_POST['t'],$i_all_pages_ids)) {
  
    $page = $db->query(sprintf("SELECT * FROM `info_pages` WHERE `id` = '%s' LIMIT 1",$_POST['t']));

    if($page->num_rows) {

	    $page = $page->fetch_assoc();
		
		$TEXT['temp-title'] = $page['title_big'];
		$TEXT['temp-text'] = $page['text'];
	
		echo display(templateSrc('/browse/title')).str_replace('{URL}',$TEXT['installation'],display(templateSrc('/browse/text'))).'<script>document.title = \''.$db->real_escape_string($page['title_nav']).'\';</script>';

    }  
	
} else {
	
    $page = $db->query(sprintf("SELECT * FROM `info_pages` WHERE `id` = '1' LIMIT 1",$_POST['t']));

    if($page->num_rows) {
		
	    $page = $page->fetch_assoc();
	    $TEXT['temp-title'] = $page['title_big'];
		$TEXT['temp-text'] = $page['text'];

		echo display(templateSrc('/browse/title')).display(templateSrc('/browse/text')).'<script>document.title = \''.$db->real_escape_string($page['title_nav']).'\';</script>';

    }	
	
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>