<?php 
// Add settings wizards
function getWizard($user,$t,$s = NULL) {
    global $TEXT;
	
	// Return if tab not set
    if(!$t) {return false;}

	// Genreral settings || NAME WIZARD
	if($t == 1) {
	    
		// Add 2 fields (First name and last name)
	    $inputs = addSampleInput(1,$TEXT['_uni-First_name'],$user['first_name'],'');
	    $inputs .= addSampleInput(2,$TEXT['_uni-Last_name'],$user['last_name'],'');
		
		// Add wizard
		return addSampleWizard($t,$TEXT['_uni-TTL-Name'],$inputs,'top-divider');

    // Genreral settings || USERNAME WIZARD
	} elseif(in_array($t,array(2,3,5,6,7,8,12))) {
		
		// Wizard description
		$wizard_info = array(	
		2 => "_uni-TTL-Username",	
		3 => "_uni-TTL-Email",	
		5 => "_uni-TTL-Profession",	
		6 => "_uni-TTL-Education",	
		7 => "_uni-TTL-Hometown",	
		8 => "_uni-TTL-Living",	
		12 => "_uni-TTL-Website",	
		);
	
		// Wizard input tittle
		$input_tittle = array(	
		2 => "_uni-Username",	
		3 => "_uni-Email",	
		5 => "_uni-Profession",	
		6 => "_uni-Education",
		7 => "_uni-Hometown",	
		8 => "_uni-Living_in",	
		12 => "_uni-Website",	
		);
	
		// Wizard input val
		$input_val = array(	
		2 => $user['username'],	
		3 => $user['email'],	
		5 => $user['profession'],	
		6 => $user['study'],	
		7 => $user['from'],	
		8 => $user['living'],	
		12 => $user['website'],	
		);
		
		// Wizard input attribute
		$input_attr = array(	
		2 => '',	
		3 => '',	
		5 => '',	
		6 => '',	
		7 => '',	
		8 => '',	
		12 => '',	
		);
		
		$divde_attr = array(	
		2 => '',	
		3 => '',	
		5 => 'top-divider',	
		6 => '',	
		7 => '',	
		8 => '',	
		12 => '',	
		);
	
	    // Add wizard with one field
		return addSampleWizard($t,$TEXT[$wizard_info[$t]],addSampleInput(1,$TEXT[$input_tittle[$t]],$input_val[$t],$input_attr[$t]),$divde_attr[$t]);
		
	// Genreral settings || PASSWORD WIZARD 
	} elseif($t == 4) {
	
	    // Add 2 fields (Old,new and repeat)
	    $inputs = addSampleInput(1,$TEXT['_uni-Current'],'','type="password"');
	    $inputs .= addSampleInput(2,$TEXT['_uni-New'],'','type="password"');
	    $inputs .= addSampleInput(3,$TEXT['_uni-Repeat'],'','type="password"');
		
		// Add wizard
		return addSampleWizard($t,$TEXT['_uni-TTL-Password'],$inputs);
		
	// Profile information || Interests WIZARD
	} elseif(in_array($t,array(9,10,11,13))) {
	
    	// Wizard description
		$wizard_info = array(	
		9 => "_uni-TTL-Interest",	
		10 => "_uni-TTL-Relation",	
		11 => "_uni-TTL-Gender",	
		13 => "_uni-TTL-Birthday",
	
		);
	
		// Wizard input tittle
		$input_tittle = array(	
		9 => "_uni-Interested_in",	
		10 => "_uni-Relationship",	
		11 => "_uni-Gender",	
	
		);
		
		if($t == 9) {
		    return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['interest'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Male']),array(2,$TEXT['_uni-Female']))) ;
		} elseif($t == 10) {
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['relationship'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Single']),array(2,$TEXT['_uni-In_a_rel']))) ;
		} elseif($t == 11) {
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),addSampleInputSelect(1,$TEXT[$input_tittle[$t]],$user['gender'],array(0,$TEXT['_uni-Not_set']),array(1,$TEXT['_uni-Male']),array(2,$TEXT['_uni-Female']))) ;
		} else {
		    
			
			$seprate = explode('-', $user['b_day']);

			if(empty($seprate[1]) || empty($seprate[0]) || empty($seprate[2])) {
			    $seprate[0] = '';
			    $seprate[1] = '';
			    $seprate[2] = '';
			}
			
			// Add 3 fields (First name and last name)
	        $inputs = addSampleInput(1,$TEXT['_uni-Day'],$seprate[0],'');
	        $inputs .= addSampleInput(2,$TEXT['_uni-Month'],$seprate[1],'');
	        $inputs .= addSampleInput(3,$TEXT['_uni-Year'],$seprate[2],'');
			
			
			return addSampleWizard($t,sprintf($TEXT[$wizard_info[$t]],'loadSettings(3);'),$inputs) ;
		
		}
	
	// Profile information || BIO WIZARD 
	} elseif($t == 14) {
	    
		// custom input for bio
		$input = '<div class="block-box-2 trans padding-10">			
		<div class="left full">			
			<div class="input-title left h6 b1 dark-font-only">
				'.$TEXT['_uni-Bio'].'
			</div>
			<div class="input-container">
				<textarea id="settings-input-1" class="h5 b1 light-font-only">'.$user['bio'].'</textarea>
			</div>
		</div>	
	</div>';

		return addSampleWizard($t,$TEXT['_uni-TTL-Bio'],$input) ;	

		
	// Privacy settings || POSTS WIZARD 
	} elseif($t == 15) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_posts'],$TEXT['_uni-TTL-Posts-sml'],$TEXT['_uni-TTL-Posts-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-posts');
		
		$inputs .= addSampleselectNe(2,$user['p_mention'],$TEXT['_uni-TTL-Mentions-sml'],$TEXT['_uni-TTL-Mentions-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-mentions');

		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || PROFILE WIZARD 
	} elseif($t == 16) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_image'],$TEXT['_uni-TTL-image-sml'],$TEXT['_uni-TTL-image-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-image','');
		$inputs .= addSampleselectNe(2,$user['p_cover'],$TEXT['_uni-TTL-cover-sml'],$TEXT['_uni-TTL-cover-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-cover');
		$inputs .= addSampleselectNe(3,$user['p_followers'],$TEXT['_uni-TTL-followers-sml'],$TEXT['_uni-TTL-followers-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-followers');
		$inputs .= addSampleselectNe(4,$user['p_followings'],$TEXT['_uni-TTL-followings-sml'],$TEXT['_uni-TTL-followings-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-followings');

		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || CONTACT WIZARD 
	} elseif($t == 17) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_private'],$TEXT['_uni-TTL-profile-sml'],$TEXT['_uni-TTL-profile-desc'],$TEXT['_uni-Requires_approval'],$TEXT['_uni-Public'],'pri-profile','');
		$inputs .= addSampleselectNe(2,$user['p_web'],$TEXT['_uni-Website'],$TEXT['_uni-TTL-website-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-website');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || INFO WIZARD 
	} elseif($t == 18) {
	    
		// Add fields
		$inputs = addSampleselectNe(1,$user['p_profession'],$TEXT['_uni-TTL-profession-sml'],$TEXT['_uni-TTL-profession-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-profession','');
		$inputs .= addSampleselectNe(2,$user['p_study'],$TEXT['_uni-TTL-study-sml'],$TEXT['_uni-TTL-study-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-study');
		$inputs .= addSampleselectNe(3,$user['p_hometown'],$TEXT['_uni-TTL-hometown-sml'],$TEXT['_uni-TTL-hometown-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-hometown');
		$inputs .= addSampleselectNe(4,$user['p_location'],$TEXT['_uni-TTL-living-sml'],$TEXT['_uni-TTL-living-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-living');
		$inputs .= addSampleselectNe(5,$user['p_interest'],$TEXT['_uni-TTL-interest-sml'],$TEXT['_uni-TTL-interest-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-interest');
		$inputs .= addSampleselectNe(6,$user['p_relationship'],$TEXT['_uni-TTL-relationship-sml'],$TEXT['_uni-TTL-relationship-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-relationship');
		$inputs .= addSampleselectNe(7,$user['p_gender'],$TEXT['_uni-TTL-gender-sml'],$TEXT['_uni-TTL-gender-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-gender');
		$inputs .= addSampleselectNe(8,$user['p_bday'],$TEXT['_uni-TTL-birth-sml'],$TEXT['_uni-TTL-birth-desc'],$TEXT['_uni-Followers'],$TEXT['_uni-Public'],'pri-birth');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Privacy settings || MODERATORS WIZARD 
	} elseif($t == 19) {
	    
		return addSampleWizard($t,'',addSampleselectNe(1,$user['p_moderators'],$TEXT['_uni-TTL-moderators-sml'],$TEXT['_uni-TTL-moderators-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-moderators',''),1) ;	
	
	// Notifications settings || ON BREEZE WIZARD 
	} elseif($t == 20) {
	    
		$inputs = addSampleselectNe(1,$user['n_type'],$TEXT['_uni-TTL-not-type-sml'],$TEXT['_uni-TTL-not-type-desc'],$TEXT['_uni-Real_time'],$TEXT['_uni-Manual'],'not-type','');
		$inputs .= addSampleselectNThree(2,$user['n_per_page'],$TEXT['_uni-TTL-not-page-sml'],$TEXT['_uni-TTL-not-page-desc'],15,10,5,'not-page','');
		$inputs .= addSampleselectNe(3,$user['n_accept'],$TEXT['_uni-TTL-not-accet-sml'],$TEXT['_uni-TTL-not-accet-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-accet','');
		$inputs .= addSampleselectNe(4,$user['n_follower'],$TEXT['_uni-TTL-not-foll-sml'],$TEXT['_uni-TTL-not-foll-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-foll','');
		$inputs .= addSampleselectNe(5,$user['n_like'],$TEXT['_uni-TTL-not-like-sml'],$TEXT['_uni-TTL-not-like-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-like','');
		$inputs .= addSampleselectNe(6,$user['n_comment'],$TEXT['_uni-TTL-not-cmmt-sml'],$TEXT['_uni-TTL-not-cmmt-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-cmmt','');
		$inputs .= addSampleselectNe(7,$user['n_mention'],$TEXT['_uni-TTL-not-mention-sml'],$TEXT['_uni-TTL-not-mention-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-mentions','');
		
		
		return addSampleWizard($t,'',$inputs,1) ;	
	
	// Notifications settings || EMAIL WIZARD 
	} elseif($t == 21) {
	    
		$inputs = addSampleselectNe(1,$user['e_accept'],$TEXT['_uni-TTL-not-accet-sml'],$TEXT['_uni-TTL-e-accet-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-accet','');
		$inputs .= addSampleselectNe(2,$user['e_follower'],$TEXT['_uni-TTL-not-foll-sml'],$TEXT['_uni-TTL-e-foll-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-foll','');
		$inputs .= addSampleselectNe(3,$user['e_like'],$TEXT['_uni-TTL-not-like-sml'],$TEXT['_uni-TTL-e-like-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-like','');
		$inputs .= addSampleselectNe(4,$user['e_comment'],$TEXT['_uni-TTL-not-cmmt-sml'],$TEXT['_uni-TTL-e-cmmt-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'not-cmmt','');
		$inputs .= addSampleselectNe(5,$user['e_mention'],$TEXT['_uni-TTL-not-mention-sml'],$TEXT['_uni-TTL-e-mention-desc'],$TEXT['_uni-Yes'],$TEXT['_uni-No'],'pri-mentions','');
		
		return addSampleWizard($t,'',$inputs,1) ;	
	}
}

// Add select of 3 types
function addSampleselectNe($t,$val,$ttl,$ttl_big,$text_1,$text_0,$img,$classes='') {
    global $TEXT;
	return '<div class="clear full '.$classes.'">
					<div style="padding:10px 5px 0px 0px;max-width:30%;" class="right clear">
					    <select id="settings-input-'.$t.'" class="small h5 b1 tin-light-font-onl">
						    '.getSelect($val,$text_1,$text_0).'
				     	</select>
				    </div>
					<div class="left padding-10" style="max-width:70%;">
		                <div class="dark-font-only b2 h4">'.$ttl.'</div>
						<span class="tin-light-font-only b1 h4">'.$ttl_big.'</span>
		            </div>
				</div>';
}

// Add select
function addSampleselectNThree($t,$val,$ttl,$ttl_big,$text_3,$text_1,$text_0,$img,$classes='') {
    global $TEXT;
	return '<div class="clear full '.$classes.'">
					<div style="padding:10px 5px 0px 0px;max-width:30%;" class="right clear">
					    <select id="settings-input-'.$t.'" class="small h5 b1 tin-light-font-onl">
						     '.getSelVal($val,$text_0,$text_1,$text_3).'
				     	</select>
				    </div>
					<div class="left padding-10" style="max-width:70%;">
		                <div class="dark-font-only b2 h4">'.$ttl.'</div>
						<span class="tin-light-font-only b1 h4">'.$ttl_big.'</span>
		            </div>
				</div>';
}

// Add select input
function addSampleInputSelect($t,$ttl,$val,$text_0,$text_1,$text_2) {
    return '<div class="block-box-2 trans">			
			<div class="left full">			
			<div class="input-title left h6 b1 dark-font-only">
				'.$ttl.'
			</div>
			<div class="input-container">
				
				<div class="block left">
				
				<select id="settings-input-'.$t.'" class="small h5 b1 tin-light-font-only">
				    <option value="'.$text_0[0].'">'.$text_0[1].'</option>
			        <option value="'.$text_1[0].'">'.$text_1[1].'</option>
			        <option value="'.$text_2[0].'">'.$text_2[1].'</option>
				</select>
				
				</div>
				
				<script>$("#settings-input-'.$t.'").val('.$val.');</script>
			</div>
		</div>	
	</div>';
}

// Add text input
function addSampleInput($t,$ttl,$val,$attr,$desc='') {
    global $TEXT;
	return '<div class="block-box-2 trans">			
		<div class="left full">			
			<div class="input-title left h6 b1 dark-font-only">
				'.$ttl.'
			</div>
			<div class="input-container">
				<input id="settings-input-'.$t.'" '.$attr.' class="h5 b1 light-font-only" value="'.$val.'" >
				<div class="input-description h4 b1 tin-light-font-only">
					'.$desc.'
				</div>
			</div>
		</div>	
	</div>';	
}

// Add wizard
function addSampleWizard($t,$ttl,$inputs,$class = '') {
    global $TEXT;

	return '<div id="settings-content-'.$t.'" class="clear full background-x '.$class.' right-divider settings-content-class bottom-divider">
	    		<div class="full">
		    		<div class="full">
			    		
						<div class="left clear2 settings-20 full">			
							'.$inputs.'	 
						</div>			
						
						<div class="center clear top-divider padding-10 h4 b1 tin-light-font-only">
				    		<div class="padding-10 text-left">'.$ttl.'</d>
						</div>
						
						<div id="settings-content-mess-'.$t.'"></div>
							
						<div class="block clear2 margin-10">
							<span id="settings-content-space-'.$t.'"></span>
				    		<div id="settings-content-save-'.$t.'" onclick="saveTab('.$t.');" class="right btn btn-small btn-small-inline btn-dark" ><span>'.$TEXT['_uni-Save_changes'].'</span></div>
				    		<button id="settings-content-close-'.$t.'" onclick="closeAll();" class="right btn btn-small btn-small-inline btn-light">'.$TEXT['_uni-Cancel'].'</button>
						</div>			
            		</div>			
				</div>
			</div>';	
}
?>