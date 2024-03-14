<?php
namespace Thim_EL_Kit\Modules\LoopItem;

use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Custom_Post_Type;
use Elementor\Modules\NestedElements\Module as NestedElementsModule;

class Init extends Modules {

	use SingletonTrait;

	public function __construct() {
		parent::__construct();

		add_action( 'thim_ekit/rest_api/create_template/before', array( $this, 'before_create_template' ), 10 );
		add_action( 'thim_ekit/rest_api/create_template/after', array( $this, 'create_template' ), 10, 2 );
		add_action( 'manage_' . Custom_Post_Type::CPT . '_posts_columns', array( $this, 'remove_columns_conditions' ), 10 );
		add_filter( 'elementor/document/wrapper_attributes', array( $this, 'elementor_wrapper_attributes' ), 10, 2 );
		add_action( 'elementor/documents/register_controls', array( $this, 'elementor_document_register_controls' ), 100 );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_inline_editor_styles' ) );
		add_filter( 'body_class', array( $this, 'body_editor_class' ), 10 );
		add_filter( 'thim_ekit/elementor/documents/preview_item', array( $this, 'document_preview_item_settings' ), 10, 2 );
		add_filter( 'elementor/css-file/dynamic/should_enqueue', array( $this, 'prevent_dynamic_enqueue_css'), 10, 2 );
	}

	public function add_admin_tabs( $tabs ) {
		$tabs['loop_item'] = array(
			'name' => esc_html__( 'Loop Item', 'thim-elementor-kit' ),
			'url'  => add_query_arg(
				array(
					'post_type'            => Custom_Post_Type::CPT,
					Custom_Post_Type::TYPE => 'loop_item',
				),
				admin_url( 'edit.php' )
			),
		);

		return $tabs;
	}

	public function remove_columns_conditions( $columns ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$type = ! empty( $_GET['thim_elementor_type'] ) ? sanitize_text_field( wp_unslash( $_GET['thim_elementor_type'] ) ) : '';

		if ( $type === 'loop_item' ) {
			unset( $columns['thim-ekit-conditions'] );
		}

		return $columns;
	}

	/**
	 * Add localize data for Loop item.
	 *
	 * @param $localize
	 *
	 * @return array|mixed|null
	 */
	public function add_localization_admin( $localize ) {
		$post_type = array(
			array(
				'label' => 'Post',
				'value' => 'post',
			),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$post_type[] = array(
				'label' => 'Product',
				'value' => 'product',
			);
		}

		if ( class_exists( 'LearnPress' ) ) {
			$post_type[] = array(
				'label' => 'Course',
				'value' => 'lp_course',
			);
		}

		$localize['loop_item']['post_type'] = $post_type;

		return $localize;
	}

	// Update post type for Loop item when crete template.
	public function create_template( $id, $request ) {
		if ( $request['type'] !== 'loop_item' ) {
			return;
		}

		$post_type = ! empty( $request['loopType'] ) ? sanitize_text_field( $request['loopType'] ) : 'post';

		update_post_meta( $id, 'thim_loop_item_post_type', $post_type );
	}

	public function elementor_wrapper_attributes( $attributes, $document ) {
		$post_id = $document->get_main_id();

		if ( empty( $post_id ) ) {
			return $attributes;
		}

		$type = get_post_meta( $post_id, Custom_Post_Type::TYPE, true );

		if ( $type === 'loop_item' ) {
			$post_type = get_post_meta( $post_id, 'thim_loop_item_post_type', true );

			if ( ! empty( $post_type ) ) {
				$attributes['data-thim-ekit-loop-item-type'] = $post_type;
			}

			$attributes['data-thim-ekit-type'] = 'loop_item';
		}

		return $attributes;
	}

	public function elementor_document_register_controls( $document ) {
		$post_id = $document->get_main_id();

		if ( empty( $post_id ) ) {
			return;
		}

		$type = get_post_meta( $post_id, Custom_Post_Type::TYPE, true );

		if ( $type === 'loop_item' ) {
			// Remove Page Layout.
			$document->remove_control( 'template' );
			$document->remove_control( 'template_default_description' );
		}
	}

	public function document_preview_item_settings( $post_type, $type ) {
		if ( get_the_ID() && $type === 'loop_item' ) {
			$post_type = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );
		}

