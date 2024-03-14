<?php
/*
Plugin Name: Blogger 301 Redirect
Plugin URI: http://techxt.com/blogger-301-redirect-plugin-for-wordpress/
Description: Redirect from blogger to wordpress. Helps you keep your blog traffic and Pagerank after migration from Blogger to WordPress.
Author URI: http://techxt.com
Author: Sudipto Pratap Mahato
Version: 2.5.3
*/

function redirect_to()
{
global $wpdb;
$stat=get_option('blog301stat');
if($stat==""){$stat="0:0:0:0:0:0";update_option('blog301stat', $stat);}
$stat1=explode(":",$stat);
$old_url = "";
$requri = str_replace("?br=","",strstr($_SERVER["REQUEST_URI"],"?br="));
if($requri!=false)
{
	$old_url = $requri;
}
if ($old_url != "")
	{
		$old_url=preg_replace('%//%', '', $old_url, 1);
		$old_url=str_replace("//","/",$old_url);
		$pos = strpos($old_url, "/");	
		if ($pos !== false) 
		{
			$old_url="blogspot.com".str_replace( substr($old_url,0,$pos),"",$old_url);
		}	
		$permalink = explode("blogspot.com", $old_url);
		if(strpos($permalink[1],"?")!==false && get_option('rev_p')=="checked")
		{
			$pl = explode("?", $permalink[1]);
			$permalink[1]=$pl[0];
		}
		$new_url="";
		if(get_option('pfeed_re')=="checked" && $new_url == "")
		{
			$si = explode("/", $permalink[1]);
			if($si[1]=="feeds" && $si[2]=="posts" && substr($si[3],0,7)=="default")
			{
				$new_url=site_url()."/feed";
				$stat1[2]=$stat1[2]+1;
			}
				
		}
		if(get_option('cfeed_re')=="checked" && $new_url == "")
		{
			$si = explode("/", $permalink[1]);
			if($si[1]=="feeds" && $si[3]=="comments" && substr($si[4],0,7)=="default")
			{
				$new_url=site_url()."/feed";
				$stat1[3]=$stat1[3]+1;
			}
		}
		if(get_option('archi_re')=="checked" && $new_url == "")
		{
			$si = explode("/", $permalink[1]);
			$si2 = explode("_", $si[1]);
			if($si2[3]=="archive.html")
			{
				$new_url=site_url()."/".$si2[0]."/".$si2[1];
				$stat1[4]=$stat1[4]+1;
			}
		}
		if($new_url == "")
		{
			$si = explode("/", $permalink[1]);
			if($si[1]=="p")
			{
				$new_url = $si[2];
			}
		}
		if($new_url == "")
		{
			$q = "SELECT ID FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ".
			"ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE ".
			"$wpdb->postmeta.meta_key='blogger_permalink' AND ".
			"$wpdb->postmeta.meta_value='$permalink[1]' LIMIT 1";
			if($wpdb->get_var($q))
			{
				$new_url =  get_permalink($wpdb->get_var($q));
				$stat1[0]=$stat1[0]+1;
			}
		}
		if(get_option('adv_ser')=="checked" && $new_url == "")
		{
			$vrs = array("-", "_", " ", ".html", ".htm","%%");
			$si = explode("/", $permalink[1]);
			$si[3] = '%'.str_replace($vrs, "%", $si[3]).'%';
			$q="SELECT ID FROM $wpdb->posts WHERE year(`post_date`) = $si[1] and month(`post_date`) = $si[2] and `post_name` like '$si[3]' and `post_type` = 'post' and `post_status` = 'publish' LIMIT 1";
			if($wpdb->get_var($q))
			{
				$new_url =  get_permalink($wpdb->get_var($q));
				$stat1[0]=$stat1[0]+1;
			}
		}
	/*	if($new_url != "")
		{
			$new_url=str_replace("//","",$new_url);
			$pos = strpos($new_url, "/");	
			if ($pos !== false) 
			{
				$new_url=site_url().str_replace( substr($new_url,0,$pos),"",$new_url);
			}	
		}*/
		if(get_option('home_re')=="checked" && $new_url == "")
		{
			$new_url = site_url().$permalink[1];
			$stat1[5]=$stat1[5]+1;
		}
		if($new_url == "")
		{
			$new_url = site_url();
			$stat1[1]=$stat1[1]+1;
		}
		$stat=implode(":",$stat1);
		update_option('blog301stat', $stat);
		wp_redirect( $new_url, 301 ); 
		exit;
	}
}

function br_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=bloggerredirect">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'br_plugin_settings_link' );

add_action( 'init', 'redirect_to' );

