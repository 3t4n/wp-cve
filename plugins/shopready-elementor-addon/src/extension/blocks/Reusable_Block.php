<?php
namespace Shop_Ready\extension\blocks;

class Reusable_Block {

	public function register() {

		add_action( 'admin_menu', array( $this, 'dashboard_menu_page' ) );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'block_support' ), 190, 2 );
		// Add Column
		add_filter( 'manage_wp_block_posts_columns', array( $this, 'set_custom_edit_wp_block_columns' ) );
		add_action( 'manage_wp_block_posts_custom_column', array( $this, 'custom_wp_block_column' ), 10, 2 );
		add_shortcode( 'shop-ready-reusable-block', array( $this, 'reusable_block_shortcode' ) );

	}


	public function block_support( $prev, $post ) {
		global $pagenow;

		if ( ( $pagenow == 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wp_block' ) || ( $pagenow == 'post.php' && isset( $_GET['post'] ) && get_post_type( sanitize_text_field( $_GET['post'] ) ) == 'wp_block' ) ) {

			return true;

		}

		return $prev;
	}

	public function reusable_block_shortcode( $atts ) {

		extract(
			shortcode_atts(
				array(
					'id' => '',
				),
				$atts
			)
		);

		if ( ! is_numeric( $id ) ) {
			return;
		}

		$content = $this->get_reusable_block( $id );
		return $content;

	}

	public function get_reusable_block( $block_id = '' ) {

		$content = get_post_field( 'post_content', $block_id );
		return apply_filters( 'shop_ready_reusable_block_content', $content );
	}

	public function set_custom_edit_wp_block_columns( $columns ) {

		if ( ! current_user_can( 'edit_users' ) ) {
			return;
		}

		unset( $columns['date'] );

		$columns['shop-ready-block'] = esc_html__( 'ShortCode For Page Builder', 'shopready-elementor-addon' );
		$columns['date']             = esc_html__( 'Date', 'shopready-elementor-addon' );

		return $columns;
	}

	public function custom_wp_block_column( $column, $post_id ) {

		switch ( $column ) {
			case 'shop-ready-block':
				echo wp_kses_post( sprintf( "[shop-ready-reusable-block id='%s']", $post_id ) );
				break;
		}
	}

	public function dashboard_menu_page() {

		if ( ! current_user_can( 'edit_users' ) ) {
			return;
		}

		add_submenu_page(
			SHOP_READY_SETTING_PATH,
			esc_html__( 'Reusable Blocks', 'shopready-elementor-addon' ),
			esc_html__( 'Reusable Blocks', 'shopready-elementor-addon' ),
			'manage_options',
			'edit.php?post_type=wp_block'
		);

	}


}
