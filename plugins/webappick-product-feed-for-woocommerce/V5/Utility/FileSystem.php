<?php
namespace CTXFeed\V5\Utility;
use WP_Error;

class FileSystem {

	/**
	 * Check Filesystem connection.
	 *
	 * @param $url
	 * @param $method
	 * @param $context
	 * @param $fields
	 *
	 * @return bool
	 */
	public static function connect_fs( $url, $method, $context, $fields = null ) {
		global $wp_filesystem;
		if ( false === ( $credentials = request_filesystem_credentials( $url, $method, false, $context, $fields ) ) ) {
			return false;
		}

		//check if credentials are correct or not.
		if ( ! WP_Filesystem( $credentials ) ) {
			request_filesystem_credentials( $url, $method, true, $context );

			return false;
		}

		return true;
	}

	/**
	 * Check if the directory for feed file exist or not and make directory
	 *
	 * @param $path
	 * @return bool
	 */
	public static function checkDir( $path ) {
		if ( ! file_exists($path) ) {
			return wp_mkdir_p($path);
		}
		return true;
	}

	/**
	 * Save XML and TXT File
	 *
	 * @param $path
	 * @param $file
	 * @param $content
	 *
	 * @return bool
	 */
	public static function saveFile( $path, $file, $content ) {
		/**
		 * @TODO use WP Filesystem API
		 * @see https://codex.wordpress.org/Filesystem_API
		 * @see http://ottopress.com/2011/tutorial-using-the-wp_filesystem/
		 *
		 * @TODO Check write permission on installation and show admin warning
		 * @see wp_is_writable()
		 */
		if ( self::checkDir( $path ) ) {
			if ( file_exists( $file ) ) {
				unlink( $file ); // phpcs:ignore
			}
			$fp = fopen( $file, 'w' ); // phpcs:ignore
			fwrite( $fp, $content ); // phpcs:ignore
			fclose( $fp ); // phpcs:ignore

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $content
	 * @param $path
	 * @param $filename
	 * @param string $admin_url
	 * @param string $nonce
	 *
	 * @return mixed|WP_Error
	 */
	public static function WriteFile( $content, $path, $filename, $admin_url = 'admin.php?page=webappick-manage-feeds', $nonce = 'wpf_feed_nonce' ) {
		global $wp_filesystem;

		$url = wp_nonce_url( $admin_url, $nonce );
		if ( self::connect_fs( $url, "", $path ) ) {
			$dir  = $wp_filesystem->find_folder( $path );
			$file = trailingslashit( $dir ) . $filename;
//			print_r($content);die();
			// Delete the file first if file already exists.
			if($wp_filesystem->exists($file)){
				self::DeleteFile( $path, $filename );
			}

			return $wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE );
		}

		return new WP_Error( "filesystem_error", "Cannot initialize filesystem" );
	}

	/**
	 * Read file from directory.
	 *
	 * @param $path
	 * @param $filename
	 * @param string $admin_url
	 * @param string $nonce
	 *
	 * @return string|WP_Error
	 */
	public static function ReadFile( $path, $filename, $admin_url = 'admin.php?page=webappick-new-feed', $nonce = 'wpf_feed_nonce' ) {
		global $wp_filesystem;

		$url = wp_nonce_url( $admin_url, $nonce );

		if ( self::connect_fs( $url, "", $path ) ) {
			$dir  = $wp_filesystem->find_folder( $path );
			$file = trailingslashit( $dir ) . $filename;

			if ( $wp_filesystem->exists( $file ) ) {
				$text = $wp_filesystem->get_contents( $file );
				if ( ! $text ) {
					return "";
				}

				return $text;
			}

			return new WP_Error( "filesystem_error", "File doesn't exist" );
		}

		return new WP_Error( "filesystem_error", "Cannot initialize filesystem" );
	}

	/**
	 * Delete file from directory.
	 *
	 * @param $path
	 * @param $filename
	 * @param string $admin_url
	 * @param string $nonce
	 *
	 * @return string|WP_Error
	 */
	public static function DeleteFile( $path, $filename, $admin_url = 'admin.php?page=webappick-new-feed', $nonce = 'wpf_feed_nonce' ) {
		global $wp_filesystem;

		$url = wp_nonce_url( $admin_url, $nonce );

		if ( self::connect_fs( $url, "", $path ) ) {
			$dir  = $wp_filesystem->find_folder( $path );
			$file = trailingslashit( $dir ) . $filename;

			if ( $wp_filesystem->exists( $file ) ) {
				return $wp_filesystem->delete( $file );
			}

			return new WP_Error( "filesystem_error", "File doesn't exist" );
		}

		return new WP_Error( "filesystem_error", "Cannot initialize filesystem" );
	}

}
