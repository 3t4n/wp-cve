<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * If user is deactivating plugin, find out why
 */
class EPHD_Deactivate_Feedback {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_feedback_dialog_scripts' ] );
		add_action( 'wp_ajax_ephd_deactivate_feedback', [ $this, 'ajax_ephd_deactivate_feedback' ] );
	}

	/**
	 * Enqueue feedback dialog scripts.
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', [ $this, 'output_deactivate_feedback_dialog' ] );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'ephd-admin-feedback', Echo_Help_Dialog::$plugin_url . 'js/admin-feedback' . $suffix . '.js', array('jquery'), Echo_Help_Dialog::$version );
		wp_register_style( 'ephd-admin-feedback-style', Echo_Help_Dialog::$plugin_url . 'css/admin-plugin-feedback' . $suffix . '.css', array(), Echo_Help_Dialog::$version );

		wp_enqueue_script( 'ephd-admin-feedback' );
		wp_enqueue_style( 'ephd-admin-feedback-style' );
	}

	/**
	 * Display a dialog box to ask the user why they deactivated the Help Dialog.
	 */
	public function output_deactivate_feedback_dialog() {

		$first_version = get_option( 'ephd_version_first' );
		$current_version = get_option( 'ephd_version' );
		if ( version_compare( $first_version, $current_version, '==' ) ) {
			$deactivate_reasons = $this->get_deactivate_reasons( 1 );
		} else {
			$deactivate_reasons = $this->get_deactivate_reasons( 2 );
		} 	?>

        <div class="ephd-deactivate-modal" id="ephd-deactivate-modal" style="display:none;">
            <div class="ephd-deactivate-modal-wrap">
                <form id="ephd-deactivate-feedback-dialog-form" method="post">
                    <div class="ephd-deactivate-modal-header">
                        <h3><?php echo esc_html__( 'Quick Feedback', 'help-dialog' ); ?></h3>
                    </div>
                    <div class="ephd-deactivate-modal-body">
                        <p><?php echo __( 'Please choose a reason to deactivate:', 'help-dialog' ) ?></p>
                        <ul class="ephd-deactivate-reasons">						    <?php
	                        foreach ( $deactivate_reasons as $reason_key => $reason_escaped_html ) { ?>
                                <li>
                                    <label>
                                        <input type="radio" name="reason_key" value="<?php echo esc_attr( $reason_key ); ?>" required>  <?php
                                        if ( ! empty( $reason_escaped_html['icon'] ) ) {    ?>
                                            <div class="ephd-deactivate-reason-icon <?php echo esc_attr( $reason_escaped_html['icon'] ); ?>"></div>  <?php
                                        }   ?>
                                        <div class="ephd-deactivate-reason-text"><?php echo esc_html( $reason_escaped_html['title'] ); ?></div>
                                    </label>
                                </li>						    <?php
	                        } ?>
                        </ul>
                        <div class="ephd-deactivate-modal-reason-input-wrap">   <?php
                            foreach ( $deactivate_reasons as $reason_key => $reason_escaped_html ) {    ?>
                                <div class="ephd-deactivate-modal-reason-inputs ephd-deactivate-modal-reason-inputs--<?php echo esc_attr( $reason_key ); ?>">   <?php
                                    if ( ! empty( $reason_escaped_html['input_placeholder'] ) ) {   ?>
                                        <textarea name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason_escaped_html['input_placeholder'] ); ?>"></textarea>   <?php
                                    }
	                                if ( ! empty( $reason_escaped_html['custom_content'] ) ) {  ?>
                                        <div class="ephd-deactivate-feedback-custom-content"><?php echo $reason_escaped_html['custom_content']; ?></div> <?php
	                                }
	                                if ( isset( $reason_escaped_html['contact_email']['title'] ) ) {   ?>
                                        <div class="ephd-deactivate-feedback-contact">
                                            <p><?php echo esc_html( $reason_escaped_html['contact_email']['title'] ); ?></p>
                                            <input type="email"
                                                   name="contact_email_<?php echo esc_attr( $reason_key ); ?>"
                                                   name="feedback-contact"
                                                   class="ephd-deactivate-feedback-contact-input" <?php
                                                    if ( ! empty( $reason_escaped_html['contact_email']['required'] ) ) {    ?>
                                                        placeholder="<?php echo __( 'Enter Email (Required)', 'help-dialog' ); ?>"
                                                        data-required="true" <?php
                                                    } else { ?>
                                                        placeholder="<?php echo __( 'Enter Email (optional)', 'help-dialog' ); ?>"  <?php
                                                    }   ?>
                                            />
                                        </div>  <?php
	                                }   ?>
                                </div>  <?php
		                    }   ?>
                        </div>
                        <p class="ephd-deactivate-modal-reasons-bottom">
	                        <?php //echo esc_html__( 'Bottom text', 'help-dialog' ); ?>
                        </p>
                    </div>

                    <div class="ephd-deactivate-modal-footer">
	                    <button class="ephd-deactivate-submit-modal"><?php echo esc_html__( 'Submit & Deactivate', 'help-dialog' ); ?></button>
	                    <button class="ephd-deactivate-button-secondary ephd-deactivate-cancel-modal"><?php echo esc_html__( 'Cancel', 'help-dialog' ); ?></button>
                        <a href="#" class="ephd-deactivate-button-secondary ephd-deactivate-skip-modal"><?php echo esc_html__( 'Skip & Deactivate', 'help-dialog' ); ?></a>
	                    <input type="hidden" name="action" value="ephd_deactivate_feedback" />  <?php
                        wp_nonce_field( '_ephd_deactivate_feedback_nonce' );    ?>
                    </div>
                </form>
            </div>
        </div>  <?php
	}

	/**
	 * Send the user feedback when Help Dialog is deactivated.
	 */
	public function ajax_ephd_deactivate_feedback() {
		global $wp_version;

		$wpnonce_value = EPHD_Utilities::post( '_wpnonce' );
		if ( empty( $wpnonce_value ) || ! wp_verify_nonce( $wpnonce_value, '_ephd_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}

		$reason_type = EPHD_Utilities::post( 'reason_key', 'N/A' );
		$reason_input = EPHD_Utilities::post( "reason_{$reason_type}", 'N/A' );
		$first_version = get_option( 'ephd_version_first' );

        // retrieve email
		$contact_email = EPHD_Utilities::post( "contact_email_{$reason_type}", '' );
		$contact_email = is_email( $contact_email ) ? $contact_email : '';
		$contact_user = ( ! empty( $contact_email ) ) ? 'Yes' : 'No';

		//Theme Name and Version
		$active_theme = wp_get_theme();
		$theme_info = $active_theme->get( 'Name' ) . ' ' . $active_theme->get( 'Version' );

		// send feedback
		$api_params = array(
			'ephd_action'       => 'ephd_process_user_feedback',
			'feedback_type'     => $reason_type,
			'feedback_input'    => $reason_input,
			'plugin_name'       => 'Help Dialog',
			'plugin_version'    => class_exists( 'Echo_Help_Dialog' ) ? Echo_Help_Dialog::$version : 'N/A',
			'first_version'     => empty( $first_version ) ? 'N/A' : $first_version,
			'wp_version'        => $wp_version,
			'theme_info'        => $theme_info,
			'contact_user'      => $contact_email . ' - ' . $contact_user
		);

		// Call the API
		wp_remote_post(
			esc_url_raw( add_query_arg( $api_params, 'https://www.echoknowledgebase.com' ) ),
			array(
				'timeout'   => 15,
				'body'      => $api_params,
				'sslverify' => false
			)
		);

		if ( $contact_user == 'Yes' ) { 
			$user = EPHD_Utilities::get_current_user();
			$first_name = $user->first_name;
			// not translations
			$subject = esc_html( 'HD - Plugin Deactivation' );
			$message =  esc_html( 'Name' ) . ': ' . esc_html( $first_name ) . ' \r\n' .
				esc_html( 'Email' ) . ': ' . esc_html( $contact_email ) . ' \r\n' .
				esc_html( 'Feedback Type' ) . ': ' . esc_html( $reason_type ) . ' \r\n' .
				esc_html( 'Feedback Input' ) . ': ' . esc_html( $reason_input );

			// send the email
			EPHD_Utilities::send_email( $message, 'help@echoknowledgebase.com', $contact_email, $first_name, $subject );
		}

		wp_send_json_success();
	}

	private function get_deactivate_reasons( $type ) {

		switch ( $type ) {
		   case 1:
		   	    $deactivate_reasons = [
			        'missing_feature'                => [
				        'title'             => __( 'I cannot find a feature', 'help-dialog' ),
				        'icon'              => 'ephdfa ephdfa-puzzle-piece',
				        'input_placeholder' => __( 'Please tell us what is missing', 'help-dialog' ),
				        'contact_email'     => [
                            'title'    => __( 'Let us help you find the feature. Please provide your contact email:', 'help-dialog' ),
                            'required' => false,
                        ],
			        ],
			        'couldnt_get_the_plugin_to_work' => [
				        'title'             => __( 'I couldn\'t get the plugin to work', 'help-dialog' ),
				        'icon'              => 'ephdfa ephdfa-question-circle-o',
				        'input_placeholder' => __( 'Please share the reason', 'help-dialog' ),
				        'contact_email'     => [
					        'title'    => __( 'Sorry to hear that. Let us help you. Please provide your contact email:', 'help-dialog' ),
					        'required' => false,
				        ],
			        ],
			        'bug_issue'                      => [
				        'title'             => __( 'Bug Issue', 'help-dialog' ),
				        'icon'              => 'ephdfa ephdfa-bug',
				        'input_placeholder' => __( 'Please describe the bug', 'help-dialog' ),
				        'contact_email'     => [
					        'title'    => __( 'We can fix the bug right away. Please provide your contact email:', 'help-dialog' ),
					        'required' => true,
				        ]
			        ],
			        'other'                          => [
				        'title'             => __( 'Other', 'help-dialog' ),
				        'icon'              => 'ephdfa ephdfa-ellipsis-h',
				        'input_placeholder' => __( 'Please share the reason', 'help-dialog' ),
				        'contact_email'     => [
					        'title'    => __( 'Can we talk to you about reason for removing the plugin?', 'help-dialog' ),
					        'required' => false,
				        ]
			        ],
			   ];
			   break;
		    case 2:
			default:
				$deactivate_reasons = [
					'no_longer_needed' => [
						'title'             => __( 'I no longer need the plugin', 'help-dialog' ),
						'icon'              => 'ephdfa ephdfa-question-circle-o',
						'custom_content'    => __( 'Thanks for using our products and have a great week', 'help-dialog' ) . '!',
						'input_placeholder' => '',
					],
					'missing_feature'  => [
						'title'             => __( 'I cannot find a feature', 'help-dialog' ),
						'icon'              => 'ephdfa ephdfa-puzzle-piece',
						'input_placeholder' => __( 'Please tell us what is missing', 'help-dialog' ),
						'contact_email'     => [
							'title'    => __( 'Let us help you find the feature. Please provide your contact email:', 'help-dialog' ),
							'required' => false,
						],
					],
					'bug_issue'                      => [
						'title'             => __( 'Bug Issue', 'help-dialog' ),
						'icon'              => 'ephdfa ephdfa-bug',
						'input_placeholder' => __( 'Please describe the bug', 'help-dialog' ),
						'contact_email'     => [
							'title'    => __( 'We can fix the bug right away. Please provide your contact email:', 'help-dialog' ),
							'required' => true,
						]
					],
					'other'            => [
						'title'             => __( 'Other', 'help-dialog' ),
						'icon'              => 'ephdfa ephdfa-ellipsis-h',
						'input_placeholder' => __( 'Please share the reason', 'help-dialog' ),
						'contact_email'     => [
							'title'    => __( 'Can we talk to you about reason to remove the plugin?', 'help-dialog' ),
							'required' => false,
						]
					]
			   ];
			   break;
	   }

		return $deactivate_reasons;
	}
}
