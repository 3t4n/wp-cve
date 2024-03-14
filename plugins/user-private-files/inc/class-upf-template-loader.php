<?php
/**
 * UPF Template Loader file.
*/

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	require plugin_dir_path( __FILE__ ) . 'class-gamajo-template-loader.php';
}

if ( ! class_exists( 'UPF_Template_Loader' ) ) {
	class UPF_Template_Loader extends Gamajo_Template_Loader{
		
		protected $filter_prefix = 'upvf';
		
		// Directory name where templates should be found into the theme.
		protected $theme_template_directory = 'upvf';

		// Plugin directory @@ UPVF_PLUGIN_DIR
		protected $plugin_directory = UPVF_PLUGIN_DIR;

		// Directory name of where the templates are stored into the plugin.
		protected $plugin_template_directory = 'templates';
		
	}
}