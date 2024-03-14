<?php
/**
 * Shortcode Button Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Shortcode Button Class.
 */
class ShortcodeButton {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Shortcode Tag
	 *
	 * @var string
	 */
	public $shortcode_tag = 'fmp';

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_head', [ $this, 'admin_head' ] );
	}

	/**
	 * Calls the functions into the correct filters
	 *
	 * @return void
	 */
	public function admin_head() {
		// check user permissions.
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled.
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', [ $this, 'mce_external_plugins' ] );
			add_filter( 'mce_buttons', [ $this, 'mce_buttons' ] );

			echo '<style>';
			echo 'i.mce-i-' . esc_html( $this->shortcode_tag ) . '{';
				echo "background: url('" . esc_url( TLPFoodMenu()->assets_url() ) . "images/icon-20x20.png');";
				echo '}';
			echo '</style>';
		}
	}

	/**
	 * Adds our tinymce plugin
	 *
	 * @param  array $plugin_array Plugins array.
	 * @return array
	 */
	public function mce_external_plugins( $plugin_array ) {
		$plugin_array[ $this->shortcode_tag ] = esc_url( TLPFoodMenu()->assets_url() ) . 'js/mce-button.js';
		return $plugin_array;
	}

	/**
	 * Adds our tinymce button
	 *
	 * @param  array $buttons Buttons.
	 * @return array
	 */
	public function mce_buttons( $buttons ) {
		array_push( $buttons, $this->shortcode_tag );
		return $buttons;
	}
}
