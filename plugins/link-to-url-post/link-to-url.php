<?php
/*
Plugin Name: Link to URL / Post
Plugin URI: http://letusbuzz.com/link-to-url-post-plugin/
Author: Sudipto Pratap Mahato
Version: 1.3
Description: Hide your long ugly affiliate links with your own short and easy to remember links or redirect your old posts to a new post or external link!!
Requires at least: 3.0
Tested up to: 3.6.1
*/

add_action('admin_menu', 'l2u_addmenu');
add_action( 'init', 'l2u_redirect_to',1);
function l2u_redirect_to()
{
global $wpdb;
$requrl=l2u_remove_front_slash($_SERVER["REQUEST_URI"]);
$p=l2u_source_exist($requrl);

if($p!==false)
{
	$post=get_post($p);
	$id=$post->ID;
	$desturl=l2u_get_dest_url($post->post_content);
	$hcnt=$post->comment_count+1;
	if($post->post_status=="publish")$redtype=301; else $redtype=302;
	$sql="UPDATE {$wpdb->posts} SET `comment_count`=$hcnt where `ID`=$id";
	$res = $wpdb->get_results($sql);
	wp_redirect( $desturl, $redtype ); 
	exit;
}

}
function l2u_get_dest_url($urls)
{
	$urls=str_replace(" ","",$urls);
	$allurls=explode("\n",$urls);
	$cnt=rand(1,count($allurls))-1;
	return $allurls[$cnt];

}
function l2u_addmenu(){
	add_options_page("Link to URL", "Link to URL", "administrator", "l2url", "l2u_options");
}
function l2u_options()
{
global $wpdb;
$sourceurl='';
$desturl='';
$postid='';
$redtype='publish';
if( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page' );
        }
if(isset($_GET['edit']))
{
	$peid=$_GET['edit'];
	if($peid!="")
	{
		$post=get_post($peid);
		$sourceurl=$post->post_title;
		$desturl=$post->post_content;
		$postid=$post->ID;
		$redtype=$post->post_status;	
	}	
}
if(isset($_GET['delete']))
	$did=$_GET['delete'];
else
	$did="";
if($did!="")
{
	wp_delete_post($did);
}
if(isset($_GET['resethits']))
{
	$peid=$_GET['resethits'];
	if($peid!="")
	{
		$sql="UPDATE {$wpdb->posts} SET `comment_count`=0 where `ID`=$peid";
		$res = $wpdb->get_results($sql);
	}
}
if(isset( $_POST['Submit'] ))l2u_save();;
?>
	<h2> Link to URL / Post Options </h2>
	 <p>Like this Plugin then why not hit the like button. Your like will motivate me to enhance the features of the Plugin :)<br />
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Ftechxt&layout=standard&show_faces=false&width=450&action=like&font=verdana&colorscheme=light&height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe><br />And if you are too generous then you can always <b>DONATE</b> by clicking the donation button.<br/>A Donation will help in the uninterrupted developement of the plugin.<br /><a href="http://letusbuzz.com/link-to-url-post-plugin/" TARGET='_blank'>Click here</a> for <b>Reference on using the plugin</b> or if you want to <b>report a bug</b> or if you want to <b>suggest a Feature</b><br /></p>
<table class="form-ta">	
<tr>
		<td style="background:#008080;border:1px solid #eee;padding:2px;" ><font color="#FFFFFF">
		<b>Add / Edit a Keyword or URL to Redirect</b></font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;" ><font color="#FFFFFF">
		Follow / Donate</font></td>
</tr>
<tr valign="top">
<td>
	<form action="<?php echo site_url();?>/wp-admin/options-general.php?page=l2url" method="post" >
		<p><b>Source Url / Keyword </b><br /><?php echo site_url().'/'; ?><input type="text" name="sourceurl" style="width: 600px;" value="<?php echo $sourceurl; ?>" /></p>
				
		<p><b>Destination Urls</b><br />Add one URL per line<br />In case you add more than one Destination URLs then the Source URL will be redirected Randomly to anyone of the destination URLs<br/> e.g. http://example.com/abcdef.htm<br /><textarea name="desturl" rows="6" cols="50" style="width:600px;"><?php echo stripslashes(htmlspecialchars($desturl)); ?></textarea></p>
		
		<input type="radio" name="redtype" value="301" <?php  if($redtype=='publish')echo ' checked';?>></input><label for="redtype">&nbsp;&nbsp;301 Permanent Redirection&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="radio" name="redtype" value="302"  <?php  if($redtype=='draft')echo ' checked';?>></input><label for="redtype">&nbsp;&nbsp;302 Temporary Redirection</label>
        
		<input type="hidden" name="postid" value="<?php echo $postid; ?>">
		<input type="hidden" name="sourceurl2" value="<?php echo $sourceurl; ?>">
	        <p class="submit">
        	    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Redirection') ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
        	    <a href="<?php echo site_url();?>/wp-admin/options-general.php?page=l2url" > <b>Cancel</b></a>
        	</p>
        	<?php wp_nonce_field(); ?>
        </form>
