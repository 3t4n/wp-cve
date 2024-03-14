<?php
/* Main tab settings class() from options.php */
if ( !class_exists( 'ppws_form_settings' ) ) {


    /* Main option key for store data for Form Settings */
    $ppws_form_settings_option = get_option( 'ppws_form_content_option' );
    class ppws_form_settings {
        public function __construct() {
            add_action( 'admin_init', array( $this, 'ppws_form_settings_register_settings_init' ) );
        }

        /* Form creation class() call back function from options.php */
        function ppws_form_settings_callback() {
            ?>
            <form action="options.php?tab=form-content" method="post">
                <?php settings_fields( 'ppws-settings-options' ); 
                echo '<p class="ppws-note ppws-note-info">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18px" height="18px" x="0" y="0" viewBox="0 0 23.625 23.625" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <path style="" d="M11.812,0C5.289,0,0,5.289,0,11.812s5.289,11.813,11.812,11.813s11.813-5.29,11.813-11.813   S18.335,0,11.812,0z M14.271,18.307c-0.608,0.24-1.092,0.422-1.455,0.548c-0.362,0.126-0.783,0.189-1.262,0.189   c-0.736,0-1.309-0.18-1.717-0.539s-0.611-0.814-0.611-1.367c0-0.215,0.015-0.435,0.045-0.659c0.031-0.224,0.08-0.476,0.147-0.759   l0.761-2.688c0.067-0.258,0.125-0.503,0.171-0.731c0.046-0.23,0.068-0.441,0.068-0.633c0-0.342-0.071-0.582-0.212-0.717   c-0.143-0.135-0.412-0.201-0.813-0.201c-0.196,0-0.398,0.029-0.605,0.09c-0.205,0.063-0.383,0.12-0.529,0.176l0.201-0.828   c0.498-0.203,0.975-0.377,1.43-0.521c0.455-0.146,0.885-0.218,1.29-0.218c0.731,0,1.295,0.178,1.692,0.53   c0.395,0.353,0.594,0.812,0.594,1.376c0,0.117-0.014,0.323-0.041,0.617c-0.027,0.295-0.078,0.564-0.152,0.811l-0.757,2.68   c-0.062,0.215-0.117,0.461-0.167,0.736c-0.049,0.275-0.073,0.485-0.073,0.626c0,0.356,0.079,0.599,0.239,0.728   c0.158,0.129,0.435,0.194,0.827,0.194c0.185,0,0.392-0.033,0.626-0.097c0.232-0.064,0.4-0.121,0.506-0.17L14.271,18.307z    M14.137,7.429c-0.353,0.328-0.778,0.492-1.275,0.492c-0.496,0-0.924-0.164-1.28-0.492c-0.354-0.328-0.533-0.727-0.533-1.193   c0-0.465,0.18-0.865,0.533-1.196c0.356-0.332,0.784-0.497,1.28-0.497c0.497,0,0.923,0.165,1.275,0.497   c0.353,0.331,0.53,0.731,0.53,1.196C14.667,6.703,14.49,7.101,14.137,7.429z" fill="#030104" data-original="#030104" class=""></path></svg> ';
                echo _e('"Form Content" tab provides many options to customize password protected form. Like Form content, Field placeholder, Valdiation message, etc.','password-protected-store-for-woocommerce');
                echo '</p>';
                ?>
                <div class="ppws-section">
                    <?php do_settings_sections( 'ppws-form-settings-section' );
                    ?>
                </div>
                <div class="ppws-submit-btn">

                    <?php submit_button( 'Save Setting' ); ?>
                </div>
            </form>
            <?php
        }

        /* Main function for register and rendering settings field */
        public function ppws_form_settings_register_settings_init() {
            /* hook for register settings */
            register_setting( 'ppws-settings-options', 'ppws_form_content_option', array( $this, 'sanitize_settings' ) );

            add_settings_section( 'ppws_form_settings_section', __( 'Form Settings', 'password-protected-store-for-woocommerce' ), array(), 'ppws-form-settings-section' );

            add_settings_field( 'ppws_form_title_textbox', __( 'Form Title', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'text', 'label_for' => 'ppws_form_mian_title', 'description' => 'Set title for password form shown on fontend.', 'placeholder' => 'Set form title' ]  );

            add_settings_field( 'ppws_form_above_content_textarea', __( 'Form Above Content', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'textarea', 'editor_id' => 'editor_one', 'label_for' => 'ppws_form_above_content', 'description' => 'Set above content for password form shown on fontend.', 'placeholder' => 'Put content here.' ] );

            add_settings_field( 'ppws_form_below_content_textarea', __( 'Form Below Content', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'textarea', 'editor_id' => 'editor_two', 'label_for' => 'ppws_form_below_content', 'description' => 'Set below content for password form shown on fontend.', 'placeholder' => 'Put content here.' ] );

            add_settings_field( 'ppws_form_button_text_textbox', __( 'Submit Button Text', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'text', 'label_for' => 'ppws_form_submit_btn_text', 'description' => 'Set submit button text for password form shown on fontend.', 'placeholder' => 'Set form button text' ] );
           
            add_settings_field( 'ppws_form_placeholder_textbox', __( 'Form Inputbox placeholder', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'text', 'label_for' => 'ppws_form_pwd_placeholder', 'description' => 'Set inputbox placeholder for password form shown on fontend.', 'placeholder' => 'Set form inputbox placeholder' ]  );
    
            add_settings_field( 'ppws_incorrect_password_message_textbox', __( 'Incorrect Password Message', 'password-protected-store-for-woocommerce' ), array( $this, 'ppws_form_settings_fun' ), 'ppws-form-settings-section', 'ppws_form_settings_section', [ 'type' => 'text', 'label_for' => 'ppws_incorrect_password_message', 'description' => 'Change incorrect password message text. Default: Password mismatch', 'placeholder' => 'Password mismatch' ]  );        
        }

        public function ppws_form_settings_fun( $args ) {
            global $ppws_form_settings_option;
            $value = isset( $ppws_form_settings_option[ $args[ 'label_for' ] ] ) ? $ppws_form_settings_option[ $args[ 'label_for' ] ] : '';

            if ( $args[ 'type' ] == 'text' ) {
                ?>
                <input type="text" class="ppws-textbox" name="ppws_form_content_option[<?php esc_attr_e( $args[ 'label_for' ] ) ?>]" id="<?php esc_attr_e( $args[ 'label_for' ] ) ?>" placeholder="<?php esc_attr_e( $args[ 'placeholder' ] ) ?>" value="<?php esc_attr_e($value); ?>">
                  <p class="ppws-note"> 
                    <?php 
                      $allowed_html = array( 'br'     => array(), );
                    echo wp_kses( $args['description'], $allowed_html ); ?> 
                </p>
                <?php
            } elseif ( $args[ 'type' ] == 'textarea' ) {
                $content = esc_html__($value);
                    
                $settings = array(
                    'textarea_name' => "ppws_form_content_option[". $args[ 'label_for' ]."]",
                   
                    'editor_css'    => '<style>.ppws-wp-editor-block .wp-editor-area{height:300px; width:100%;}</style>',
                ); 
                ?>
    
                <div class="ppws-wp-editor-block">
                    <?php //wp_editor( htmlspecialchars_decode($value), $args[ 'editor_id' ], $settings ); ?>
                    <textarea name="ppws_form_content_option[<?php esc_attr_e( $args[ 'label_for' ] ) ?>];" id="<?php esc_attr_e($args[ 'label_for' ]); ?>" rows="12" class="wp-editor wp-editor-area"> <?php  echo wp_unslash($content); ?></textarea>
                </div>
                  <p class="ppws-note"> 
                    <?php 
                      $allowed_html = array( 'br'     => array(), );
                    echo wp_kses( $args['description'], $allowed_html ); ?> 
                </p>
                <?php
            }
        }

        public function sanitize_settings( $input ) {
            $new_input = array();

            if( isset( $input[ 'ppws_form_mian_title' ] ) && !empty( $input[ 'ppws_form_mian_title' ] ) ) {
                $new_input[ 'ppws_form_mian_title' ] = sanitize_text_field( $input[ 'ppws_form_mian_title' ] );
            }

            if( isset( $input[ 'ppws_form_above_content' ] ) && !empty( $input[ 'ppws_form_above_content' ] ) ) {
                $new_input[ 'ppws_form_above_content' ] = sanitize_text_field( htmlentities($input[ 'ppws_form_above_content' ]) );
            }

            if( isset( $input[ 'ppws_form_below_content' ] ) && !empty( $input[ 'ppws_form_below_content' ] ) ) {
                $new_input[ 'ppws_form_below_content' ] = sanitize_text_field( htmlentities($input[ 'ppws_form_below_content' ]) );
            }

            if( isset( $input[ 'ppws_form_submit_btn_text' ] ) && !empty( $input[ 'ppws_form_submit_btn_text' ] ) ) {
                $new_input[ 'ppws_form_submit_btn_text' ] = sanitize_text_field( $input[ 'ppws_form_submit_btn_text' ] );
            }

            if( isset( $input[ 'ppws_form_pwd_placeholder' ] ) && !empty( $input[ 'ppws_form_pwd_placeholder' ] ) ) {
                $new_input[ 'ppws_form_pwd_placeholder' ] = sanitize_text_field( $input[ 'ppws_form_pwd_placeholder' ] );
            }

            if( isset( $input[ 'ppws_incorrect_password_message' ] ) && !empty( $input[ 'ppws_incorrect_password_message' ] ) ) {
                $new_input[ 'ppws_incorrect_password_message' ] = sanitize_text_field( $input[ 'ppws_incorrect_password_message' ] );
            }

            return $new_input;
        }
    }
}
?>