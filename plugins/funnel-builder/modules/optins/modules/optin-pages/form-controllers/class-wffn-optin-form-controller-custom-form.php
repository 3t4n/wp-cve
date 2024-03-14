<?php

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will control all custom form actions for optin submission
 * Class WFFN_Optin_Form_Controller_Custom_Form
 */
if ( ! class_exists( 'WFFN_Optin_Form_Controller_Custom_Form' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Form_Controller_Custom_Form extends WFFN_Optin_Form_Controller {

		private static $ins = null;
		public $slug = 'form';

		/**
		 * count form on page and create unique id
		 */
		private $render_count = 0;

		/**
		 * WFFN_Optin_Form_Controller_Custom_Form constructor.
		 */
		public function __construct() {
			add_shortcode( 'wfop_' . $this->slug, [ $this, 'add_optin_form_shortcode' ] );

			/**
			 * Redirect to next step if wffn_next_link is added in redirection url parameters.
			 */

			/**
			 * handle custom from post actions.
			 */
			add_action( 'wp_ajax_wffn_submit_custom_optin_form', array( $this, 'handle_submission' ) );
			add_action( 'wp_ajax_nopriv_wffn_submit_custom_optin_form', array( $this, 'handle_submission' ) );

			add_action( 'init', array( $this, 'maybe_handle_crm_return' ), 10 );


			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Form_Controller_Custom_Form|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return string
		 */
		public function get_form_group() {
			return 'custom_form';
		}

		/**
		 * Return title of this form builder controller
		 */
		public function get_title() {
			return __( 'Custom Form', 'funnel-builder' );
		}

		/**
		 * @return string|void
		 */
		public function get_form_shortcode() {
			return '[wffn_optin_' . $this->slug . ']';
		}


		/**
		 * @return bool|false|string
		 */
		public function add_optin_form_shortcode() {
			$optin_page_id = WFOPP_Core()->optin_pages->get_optin_id();

			if ( $optin_page_id > 0 ) {

				$optinPageId = $optin_page_id;
				if ( $optinPageId > 0 && intval( $optinPageId ) === intval( $optin_page_id ) ) {
					$optinFields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optin_page_id );

					if ( count( $optinFields ) > 0 ) {
						ob_start();
						$customizations = WFOPP_Core()->optin_pages->form_builder->get_form_customization_option( 'all', $optinPageId );
						$font_array     = [];
						if ( 'default' !== $customizations['input_font_family'] && 'inherit' !== $customizations['input_font_family'] ) {
							$font_array[] = $customizations['input_font_family'];
						}
						if ( 'default' !== $customizations['button_font_family'] && 'inherit' !== $customizations['button_font_family'] ) {
							$font_array[] = $customizations['button_font_family'];
						}
						if ( ! empty( $font_array ) ) {
							$font_array      = array_unique( $font_array );
							$font_string     = implode( '|', $font_array );
							$google_font_url = "//fonts.googleapis.com/css?family=" . $font_string;
							wp_enqueue_style( 'wfop-google-fonts', esc_url( $google_font_url ), array(), WFFN_VERSION, 'all' );
						}

						$class = '';

						if ( $customizations['show_input_label'] === 'no' ) {
							$class = "wfop_hide_label";
						}

						$get_embed_mode = WFOPP_Core()->optin_pages->get_embed_mode();

						/**
						 * Render popover for the preview mode
						 */
						if ( WFOPP_Core()->optin_pages->form_builder->is_preview ) {
							$this->frontend_render_form( $optinPageId, $get_embed_mode, $class );
						} else {
							//for inline mode, preview OR front
							$this->frontend_render_form( $optinPageId, 'inline', $class );
						}

						return ob_get_clean();
					}
				}
			}

			return false;
		}


		public function frontend_render_form( $optin_id = 0, $form_mode = 'inline', $class = '' ) {
			$status  = false;
			$optinid = intval( filter_input( INPUT_POST, 'optin_id', FILTER_SANITIZE_NUMBER_INT ) );
			if ( $optinid > 0 ) {
				$optin_id  = $optinid;
				$class     = filter_input( INPUT_POST, 'op_class' );
				$form_mode = filter_input( INPUT_POST, 'op_mode' );
				$status    = true;
			}

			if ( $optin_id <= 0 ) {
				return;
			}

			$class          = 'wfop-custom-form ' . $class;
			$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optin_id );
			$optin_layout   = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $optin_id );
			$customizations = WFOPP_Core()->optin_pages->form_builder->get_form_customization_option( 'all', $optin_id );
			$this->_output_form( $class, $optin_layout, $optin_id, $optin_settings, $form_mode, $customizations );
			$customizer_settings = WFOPP_Core()->optin_pages->form_builder->get_form_customization_option( 'all', $optin_id );
			$customizations      = wp_parse_args( $customizations, $customizer_settings );
			include WFOPP_Core()->optin_pages->get_module_path() . '/internal-css.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction

			if ( $status ) {
				exit;
			}
		}

		public function _output_form( $class, $optin_layout, $optinPageId, $optin_settings, $form_mode, $customizations ) {
			$this->render_count++;

            $submit_btn_text     = isset( $customizations['button_text'] ) ? $customizations['button_text'] : '';
			$subtitle            = isset( $customizations['subtitle'] ) ? $customizations['subtitle'] : '';
			$submitting_btn_text = isset( $customizations['button_submitting_text'] ) ? $customizations['button_submitting_text'] : 'Submitting...';
			$submit_btn_size     = isset( $customizations['button_size'] ) ? $customizations['button_size'] : 'med';
			$field_size          = isset( $customizations['field_size'] ) ? $customizations['field_size'] : 'small';

			$button_args = array(
				'title'           => $submit_btn_text,
				'submitting_text' => $submitting_btn_text,
				'data-size'       => $submit_btn_size,
				'subtitle'        => $subtitle
			);

			do_action( 'wfopp_output_form_before', $optinPageId, $optin_settings, $form_mode, $customizations, $class, $optin_layout );
			echo $this->add_recaptcha_script(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
            <div class="bwf_clear"></div>
            <div class="wffn-optin-form bwfac_forms_outer <?php echo esc_attr( $class ); ?>" data-field-size="<?php echo esc_attr( $field_size ) ?>">
				<?php do_action( 'wfopp_output_form_tag_before', $optinPageId, $optin_settings, $form_mode, $customizations, $class, $optin_layout ); ?>

                <form class="wffn-custom-optin-from" method="post">
					<?php
					foreach ( $optin_layout as $optin_step => $fields ) {
						if ( count( $fields ) === 0 ) {
							continue;
						}
						?>

                        <div class="wfop_section <?php echo esc_attr( $optin_step ); ?>">
							<?php
							$optin_form_fields = apply_filters( 'wffn_custom_integration_field_merge', $fields, $optinPageId );

							foreach ( $optin_form_fields as $fieldData ) {
								$fieldData['hash_key'] = apply_filters( 'wffn_optin_advanced_field_name', $this->render_count, $fields );
								$field_object          = WFOPP_Core()->form_fields->get_integration_object( $fieldData['type'] );
								if ( $field_object instanceof WFFN_Optin_Form_Field ) {
									$field_object->load_scripts();
									$field_object->load_style();
									$field_object->get_field_output( $fieldData );
								}
							} ?>
                        </div>
						<?php

					}
					$is_preview = wffn_string_to_bool( filter_input( INPUT_GET, 'preview' ) ); ?>
                    <div class="bwfac_form_sec submit_button">
                        <input type="hidden" value="<?php echo esc_attr( is_admin() ) ?>" name="optin_is_admin">
                        <input type="hidden" value="<?php echo esc_attr( wp_doing_ajax() ) ?>" name="optin_is_ajax">
                        <input type="hidden" value="<?php echo esc_attr( $is_preview ) ?>" name="optin_is_preview">
                        <input type="hidden" value="<?php echo esc_attr( $optinPageId ) ?>" name="optin_page_id">
                        <input type="hidden" value="<?php echo esc_attr( $optin_settings['formBuilder'] ) ?>" name="formBuilder">
						<?php $this->wffn_get_button_html( $button_args ); ?>
                    </div>
					<?php echo $this->add_recaptcha_field();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </form>
				<?php do_action( 'wfopp_output_form_tag_after', $optinPageId, $optin_settings, $form_mode, $customizations, $class, $optin_layout ); ?>


            </div>
			<?php
			do_action( 'wfopp_output_form_after', $optinPageId, $optin_settings, $form_mode, $customizations, $class, $optin_layout );

		}

		public function add_recaptcha_field() {
			$html       = '';
			$db_options = WFOPP_Core()->optin_pages->get_option();

			if ( WFOPP_Core()->optin_pages->form_builder->is_preview ) {
				return $html;
			}

			if ( ! isset( $db_options['op_recaptcha'] ) && $db_options['op_recaptcha'] !== 'true' ) {
				return $html;
			}

			if ( isset( $db_options['op_recaptcha_site'] ) && $db_options['op_recaptcha_site'] !== '' ) {
				$html .= '<!-- Google reCAPTCHA widget -->';
				$html .= '<div class="g-recaptcha" data-sitekey="' . $db_options['op_recaptcha_site'] . '" data-badge="bottomright" data-size="invisible" data-callback="wffn_captchaResponse"></div>';
				$html .= '<input type="hidden" id="wffn-captcha-response" name="wffn-captcha-response" />';

			}

			return $html;
		}

		public function add_recaptcha_script() {
			$db_options = WFOPP_Core()->optin_pages->get_option();
			if ( ! WFOPP_Core()->optin_pages->form_builder->is_preview && $db_options['op_recaptcha'] && $db_options['op_recaptcha'] === 'true' ) {

				?>
                <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script> <?php //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
                <script>
                    var onloadCallback = function () {
                        grecaptcha.execute();
                    };

                    function wffn_captchaResponse(response) {
                        document.getElementById('wffn-captcha-response').value = response;
                    }
                </script>

				<?php
			}
		}

		/**
		 * Get Redirecting to next step if next link is in URL
		 */
		public function get_redirect_to_next_step( $posted_data ) {
			$current_step = WFFN_Core()->data->get_current_step();

			$step_id         = isset( $current_step['id'] ) ? $current_step['id'] : 0;
			$url_from_filter = '';
			if ( $step_id > 0 ) {

				WFOPP_Core()->optin_pages->setup_custom_options( $step_id );
				$data = WFOPP_Core()->optin_pages->get_custom_option();
				if ( isset( $data['custom_redirect_page'] ) && $data['custom_redirect'] === 'true' ) {
					if ( is_array( $data['custom_redirect_page'] ) && count( $data['custom_redirect_page'] ) > 0 ) {
						$custom_redirect_page = get_post( $data['custom_redirect_page']['id'] );
						if ( $custom_redirect_page instanceof WP_Post && 'publish' === $custom_redirect_page->post_status ) {
							$url_from_filter = add_query_arg( array( 'wfop_source' => $step_id ), get_permalink( $custom_redirect_page->ID ) );
						}
					}
				}
				if ( empty( $url_from_filter ) ) {
					$url_from_filter = WFFN_Core()->data->get_next_url( $step_id );
				}

			}
			$url_from_filter = apply_filters( 'wffn_optin_redirect', $url_from_filter, $current_step, $posted_data );

			if ( ! empty( $url_from_filter ) ) {
				$url_from_filter = add_query_arg( array( 'opid' => $posted_data['opid'] ), $url_from_filter );
			}


			$url = empty( $url_from_filter ) ? site_url() : $url_from_filter;

			return WFFN_Core()->data->maybe_add_funnel_session_param( $url );

		}

		/**
		 *
		 */
		public function handle_submission() {
			$optin_page_id = filter_input( INPUT_POST, 'optin_page_id', FILTER_SANITIZE_NUMBER_INT );
			$posted_data   = $this->get_posted_data( $optin_page_id );
			$response      = $this->wffn_recaptcha_response( $posted_data );

			$result = [];
			if ( $response['success'] ) {
				if ( isset( $posted_data['wffn-captcha-response'] ) ) {
					unset( $posted_data['wffn-captcha-response'] );
				}

				WFFN_Core()->logger->log( "Custom form posted data: " . print_r( $posted_data, true ) );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				$field_settings         = [];
				$optin_actions_settings = WFOPP_Core()->optin_actions->get_optin_action_settings( $optin_page_id );
				$result['posted_data']  = $posted_data;
				try {
					$posted_data_after_action = $this->handle_actions( $result['posted_data'], $field_settings, $optin_actions_settings );
					if ( ! empty( $posted_data_after_action ) && count( $posted_data_after_action ) > 0 ) {
						$result['posted_data'] = $posted_data_after_action;
					}
				} catch ( Exception $e ) {
					WFFN_Core()->logger->log( "Exception occured during form submission" . print_r( $e, true ) );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				}

				do_action( 'wffn_optin_form_submit', $optin_page_id, $result['posted_data'] );

				WFFN_Core()->logger->log( "Actions ran successfully" . print_r( $result['posted_data'], true ) );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

				$result             = apply_filters( 'wfopp_modify_form_submit_result', $result, $optin_page_id );
				$result['next_url'] = $this->get_redirect_to_next_step( $result['posted_data'] );
				WFFN_Core()->logger->log( "returning : " . print_r( $result, true ) );  //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

			}

			wp_send_json( $result );
		}

		public function get_posted_data( $optin_page_id ) {
			$raw_posted_data = $_POST; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$posted          = [];
			$get_fields      = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optin_page_id );

			$optin_form_fields = apply_filters( 'wffn_custom_integration_field_merge', $get_fields, $optin_page_id );

			foreach ( $optin_form_fields as $field ) {
				$get_field_object = WFOPP_Core()->form_fields->get_integration_object( $field['type'] );
				if ( empty( $get_field_object ) ) {
					continue;
				}
				if ( isset( $get_field_object->is_custom_field ) && $get_field_object->is_custom_field ) {
					$posted[ $field['InputName'] ] = $get_field_object->get_sanitized_value( $raw_posted_data, $field );
				} else {
					$posted[ $get_field_object::get_slug() ] = $get_field_object->get_sanitized_value( $raw_posted_data, $field );
				}
			}
			if ( isset( $raw_posted_data['wffn-captcha-response'] ) && ! empty( $raw_posted_data['wffn-captcha-response'] ) ) {
				$posted['wffn-captcha-response'] = wffn_clean( $raw_posted_data['wffn-captcha-response'] );
			}
			$posted['optin_page_id'] = $optin_page_id;
			$posted                  = apply_filters( 'wffn_optin_posted_data', $posted, $raw_posted_data );

			return $posted;
		}


		/**
		 * @param $button_args
		 */
		public function wffn_get_button_html( $button_args ) {
			$args = wp_parse_args( $button_args, $this->get_default_button_args() ); ?>
            <div class="<?php echo esc_attr( $args['wrapper_class'] ) ?>" id="<?php echo esc_attr( $args['wrapper_id'] ) ?>">
				<?php if ( 'button' === $args['type'] ){ ?>
                <button class="<?php echo esc_attr( $args['button_class'] ) ?>" data-subitting-text="<?php echo esc_attr( $args['submitting_text'] ) ?>" type="submit" id="<?php echo esc_attr( $args['button_id'] ) ?>" data-size="<?php echo esc_attr( $args['data-size'] ) ?>">
					<?php } else{ ?>
                    <a href="<?php echo esc_url( $args['link'] ) ?>">
						<?php } ?>
                        <span class="<?php echo esc_attr( $args['text_wrapper'] ) ?>">
							<?php if ( $args['show_icon'] ) {

								if ( isset( $args['icon_html'] ) && ! empty( $args['icon_html'] ) ) {
									echo $args['icon_html']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								} else {
									$this->maybe_get_icon_html( $args );
								}

							} ?>
							<span class="<?php echo esc_attr( $args['title_class'] ) ?>"><?php echo wp_kses_post( $args['title'] ); ?></span>
						</span>
						<?php if ( ! empty( $args['subtitle'] ) ) { ?>
                            <span class="<?php echo esc_attr( $args['subtitle_class'] ) ?>"><?php echo wp_kses_post( $args['subtitle'] ); ?></span>
						<?php } ?>
						<?php if ( 'button' === $args['type'] ){ ?>
                </button>
			<?php } else { ?>
                </a>
			<?php } ?>
            </div>
			<?php
		}

		public function get_default_button_args() {
			return apply_filters( 'wffn_general_button_default_args', array(
				'title'                => '',
				'subtitle'             => '',
				'type'                 => 'button',
				'link'                 => '#',
				'submitting_text'      => '',
				'wrapper_class'        => 'bwf-custom-button',
				'wrapper_id'           => 'bwf-custom-button-wrap',
				'text_wrapper'         => 'bwf-text-wrapper',
				'button_class'         => 'wfop_submit_btn',
				'button_id'            => 'wffn_custom_optin_submit',
				'data-size'            => 'normal',
				'button-width'         => 'normal',
				'show_icon'            => false,
				'icon_container_class' => 'bwf_icon',
				'icon_class'           => '',
				'icon_position'        => '',
				'title_class'          => 'bwf_heading',
				'subtitle_class'       => 'bwf_subheading',
			) );
		}

		/**
		 * @param $args
		 */
		public function maybe_get_icon_html( $args ) { ?>
            <span class="<?php echo esc_attr( $args['icon_container_class'] ) . ' ' . esc_attr( $args['icon_position'] ); ?>">
				<?php if ( class_exists( '\Elementor\Icons_Manager' ) ) {
					\Elementor\Icons_Manager::render_icon( $args['icon_class'], [ 'aria-hidden' => 'true' ] );
				} else { ?>
                    <i aria-hidden="true" class="far fa-bell"></i>
				<?php } ?>
			</span>

			<?php
		}

		public function maybe_handle_crm_return() {


			if ( 'yes' === filter_input( INPUT_GET, 'wffn-next-link' ) ) {

				if ( false === WFFN_Core()->data->has_valid_session() ) {
					return;
				}
				$getData = WFFN_Core()->data->get( 'opid' );


				if ( empty( $getData ) ) {
					return;
				}
				$bwf_optin = WFFN_DB_Optin::get_instance();
				$optin     = $bwf_optin->get_contact_by_opid( $getData );

				if ( empty( $optin ) || ( $optin->id === 0 && $optin->email !== '' ) ) {
					return;
				}

				$data = json_decode( $optin->data );
				if ( ! is_object( $data ) ) {
					$data = new stdClass();
				}
				$data->opid        = $optin->opid;
				$data->opid        = $optin->opid;
				$data->cid         = $optin->cid;
				$data->optin_email = $optin->email;
				$posted_data       = json_decode( wp_json_encode( $data ), true );
				$redirect          = $this->get_redirect_to_next_step( $posted_data );
				if ( empty( $redirect ) ) {
					return;
				}
				wp_redirect( $redirect );
				exit;
			}
		}
	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->form_controllers->register( WFFN_Optin_Form_Controller_Custom_Form::get_instance() );
	}
}