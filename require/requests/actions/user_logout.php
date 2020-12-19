<?php
session_start();

require_once('../../../main/config.php');        // Import configuration
require_once('../../main/database.php');         // Import classes
require_once('../../main/classes.php');          // Import classes

// New login class	
$out = new main();
$out->db = $db;

// If success redirect to homepage else reloading will automatically detect user login status
echo ($out->logOut() == 0) ? '<script>window.location.href = \''.$TEXT['installation'].'\' </script>' : '<script>location.reload(); </script>';

if(isset($db) && $db) {
	mysqli_close($db);
}
?>