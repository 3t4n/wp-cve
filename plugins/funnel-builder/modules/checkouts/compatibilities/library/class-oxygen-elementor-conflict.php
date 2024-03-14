<?php

#[AllowDynamicProperties] 

  class WFACP_Oxygen_Elementor_conflict {
	public function __construct() {
		add_action( 'template_redirect', [ $this, 'remove_all_filter' ] );
		add_action( 'save_post', [ $this, 'delete_elementor_meta' ], 10, 2 );
		add_action( 'elementor/document/after_save', [ $this, 'delete_oxygen_data' ], 10, 2 );
	}

	public function is_enabled() {

		if ( isset( $_GET['ct_builder'] ) ) {
			return false;
		}
		if ( class_exists( 'OXY_VSB_Connection' ) && class_exists( '\Elementor\Plugin' ) ) {
			global $post;

			$elementor_open = false;
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' && isset( $_GET['post'] ) ) {
				$post           = get_post( $_GET['post'] );
				$elementor_open = true;

			} else if ( isset( $_GET['elementor-preview'] ) ) {
				$post           = get_post( $_GET['elementor-preview'] );
				$elementor_open = true;
			}

			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
				if ( true == $elementor_open ) {
					return $elementor_open;
				}
				$ct_settings = get_post_meta( $post->ID, 'ct_page_settings', true );
				if ( ! empty( $ct_settings ) ) {
					return false;
				}

				return true;
			}
		}

		return false;
	}


	public function remove_all_filter() {
		if ( $this->is_enabled() ) {
			remove_all_filters( 'template_include' );
			add_filter( 'template_include', [ $this, 'adding_our_default_template' ] );
		}
	}

	public function adding_our_default_template( $my_template ) {
		$my_template = WFACP_Core()->template_loader->assign_template( $my_template );
		if ( '' == $my_template ) {
			$my_template = WFACP_Core()->dir( 'public/page-template/template-canvas.php' );
		}

		return $my_template;
	}

	public function delete_elementor_meta( $post_id, $post ) {
		if ( isset( $_GET['action'] ) && $_GET['action'] == "ct_save_components_tree" && ! is_null( $post ) && $post->post_type = WFACP_Common::get_post_type_slug() ) {
			delete_post_meta( $post_id, '_elementor_version' );
			delete_post_meta( $post_id, '_elementor_template_type' );
			delete_post_meta( $post_id, '_elementor_edit_mode' );
			delete_post_meta( $post_id, '_elementor_data' );
			delete_post_meta( $post_id, '_elementor_controls_usage' );
			delete_post_meta( $post_id, '_elementor_css' );

			return;
		}
	}

	public function delete_oxygen_data( $instance, $data ) {

		$post = $instance->get_post();
		if ( ! is_null( $post ) && $post->post_type = WFACP_Common::get_post_type_slug() ) {
			delete_post_meta( $post->ID, 'ct_page_settings' );
		}
	}
}

new WFACP_Oxygen_Elementor_conflict();
