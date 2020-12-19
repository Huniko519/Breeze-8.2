<?php
// Languages directory
$dir = __DIR__ .'/languages/';

// Search for .PHP files
$language = glob($dir . '*.php', GLOB_BRACE);

// Set default language
$set_lang = ($page_settings['default_lang']) ? $page_settings['default_lang'] : 'English';
$availables = '';
	
// If requested change language
if(isset($_GET['lang'])) {
	
	// If requested language exists set language
	if(in_array($dir.$_GET['lang'].'.php', $language)) {
		$set_lang = $_GET['lang'];
		setcookie('lang', $set_lang, time() +  (10 * 365 * 24 * 60 * 60) ,'/');
	} else {
		setcookie('lang', $set_lang, time() +  (10 * 365 * 24 * 60 * 60) ,'/');
	}

// Else check whether cookie is set	
} elseif(isset($_COOKIE['lang'])) {
	
	// If language set in cookie exists set it
	if(in_array($dir.$_COOKIE['lang'].'.php', $language)) {
		$set_lang = $_COOKIE['lang'];
	}
	
// Else extend default language lifespan
} else {
	setcookie('lang', $set_lang, time() +  (10 * 365 * 24 * 60 * 60) ,'/');
}

// Set active language
if(in_array($dir.$set_lang.'.php', $language)) {

	// Import language
    require_once($dir.$set_lang.'.php');
	
	$TEXT['TEMP-active_lang'] = $TEXT['LANG_SETTINGS_NAME'];
} else {

	// Import language
    require_once(__DIR__ .'/languages/English.php');
	
	$TEXT['TEMP-active_lang'] = $TEXT['LANG_SETTINGS_NAME'];
}

// Create list of available languages
foreach($language as $avail_lang) {
	
	$path = pathinfo($avail_lang);
	
	// If active add to active class else add to available class
    if($path['filename'] == $set_lang) {
	    $availables .= '<a style="text-decoration:none;" href="'.$TEXT['installation'].'/index.php?lang='.$path['filename'].'" class="full center margin-10-0-0-0 btn h7 b3 btn-small btn-active">'.ucfirst($path['filename']).'</a>';			
	} else {
	    $availables .= '<a style="text-decoration:none;" href="'.$TEXT['installation'].'/index.php?lang='.$path['filename'].'" class="full center margin-10-0-0-0 btn h6 b3 btn-small btn-light">'.ucfirst($path['filename']).'</a>';	
	}
	
}
// Available languages
$TEXT['TEMP-available-languages'] =  $availables;
?>