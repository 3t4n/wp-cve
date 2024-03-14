<?php 
/**
 * Bootstrap Blocks for WP Editor Theming.
 *
 * @version 1.3.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2023-04-18
 * 
 * @since 1.3.0
 * added xxl, fixed colors 7 and 8
 * @since 1.2.0
 * color css in admin too
 * @since 1.1.2
 * Integrated external bootstrap files as internal library

 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function load_mod_gtb_theming()
{

	GutenbergBootstrap::AddModule('theming',array(
		'name' => 'Scripts & CSS theming',
		'version'=>'1.2.0'
	));
	
	function init_mod_gtb_theming()
	{
		global $gtb_options;
		$gtb_options = get_option( 'gtbbootstrap_options' );
		if (empty($gtb_options)) return;

		function gtb_colors_css()
		{
			global $gtb_options;
			return ".color-primary,.has-gtb-color-1-color{color:".$gtb_options['bootstrap_color1']."!important;}
			.bg-primary,.has-gtb-color-1-background-color{background-color:".$gtb_options['bootstrap_color1']."!important;}
			.color-secondary,.has-gtb-color-2-color{color:".$gtb_options['bootstrap_color2']."!important;}
			.bg-secondary,.has-gtb-color-2-background-color{background-color:".$gtb_options['bootstrap_color2']."!important;}
			.color-success,.has-gtb-color-3-color{color:".$gtb_options['bootstrap_color3']."!important;}
			.bg-success,.has-gtb-color-3-background-color{background-color:".$gtb_options['bootstrap_color3']."!important;}
			.color-danger,.has-gtb-color-4-color{color:".$gtb_options['bootstrap_color4']."!important;}
			.bg-danger,.has-gtb-color-4-background-color{background-color:".$gtb_options['bootstrap_color4']."!important;}
			.color-warning,.has-gtb-color-5-color{color:".$gtb_options['bootstrap_color5']."!important;}
			.bg-warning,.has-gtb-color-5-background-color{background-color:".$gtb_options['bootstrap_color5']."!important;}
			.color-info,.has-gtb-color-6-color{color:".$gtb_options['bootstrap_color6']."!important;}
			.bg-info,.has-gtb-color-6-background-color{background-color:".$gtb_options['bootstrap_color6']."!important;}
			.color-white,.has-gtb-color-7-color{color:".$gtb_options['bootstrap_color7']."!important;}
			.bg-white,.has-gtb-color-7-background-color{background-color:".$gtb_options['bootstrap_color7']."!important;}
			.color-black,.has-gtb-color-8-color{color:".$gtb_options['bootstrap_color8']."!important;}
			.bg-black,.has-gtb-color-8-background-color{background-color:".$gtb_options['bootstrap_color8']."!important;}";
		}

		function enqueue_gtb_bootstrap_theming()
		{
			global $bootstrap_template_loaded;

			$gtb_options = get_option( 'gtbbootstrap_options' );
			$bootstrap_included = !empty($gtb_options['bootstrap_included']) && $gtb_options['bootstrap_included'] == 'Y';
			$bootstrap_colors_included = !empty($gtb_options['bootstrap_colors_included']);
			$bootstrap_on_template = !empty($gtb_options['bootstrap_on_template']);
			$update_time = WP_DEBUG?time():GUTENBERGBOOTSTRAP_VERSION;

			if (empty($bootstrap_template_loaded) && $bootstrap_on_template) return;

			$custom_css = '.gtb-sp{width:0! important;display: relative! important;margin:0 auto! important;}.gtb-sp>.gtb-fw {width: 100vw;position: relative;left: 50%;right: 50%;margin-left: -50vw;margin-right: -50vw;}'.PHP_EOL;
	
			if ($bootstrap_colors_included):
				$custom_css .= gtb_colors_css();
			endif;

			if (!$bootstrap_included || $bootstrap_on_template):

				// Styles.
				$bootstrap_version = !empty($gtb_options['bootstrap_version'])?$gtb_options['bootstrap_version']:'5.3';
				wp_enqueue_style('bootstrap', plugins_url( '/libs/bootstrap/'.$bootstrap_version.'/bootstrap.min.css',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ), array(), $update_time);
				wp_enqueue_script('bootstrap', plugins_url( '/libs/bootstrap/'.$bootstrap_version.'/bootstrap.min.js',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ), array( 'jquery' ), $update_time, true);
				wp_enqueue_script('popper', plugins_url( '/libs/bootstrap/'.$bootstrap_version.'/popper.min.js',GUTENBERGBOOTSTRAP_PLUGIN_BASENAME ), array( 'jquery' ), $update_time, true);
			else:
				wp_register_style( 'bootstrap', false );
				wp_enqueue_style( 'bootstrap' );
			endif;
			wp_add_inline_style( 'bootstrap', $custom_css );
		}

		add_action('wp_enqueue_scripts', 'enqueue_gtb_bootstrap_theming');

		if (!empty($gtb_options['bootstrap_colors_included'])):
			add_theme_support( 'editor-color-palette', 
				array(
					array(
						'name' => __( 'primary' ),
						'slug' => 'gtb_color1',
						'color' => $gtb_options['bootstrap_color1']
					),
					array(
						'name' => __( 'secondary' ),
						'slug' => 'gtb_color2',
						'color' => $gtb_options['bootstrap_color2']
					),
					array(
						'name' => __( 'success' ),
						'slug' => 'gtb_color3',
						'color' => $gtb_options['bootstrap_color3']
					),
					array(
						'name' => __( 'danger' ),
						'slug' => 'gtb_color4',
						'color' => $gtb_options['bootstrap_color4']
					),
					array(
						'name' => __( 'warning' ),
						'slug' => 'gtb_color5',
						'color' => $gtb_options['bootstrap_color5']
					),
					array(
						'name' => __( 'info' ),
						'slug' => 'gtb_color6',
						'color' => $gtb_options['bootstrap_color6']
					),
					array(
						'name' => __( 'white' ),
						'slug' => 'gtb_color7',
						'color' => $gtb_options['bootstrap_color7']
					),
					array(
						'name' => __( 'black' ),
						'slug' => 'gtb_color8',
						'color' => $gtb_options['bootstrap_color8']
					)
			));

			// this is for > WP5.7 admin
			if (is_admin() && !empty($gtb_options['bootstrap_colors_included'])):
				function gtb_admin_styles() {
					echo '<style>'.gtb_colors_css().'</style>';
				}
				add_action('admin_head', 'gtb_admin_styles');
			endif;

		endif;
	}
	add_action('gtb_init','init_mod_gtb_theming');
}

add_action('gtb_bootstrap_modules','load_mod_gtb_theming');