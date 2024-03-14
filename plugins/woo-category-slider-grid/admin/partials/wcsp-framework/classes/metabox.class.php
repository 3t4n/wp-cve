<?php

/**
 * Framework metabox.class file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Metabox' ) ) {
	/**
	 *
	 * Metabox Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Metabox extends SP_WCS_Abstract {

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
			'class'              => '',
			'post_type'          => 'post',
			'data_type'          => 'serialize',
			'context'            => 'advanced',
			'priority'           => 'default',
			'exclude_post_types' => array(),
			'page_templates'     => '',
			'post_formats'       => '',
			'show_restore'       => false,
			'enqueue_webfont'    => true,
			'async_webfont'      => false,
			'output_css'         => true,
			'theme'              => 'dark',
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
			$this->args           = apply_filters( "spf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections       = apply_filters( "spf_{$this->unique}_sections", $params['sections'], $this );
			$this->post_type      = ( is_array( $this->args['post_type'] ) ) ? $this->args['post_type'] : array_filter( (array) $this->args['post_type'] );
			$this->post_formats   = ( is_array( $this->args['post_formats'] ) ) ? $this->args['post_formats'] : array_filter( (array) $this->args['post_formats'] );
			$this->page_templates = ( is_array( $this->args['page_templates'] ) ) ? $this->args['page_templates'] : array_filter( (array) $this->args['page_templates'] );
			$this->pre_fields     = $this->pre_fields( $this->sections );

			add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
			add_action( 'save_post', array( &$this, 'save_meta_box' ), 10, 2 );

			if ( ! empty( $this->page_templates ) || ! empty( $this->post_formats ) ) {
				foreach ( $this->post_type as $post_type ) {
					add_filter( 'postbox_classes_' . $post_type . '_' . $this->unique, array( &$this, 'add_metabox_classes' ) );
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

				$classes[] = 'spf-post-formats';

				$key = array_search( 'standard', $this->post_formats, true );
				if ( ( $key ) !== false ) {
					$this->post_formats[ $key ] = 'default';
				}

				foreach ( $this->post_formats as $format ) {
					$classes[] = 'spf-post-format-' . $format;
				}

				if ( ! in_array( $saved_post_format, $this->post_formats, true ) ) {
					$classes[] = 'spf-hide';
				} else {
					$classes[] = 'spf-show';
				}
			}

			if ( ! empty( $this->page_templates ) ) {

				$saved_template = ( is_object( $post ) && ! empty( $post->page_template ) ) ? $post->page_template : 'default';

				$classes[] = 'spf-page-templates';

				foreach ( $this->page_templates as $template ) {
					$classes[] = 'spf-page-' . preg_replace( '/[^a-zA-Z0-9]+/', '-', strtolower( $template ) );
				}

				if ( ! in_array( $saved_template, $this->page_templates, true ) ) {
					$classes[] = 'spf-hide';
				} else {
					$classes[] = 'spf-show';
				}
			}

			return $classes;

		}

		/**
		 * Add metabox
		 *
		 * @param array $post_type The post types.
		 */
		public function add_meta_box( $post_type ) {

			if ( ! in_array( $post_type, $this->args['exclude_post_types'], true ) ) {
				add_meta_box( $this->unique, $this->args['title'], array( &$this, 'add_meta_box_content' ), $this->post_type, $this->args['context'], $this->args['priority'], $this->args );
			}

		}

		/**
		 * Get default value.
		 *
		 * @param array $field The field value.
		 * @return mixed
		 */
		public function get_default( $field ) {

			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : '';
			$default = ( isset( $field['default'] ) ) ? $field['default'] : $default;

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

			$value = '';

			if ( is_object( $post ) && ! empty( $field['id'] ) ) {

				if ( 'serialize' !== $this->args['data_type'] ) {
					$meta  = get_post_meta( $post->ID, $field['id'] );
					$value = ( isset( $meta[0] ) ) ? $meta[0] : null;
				} else {
					$meta  = get_post_meta( $post->ID, $this->unique, true );
					$value = ( isset( $meta[ $field['id'] ] ) ) ? $meta[ $field['id'] ] : null;
				}

				$default = $this->get_default( $field );
				$value   = ( isset( $value ) ) ? $value : $default;

			}

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

			$has_nav  = ( count( $this->sections ) > 1 && 'side' !== $this->args['context'] ) ? true : false;
			$show_all = ( ! $has_nav ) ? ' spf-show-all' : '';
			$errors   = ( is_object( $post ) ) ? get_post_meta( $post->ID, '_spf_errors', true ) : array();
			$errors   = ( ! empty( $errors ) ) ? $errors : array();

			if ( is_object( $post ) && ! empty( $errors ) ) {
				delete_post_meta( $post->ID, '_spf_errors' );
			}

			wp_nonce_field( 'spf_metabox_nonce', 'spf_metabox_nonce' );

			echo '<div class="spf spf-theme-' . esc_attr( $this->args['theme'] ) . ' ' . esc_attr( $this->args['class'] ) . ' spf-metabox">';

			echo '<div class="spf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="spf-nav spf-nav-metabox" data-unique="' . esc_attr( $this->unique ) . '">';

					echo '<ul>';
					$tab_key = 1;
				foreach ( $this->sections as $section ) {

						$tab_error = ( ! empty( $errors['sections'][ $tab_key ] ) ) ? '<i class="spf-label-error spf-error">!</i>' : '';
						$tab_icon  = ( ! empty( $section['icon'] ) ) ? '<i class="spf-icon ' . $section['icon'] . '"></i>' : '';

						echo '<li class="' . esc_attr( $this->unique ) . '_' . esc_attr( $tab_key ) . '"><a href="#" data-section="' . esc_attr( $this->unique ) . '_' . esc_attr( $tab_key ) . '">' . wp_kses_post( $tab_icon . $section['title'] . $tab_error ) . '</a></li>';

						$tab_key++;
				}
					echo '</ul>';

						echo '</div>';

			}

					echo '<div class="spf-content">';

						echo '<div class="spf-sections">';

						$section_key = 1;

			foreach ( $this->sections as $section ) {

				$onload = ( ! $has_nav ) ? ' spf-onload' : '';

				echo '<div id="spf-section-' . esc_attr( $this->unique ) . '_' . esc_attr( $section_key ) . '" class="spf-section' . esc_attr( $onload ) . '">';

				$section_icon  = ( ! empty( $section['icon'] ) ) ? '<i class="spf-icon ' . $section['icon'] . '"></i>' : '';
				$section_title = ( ! empty( $section['title'] ) ) ? $section['title'] : '';

				echo ( $section_title || $section_icon ) ? '<div class="spf-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						if ( ! empty( $field['id'] ) && ! empty( $errors['fields'][ $field['id'] ] ) ) {
							$field['_error'] = $errors['fields'][ $field['id'] ];
						}

						SP_WCS::field( $field, $this->get_meta_value( $field ), $this->unique, 'metabox' );

					}
				} else {

					echo '<div class="spf-no-option spf-text-muted">' . esc_html__( 'No option provided by developer.', 'woo-category-slider-grid' ) . '</div>';

				}

				echo '</div>';

				$section_key++;
			}

			echo '</div>';

			echo '<a class="btn btn-success" id="sp-wcsp-show-preview" data-id="' . esc_attr( $post->ID ) . '"href=""> <i class="fa fa-eye" aria-hidden="true"></i> ' . esc_html( 'Show Preview' ) . '</a>';

			echo '<div class="clear"></div>';

			if ( ! empty( $this->args['show_restore'] ) ) {

				echo '<div class=" spf-metabox-restore">';
				echo '<label>';
				echo '<input type="checkbox" name="' . esc_attr( $this->unique ) . '[_restore]" />';
				echo '<span class="button spf-button-restore">' . esc_html__( 'Restore', 'woo-category-slider-grid' ) . '</span>';
				echo '<span class="button spf-button-cancel">' . sprintf( '<small>( %s )</small> %s', esc_html__( 'update post for restore ', 'woo-category-slider-grid' ), esc_html__( 'Cancel', 'woo-category-slider-grid' ) ) . '</span>';
				echo '</label>';
				echo '</div>';

			}

					echo '</div>';

					echo ( $has_nav ) ? '<div class="spf-nav-background"></div>' : '';

					echo '<div class="clear"></div>';

				echo '</div>';

			echo '</div>';

		}

		/**
		 * Save metabox.
		 *
		 * @param array  $post_id The post IDs.
		 * @param object $post  post.
		 * @return void
		 */
		public function save_meta_box( $post_id, $post ) {

			if ( wp_verify_nonce( spf_get_var( 'spf_metabox_nonce' ), 'spf_metabox_nonce' ) ) {

				$errors  = array();
				$request = spf_get_var( $this->unique );

				if ( ! empty( $request ) ) {

					// ignore _nonce.
					if ( isset( $request['_nonce'] ) ) {
						unset( $request['_nonce'] );
					}

					// sanitize and validate.
					$section_key = 1;
					foreach ( $this->sections as $section ) {

						if ( ! empty( $section['fields'] ) ) {

							foreach ( $section['fields'] as $field ) {

								if ( ! empty( $field['id'] ) ) {

									// sanitize.
									if ( ! empty( $field['sanitize'] ) ) {

										$sanitize                = $field['sanitize'];
										$value_sanitize          = isset( $request[ $field['id'] ] ) ? $request[ $field['id'] ] : '';
										$request[ $field['id'] ] = call_user_func( $sanitize, $value_sanitize );

									}

									// validate.
									if ( ! empty( $field['validate'] ) ) {

										$validate       = $field['validate'];
										$value_validate = isset( $request[ $field['id'] ] ) ? $request[ $field['id'] ] : '';
										$has_validated  = call_user_func( $validate, $value_validate );

										if ( ! empty( $has_validated ) ) {

											$errors['sections'][ $section_key ] = true;
											$errors['fields'][ $field['id'] ]   = $has_validated;
											$request[ $field['id'] ]            = $this->get_meta_value( $field );

										}
									}

									// auto sanitize.
									if ( ! isset( $request[ $field['id'] ] ) || is_null( $request[ $field['id'] ] ) ) {
										$request[ $field['id'] ] = '';
									}
								}
							}
						}

						$section_key++;
					}

					$request = apply_filters( "spf_{$this->unique}_save", $request, $post_id, $this );

					do_action( "spf_{$this->unique}_save_before", $request, $post_id, $this );

					if ( empty( $request ) || ! empty( $request['_restore'] ) ) {

						if ( 'serialize' !== $this->args['data_type'] ) {
							foreach ( $request as $key => $value ) {
								delete_post_meta( $post_id, $key );
							}
						} else {
							delete_post_meta( $post_id, $this->unique );
						}
					} else {

						if ( 'serialize' !== $this->args['data_type'] ) {
							foreach ( $request as $key => $value ) {
								update_post_meta( $post_id, $key, $value );
							}
						} else {
							update_post_meta( $post_id, $this->unique, $request );
						}

						if ( ! empty( $errors ) ) {
							update_post_meta( $post_id, '_spf_errors', $errors );
						}
					}

					do_action( "spf_{$this->unique}_saved", $request, $post_id, $this );

					do_action( "spf_{$this->unique}_save_after", $request, $post_id, $this );

				}
			}
		}
	}
}
