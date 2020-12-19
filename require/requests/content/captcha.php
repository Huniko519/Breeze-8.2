<?php
session_start();

if(isset($_GET['capt'])) { // If CAPTCHA is requested 

    // CAPTCHA TEXT
    $_SESSION['CAPTCHA'] = mt_rand(1000, 999999);

	// Set height and width
	$width = 80;
	$height = 37;
	
    // Creating blank image
    $image = imagecreate($width, $height); 
	
	// Set captcha color
	if(isset($_GET['dark'])) {
		$col1 = 63;
		$col2 = 66;
		$col3 = 87;
	} else {
		$col1 = 6;
		$col2 = 176;
		$col3 = 255;
	}
	
	
    // setting colours
    $black = imagecolorallocate($image, $col1, $col2, $col3);   // Background
    $white = imagecolorallocate($image, 255, 255, 255); // Fonts

    // Creating strings on image
    imagestring($image, 9, 17, 10, $_SESSION['CAPTCHA'], $white);

    // Adding lines over text to make CAPTCHA test difficult
    imageline($image, 0, mt_rand(5, $height-2), $width, mt_rand(5, $height-3), $white);
    imageline($image, 0, mt_rand(5, $height-8), $width, mt_rand(5, $height-4), $white);

    echo imagejpeg($image, null, 100);
	
}
?>