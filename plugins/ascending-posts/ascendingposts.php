<?php
   /*
   Plugin Name: Ascending Posts Plugin
   Plugin URI: http://www.flyplugins.com
   Description: This plugin adds a feature to a post category to allow the posts in that particular category to be displayed in ascending or descending order by date.
   Version: 1.6
   Author: Fly Plugins
   Author URI: http://www.flyplugins.com
   License: GPL3
*/


require_once('fly_plugins_tools.php');		


/** 
 * Show the plugin settings page.
 */
function AP_plugin_settingsPage() 
{
	?>
	<body>
	<div class="wrap"><a href="http://www.flyplugins.com">
		<div id="fly-icon" style="background: url(<?php echo plugins_url('',__FILE__); ?>/images/fly32x32.png) no-repeat;" class="icon32"><br /></div></a>
	<h2>Ascending Post Configuration</h2> <br />
					<div class="postbox-container" style="width:70%;">
						<div class="metabox-holder">	
							<div class="meta-box-sortables">
							In order to change the order of a category from descending to ascending by date, follow these simple instructions:
							<ol>
							<li>Create a new category or if you want to modify a currently existing category skip to step #2.</li>
							<li><strong>Click edit</strong> under the newly created category, or <strong>click edit</strong> under the already existing category.</li>
							<li>Next to the "Sort Order" option, select <strong>"ascending"</strong> or <strong>"descending"</strong> from the drop down menu based upon your choice for that particular category. By default, "descending" is selected which is the default order for WordPress.</li>
							</div>
						</div>
					</div>
					<div class="postbox-container" style="width:20%;">
						<div class="metabox-holder">	
							<div class="meta-box-sortables">
								<?php
									$flydisplay = new Fly_Plugin_Admin();
									$flydisplay->donate();
									$flydisplay->fly_news(); 
								?>
							</div>
						</div>
					</div>
	</div>
	</body>
	<?php						
}


/**
 * Add admin post ascending options. 
 */
function AP_category_addExtraCategoryFields( $tag ) 
{
	global $theme_css, $cat_meta;
	$t_id = $tag->term_id;
	$curr_meta = $cat_meta[$t_id];
	$direction=( $curr_meta && isset( $curr_meta['order'] ) && $curr_meta['order'] ) ? $curr_meta['order'] : "DESC";

	$sort_order = array(
		'DESC' => array(
	 	'value' => 'DESC',
	 	'label' => 'Descending (Newest Posts First)'
	 	),
	 	'ASC' => array(
	 	'value' => 'ASC',
	 	'label' => 'Ascending (Oldest Posts First)'
	 	),
	);
	?>
	<tr>
	 	<th scope="row" valign="top"><label for="cat_meta[order]"><?php _e('Sort Order'); ?></label></th>
	 	<td>
	 		<select id="cat_meta[order]" name="cat_meta[order]">
	<?php
		foreach ( $sort_order as $option ) :
	     	$label = $option['label'];
	     	$selected = '';
	     		if ( $direction && $direction ==  $option['value'] ) $selected = 'selected="selected"';
	     			echo '<option style="padding-right: 10px;" value="' . esc_attr( $option['value'] ) . '" ' . $selected . '>' . $label . '</option>';
	   	endforeach;
	?>
	 		</select>
	 	</td>
	</tr>
	<?php
}

/**
 * Does the actual saving of the option for this category.
 */
function AP_category_saveExtraCategoryFields($term_id) 
{
	global $cat_meta;
	
	if ( isset( $_POST['cat_meta'] ) ) 
	{
		$t_id = $term_id;
		$cat_keys = array_keys($_POST['cat_meta']);
		$curr_meta = array();
		
		foreach ($cat_keys as $key)
		{
			if (isset($_POST['cat_meta'][$key])){
				$curr_meta[$key] = $_POST['cat_meta'][$key];
			}
		}
   		$cat_meta[$t_id] = $curr_meta;
   		update_option( "theme_cat_meta", $cat_meta );
 	}
}
$cat_meta = get_option("theme_cat_meta");
/* End - Add Admin Post Ascending Option */



/**
 * Function that changes the sort order of the categories by modifying the 
 * query string.
 */
function AP_categories_sortPostsByAscending($wp_query) 
{
	global $cat, $cat_meta, $query_string;
	if (is_archive() || is_category()) 
	{
		if (isset($cat_meta[$cat]['order']) &&  $cat_meta[$cat]['order'] == 'ASC') 
		{
			query_posts($query_string . "&cat=".$cat."&order=ASC");
		}
	}
} 


/**
 * Plugin initialisation.
 */
function AP_plugin_init()
{
	// Menu
	add_action('admin_menu', 				'AP_plugin_menu');
	
	// Change category sorting
	add_action('wp_head', 					'AP_categories_sortPostsByAscending');
	add_action('edit_category_form_fields', 'AP_category_addExtraCategoryFields');
	add_action('edited_category', 			'AP_category_saveExtraCategoryFields');
	
	// Add settings link to plugins page
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'AP_plugin_addSettingsLink');
}
add_action('init', 'AP_plugin_init');


/**
 * Adds the plugin menu.
 */
function AP_plugin_menu() {
	add_options_page('Ascending Post Configuration', 'Ascending Post' , 'manage_options', __FILE__, 'AP_plugin_settingsPage');
}


/**
 * Adds a link to the plugin page to click through straight to the plugin page.
 */
function AP_plugin_addSettingsLink($links) 
{ 
	$settings_link = sprintf('<a href="%s">Settings</a>', admin_url('options-general.php?page=ascending-posts/ascendingposts.php')); 
	array_unshift($links, $settings_link); 
	return $links; 
}
 
?>