		return $post_type;
	}

	public function enqueue_inline_editor_styles() {
		if ( $this->is_loop_theme_builder() ) {
			$css = '
				.elementor-edit-area-active[data-thim-ekit-type=loop_item] .elementor-section-wrap:not(:empty) + #elementor-add-new-section {
					display: none;
				}
				.thim-kit-loop-template-canvas {
					display: flex;
					align-items: center;
					justify-content: center;
					min-height: 100vh;
				}
				.thim-kit-loop-template-canvas [data-thim-ekit-type=loop_item] {
					max-width: var(--thim-preview-width, 410px); /* --thim-preview-width is defined in class-elementor.php */
					width: var(--thim-preview-width, 410px);
				}
				.thim-kit-loop-template-canvas .elementor-add-section-area-button:not(.elementor-add-section-button) {
					display: none;
				}
			';

			wp_add_inline_style( 'editor-preview', $css );
		}
	}

	public function body_editor_class( $class ) {
		if ( $this->is_loop_theme_builder() ) {
			$class[] = 'thim-kit-loop-template-canvas';
		}
		return $class;
	}

	private function is_loop_theme_builder() {
		return $this->is_editing_existing_loop_item() || $this->is_creating_new_loop_item();
	}

	private function is_editing_existing_loop_item() {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_thim_kit = ! empty( $_GET['thim_elementor_kit'] ) ? wp_kses_post_deep( wp_unslash( $_GET['thim_elementor_kit'] ) ) : '';
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = ! empty( $_GET['elementor-preview'] ) ? wp_kses_post_deep( wp_unslash( $_GET['elementor-preview'] ) ) : '';

		$type = get_post_meta( $post_id, Custom_Post_Type::TYPE, true );

		return ! empty( $is_thim_kit ) && $type === 'loop_item';
	}

	private function is_creating_new_loop_item() {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_type = ! empty( $_GET['post_type'] ) ? wp_kses_post_deep( wp_unslash( $_GET['post_type'] ) ) : '';
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = ! empty( $_GET['p'] ) ? wp_kses_post_deep( wp_unslash( $_GET['p'] ) ) : '';

		$type = get_post_meta( $post_id, Custom_Post_Type::TYPE, true );

		return 'thim_elementor_kit' === $post_type && $type === 'loop_item';
	}

	public function get_preview_id() {
		global $post;

		$output = false;

		if ( $post ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

			if ( $document ) {
				$preview_id = $document->get_settings( 'thim_ekits_preview_id' );

				$output = ! empty( $preview_id ) ? absint( $preview_id ) : false;
			}
		}

		return $output;
	}

	public function before_editor_preview_query() {
		$current_post_id = get_the_ID();
		$document        = \Elementor\Plugin::$instance->documents->get_doc_or_auto_save( $current_post_id );

		if ( ! $document || ! $document instanceof \Elementor\Core\Base\Document ) {
			return;
		}

		$type = get_post_meta( $current_post_id, Custom_Post_Type::TYPE, true );

		if ( $type === 'loop_item' ) {
			$preview_id = $this->get_preview_id();
			$post_type  = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );
			$args = array(
				'p'         => absint( $preview_id ),
				'post_type' => $post_type,
			);	
			if(post_type_exists($post_type)){
				if ( $preview_id ) {
					$query = $args;
				} else {
					$query_vars = array(
						'post_type'      => $post_type,
						'posts_per_page' => 1,
					);

					$posts = get_posts( $query_vars );

					if ( ! empty( $posts ) ) {
						$query = array(
							'p'         => $posts[0]->ID,
							'post_type' => $post_type,
						);
					}
				}
			}else{
				$query = apply_filters('thim-kits/data-loop-item/custom-query',$args);
			}	
			if ( ! empty( $query ) ) {
				\Elementor\Plugin::instance()->db->switch_to_query( $query, true );
			}
		}
	}

	// Prevent enqueue default dynamic CSS for loop item templates.
	public function prevent_dynamic_enqueue_css ( $should_enqueue, $post_id ) {
		$type = get_post_meta( $post_id, Custom_Post_Type::TYPE, true );

		if ( $type === 'loop_item' ) {
			$should_enqueue = false;
		}

		return $should_enqueue;
	}

	public function before_create_template( $request ) {
		if ( $request['type'] === 'loop_item' && ! empty( $request['layout']['id'] ) ) {
			$is_active = \Elementor\Plugin::instance()->experiments->is_feature_active( NestedElementsModule::EXPERIMENT_NAME );

			if ( ! $is_active ) {
				throw new \Exception( sprintf( __( 'Please enable <b>Flexbox Container</b> in Elementor > Settings > Features: %s', 'thim-elementor-kit' ), '<a href="' . admin_url( 'admin.php?page=elementor#tab-experiments' ) . '" target="_blank" rel="noopener">' . __( 'Go to Settings', 'thim-elementor-kit' ) . '</a>' ) );
			}
		}
	}
}

Init::instance();
