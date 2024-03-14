<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\WordPress\Repository;

class PostRepository
{
	private function __construct()
	{}

	public function find_by_author(\WP_User $author, $limit = 10)
	{
		return self::find(array(
			'author' => $author->ID,
			'posts_per_page' => $limit,
		));
	}

	public static function findByPostSlug($postSlug, $postType = 'post')
	{
		$arrPosts = self::find(array('posts_per_page' => 1, 'name' => $postSlug, 'post_type' => $postType));
		return isset($arrPosts[0]) ? $arrPosts[0] : null;
	}

	public static function findByPostType($postType, array $arrAdditionalArgs = null)
	{
		!empty($arrAdditionalArgs) ?: $arrAdditionalArgs = array();

		return self::find(array_merge($arrAdditionalArgs, array('post_type' => $postType)));
	}

	public static function findByPostId($postId)
	{
		return \get_post((int)$postId); // returns null if not found
	}

	public static function save(array $post)
	{
		$postId = (empty($post['ID'])) ?  wp_insert_post($post, false) : wp_update_post($post, false);

		return is_a($postId, '\WP_Error') ? 0 : $postId;
	}

	private static function find(array $query)
	{

		$query = array_merge(

				array(
					'post_status' => 'any',
					'posts_per_page' => -1,
					'orderby' => 'ID',
			),

			$query
		);

		return \get_posts($query);

		/* Default ARGS
		$args = array(
				'posts_per_page'   => 5,
				'offset'           => 0,
				'category'         => '',
				'category_name'    => '',
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => '',
				'exclude'          => '',
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => 'post',
				'post_mime_type'   => '',
				'post_parent'      => '',
				'author'	   => '',
				'author_name'	   => '',
				'post_status'      => 'publish',
				'suppress_filters' => true
		);*/



	}

	public static function delete($postId, $sendToTrash = false)
	{
		return \wp_delete_post($postId, !$sendToTrash);
	}
}