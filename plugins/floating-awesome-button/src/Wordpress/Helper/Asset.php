<?php

namespace Fab\Wordpress\Helper;

! defined( 'WPINC ' ) or die;

/**
 * Add extra layer for WordPress functions
 *
 * @package    Fab
 * @subpackage Fab\Wordpress
 */

trait Asset {

	/**
	 * WordpressEnqueue Media - for custom wp_editor
	 *
	 * @return  void
	 */
	public function wp_enqueue_media() {
		wp_enqueue_media(); }

	/**
	 * WordPress Includes URL - Retrieves the URL to the includes directory.
	 *
	 * @var     string      $path   Path relative to the includes URL.
	 * @var     string      $scheme   Scheme to give the includes URL context. Accepts 'http', 'https', or 'relative'.
	 * @return  string
	 */
	public function includes_url( $path = '', $scheme = null ) {
		return includes_url( $path, $scheme ); }

	/**
	 * WordPress path function
	 */
	public function getPath( $path ) {
		if ( ! function_exists( 'get_home_path' ) ) {
			include_once ABSPATH . '/wp-admin/includes/file.php';
		}
		$path              = array(
			'path'        => $path,
			'home_path'   => get_home_path(),
			'home_url'    => get_home_url(),
			'admin_url'   => admin_url(),
			'plugin_path' => plugin_dir_path( $path ),
			'plugin_url'  => plugin_dir_url( $path ),
			'upload_dir'  => wp_upload_dir(),
			'ajax_url'    => get_home_url() . '/wp-admin/admin-ajax.php',
		);
		$path['view_path'] = $path['plugin_path'] . 'src/View/';
		return $path;
	}

	/*** Localize a script */
	public function wp_localize_script( string $handle, string $object_name, array $l10n ) {
		wp_localize_script( $handle, $object_name, $l10n );
	}

	/**
	 * WordPress enqueue style
	 *
	 * @var   string    $handle     Name of the script. Should be unique
	 * @var   string    $src        Full URL of the script, or path of the script relative to the WordPress root directory
	 * @var   array     $deps       An array of registered script handles this script depends on
	 * @var   string    $ver        String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes
	 * @var   bool      $media      The media for which this stylesheet has been defined.
	 */
	public function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {
        if ( ! strpos( $src, '//' ) ) {
            $file = sprintf('%sassets/%s', json_decode( FAB_PATH )->plugin_path, $src);
            $src = sprintf('%sassets/%s', json_decode( FAB_PATH )->plugin_url, $src);
            if(!file_exists($file)) return;
        }
		wp_enqueue_style( $handle, $src, $deps, $ver, $media );
	}

	/**
	 * WordPress enqueue script
	 *
	 * @var   string    $handle     Name of the script. Should be unique
	 * @var   string    $src        Full URL of the script, or path of the script relative to the WordPress root directory
	 * @var   array     $deps       An array of registered script handles this script depends on
	 * @var   string    $ver        String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes
	 * @var   bool      $in_footer      Whether to enqueue the script before </body> instead of in the <head>
	 */
	public function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {
		if ( ! strpos( $src, '//' ) ) {
            $file = sprintf('%sassets/%s', json_decode( FAB_PATH )->plugin_path, $src);
            $src = sprintf('%sassets/%s', json_decode( FAB_PATH )->plugin_url, $src);
            if(!file_exists($file)) return;
        }
		wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
	}

	/**
	 * Enqueue assets at frontend
	 */
	public function enqueue_assets( $assets ) {
		foreach ( $assets as $asset_id => $asset ) {
			$asset = (object) $asset;
			if ( $asset->type == 'css' && $asset->status ) {
				$this->wp_enqueue_style( $asset_id, $asset->src );
			} elseif ( $asset->type == 'js' && $asset->status ) {
				$this->wp_enqueue_script( $asset_id, $asset->src, array(), '', isset( $asset->in_footer ) ? true : false );
			}
		}
	}

}
