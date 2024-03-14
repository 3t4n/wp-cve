<?php

if (!class_exists('Related_Links_Settings')) 
{
class Related_Links_Settings
{	
	/**
	 * Constructor
	 */
	public function __construct()
	{		
		add_action('admin_init', array($this, 'init_page'));
		add_action('admin_menu', array($this, 'add_page'));
	}
	
	/**
	 * Add default settings
	 */
	public function add_default_settings() 
	{
		$user_options = get_option('related_links_settings');
	
		// add default option if nothing exits
		if(!$user_options) 
		{
			$post_types = $this->get_linkable_post_types();
			$option = array('types' => array());
			
			// add the post types
			foreach($post_types as $index => $value)
			{
				$option['types'][$index] = $value;
			}
			
			// add the bookmarks type
			//$option['types']['bookmark'] = 'bookmark';
			
			add_option('related_links_settings', $option);
		}
	}
	
	/**
	 * Remove default settings
	 */
	public function remove_default_settings() 
	{
		// delete the user options
		delete_option('related_links_settings');
	}

	/**
	 * Register our settings. Add the settings section, and settings fields
	 */
	public function init_page()
	{
		register_setting('related_links_settings', 'related_links_settings');
		add_settings_section('post_types_section', __('Meta-Box options', 'related-links'), array($this, 'create_post_types_section'), __FILE__);
		add_settings_field('post_types_checkboxes', __('Show this type of content in the list:', 'related-links'), array($this, 'create_post_types_checkboxes'), __FILE__, 'post_types_section');
	}
	
	public function create_post_types_section() 
	{
		?>
		<p><?php _e( 'The meta-box is visible on every writing page. It shows a list of content to which you can link to.', 'related-links' ); ?></p>
		<?php
	}

	public function create_post_types_checkboxes() 
	{
		$options = get_option('related_links_settings');
		$post_types = $this->get_linkable_post_types();
		
		// add all link types that have a gui
		foreach($post_types as $post_type)
		{
			$post_type_object = get_post_type_object( $post_type );
			
			?>
			<label><input name="related_links_settings[types][<?php echo $post_type; ?>]" value="<?php echo $post_type; ?>" type="checkbox" <?php if(isset($options['types']) && isset($options['types'][$post_type])) { ?> checked="checked"<?php } ?> /> <?php echo $post_type_object->label; ?></label><br />
			<?php
		}
		
		// add the bookmarks type 
		/*
		?>
		<label><input name="related_links_settings[types][bookmark]" value="bookmark" type="checkbox" <?php if(isset($options['types']) && isset($options['types']['bookmark'])) { ?> checked="checked"<?php } ?> /> <?php _e( 'Links' ); ?></label><br />
		<?php
		*/
	}
			
	/**
	 * Add sub page to the Settings Menu
	 */
	public function add_page() 
	{
		add_options_page('Related Links Page', 'Related Links', 'administrator', __FILE__, array($this, 'create_page_content'));
	}
	
	/**
	 * Add the page structure to the sub page
	 */
	public function create_page_content() 
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		$hidden_submit = 'related_links_submit_hidden';
		
		// See if the user has posted us some information
		if( isset($_POST[$hidden_submit]) && $_POST[$hidden_submit] == 'submit' )
		{
			// Save the posted value in the database
			update_option('related_links_settings', $_POST['related_links_settings'] );
			
			// Put an settings updated message on the screen
			?><div class="updated"><p><strong><?php _e('Settings saved.'); ?></strong></p></div><?php
		}
		
		// Now display the settings editing screen
		?><div class="wrap">
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Related Links Settings', 'related-links'); ?></h2>
			<form action="" method="post">
				<input type="hidden" name="<?php echo $hidden_submit; ?>" value="submit">
				<?php settings_fields('related_links_settings'); ?>
				<?php do_settings_sections(__FILE__); ?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
			</form>
		</div><?php
	}
	
	/**
	 * Get all posty types that can be enabled in the metabox
	 */
	public function get_linkable_post_types() 
	{
		$args = array('public' => true, 'publicly_queryable' => true, 'show_ui' => true);
		
		return get_post_types($args, 'names', 'or');		
	}
}
}
?>