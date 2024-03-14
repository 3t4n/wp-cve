<?php

/**
 *
 * Class A5 MCE Button
 *
 * @ A5 Plugin Framework
 * Version: 1.0 beta 20160127
 *
 * Adds buttons to the Editor, code from
 *
 * NextGEN Gallery of Alex Rabe
 *
 */
 
class A5_AddMceButton {
	
	const version = '1.0';
	
	public $pluginname = '', $path = '', $internalVersion = 100, $hook = '';
	
	function __construct($path, $pluginname, $hook)  {
		
		// Set path to editor_plugin.js
		$this->path = plugins_url('/'.$path.'/tinymce/');
		$this->pluginname = $pluginname;
		$this->hook = $hook;
		
		// Modify the version when tinyMCE plugins are changed.
		add_filter('tiny_mce_version', array ($this, 'change_tinymce_version') );

		// init process for button control
		add_action('init', array ($this, 'addbuttons') );
	}

	/**
	 * A5_AddMceButton::addbuttons()
	 * 
	 * @return void
	 */
	function addbuttons() {
	
		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) 
			return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
		 
			// add the button for wp2.5 in a new way
			add_filter('mce_external_plugins', array ($this, 'add_tinymce_plugin' ), 5);
			add_filter($this->hook, array ($this, 'register_button' ), 5);
		
		}
	
	}
	
	/**
	 * A5_AddMceButton::register_button()
	 * used to insert button in wordpress 2.5x editor
	 * 
	 * @return $buttons
	 */
	function register_button($buttons) {
	
		array_push($buttons, 'separator', $this->pluginname );
	
		return $buttons;
	
	}
	
	/**
	 * A5_AddMceButton::add_tinymce_plugin()
	 * Load the TinyMCE plugin : editor_plugin.js
	 * 
	 * @return $plugin_array
	 */
	function add_tinymce_plugin($plugin_array) {    
	
		$plugin_array[$this->pluginname] =  $this->path . 'editor_plugin.js';
		
		return $plugin_array;
	
	}
	
	/**
	 * A5_AddMceButton::change_tinymce_version()
	 * A different version will rebuild the cache
	 * 
	 * @return $version
	 */
	function change_tinymce_version($version) {
		
		$version = $version + $this->internalVersion;
		
		return $version;
	
	}
	
}

?>