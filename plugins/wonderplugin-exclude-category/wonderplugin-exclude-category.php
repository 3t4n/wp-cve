<?php
/*
Plugin Name: Exclude Category from Blog
Plugin URI: https://www.wonderplugin.com/wordpress-exclude-category-from-blog/
Description: Exclude categories from WordPress blog page, home page or search
Version: 1.2
Author: Magic Hills Pty Ltd
Author URI: https://www.wonderplugin.com
*/

class WonderPlugin_Exclude_Category_Plugin {

	function __construct() {

		$this->init();
	}
	
	function init() {

		add_action( 'admin_menu', array($this, 'register_menu') );

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'modify_plugin_action_links') );
		add_filter( 'pre_get_posts', array($this, 'exclude_categories') );
	}
	
	function modify_plugin_action_links( $links ) {
		
		$links[] = '<a href="'. admin_url('options-general.php?page=wonderplugin_exclude_category') . '">Settings</a>';
		$links[] = '<a href="https://www.wonderplugin.com/wordpress-exclude-category-from-blog/#tutorial" target="_blank">Online Tutorial</a>';
		return $links;
	}
	
	function register_menu() {

		add_options_page('Exclude Categories', 'Exclude Categories', 'manage_options', 'wonderplugin_exclude_category', array($this, 'edit_settings' ) );
	}
	
	function edit_settings() {
	
		?>
		<div class='wrap'>			
		<h2><?php _e( 'Exclude Categories from Blog', 'wonderplugin_exclude_category' ); ?> </h2>

		<?php
		if ( isset($_POST['save-wonderplugin-options']) && check_admin_referer('wonderplugin-exclude-category', 'wonderplugin-exclude-category-settings') )
		{
			$this->save_settings($_POST);
			echo '<div class="updated"><p>Settings saved.</p></div>';
		}
		$settings = $this->get_settings();
		?>
	
		<form method="post">
		<?php wp_nonce_field('wonderplugin-exclude-category', 'wonderplugin-exclude-category-settings'); ?>

		<h3>Options</h3>

		<table class="form-table">
		<tr>
			<th>Main Query</th>
			<td><label><input type='checkbox' name='mainquery_only' value='1' <?php echo ($settings['mainquery_only'] == '1') ? 'checked' : ''; ?>>Apply to WordPress main query only</label></td>
		</tr>
		</table>

		<h3>Select Categories</h3>

		<table class="widefat">
		<tr>
			<td>Category Name</td>
			<td>Category ID</td>
			<td>Exclude from Blog Page</td>
			<td>Exclude from Search</td>
		</tr>

		<?php
			$cats = get_categories(array(
				'hide_empty' => 0
			));

			foreach($cats as $cat)
			{
				$is_blog_checked = (!empty($settings['exclude_blog']) && in_array($cat->cat_ID, $settings['exclude_blog']));
				$is_search_checked = (!empty($settings['exclude_search']) && in_array($cat->cat_ID, $settings['exclude_search']));

				echo '<tr>';
				echo '<td><strong>' . $cat->cat_name . '</strong></td>';
				echo '<td>' . $cat->cat_ID . '</td>';
				echo '<td><input type="checkbox" name="exclude_blog_' . $cat->cat_ID . '" value="1"'. ($is_blog_checked ? ' checked' : '') . '></td>';
				echo '<td><input type="checkbox" name="exclude_search_' . $cat->cat_ID . '" value="1"'. ($is_search_checked ? ' checked' : '') . '></td>';
				echo '</tr>';
			}
		?>
		</table>
		<p class="submit"><input type="submit" name="save-wonderplugin-options" id="save-wonderplugin-options" class="button button-primary" value="Save Changes"  /></p>
		</form>

		<table class="form-table">
		<tr>
			<th>Online Tutorial</th>
			<td><a href="https://www.wonderplugin.com/wordpress-exclude-category-from-blog/#tutorial" target="_blank">How to exclude categories from Blog in WordPress</a></td>
		</tr>
		</table>
	<?php
	}
	
	function get_settings() {

		$settings = array();
		
		$settings['exclude_blog'] = get_option( 'wonderplugin_exclude_blog', array() );
		$settings['exclude_search'] = get_option( 'wonderplugin_exclude_search', array() );
		$settings['mainquery_only'] = get_option( 'wonderplugin_mainquery_only', 1 );

		return $settings;
	}
	
	function save_settings($options) {

		$mainquery_only = 0;
		$blog_ids = array();
		$search_ids = array();

		foreach($options as $key => $value)
		{
			if ($key == 'mainquery_only' && $value == '1')
			{
				$mainquery_only = 1;
			}
			else if (substr($key, 0, 13) === "exclude_blog_")
			{
				$cat_id = substr($key, 13);

				if (is_numeric($cat_id))
				{
					$blog_ids[] = $cat_id;
				}	
			}
			else if (substr($key, 0, 15) === "exclude_search_")
			{
				$cat_id = substr($key, 15);

				if (is_numeric($cat_id))
				{
					$search_ids[] = $cat_id;
				}	
			}
		}

		update_option( 'wonderplugin_mainquery_only', $mainquery_only );
		update_option( 'wonderplugin_exclude_blog', $blog_ids );
		update_option( 'wonderplugin_exclude_search', $search_ids );
	}

	function exclude_categories( $query ) {

		if ( is_admin() )
			return;

		$settings = $this->get_settings();

		if ($query->is_home() && !empty($settings['exclude_blog']))
		{
			if ($settings['mainquery_only'] !== '1' || $query->is_main_query())
			{
				$query->set('category__not_in', $settings['exclude_blog']);
			}
		}
		else if ($query->is_search() && !empty($settings['exclude_search']))
		{
			if ($settings['mainquery_only'] !== '1' || $query->is_main_query())
			{
				$query->set('category__not_in', $settings['exclude_search']);
			}
		}
	}

}

$wonderplugin_exclude_category_plugin = new WonderPlugin_Exclude_Category_Plugin();