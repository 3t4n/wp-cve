<?php
/*
Plugin Name: Revision Diet
Plugin URI: http://www.davidjmiller.org/revision-diet/
Description: Removes revisions beyond a specified limit
Version: 1.0.1
Author: David Miller
Author URI: http://www.davidjmiller.org/
*/

/*
	Full help and instructions at http://www.davidjmiller.org/revision-diet/
*/

load_plugin_textdomain('revision_diet', 'wp-content/plugins/revision-diet'); 

function apply_diet($post_id) {
	global $wpdb;
	$options = get_option(basename(__FILE__, ".php"));
	$limit = stripslashes($options['limit']);
	$query = "select id from $wpdb->posts where post_type = 'revision' and post_name like '" . $post_id  ."-revision%' order by id desc";
	$results = $wpdb->get_results($query);
	if (count($results)) {
		$revision_num = 0;
		foreach ($results as $result) {
			if ($revision_num < $limit) $revision_num++;
			else wp_delete_post($result->id);
		}
	}
}

function trim_fat($id) {
	global $wpdb;
	$options = get_option(basename(__FILE__, ".php"));
	$limit = stripslashes($options['limit']);
	$query = "select id from $wpdb->posts where post_type = 'revision' and post_name like '" . $id."-revision%' order by id desc";
	$results = $wpdb->get_results($query);
	if (count($results)) {
		$revision_num = 0;
		foreach ($results as $result) {
			if ($revision_num < $limit) $revision_num ++;
			else wp_delete_post($result->id);
		}
	}
}

/*
	Define the options menu
*/

function revision_diet_option_menu() {
	if (function_exists('current_user_can')) {
		if (!current_user_can('manage_options')) return;
	} else {
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}
	if (function_exists('add_options_page')) {
		add_options_page(__('Revision Diet Options', 'revision_diet'), __('Revision Diet', 'revision_diet'), 1, __FILE__, 'rd_options_page');
	}
}

// Install the options page
add_action('admin_menu', 'revision_diet_option_menu');

// Prepare the default set of options
$default_options['limit'] = 5;

// the plugin options are stored in the options table under the name of the plugin file sans extension
add_option(basename(__FILE__, ".php"), $default_options, 'options for the Revision Diet plugin');

// This method displays, stores and updates all the options
function rd_options_page(){
	global $wpdb;
	// This bit stores any updated values when the Update button has been pressed
	if (isset($_POST['update_options'])) {
		// Fill up the options array as necessary
		$options['limit'] = $_POST['limit'];

		// store the option values under the plugin filename
		update_option(basename(__FILE__, ".php"), $options);
		
		// Show a message to say we've done something
		echo '<div class="updated"><p>' . __('Options saved', 'revision_diet') . '</p></div>';
	} elseif (isset($_POST['purge'])) {
		$query = "select id from $wpdb->posts where post_type = 'post'";
		$results = $wpdb->get_results($query);
		if (count($results)) {
			foreach ($results as $result) {
				trim_fat($result->id);
			}
		}

		// Show a message to say we've done something
		echo '<div class="updated"><p>' . __('Trim Completed', 'revision_diet') . '</p></div>';
	}
		// If we are just displaying the page we first load up the options array
		$options = get_option(basename(__FILE__, ".php"));
	//now we drop into html to display the option page form
	?>
		<div class="wrap">
		<h2><?php echo ucwords(str_replace('-', ' ', basename(__FILE__, ".php"). __(' Options', 'revision_diet'))); ?></h2>
		<h3><a href="http://www.davidjmiller.org/revision-diet/"><?php _e('Help and Instructions', 'random_attachment') ?></a></h3>
		<form method="post" action="">
		<fieldset class="options">
		<table class="optiontable">
			<tr valign="top">
				<th scope="row" align="right"><?php _e('Number of revisions to maintain', 'revision_diet') ?>:</th>
				<td><input name="limit" type="text" id="limit" value="<?php echo $options['limit']; ?>" size="2" /></td>
			</tr>
		</table>
		</fieldset>
		<div class="submit"><input type="submit" name="update_options" value="<?php _e('Update', 'revision_diet') ?>"  style="font-weight:bold;" /></div>
		<div class="submit"><input type="submit" name="purge" value="<?php _e('Trim Excess Revisions', 'revision_diet') ?>"  style="font-weight:bold;" /></div>
		</form>    		
	</div>
	<?php	
}

$options = get_option(basename(__FILE__, ".php"));
// initialise Revision Diet plugin
add_action('save_post','apply_diet');
?>