<?php
/*
Plugin Name:       Social Link Pages: link-in-bio landing pages for your social media profiles
Plugin URI:        https://sociallinkpages.com
Description:       Create link-in-bio landing pages for your social profiles. All of your links at a single url for Instagram, Twitter, Facebook and more.
Tags:              link-in-bio, social profile, Instagram, social links, Linktree, linktr.ee, about.me, carrd
Version:           1.6.9
Release Date:      January 13, 2024
Tested up to:      6.4.2
Stable tag:        1.6.9
Text Domain:       social-link-pages
Domain Path:       /languages
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
*/

require __DIR__ . '/vendor/autoload.php';

use SocialLinkPages\Singleton;
use \SocialLinkPages\Admin;
use \SocialLinkPages\Api;
use \SocialLinkPages\Db;
use \SocialLinkPages\Page;

class Social_Link_Pages extends Singleton {

	public $plugin_dir_url;
	public $plugin_dir_path;
	public $plugin_basename;
	public $plugin_name_friendly;
	public $plugin_data;

	protected function setup() {
		$this->plugin_dir_url       = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path      = plugin_dir_path( __FILE__ );
		$this->plugin_basename      = plugin_basename( __FILE__ );
		$this->plugin_name_friendly = strtolower( __CLASS__ );

		Db::instance();
		Api::instance();
		Page::instance();
		add_action( 'plugins_loaded', [ 'SocialLinkPages\Admin', 'instance' ], 10 );
		add_action( 'plugins_loaded', [ $this, 'on_plugin_update' ], 1000 );
	}

	public function get_asset_urls( $app, $type = 'css' ) {
//		if ( ! is_dir( Social_Link_Pages()->get_asset_path( $app ) ) ) {
//
//		}

		$dir = new DirectoryIterator( Social_Link_Pages()->get_asset_path( $app ) . $type );

		$scripts = array();
		foreach ( $dir as $file ) {
			if ( pathinfo( $file, PATHINFO_EXTENSION ) === $type ) {
				$fullName = basename( $file );
//				$name     = substr( basename( $fullName ), 0, strpos( basename( $fullName ), '.' ) );

				$scripts[] = array(
					'name'    => $fullName,
					'url'     => sprintf(
						'%s%s/%s',
						Social_Link_Pages()->get_asset_url( $app ),
						$type,
						$fullName
					),
					'version' => Social_Link_Pages()->plugin_data()['Version']
				);
			}
		}

		return $scripts;
	}

	public function get_asset_path( $app = 'admin' ) {
		return sprintf(
			'%s%s/build/static/',
			apply_filters(
				$this->plugin_name_friendly . '-plugin_dir_path',
				Social_Link_Pages()->plugin_dir_path
			),
			$app
		);
	}

	public function get_asset_url( $app = 'admin' ) {
		return sprintf(
			'%s%s/build/static/',
			apply_filters(
				$this->plugin_name_friendly . '-plugin_dir_url',
				Social_Link_Pages()->plugin_dir_url
			),
			$app
		);
	}

	public function plugin_data() {
		if ( empty( $this->plugin_data ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$this->plugin_data = get_plugin_data( __FILE__ );
		}

		return $this->plugin_data;
	}

	public function get_plugin_name_formal() {
		return apply_filters(
			Social_Link_Pages()->plugin_name_friendly . '_plugin_name_formal',
			ucwords( str_replace( '_', ' ', $this->plugin_name_friendly ) )
		);
	}

	public function use_local() {
		if ( ! empty( $_GET['is-local'] ) || isset( $_COOKIE['is-local'] ) ) {
			return true;
		}

		return false;
	}

	public function is_local() {
		return in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) );
	}

	public function on_plugin_update() {
		$plugin_option_data = \SocialLinkPages\Db::get_option_plugin_data();

		if ( ! empty( $plugin_option_data['installed_version'] ) && $this->plugin_data()['Version'] === $plugin_option_data['installed_version'] ) {
			return;
		}

		// Do actions.
		do_action( Social_Link_Pages()->plugin_name_friendly . '_do_plugin_update', $plugin_option_data );

		// Re-get options in case actions update options.
		$plugin_option_data = \SocialLinkPages\Db::get_option_plugin_data();

		// Update version.
		$plugin_option_data['installed_version'] = $this->plugin_data()['Version'];

		// Store update.
		update_option(
			Db::get_option_name_plugin_data(),
			$plugin_option_data,
			false
		);
	}
}

function Social_Link_Pages() {
	return Social_Link_Pages::instance();
}

Social_Link_Pages();