<?php
require_once('./main/config.php');             // Import configuration
require_once('./require/main/database.php');   // Import database connection
require_once('./require/main/admin.php');      // Import all classes
require_once('./require/main/settings.php');   // Import settings
require_once('./language.php');                // Import language

// Add administration class
$profile = new manage();
$profile->db = $db;	

// Reset
$TEXT['content_redirect'] = $TEXT['posts'] = '';

// Check administration credentials
if((isset($_SESSION['a_username']) && isset($_SESSION['a_password'])) || (isset($_COOKIE['a_username']) && isset($_COOKIE['a_password']))) {
    
	// Pass properties
	$profile->username = (isset($_SESSION['a_username'])) ? $_SESSION['a_username'] : $_COOKIE['a_username'];
	$profile->password = (isset($_SESSION['a_password'])) ? $_SESSION['a_password'] : $_COOKIE['a_password'];
	
	// Try fetching logged administration
	$admin = $profile->getAdmin();
	
	// If administration is logged and exists
	if(!empty($admin['id'])) {
		
		// If a theme is requested
		if(isset($_GET['theme'])) {
			
			// Apply theme and redirect to themes page
			if($profile->applyTheme($_GET['theme'])) {
				header("Location: ".$TEXT['installation']."/index.php?admin=done&view=themes");
			}
		
		}
		
		// Generate navigation 
		$TEXT['page_navigation'] = $profile->genNavigation($admin);
		
		// Add CSS loader to body
		$TEXT['page_mainbody'] = '<div id="content-body" name="content-body" class="main-body clear" style="margin-left:250px;margin-top:45px;">
		                            <div align="center" id="temp_pre_loader_load_more_feed">
										<img class="margin-20 loader-big" src="'.$TEXT['installation'].'/themes/'.$page_settings['theme'].'/img/icons/loader.svg" ></img>
									</div>
								</div>
								<script>refreshElements();</script>';
								
		// Display page
		echo display('themes/'.$TEXT['theme'].'/html/main/main'.$TEXT['templates_extension']);	

		$val = (isset($_GET['v']) && !empty($_GET['v']) && !isXSSED($_GET['v'])) ? $_GET['v'] : FALSE ;
		
		// Check URL
		//if(isset($_GET['view']) && in_array($_GET['view'],array("languages","backgrounds","extra","seo","smtp","security","regsearch","extensions","groups","patch","users","update","themes","websettings","usersettings","reports","adds","edit","search"))) {
		
		// Explore content
		if(isset($_GET['view']) && in_array($_GET['view'],array("users","groups","reports"))) {
			
			if($_GET['view'] == "users") {
				echo "<script>_admin(42,0,0,'load','users',1,1);</script>";
			} elseif($_GET['view'] == "groups") {
				echo "<script>_admin(46,0,0,'load','groups',1,1);</script>";
			} elseif($_GET['view'] == "reports") {
				echo "<script>_admin(50,0,0,'load','reports',1,1);</script>";
			}
			echo '<script>$("#EXP_SET_ST").click();</script>';
		
        // Manage		
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("backgrounds","pagecategories","blogcats","sponsors","info"))) {
			
			if($_GET['view'] == "backgrounds") {
				echo "<script>_admin(52,0,0,'load','backgrounds',1,1);</script>";
			} elseif($_GET['view'] == "pagecategories") {
				echo "<script>_admin(55,0,0,'load','pagecats',1,1);</script>";
			} elseif($_GET['view'] == "sponsors") {
				echo "<script>_admin(57,0,0,'load','sponsors',1,1);</script>";
			} elseif($_GET['view'] == "blogcats") {
				echo "<script>_admin(70,0,0,'load','blogcats',1,1);</script>";
			} elseif($_GET['view'] == "info") {
				echo "<script>_admin(63,0,0,'load','infopages',1,1);</script>";
			} 
			
			echo '<script>$("#MNA_SET_ST").click();</script>';
		
        // Boost		
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("paypal","boads"))) {
			
			if($_GET['view'] == "paypal") {
				echo "<script>_admin(76,0,0,'load','paypal',1,1);</script>";
			} elseif($_GET['view'] == "boads") {
				echo "<script>_admin(78,0,0,'load','boads',1,1);</script>";
			}
			
			echo '<script>$("#SPO_SET_ST").click();</script>';
		
        // Extenion settings		
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("extensions"))) {
			
			if($_GET['view'] == "extensions") {
				echo "<script>_admin(58,0,0,'load','extensions',1,1);</script>";
			} 
			echo '<script>$("#EXT_SET_ST").click();</script>';
		
        // Core settings		
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("updates","patches"))) {
			
			if($_GET['view'] == "updates") {
				echo "<script>_admin(59,0,0,'load','updates',1,1);</script>";
			} elseif($_GET['view'] == "patches") {
				echo "<script>_admin(60,0,0,'load','patches',1,1);</script>";
			}
			
			echo '<script>$("#COR_SET_ST").click();</script>';		
		
		// Admin settings
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("access"))) {
			
			if($_GET['view'] == "access") {
				echo "<script>_admin(61,0,0,'load','access',1,1);</script>";
			} 
			
			echo '<script>$("#ADM_SET_ST").click();</script>';
			
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("seo","smtp","videos", "sockets", "polling","images","website","themes","languages"))) {
		
		    if($_GET['view'] == "seo") {
				echo "<script>_admin(2,0,0,'load','seo',1,1);</script>";
			} elseif($_GET['view'] == "smtp") {
				echo "<script>_admin(1,0,0,'load','smtp',1,1);</script>";
			} elseif($_GET['view'] == "images") {
				echo "<script>_admin(17,0,0,'load','images',1,1);</script>";
			} elseif($_GET['view'] == "website") {
				echo "<script>_admin(19,0,0,'load','website',1,1);</script>";
			} elseif($_GET['view'] == "themes") {
				echo "<script>_admin(39,0,0,'load','themes',1,1);</script>";
			} elseif($_GET['view'] == "videos") {
				echo "<script>_admin(80,0,0,'load','videos',1,1);</script>";
			} elseif($_GET['view'] == "polling") {
				echo "<script>_admin(68,0,0,'load','polling',1,1);</script>";
			} elseif($_GET['view'] == "sockets") {
				echo "<script>_admin(84,0,0,'load','sockets',1,1);</script>";
			} elseif($_GET['view'] == "languages") {
				echo "<script>_admin(41,0,0,'load','languages',1,1);</script>";
			} else {
				echo '<script>loadStats();</script>';
			}		
		    
			echo '<script>$("#MAJ_SET_ST").click();</script>';
		
		// Content Settings
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("user","express","popular","posts","chats","comments","grouplimits"))) {
		
		    if($_GET['view'] == "user") {
				echo "<script>_admin(21,0,0,'load','users',1,1);</script>";
			} elseif($_GET['view'] == "express") {
				echo "<script>_admin(23,0,0,'load','express',1,1);</script>";
			} elseif($_GET['view'] == "popular") {
				echo "<script>_admin(82,0,0,'load','popular',1,1);</script>";
			} elseif($_GET['view'] == "posts") {
				echo "<script>_admin(25,0,0,'load','posts',1,1);</script>";
			} elseif($_GET['view'] == "chats") {
				echo "<script>_admin(27,0,0,'load','chats',1,1);</script>";
			} elseif($_GET['view'] == "grouplimits") {
				echo "<script>_admin(29,0,0,'load','groups',1,1);</script>";
			} elseif($_GET['view'] == "comments") {
				echo "<script>_admin(31,0,0,'load','comments',1,1);</script>";
			} else {
				echo '<script>loadStats();</script>';
			}		
		    
			echo '<script>$("#CON_SET_ST").click();</script>';
		
		// Features Settings
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("expcontent","extra","trendingtags","groupfeatures"))) {
		
		    if($_GET['view'] == "expcontent") {
				echo "<script>_admin(33,0,0,'load','expcontent',1,1);</script>";
			} elseif($_GET['view'] == "trendingtags") {
				echo "<script>_admin(35,0,0,'load','trendingtags',1,1);</script>";
			} elseif($_GET['view'] == "groupfeatures") {
				echo "<script>_admin(37,0,0,'load','groupfeatures',1,1);</script>";
			} elseif($_GET['view'] == "extra") {
				echo "<script>_admin(15,0,0,'load','extra',1,1);</script>";
			} elseif($_GET['view'] == "grouplimits") {
				echo "<script>_admin(29,0,0,'load','groups',1,1);</script>";
			} elseif($_GET['view'] == "comments") {
				echo "<script>_admin(31,0,0,'load','comments',1,1);</script>";
			} else {
				echo '<script>loadStats();</script>';
			}		
		    
			echo '<script>$("#FEA_SET_ST").click();</script>';
		
		// Registration settings
		} elseif(isset($_GET['view']) && in_array($_GET['view'],array("privacy","blocking","notification","security","regsearch"))) {
			
			if($_GET['view'] == "security") {
				echo "<script>_admin(13,0,0,'load','security',1,1);</script>";
			} elseif($_GET['view'] == "regsearch") {
				echo "<script>_admin(11,0,0,'load','search',1,1);</script>";
			} elseif($_GET['view'] == "privacy") {
				echo "<script>_admin(9,0,0,'load','privacy',1,1);</script>";
			} elseif($_GET['view'] == "blocking") {
				echo "<script>_admin(7,0,0,'load','blocking',1,1);</script>";
			} elseif($_GET['view'] == "notification") {
				echo "<script>_admin(5,0,0,'load','notification',1,1);</script>";
			} else {
				echo '<script>loadStats();</script>';
			}
			
			echo '<script>$("#REG_SET_ST").click();</script>';
			
		} else {
			
			// Use JS function to display requested page if page exists
			if($_GET['view'] == "themes") {
				echo '<script>loadThemes();</script>';
			} elseif($_GET['view'] == "languages") {
				echo '<script>loadLanguages();</script>';
			} elseif($_GET['view'] == "update") {
				echo '<script>updateWebsite();</script>';
			} elseif($_GET['view'] == "patch") {
				echo '<script>patchWebsite();</script>';
			} elseif($_GET['view'] == "backgrounds") {
				echo '<script>loadBackgrounds();</script>';
			} elseif($_GET['view'] == "extensions") {
				echo '<script>loadExtensions();</script>';
			} elseif($_GET['view'] == "websettings") {
				echo '<script>loadWebSettings();</script>';
			} elseif($_GET['view'] == "usersettings") {
				echo '<script>loadNewregsettings();</script>';
			} elseif($_GET['view'] == "reports") {
				echo '<script>loadReports(0,1,1);</script>';
			} elseif($_GET['view'] == "adds") {
				echo '<script>manageAdds();</script>';
			} elseif($_GET['view'] == "edit") {
				echo '<script>editAdmin();</script>';
			} elseif($_GET['view'] == "search" && $val) {
				echo '<script>$(\'.wefw4er3e\').val(\''.$val.'\');searchAdmin(0,0,1);</script>';
			} elseif($_GET['view'] == "users") {
				echo '<script>manageUsers(0,0,1);</script>';
			} elseif($_GET['view'] == "groups") {
				echo '<script>manageGroups(0,0,1);</script>';
			
			} elseif($_GET['view'] == "extra") {
				echo "<script>_admin(15,0,0,'load','extra',1,1);</script>";
			} elseif($_GET['view'] == "security") {
				echo "<script>_admin(13,0,0,'load','security',1,1);</script>";
			} elseif($_GET['view'] == "regsearch") {
				echo "<script>_admin(11,0,0,'load','search',1,1);</script>";
			} elseif($_GET['view'] == "privacy") {
				echo "<script>_admin(9,0,0,'load','privacy',1,1);</script>";
			} elseif($_GET['view'] == "blocking") {
				echo "<script>_admin(7,0,0,'load','blocking',1,1);</script>";
			} elseif($_GET['view'] == "seo") {
				echo "<script>_admin(2,0,0,'load','seo',1,1);</script>";
			} elseif($_GET['view'] == "smtp") {
				echo "<script>_admin(1,0,0,'load','smtp',1,1);</script>";
			} elseif($_GET['view'] == "notification") {
				echo "<script>_admin(5,0,0,'load','notification',1,1);</script>";
			} else {
				echo '<script>loadStats();</script>';
			}		
			
			// Else fetch full body
			echo '<script>loadStats();</script>';		
		}
			
    // Go to home
	} else {
		echo '<script>window.location.href = \''.$TEXT['installation'].'\' ;</script>';
	}	
	
// Welcome page for administration
} else {
	
	// Redirect to home
    echo '<script>window.location.href = \''.$TEXT['installation'].'\' ;</script>';
}

if(isset($db) && $db) {
	mysqli_close($db);
}
?>