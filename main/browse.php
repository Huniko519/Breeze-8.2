<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/classes.php');    // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Check whether requested page exists
$TEXT['temp-browseto'] = (isset($_GET['browse']) && is_numeric($_GET['browse'])) ? '<script>browseData('.$_GET['browse'].');</script>' : '<script>browseData(0);</script>' ;

$i_pages = $db->query("SELECT * FROM `info_pages` WHERE `published` = '1'");
if($i_pages->num_rows) {
	
	// Reset imports
	$i_all_pages = array();$TEXT['temp-i_pages']='';
	
	// Fetch settings
	while($i_page = $i_pages->fetch_assoc()) {
		$i_all_pages[] = $i_page;
	}
	
	$teml = display(templateSrc('/browse/nav-item'),0,1);
	
	// Bind settings
	foreach($i_all_pages as $i_page) {
		$TEXT['temp-href'] = $TEXT['installation'].'/browse/'.$i_page['id'];
		$TEXT['temp-title'] = $i_page['title_nav'];
		$TEXT['temp-id'] = $i_page['id'];
		$TEXT['temp-navitems'] .= display('',$teml);
	}
}




// Else display homepage
echo display('themes/'.$TEXT['theme'].'/html/browse/main'.$TEXT['templates_extension']);	

if(isset($db) && $db) {
	mysqli_close($db);
}
?>