<?php

if ( ! defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Replace Default Words
Plugin URI: http://mandegarweb.com/?p=3200
Description: Replace the default words in the core, plugins and themes
Author: Mandegarweb Team
Version: 1.3
Author URI: http://mandegarweb.com/
Text Domain: rdw
Domain Path: /lang/
*/

include_once('config.php');

add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() )
{
    $existing_mimes['xml'] = 'application/postscript';
    return $existing_mimes;
}

class mandegarweb_default_words
{
	function mandegarweb_replace_new_world()
	{       
		    $user=site_url();
			global $wpdb;
			$table=$wpdb->prefix."replace_mandegarweb";
			$nums= $wpdb->get_results("SELECT * FROM $table WHERE user='$user'  ");
			$words =array();
				foreach ($nums as $key) {
				$words[$key->befor]=$key->new;
				}$GLOBALS['replace_def']=$words;
		add_filter('gettext','mandegarweb_replace_words_array');
		add_filter('ngettext','mandegarweb_replace_words_array');
		add_filter('gettext_with_context','mandegarweb_replace_words_array');
		function mandegarweb_replace_words_array($replaces)
		{
            $words=$GLOBALS['replace_def'];
			$replaces=str_ireplace(array_keys($words),$words,$replaces );
			return $replaces;
		}
	}

	function mandegarweb_localization_init()
	{
		$path = dirname(plugin_basename( __FILE__ )) . '/lang/';
		$loaded = load_plugin_textdomain( 'rdw', false, $path);
	}

	function mandegarweb_index()
	{
	add_action('init', array(__CLASS__,'mandegarweb_localization_init'));
	add_action('admin_menu',array(__CLASS__, 'mandegarweb_replace_function'));
	add_action( 'admin_init', array(__CLASS__,'mandegarweb_replace_word_css' ));
	}

	function mandegarweb_replace_function()
	{
		add_management_page(__('replace default words', 'rdw' ), __('RDW', 'rdw' ), 'administrator', 'replace-default-words', array(__CLASS__, 'mandegarweb_replace_word_default'),'replace_mp_options_page');
	}

	function mandegarweb_replace_word_css()
	{
		wp_enqueue_style( 'sdm_admin_styles', plugins_url('/css/default.css', __FILE__) );
	}

