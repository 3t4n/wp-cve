<?php

/**
 * @class NJBA_Form_Module
 */
class NJBA_Form_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Contact Form', 'bb-njba' ),
			'description'     => __( 'Addon to display form.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'form_style' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-contact-form/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-contact-form/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'icon'            => 'editor-table.svg',
		) );
		add_action( 'wp_ajax_njba_builder_email', array( $this, 'send_mail' ) );
		add_action( 'wp_ajax_nopriv_njba_builder_email', array( $this, 'send_mail' ) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );

		$this->add_css( 'njba-form-frontend', NJBA_MODULE_URL . 'modules/njba-contact-form/css/frontend.css' );

	}

	static public function mailto_email() {
		return $this->settings->mailto_email;
	}

	static public function send_mail( $params = array() ) {
		global $njba_contact_from_name, $njba_contact_from_email, $from_name, $from_email;
		// Get the contact form post data
		$node_id          = isset( $_POST['node_id'] ) ? sanitize_text_field( $_POST['node_id'] ) : false;
		$template_id      = isset( $_POST['template_id'] ) ? sanitize_text_field( $_POST['template_id'] ) : false;
		$template_node_id = isset( $_POST['template_node_id'] ) ? sanitize_text_field( $_POST['template_node_id'] ) : false;
		$mailto           = get_option( 'admin_email' );
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
			if ( isset( $settings->mailto_email ) && ! empty( $settings->mailto_email ) ) {
				$mailto = $settings->mailto_email;
			}
			$subject = $settings->email_subject;
			if ( $subject != '' ) {

				if ( isset( $_POST['name'] ) ) {
					$subject = str_replace( '[NAME]', sanitize_text_field( $_POST['name'] ), $subject );
				}
				if ( isset( $_POST['last_name'] ) ) {
					$subject = str_replace( '[LAST NAME]', sanitize_text_field( $_POST['last_name'] ), $subject );
				}
				if ( isset( $_POST['subject'] ) ) {
					$subject = str_replace( '[SUBJECT]', sanitize_text_field( $_POST['subject'] ), $subject );
				}
				if ( isset( $_POST['email'] ) ) {
					$subject = str_replace( '[EMAIL]', sanitize_text_field( $_POST['email'] ), $subject );
				}
				if ( isset( $_POST['phone'] ) ) {
					$subject = str_replace( '[PHONE]', sanitize_text_field( $_POST['phone'] ), $subject );
				}
				if ( isset( $_POST['message'] ) ) {
					$subject = str_replace( '[MESSAGE]', sanitize_text_field( $_POST['message'] ), $subject );
				}

			}
			$njba_contact_from_email = ( isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : null );
			$njba_contact_from_name  = ( isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null );

			// Validate reCAPTCHA if enabled
			if ( isset( $settings->recaptcha_toggle ) && 'show' == $settings->recaptcha_toggle && $settings->recaptcha_response ) {
				if ( ! empty( $settings->recaptcha_secret_key ) && ! empty( $settings->recaptcha_site_key ) ) {
					if ( version_compare( phpversion(), '5.3', '>=' ) ) {
						include FLBuilderModel::$modules['njba-contact-form']->dir . 'includes/validate-recaptcha.php';
					} else {
						$response['error'] = false;
					}
				} else {
					$response = array(
						'error'   => true,
						'message' => __( 'Your reCAPTCHA Site or Secret Key is missing!', 'bb-njba' ),
					);
				}
			} else {
				$response['error'] = false;
			}

			$from_name  = $settings->from_name;
			$from_email = $settings->from_email;
			add_filter( 'wp_mail_from', 'NJBAFormModule::mail_from' );
			add_filter( 'wp_mail_from_name', 'NJBAFormModule::from_name' );

			$headers  = array(
				'Reply-To: ' . $njba_contact_from_email . ' <' . $njba_contact_from_email . '>',
				'Content-Type: text/html; charset=UTF-8',
			);
			$template = $settings->email_template;
			if ( isset( $_POST['name'] ) ) {
				$template = str_replace( '[NAME]', sanitize_text_field( $_POST['name'] ), $template );
			}
			if ( isset( $_POST['last_name'] ) ) {
				$template = str_replace( '[LAST NAME]', sanitize_text_field( $_POST['last_name'] ), $template );
			}
			if ( isset( $_POST['email'] ) ) {
				$template = str_replace( '[EMAIL]', sanitize_text_field( $_POST['email'] ), $template );
			}
			if ( isset( $_POST['subject'] ) ) {
				$template = str_replace( '[SUBJECT]', sanitize_text_field( $_POST['subject'] ), $template );
			}
			if ( isset( $_POST['phone'] ) ) {
				$template = str_replace( '[PHONE]', sanitize_text_field( $_POST['phone'] ), $template );
			}
			if ( isset( $_POST['message'] ) ) {
				$template = str_replace( '[MESSAGE]', sanitize_text_field( $_POST['message'] ), $template );
			}
			$template = wpautop( $template );
			// Double check the mailto email is proper and send

			if ( $mailto ) {
				wp_mail( $mailto, stripslashes( $subject ), do_shortcode( stripslashes( $template ) ), $headers );
				die( '1' );
			} else {
				die( $mailto );
			}

		}
	}

	static public function mail_from( $original_email_address ) {
		global $from_email;
		$original_email_address = 'info@ninjabeaveraddon.com';

		return ( $from_email != '' ) ? $from_email : $original_email_address;

	}

	static public function from_name( $original_name ) {
		global $from_name;
		$original_name = 'ninjabeaveraddon';

		return ( $from_name != '' ) ? $from_name : $original_name;

	}

	public function enqueue_scripts() {
		$settings = $this->settings;
		if ( isset( $settings->recaptcha_toggle, $settings->recaptcha_site_key ) && 'show' == $settings->recaptcha_toggle && ! empty( $settings->recaptcha_site_key )
		) {

			$site_lang = substr( get_locale(), 0, 2 );
			$this->add_js(
				'g-recaptcha',
				'https://www.google.com/recaptcha/api.js?onload=onLoadFLReCaptcha&render=explicit&hl=' . $site_lang,
				array(),
				'2.0',
				true
			);
		}
	}

	/**
	 * @method  add_async_attribute for the enqueued `g-recaptcha` script
	 * @param string $tag Script tag
	 * @param string $handle Registered script handle
	 *
	 * @return string|string[]
	 */
	public function add_async_attribute( $tag, $handle ) {
		if ( ( 'g-recaptcha' != $handle ) || ( 'g-recaptcha' == $handle && strpos( $tag, 'g-recaptcha-api' ) != false ) ) {
			return $tag;
		}

		return str_replace( ' src', ' id="g-recaptcha-api" async="async" defer="defer" src', $tag );
	}

	/**
	 * Use this method to work with settings data before
	 * it is saved. You must return the settings object.
	 *
	 * @method update
	 * @param $settings {object}
	 *
	 * @return object
	 */
	public function update( $settings ) {
		return $settings;
	}

	/**
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
	}
}

$host = 'localhost';
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
	$host = $_SERVER['HTTP_HOST'];
}
$current_url = 'http://' . $host . strtok( $_SERVER['REQUEST_URI'], '?' );
//$default_subject = sprintf( __(' Request received from %s (%s)' , 'bb-njba' ), get_bloginfo( 'name' ), $current_url);
$default_subject  = 'Request received from ' . get_bloginfo( 'name' ) . '';
$default_template = sprintf( __( '<strong>Name:</strong> [NAME]
<strong>Email:</strong> [EMAIL]
<strong>Last Name:</strong> [LAST NAME]
<strong>Subject:</strong> [SUBJECT]
<strong>Message Body:</strong>
[MESSAGE]
----
You have received a new submission from %s
(%s)', 'bb-njba' ), get_bloginfo( 'name' ), $current_url );
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Form_Module', array(
	'general'         => array(
		'title'    => __( 'General', 'bb-njba' ),
		'sections' => array(
			'general'         => array(
				'title'  => '',
				'fields' => array(
					'form_custom_title_desc' => array(
						'type'    => 'select',
						'label'   => __( 'Custom Title & Description', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'custom_title', 'custom_description' ),
							),
						)
					),
					'custom_title'           => array(
						'type'        => 'text',
						'label'       => __( 'Custom Title', 'bb-njba' ),
						'default'     => 'Contact Us',
						'description' => '',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					),
					'custom_description'     => array(
						'type'        => 'textarea',
						'label'       => __( 'Custom Description', 'bb-njba' ),
						'default'     => 'Create a stylish contact form that people would love to fill.',
						'placeholder' => '',
						'rows'        => '6',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title'
						)
					),
				)
			),
			'first_name'      => array(
				'title'  => __( 'First Name Field', 'bb-njba' ),
				'fields' => array(
					'first_name_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'First Name', 'bb-njba' ),
						'default' => 'show',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'first_name_width', 'first_name_label', 'first_name_placeholder', 'first_name_required', 'first_name_icon' ),
							)
						)
					),
					'first_name_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'first_name_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'First Name', 'bb-njba' ),
					),
					'first_name_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'First Name', 'bb-njba' ),
					),
					'first_name_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'first_name_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make name field compulsory.', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'last_name'       => array(
				'title'  => __( 'Last Name Field', 'bb-njba' ),
				'fields' => array(
					'last_name_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'Last Name', 'bb-njba' ),
						'default' => 'show',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'last_name_width', 'last_name_label', 'last_name_placeholder', 'last_name_required', 'last_name_icon' ),
							)
						)
					),
					'last_name_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'last_name_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'Last Name', 'bb-njba' ),
					),
					'last_name_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'Last Name', 'bb-njba' ),
					),
					'last_name_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'last_name_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make name field compulsory.', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'email_section'   => array(
				'title'  => __( 'Email Field', 'bb-njba' ),
				'fields' => array(
					'email_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'Email', 'bb-njba' ),
						'default' => 'show',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'email_width', 'email_label', 'email_placeholder', 'email_required', 'email_icon' ),
							)
						)
					),
					'email_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'email_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'Email', 'bb-njba' ),
					),
					'email_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'Email', 'bb-njba' ),
					),
					'email_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'email_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make email field compulsory.', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'subject_section' => array(
				'title'  => __( 'Subject Field', 'bb-njba' ),
				'fields' => array(
					'subject_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'Subject', 'bb-njba' ),
						'default' => 'show',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'subject_width', 'subject_label', 'subject_placeholder', 'subject_required', 'subject_icon' ),
							)
						)
					),
					'subject_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'subject_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'Subject', 'bb-njba' ),
					),
					'subject_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'Subject', 'bb-njba' ),
					),
					'subject_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'subject_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make subject field compulsory.', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'phone_section'   => array(
				'title'  => __( 'Phone Field', 'bb-njba' ),
				'fields' => array(
					'phone_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'Phone', 'bb-njba' ),
						'default' => 'hide',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'phone_width', 'phone_label', 'phone_placeholder', 'phone_required', 'phone_icon' ),
							)
						)
					),
					'phone_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'phone_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'Phone', 'bb-njba' ),
					),
					'phone_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'Phone', 'bb-njba' ),
					),
					'phone_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'phone_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make phone field compulsory.', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'msg_section'     => array(
				'title'  => __( 'Message Field', 'bb-njba' ),
				'fields' => array(
					'msg_toggle'      => array(
						'type'    => 'select',
						'label'   => __( 'Message', 'bb-njba' ),
						'default' => 'show',
						'options' => array(
							'show' => __( 'Show', 'bb-njba' ),
							'hide' => __( 'Hide', 'bb-njba' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array(
									'msg_width',
									'msg_height',
									'msg_label',
									'msg_placeholder',
									'msg_required',
									'textarea_top_margin',
									'textarea_bottom_margin',
									'msg_icon'
								),
							)
						)
					),
					'msg_width'       => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => '100',
						'options' => array(
							'100' => __( '100%', 'bb-njba' ),
							'50'  => __( '50%', 'bb-njba' ),
						)
					),
					'msg_label'       => array(
						'type'    => 'text',
						'label'   => __( 'Label', 'bb-njba' ),
						'default' => __( 'Message', 'bb-njba' ),
					),
					'msg_placeholder' => array(
						'type'    => 'text',
						'label'   => __( 'Placeholder', 'bb-njba' ),
						'default' => __( 'Message', 'bb-njba' ),
					),
					'msg_icon'        => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'msg_required'    => array(
						'type'    => 'select',
						'label'   => __( 'Required', 'bb-njba' ),
						'help'    => __( 'Enable to make message field compulsory.', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No',
						),
					),
				)
			),
			'success'         => array(
				'title'  => __( 'Success', 'bb-njba' ),
				'fields' => array(
					'success_action'  => array(
						'type'    => 'select',
						'label'   => __( 'Success Action', 'bb-njba' ),
						'default' => 'show_message',
						'options' => array(
							'none'         => __( 'None', 'bb-njba' ),
							'show_message' => __( 'Show Message', 'bb-njba' ),
							'redirect'     => __( 'Redirect', 'bb-njba' )
						),
						'toggle'  => array(
							'show_message' => array(
								'fields' => array( 'success_message' )
							),
							'redirect'     => array(
								'fields' => array( 'success_url' )
							)
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'success_message' => array(
						'type'          => 'editor',
						'label'         => '',
						'media_buttons' => false,
						'rows'          => 8,
						'default'       => __( 'Thanks for your message! We’ll be in touch soon.', 'bb-njba' ),
						'preview'       => array(
							'type' => 'none'
						)
					),
					'success_url'     => array(
						'type'    => 'link',
						'label'   => __( 'Success URL', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					)
				)
			),
		)
	),
	'template'        => array(
		'title'    => __( 'Email', 'bb-njba' ),
		'sections' => array(
			'email-subject'  => array(
				'title'  => __( '', 'bb-njba' ),
				'fields' => array(
					'mailto_email'  => array(
						'type'        => 'text',
						'label'       => __( 'Send To Email', 'bb-njba' ),
						'default'     => '',
						'placeholder' => 'example@mail.com',
						'help'        => __( 'The contact form will send to this e-mail. Defaults to the admin email.', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
					'email_subject' => array(
						'type'    => 'text',
						'label'   => __( 'Email Subject', 'bb-njba' ),
						'default' => $default_subject,
						'help'    => __( 'The subject of email received, by default if you have enabled subject it would be shown by shortcode or you can manually add yourself',
							'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'from_name'     => array(
						'type'    => 'text',
						'label'   => __( 'From Name', 'bb-njba' ),
						'default' => '',
						'help'    => __( 'The contact form will send to this From name. Defaults to the admin name.', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'from_email'    => array(
						'type'        => 'text',
						'label'       => __( 'From Email', 'bb-njba' ),
						'default'     => '',
						'placeholder' => 'example@mail.com',
						'help'        => __( 'The contact form will send to this From e-mail. Defaults to the admin email.', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
				)
			),
			'email-template' => array(
				'title'  => __( 'Email Template', 'bb-njba' ),
				'fields' => array(
					'email_template' => array(
						'type'        => 'editor',
						'label'       => '',
						'rows'        => 8,
						'default'     => $default_template,
						'description' => __( 'Here you can design the email you receive', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
					'email_success'  => array(
						'type'    => 'text',
						'label'   => __( 'Success Message', 'bb-njba' ),
						'default' => __( 'Message Sent!', 'bb-njba' ),
					),
					'email_error'    => array(
						'type'    => 'text',
						'label'   => __( 'Error Message', 'bb-njba' ),
						'default' => __( 'Message failed. Please try again.', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
				)
			),
		)
	),
	'style'           => array(
		'title'    => __( 'Style', 'bb-njba' ),
		'sections' => array(
			'form-general'      => array(
				'title'  => '',
				'fields' => array(
					'form_style'         => array(
						'type'    => 'select',
						'label'   => __( 'Form Style', 'bb-njba' ),
						'default' => 'style1',
						'options' => array(
							'style1' => __( 'Style 1', 'bb-njba' ),
							'style2' => __( 'Style 2', 'bb-njba' ),
							'style3' => __( 'Style 3', 'bb-njba' ),
						),
						'toggle'  => array(
							'style1' => array(
								'fields' => array( 'input_border_width', 'input_border_color', 'input_border_radius' )
							),
							'style2' => array(
								'fields' => array( 'border_style', 'input_border', 'border_color', 'border_radius' )
							),
							'style3' => array(
								'fields' => array( 'border_style', 'input_border', 'border_color', 'border_radius', 'textarea_border' )
							)
						),
						'help'    => __( 'Input field Appearance', 'bb-njba' ),
					),
					'enable_label'       => array(
						'type'    => 'select',
						'label'   => __( 'Enable Label', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						)
					),
					'enable_placeholder' => array(
						'type'    => 'select',
						'label'   => __( 'Enable Placeholder', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						)
					),
					'enable_icon'        => array(
						'type'    => 'select',
						'label'   => __( 'Enable Input Icon', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						)
					),
				)
			),
			'form-style'        => array(
				'title'  => 'Form Style',
				'fields' => array(
					'form_bg_type'         => array(
						'type'    => 'select',
						'label'   => __( 'Background Type', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'  => __( 'None', 'bb-njba' ),
							'color' => __( 'Color', 'bb-njba' ),
							'image' => __( 'Image', 'bb-njba' ),
						),
						'toggle'  => array(
							'color' => array(
								'fields' => array( 'form_bg_color', 'form_bg_color_opc' )
							),
							'image' => array(
								'fields' => array( 'form_bg_img', 'form_bg_img_pos', 'form_bg_img_size', 'form_bg_img_repeat' )
							),
						),
					),
					'form_bg_img'          => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background-image',
						),
					),
					'form_bg_img_pos'      => array(
						'type'    => 'select',
						'label'   => __( 'Background Position', 'bb-njba' ),
						'default' => 'center center',
						'options' => array(
							'left top'      => __( 'Left Top', 'bb-njba' ),
							'left center'   => __( 'Left Center', 'bb-njba' ),
							'left bottom'   => __( 'Left Bottom', 'bb-njba' ),
							'center top'    => __( 'Center Top', 'bb-njba' ),
							'center center' => __( 'Center Center', 'bb-njba' ),
							'center bottom' => __( 'Center Bottom', 'bb-njba' ),
							'right top'     => __( 'Right Top', 'bb-njba' ),
							'right center'  => __( 'Right Center', 'bb-njba' ),
							'right bottom'  => __( 'Right Bottom', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background-position',
						),
					),
					'form_bg_img_repeat'   => array(
						'type'    => 'select',
						'label'   => __( 'Background Repeat', 'bb-njba' ),
						'default' => 'repeat',
						'options' => array(
							'no-repeat' => __( 'No Repeat', 'bb-njba' ),
							'repeat'    => __( 'Repeat All', 'bb-njba' ),
							'repeat-x'  => __( 'Repeat Horizontally', 'bb-njba' ),
							'repeat-y'  => __( 'Repeat Vertically', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background-repeat',
						),
					),
					'form_bg_img_size'     => array(
						'type'    => 'select',
						'label'   => __( 'Background Size', 'bb-njba' ),
						'default' => 'cover',
						'options' => array(
							'contain' => __( 'Contain', 'bb-njba' ),
							'cover'   => __( 'Cover', 'bb-njba' ),
							'initial' => __( 'Initial', 'bb-njba' ),
							'inherit' => __( 'Inherit', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background-size',
						),
					),
					'form_bg_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background',
						),
					),
					'form_bg_color_opc'    => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'background-color',
						),
					),
					'form_padding'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 40,
							'bottom' => 40,
							'left'   => 40,
							'right'  => 40
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'padding-top',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'default'     => '40',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'default'     => '40',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							)
						)
					),
					'form_border_style'    => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'form_border_width', 'form_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'form_border_width', 'form_border_color' )
							),
							'dashed' => array(
								'fields' => array( 'form_border_width', 'form_border_color' )
							),
							'double' => array(
								'fields' => array( 'form_border_width', 'form_border_color' )
							),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'border-style',
						),
					),
					'form_border_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'F8F8F8',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'border-color',
						),
					),
					'form_border_width'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'bottom' => 1,
							'left'   => '',
							'right'  => '',
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'border-top',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'border-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'border-left',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form',
									'property' => 'border-right',
									'unit'     => 'px'
								),
							)
						)
					),
					'form_radius'          => array(
						'type'        => 'text',
						'label'       => __( 'Round Corner', 'bb-njba' ),
						'maxlength'   => '4',
						'size'        => '6',
						'description' => 'px',
						'placeholder' => '0',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form',
							'property' => 'border-radius',
						),
					),
					'form_box_shadow'      => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-h'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-v'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa fa-circle-thin'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa fa-circle'
							)

						)
					),
					'box_shadow_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Shadow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'box_shadow_opacity'   => array(
						'type'        => 'text',
						'label'       => __( 'Shadow Opacity', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => 50,
					),
					'input_custom_width'   => array(
						'type'    => 'select',
						'label'   => __( 'Inputs Width', 'bb-njba' ),
						'default' => 'default',
						'options' => array(
							'default' => __( 'Default', 'bb-njba' ),
							'custom'  => __( 'Custom', 'bb-njba' ),
						),
						'toggle'  => array(
							'custom' => array(
								'fields' => array( 'input_name_width', 'input_email_width', 'input_button_width' )
							)
						)
					),
					'input_name_width'     => array(
						'type'        => 'text',
						'label'       => __( 'Name Field Width', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => '',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group.njba-first-name, .njba-contact-form .njba-input-group.njba-last-name, .njba-contact-form .njba-input-group.njba-subject, .njba-contact-form .njba-input-group.njba-phone',
							'property' => 'width'
						)
					),
					'input_email_width'    => array(
						'type'        => 'text',
						'label'       => __( 'Email Field Width', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => '',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group.njba-email',
							'property' => 'width'
						)
					),
					'input_textarea_width' => array(
						'type'        => 'text',
						'label'       => __( 'Textarea Field Width', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => '',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group.njba-message',
							'property' => 'width'
						)
					),
					'input_button_width'   => array(
						'type'        => 'text',
						'label'       => __( 'Button Width', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => '',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-contact-form-submit',
							'property' => 'width'
						)
					),
					'inputs_space'         => array(
						'type'        => 'text',
						'label'       => __( 'Spacing Between Inputs Top/Bottom', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => '1',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group',
							'property' => 'margin-bottom'
						)
					),
					'input_spacing'        => array(
						'type'        => 'text',
						'label'       => __( 'Spacing Between Inputs', 'bb-njba' ),
						'description' => 'px',
						'size'        => 5,
						'default'     => '1',
					),
				)
			),
			'title_style'       => array( // Section
				'title'  => __( 'Title', 'bb-njba' ),
				'fields' => array(
					'title_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'text-align'
						)
					),
					'title_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color' ),
						'default'    => '333333',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'color',
						)
					),
					'title_margin'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 20,
							'right'  => 0,
							'bottom' => 20,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),
			'description_style' => array( // Section
				'title'  => __( 'Description', 'bb-njba' ),
				'fields' => array(
					'description_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'text-align'
						)
					),
					'description_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '333333',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'color',
						)
					),
					'description_margin'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 20,
							'right'  => 0,
							'bottom' => 20,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),
			'label_style'       => array( // Section
				'title'  => __( 'Label', 'bb-njba' ),
				'fields' => array(
					'form_label_color' => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '333333',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group label',
							'property' => 'color'
						)
					),
				)
			),
			'error-style'       => array(
				'title'  => __( 'Validation Style', 'bb-njba' ),
				'fields' => array(
					'invalid_msg_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Input Message Color', 'bb-njba' ),
						'default'    => 'dd4420',
						'show_reset' => true,
						'help'       => __( 'This color would be applied to validation message and error icon in input field', 'bb-njba' ),
						'preview'    => 'none'
					),
					'invalid_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Input border color', 'bb-njba' ),
						'default'    => 'dd4420',
						'show_reset' => true,
						'help'       => __( 'If the validation is not right then this color would be applied to input border', 'bb-njba' ),
						'preview'    => 'none'
					),
					'success_msg_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Success Message Color', 'bb-njba' ),
						'default'    => '10b210',
						'show_reset' => true,
						'preview'    => 'none'
					),
					'error_msg_color'      => array(
						'type'       => 'color',
						'label'      => __( 'Error Message color', 'bb-njba' ),
						'default'    => 'dd4420',
						'show_reset' => true,
						'preview'    => 'none'
					),
				)
			),
		)
	),
	'reCAPTCHA'       => array(
		'title'       => __( 'Captcha', 'fl-builder' ),
		'sections'    => array(
			'recaptcha_general' => array(
				'title'  => '',
				'fields' => array(
					'recaptcha_toggle'        => array(
						'type'    => 'select',
						'label'   => 'reCAPTCHA Field',
						'default' => 'hide',
						'options' => array(
							'show' => __( 'Show', 'fl-builder' ),
							'hide' => __( 'Hide', 'fl-builder' ),
						),
						'toggle'  => array(
							'show' => array(
								'fields' => array( 'recaptcha_site_key', 'recaptcha_secret_key', 'recaptcha_validate_type', 'recaptcha_theme' ),
							),
						),
						'help'    => __( 'If you want to show this field, please provide valid Site and Secret Keys.', 'fl-builder' ),
					),
					'recaptcha_site_key'      => array(
						'type'    => 'text',
						'label'   => __( 'Site Key', 'fl-builder' ),
						'default' => '',
						'preview' => array(
							'type' => 'none',
						),
					),
					'recaptcha_secret_key'    => array(
						'type'    => 'text',
						'label'   => __( 'Secret Key', 'fl-builder' ),
						'default' => '',
						'preview' => array(
							'type' => 'none',
						),
					),
					'recaptcha_validate_type' => array(
						'type'    => 'select',
						'label'   => __( 'Validate Type', 'fl-builder' ),
						'default' => 'normal',
						'options' => array(
							'normal'    => __( '"I\'m not a robot" checkbox', 'fl-builder' ),
							'invisible' => __( 'Invisible', 'fl-builder' ),
						),
						'help'    => __( 'Validate users with checkbox or in the background.<br />Note: Checkbox and Invisible types use seperate API keys.',
							'fl-builder' ),
						'preview' => array(
							'type' => 'none',
						),
					),
					'recaptcha_theme'         => array(
						'type'    => 'select',
						'label'   => __( 'Theme', 'fl-builder' ),
						'default' => 'light',
						'options' => array(
							'light' => __( 'Light', 'fl-builder' ),
							'dark'  => __( 'Dark', 'fl-builder' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
		),
		'description' => sprintf( __( 'Please register keys for your website at the <a%s>Google Admin Console</a>.', 'fl-builder' ),
			' href="https://www.google.com/recaptcha/admin" target="_blank"' ),
	),
	'input'           => array(
		'title'    => __( 'Input', 'bb-njba' ),
		'sections' => array(
			'input-colors'       => array(
				'title'  => __( 'Input Color', 'bb-njba' ),
				'fields' => array(
					'input_text_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'default'    => '333333',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-input-group-wrap .njba-input-group textarea, .njba-input-group-wrap .njba-input-group input',
							'property' => 'color'
						)
					),
					'input_background_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group-wrap input, .njba-contact-form .njba-input-group-wrap input:focus, .njba-contact-form .njba-input-group-wrap textarea',
							'property' => 'background-color'
						)
					),
					'input_background_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'input_icon_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-input-group .njba-input-icon i',
							'property' => 'color'
						)
					),
					'input_icon_size'            => array(
						'type'        => 'text',
						'label'       => __( 'Icon Size', 'bb-njba' ),
						'placeholder' => __( 'Inherit', 'bb-njba' ),
						'size'        => '8',
						'description' => 'px',
						'help'        => __( 'If icon size is kept bank then title font size would be applied', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-input-group .njba-input-icon i',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'position_top'               => array(
						'type'        => 'text',
						'label'       => __( 'Icon Position Top', 'bb-njba' ),
						'placeholder' => __( 'Inherit', 'bb-njba' ),
						'size'        => '8',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-input-group .njba-input-icon',
							'property' => 'top',
							'unit'     => 'px'
						)
					),
					'position_left'              => array(
						'type'        => 'text',
						'label'       => __( 'Icon Position Left', 'bb-njba' ),
						'placeholder' => __( 'Inherit', 'bb-njba' ),
						'size'        => '8',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-input-group .njba-input-icon',
							'property' => 'left',
							'unit'     => 'px'
						)
					),

				)
			),
			'input-border-style' => array(
				'title'  => __( 'Input Border Style', 'bb-njba' ),
				'fields' => array(
					'input_border_width' => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'placeholder' => '1',
						'default'     => '1',
						'description' => 'px',
						'maxlength'   => '2',
						'size'        => '6',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group-wrap input, .njba-contact-form .njba-input-group-wrap input:focus, .njba-contact-form .njba-input-group-wrap textarea',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),

					'input_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'cccccc',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group-wrap input, .njba-contact-form .njba-input-group-wrap input:focus, .njba-contact-form .njba-input-group-wrap textarea',
							'property' => 'border-color',
						)
					),

					'input_border_radius' => array(
						'type'        => 'text',
						'default'     => '5',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Round Corners', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group-wrap input, .njba-contact-form .njba-input-group-wrap input:focus, .njba-contact-form .njba-input-group-wrap textarea',
							'property' => 'border-radius',
							'unit'     => 'px'
						)
					),
					'border_style'        => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input, .njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
							'property' => 'border-style',
							'unit'     => 'px'
						)
					),
					'input_border'        => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'bottom' => 1,
							'left'   => '',
							'right'  => '',
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input',
									'property' => 'border-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input',
									'property' => 'border-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input',
									'property' => 'border-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input',
									'property' => 'border-left',
									'unit'     => 'px'
								),
							)
						)
					),
					'textarea_border'     => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Textarea Border', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => 1,
							'left'   => 1,
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
									'property' => 'border-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
									'property' => 'border-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
									'property' => 'border-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
									'property' => 'border-left',
									'unit'     => 'px'
								),
							)
						)
					),
					'border_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'cccccc',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input, .njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
							'property' => 'border-color'
						)
					),

					'border_radius' => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Round Corners', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form.njba-form-style2 .njba-input-group-wrap input, .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea, .njba-contact-form.njba-form-style3 .njba-input-group-wrap input, .njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea',
							'property' => 'border-radius',
							'unit'     => 'px'
						)
					),
				)
			),
			'input-fields'       => array(
				'title'  => __( 'Input Size and Aignment', 'bb-njba' ),
				'fields' => array(
					'input_text_align' => array(
						'type'    => 'select',
						'label'   => __( 'Text Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form .njba-input-group-wrap input, .njba-contact-form .njba-input-group-wrap input:focus, .njba-contact-form .njba-input-group-wrap textarea',
							'property' => 'text-align'
						)
					),
					'msg_height'       => array(
						'type'        => 'text',
						'label'       => __( 'Textarea Height', 'bb-njba' ),
						'placeholder' => '130',
						'size'        => '8',
						'description' => __( 'px', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form textarea',
							'property' => 'min-height',
							'unit'     => 'px'
						)
					),
					'input_padding'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 15,
							'right'  => 15,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form textarea, .njba-contact-form input[type="text"], .njba-contact-form input[type="tel"], .njba-contact-form input[type="email"]',
									'property' => 'padding-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form textarea, .njba-contact-form input[type="text"], .njba-contact-form input[type="tel"], .njba-contact-form input[type="email"]',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form textarea, .njba-contact-form input[type="text"], .njba-contact-form input[type="tel"], .njba-contact-form input[type="email"]',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-contact-form textarea, .njba-contact-form input[type="text"], .njba-contact-form input[type="tel"], .njba-contact-form input[type="email"]',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),

		)
	),
	'button'          => array(
		'title'    => __( 'Button', 'bb-njba' ),
		'sections' => array(
			'button-style'  => array(
				'title'  => __( 'Submit Icon Button', 'bb-njba' ),
				'fields' => array(
					'btn_text'             => array(
						'type'    => 'text',
						'label'   => __( 'Text', 'bb-njba' ),
						'default' => 'Send Your Message',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-button-text'
						)
					),
					'buttton_icon_select'  => array(
						'type'    => 'select',
						'label'   => __( 'Icon Type', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'      => __( 'None', 'bb-njba' ),
							'font_icon' => __( 'Icon', 'bb-njba' ),
						),
						'toggle'  => array(
							'font_icon' => array(
								'fields'   => array( 'button_font_icon', 'button_icon_aligment', 'icon_color', 'icon_margin' ),
								'sections' => array( '' ),
							),

						)
					),
					'button_font_icon'     => array(
						'type'  => 'icon',
						'label' => __( 'Icon', 'bb-njba' )
					),
					'button_icon_aligment' => array(
						'type'    => 'select',
						'label'   => __( 'Icon Position', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'  => __( 'Before Text', 'bb-njba' ),
							'right' => __( 'After Text', 'bb-njba' )
						),
					),
					'icon_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn i',
							'property' => 'color'
						)
					),
					'icon_margin'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)

						)
					),
				)
			),
			'btn-colors'    => array(
				'title'  => __( 'Button Colors', 'bb-njba' ),
				'fields' => array(
					'btn_text_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'color'
						)
					),
					'btn_text_hover_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Hover Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none'
						)
					),
					'btn_background_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '03a9f4',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'background'
						)
					),
					'btn_background_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '6d6d6d',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none'
						)
					),
				)
			),
			'btn-structure' => array(
				'title'  => __( 'Button Structure', 'bb-njba' ),
				'fields' => array(
					'btn_align'              => array(
						'type'    => 'select',
						'label'   => __( 'Button Width/Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main',
							'property' => 'text-align'
						)
					),
					'btn_border_style'       => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-style'
						)
					),
					'btn_border_width'       => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => 1,
							'left'   => 1,
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-top-width',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-bottom-width',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-left-width',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-right-width',
									'unit'     => 'px'
								),
							)
						)
					),
					'btn_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '03a9f4',
						'show_reset' => true,
					),
					'btn_hover_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Hover Border Color', 'bb-njba' ),
						'default'    => 'bababa',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none'
						)
					),
					'btn_radius'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top-left'     => 5,
							'top-right'    => 5,
							'bottom-left'  => 5,
							'bottom-right' => 5
						),
						'options'     => array(
							'top-left'     => array(
								'placeholder' => __( 'Top Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-top-left-radius',
									'unit'     => 'px'
								)
							),
							'top-right'    => array(
								'placeholder' => __( 'Top Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-top-right-radius',
									'unit'     => 'px'
								),
							),
							'bottom-left'  => array(
								'placeholder' => __( 'Bottom Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-bottom-left-radius',
									'unit'     => 'px'
								),
							),
							'bottom-right' => array(
								'placeholder' => __( 'Bottom Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-bottom-right-radius',
									'unit'     => 'px'
								),
							)

						)
					),
					'btn_box_shadow'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow', 'bb-njba' ),
						'description' => 'px',
						'show_reset'  => true,
						'default'     => array(
							'left_right' => 0,
							'top_bottom' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'left_right' => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-h'
							),
							'top_bottom' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-v'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa fa-circle-thin'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa fa-circle'
							)
						),
					),
					'btn_shadow_color'       => array(
						'type'    => 'color',
						'label'   => __( 'Shadow Color', 'bb-njba' ),
						'default' => '000000',
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'box-shadow'
						)
					),
					'btn_padding'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 8,
							'right'  => 10,
							'bottom' => 8,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							)

						)
					),
					'btn_margin'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)

						)
					),
				)
			),
		)
	),
	'form_typography' => array( // Tab
		'title'    => __( 'Typography', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'title_typography'       => array( // Section
				'title'  => __( 'Title', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'title_font_family' => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 500
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'title_font_size'   => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'title_line_height' => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
				)
			),
			'description_typography' => array(
				'title'  => __( 'Description', 'bb-njba' ),
				'fields' => array(
					'description_font_family' => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
					),
					'description_font_size'   => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'description_line_height' => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
				)
			),
			'label_typography'       => array( // Section
				'title'  => __( 'Label', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'label_font_family'    => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'label_font_size'      => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form label',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'label_text_transform' => array(
						'type'    => 'select',
						'label'   => __( 'Text Transform', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'      => __( 'Default', 'bb-njba' ),
							'lowercase' => __( 'lowercase', 'bb-njba' ),
							'uppercase' => __( 'UPPERCASE', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form label',
							'property' => 'text-transform',
						)
					),
				)
			),
			'input_typography'       => array( // Section
				'title'  => __( 'Input', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'input_font_family'    => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'input_font_size'      => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form input, .njba-contact-form textarea',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'input_text_transform' => array(
						'type'    => 'select',
						'label'   => __( 'Text Transform', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'      => __( 'Default', 'bb-njba' ),
							'lowercase' => __( 'lowercase', 'bb-njba' ),
							'uppercase' => __( 'UPPERCASE', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-contact-form input, .njba-contact-form textarea',
							'property' => 'text-transform',
						)
					),
				)
			),
			'button_typography'      => array( // Section
				'title'  => __( 'Button', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'button_font_family' => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'button_font_size'   => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'font-size',
							'unit'     => 'px'
						),
					),
				)
			),
		)
	),

) );
