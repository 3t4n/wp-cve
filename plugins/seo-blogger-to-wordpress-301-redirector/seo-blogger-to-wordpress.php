<?php
/*
Plugin Name: SEO Blogger to Wordpress using 301 Redirection
Plugin URI: http://suhastech.com/seo-blogger-to-wordpress
Description: This plugin will 301 redirect all incoming traffic from your Blogger account to your newly setup Wordpress account. Please read the documentation at suhastech.com/seo-blogger-to-wordpress before you continue.
Version: 0.4.8
Author: Suhas Sharma
Author URI: http://suhastech.com
*/

/*  Copyright 2010 Suhas Sharma <sharma@suhastech.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Main action handler
 *
 * @package SEO Blogger to Wordpress
 * @since 0.1
 *
 * redirects all incoming traffic from your Blogger account to your newly setup Wordpress account.
 */
 
 if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

function suhas_generator() {
    add_management_page('SEO Blogger to Wordpress', 'SEO Blogger to Wordpress', 'manage_options',
            __FILE__, 'admin_page');
    }
	
	function admin_page() {
	$purl = plugin_dir_url(__FILE__);
	?>
	
	<div class="wrap">
	<div id="icon-plugins" class="icon32"></div><h2>SEO Blogger to Wordpress</h2><br/>
 <h3>Please read the <a target="_blank" href="http://suhastech.com/seo-blogger-to-wordpress">documentation</a> before you continue.</h3>

    <div class="metabox-holder" style="width:65%; float:left; margin-right:10px;">
 
        <div class="postbox">
            <h3 style="cursor:default;"><img style="vertical-align:middle" src="<?php echo $purl."images/template.png";?>" /> Blogger Classic Template</h3>
            <div class="inside" style="padding:0px 6px 0px 6px;">
               	
	<p>This is for yourblog.blogspot.com domain users only. Goto Blogger Dashboard --> Design --> Layout --> Edit HTML. Switch to Classic Template. Copy the generated template to the text area. Save.</p>
	<form method="post" action="">
	Your current Wordpress URL: <input type="text" name="url" value="<?php echo home_url('/'); ?>" size="55"/>
	<input type="hidden" name="blogspot" value="1" />
	<input type="submit" value="Generate" name="Generate" class="button-primary"/>
	</form>
            </div>
 
        </div>
 
      <div class="postbox">
            <h3 style="cursor:default;"><img style="vertical-align:middle" src="<?php echo $purl."images/delete.png";?>" /> Delete all Blogger Imported posts</h3>
            <div class="inside" style="padding:0px 6px 0px 6px;">
	<p>If the Blogger Importer didn't work that well, You can delete all Blogger Imported posts. So, you can start over.</p>
	
	<form method="post" action="">
	<input type="hidden" name="delete" value="1" />
	<input type="submit" value="Delete" name="Delete" class="button-primary"/>
	</form>
            </div>
 
        </div> 
		
		<div class="postbox">
            <h3 style="cursor:default;"><img style="vertical-align:middle" src="<?php echo $purl."images/download.png";?>" /> Downloading Images</h3>
            <div class="inside" style="padding:0px 6px 0px 6px;">
	<p>Click on 'Download' to download all the images from the Picasa server to your server. They all will be saved into the Media Library. By doing this, automatic thumbnails advertised by themes will now work.</p>
	<p>Please note that, this will take a considerable amount of time. You can close this anytime you want, the next time you press 'Download", it'll start from the same place it stopped. </p>
	<p>"Download Successful" marks the end of the process.</p>
	<p>&lt;a href="http://example.com/interlinkedimage.jpg"&gt;&lt;img src="actualimage.jpg" /&gt;&lt;/a&gt;</p>
	<form method="post" action="">
	<input type="hidden" name="magic" value="1" />
	Download Interlinked Images? <input type="checkbox" name="interlinked" value="1" />
	<input type="submit" value="Download" name="Download" class="button-primary"/>
	</form>
            </div>
 
        </div>
    </div>
 
    <div class="metabox-holder" style="width:30%; float:left;">
 
        <div class="postbox">
            <h3 style="cursor:default;"><img style="vertical-align:middle" src="<?php echo $purl."images/help.png";?>" /> Need Help?</h3>
            <div class="inside" style="padding:0px 6px 0px 6px;">
			 <p />
			 <ol>
			 <li>Add a comment on <a href="http://suhastech.com/seo-blogger-to-wordpress" target="_blank">this post.</a></li>
			 <li>We provide professional Blogger to Wordpress Migration service. You might want to <a href="http://suhastech.com/services/blogger-to-wordpress-migration/" target="_blank">look at that.</a></li>
			 </ol>
               
            </div>
        </div>
		
		 <div class="postbox">
            <h3 style="cursor:default;"><img style="vertical-align:middle" src="<?php echo $purl."images/like.png";?>" /> Like this plugin?</h3>
            <div class="inside" style="padding:0px 6px 0px 6px;">
			<p> If you like this plugin, you can</p>
			 <ol>
			 <li>Give a 5 star rating at <a href="http://wordpress.org/extend/plugins/seo-blogger-to-wordpress-301-redirector/" target="_blank">wordpress.org</a></li>
			 <li><a href="?page=<?php echo $_GET['page']; ?>&do=blog" target="_blank">Blog about</a> your move to Wordpress.</li>
			 <li><a href="http://suhastech.com/donate" target="_blank">Donate</a> as a token of appreciation. I took a lot of time to build this plugin.</li>
			 <li><a href="http://twitter.com/intent/tweet?source=webclient&text=I%27m+on+Wordpress%2C+now%21+I%27m+using+a+plugin+called+%27SEO+Blogger+to+Wordpress%27+by+%40suhastech.+Check+it+out%21+http%3A%2F%2Ft.co%2FmDNzYdKO" target="_blank">Tweet</a> your love.</li>
			 <li><a href="http://twitter.com/suhastech" target="_blank">Follow me</a> on Twitter or Visit <a href="http://suhastech.com" target="_blank">my Blog</a></li>
			 </ol>
               
            </div>
        </div>
 
        
    </div>
</div>
  
<!-- required to clear for additional content -->
<div style="clear:both;"></div>

	<?php
		if (isset($_GET['do']))
		{
		
		function wp_exist_post($id) {
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM wp_posts WHERE id = '" . $id . "'", 'ARRAY_A');
		}
if (get_option('suhastech_seo_blogger_to_wordpress_blog') === false)
{	
$my_post = array(
     'post_title' => 'We are on Wordpress!',
     'post_content' => 'Yay! We are on Wordpress, now. <br/> <br/> <br/> We are using a plugin called <a href="http://suhastech.com/seo-blogger-to-wordpress">SEO Blogger to Wordpress</a> to seamlessly redirect all our previously acquired traffic to our spanking new wordpress blog.',
     'post_status' => 'draft',
     'post_author' => 1
  );

$postID =  wp_insert_post( $my_post );
add_option('suhastech_seo_blogger_to_wordpress_blog', $postID);
}
else
{
$postID =get_option('suhastech_seo_blogger_to_wordpress_blog') ;
if (wp_exist_post($postID) == "")
{
$my_post = array(
     'post_title' => 'We are on Wordpress!',
     'post_content' => 'Yay! We are on Wordpress, now. <br/> <br/> <br/> We are using a plugin called <a href="http://suhastech.com/seo-blogger-to-wordpress">SEO Blogger to Wordpress</a> to seamlessly redirect all our previously acquired traffic to our spanking new wordpress blog.',
     'post_status' => 'draft',
     'post_author' => 1
  );

$postID2 =  wp_insert_post( $my_post );
update_option('suhastech_seo_blogger_to_wordpress_blog', $postID2);
}

}
  echo '<meta http-equiv="refresh" content="0; url='.home_url('/wp-admin/post.php?post='.$postID.'&action=edit').'">'; 
  }
	if (isset($_POST['magic']))
	{
	set_time_limit(0);
	global $post;
	include('lib.php');
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$count = 0;
$wp_query = new WP_Query( 'posts_per_page=-1');
if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
if (!(get_post_meta($post->ID, "blogger_images",true) == "1"))
{
$new_content = get_the_content();

if(!function_exists('media_sideload_image')) {
    displayError("Error: Wierd, You are on wordpress, right? I don't have access to a function called media_sideload_image which is vital to download images.");
} else {
$html = str_get_html($new_content);
foreach($html->find('img') as $element) {
   $original = $element->src;
    $replace = explode("?", $original);
    $final = explode("/", $replace[0]);
    
	if ((preg_match("/photobucket.com/i", $replace[0])) || (preg_match("/blogspot.com/i", $replace[0])) || (preg_match("/ggpht.com/i", $replace[0])) || (preg_match("/googleusercontent.com/i", $replace[0])))
	{
		$upload = media_sideload_image(urldecode(str_replace("+","%2B",trim($replace[0]))), $post->ID);
		

	if ( !is_wp_error($upload) ) {

		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $upload, $locals);

		foreach ( $locals[1] as $newurl ) :
    $new_content = str_replace($original,  $newurl ,$new_content);
		endforeach;
		
   $count++;	
	}else
{
echo "<b>Error</b> with ".$replace[0] ;
   echo " ".$upload->get_error_message()."<br />";
}
	

	}
   
                                       }
	// Interlinked Images Import
	if(isset($_POST['interlinked']))
	{
	if($_POST['interlinked'] == "1")		
{	
foreach($html->find('a') as $element) {
   $original = $element->href;
    $replace = explode("?", $original);
    $final = explode("/", $replace[0]);
	$filename = trim(str_replace('%', '', end($final)));
    $ext = substr(trim($replace[0]), -4);
	if ($ext == ".JPG" || $ext == ".jpg" || $ext == ".PNG" || $ext == ".png" || $ext == ".GIF" || $ext == ".gif" || $ext == ".jpeg")
	     {
	if ((preg_match("/photobucket.com/i", $replace[0])) || (preg_match("/blogspot.com/i", $replace[0])) || (preg_match("/ggpht.com/i", $replace[0])) || (preg_match("/googleusercontent.com/i", $replace[0])))
	{
		$upload = media_sideload_image(urldecode(str_replace("+","%2B",trim($replace[0]))), $post->ID);
		
	if ( !is_wp_error($upload) ) {


		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $upload, $locals);

		foreach ( $locals[1] as $newurl ) :
    $new_content = str_replace($original,  $newurl ,$new_content);
		endforeach;
			
   $count++;
	}else
{
echo "<b>Error</b> with ".$replace[0] ;
   echo " ".$upload->get_error_message()."<br />";
}
   


	}
         }
                                       }
}
}
// End Interlinked									   

									   
									   
									   
									   
}

echo "Images downloaded from '".$post->post_title."'<br />";

$my_post = array();
  $my_post['ID'] = $post->ID;
   if ($new_content[0] == ">")
  {      
      $my_post['post_content'] = substr($new_content, 1);
  } else  
  {
  $my_post['post_content'] = $new_content;
  }
  if ($post->post_title[0] == ">" )
  {
  $my_post['post_title'] = substr($post->post_title, 1);
  } else
  {
  $my_post['post_title'] = $post->post_title;  
  }

  wp_update_post( $my_post );
	add_post_meta($post->ID, 'blogger_images', '1', true);
	echo "Post Updated ".$post->ID.". ".$count." images imported. <br />";
	}


