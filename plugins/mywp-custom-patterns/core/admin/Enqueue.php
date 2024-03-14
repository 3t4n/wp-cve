<?php

namespace Whodunit\MywpCustomPatterns\Admin;


use Whodunit\MywpCustomPatterns\Init\Core;


class Enqueue {
	protected $core;

	public function __construct( Core $Core ) {
		$this->core = $Core;
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ), 20 );
		add_action( 'admin_head', array( $this, 'admin_menu' ), 20 );
		add_action( 'in_admin_header', array( $this, 'admin_post_list_header' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_post_list_assets' ), 20 );
	}

	public function admin_menu() {
		echo "<style>#adminmenu .menu-icon-mywp-custom-patterns .wp-menu-image.svg{ background-size: 18px; }</style>";
	}

	public function admin_post_list_assets() {
		global $current_screen;
		if ( $this->core->name_cpt !== $current_screen->post_type || ( 'edit' !== $current_screen->base && 'edit-tags' !== $current_screen->base ) ) {
			return;
		}

		wp_enqueue_style(
			'mywp-custom-patterns',
			$this->core->plugin_url . 'css/template.min.css',
			array()
		);
	}

	public function admin_post_list_header() {
		global $current_screen;
		if ( $this->core->name_cpt !== $current_screen->post_type || ( 'edit' !== $current_screen->base && 'edit-tags' !== $current_screen->base ) ) {
			return;
		}

		global $path_url;
		$path_url = $this->core->plugin_url;
		include( $this->core->base_dir . '/admin-parts/admin-header.php' );
	}


	public function block_editor_assets() {
		global $current_screen;
		if ( $this->core->name_cpt === $current_screen->post_type ) {
			return;
		}

		wp_enqueue_script(
			'mywp-custom-patterns-script',
			$this->core->plugin_url . 'js/template.min.js',
			array( 'wp-api-fetch', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-plugins', 'wp-edit-post' )
		);

		wp_enqueue_style(
			'mywp-custom-patterns-style',
			$this->core->plugin_url . 'css/template.min.css',
			array( 'wp-edit-blocks' )
		);

		wp_set_script_translations( 'mywp-custom-patterns-script', 'mywp-custom-patterns' );


		$template_list = $categories_list = array();


		$mywp_templates = $this->core->get_templates();
		if ( count( $mywp_templates ) > 0 ) {
			foreach ( $mywp_templates as $item_template ) {
				if ( '' !== $item_template->post_content ) {
					$template_list[ $item_template->ID ] = array(
						'id'      => $item_template->ID,
						'title'   => $item_template->post_title,
						'content' => $item_template->post_content,
					);
				}
			}
		}

		$mywp_categories = $this->core->get_categories();
		if ( count( $mywp_categories ) > 0 ) {
			foreach ( $mywp_categories as $mywp_category ) {
				$categories_list[ $mywp_category->term_id ] = array(
					'id'    => $mywp_category->term_id,
					'title' => $mywp_category->name,
				);
			}
		}


		wp_localize_script( 'mywp-custom-patterns-script',
			'MYWP_DATA',
			array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'template_list'   => $template_list,
				'nonce'           => wp_create_nonce( 'create_pattern' ),
				'categories_list' => $categories_list,
			)
		);
	}
}