	function mandegarweb_replace_word_default()
	{
		if (isset($_POST['subdel'])==' ' . __('Delete','rdw') . ' ') {
			global $wpdb;
			$table=$wpdb->prefix."replace_mandegarweb";
			$id=trim($_POST['delete']);
			$result=$wpdb->delete($table, array('id'=> $id) );
				if ($result) {
					echo '<b style="color:green;">' . __('Deleted','rdw') . '</b>';
			}
		}

		if (isset($_POST['submit'])) {
			$befor=mysql_escape_string(trim($_POST['befor']));
			$new=mysql_escape_string(trim($_POST['new']));
				if ($new=='' ||$befor=='' ) {
					echo '<div id="message" class="error"><p>' . __('Field is empty','rdw') . '</p></div>';
				}else{
					global $wpdb;
					$table=$wpdb->prefix."replace_mandegarweb";
					$result=$wpdb->insert(
					$table,
					array(
						'befor'=>$befor,
						'new'=>$new,
						'user'=>site_url()
			)
	);

		if ($result) {
				echo '<div id="message" class="updated"><p>' . __('Successfully added','rdw') . '</p></div>';
			}
		}
	}

	if (isset($_POST['export'])) {
		$content=WP_CONTENT_DIR;
		touch($content.'/uploads/rdw-backup.xml');
		$p=fopen($content.'/uploads/rdw-backup.xml', 'w');
		$user=site_url();
		global $wpdb;
		$table=$wpdb->prefix."replace_mandegarweb";
		$nums= $wpdb->get_results("SELECT * FROM $table WHERE user='{$user}'  ");

		/* create a dom document with encoding utf8 */
		$domtree = new DOMDocument('1.0', 'UTF-8');

		/* create the root element of the xml tree */
		$xmlRoot = $domtree->createElement("Row");

		/* append it to the document created */
		$xmlRoot = $domtree->appendChild($xmlRoot);

		foreach ($nums as $key) {
			$currentTrac = $domtree->createElement("Cell");
			$currentTrack = $xmlRoot->appendChild($currentTrac);
			/* you should enclose the following two lines in a cicle */
			$currentTrack->appendChild($domtree->createElement('Data',$key->id));
			$currentTrac->appendChild($domtree->createElement('Data',$key->befor));
			$currentTrac->appendChild($domtree->createElement('Data',$key->new));
			$currentTrac->appendChild($domtree->createElement('Data',$key->user));
			/* get the xml printed */
		}
 
		fputs($p,$domtree->saveXML());
			echo '<div id="message" class="updated"><p>' . __('Saved','rdw') . ' - <a target="_blank" href="'.WP_CONTENT_URL.'/uploads/rdw-backup.xml">' . __('View file','rdw') . '</a></p></div>';
	}

	if (isset($_POST['imsubmit'])) {
		$file=explode('.', $_POST['import']);
		$end=count($file);

	if($file[$end-1]=='xml') {
		$path=$_POST['import'];
		$r=0;
		$pat='';
		$pat=$path;
		global $wpdb;
		$table=$wpdb->prefix."replace_mandegarweb";
		$doc = new DOMDocument(); 
		$doc->load($pat);
		$rows = $doc->getElementsByTagName( "Cell" );
		foreach( $rows as $row ) 
	{
		$infor = $row->getElementsByTagName( "Data" );
		$data['id']= $infor->item(0)->nodeValue; 
		$data['befor'] = $infor->item(1)->nodeValue; 
		$data['new'] = $infor->item(2)->nodeValue;   
		$data['user'] = $infor->item(3)->nodeValue;
		$wpdb->insert($table,$data);
	}
		}if ($file[$end-1]!='xml'){
			echo '<div id="message" class="update-nag"><p>' . __('The file undefined ','rdw') . '</p></div>';
		}
	}
?>

<div class="header">
	<a target="_blank" href="http://mandegarweb.com/" class="RSS">
		<?php echo '<img src="' . plugins_url( 'wordpress-hosting.gif', __FILE__ ) . '" > '; ?>
	</a>
</div>

<form id="form" action="" method="POST">
	<input type="text" placeholder="<?php _e('Enter the default word','rdw'); ?>" name="befor">
	<input type="text" placeholder="<?php _e('Enter the new word','rdw'); ?>" name="new">
	<input type="submit" value="<?php _e('Add','rdw'); ?>" name="submit">
</form>

<hr>
<form method="POST" enctype="multipart/form-data">
	<?php _e('Import xml file','rdw');?><input type="text" name="import" placeholder="<?php _e('Enter url xml file','rdw');?>">
	<input type="submit" name="imsubmit" value="<?php _e('Submit','rdw'); ?>">
</form>
<form method="POST" action="">
	<input type="submit" name="export" value="<?php _e('Export','rdw'); ?>">
</form>
<hr>
<form id="form" action="" method="POST">
	<input type="submit" value="<?php _e('Show / Update','rdw'); ?>" name="subshow">
</form>
<?php
if (isset($_POST['subshow'])==' ' . __('Show / Update','rdw') . ' ') {
?>

<table class="defalt">
	<tr>
		<td><?php _e('Number','rdw'); ?></td>
		<td><?php _e('Default word','rdw'); ?></td>
		<td><?php _e('New word','rdw'); ?></td>
	</tr>
<?php
	global $wpdb;
	$table=$wpdb->prefix."replace_mandegarweb";
	$user=site_url();
	$nums= $wpdb->get_results("SELECT * FROM $table WHERE user='{$user}' ");
	$w=0;
	foreach ($nums as $key) {
	$w++;?>
	 	<tr>
	 		<td><?php echo $w;?></td>
	 		<td class="tdto">
				<span class="<?php echo 'befor-'.$w;?>"><?php echo $key->befor;?></span>
				<input type="text" value="<?php echo $key->befor;?>" id="<?php echo 'befor-'.$w;?>" style="display:none;" name="<?php echo $key->id;?>">
			</td>
	 		<td class="tdto">
				<span class="<?php echo 'new-'.$w;?>"><?php echo $key->new;?></span>
				<input type="text" value="<?php echo $key->new;?>" id="<?php echo 'new-'.$w;?>" name="<?php echo $key->id;?>" style="display:none;">
			</td>
	 		<td>
	 			<form id="form" action="" method="POST">
	 				<input type="hidden" value="<?php echo $key->id;?>" name="delete">
					<input type="submit" value="<?php _e('Delete','rdw'); ?>" name="subdel">
				</form>
	 		</td>
	 	</tr>
<?php } ?>
</table>	
<?php }}}
$poriject=new mandegarweb_default_words();
$poriject->mandegarweb_index();
$poriject->mandegarweb_replace_new_world();

class mandegarweb_update
{
	function mandegarweb_update_index()
	{
		add_action('wp_ajax_test_response', array(__CLASS__,'mandegarweb_update_ajax'));
		add_action('admin_init', array(__CLASS__,'mandegarweb_update_load_script'));
	}

	function mandegarweb_update_load_script()
	{
		// load our jquery file that sends the $.post request
		wp_enqueue_script( "ajax-update", plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );

		// make the ajaxurl var available to the above script
		wp_localize_script( 'ajax-update', 'the_ajax_url', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	function mandegarweb_update_ajax()
	{
		if(!$_POST['value'] || !$_POST['ide'] || !$_POST['wich']){
		__('Field is not posted','rdw');
		}
		else{
			$value=trim($_POST['value']);
			$ide=trim($_POST['ide']);
			$wich=trim($_POST['wich']);
			$sperate=explode('-',$wich);
			global $wpdb;
			$table=$wpdb->prefix."replace_mandegarweb";
			$result=$wpdb->update(
			$table,
			array(
				$sperate[0]=>$value,
			),
			array( 'id'=>$ide )
			);

		if ($result) {
			echo ' ' . __('Edited successfully','rdw') . ' ';
		}else{
			echo ' ' . __('Error editing','rdw') . ' ';
		}
		die();
		}
	}
}

$updated=new mandegarweb_update();
$updated->mandegarweb_update_index();
?>