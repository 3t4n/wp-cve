<?php

namespace ZPOS\Admin\Stations;

use ZPOS\Plugin;
use ZPOS\Station;
use ZPOS\Admin\Tabs\Users\UserSettings;
use ZPOS_UI\License as UILicense;
use const ZPOS\PLUGIN_NAME;

class Post
{
	const TYPE = PLUGIN_NAME . '-station';

	public function __construct()
	{
		add_action('init', [$this, 'registerPostType']);
		add_action('init', [$this, 'registerPermastruct']);
		add_filter('post_type_link', [$this, 'recipePermalink'], 10, 2);
		add_filter('user_has_cap', [$this, 'accessWoocommercePos'], 10, 3);
		add_filter('user_has_cap', [$this, 'deleteWoocommercePos'], 10, 3);

		add_filter('post_updated_messages', [$this, 'singleMessages']);
		add_filter('bulk_post_updated_messages', [$this, 'listMessages'], 10, 2);
	}

	public function deleteWoocommercePos($allcaps, $caps, $args)
	{
		if (in_array('delete_woocommerce_pos', $caps)) {
			if (isset($args[2])) {
				$pos = (int) $args[2];

				if (in_array($pos, [Station::getWCStationID(), Station::getDefaultStationID()])) {
					$allcaps['delete_woocommerce_pos'] = false;
				}
			}
		}
		return $allcaps;
	}

	public function accessWoocommercePos($allcaps, $caps, $args)
	{
		if (
			in_array('access_woocommerce_pos', $caps) &&
			isset($allcaps['access_woocommerce_pos']) &&
			$allcaps['access_woocommerce_pos']
		) {
			list($cap, $user) = $args;
			$users = UserSettings::getUsers();

			if (!$users) {
				$users = [];
			}

			$userAssign = in_array(get_user_by('id', $user)->user_login, array_values($users));

			$userAssign = apply_filters(__METHOD__, $userAssign);

			$allcaps['access_woocommerce_pos'] = $userAssign;
		}
		return $allcaps;
	}

	public function registerPostType()
	{
		register_post_type(self::TYPE, [
			'label' => __('POS Station', 'zpos-wp-api'),
			'labels' => [
				'name' => __('POS Stations', 'zpos-wp-api'),
				'singular_name' => __('POS Station', 'zpos-wp-api'),
				'menu_name' => __('POS', 'zpos-wp-api'),
				'search_items' => __('Search Stations', 'zpos-wp-api'),
				'edit_item' => __('Edit Station', 'zpos-wp-api'),
				'add_new' => __('Add New Station', 'zpos-wp-api'),
				'add_new_item' => __('Add New Station', 'zpos-wp-api'),
				'item_published' => __('Station Created', 'zpos-wp-api'),
				'item_published_privately' => __('Station Created', 'zpos-wp-api'),
				'item_updated' => __('Station Updated', 'zpos-wp-api'),
				'view_item' => __('View POS', 'zpos-wp-api'),
				'not_found' => __('No Stations found', 'zpos-wp-api'),
				'not_found_in_trash' => __('No Stations found', 'zpos-wp-api'),
			],
			'supports' => ['title'],
			'can_export' => false,
			'public' => false,
			'show_ui' => true,
			'query_var' => false,
			'publicly_queryable' => true,
			'show_in_menu' => true,
			'menu_icon' => 'dashicons-pos',
			'menu_position' => 80,
			'capability_type' => 'pos-station',
			'capabilities' => [
				'edit_post' => 'manage_woocommerce_pos',
				'read_post' => 'access_woocommerce_pos',
				'delete_posts' => 'delete_woocommerce_pos',
				'delete_post' => 'delete_woocommerce_pos',
				'edit_posts' => 'manage_woocommerce_pos',
				'edit_others_posts' => 'manage_woocommerce_pos',
				'publish_posts' => 'manage_woocommerce_pos',
				'read_private_posts' => 'manage_woocommerce_pos',
				'create_posts' => 'manage_woocommerce_pos',
			],
		]);
	}

	public function registerPermastruct()
	{
		add_rewrite_tag('%pos%', '([^/]+)', 'post_type=' . self::TYPE . '&p=');
		add_rewrite_tag('%rest_pos%', '(.*)', 'rest=');

		add_rewrite_rule(
			'^pos(\/(\d+){0}[a-zA-z]+[\w\/]*)?$',
			'index.php?post_type=' . self::TYPE . '&p=' . Station::getDefaultStationID(),
			'top'
		);

		add_permastruct(self::TYPE, 'pos/%pos%%rest_pos%', ['with_front' => false]);
	}

	function recipePermalink($permalink, $post)
	{
		if ($post->post_type !== self::TYPE) {
			return $permalink;
		}

		if ($post->ID === Station::getDefaultStationID()) {
			$replace_id = '';
			$permalink = str_replace('%rest_pos%/', '', $permalink);
		} else {
			$replace_id = $post->ID;
		}

		$permalink = str_replace('%pos%', $replace_id, $permalink);
		$permalink = str_replace('%rest_pos%', '', $permalink);

		return $permalink;
	}

	public static function parentLink()
	{
		return add_query_arg('post_type', self::TYPE, 'edit.php');
	}

	public function singleMessages($messages)
	{
		$post_ID = get_the_ID();
		$permalink = Station::getURL($post_ID);

		$view_post_link_html =
			!Plugin::isActive('pos-ui') || UILicense::isActive()
				? sprintf(' <a href="%1$s">%2$s</a>', esc_url($permalink), __('View POS', 'zpos-wp-api'))
				: '';

		$messages[self::TYPE] = $messages['post'];
		$messages[self::TYPE][1] = __('POS Station updated.', 'zpos-wp-api') . $view_post_link_html;
		$messages[self::TYPE][4] = __('POS Station updated.', 'zpos-wp-api');
		$messages[self::TYPE][6] = __('POS Station saved.', 'zpos-wp-api') . $view_post_link_html;
		$messages[self::TYPE][7] = __('POS Station saved.', 'zpos-wp-api') . $view_post_link_html;
		$messages[self::TYPE][8] = __('POS Station saved.', 'zpos-wp-api') . $view_post_link_html;
		return $messages;
	}

	public function listMessages($bulk_messages, $bulk_counts)
	{
		$bulk_messages[self::TYPE] = [
			/* translators: %s: Number of posts. */
			'updated' => _n(
				'%s station updated.',
				'%s stations updated.',
				$bulk_counts['updated'],
				'zpos-wp-api'
			),
			'locked' =>
				1 == $bulk_counts['locked'] /* translators: %s: Number of posts. */
					? __('1 station not updated, somebody is editing it.', 'zpos-wp-api')
					: _n(
						'%s station not updated, somebody is editing it.',
						'%s stations not updated, somebody is editing them.',
						$bulk_counts['locked'],
						'zpos-wp-api'
					),
			/* translators: %s: Number of posts. */
			'deleted' => _n(
				'%s station permanently deleted.',
				'%s stations permanently deleted.',
				$bulk_counts['deleted'],
				'zpos-wp-api'
			),
			/* translators: %s: Number of posts. */
			'trashed' => _n(
				'%s station moved to the Trash.',
				'%s stations moved to the Trash.',
				$bulk_counts['trashed'],
				'zpos-wp-api'
			),
			/* translators: %s: Number of posts. */
			'untrashed' => _n(
				'%s station restored from the Trash.',
				'%s stations restored from the Trash.',
				$bulk_counts['untrashed'],
				'zpos-wp-api'
			),
		];
		return $bulk_messages;
	}
}