</td>
<td width="25%"><b>Follow us on</b><br/><a href="http://twitter.com/letusbuzz" target="_blank"><img src="http://a0.twimg.com/a/1303316982/images/twitter_logo_header.png" /></a><br/><a href="http://facebook.com/letusbuzzz" target="_blank"><img src="https://secure-media-sf2p.facebook.com/ads3/creative/pressroom/jpg/b_1234209334_facebook_logo.jpg" height="38px" width="118px"/></a><p></p>
<p></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="isudipto@gmail.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Link to URL or POST">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br />Consider a Donation and remember $X is always better than $0<br/>
<a href="http://techxt.com/link2url_ad" target="_blank"><img src="http://techxt.com/link2url_ad.png" /></a>
</td>
</tr>
</table>
        <?php l2u_list_redirections(); ?>
<?php	
}
function l2u_save() {
	$sourceurl=l2u_remove_front_slash(trim($_POST['sourceurl']));
	$sourceurl2=l2u_remove_front_slash(trim($_POST['sourceurl2']));
	$desturl=trim($_POST['desturl']);
	$postid=trim($_POST['postid']);
	$redtype=$_POST['redtype'];
	if($redtype=="301")$redtype='publish'; else $redtype='draft';
	if($sourceurl=="" || $desturl=="")return false;
	if($sourceurl2!="")$pid=l2u_source_exist($sourceurl2);
	else $pid=l2u_source_exist($sourceurl);
	if($postid!="" && $postid!=$pid){
	 	l2u_display_message("Duplicate Keyword Exist");
	 	return;
	}
	if($pid!==false && $postid=="")
	{
		l2u_display_message("Duplicate Keyword Exist");
	 	return;
	}
	if($pid===false){
		if($postid!="")$pid=$postid; else $pid='';
	}
	
	$l2u_data = array(
		'ID' =>  $pid,
                'post_type' => 'l2u',
                'post_title' => $sourceurl,
                'post_content' => $desturl,
                'post_status' => $redtype
              );
          $p=wp_insert_post( $l2u_data );
	l2u_display_message('Saved Keyword / URL');
}

function l2u_list_redirections()
{
        $args = array(
            'post_type' => 'l2u',
            'posts_per_page' => -1,
            'orderby' => 'ID',
            'order' => 'ASC'
        );
        $posts = new WP_Query( $args );
?>
        <h3>List of all Redirections</h3>
        <table width="98%" cellpadding="0" style="border:1px solid #eee;border-collapse: collapse;table-layout: fixed;" cellspacing="0">
	<tr>
		<td style="background:#008080;border:1px solid #eee;padding:2px;"  width="20"><font color="#FFFFFF">
		S.NO.</font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;" width="35%" ><font color="#FFFFFF">
		Source URL / Keyword</font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;" width="35%" ><font color="#FFFFFF">
		Destination URL</font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;"  width="35"><font color="#FFFFFF">
		Redirection Type</font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;"  width="20"><font color="#FFFFFF">
		Hit Count</font></td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;"  width="20">&nbsp;</td>
		<td style="background:#008080;border:1px solid #eee;padding:2px;"  width="30">&nbsp;</td>
	</tr>
<?php
$cnt=1;
        foreach( (array) $posts->posts as $post ) {
        if($post->post_status=="publish")$redtype='301'; else $redtype='302';
?>
		<tr>
			<td style="border:1px solid #eee;padding:2px;text-align: center;"><?php echo $cnt; ?></td>
			<td style="border:1px solid #eee;padding:2px; word-wrap:break-word;"><?php echo '<a href="'.site_url().'/'.l2u_remove_front_slash($post->post_title).'" target="_blank">'.site_url().'/'.l2u_remove_front_slash($post->post_title).'</a>'; ?></td>
			<td style="border:1px solid #eee;padding:2px; word-wrap:break-word;"><?php l2u_list_all_dest($post->post_content); ?></td>
			<td style="border:1px solid #eee;padding:2px;text-align: center;"><?php echo $redtype; ?></td>
			<td style="border:1px solid #eee;padding:2px;text-align: right;"><?php echo $post->comment_count; ?>&nbsp;&nbsp;<a onclick = "if (! confirm('Do you really want to reset hits?')) return false;" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=l2url&resethits=<?php echo $post->ID; ?>">x</a></td>
			<td style="border:1px solid #eee;padding:2px;text-align: right;"><a href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=l2url&edit=<?php echo $post->ID; ?>">Edit</a></td>
			<td style="border:1px solid #eee;padding:2px;text-align: right;"><a onclick = "if (! confirm('Do you really want to delete?')) return false;" href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=l2url&delete=<?php echo $post->ID; ?>">Delete</a></td>
		</tr>
        	
<?php
		$cnt=$cnt+1;
        }
echo "</table>";
}

function l2u_list_all_dest($urls)
{
	$urls=str_replace(" ","",$urls);
	$allurls=explode("\n",$urls);
	foreach($allurls as $url)
	{
		echo '<a href="'.$url.'" target="_blank">'.$url.'</a><br/>';	
	}
}

function l2u_source_exist($pt)
{
        global $wpdb;
	$sql = "SELECT * FROM {$wpdb->posts} WHERE `post_type`='l2u' and `post_title`='$pt'";
	$res = $wpdb->get_results($sql);
	if($res){
		$post=$res[0];
		return $post->ID;
	}else return false;
}

function l2u_remove_front_slash($url)
{
	if(substr($url,0,1)=='/')
		return substr($url,1,strlen($url));
	else 
		return $url;
		
}

function l2u_display_message($msg)
{
?>
	<div class="updated settings-error" id="setting-error-settings_updated"> 
	<p><strong><?php echo $msg; ?></strong></p></div>
<?php
}
?>