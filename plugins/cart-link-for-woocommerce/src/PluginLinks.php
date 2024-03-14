<?php

namespace IC\Plugin\CartLinkWooCommerce;

use IC\Plugin\CartLinkWooCommerce\Campaign\RegisterPostType;

class PluginLinks {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data .
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	public function hooks() {
		add_filter( 'plugin_action_links', [ $this, 'modify_plugin_links' ], 10, 2 );
	}

	/**
	 * @param string[] $actions     .
	 * @param string   $plugin_file .
	 *
	 * @return mixed|void
	 */
	public function modify_plugin_links( $actions, string $plugin_file ): array {
		if ( $this->plugin_data->get_plugin_file() !== $plugin_file ) {
			return $actions;
		}

		$link = add_query_arg( 'post_type', RegisterPostType::POST_TYPE, admin_url( 'edit.php' ) );

		$new_actions             = [];
		$new_actions['settings'] = '<a href="' . esc_url( $link ) . '">' . __( 'Settings', 'cart-link-for-woocommerce' ) . '</a>';

		return $new_actions + $actions;
	}
}
