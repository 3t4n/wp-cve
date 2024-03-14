<?php
/**
 * Framework metabox.class file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin/views.
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;
use ShapedPlugin\WooProductSlider\Frontend\Frontend;
use ShapedPlugin\WooProductSlider\Frontend\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Metabox' ) ) {
	/**
	 *
	 * Metabox Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Metabox extends SPF_WPSP_Abstract {
		/**
		 * Property.
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Properties for metabox.
		 *
		 * @var string
		 */
		public $abstract = 'metabox';
		/**
		 * Pre fields property.
		 *
		 * @var array
		 */
		public $pre_fields = array();
		/**
		 * Sections.
		 *
		 * @var array
		 */
		public $sections = array();
		/**
		 * Post Types.
		 *
		 * @var array
		 */
		public $post_type = array();
		/**
		 * Post_formats
		 *
		 * @var array
		 */
		public $post_formats = array();
		/**
		 * Page_templates
		 *
		 * @var array
		 */
		public $page_templates = array();

		/**
		 * Arguments.
		 *
		 * @var array
		 */
		public $args = array(
			'title'              => '',
			'post_type'          => 'post',
			'data_type'          => 'serialize',
			'context'            => 'advanced',
			'priority'           => 'default',
			'exclude_post_types' => array(),
			'page_templates'     => '',
			'post_formats'       => '',
			'show_reset'         => false,
			'show_restore'       => false,
			'enqueue_webfont'    => true,
			'async_webfont'      => false,
			'spwps_shortcode'    => true,
			'output_css'         => true,
			'nav'                => 'normal',
			'theme'              => 'dark',
			'class'              => '',
			'defaults'           => array(),
		);

		/**
		 * Run metabox construct.
		 *
		 * @param mixed $key The metabox key.
		 * @param array $params The metabox parameters.
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique         = $key;
			$this->args           = apply_filters( "spwps_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections       = apply_filters( "spwps_{$this->unique}_sections", $params['sections'], $this );
			$this->post_type      = ( is_array( $this->args['post_type'] ) ) ? $this->args['post_type'] : array_filter( (array) $this->args['post_type'] );
			$this->post_formats   = ( is_array( $this->args['post_formats'] ) ) ? $this->args['post_formats'] : array_filter( (array) $this->args['post_formats'] );
			$this->page_templates = ( is_array( $this->args['page_templates'] ) ) ? $this->args['page_templates'] : array_filter( (array) $this->args['page_templates'] );
			$this->pre_fields     = $this->pre_fields( $this->sections );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );
			add_action( 'edit_attachment', array( $this, 'save_meta_box' ) );
			// Preview ajax.
			add_action( 'wp_ajax_spwps_preview_meta_box', array( $this, 'spwps_preview_meta_box' ) );

			if ( ! empty( $this->page_templates ) || ! empty( $this->post_formats ) || ! empty( $this->args['class'] ) ) {
				foreach ( $this->post_type as $post_type ) {
					add_filter( 'postbox_classes_' . $post_type . '_' . $this->unique, array( $this, 'add_metabox_classes' ) );
				}
			}

			// wp enqeueu for typography and output css.
			parent::__construct();

		}

		/**
		 * Instance.
		 *
		 * @param string $key Key of the metabox.
		 * @param array  $params Array of parameters.
		 * @return statement
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}
		/**
		 * Pre fields
		 *
		 * @param array $sections The sections.
		 * @return statement
		 */
		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;

		}
		/**
		 * Add metabox classes.
		 *
		 * @param array $classes The metabox classes.
		 */
		public function add_metabox_classes( $classes ) {

			global $post;

			if ( ! empty( $this->post_formats ) ) {

				$saved_post_format = ( is_object( $post ) ) ? get_post_format( $post ) : false;
				$saved_post_format = ( ! empty( $saved_post_format ) ) ? $saved_post_format : 'default';

				$classes[] = 'spwps-post-formats';

				// Sanitize post format for standard to default.
				if ( false !== ( $key = array_search( 'standard', $this->post_formats ) ) ) {
					$this->post_formats[ $key ] = 'default';
				}

				foreach ( $this->post_formats as $format ) {
					$classes[] = 'spwps-post-format-' . $format;
				}

				if ( ! in_array( $saved_post_format, $this->post_formats ) ) {
					$classes[] = 'spwps-metabox-hide';
				} else {
					$classes[] = 'spwps-metabox-show';
				}
			}

			if ( ! empty( $this->page_templates ) ) {

				$saved_template = ( is_object( $post ) && ! empty( $post->page_template ) ) ? $post->page_template : 'default';

				$classes[] = 'spwps-page-templates';

				foreach ( $this->page_templates as $template ) {
					$classes[] = 'spwps-page-' . preg_replace( '/[^a-zA-Z0-9]+/', '-', strtolower( $template ) );
				}

				if ( ! in_array( $saved_template, $this->page_templates ) ) {
					$classes[] = 'spwps-metabox-hide';
				} else {
					$classes[] = 'spwps-metabox-show';
				}
			}

			if ( ! empty( $this->args['class'] ) ) {
				$classes[] = $this->args['class'];
			}

			return $classes;

		}

		/**
		 * Add metabox
		 *
		 * @param array $post_type The post types.
		 */
		public function add_meta_box( $post_type ) {

			if ( ! in_array( $post_type, $this->args['exclude_post_types'] ) ) {
				add_meta_box( $this->unique, $this->args['title'], array( $this, 'add_meta_box_content' ), $this->post_type, $this->args['context'], $this->args['priority'], $this->args );
			}

		}

			/**
			 * Get default value.
			 *
			 * @param array $field The field value.
			 * @return mixed
			 */
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Get meta value.
		 *
		 * @param object $field The field.
		 * @return statement
		 */
		public function get_meta_value( $field ) {

			global $post;

			$value = null;

			if ( is_object( $post ) && ! empty( $field['id'] ) ) {

				if ( 'serialize' !== $this->args['data_type'] ) {
					$meta  = get_post_meta( $post->ID, $field['id'] );
					$value = ( isset( $meta[0] ) ) ? $meta[0] : null;
				} else {
					$meta  = get_post_meta( $post->ID, $this->unique, true );
					$value = ( isset( $meta[ $field['id'] ] ) ) ? $meta[ $field['id'] ] : null;
				}
			} elseif ( 'tabbed' === $field['type'] ) {
				$value = get_post_meta( $post->ID, $this->unique, true );
			}

			$default = ( isset( $field['id'] ) ) ? $this->get_default( $field ) : '';
			$value   = ( isset( $value ) ) ? $value : $default;

			return $value;

		}

		/**
		 * Add metabox content
		 *
		 * @param object $post The post.
		 * @param array  $callback The callback function.
		 * @return void
		 */
		public function add_meta_box_content( $post, $callback ) {

			global $post;

			$has_nav        = ( count( $this->sections ) > 1 && 'side' !== $this->args['context'] ) ? true : false;
			$show_all       = ( ! $has_nav ) ? ' spwps-show-all' : '';
			$post_type      = ( is_object( $post ) ) ? $post->post_type : '';
			$errors         = ( is_object( $post ) ) ? get_post_meta( $post->ID, '_spwps_errors_' . $this->unique, true ) : array();
			$errors         = ( ! empty( $errors ) ) ? $errors : array();
			$theme          = ( $this->args['theme'] ) ? ' spwps-theme-' . $this->args['theme'] : '';
			$nav_type       = ( 'inline' === $this->args['nav'] ) ? 'inline' : 'normal';
			$shortcode_show = isset( $this->args['spwps_shortcode'] ) ? $this->args['spwps_shortcode'] : false;
			$is_preview     = isset( $this->args['preview'] ) ? $this->args['preview'] : false;

			if ( is_object( $post ) && ! empty( $errors ) ) {
				delete_post_meta( $post->ID, '_spwps_errors_' . $this->unique );
			}
			wp_nonce_field( 'spwps_metabox_nonce', 'spwps_metabox_nonce' . $this->unique );
			if ( $is_preview ) {
				?>
			<div id="spwps_live_preview">
				<div class="postbox-header ">
					<h2>Live Preview</h2>
				</div>
				<div class="inside">
					<div class="spwps spwps-metabox spwps-theme-dark">
						<div class="spwps-wrapper spwps-show-all">
							<div class="spwps-content">
								<div class="spwps-sections">
									<div class="spwps-field spwps-field-preview">
										<div class="spwps-preview-box">
											<div id="spwps-preview-box">
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
				<?php
			}
			echo '<div class="spwps spwps-metabox' . esc_attr( $theme ) . '">';
			$current_screen        = get_current_screen();
			$the_current_post_type = $current_screen->post_type;
			if ( 'sp_wps_shortcodes' === $the_current_post_type && $shortcode_show ) {
				echo '<div class="sp-wpsp-mbf-banner">';
				echo '<div class="sp-wpsp-mbf-logo"><img src="' . esc_url( SP_WPS_URL ) . 'Admin/assets/images/wps-logo.svg" alt="Product Slider for WooCommerce"></div>';
				echo '<div class="sp-wpsp-submit-options"><span class="support-area"><i class="fa fa-life-ring"></i> Support</span><div class="spwps-help-text spwps-support"><div class="spwps-info-label">Documentation</div>Check out our documentation and more information about what you can do with the Product Slider for WooCommerce.<a class="spwps-open-docs browser-docs" href="https://docs.shapedplugin.com/docs/woocommerce-product-slider/overview/" target="_blank">Browse Docs</a><div class="spwps-info-label">Need Help or Missing a Feature?</div>Feel free to get help from our friendly support team or request a new feature if needed. We appreciate your suggestions to make the plugin better.<a class="spwps-open-docs support" href="https://shapedplugin.com/create-new-ticket/" target="_blank">Get Help</a><a class="spwps-open-docs feature-request" href="https://shapedplugin.com/contact-us/" target="_blank">Request a Feature</a></div></div>';
				echo '</div>';
				?>
		<div class="wpspro_shortcode text-center">
		<div class="wpspro-col-lg-3">
		<div class="wpspro-after-copy-text wpspro-pagination-not-work"><i class="fa fa-check-circle"></i> The pagination will work in the frontend well. </div>
		<div class="wpspro-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>
		<div class="wpspro_shortcode_content">
			<h2 class="wpspro-shortcode-title"><?php esc_html_e( 'Shortcode', 'woo-product-slider' ); ?> </h2>
			<p><?php esc_html_e( 'Copy and paste this shortcode into your posts or pages:', 'woo-product-slider' ); ?></p>
			<div class="shortcode-wrap">
				<div class="spsc-code selectable">[woo_product_slider <?php echo 'id="' . esc_attr( $post->ID ) . '"'; ?>]</div>
			</div>
		</div>
	</div>
	<div class="wpspro-col-lg-3">
		<div class="wpspro_shortcode_content">
			<h2 class="wpspro-shortcode-title"><?php esc_html_e( 'Page Builders', 'woo-product-slider' ); ?> </h2>
			<p class="wpspro-page-builder-note"><?php echo wp_kses_post( 'Woo Product Slider has seamless integration with <b>Gutenberg</b>, Classic Editor, Elementor, Divi, Bricks, Beaver, Oxygen, WPBakery Builder, etc.' ); ?></p>
		</div>
	</div>
	<div class="wpspro-col-lg-3">
		<div class="wpspro_shortcode_content">
			<h2 class="wpspro-shortcode-title"><?php esc_html_e( 'Template Include', 'woo-product-slider' ); ?> </h2>
			<p><?php esc_html_e( 'Paste the PHP code into your template file:', 'woo-product-slider' ); ?></p>
			<div class="shortcode-wrap">
				<div class="spsc-code selectable">&lt;?php woo_product_slider( <?php echo esc_attr( $post->ID ); ?> ); ?&gt;</div>
			</div>
		</div>
	</div>
</div>
<div class="wpspro_shortcode_divider"></div>
				<?php
			}
			echo '<div class="spwps-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="spwps-nav spwps-nav-' . esc_attr( $nav_type ) . ' spwps-nav-metabox">';

				echo '<ul>';

				$tab_key = 0;

				foreach ( $this->sections as $section ) {

					if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
						continue;
					}

					$tab_error = ( ! empty( $errors['sections'][ $tab_key ] ) ) ? '<i class="spwps-label-error spwps-error">!</i>' : '';
					$tab_icon  = ( ! empty( $section['icon'] ) ) ? '<i class="spwps-tab-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

					echo '<li><a href="#" data-section="' . esc_attr( $this->unique . '_' . $tab_key ) . '">' . wp_kses_post( $tab_icon . $section['title'] . $tab_error ) . '</a></li>';

					$tab_key++;

				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="spwps-content">';

			echo '<div class="spwps-sections">';

			$section_key = 0;

			foreach ( $this->sections as $section ) {

				if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
					continue;
				}

				$section_onload = ( ! $has_nav ) ? ' spwps-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="spwps-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

				echo '<div class="spwps-section hidden' . esc_attr( $section_onload . $section_class ) . '">';

				echo ( $section_title || $section_icon ) ? '<div class="spwps-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="spwps-field spwps-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						if ( ! empty( $field['id'] ) && ! empty( $errors['fields'][ $field['id'] ] ) ) {
							$field['_error'] = $errors['fields'][ $field['id'] ];
						}

						if ( ! empty( $field['id'] ) ) {
							$field['default'] = $this->get_default( $field );
						}

						SPF_WPSP::field( $field, $this->get_meta_value( $field ), $this->unique, 'metabox' );

					}
				} else {

					echo '<div class="spwps-no-option">' . esc_html__( 'No data available.', 'woo-product-slider' ) . '</div>';

				}

				echo '</div>';

				$section_key++;

			}

			echo '</div>';
			echo '<a class="btn btn-success" id="spwps-show-preview" data-id="' . esc_attr( $post->ID ) . '"href=""> <i class="fa fa-eye" aria-hidden="true"></i> Show Preview</a>';

			if ( ! empty( $this->args['show_restore'] ) || ! empty( $this->args['show_reset'] ) ) {

				echo '<div class="spwps-sections-reset">';
				echo '<label>';
				echo '<input type="checkbox" name="' . esc_attr( $this->unique ) . '[_reset]" />';
				echo '<span class="button spwps-button-reset">' . esc_html__( 'Reset', 'woo-product-slider' ) . '</span>';
				echo '<span class="button spwps-button-cancel">' . sprintf( '<small>( %s )</small> %s', esc_html__( 'update post', 'woo-product-slider' ), esc_html__( 'Cancel', 'woo-product-slider' ) ) . '</span>';
				echo '</label>';
				echo '</div>';

			}

			echo '</div>';

			echo ( $has_nav && 'normal' === $nav_type ) ? '<div class="spwps-nav-background"></div>' : '';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo '</div>';

		}

		/**
		 * Save metabox.
		 *
		 * @param array $post_id The post IDs.
		 * @return statement
		 */
		public function save_meta_box( $post_id ) {

			$count    = 1;
			$data     = array();
			$errors   = array();
			$noncekey = 'spwps_metabox_nonce' . $this->unique;
			$nonce    = ( ! empty( $_POST[ $noncekey ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ $noncekey ] ) ) : ''; // phpcs:ignore

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! wp_verify_nonce( $nonce, 'spwps_metabox_nonce' ) ) {
				return $post_id;
			}

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach.
			$request = ( ! empty( $_POST[ $this->unique ] ) ) ? $_POST[ $this->unique ] : array(); // phpcs:ignore
			if ( ! empty( $request ) ) {
				foreach ( $this->sections as $section ) {
					if ( ! empty( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							$this->process_field( $field, $request, $count, $data, $errors );
						}
					}
					$count++;
				}
			}

			$data = apply_filters( "spwps_{$this->unique}_save", $data, $post_id, $this );

			do_action( "spwps_{$this->unique}_save_before", $data, $post_id, $this );

			if ( empty( $data ) || ! empty( $request['_reset'] ) ) {

				if ( 'serialize' !== $this->args['data_type'] ) {
					foreach ( $data as $key => $value ) {
						delete_post_meta( $post_id, $key );
					}
				} else {
					delete_post_meta( $post_id, $this->unique );
				}
			} else {

				if ( 'serialize' !== $this->args['data_type'] ) {
					foreach ( $data as $key => $value ) {
						update_post_meta( $post_id, $key, $value );
					}
				} else {
					update_post_meta( $post_id, $this->unique, $data );
				}

				if ( ! empty( $errors ) ) {
					update_post_meta( $post_id, '_spwps_errors_' . $this->unique, $errors );
				}
			}

			do_action( "spwps_{$this->unique}_saved", $data, $post_id, $this );

			do_action( "spwps_{$this->unique}_save_after", $data, $post_id, $this );

		}

		/**
		 * Function Backed preview.
		 *
		 * @since 2.2.5
		 */
		public function spwps_preview_meta_box() {
			if ( ! isset( $_POST['ajax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), 'spwps_metabox_nonce' ) ) {
				return;
			}
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				$link = esc_url(
					add_query_arg(
						array(
							'tab'       => 'plugin-information',
							'plugin'    => 'woocommerce',
							'TB_iframe' => 'true',
							'width'     => '640',
							'height'    => '500',
						),
						admin_url( 'plugin-install.php' )
					)
				);
				echo '<div class="error"><p>You must install and activate <a class="thickbox open-plugin-details-modal" href="' . $link . '"><strong>WooCommerce</strong></a> plugin to make the <strong>Product Slider for WooCommerce</strong> work.</p></div>'; // phpcs:ignore
				die();
			}
			$count   = 1;
			$errors  = array();
			$setting = array();
			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach.
			$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
			parse_str( $data, $setting );
			// Shortcode id.
			$post_id = absint( $setting['post_ID'] );
			$title   = sanitize_textarea_field( $setting['post_title'] );
			$request = $setting['sp_wps_shortcode_options'];
			$data    = array(); // Sanitize data added here.

			if ( ! empty( $request ) ) {
				foreach ( $this->sections as $section ) {
					if ( ! empty( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							$this->process_field( $field, $request, $count, $data, $errors );
						}
					}
					$count++;
				}
			}
			ob_start();
			$shortcode_data = $data;
			$dynamic_style  = Frontend::load_dynamic_style( $post_id, $shortcode_data );
			echo '<style type="text/css">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';// phpcs:ignore
			Helper::spwps_html_show( $post_id, $shortcode_data, $title );
			echo ob_get_clean(); // phpcs:ignore
			die();
		}

		/**
		 * Process a field, handling tabbed fields if applicable.
		 *
		 * @param array $field   The field configuration.
		 * @param array $request The POST request data.
		 * @param int   $count   The count value.
		 * @param array $data    The data array to be populated.
		 * @param array $errors  The errors array to be populated.
		 */
		public function process_field( $field, $request, $count, &$data, &$errors ) {
			if ( 'tabbed' === $field['type'] && ! empty( $field['tabs'] ) ) {
				foreach ( $field['tabs'] as $tab ) {
					if ( ! empty( $tab['fields'] ) ) {
						foreach ( $tab['fields'] as $tab_field ) {
							$this->process_single_field( $tab_field, $request, $count, $data, $errors );
						}
					}
				}
			} else {
				$this->process_single_field( $field, $request, $count, $data, $errors );
			}
		}

		/**
		 * Process a single field, sanitizing and validating its value.
		 *
		 * @param array $field   The field configuration.
		 * @param array $request The POST request data.
		 * @param int   $count   The count value.
		 * @param array $data    The data array to be populated.
		 * @param array $errors  The errors array to be populated.
		 */
		public function process_single_field( $field, $request, $count, &$data, &$errors ) {
			if ( ! empty( $field['id'] ) && ! ( isset( $field['only_pro'] ) ) ) {
				$field_id    = $field['id'];
				$field_value = isset( $request[ $field_id ] ) ? $request[ $field_id ] : '';

				// Sanitize "post" request of field.
				if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
					$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );
				} else {
					$data[ $field_id ] = ( is_array( $field_value ) ) ? wp_kses_post_deep( $field_value ) : wp_kses_post( $field_value );
				}

				// Validate "post" request of field.
				if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
					$has_validated = call_user_func( $field['validate'], $field_value );

					if ( ! empty( $has_validated ) ) {
						$errors['sections'][ $count ]  = true;
						$errors['fields'][ $field_id ] = $has_validated;
						$data[ $field_id ]             = $this->get_meta_value( $field );
					}
				}
			}
		}
	}

}
