<?php

namespace src\wetail;

if ( !defined( 'ABSPATH' ) ) die();

use Mustache_Autoloader;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use src\fortnox\WF_Plugin;

if( ! class_exists( __NAMESPACE__ . "\WF_View" ) ):
class WF_View
{
	/**
	 * Get Mustache instance (singleton)
	 */
	public static function getMustache() 
	{
		static $mustache = null;
		
		if( empty( $mustache ) ) {
			$plugin_dir = plugin_dir_path( dirname( dirname( __FILE__ ) ) );
			$template_dir = "{$plugin_dir}assets/templates";
			
			if( ! class_exists( 'Mustache_Autoloader' ) ) {
				require_once "{$plugin_dir}vendor/mustache-php/src/Mustache/Autoloader.php";
				Mustache_Autoloader::register();
			}
			
			$mustache = new Mustache_Engine( [
				'loader' => new Mustache_Loader_FilesystemLoader( $template_dir, [
					'extension' => "ms"
				] ),
				/*'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsDir, [
					'extension' => "ms"
				] ),*/
				'cache' => WP_CONTENT_DIR . '/cache/mustache',
				'helpers' => self::getMustacheHelpers()
			] );

			$mustache->addHelper( 'wc_help_tip', array(
				'wc_help_tip' => function() {
					ob_start();
					include 'test.php';
					return ob_get_clean();
				},
			) );

			function wc_help_tip( $tip, $allow_html = false ) {
				if ( $allow_html ) {
					$tip = wc_sanitize_tooltip( $tip );
				} else {
					$tip = esc_attr( $tip );
				}

				return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
			}
		}
		
		return $mustache;
	}
	
	/**
	 * Get Mustache helpers
	 */
	public static function getMustacheHelpers() 
	{
		$helpers = [];
		
		$helpers['i18n'] = function( $text, $helper ) {
			return $helper->render( __( $text, WF_Plugin::TEXTDOMAIN ) );
		};

		$helpers['formatTooltip'] = function( $tip, $helper ) {
			return $helper->render( '<span class="woocommerce-help-tip" data-tip="' . esc_attr( $tip ) . '"></span>' );
		};

		$helpers['formatHtmlTooltip'] = function( $tip, $helper ) {
			return $helper->render( '<span class="woocommerce-help-tip" data-tip="' . wc_sanitize_tooltip( $tip ) . '"></span>' );
		};

		return $helpers;
	}
	
	/**
	 * Render view
	 *
	 * @param $template
	 * @param $data
	 */
	public static function render( $template, $data = [] )
	{
		$mustache = self::getMustache();
		
		print $mustache->render( $template, $data );
	}
}
endif;
