<?php
/**
 * @class TNITContactFormModule
 */

class TNITContactFormModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */

	public $user_data = array();

	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Contact Form', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$formstyler_module,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-contact-form/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-contact-form/',
				'partial_refresh' => true,
			)
		);

		add_action( 'wp_ajax_tnit_builder_email', array( $this, 'tnit_send_mail' ) );
		add_action( 'wp_ajax_nopriv_tnit_builder_email', array( $this, 'tnit_send_mail' ) );

		$this->user_data = Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_user_data' );

	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {

		// Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'font-awesome-5' );

		$settings = $this->settings;
		if ( isset( $settings->recaptcha_toggle ) && 'show' == $settings->recaptcha_toggle
			&& isset( $this->user_data['recaptcha']['site_key'] ) && ! empty( $this->user_data['recaptcha']['site_key'] )
		) {

			$site_lang = substr( get_locale(), 0, 2 );
			$post_id   = FLBuilderModel::get_post_id();

			$this->add_js(
				'g-recaptcha',
				'https://www.google.com/recaptcha/api.js?onload=onLoadFLReCaptcha&render=explicit&hl=' . $site_lang,
				array( 'fl-builder-layout-' . $post_id ),
				'2.0',
				true
			);
		}
	}

	/**
	 * @method  add_async_attribute for the enqueued `g-recaptcha` script
	 * @param string $tag    Script tag
	 * @param string $handle Registered script handle
	 */
	public function add_async_attribute( $tag, $handle ) {
		if ( ( 'g-recaptcha' !== $handle ) || ( 'g-recaptcha' === $handle && strpos( $tag, 'g-recaptcha-api' ) !== false ) ) {
			return $tag;
		}

		return str_replace( ' src', ' id="g-recaptcha-api" async="async" defer="defer" src', $tag );
	}

	/**
	 * Render reCaptcha attributes.
	 *
	 * @return string
	 */
	public function recaptcha_data_attributes() {
		$settings               = $this->settings;
		$attrs['data-sitekey']  = $this->user_data['recaptcha']['site_key'];
		$attrs['data-validate'] = 'normal';
		$attrs['data-theme']    = $settings->recaptcha_theme;

		foreach ( $attrs as $attr_key => $attr_val ) {
			echo ' ' . $attr_key . '="' . $attr_val . '"';
		}
	}

	/**
	 * Connects Beaver Themer field connections before sending mail
	 * as those won't be connected during a wp_ajax call.
	 *
	 * @method connect_field_connections_before_send
	 */
	public function connect_field_connections_before_send() {
		if ( class_exists( 'FLPageData' ) && isset( $_REQUEST['layout_id'] ) ) {

			$posts = query_posts(
				array(
					'p'         => absint( $_REQUEST['layout_id'] ),
					'post_type' => 'any',
				)
			);

			if ( count( $posts ) ) {
				global $post;
				$post = $posts[0];
				setup_postdata( $post );
				FLPageData::init_properties();
			}
		}
	}

	/**
	 * @method tnit_send_mail
	 */
	public function tnit_send_mail() {

		// Try to connect Themer connections before sending.
		self::connect_field_connections_before_send();

		// Get the contact form post data
		$node_id            = isset( $_POST['node_id'] ) ? sanitize_text_field( $_POST['node_id'] ) : false;
		$template_id        = isset( $_POST['template_id'] ) ? sanitize_text_field( $_POST['template_id'] ) : false;
		$template_node_id   = isset( $_POST['template_node_id'] ) ? sanitize_text_field( $_POST['template_node_id'] ) : false;
		$recaptcha_response = isset( $_POST['recaptcha_response'] ) ? $_POST['recaptcha_response'] : false;

		$subject     = ( isset( $_POST['subject'] ) ? $_POST['subject'] : __( 'Contact Form Submission', 'xpro-bb-addons' ) );
		$admin_email = get_option( 'admin_email' );
		$site_name   = get_option( 'blogname' );
		$response    = array(
			'error'   => true,
			'message' => __( 'Message failed. Please try again.', 'xpro-bb-addons' ),
		);

		if ( $node_id ) {

			// Get the module settings.
			if ( $template_id ) {
				$post_id  = FLBuilderModel::get_node_template_post_id( $template_id );
				$data     = FLBuilderModel::get_layout_data( 'published', $post_id );
				$settings = $data[ $template_node_id ]->settings;
			} else {
				$module   = FLBuilderModel::get_module( $node_id );
				$settings = $module->settings;
			}

			if ( class_exists( 'FLThemeBuilderFieldConnections' ) ) {
				$settings = FLThemeBuilderFieldConnections::connect_settings( $settings );
			}

			if ( isset( $settings->mailto_email ) && ! empty( $settings->mailto_email ) ) {
				$mailto = $settings->mailto_email;
			} elseif ( isset( $user_data['contact_form']['mail'] ) ) {
				$mailto = $user_data['contact_form']['mail'];
			} else {
				$mailto = $admin_email;
			}

			if ( empty( $settings->mailto_email ) ) {
				$mailto = $user_data['contact_form']['mail'];
			} elseif ( isset( $settings->mailto_email ) && ! empty( $settings->mailto_email ) ) {
				$mailto = $settings->mailto_email;
			} else {
				$mailto = $admin_email;
			}

			if ( isset( $settings->subject_toggle ) && ( 'hide' === $settings->subject_toggle ) && isset( $settings->subject_hidden ) && ! empty( $settings->subject_hidden ) ) {
				$subject = $settings->subject_hidden;
			}

			// Validate reCAPTCHA if enabled
			if ( isset( $settings->recaptcha_toggle ) && 'show' === $settings->recaptcha_toggle && $recaptcha_response ) {
				if ( isset( $this->user_data['recaptcha']['site_key'] ) && isset( $this->user_data['recaptcha']['secret_key'] ) ) {
					if ( version_compare( phpversion(), '5.3', '>=' ) ) {
						include FLBuilderModel::$modules['tnit-contact-form']->dir . 'includes/validate-recaptcha.php';
					} else {
						$response['error'] = false;
					}
				} else {
					$response = array(
						'error'   => true,
						'message' => __( 'Your reCAPTCHA Site or Secret Key is missing!', 'xpro-bb-addons' ),
					);
				}
			} else {
				$response['error'] = false;
			}

			$fl_contact_from_email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null );
			$fl_contact_from_name  = ( isset( $_POST['name'] ) ? $_POST['name'] : '' );

			if ( isset( $_POST['name'] ) ) {
				$site_name = apply_filters( 'tnit_contact_form_from', $site_name, $_POST['name'] );
			}

			$headers = array(
				'From: ' . $site_name . ' <' . $admin_email . '>',
				'Reply-To: ' . $fl_contact_from_name . ' <' . $fl_contact_from_email . '>',
			);

			// Build the email
			$template = '';

			if ( isset( $_POST['name'] ) ) {
				$template .= "Name: $_POST[name] \r\n";
			}
			if ( isset( $_POST['email'] ) ) {
				$template .= "Email: $_POST[email] \r\n";
			}
			if ( isset( $_POST['phone'] ) ) {
				$template .= "Phone: $_POST[phone] \r\n";
			}
			if ( isset( $_POST['message'] ) ) {
				$template .= __( 'Message', 'xpro-bb-addons' ) . ": \r\n" . $_POST['message'];
			}

			// Double check the mailto email is proper and no validation error found, then send.
			if ( $mailto && false === $response['error'] ) {

				$subject = esc_html( do_shortcode( $subject ) );
				$mailto  = esc_html( do_shortcode( $mailto ) );
				/**
				 * Before sending with wp_mail()
				 *
				 * @see fl_module_contact_form_before_send
				 */
				do_action( 'fl_module_tnit_contact_form_before_send', $mailto, $subject, $template, $headers, $settings );
				$result = wp_mail( $mailto, $subject, $template, $headers );

				/**
				 * After sending with wp_mail()
				 *
				 * @see fl_module_contact_form_after_send
				 */
				do_action( 'fl_module_tnit_contact_form_after_send', $mailto, $subject, $template, $headers, $settings, $result );
				$response['message'] = __( 'Sent!', 'xpro-bb-addons' );
			}

			wp_send_json( $response );

		}
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITContactFormModule',
	array(
		'general'               => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general'   => array(
					'title'  => '',
					'fields' => array(
						'mailto_email'     => array(
							'type'        => 'text',
							'label'       => __( 'Send To Email', 'xpro-bb-addons' ),
							'default'     => '',
							'placeholder' => isset( Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_user_data' )['contact_form']['mail'] ) ? Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_user_data' )['contact_form']['mail'] : __( 'Enter your mail here', 'xpro-bb-addons' ),
							'help'        => __( 'The contact form will send to this e-mail. Defaults to the admin email.', 'xpro-bb-addons' ),
						),
						'tnit_form_layout' => array(
							'type'    => 'button-group',
							'label'   => __( 'Layout', 'xpro-bb-addons' ),
							'default' => 'stacked',
							'options' => array(
								'stacked'        => __( 'Stacked', 'xpro-bb-addons' ),
								'inline'         => __( 'Inline', 'xpro-bb-addons' ),
								'stacked-inline' => __( 'Modern', 'xpro-bb-addons' ),
							),
						),
					),
				),
				'content'   => array(
					'title'     => __( 'Content', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'tnit_form_title'  => array(
							'type'    => 'text',
							'label'   => __( 'Title', 'xpro-bb-addons' ),
							'default' => __( 'Contact Form', 'xpro-bb-addons' ),
						),
						'form_description' => array(
							'type'    => 'textarea',
							'label'   => __( 'Description', 'xpro-bb-addons' ),
							'default' => __( 'Enter description here...', 'xpro-bb-addons' ),
							'rows'    => '6',
						),
					),
				),
				'feilds'    => array(
					'title'     => __( 'Fields', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'tnit_name_toggle'         => array(
							'type'    => 'button-group',
							'label'   => __( 'Name Field', 'xpro-bb-addons' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'xpro-bb-addons' ),
								'hide' => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'name_placeholder' ),
								),
							),
						),
						'name_placeholder'         => array(
							'type'    => 'text',
							'label'   => __( 'Name Placeholder', 'xpro-bb-addons' ),
							'default' => __( 'Your Name', 'xpro-bb-addons' ),
						),
						'tnit_email_placeholder'   => array(
							'type'    => 'text',
							'label'   => __( 'Email Placeholder', 'xpro-bb-addons' ),
							'default' => __( 'Your Email', 'xpro-bb-addons' ),
						),
						'tnit_phone_toggle'        => array(
							'type'    => 'button-group',
							'label'   => __( 'Phone Field', 'xpro-bb-addons' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'xpro-bb-addons' ),
								'hide' => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'tnit_phone_placeholder' ),
								),
							),
						),
						'tnit_phone_placeholder'   => array(
							'type'    => 'text',
							'label'   => __( 'Phone Placeholder', 'xpro-bb-addons' ),
							'default' => __( 'Your Phone', 'xpro-bb-addons' ),
						),
						'subject_toggle'           => array(
							'type'    => 'button-group',
							'label'   => __( 'Subject Field', 'xpro-bb-addons' ),
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'xpro-bb-addons' ),
								'hide' => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'tnit_subject_placeholder' ),
								),
								'hide' => array(
									'fields' => array( 'subject_hidden' ),
								),
							),
						),
						'tnit_subject_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Subject Placeholder', 'xpro-bb-addons' ),
							'default' => __( 'Your Subject', 'xpro-bb-addons' ),
						),
						'subject_hidden'           => array(
							'type'    => 'text',
							'label'   => __( 'Email Subject', 'xpro-bb-addons' ),
							'default' => 'Contact Form Submission',
							'help'    => __( 'You can choose the subject of the email. Defaults to Contact Form Submission.', 'xpro-bb-addons' ),
						),
						'tnit_message_toggle'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Message Field', 'xpro-bb-addons' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'xpro-bb-addons' ),
								'hide' => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'tnit_message_placeholder' ),
								),
							),
						),
						'tnit_message_placeholder' => array(
							'type'    => 'text',
							'label'   => __( 'Message Placeholder', 'xpro-bb-addons' ),
							'default' => __( 'Enter Your Message', 'xpro-bb-addons' ),
						),
					),
				),
				'recaptcha' => array(
					'title'     => __( 'reCAPTCHA', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'recaptcha_toggle'    => array(
							'type'    => 'button-group',
							'label'   => 'reCAPTCHA Field',
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'xpro-bb-addons' ),
								'hide' => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show' => array(
									'fields' => array( 'recaptcha_site_key', 'recaptcha_secret_key', 'recaptcha_theme', 'recaptcha_alignment', 'xpro_information' ),
								),
							),
						),
						'recaptcha_theme'     => array(
							'type'    => 'button-group',
							'label'   => __( 'Theme', 'xpro-bb-addons' ),
							'default' => 'light',
							'options' => array(
								'light' => __( 'Light', 'xpro-bb-addons' ),
								'dark'  => __( 'Dark', 'xpro-bb-addons' ),
							),
						),
						'recaptcha_alignment' => array(
							'type'    => 'align',
							'label'   => __( 'Alignment', 'xpro-bb-addons' ),
							'default' => 'center',
						),
						'xpro_information'    => array(
							'type'    => 'raw',
							'content' => '<p>For reCaptcha please enter valid Site Key and Secret Key in <a href="' . get_site_url() . '/wp-admin/admin.php?page=xpro_dashboard_welcome#bb-integrations" target="_blank">User Data</a></p>',
						),
					),
				),
				'success'   => array(
					'title'     => __( 'Success', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'success_action'  => array(
							'type'    => 'select',
							'label'   => __( 'Success Action', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'         => __( 'None', 'xpro-bb-addons' ),
								'show_message' => __( 'Show Message', 'xpro-bb-addons' ),
								'redirect'     => __( 'Redirect', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'show_message' => array(
									'fields'   => array( 'success_message' ),
									'sections' => array( 'form_success_styling', 'form_success_typography' ),
								),
								'redirect'     => array(
									'fields' => array( 'success_url' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'success_message' => array(
							'type'          => 'editor',
							'label'         => '',
							'media_buttons' => false,
							'rows'          => 8,
							'default'       => __( 'Thanks for your message! We’ll be in touch soon.', 'xpro-bb-addons' ),
							'connections'   => array( 'string', 'html' ),
							'preview'       => array(
								'type' => 'none',
							),
						),
						'success_url'     => array(
							'type'        => 'link',
							'label'       => __( 'Success URL', 'xpro-bb-addons' ),
							'connections' => array( 'url' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),

			),
		),
		'form_style'            => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'form_styles'    => array(
					'title'  => __( 'Form Styles', 'xpro-bb-addons' ),
					'fields' => array(
						'form_bg_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box_v2',
								'property' => 'background-color',
							),
						),
						'form_border'   => array(
							'type'       => 'border',
							'label'      => __( 'Form Border', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box_v2',
							),
						),
						'form_padding'  => array(
							'type'        => 'dimension',
							'label'       => __( 'Form Padding', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '20',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => true,
						),
					),
				),
				'content_styles' => array(
					'title'  => __( 'Content Styles', 'xpro-bb-addons' ),
					'fields' => array(
						'content_alignment'         => array(
							'type'       => 'align',
							'label'      => __( 'Content Alignment', 'tnit' ),
							'responsive' => true,
							'help'       => __( 'Title and descrpition alignment.', 'xpro-bb-addons' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-contact-title-holder, .tnit-contact-desc-holder',
								'property' => 'text-align',
							),
						),
						'title_margin_bottom'       => array(
							'type'        => 'unit',
							'label'       => 'Title Margin Bottom',
							'units'       => array( 'px' ),
							'default'     => '10',
							'placeholder' => '0',
							'slider'      => true,
							'responsive'  => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-contact-title-holder',
								'property' => 'margin-bottom',
							),
						),
						'descrpition_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => 'Description Margin Bottom',
							'units'       => array( 'px' ),
							'default'     => '20',
							'placeholder' => '0',
							'slider'      => true,
							'responsive'  => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-contact-desc-holder',
								'property' => 'margin-bottom',
							),
						),
					),
				),
			),
		),
		'input_style'           => array(
			'title'    => __( 'Inputs', 'xpro-bb-addons' ),
			'sections' => array(
				'input_colors_setting' => array(
					'title'  => __( 'Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'input_field_text_color'  => array(
							'type'    => 'color',
							'label'   => __( 'Text Color', 'xpro-bb-addons' ),
							'default' => '333333',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .tnit-contact-name, ,
                            							.tnit-form-box .inner-holder .tnit-contact-email,
                            							.tnit-form-box .inner-holder .tnit-contact-phone,
                            							.tnit-form-box .inner-holder .tnit-contact-subject,
                            							.tnit-form-box .inner-holder .tnit-contact-message',
								'property' => 'color',
							),
						),
						'input_placeholder_color' => array(
							'type'       => 'color',
							'label'      => __( 'Placeholder Color', 'xpro-bb-addons' ),
							'default'    => '000000',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .tnit-contact-name::placeholder,
                            							.tnit-form-box .inner-holder .tnit-contact-email::placeholder,
                            							.tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
                            							.tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
                            							.tnit-form-box .inner-holder .tnit-contact-message::placeholder',
								'property' => 'color',
							),
						),
						'input_field_bg_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'default'    => 'ffffff',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .tnit-contact-name, .tnit-form-box .inner-holder .tnit-contact-name::placeholder,
                            				.tnit-form-box .inner-holder .tnit-contact-email, .tnit-form-box .inner-holder .tnit-contact-email::placeholder,
                            				.tnit-form-box .inner-holder .tnit-contact-phone, .tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
                            				.tnit-form-box .inner-holder .tnit-contact-subject, .tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
                            				.tnit-form-box .inner-holder .tnit-contact-message, .tnit-form-box .inner-holder .tnit-contact-message::placeholder',
								'property' => 'background-color',
							),
						),
					),
				),
				'input_border_setting' => array(
					'title'     => __( 'Border', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'input_border'             => array(
							'type'       => 'border',
							'label'      => __( 'Border Style', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'input_border_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Focus Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
					),
				),
				'input_size_style'     => array(
					'title'     => __( 'Size & Alignment', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'input_field_height'    => array(
							'type'    => 'unit',
							'label'   => __( 'Input Height', 'xpro-bb-addons' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => '45',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder input[type=text], 
                            						  .tnit-form-box .inner-holder input[type=tel], 
                            						  .tnit-form-box .inner-holder input[type=email]',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
						'input_textarea_height' => array(
							'type'    => 'unit',
							'label'   => __( 'Textarea Height', 'xpro-bb-addons' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => '140',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder textarea',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
					),
				),
				'input_spacing'        => array(
					'title'     => __( 'Spacing', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'input_field_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'slider'     => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .tnit-contact-name, 
												   .tnit-form-box .inner-holder .tnit-contact-email,
												   .tnit-form-box .inner-holder .tnit-contact-phone,
												   .tnit-form-box .inner-holder .tnit-contact-subject,
												   .tnit-form-box .inner-holder .tnit-contact-message',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive' => true,
						),
						'input_field_margin'  => array(
							'type'       => 'unit',
							'label'      => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'default'    => '10',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'button'                => array(
			'title'    => __( 'Button', 'xpro-bb-addons' ),
			'sections' => array(
				'btn_general'   => array(
					'title'  => '',
					'fields' => array(
						'tnit_button_text' => array(
							'type'    => 'text',
							'label'   => __( 'Button Text', 'tnit' ),
							'default' => __( 'Click Here', 'tnit' ),
						),
					),
				),
				'btn_colors'    => array(
					'title'     => __( 'Colors', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'btn_bg_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_bg_hover_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_text_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'btn_text_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Text Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
					),
				),
				'btn_border'    => array(
					'title'     => __( 'Border', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'button_border'     => array(
							'type'       => 'border',
							'label'      => __( 'Border Style', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .inner-holder .btn-submit',
							),
						),
						'btn_border_hcolor' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_margin_top'    => array(
							'type'       => 'unit',
							'label'      => 'Margin Top',
							'units'      => array( 'px' ),
							'default'    => '20',
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .inner-holder .btn-submit',
								'property' => 'margin-top',
							),
						),
					),
				),
				'cta_structure' => array(
					'title'     => __( 'Structure', 'tnit' ),
					'collapsed' => true,
					'fields'    => array(
						'cta_width'        => array(
							'type'    => 'select',
							'label'   => 'Width',
							'default' => 'auto',
							'options' => array(
								'auto'   => __( 'Auto', 'xpro-bb-addons' ),
								'full'   => __( 'Full Width', 'xpro-bb-addons' ),
								'custom' => __( 'Custom', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'auto'   => array(
									'fields' => array( 'btn_alignment' ),
								),
								'custom' => array(
									'fields' => array( 'cta_custom_width', 'cta_custom_height', 'btn_alignment' ),
								),
							),
						),
						'cta_custom_width' => array(
							'type'    => 'unit',
							'label'   => 'Custom Width',
							'units'   => array( 'px' ),
							'default' => '200',
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .inner-holder .btn-submit',
							),
						),
						'btn_padding'      => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
						),
						'btn_alignment'    => array(
							'type'       => 'align',
							'label'      => __( 'Button Alignment', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => 'center',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .inner-holder.tnit-btn-holder',
								'property' => 'text-align',
							),
						),
					),
				),
			),
		),
		'form_messages_setting' => array(
			'title'    => __( 'Messages', 'xpro-bb-addons' ),
			'sections' => array(
				'form_error_styling'   => array(
					'title'  => __( 'Errors', 'xpro-bb-addons' ),
					'fields' => array(
						'validation_message_color'      => array(
							'type'    => 'color',
							'label'   => __( 'Error Field Message Color', 'xpro-bb-addons' ),
							'default' => 'dd4420',
						),
						'validation_field_border_color' => array(
							'type'    => 'color',
							'label'   => __( 'Error Field Border Color', 'xpro-bb-addons' ),
							'default' => 'dd4420',
						),
					),
				),
				'form_success_styling' => array(
					'title'  => __( 'Success Message', 'xpro-bb-addons' ),
					'fields' => array(
						'success_message_color' => array(
							'type'    => 'color',
							'label'   => __( 'Color', 'xpro-bb-addons' ),
							'default' => '29bb41',
						),
					),
				),
			),
		),
		'typography'            => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'title_typography'       => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'tnit_title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .tnit-title-contact',
							),
						),
						'title_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .tnit-form-box .tnit-title-contact',
								'property' => 'color',
							),
						),
					),
				),
				'description_typography' => array(
					'title'  => __( 'Description', 'xpro-bb-addons' ),
					'fields' => array(
						'tnit_description_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box_v2 .tnit-contact-desc-holder .tnit-desc-contactv1',
							),
						),
						'decrpition_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'default'    => '000000',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-contact-desc-holder .tnit-desc-contactv1',
								'property' => 'color',
							),
						),
					),
				),
				'input_typography'       => array(
					'title'  => __( 'Input', 'xpro-bb-addons' ),
					'fields' => array(
						'tnit_input_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .tnit-contact-name, .tnit-form-box .inner-holder .tnit-contact-name::placeholder, 
										     .tnit-form-box .inner-holder .tnit-contact-email, .tnit-form-box .inner-holder .tnit-contact-email::placeholder,
										     .tnit-form-box .inner-holder .tnit-contact-phone, .tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
										     .tnit-form-box .inner-holder .tnit-contact-subject, .tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
										     .tnit-form-box .inner-holder .tnit-contact-message, .tnit-form-box .inner-holder .tnit-contact-message::placeholder',
							),
						),
					),
				),
				'button_typography'      => array(
					'title'  => __( 'Button', 'xpro-bb-addons' ),
					'fields' => array(
						'tnit_button_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-form-box .inner-holder .btn-submit',
							),
						),
					),
				),
			),
		),
	)
);
