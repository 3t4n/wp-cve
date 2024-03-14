<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Workflow\Automation\AutomationPostType;

/**
 * Communication type taxonomy definition. Should be hooked with AutomationPostType
 *
 * @since 2.8
 */
final class CommunicationListPostType implements HookProvider {
	/**
	 * @var string
	 */
	public const TYPE = 'shopmagic_list';

	public function hooks(): void {
		add_action(
			'init',
			function () {
				$this->setup_post_type();
			}
		);
	}

	/**
	 * Initializes custom post type for List types.
	 *
	 * @internal
	 */
	public function setup_post_type(): void {
		$labels = [
			'name'               => _x( 'Lists', 'post type general name', 'shopmagic-for-woocommerce' ),
			'singular_name'      => _x( 'List', 'post type singular name', 'shopmagic-for-woocommerce' ),
			'menu_name'          => _x( 'Lists', 'admin menu', 'shopmagic-for-woocommerce' ),
			'name_admin_bar'     => _x( 'Lists', 'add on admin bar', 'shopmagic-for-woocommerce' ),
			'add_new'            => _x( 'Add New', 'list', 'shopmagic-for-woocommerce' ),
			'add_new_item'       => __( 'Add New List', 'shopmagic-for-woocommerce' ),
			'new_item'           => __( 'New List', 'shopmagic-for-woocommerce' ),
			'edit_item'          => __( 'Edit List', 'shopmagic-for-woocommerce' ),
			'view_item'          => __( 'View List', 'shopmagic-for-woocommerce' ),
			'all_items'          => __( 'Lists', 'shopmagic-for-woocommerce' ),
			'search_items'       => __( 'Search Lists', 'shopmagic-for-woocommerce' ),
			'parent_item_colon'  => __( 'Parent Lists:', 'shopmagic-for-woocommerce' ),
			'not_found'          => __( 'No Lists found.', 'shopmagic-for-woocommerce' ),
			'not_found_in_trash' => __( 'No Lists found in Trash.', 'shopmagic-for-woocommerce' ),
		];

		$args = [
			'labels'             => $labels,
			'description'        => __( 'ShopMagic lists.', 'shopmagic-for-woocommerce' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => AutomationPostType::POST_TYPE_MENU_URL,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'list' ],
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 10,
			'supports'           => [ 'title' ],
			'taxonomies'         => [],
		];

		register_post_type( self::TYPE, $args );
	}
}
