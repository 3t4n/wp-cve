<?php
namespace Thim_EL_Kit\Modules;

use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Modules\Cache;

abstract class Modules {
	public $tab = '';

	public $tab_name = '';

	public $template_include = '';

	private $layouts_cache = array();

	public function __construct() {
		add_filter( 'thim_ekit/post_type/register_tabs', array( $this, 'add_admin_tabs' ) );
		add_filter( 'thim_ekit/admin/enqueue/localize', array( $this, 'add_localization_admin' ) );
		add_filter( 'thim_ekit/post_type/single_template/override', array( $this, 'override_single_template' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		add_filter( 'template_include', array( $this, 'template_include' ), 12 ); // after Elementor and WooCommerce.
		add_filter( 'body_class', array( $this, 'body_class' ), 10 );

		add_action( 'elementor/dynamic_tags/before_render', array( $this, 'before_editor_preview_query' ) );
		add_action( 'elementor/dynamic_tags/after_render', array( $this, 'after_editor_preview_query' ) );

		add_action(
			'elementor/widget/before_render_content',
			function( $widget_base ) {
				if ( $this->is_editor_preview() || $this->is_modules_view() ) {
					return $this->before_editor_preview_query();
				}
			}
		);
		add_filter(
			'elementor/widget/render_content',
			function( $widget_content, $widget_base ) {
				if ( $this->is_editor_preview() || $this->is_modules_view() ) {
					$this->after_editor_preview_query();
				}

				echo $widget_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			},
			10,
			2
		);
	}

	public function add_admin_tabs( $tabs ) {
		if ( ! empty( $this->tab ) ) {
			$tabs[ $this->tab ] = array(
				'name' => $this->tab_name,
				'url'  => add_query_arg(
					array(
						'post_type'            => Custom_Post_Type::CPT,
						Custom_Post_Type::TYPE => $this->tab,
					),
					admin_url( 'edit.php' )
				),
			);
		}

		return $tabs;
	}

	public function add_localization_admin( $localize ) {
		$localize['list_conditions'][ $this->tab ] = apply_filters( 'thim_ekit/modules/list_conditions', $this->get_conditions(), $this->tab );

		return $localize;
	}

	public function elementor_template( $template, $post_id ) {
		$elementor_modules = \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' );

		$page_template = $elementor_modules::TEMPLATE_HEADER_FOOTER;

		if ( ! empty( $post_id ) ) {
			$document      = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );
			$page_settings = $document->get_settings( 'template' );

			if ( ! in_array( $page_settings, array( '', 'default', 'elementor_theme' ) ) ) {
				$page_template = $page_settings;
			}
		}

		$template = $elementor_modules->get_template_path( $page_template );

		return apply_filters( 'thim_ekit/modules/elementor_template', $template, $this->tab );
	}

	/** Override for Elementor Editor */
	public function override_single_template( $template, $post ) {
		if ( apply_filters( 'thim_ekit/modules/override_single_template', false, $this->tab ) ) {
			return $template;
		}

		$type = get_post_meta( $post->ID, Custom_Post_Type::TYPE, true );

		if ( $post->post_type === Custom_Post_Type::CPT && $type === $this->tab ) {
			$template = $this->elementor_template( $template, 0 );

			if ( file_exists( $template ) ) {
				return $template;
			}
		}

		return $template;
	}

	public function template_include( $template ) {
		if ( apply_filters( 'thim_ekit/modules/template_include', false, $this->tab ) ) {
			return $template;
		}

		if ( ! empty( $this->template_include ) && $this->template_include ) {
			$post_id = $this->get_layout_id( $this->tab );

			if ( ! empty( $post_id ) ) {
				$elementor_modules = \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' );

				$template = $this->elementor_template( $template, $post_id );

				// Add structured data for single product.
				if ($this->tab === 'single-product' && function_exists( 'WC' ) && is_object( WC()->structured_data ) ) {
					the_post();

					WC()->structured_data->generate_product_data();
				}

				$elementor_modules->set_print_callback(
					function() use ( $post_id ) {
						echo \Elementor\Plugin::instance()->frontend->get_builder_content( absint( $post_id ), false );
						return true;
					}
				);
			}

			return $template;
		}

		return $template;
	}

	/**
	 * Added class to body
	 *
	 * @param $classes
	 * @since 1.1.6
	 * @return mixed
	 */
	public function body_class( $classes ) {
		if ( ! empty( $this->template_include ) && ! empty( $this->get_layout_id( $this->tab ) ) ) {
			$classes[] = 'thim-ekit-template';
		}

		return $classes;
	}

	public function is_modules_view() {
		return isset( $_GET['thim_elementor_kit'] );
	}

	public function is_editor_preview() {
		return \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || is_preview();
	}

	public function get_layout_id( $type ) {
		if ( isset( $this->layouts_cache[ $type ] ) ) {
			return $this->layouts_cache[ $type ];
		}

		$cache               = Cache::instance();
		$conditions_data     = $cache->get( $type );
		$sorted_data         = array();
		$conditions_priority = array();

		foreach ( $conditions_data as $layout_id => $conditions ) {
			$post = get_post( $layout_id );

			if ( ! $post ) {
				continue;
			}

			if ( ! empty( $conditions ) ) {
				foreach ( $conditions as $condition ) {
					$is = function () use ( $condition ) {
						return apply_filters( 'thim_ekit/modules/is', $this->is( $condition ), $condition, $this->tab );
					};
					if (  $is() && 'publish' === $post->post_status ) {
						$sorted_data[ $layout_id ][ $condition['comparison'] ][] = $this->priority( $condition['type'] );
					}
				}
			}
		}

		foreach ( $sorted_data as $post_id => $conditions ) {
			if ( isset( $conditions['include'] ) ) {
				foreach ( $conditions['include'] as $priority ) {
					$conditions_priority[ $post_id ] = $priority;
				}
			}

			if ( isset( $conditions['exclude'] ) ) {
				foreach ( $conditions['exclude'] as $priority ) {
					unset( $conditions_priority[ $post_id ] );
				}
			}
		}

		asort( $conditions_priority );

		$conditions_priority = array_flip( $conditions_priority );

		$this->layouts_cache[ $type ] = end( $conditions_priority );

		return $this->layouts_cache[ $type ];
	}

	public function is( $condition ) {
		return false;
	}

	public function priority( $type ) {
		return 100;
	}

	public function get_conditions() {
		return array();
	}

	public function add_meta_box() {}

	public function render_meta_box( $post ) {
		wp_nonce_field( 'thim_ekit_meta_box', 'thim_ekit_meta_box_nonce' );
	}

	public function save_meta_boxes( $post_id = 0, $post = null ) {
		if ( ! isset( $_POST['thim_ekit_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['thim_ekit_meta_box_nonce'], 'thim_ekit_meta_box' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->save_meta_box( $post_id );
	}

	public function save_meta_box( $post_id ) {}

	public function before_editor_preview_query() {
		$current_post_id = get_the_ID();
		$document        = \Elementor\Plugin::$instance->documents->get_doc_or_auto_save( $current_post_id );

		if ( ! $document || ! $document instanceof \Elementor\Core\Base\Document ) {
			return;
		}

		$type = get_post_meta( $current_post_id, Custom_Post_Type::TYPE, true );

		if ( $type && $type === $this->tab && method_exists( $this, 'before_preview_query' ) ) {
			$this->before_preview_query();
		}
	}

	public function after_editor_preview_query() {
		\Elementor\Plugin::instance()->db->restore_current_query();
	}
}
