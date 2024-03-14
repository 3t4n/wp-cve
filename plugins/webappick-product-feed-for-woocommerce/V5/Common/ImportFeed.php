<?php

namespace CTXFeed\V5\Common;

use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Utility\Config;
use RuntimeException;

class ImportFeed {

	public function __construct() {
		add_action( 'admin_post_wpf_import', [ $this, 'import_feed' ] );
	}

	/**
	 * @throws \Exception
	 */
	public function import_feed() {
		check_admin_referer( 'wpf_import' );

		$wpf_import_file = isset( $_FILES['wpf_import_file'] ) ? $_FILES['wpf_import_file'] : '';
		$wpf_import_feed_name = isset( $_POST['wpf_import_feed_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wpf_import_feed_name'] ) ) : "";
		$wpf_import_file_name = isset( $wpf_import_file['name'] ) ? sanitize_file_name( wp_unslash( $wpf_import_file['name'] ) ) : "";
		$wpf_import_file_tmp_name = isset( $wpf_import_file['tmp_name'] ) ? sanitize_text_field( $wpf_import_file['tmp_name'] ) : "";

		if (
			$wpf_import_file &&
			$wpf_import_feed_name &&
			$wpf_import_file_name &&
			$wpf_import_file_tmp_name &&
			'wpf' === pathinfo( wp_unslash( $wpf_import_file_name ), PATHINFO_EXTENSION ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			$data      = file_get_contents( $wpf_import_file_tmp_name );

			if ( empty( $data ) ) {
				throw new RuntimeException( esc_html__( "Empty File Uploaded. Upload a Valid File.", 'woo-feed' ) );
			}

			$feed = gzinflate( $data );
			if ( false === $feed ) {
				throw new RuntimeException( esc_html__( 'Unable to read file content', 'woo-feed' ) );
			}

			// unpack meta data.
			$meta_length = unpack( 'V', $feed );
			if ( false === $meta_length ) {
				throw new RuntimeException( esc_html__( 'Unable to read data from file.', 'woo-feed' ));
			}
			$meta = unpack( 'A*', substr( $feed, 4, $meta_length[1] ) )[1];
			if ( false === $meta || 0 !== strpos( $meta, '{' ) ) {
				throw new RuntimeException( esc_html__( 'Unable to read file info.', 'woo-feed' ));
			}
			$meta = json_decode( $meta, true );
			// unpack feed data.
			$feed = substr( $feed, $meta_length[1] + 8 ); // 4 bytes for each V (length data)
			$feed = unpack( 'A*', $feed )[1];
			if ( false === $feed || 0 !== strpos( $feed, '{' ) ) {
				throw new RuntimeException( esc_html__( 'Unable to read feed data from file.', 'woo-feed' ));
			}
			if ( md5( $feed ) !== $meta['hash'] ) {
				throw new RuntimeException( esc_html__( 'Unable to verify the file.', 'woo-feed' ));
			}

			$feed = json_decode( $feed, true );
			if ( ! is_array( $feed ) ) {
				throw new RuntimeException( esc_html__( 'Invalid or corrupted config file.', 'woo-feed' ));
			}
			$config_class = new Config( $feed );
			$feed = $config_class->get_config();

			$new_name = sanitize_text_field( wp_unslash( $_POST['wpf_import_feed_name'] ) );
			$new_name = trim( $new_name );
			if ( ! empty( $new_name ) ) {
				$opt_name         = $new_name;
				$feed['filename'] = $new_name;
			} else {
				$opt_name         = $feed['filename'];
				$feed['filename'] = str_replace_trim( [ '-', '_' ], ' ', $feed['filename'] );
				$feed['filename'] = sprintf(
					'%s: %s',
					esc_html__( ' Imported', 'woo-feed' ),
					ucwords( $feed['filename'] )
				);
			}
			// New Slug.
			$opt_name = FeedHelper::generate_unique_feed_file_name( $opt_name,
				$feed['feedType'],
				$feed['provider'] );
			// save config.
			$fileName = FeedHelper::save_feed_config_data( $feed, $opt_name, false );

			$newFeedConfig['option_name'] = "wf_feed_$opt_name";
			$newFeedConfig['option_value']['feedrules'] = $feed;

			$status = FeedHelper::generate_feed( $newFeedConfig );
			// Redirect back to the list.
			wp_safe_redirect(
				add_query_arg(
					[
						'feed_imported'   => (int) false !== $fileName,
						'feed_regenerate' => 1,
						'feed_name'       => $fileName ?: '',
					],
					esc_url( admin_url( 'admin.php?page=webappick-manage-feeds' ) )
				)
			);
			die();
		}

		throw new RuntimeException( esc_html__( 'Invalid Request.', 'woo-feed' ));
	}
}
