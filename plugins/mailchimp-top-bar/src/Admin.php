<?php
/*
Mailchimp Top Bar
Copyright (C) 2015, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
namespace MailChimp\TopBar;

class Admin {

	/**
	 * Add plugin hooks
	 */
	public function add_hooks()
    {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_filter( 'mc4wp_admin_menu_items', array( $this, 'add_menu_item' ) );
		add_action( 'admin_footer_text', array( $this, 'footer_text' ), 11 );
		add_action( 'mc4wp_admin_enqueue_assets', array( $this, 'load_assets' ), 10, 2 );

		// for BC with Mailchimp for WP < 3.0
		add_filter( 'mc4wp_menu_items', array( $this, 'add_menu_item' ) );
	}

	/**
	 * Runs on `admin_init`
	 */
	public function init() {
		// only run for administrators
		// TODO: Use mc4wp capability here
		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// register settings
		register_setting( 'mailchimp_top_bar', 'mailchimp_top_bar', array( $this, 'sanitize_settings' ) );

		// add link to settings page from plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( MAILCHIMP_TOP_BAR_FILE ), array( $this, 'add_plugin_settings_link' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links'), 10, 2 );
	}

	/**
	 * Register menu pages
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function add_menu_item( array $items ) {

			$item = array(
				'title' => __( 'Mailchimp Top Bar', 'mailchimp-top-bar' ),
				'text' => __( 'Top Bar', 'mailchimp-top-bar' ),
				'slug' => 'top-bar',
				'callback' => array( $this, 'show_settings_page' )
			);

			// insert item before the last menu item
			array_splice( $items, count( $items ) - 1, 0, array( $item ) );

			return $items;
	}

	/**
	 * Add the settings link to the Plugins overview
	 *
	 * @param array $links
	 * @return array
	 */
	public function add_plugin_settings_link( array $links ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=mailchimp-for-wp-top-bar' ), __( 'Settings', 'mailchimp-for-wp' ) );
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Adds meta links to the plugin in the WP Admin > Plugins screen
	 *
	 * @param array $links
     * @param string $file
	 * @return array
	 */
	public function add_plugin_meta_links( array $links, $file ) {
		if( $file !== plugin_basename( MAILCHIMP_TOP_BAR_FILE ) ) {
			return $links;
		}

		$links[] = sprintf( __( 'An add-on for %s', 'mailchimp-top-bar' ), '<a href="https://www.mc4wp.com/#utm_source=wp-plugin&utm_medium=mailchimp-top-bar&utm_campaign=plugins-page">Mailchimp for WordPress</a>' );
		return $links;
	}

	/**
	 * Load assets if we're on the settings page of this plugin
	 *
	 * @param string $suffix
	 * @param string $page
	 * @return void
	 */
	public function load_assets( $suffix = '', $page = '' ) {

		if( $page !== 'top-bar' ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mailchimp-top-bar-admin', $this->asset_url( "/admin.js" ), array( 'jquery', 'wp-color-picker' ), MAILCHIMP_TOP_BAR_VERSION, true );
	}

	/**
	 * Outputs the settings page
	 */
	public function show_settings_page() {
		$current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'settings';
		$options = mctb_get_options();
		$mailchimp = new \MC4WP_MailChimp();
		$lists = $mailchimp->get_lists();

		require MAILCHIMP_TOP_BAR_DIR . '/views/settings-page.php';
	}

	/**
	 * @param string $url
	 * @return string
	 */
	protected function asset_url( $url ) {
		return plugins_url( '/assets' . $url, MAILCHIMP_TOP_BAR_FILE );
	}

	/**
	 * @param string $option_name
	 * @return string
	 */
	protected function name_attr( $option_name ) {
		return 'mailchimp_top_bar[' . $option_name . ']';
	}

	/**
	 * @param array $dirty
	 * @return array $clean
	 */
	public function sanitize_settings( array $dirty ) {

		$clean = $dirty;

		 // Dynamic sanitization
		foreach( $clean as $key => $value ) {

			// make sure colors start with `#`
			if( substr( $key, 0, 6 ) === 'color_' ) {
				if( '' !== $value && $value[0] !== '#' ) {
					$clean[$key] = '#' . $value;
				}
			}
		}

		// only allow simple HTML in the bar text
		$clean['text_bar'] = strip_tags( $dirty['text_bar'], '<strong><b><em><i><u><a><span>' );

		// make sure size is either `small`, `medium` or `big`
		if( ! in_array( $dirty['size'], array( 'small', 'medium', 'big' ) ) ) {
			$clean['size'] = 'medium';
		}

		if( ! in_array( $dirty['position'], array( 'top', 'bottom' ) ) ) {
			$clean['position'] = 'top';
		}

		return $clean;
	}

	/**
	 * Ask for a plugin review in the WP Admin footer, if this is one of the plugin pages.
	 *
	 * @param string $text
	 * @return string
	 */
	public function footer_text( $text ) {

		if( ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'mailchimp-for-wp-top-bar' ) === 0 ) ) {
			$text = sprintf( 'If you enjoy using <strong>Mailchimp Top Bar</strong>, please leave us a <a href="%s" target="_blank">★★★★★</a> rating. A <strong style="text-decoration: underline;">huge</strong> thank you in advance!', 'https://wordpress.org/support/view/plugin-reviews/mailchimp-top-bar?rate=5#postform' );
		}

		return $text;
	}


}
