<?php

namespace WPAdminify\Inc\Admin\Options;

use WPAdminify\Inc\Utils;
use WPAdminify\Inc\Admin\AdminSettingsModel;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

class Custom_CSS_JS_Admin_Area extends AdminSettingsModel {

	public function __construct() {
		$this->general_post_settings();
	}

	public function get_defaults() {
		return [
			'custom_css' => '',
			'custom_js' => '',
		];
	}


	/**
	 * Post Status Columns
	 */

	public function custom_css_js_fields( &$fields ) {
		$fields[] = array(
			'type'    => 'subheading',
			'content' => Utils::adminfiy_help_urls(
				__( 'Custom CSS/JS Settings', 'adminify' ),
				'https://wpadminify.com/kb/wp-adminify-options-panel/#custom-css-js',
				'https://www.youtube.com/playlist?list=PLqpMw0NsHXV-EKj9Xm1DMGa6FGniHHly8',
				'https://www.facebook.com/groups/jeweltheme',
				'https://wpadminify.com/support/'
			),
		);

		$fields[] = array(
			'id'       => 'custom_css',
			'type'     => 'code_editor',
			'title'    => __( 'Custom CSS (Admin Area)', 'adminify' ),
			'subtitle' => __( 'Write your own <strong>Custom CSS</strong> for WordPress Admin.', 'adminify' ),
			'desc'     => __( 'Don\'t place &lt;style&gt;&lt;/style&gt; tag inside editor.', 'adminify' ),
			'settings' => array(
				'theme' => 'monokai',
				'mode'  => 'css',
			),
			'sanitize' => false,
			'default'  => $this->get_default_field( 'custom_css' ),
		);

		$fields[] = array(
			'id'       => 'custom_js',
			'type'     => 'code_editor',
			'title'    => __( 'Custom JavaScript  (Admin Area)', 'adminify' ),
			'subtitle' => __( 'Write your own <strong>Custom Script</strong> for WordPress Admin.', 'adminify' ),
			'desc'     => __( 'Don\'t place &lt;script&gt;&lt;/script&gt; tag inside editor.', 'adminify' ),
			'settings' => array(
				'theme' => 'dracula',
				'mode'  => 'javascript',
			),
			'sanitize' => false,
			'default'  => $this->get_default_field( 'custom_js' ),
		);
	}

	public function general_post_settings() {
		if ( ! class_exists( 'ADMINIFY' ) ) {
			return;
		}

		$fields = array();
		$this->custom_css_js_fields( $fields );

		// Custom CSS Settings
		\ADMINIFY::createSection(
			$this->prefix,
			array(
				'title'  => __( 'Custom CSS/JS', 'adminify' ),
				'icon'   => 'fas fa-code',
				'fields' => $fields,
			)
		);
	}
}
