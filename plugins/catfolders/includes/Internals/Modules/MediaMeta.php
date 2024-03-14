<?php

namespace CatFolders\Internals\Modules;

use CatFolders\Traits\Singleton;
use CatFolders\Classes\Helpers;

class MediaMeta {
	use Singleton;

	const SIZE_KEY = 'catf_filesize';

	public function doHooks() {
		add_filter( 'manage_media_columns', array( $this, 'manage_media_columns' ) );
		add_action( 'manage_media_custom_column', array( $this, 'manage_media_custom_column' ), 10, 2 );
		add_filter( 'manage_upload_sortable_columns', array( $this, 'manage_upload_sortable_columns' ) );
		add_action( 'added_post_meta', array( $this, 'added_post_meta' ), 10, 4 );
	}

	public function manage_media_columns( $posts_columns ) {
		$posts_columns[ self::SIZE_KEY ] = __( 'File Size', 'catfolders' );
		return $posts_columns;
	}

	public function manage_upload_sortable_columns( $columns ) {
		$columns[ self::SIZE_KEY ] = self::SIZE_KEY;
		return $columns;
	}

	public function manage_media_custom_column( $column_name, $post_id ) {
		if ( self::SIZE_KEY === $column_name ) {
			echo esc_html( size_format( Helpers::get_bytes( $post_id ), 2 ) );
		}
		return false;
	}

	public function added_post_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
		if ( '_wp_attachment_metadata' === $meta_key ) {
			$bytes = Helpers::get_bytes( $post_id );
			if ( $bytes ) {
				update_post_meta( $post_id, self::SIZE_KEY, $bytes );
			}
		}
	}
}
