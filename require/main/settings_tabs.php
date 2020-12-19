<?php 
// Add settings wizards
function getTab($user,$t,$s = NULL) {
    global $TEXT;
	
	// Return if tab not set
    if(!$t) {return false;}
	
	// Add some identifiers
	if(isset($s) && $s !== 0) {
		
		// Settings saved class
		$class = ($s == 2) ? 'theme-color-active' : 'theme-x-color-active';
	    
		$save_type = ($s == 2) ? $TEXT['_uni-No_changes'] : $TEXT['_uni-Changes_saved'];
		
		// Settings saved message
		$pencil = '<div id="settings-tab-space-'.$t.'" class="right tin-light-font-only clear">
			            '.$save_type.'
					</div>';

	} else {
		
		// New
		$class = '';
		
		$pencil =  '<div id="settings-tab-space-'.$t.'" class="right clear"></div>';

	} 

	// Genreral settings || NAME TAB
	if($t == 1) {
	    return addSampleTab($t,$TEXT['_uni-Name'],fixName(100,$user['username'],$user['first_name'],$user['last_name']),$class,$pencil,'') ;	
	
	// Genreral settings || USERNAME TAB
	} elseif($t == 2) {
	    return addSampleTab($t,$TEXT['_uni-Username'],fixTEXT(20,$user['username']),$class,$pencil,'') ;	
	
	// Genreral settings || EMAIL TAB
	} elseif($t == 3) {
	    return addSampleTab($t,$TEXT['_uni-Email'],fixTEXT(20,$user['email']),$class,$pencil,'') ;	
	
	// Genreral settings || PASSWORD TAB
	} elseif($t == 4) {
	    $changed = (empty($user['p_chn'])) ? $TEXT['_uni-Never_changed'] : $TEXT['_uni-Changed'].' <span class="timeago" title="'.$user['p_chn'].'" >'.fuzzyStamp($user['p_chn'],1).'<span>';
	    return addSampleTab($t,$TEXT['_uni-Password'],$changed,$class,$pencil,'') ;	
	
	// Profile information || PROFFESION TAB
	} elseif($t == 5) {
	    $set = (empty($user['profession'])) ? $TEXT['_uni-Not_set'] : $user['profession'];
	    return addSampleTab($t,$TEXT['_uni-Profession'],$set,$class,$pencil,'') ;	
	
	// Profile information || EDUCATION TAB
	} elseif($t == 6) {
	    $set = (empty($user['study'])) ? $TEXT['_uni-Not_set'] : $user['study'];
	    return addSampleTab($t,$TEXT['_uni-Education'],$set,$class,$pencil,'') ;	
	
	// Profile information || HOMETOWN TAB
	} elseif($t == 7) {
	    $set = (empty($user['from'])) ? $TEXT['_uni-Not_set'] : $user['from'];
	    return addSampleTab($t,$TEXT['_uni-Hometown'],$set,$class,$pencil,'') ;	
	
	// Profile information || LIVING CITY TAB
	} elseif($t == 8) {
	    $set = (empty($user['living'])) ? $TEXT['_uni-Not_set'] : $user['living'];
	    return addSampleTab($t,$TEXT['_uni-Living_in'],$set,$class,$pencil,'') ;	
	
	// Profile information || INTERESTED IN TAB
	} elseif($t == 9) {
		if(empty($user['interest'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['interest'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
		}
	    return addSampleTab($t,$TEXT['_uni-Interested_in'],$set,$class,$pencil,'') ;	
	
	// Profile information || RELATIONSHIP TAB
	} elseif($t == 10) {
		if(empty($user['relationship'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['relationship'] > 1) ? $TEXT['_uni-In_a_rel']: $TEXT['_uni-Single'];
		}
	    return addSampleTab($t,$TEXT['_uni-Relationship'],$set,$class,$pencil,'') ;	
	
    // Profile information || GENDER TAB
	} elseif($t == 11) {
		if(empty($user['gender'])) {
		    $set = $TEXT['_uni-Not_set'];
		} else {
		    $set = ($user['gender'] > 1) ? $TEXT['_uni-Female']: $TEXT['_uni-Male'];
		}
	    return addSampleTab($t,$TEXT['_uni-Gender'],$set,$class,$pencil,'') ;	
	
	// Profile information || WEBSITE TAB
	} elseif($t == 12) {
	    $set = (empty($user['website'])) ? $TEXT['_uni-Not_set'] : $user['website'];
	    return addSampleTab($t,$TEXT['_uni-Website'],$set,$class,$pencil,'') ;	
	
	// Profile information || BIRTH TAB
	} elseif($t == 13) {
	    
		// Verify date
 		$set = (empty($user['b_day']) ||  !getBirthday($user['b_day'])) ? $TEXT['_uni-Not_set'] : getBirthday($user['b_day']);
	    return addSampleTab($t,$TEXT['_uni-Bday'],$set,$class,$pencil,'') ;	
		
	// Profile information || BIO TAB
	} elseif($t == 14) {
	    $set = (empty($user['bio'])) ? $TEXT['_uni-Not_set'] : fixText(250,$user['bio']);
	    return addSampleTab($t,$TEXT['_uni-Bio'],$set,$class,$pencil,'') ;	
	
	// Privacy setings || POSTS TAB
	} elseif($t == 15) {
	    return addSampleTab($t,$TEXT['_uni-Posts'],$TEXT['_uni-TTL-Posts_privacy'],$class,$pencil,'') ;	
	
	// Privacy setings || PROFILE TAB
	} elseif($t == 16) {
	    return addSampleTab($t,$TEXT['_uni-Profile'],$TEXT['_uni-TTL-Profile_privacy'],$class,$pencil,'') ;	
	
	// Privacy setings || CONTACT TAB
	} elseif($t == 17) {
	    return addSampleTab($t,$TEXT['_uni-Contact'],$TEXT['_uni-TTL-Profile-contact'],$class,$pencil,'') ;	
	
	// Privacy setings || INFO TAB
	} elseif($t == 18) {
	    return addSampleTab($t,$TEXT['_uni-Info'],$TEXT['_uni-TTL-Profile-info'],$class,$pencil,'') ;	

    // Privacy setings || SECURITY TAB
	} elseif($t == 19) {
	    return addSampleTab($t,$TEXT['_uni-Security'],$TEXT['_uni-TTL-Profile-security'],$class,$pencil,'') ;	
	
	// Notifications setings || ON BREEZE
	} elseif($t == 20) {
	    return addSampleTab($t,$TEXT['web_name'],sprintf($TEXT['_uni-TTL-not-breeze-desc'],$TEXT['web_name']),$class,$pencil,'') ;	
	
    // Notifications setings || ON EMAIL
	} elseif($t == 21) {
	    return addSampleTab($t,$TEXT['_uni-Email'],$TEXT['_uni-TTL-not-email-desc'],$class,$pencil,'') ;	
	}
}

// Add sample tab
function addSampleTab($t,$name,$val,$class,$pencil,$classes = NULL) {
	return '
	<div id="settings-tab-'.$t.'" onclick="openTab('.$t.');" class="block-box-2 '.$class.' '.$classes.' trans pointer tin-light-font theme-color-active-hvr settings-20 bottom-divider">			
		<div class="left full padding-10">			
			<div class="input-title left h6 b1 dark-font-only">
				'.$name.'
			</div>
			<div class="input-container">
				<div class="input-description-2 h4 b1">
					<div id="settings-tab-val-'.$t.'" class="noflow">
						'.$pencil.'
						'.$val.'
					</div>
				</div>
			</div>
		</div>	
	</div>';
	
	$asd = '
	
	
	<div   class="'.$class.' clear '.$classes.' noselect h4 b1 pointer show-edit margin-10">
	                <div class=" tin-light-font noflow full">
		                <div class="left dark-font-only inline b2"></div>
		                <div class="noflow inline clear">
                            
			                <span></span>
			            </div>		
		            </div>
	            </div>';
}
?>