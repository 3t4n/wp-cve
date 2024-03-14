<?php
/**
 * @author  CodeFlavors
 * @project third-party-compatibility.php
 */

namespace Vimeotheque\Templates;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Initialize the theme custom templates.
 */
class Templates_Init {
	/**
	 * @var Template_Loader
	 */
	private $template_loader;

	/**
	 * Constructor
	 *
	 * Check for theme support and if found initialize the templates.
	 */
	public function __construct(){
		add_action(
			'after_setup_theme',
			function(){
				if( current_theme_supports( 'vimeotheque' ) ){
					$this->init();
				}
			}
		);
	}

	/**
	 * Initializes the entire functionality.
	 *
	 * @return void
	 */
	private function init(){
		$this->initialize();

		if( !is_admin() ){
			$this->template_loader = new Template_Loader();
			new Frontend_Scripts();
			new Single_Video();
		}

		add_filter(
			'vimeotheque\post_content_embed',
			function(){
				$post = get_post();
				return $post->post_type != Plugin::instance()->get_cpt()->get_post_type();
			}
		);
	}

	/**
	 * Include the templating functions.
	 *
	 * @return void
	 */
	private function initialize() {
		require_once \Vimeotheque\Helper::get_path() . 'includes/libs/templates/functions.php';
		require_once \Vimeotheque\Helper::get_path() . 'includes/libs/templates/post-template.php';
	}

}