endwhile;
endif;

echo "<p><b>Download Successful</b></p><p>This plugin adds some post meta data to identify downloaded images. Click on 'Clear Temporary Settings' to delete them.</p>".'<form method="POST" action=""><input type="hidden" name="clear" value="1" /><input type="submit" value="Clear Temporary Settings" class="button-primary"/></form>';
	}
	if (isset($_POST['clear']))
	{
	global $post;
	$allposts = get_posts('numberposts=-1&post_type=post&post_status=any');

  foreach( $allposts as $postinfo) {
    delete_post_meta($postinfo->ID, 'blogger_images');
  }

echo "<b>Temporary Information Deleted</b>";
	}
	if (isset($_POST['delete']))
	{
	set_time_limit(0);
	
	global $post;
	
query_posts('meta_key=blogger_blog&meta_value=&posts_per_page=-1');  
if (have_posts()) : 
while (have_posts()) : the_post(); 

wp_delete_post( $post->ID);
echo "Deleted ".$post->ID."<br />";
endwhile;
endif;
echo "<b>All blogger posts, related to this URL deleted. Did this by mistake? Don't worry, you can restore these posts in the trash.</b>";

	}
	if (isset($_POST['blogspot']))
	{
	if (isset($_POST['url']))
	{
	$site = $_POST['url'];
	$ult1 = 'http://'.$site.'/';

$ult2 = str_replace('http://http://', 'http://', $ult1);
$ult3  = str_replace('//', '/', $ult2);
$ult4 = str_replace('http:/', 'http://', $ult3);
if (!(preg_match("/blogspot.com/i", $ult )) && !(preg_match("/www/i", $ult ))   )
{
$ult5 = str_replace('http://', 'http://www.', $ult4);
$ult = str_replace('http://www.www.', 'http://www.', $ult5);

}
else {
$ult = $ult4;
}
if ((preg_match("/blogspot.com/i", $ult ))   )
{

$ult = str_replace('http://www.', 'http://', $ult4);
}

echo '<textarea cols="80" rows="20"><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="<$BlogLanguageDirection$>"><head> <title>  <$BlogPageTitle$> </title> <script type="text/javascript"> <MainorArchivePage>window.location.href="'.$ult.'"</MainOrArchivePage>  <Blogger><ItemPage>window.location.href="'.$ult.'?blogger=<$BlogItemPermalinkURL$>"</ItemPage></Blogger> </script> <MainPage><link rel="canonical" href="'.$ult.'" /></MainPage>  <Blogger><ItemPage><link rel="canonical" href="'.$ult.'?blogger=<$BlogItemPermalinkURL$>" /></ItemPage></Blogger></head><body> <div style="border: #ccc 1px solid; background: #eee; padding: 20px; margin: 80px;">  <p>This page has moved to a new address.</p>   <h1>     <MainOrArchivePage><a href="'.$ult.'"><$BlogTitle$></a></MainOrArchivePage>     <Blogger>       <ItemPage><a href="'.$ult.'?blogger=<$BlogItemPermalinkURL$>"><$BlogItemTitle$></a></ItemPage>     </Blogger>    </h1>  </div>  </body>  </html></textarea>';

} else { echo "No URL specified";}
	
	}
	echo " ";
	
	}
 