function br_option()
{
$clrstat=(isset($_GET['clearstat']))?$_GET['clearstat']:"";
$stat=get_option('blog301stat');
if($stat=="" || $clrstat=="true"){$stat="0:0:0:0:0:0";update_option('blog301stat', $stat);}
$stat1=explode(":",$stat);
	?>
	<div class="wrap">
	<h2>Blogger 301 Redirect - Settings</h2>
	Like this Plugin then why not hit the like button<br />
	<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Ftechxt&layout=standard&show_faces=false&width=450&action=like&font=verdana&colorscheme=light&height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe><br />And if you are too generous then you can always <b>DONATE</b> by clicking the donation button.<br/>If you like the plugin then <b>write a review</b> of it pointing out the plus and minus points.
	<p><b>Necessary steps before post migration from Blogger</b></p>
	1. Set the Timezone (Setting > General > Timezone)<br/>2. Change the Permalinks Structure to Custom [ <b>/%year%/%monthnum%/%postname%.html</b> ]<br/>&nbsp;&nbsp;&nbsp;(without brackets [ ] from Settings > Permalinks > Custom Structure)<br/>&nbsp;&nbsp;&nbsp;<i>(Note: Permalink change is recommended but not required. You can keep your own custom permalink structure)</i><br/>3. Import Posts using <a href="/wp-admin/admin.php?import=blogger">Blogger Import Plugin</a><br/>&nbsp;&nbsp;&nbsp;(Tools > Import > Blogger) 
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
<table class="form-ta">	
<tr valign="top">
	<td width="78%">
<table class="form-table">
        <tr valign="top">
	<td><b>Options</b></td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="adv_ser" value="checked" <?php echo get_option('adv_ser'); ?> />Activate Advance search<br />Rarely used but sometimes very useful if you migrated without using Blogger import plugin. <br/>Only works if post publish dates and post titles are same on both blogs.</td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="home_re" value="checked" <?php echo get_option('home_re'); ?> />Do not redirect to homepage if page not found (Display 404 error page)<br /> Check this if you have installed <a href="http://wordpress.org/extend/plugins/redirection/">Redirection Plugin</a> or similar plugin to handle page not found errors</td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="pfeed_re" value="checked" <?php echo get_option('pfeed_re'); ?> />Redirect Blogger Post feeds to Wordpress feeds (Recommended)<br /> Check this if you want to redirect <b>http://example.blogspot.com/feeds/posts/default</b> => <b>http://example.com/feed</b></td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="cfeed_re" value="checked" <?php echo get_option('cfeed_re'); ?> />Redirect Blogger Comment feeds to Wordpress feeds (Recommended)<br /> Check this if you want to redirect <b>http://example.blogspot.com/feeds/XXXXXXXXX/comments/default</b> => <b>http://example.com/feed</b></td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="archi_re" value="checked" <?php echo get_option('archi_re'); ?> />Redirect Blogger archives to Wordpress (Recommended)<br /> Check this if you want to redirect <b>http://example.blogspot.com/yyyy_mm_dd_archive.html</b> => <b>http://example.com/yyyy/mm/</b></td>
	</tr>
	<tr valign="top">
	<td><input type="checkbox" name="rev_p" value="checked" <?php echo get_option('rev_p'); ?> />Remove parameters from URL <br /> Check this if you want to redirect <b>http://example.blogspot.com/xyz.html?m=1</b> => <b>http://example.com/xyz.html</b></td>
	</tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="adv_ser,home_re,pfeed_re,cfeed_re,archi_re,rev_p" />
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
	
<div style="background: #6699FF; padding-left: 20px;">
	<h2> Method 1: Using New Blogger Template </h2> 
	<textarea onclick="this.select()" cols="80" rows="16" readonly="true" style="width:95%">&lt;?xml version="1.0" encoding="UTF-8" ?&gt;
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html expr:dir='data:blog.languageDirection' lang='en' xml:lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:b='http://www.google.com/2005/gml/b' xmlns:data='http://www.google.com/2005/gml/data' xmlns:expr='http://www.google.com/2005/gml/expr' xmlns:fb='http://ogp.me/ns/fb#' xmlns:og='http://ogp.me/ns#'>
<head profile='http://a9.com/-/spec/opensearch/1.1/'>
<title><data:blog.title/></title>
<b:if cond='data:blog.pageType == &quot;index&quot;'>
<link rel='canonical' href='<?php echo site_url()."/"; ?>' />
<meta content='0;url=<?php echo site_url()."/"; ?>' http-equiv='refresh'/>
<b:else/>
<link rel='canonical' expr:href='&quot;<?php echo site_url()."/"; ?>?br=&quot; + data:blog.url' />
<meta expr:content='&quot;0;url=<?php echo site_url()."/"; ?>?br=&quot; + data:blog.url' http-equiv='refresh'/>		
</b:if>
<script type='text/javascript'>
var wpblog = &quot;<?php echo site_url()."/"; ?>?br=&quot;;
wpblog = wpblog  + window.location.href.replace('http:','');
window.location.replace(wpblog);
</script>
<b:skin><![CDATA[/*
-----------------------------------------------
Blogger Template Style
Name:     Blogger 301 redirect
Designer: Sudipto Pratap Mahato
URL:     http://techxt.com
Started:     07:41 1/1/2013
----------------------------------------------- */
/*

]]></b:skin>
</head>
<body>
<b:section id='header' showaddelement='no'>
<b:widget id='Header1' locked='true' title='techxt (Header)' type='Header'/>
</b:section>

<div>
<p>This page has found a new home </p>
  <a href='<?php echo site_url()."/"; ?>'><data:blog.title/></a>
  <a expr:href='&quot;<?php echo site_url()."/?"; ?>br=&quot; + data:blog.url' />
</div>
<a href='http://techxt.com/?'>Blogger 301 Redirect Plugin</a>
</body>
</html></textarea>
<p><b>Where to copy the above Template</b></p>
	<ol>
	<li>Login to your Blogger Dashboard</li>
	<li>Goto Template &gt; Backup/Restore button (Top right) <b>Download Full Template</b> (To take 
	a backup of your template)</li>
	<li>After taking backup Click  <b>Edit HTML </b>(click Proceed if asked)</li>
	<li>Replace the code of your blogger template with the above code</li>
	<li>Finally click <b>Save Template changes </b> (Click Delete Widgets if asked) &gt; Click Close</li>
	<li>Now click the <b>Gear Icon</b> below the Mobile template</li>
	<li>Select <b>No. Show desktop template on mobile devices.</b> &gt; Click Save </li>
	</ol>
</div>
	<div style="background: none repeat scroll 0px 0px rgb(0, 240, 240); padding-left: 20px;">
	<h2> Method 2: Using Classic Template </h2> 
	The Template that you are required to copy to your Blogger blog<br />
	<textarea onclick="this.select()" cols="80" rows="16" readonly="true" style="width:95%">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="<$BlogLanguageDirection$>">
<head><title><$BlogPageTitle$></title>
<script type="text/javascript">
var wpblog = &quot;<?php echo site_url()."/"; ?>?br=&quot;;
wpblog = wpblog + window.location.href.replace('http:','');
<MainorArchivePage>window.location.href="<?php echo site_url()."/"; ?>"</MainOrArchivePage>
<Blogger><ItemPage>window.location.href=wpblog</ItemPage></Blogger>
</script>
<MainPage><link rel="canonical" href="<?php echo site_url()."/"; ?>" /></MainPage>
<Blogger><ItemPage><link rel="canonical" href="<?php echo site_url()."/"; ?>?br=<$BlogItemPermalinkURL$>" /></ItemPage></Blogger>
<MainorArchivePage><meta content='0;url=<?php echo site_url()."/"; ?>' http-equiv='refresh'/></MainOrArchivePage>
<Blogger><ItemPage><meta content='0;url=<?php echo site_url()."/"; ?>?br=<$BlogItemPermalinkURL$>' http-equiv='refresh'/></ItemPage></Blogger>
</head>
<body>
<div>
<p>This page has found a new home </p>
<h1><MainOrArchivePage><a href="<?php echo site_url()."/"; ?>"><$BlogTitle$></a></MainOrArchivePage>
<Blogger><ItemPage><a href="<?php echo site_url()."/"; ?>?br=<$BlogItemPermalinkURL$>"><$BlogItemTitle$></a></ItemPage></Blogger></h1>
</div>
<a href='http://techxt.com/?'>Blogger 301 Redirect Plugin</a>
</body></html>
</textarea>
	
	<p><b>Where to copy the above Template</b></p>
	<ol>
	<li>Login to your Blogger Dashboard</li>
	<li>Goto Template &gt; Backup/Restore button (Top right) <b>Download Full Template</b> (To take 
	a backup of your template)</li>
	<li>Then Scroll down to the buttom of the page click <b>Revert to classic templates </b>(link at the bottom of the 
	page) and select OK to revert to classic template</li>
	<li>Replace the code of your blogger template with the above code</li>
	<li>Finally click <b>Save Template changes </b></li>
	</ol>
</div>
	<p><b>Test your settings</b></p>
	<?php
		getbloggerblogs();
		
	
	?>
	<p><b>Need more help checkout the below page and post a comment</b></p>
	<a href="http://techxt.com/blogger-301-redirect-plugin-for-wordpress/">http://techxt.com/blogger-301-redirect-plugin-for-wordpress/</a>
	</div><table border="0" width="400">
	<tr><td width="255"><h3>Redirection Statistics</h3></td><td>&nbsp;</td>
	</tr><tr><td width="255"><b>Redirection</b></td><td><b>Hits</b></td>
	</tr><tr><td width="255">Post Redirections</td><td><?php echo $stat1[0]; ?></td>
	</tr><tr><td width="255">Redirection to Homepage</td><td><?php echo $stat1[1];?></td>
	</tr><tr><td width="255">Blogger Post feeds Redirections</td><td><?php echo $stat1[2];?></td>
	</tr><tr><td width="255">Blogger Comment feeds Redirection</td><td><?php echo $stat1[3];?></td>
	</tr><tr><td width="255">Blogger Archive page Redirections</td><td><?php echo $stat1[4];?></td>
	</tr><tr><td width="255">404 Errors</td><td><?php echo $stat1[5];?></td></tr>
	<tr><td width="255"><p/><b>Total Hits </b></td><td><b><?php echo $stat1[0]+$stat1[1]+$stat1[2]+$stat1[3]+$stat1[4]+$stat1[5];?></b></td>
	</tr><tr><td width="255"><a href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=bloggerredirect&clearstat=true" onclick = "if (! confirm('Do you really want to clear all Stats?')) return false;">Clear All Statistics</a></td><td></td></tr></table>
</td><td width="2%">&nbsp;</td><td width="20%"><a href="http://techxt.com/blogger301_ad" target="_blank"><img src="http://techxt.com/blogger301_ad.png" /></a><br/><b>Follow us on</b><br/><a href="http://twitter.com/techxt" target="_blank"><img src="https://2.bp.blogspot.com/-UX3rw13AbME/XSruAqebbTI/AAAAAAAAUxM/VYVklmneurYPZpwTGo8UaTA4kUwQwvJYwCLcBGAs/s1600/Twitter.png" /></a><br/><a href="http://facebook.com/techxt" target="_blank"><img src="https://1.bp.blogspot.com/-ZZ_NcC98H8w/XSruAVTu8QI/AAAAAAAAUxI/9UddAD1oLyoLfV5fLDwrZCd3bV4eqrI3wCLcBGAs/s1600/Facebook.png" height="38px" width="118px"/></a><p></p><b>Feeds and News</b><br /><?php get_feeds() ?>
<p></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="isudipto@gmail.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Blogger 301 Redirect Plugin">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br />Consider a Donation and remember $X is always better than $0
</td></tr></table>
	<?php 
}

function get_feeds() {
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed('http://feeds.feedburner.com/techxtrack');
if (!is_wp_error( $rss ) ){
$rss5 = $rss->get_item_quantity(5); 
$rss1 = $rss->get_items(0, $rss5); 
}
?>
<ul>
<?php if (!$rss5 == 0)foreach ( $rss1 as $item ){?>
<li style="list-style-type:circle">
<a target="_blank" href='<?php echo $item->get_permalink(); ?>'><?php echo $item->get_title(); ?></a>
</li>
<?php } ?>
</ul>
<?php
}
function br_admin()
{
	add_options_page('Blogger 301 Redirect', 'Blogger 301 Redirect', 7, 'bloggerredirect', 'br_option');
}
add_action('admin_menu', 'br_admin');
function getbloggerblogs()
{
global $wpdb;
$sql = "SELECT DISTINCT `meta_value` FROM $wpdb->postmeta WHERE `meta_key`='blogger_blog'";
$res = $wpdb->get_results($sql);
if($res){
$dt=date('Y');
$dt1=$dt-1;
echo "<ol>";
echo "<p>Found imported posts for following Blogger blogs. <br/>Click on links to see Google indexed pages of your blog. <br/>To check redirections click on your site links from Google search results. All links should get properly redirected to your new site respective posts.</p>"; 
foreach($res as $r)
echo '<li>'.$r->meta_value.'<p><a TARGET="_blank" href="http://www.google.com/search?q=site%3A'.$r->meta_value.'/'.$dt.'">Link 1</a>&nbsp;&nbsp;&nbsp;<a TARGET="_blank" href="http://www.google.com/search?q=site%3A'.$r->meta_value.'/'.$dt1.'">Link 2</a>&nbsp;&nbsp;&nbsp;<a TARGET="_blank" href="http://www.google.com/search?q=site%3A'.$r->meta_value.'">Link 3</a></p></li>';
echo "</ol>";
}else echo "No Posts imported from blogger blog or you did not use the Blogger Import Plugin to import posts.<br/>If you did not use Blogger Import plugin to import your blog posts then remember to check the <b>Activate Advance Search Option</b><br/>To check your redirections open Google.com and search <b>site:your_blogger_blog.blogspot.com</b> and click your site links from the result page <br/>or login to your <b>Blogger dashboard > Postings</b> and click on view";
}
?>