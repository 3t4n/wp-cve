<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Shortcoder Class
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Shortcoder' ) ) {
	class KIANFR_Shortcoder extends KIANFR_Abstract
	{

		// constans
		public $unique       = '';
		public $abstract     = 'shortcoder';
		public $blocks       = [];
		public $sections     = [];
		public $pre_tabs     = [];
		public $pre_sections = [];
		public $args         = [
			'button_title'   => 'Add Shortcode',
			'select_title'   => 'Select a shortcode',
			'insert_title'   => 'Insert Shortcode',
			'show_in_editor' => true,
			'show_in_custom' => false,
			'defaults'       => [],
			'class'          => '',
			'gutenberg'      => [
				'title'       => 'KIANFR Shortcodes',
				'description' => 'KIANFR Shortcode Block',
				'icon'        => 'screenoptions',
				'category'    => 'widgets',
				'keywords'    => [ 'shortcode', 'kianfr', 'insert' ],
				'placeholder' => 'Write shortcode here...',
			],
		];

		// run shortcode construct
		public function __construct( $key, $params = [] )
		{
			$this->unique       = $key;
			$this->args         = apply_filters( "kianfr_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections     = apply_filters( "kianfr_{$this->unique}_sections", $params['sections'], $this );
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			add_action( 'admin_footer', [ $this, 'add_footer_modal_shortcode' ] );
			add_action( 'customize_controls_print_footer_scripts', [ $this, 'add_footer_modal_shortcode' ] );
			add_action( 'wp_ajax_kianfr-get-shortcode-' . $this->unique, [ $this, 'get_shortcode' ] );

			if ( ! empty( $this->args['show_in_editor'] ) ) {
				KIANFR::$shortcode_instances[ $this->unique ] = wp_parse_args( [
					'hash'     => md5( $key ),
					'modal_id' => $this->unique,
				], $this->args );

				// elementor editor support
				if ( KIANFR::is_active_plugin( 'elementor/elementor.php' ) ) {
					add_action( 'elementor/editor/before_enqueue_scripts', [ 'KIANFR', 'add_admin_enqueue_scripts' ] );
					add_action( 'elementor/editor/footer', [ 'KIANFR_Field_icon', 'add_footer_modal_icon' ] );
					add_action( 'elementor/editor/footer', [ $this, 'add_footer_modal_shortcode' ] );
				}
			}
		}

		// instance
		public static function instance( $key, $params = [] )
		{
			return new self( $key, $params );
		}

		public function pre_tabs( $sections )
		{
			$result  = [];
			$parents = [];
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count ++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], [ 'priority' => 'ASC' ], 'ASC', true );
				}
				$result[] = $section;
				$count ++;
			}

			return wp_list_sort( $result, [ 'priority' => 'ASC' ], 'ASC', true );
		}

		public function pre_sections( $sections )
		{
			$result = [];

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$result[] = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		// get default value
		public function get_default( $field )
		{
			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;
		}

		public function add_footer_modal_shortcode()
		{
			if ( ! wp_script_is( 'kianfr' ) ) {
				return;
			}

			$class        = ( $this->args['class'] ) ? ' ' . esc_attr( $this->args['class'] ) : '';
			$has_select   = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$single_usage = ( ! $has_select ) ? ' kianfr-shortcode-single' : '';
			$hide_header  = ( ! $has_select ) ? ' hidden' : '';

			?>
            <div id="kianfr-modal-<?php
			echo esc_attr( $this->unique ); ?>" class="wp-core-ui kianfr-modal kianfr-shortcode hidden<?php
			echo esc_attr( $single_usage . $class ); ?>" data-modal-id="<?php
			echo esc_attr( $this->unique ); ?>" data-nonce="<?php
			echo esc_attr( wp_create_nonce( 'kianfr_shortcode_nonce' ) ); ?>">
                <div class="kianfr-modal-table">
                    <div class="kianfr-modal-table-cell">
                        <div class="kianfr-modal-overlay"></div>
                        <div class="kianfr-modal-inner">
                            <div class="kianfr-modal-title">
								<?php
								echo $this->args['button_title']; ?>
                                <div class="kianfr-modal-close"></div>
                            </div>
							<?php

							echo '<div class="kianfr-modal-header' . esc_attr( $hide_header ) . '">';
							echo '<select>';
							echo ( $has_select ) ? '<option value="">' . esc_attr( $this->args['select_title'] ) . '</option>' : '';

							$tab_key = 1;

							foreach ( $this->pre_tabs as $tab ) {
								if ( ! empty( $tab['subs'] ) ) {
									echo '<optgroup label="' . esc_attr( $tab['title'] ) . '">';

									foreach ( $tab['subs'] as $sub ) {
										$view      = ( ! empty( $sub['view'] ) ) ? ' data-view="' . esc_attr( $sub['view'] ) . '"' : '';
										$shortcode = ( ! empty( $sub['shortcode'] ) ) ? ' data-shortcode="' . esc_attr( $sub['shortcode'] ) . '"' : '';
										$group     = ( ! empty( $sub['group_shortcode'] ) ) ? ' data-group="' . esc_attr( $sub['group_shortcode'] ) . '"' : '';

										echo '<option value="' . esc_attr( $tab_key ) . '"' . $view . $shortcode . $group . '>' . esc_attr( $sub['title'] ) . '</option>';

										$tab_key ++;
									}

									echo '</optgroup>';
								} else {
									$view      = ( ! empty( $tab['view'] ) ) ? ' data-view="' . esc_attr( $tab['view'] ) . '"' : '';
									$shortcode = ( ! empty( $tab['shortcode'] ) ) ? ' data-shortcode="' . esc_attr( $tab['shortcode'] ) . '"' : '';
									$group     = ( ! empty( $tab['group_shortcode'] ) ) ? ' data-group="' . esc_attr( $tab['group_shortcode'] ) . '"' : '';

									echo '<option value="' . esc_attr( $tab_key ) . '"' . $view . $shortcode . $group . '>' . esc_attr( $tab['title'] ) . '</option>';

									$tab_key ++;
								}
							}

							echo '</select>';
							echo '</div>';

							?>
                            <div class="kianfr-modal-content">
                                <div class="kianfr-modal-loading">
                                    <div class="kianfr-loading"></div>
                                </div>
                                <div class="kianfr-modal-load"></div>
                            </div>
                            <div class="kianfr-modal-insert-wrapper hidden"><a href="#"
                                                                               class="button button-primary kianfr-modal-insert"><?php
									echo $this->args['insert_title']; ?></a></div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

		public function get_shortcode()
		{
			ob_start();

			$nonce         = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$shortcode_key = ( ! empty( $_POST['shortcode_key'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shortcode_key'] ) ) : '';

			if ( ! empty( $shortcode_key ) && wp_verify_nonce( $nonce, 'kianfr_shortcode_nonce' ) ) {
				$unallows  = [ 'group', 'repeater', 'sorter' ];
				$section   = $this->pre_sections[ $shortcode_key - 1 ];
				$shortcode = ( ! empty( $section['shortcode'] ) ) ? $section['shortcode'] : '';
				$view      = ( ! empty( $section['view'] ) ) ? $section['view'] : 'normal';

				if ( ! empty( $section ) ) {
					//
					// View: normal
					if ( ! empty( $section['fields'] ) && $view !== 'repeater' ) {
						echo '<div class="kianfr-fields">';

						echo ( ! empty( $section['description'] ) ) ? '<div class="kianfr-field kianfr-section-description">' . $section['description'] . '</div>' : '';

						foreach ( $section['fields'] as $field ) {
							if ( in_array( $field['type'], $unallows ) ) {
								$field['_notice'] = true;
							}

							// Extra tag improves for spesific fields (border, spacing, dimensions etc...)
							$field['tag_prefix'] = ( ! empty( $field['tag_prefix'] ) ) ? $field['tag_prefix'] . '_' : '';

							$field_default = ( isset( $field['id'] ) ) ? $this->get_default( $field ) : '';

							KIANFR::field( $field, $field_default, $shortcode, 'shortcode' );
						}

						echo '</div>';
					}

					//
					// View: group and repeater fields
					$repeatable_fields = ( $view === 'repeater' && ! empty( $section['fields'] ) ) ? $section['fields'] : [];
					$repeatable_fields = ( $view === 'group' && ! empty( $section['group_fields'] ) ) ? $section['group_fields'] : $repeatable_fields;

					if ( ! empty( $repeatable_fields ) ) {
						$button_title    = ( ! empty( $section['button_title'] ) ) ? ' ' . $section['button_title'] : esc_html__( 'Add New', 'kianfr' );
						$inner_shortcode = ( ! empty( $section['group_shortcode'] ) ) ? $section['group_shortcode'] : $shortcode;

						echo '<div class="kianfr--repeatable">';

						echo '<div class="kianfr--repeat-shortcode">';

						echo '<div class="kianfr-repeat-remove fas fa-times"></div>';

						echo '<div class="kianfr-fields">';

						foreach ( $repeatable_fields as $field ) {
							if ( in_array( $field['type'], $unallows ) ) {
								$field['_notice'] = true;
							}

							// Extra tag improves for spesific fields (border, spacing, dimensions etc...)
							$field['tag_prefix'] = ( ! empty( $field['tag_prefix'] ) ) ? $field['tag_prefix'] . '_' : '';

							$field_default = ( isset( $field['id'] ) ) ? $this->get_default( $field ) : '';

							KIANFR::field( $field, $field_default, $inner_shortcode . '[0]', 'shortcode' );
						}

						echo '</div>';

						echo '</div>';

						echo '</div>';

						echo '<div class="kianfr--repeat-button-block"><a class="button kianfr--repeat-button" href="#"><i class="fas fa-plus-circle"></i> ' . $button_title . '</a></div>';
					}
				}
			} else {
				echo '<div class="kianfr-field kianfr-error-text">' . esc_html__( 'Error: Invalid nonce verification.', 'kianfr' ) . '</div>';
			}

			wp_send_json_success( [ 'content' => ob_get_clean() ] );
		}

		// Once editor setup for gutenberg and media buttons
		public static function once_editor_setup()
		{
			if ( function_exists( 'register_block_type' ) ) {
				add_action( 'enqueue_block_editor_assets', [ 'KIANFR_Shortcoder', 'add_guteberg_blocks' ] );
			}

			if ( kianfr_wp_editor_api() ) {
				add_action( 'media_buttons', [ 'KIANFR_Shortcoder', 'add_media_buttons' ] );
			}
		}

		// Add gutenberg blocks.
		public static function add_guteberg_blocks()
		{
			$depends = [ 'wp-blocks', 'wp-element', 'wp-components' ];

			if ( wp_script_is( 'wp-edit-widgets' ) ) {
				$depends[] = 'wp-edit-widgets';
			} else {
				$depends[] = 'wp-edit-post';
			}

			wp_enqueue_script( 'kianfr-gutenberg-block', KIANFR::include_plugin_url( 'assets/js/gutenberg.js' ), $depends );

			wp_localize_script( 'kianfr-gutenberg-block', 'kianfr_gutenberg_blocks', KIANFR::$shortcode_instances );

			foreach ( KIANFR::$shortcode_instances as $value ) {
				register_block_type( 'kianfr-gutenberg-block/block-' . $value['hash'], [
					'editor_script' => 'kianfr-gutenberg-block',
				] );
			}
		}

		// Add media buttons
		public static function add_media_buttons( $editor_id )
		{
			foreach ( KIANFR::$shortcode_instances as $value ) {
				echo '<a href="#" class="button button-primary kianfr-shortcode-button" data-editor-id="' . esc_attr( $editor_id ) . '" data-modal-id="' . esc_attr( $value['modal_id'] ) . '">' . $value['button_title'] . '</a>';
			}
		}

	}
}