function suhas_blogger() {
global $wpdb;
	if ( !is_404() )
		return;
	$subdirectory = explode ("/", get_home_url());
        $req_uri = str_replace("/".end($subdirectory), "", $_SERVER['REQUEST_URI']);	
        $url = explode("?", $req_uri);	
        $result = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'blogger_permalink' AND meta_value = '$url[0]'");


$res = array();

foreach($result as $value_id){
   $rel_post_id = $value_id->post_id;
   $rel_post = get_posts('p='.$rel_post_id) ;
   if($rel_post) {

      array_push($res, $value_id );
      break;
   }
}
if(isset($res[0]->post_id))	
{
wp_redirect(get_permalink($res[0]->post_id), 301 );
}
else
{
return;
}
	exit();
}

function suhas_blogspot() {
global $wpdb;
 $old_url = $_GET['blogger'];

 if ($old_url != "") {
 
   $permalink = preg_replace("/http:\\/\\/[a-z0-9-.]+/", "", $old_url);
   $url = explode("?", $permalink);
   $result = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'blogger_permalink' AND meta_value = '$url[0]'");

$res = array();

foreach($result as $value_id){
   $rel_post_id = $value_id->post_id;
   $rel_post = get_posts('p='.$rel_post_id) ;
   if($rel_post) {

      array_push($res, $value_id );
      break;
   }
}

if(isset($res[0]->post_id))	
{
wp_redirect(get_permalink($res[0]->post_id), 301 );
}
else
{
return;
}

 }
exit();
}

add_action('admin_menu', 'suhas_generator');
add_action( 'template_redirect', 'suhas_blogger' );
if ( isset( $_GET['blogger'] ) )
add_action( 'template_redirect', 'suhas_blogspot' );
?>
