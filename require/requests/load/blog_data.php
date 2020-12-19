<?php
session_start();

require_once("../../../main/config.php");        // Import configuration
require_once('../../main/database.php');         // Import database connection
require_once('../../main/blogs.php');            // Import blog classes
require_once('../../main/settings.php');         // Import settings
require_once('../../../language.php');           // Import language

// User class
$profile = new main();
$profile->db = $db;
$profile->settings = $page_settings;

// Pass properties to fetch logged user if exists
$profile->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
$profile->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];

// Try fetching logged user
$user = $profile->getUser();

$main_body = $right_body = $add_title = $TEXT['temp-extend_in'] = '';

// Check login data
if(empty($user['idu'])){
	$user = false;
} else {
	$profile->followings = $profile->listFollowings($user['idu']);
}

// Explore category
if($_POST['ff'] == '1') {
	
	$cat = $profile->getCategoryById($_POST['p']);
	
	if($cat) {

		$add_title = '<script>document.title = \''.$TEXT['_uni-Explore'].' | '.$TEXT[$cat['cat_name']].'\';store(\'/blogs/category/'.$cat['cid'].'\');</script>';
	
	    $TEXT['temp-extend_in'] = $TEXT['_uni-in'].' '.$TEXT[$cat['cat_name']];
		
		echo $profile->getRecentBlogs($cat['cid']);
	
		echo $profile->getTrendingBlogs($cat['cid']);

		$TEXT['posts'] = '<script>blogAricles('.$cat['cid'].',0,25)</script>';
		
		$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);	

		$TEXT['content'] = $profile->getPopularPosts($cat['cid']);
		
		$TEXT['content'] .= $profile->getAllCategories();

		$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
	
	} else {
		echo '<script>loadBlogHome(1);</script>';
	}
	
// Blog articles
} elseif($_POST['ff'] == '2') {
	
	$cat = ($_POST['p']) ? $_POST['p'] : null;
	
	$start = ($_POST['f']) ? $_POST['f'] : 0;
 
    $TEXT['posts'] = $profile->getArticles($user,$cat,$start);
	
    echo ($start) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

// Article
} elseif($_POST['ff'] == '3') {
	
	$id = ($_POST['p']) ? $_POST['p'] : null;

    list($article,$article_page) = $profile->getArticle($user,$id);
 
    $add_title = '<script>$("meta[name=\'description\']").attr("content", \''.$db->real_escape_string($article['blog_description']).'\');document.title = \''.$db->real_escape_string($article['blog_heading']).' | '.$TEXT['web_name'].'\';</script>';
	
    $TEXT['content'] = $article_page;	
	
	$TEXT['temp-add_name'] = $TEXT['_uni-By'].' '.fixName(45,$article['username'],$article['first_name'],$article['last_name']);
	
    $TEXT['content'] .= $profile->getRecentBlogs($article['idu'],1);

    $TEXT['content'] .= $profile->getCommentBox($id,$user);	
	
    $TEXT['posts'] .= $profile->getComments($user,$id,0);	

	$main_body = display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

	$cat = $profile->getCategoryById($article['blog_type']);
	
	$TEXT['temp-extend_in'] = $TEXT['_uni-in'].' '.$TEXT[$cat['cat_name']];

	$TEXT['content'] = (substr($page_settings['bible_view'],8,1)) ? getBible() : '';
	
	$TEXT['content'] .= $profile->getPopularPosts($article['blog_type']);
	
	$TEXT['content'] .= $profile->getParTags($article['blog_tags']);
	
	$TEXT['content'] .= $profile->getAllCategories();
	
	$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);

// Delete comment
} elseif($_POST['ff'] == '4') {
	
	echo $profile->deleteComment($_POST['v1'],$user) ;
	
// Load comments
} elseif($_POST['ff'] == '5') {
	
	echo $profile->getComments($user,$_POST['v1'],$_POST['f']) ;

// Blog search	
} elseif($_POST['ff'] == '6') {
	
	// Get starting point
    $from = (isset($_POST['f']) && $_POST['f'] > 0) ? $_POST['f'] : 0 ;
	
	// Trim searched value
	$search = trim($_POST['v1']);
	
    $TEXT['posts'] = $profile->getSearch($user,$search,$from);	

	$main_body = ($from) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

	if(!$from) {
		$TEXT['content'] = $profile->getPopularPosts($article['blog_type']);
	
		$TEXT['content'] .= $profile->getParTags($profile->getPopularTags(),1);
	
		$TEXT['content'] .= $profile->getAllCategories();	
		
		$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
	}

// Articles page in profile
} elseif($_POST['ff'] == '7') {
	
    $TEXT['posts'] = $profile->getUserArticles($user);
	
	$main_body = ($from) ? $TEXT['posts'] : display('../../../themes/'.$TEXT['theme'].'/html/main/left_large'.$TEXT['templates_extension']);

	$TEXT['temp-title'] = $TEXT['_uni-Blog_create-1'] ;
	$TEXT['temp-info'] = $TEXT['_uni-Blog_create-1-ttl'] ;
	$TEXT['temp-widget_color'] = 'violet';
	$TEXT['temp-widget_class'] = 'mentions';
	$TEXT['temp-js'] = 'locate(\''.$TEXT['installation'].'/?new=blog\');';
	$TEXT['temp-href'] = $TEXT['installation'].'?new=blog';
    $TEXT['temp-btn'] = $TEXT['_uni-Create_a_blog'];

	// Generate widget from template 
	$TEXT['content'] = '<div class="margin-5 rounded noflow">'.display(templateSrc('/modals/widget')).'</div>';
	
	$TEXT['content'] .= $profile->getPopularPosts();
	
	$right_body = display('../../../themes/'.$TEXT['theme'].'/html/main/right_small'.$TEXT['templates_extension']);	
	
} elseif($_POST['ff'] == '8') {
	
    $from = (isset($_POST['p']) && $_POST['p'] > 0) ? $_POST['p'] : 0 ;
	
    echo $profile->getUserArticles($user,$from);

// Delete blog
} elseif($_POST['ff'] == '9') {
	
    $id = (isset($_POST['p']) && $_POST['p'] > 0) ? $_POST['p'] : 0 ;
	
    echo $profile->deleteBlog($user,$id);
	
}

// Display full page
echo $main_body.$right_body.$add_title;

if(isset($db) && $db) {
	mysqli_close($db);
}
?>