<?php

if ( ! class_exists( 'ContentAd__Includes__Images' ) ) {

	class ContentAd__Includes__Images {

		public static function get_attached_images( $post_id ) {
			return get_posts( array(
				'post_parent' => $post_id,
				'post_mime_type' => 'image',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'posts_per_page' => -1,
			) );
		}

		public static function get_first_image_src_from_content( $post_id ) {
			if( $post = get_post( $post_id ) ) {
				$pattern = '#<img.+src=[\'"]([^\'"]+)[\'"].*>#i';
				preg_match( $pattern, $post->post_content, $matches);
				if( ! empty( $matches[1] ) ) {
					return $matches[1];
				}
			}
			return false;
		}

		public static function strip_image_size_from_url( $url ) {
			return preg_replace( '#-[0-9]{3,}x[0-9]{3,}#', '', $url );
		}

		public static function get_featured_image_id( $post_id ) {
			return get_post_meta( $post_id, '_thumbnail_id', true );
		}

		public static function get_image_src_by_id( $attachment_id, $size = 'thumbnail' ) {
			if( $image = wp_get_attachment_image_src( $attachment_id, $size ) ) {
				return array_shift( $image );
			}
			return false;
		}

		public static function get_featured_image_src( $post_id ) {
			if( $attachment_id = self::get_featured_image_id( $post_id ) ) {
				return self::get_image_src_by_id( $attachment_id );
			}
			return false;
		}

		public static function get_primary_image_src( $post_id ) {

			if( $featured_image_src = self::get_featured_image_src( $post_id ) ) {
				return $featured_image_src;
			}

			if( $image_src_from_content = self::get_first_image_src_from_content( $post_id ) ) {
				return $image_src_from_content;
			}

			$attached_images = self::get_attached_images( $post_id );
			if( $attached_images && is_array( $attached_images ) ) {
				$images = wp_list_pluck( $attached_images, 'ID' );
				return self::get_image_src_by_id( array_shift( $images ) );
			}

			return false;
		}

	}

}