<?php

namespace ZPOS\Model;

class Product
{
	const POST_TYPES = ['product', 'product_variation'];
	const PREFIX = '__pos_deleted_';
	static $cachedPostData = [];

	public function __construct()
	{
		add_action('before_delete_post', [$this, 'deletePost']);
		add_action('deleted_post', [$this, 'deletedPost']);
		add_filter('woocommerce_hidden_order_itemmeta', [$this, 'hiddenOrderItemMeta']);
	}

	public static function getOptionName($type, $year = null, $month = null)
	{
		if ($year === null) {
			$year = date('Y');
		}
		if ($month === null) {
			$month = date('n');
		}

		return implode('_', [self::PREFIX, $type, $year, $month]);
	}

	public function deletePost($post_id)
	{
		$post = get_post($post_id);
		if (!in_array($post->post_type, self::POST_TYPES)) {
			return;
		}
		self::$cachedPostData[$post_id] = [
			'post_type' => $post->post_type,
			'ID' => $post->ID,
		];
	}

	// only update options after real delete
	public function deletedPost($post_id)
	{
		if (!isset(self::$cachedPostData[$post_id])) {
			return;
		}

		$post = self::$cachedPostData[$post_id];
		$type = $post['post_type'];

		$deleted = get_option(self::getOptionName($type), []);
		$index = +date('j');
		if (!isset($deleted[$index])) {
			$deleted[$index] = [];
		}
		$deleted[$index][] = $post_id;
		update_option(self::getOptionName($type), $deleted, false);
	}

	public function hiddenOrderItemMeta($meta)
	{
		$meta[] = '_pos_subtotal';
		$meta[] = '_pos_total';
		$meta[] = '_pos_price';
		return $meta;
	}
}
