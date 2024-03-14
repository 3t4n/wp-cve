<?php 
/**
 * Bootstrap Blocks for WP Editor Layout.
 *
 * @version 1.0.3
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2021-05-15
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'GUTENBERGBOOTSTRAP_VERSION' ) ) {
	exit;
}
function load_mod_gtb_bootstrap_layout()
{
	GutenbergBootstrap::AddModule('layout', array(
		'name' => 'Free Layout Package',
		'version'=>'1.0.3',
	));

	include_once(GUTENBERGBOOTSTRAP_PLUGIN_DIR.'/modules/layout/rest-api-options.php');

	function init_mod_gtb_bootstrap_layout()
	{

		include_once(dirname(__FILE__).'/block-container.php');
		include_once(dirname(__FILE__).'/block-row.php');
		include_once(dirname(__FILE__).'/block-column.php');


		function gtb_bootstrap_editor_assets()
		{
			$user_id = get_current_user_id();
			$editor_width = intval(get_user_meta($user_id, 'editor_width' ,true));
			$update_time = WP_DEBUG?time():GUTENBERGBOOTSTRAP_VERSION;
			wp_enqueue_script(
				'gtb-bootstrap-editor', // Handle.
				plugins_url( '/modules/layout/dist/blocks.js',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ), // Block.build.js: We register the block here. Built with Webpack.
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ), // Dependencies, defined above.
				$update_time, // Version: File modification time.
				true // Enqueue the script in the footer.
			);


			wp_localize_script( 'gtb-bootstrap-editor', 'gtb_settings', array_merge(
				array(
					'version'=>GUTENBERGBOOTSTRAP_VERSION, 
					'asset_url'=>plugins_url( '/modules/settings-page/assets/',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ),
					'licensed'=>defined('GTBBOOTSTRAP_DESIGN_LC')?'1':'',
					'editor_width'=>$editor_width
				),
				get_option( 'gtbbootstrap_options' )
				)
			 );


			// Styles.
			wp_enqueue_style(
				'gtb-bootstrap-editor', // Handle.
				plugins_url( '/modules/layout/dist/blocks.css',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ), // Block editor CSS.
				array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
				$update_time // Version: File modification time.
			);
			
			$editor_css = '.dummy-class{}';
			if (!empty($editor_width) && $editor_width > 5)
			{
				$editor_width = ($editor_width * .5) + 40;
				$editor_css = '
				.wp-block {max-width: '.$editor_width.'vw;}
				.wp-block[data-align="wide"]{max-width: '.$editor_width.'vw;}
				.wp-block[data-align="full"] {max-width: '.$editor_width.'vw;}';
			}
			wp_add_inline_style( 'gtb-bootstrap-editor', $editor_css);
		}

			
		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets', 'gtb_bootstrap_editor_assets' );
	}
	add_action('gtb_init','init_mod_gtb_bootstrap_layout');
}

add_action('gtb_bootstrap_modules','load_mod_gtb_bootstrap_layout');

