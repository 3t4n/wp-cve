<?php
function generate_guestbook() {
require(ABSPATH . 'wp-config.php');

global $wpdb;

$template_dir = get_template_directory();
$template_name = get_template();
$template_name = strtolower($template_name);
$home_url = get_settings('home');
$perma = get_settings('permalink_structure');

if (file_exists($template_dir.'/single.php')) {
$single = file($template_dir.'/single.php');
} else {
$stop = 1;
}

if (file_exists($template_dir.'/comments.php')) {
$comments = file($template_dir.'/comments.php');
} else {
$stop = 1;
}
$msg = "";
if (!$stop) {
$chmd = chmod($template_dir, 0777);
if ($chmd) {
$msg .= "Theme folder chmoded to 777!<br />";
} else {
$msg .= "Theme folder was not chmoded to 777!<br />";
}
if (!file_exists($template_dir.'/guestbook.php')) {
$guestbook = @fopen($template_dir.'/guestbook.php',"w");

$ignoretime = "the_time";
$ignoredate = "the_date";

$top = "<?php
/*
Template Name: Guestbook
*/
?>
";
fputs($guestbook,$top);
foreach($single as $line) {	          
           $line = str_replace("comments_template();", "comments_template('/guestcomments.php');", $line);	
           if ((!strstr($line,$ignoretime)) || (!strstr($line,$ignoredate))) {
           fputs($guestbook,$line);
          }
   }
fclose($guestbook);
$msg .= "Guestbook Template Created!<br />";
} else {
$msg .= "Guestbook Template Already Exists!<br />";
}
//comments
if (!file_exists($template_dir.'/guestcomments.php')) {
$guestcomments = @fopen($template_dir.'/guestcomments.php',"w");
foreach($comments as $comline) { //change wording to fit guestbook

	         $comline = str_replace('foreach ($comments as $comment)', 'foreach (array_reverse($comments) as $comment)', $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a Reply\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a Comment\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a Response\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a reply\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a comment\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave a response\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your Reply\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your Comment\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your Response\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your reply\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your comment\b/', "Sign My Guestbook", $comline);
	         $comline = preg_replace('/\b(?<!\$)Leave your response\b/', "Sign My Guestbook", $comline);
           $comline = preg_replace('/\b(?<!\$)Responses\b/', "Guestbook Entries", $comline);
           $comline = preg_replace('/\b(?<!\$)Response\b/', "Guestbook Entry", $comline);
           $comline = preg_replace('/\b(?<!\$)Comments\b/', "Guestbook Entries", $comline);
           $comline = preg_replace('/\b(?<!\$)Comment\b/', "Guestbook Entry", $comline);
           $comline = preg_replace('/\b(?<!\$)Replies\b/', "Guestbook Entries", $comline);
           $comline = preg_replace('/\b(?<!\$)Reply\b/', "Guestbook Entry", $comline);
           preg_replace('/\b(?<!\$)original\b/', "replacement", $line);
           $comline = str_replace("to &#8220;<?php the_title(); ?>&#8221;", "", $comline);
           
           fputs($guestcomments,$comline);
   }
fclose($guestcomments);
$msg .= "Guestbook Comments Template Created!<br />";
} else {
$msg .= "Guestbook Comments Template Already Exists!<br />";
}
//Check if there's a guestbook:
$check = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_status='publish' AND post_name='guestbook'");

//If no guestbook is found, create a new guestbook page:
if (!$check) {
$content = "Please sign my Guestbook.  Thanks!<br /> <small><a href=\"http://www.alleba.com/blog/2006/09/21/wordpress-guestbook-generator-plugin/\">Made with Wordpress Guestbook Generator</a></small>";
$title = "Guestbook";
$status = "publish";
$type = "page";

$post_author = 1;
$post_date = current_time('mysql');
$post_date_gmt = current_time('mysql', 1);
$post_content = $content;
$post_title = $title;
$post_status = $status;
$post_type = $type;
$post_data = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'post_type');
$sql1 = wp_insert_post($post_data);
//Confirm that a guestbook was indeed created:
if ($sql1) {
$msg .= "Guestbook Page Created!<br />";
} else {
$msg .= "Guestbook Page NOT Created!<br />";
}

$maxid = $wpdb->get_var("SELECT MAX(ID) FROM $wpdb->posts");
$sqlpost2cat = "INSERT INTO $wpdb->post2cat (post_id, category_id) VALUES ('$maxid','1')";
$sql2= "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ('$maxid','_wp_page_template','guestbook.php')";
$sql3 = $wpdb->query($sql2);
if ($sql3) {
$msg .= "Guestbook Template Successfully Assigned!<br />";
if (!$perma) {
$msg .= "<a href=\"$home_url/?page_id=$maxid\">View Your New Guestbook!</a>";
} else {
$msg .= "<a href=\"$home_url/guestbook/\">View Your New Guestbook!</a>";
}
} else {
$msg .= "Guestbook Template NOT Assigned!";
}
} else {
$msg .= "Guestbook Page Already Exists!<br />";
if (!$perma) {
$msg .= "<a href=\"$home_url/?page_id=$check\">View Your Guestbook!</a>";
} else {
$msg .= "<a href=\"$home_url/guestbook/\">View Your Guestbook!</a>";
}
}
} else {
$msg = "The required template files were not found in your current theme.  Guestbook cannot be generated.";
}
return $msg;
}
?>