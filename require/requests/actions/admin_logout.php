<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/admin.php');           // Import all classes

// New administration settings	
$out = new AdminLogin();
 
// Logout
echo ($out->logOut() == 0) ? '<script>window.location.href = \''.$TEXT['installation'].'\' </script>' : '<script>location.reload();</script>'; 

?>