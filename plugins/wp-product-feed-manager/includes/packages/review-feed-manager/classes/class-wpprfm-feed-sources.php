<?php

/**
 * WPPRFM Feed Sources Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Feed_Sources' ) ) :

	class WPPRFM_Feed_Sources {

		/**
		 * Returns an array with objects containing all WP comment source fields.
		 *
		 * @return array
		 */
		public static function review_feed_specific_sources() {
			return [
				(object) [
					'attribute_name'  => 'comment_ID',
					'attribute_label' => 'Comment ID',
				],
				(object) [
					'attribute_name'  => 'comment_post_ID',
					'attribute_label' => 'Comment Post ID',
				],
				(object) [
					'attribute_name'  => 'comment_author',
					'attribute_label' => 'Comment Author',
				],
				(object) [
					'attribute_name'  => 'comment_author_email',
					'attribute_label' => 'Comment Author Email',
				],
				(object) [
					'attribute_name'  => 'comment_author_url',
					'attribute_label' => 'Comment Author Url',
				],
				(object) [
					'attribute_name'  => 'comment_author_IP',
					'attribute_label' => 'Comment Author IP',
				],
				(object) [
					'attribute_name'  => 'user_id',
					'attribute_label' => 'Comment User ID',
				],
				(object) [
					'attribute_name'  => 'comment_date',
					'attribute_label' => 'Comment Date',
				],
				(object) [
					'attribute_name'  => 'comment_date_gmt',
					'attribute_label' => 'Comment Date GMT',
				],
				(object) [
					'attribute_name'  => 'comment_url',
					'attribute_label' => 'Comment Url',
				],
				(object) [
					'attribute_name'  => 'comment_content',
					'attribute_label' => 'Comment Content',
				],
				(object) [
					'attribute_name'  => 'rating',
					'attribute_label' => 'Comment Rating',
				],
				(object) [
					'attribute_name'  => 'comment_approved',
					'attribute_label' => 'Comment Approved',
				],
				(object) [
					'attribute_name'  => 'comment_agent',
					'attribute_label' => 'Comment Agent',
				],
				(object) [
					'attribute_name'  => 'comment_type',
					'attribute_label' => 'Comment Type',
				],
				(object) [
					'attribute_name'  => 'comment_parent',
					'attribute_label' => 'Comment Parent',
				],
				(object) [
					'attribute_name'  => 'comment_rating_min',
					'attribute_label' => 'Comment Rating Overall Min',
				],
				(object) [
					'attribute_name'  => 'comment_rating_max',
					'attribute_label' => 'Comment Rating Overall Max',
				],
			];
		}
	}

	// end of WPPRFM_Feed_Sources class

endif;
