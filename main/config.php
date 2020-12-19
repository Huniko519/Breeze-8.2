<?php
/*                               Breeze Ultimate social network Quick SETUP                            */
/*                                      Breeze developers                                       */

// Stop reporting warnings
error_reporting(0);

// Set eror handler
set_error_handler("handleAll");

// Global ARRAYs
$TEXT = $SET = array();
 
//---------------------------------------- START    -------------------------------------------//

// MySQL database SETUP
$SET['database_host'] = 'localhost';              // MySQL Database HOST 
$SET['database_user'] = 'root';              // MySQL Database USERNAME
$SET['database_pass'] = '';              // MySQL Database PASSWORD
$SET['database_name'] = 'test2';              // MySQL Database NAME

$SET['installation'] = 'http://' . $_SERVER['HTTP_HOST'];      // Installation URL

// Email which will be used to send emails for features like email verification 
$TEXT['web_mail'] = 'no-reply@localhost.com';

// Timezone
$TEXT['timezone'] = '+5:30';                    // e.g. +5:30 for India

// Purchase code
$TEXT['pcode'] = 'PB_CODE';                     // Envato item purchase code

//---------------------------------------- FINISHED -------------------------------------------//

// Add installation URL to global ARRAY $TEXT
$TEXT['installation'] = $SET['installation'];

// Error Handler
function handleAll($errno, $errstr, $errfile, $errline) {
 
    if(strpos($errfile, 'thumb.php') !== false) return true;
 
    $currentDateTime = time();
 
    /* HTML Version
        $error_is = '<span style="color:#f00">FB_THROW</span> :  <span style="color:#f00">'.$errfile.'</span> <span style="color:#006af5">('.$errline.' <span style="color:#f00">['.$errno.']</span>)</span>  <span style="color:#006af5">'.$errstr.'</span> '.date('D F Y').'<br>';
        $myfile = fopen(__DIR__."/logs/php.html", "a") or die("Unable to open file!");
	*/
 
    /* LOG Version */
    $error_is = 'FB_THROW :  '.$errfile.'('.$errline.' ['.$errno.'])  '.$errstr.' '.date('D F Y');
    $myfile = fopen(__DIR__."/logs/php.log", "a") or die("Unable to open file!");

    fwrite($myfile, $error_is ."\n");

    fclose($myfile);

}

// Some global indexes
$TEXT['content'] = $TEXT['content_redirect'] = $TEXT['posts'] = '' ;
$TEXT['content_main_page'] = '<script>addContent(1);</script>' ;

?>