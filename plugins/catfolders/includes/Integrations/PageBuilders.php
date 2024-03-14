<?php
namespace CatFolders\Integrations;

use CatFolders\Backend\Enqueue;

defined( 'ABSPATH' ) || exit;

class PageBuilders {
	protected $enqueue;

	public function __construct() {
		$this->enqueue = Enqueue::instance();
		add_action( 'init', array( $this, 'prepareEnqueue' ) );
	}

	public function getPageBuilders() {
		return array(
			'Elementor',
			'Beaver',
			'Cornerstone',
			'Brizy',
			'Divi',
			'Thrive',
			'Fusion',
			'OxygenBuilder',
			'TatsuBuilder',
			'Dokan',
			'Themify',
			'BricksBuilder',
			'Avada',
		);
	}

	public function prepareEnqueue() {
		$pageBuilders = $this->getPageBuilders();

		foreach ( $pageBuilders as $builder ) {
			$this->{"enqueueFor{$builder}"}();
		}
	}

	public function enqueueScripts( $enqueue_media = false, $enqueue_footer = false ) {
		if ( $enqueue_media ) {
			wp_enqueue_media();
		}

		if ( $enqueue_footer ) {
			add_action(
				'wp_footer',
				function() {
					$this->enqueue->enqueueAdminScripts( 'builders' );
				}
			);
		}

		$this->enqueue->enqueueAdminScripts( 'builders' );
	}

	public function enqueueForElementor() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForBeaver() {
		if ( class_exists( 'FLBuilderLoader' ) ) {
			add_action(
				'fl_before_sortable_enqueue',
				function() {
					$this->enqueueScripts( false, true );
				}
			);
		}
	}

	public function enqueueForBrizy() {
		if ( class_exists( 'Brizy_Editor' ) ) {
			add_action( 'brizy_editor_enqueue_scripts', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForCornerstone() {
		if ( class_exists( 'Cornerstone_Plugin' ) ) {
			add_action( 'cornerstone_before_wp_editor', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForDivi() {
		if ( class_exists( 'ET_Builder_Element' ) ) {
			add_action(
				'et_fb_enqueue_assets',
				function() {
					$this->enqueueScripts();
				}
			);
		}
	}

	public function enqueueForThrive() {
		if ( defined( 'TVE_IN_ARCHITECT' ) || class_exists( 'Thrive_Quiz_Builder' ) ) {
			add_action( 'tcb_main_frame_enqueue', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForFusion() {
		if ( class_exists( 'Fusion_Builder_Front' ) ) {
			add_action( 'fusion_builder_enqueue_live_scripts', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForOxygenBuilder() {
		if ( defined( 'CT_VERSION' ) ) {
			add_action( 'oxygen_enqueue_ui_scripts', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForTatsuBuilder() {
		if ( defined( 'TATSU_VERSION' ) ) {
			add_action( 'tatsu_builder_footer', array( $this, 'enqueueScripts' ) );
		}
	}

	public function enqueueForDokan() {
		if ( defined( 'DOKAN_PLUGIN_VERSION' ) ) {
			add_action(
				'dokan_enqueue_scripts',
				function() {
					if ( function_exists( 'dokan_is_seller_dashboard' ) ) {
						if ( ( dokan_is_seller_dashboard() || ( get_query_var( 'edit' ) && is_singular( 'product' ) ) ) || apply_filters( 'dokan_forced_load_scripts', false ) ) {
							$this->enqueueScripts();
						}
					}
				}
			);
		}
	}

	public function enqueueForThemify() {
		add_action(
			'wp_ajax_tb_load_editor',
			function() {
				$this->enqueueScripts( true );
			},
			9
		);
	}

	public function enqueueForBricksBuilder() {
		if ( defined( 'BRICKS_VERSION' ) ) {
			if ( function_exists( 'bricks_is_builder' ) && \bricks_is_builder() ) {
				add_action( 'bricks_after_footer', array( $this, 'enqueueScripts' ) );
			}
		}
	}

	public function enqueueForAvada() {
		if ( ! class_exists( 'Fusion_Builder_Front' ) && defined( 'AVADA_VERSION' ) ) {
			add_action( 'fusion_enqueue_live_scripts', array( $this, 'enqueueScripts' ) );
		}
	}
}
