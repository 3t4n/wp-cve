<?php
if (!class_exists('ppws_advanced_settings')) {
    /* Main key */
    $ppws_advanced_settings = get_option('ppws_advanced_settings');
    class ppws_advanced_settings{

        public function __construct(){
            add_action('admin_init', array($this, 'ppws_advanced_settings_init'));
        }

        function ppws_advanced_settings_callback() {
            global $ppws_advanced_settings; ?>
            <form action="options.php?tab=advanced" class="ppws-advanced-setting-form" method="post">
                <?php 
                settings_fields('ppws-settings-options'); 
                
                echo '<p class="ppws-note ppws-note-info">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="18px" height="18px" x="0" y="0" viewBox="0 0 23.625 23.625" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <path style="" d="M11.812,0C5.289,0,0,5.289,0,11.812s5.289,11.813,11.812,11.813s11.813-5.29,11.813-11.813   S18.335,0,11.812,0z M14.271,18.307c-0.608,0.24-1.092,0.422-1.455,0.548c-0.362,0.126-0.783,0.189-1.262,0.189   c-0.736,0-1.309-0.18-1.717-0.539s-0.611-0.814-0.611-1.367c0-0.215,0.015-0.435,0.045-0.659c0.031-0.224,0.08-0.476,0.147-0.759   l0.761-2.688c0.067-0.258,0.125-0.503,0.171-0.731c0.046-0.23,0.068-0.441,0.068-0.633c0-0.342-0.071-0.582-0.212-0.717   c-0.143-0.135-0.412-0.201-0.813-0.201c-0.196,0-0.398,0.029-0.605,0.09c-0.205,0.063-0.383,0.12-0.529,0.176l0.201-0.828   c0.498-0.203,0.975-0.377,1.43-0.521c0.455-0.146,0.885-0.218,1.29-0.218c0.731,0,1.295,0.178,1.692,0.53   c0.395,0.353,0.594,0.812,0.594,1.376c0,0.117-0.014,0.323-0.041,0.617c-0.027,0.295-0.078,0.564-0.152,0.811l-0.757,2.68   c-0.062,0.215-0.117,0.461-0.167,0.736c-0.049,0.275-0.073,0.485-0.073,0.626c0,0.356,0.079,0.599,0.239,0.728   c0.158,0.129,0.435,0.194,0.827,0.194c0.185,0,0.392-0.033,0.626-0.097c0.232-0.064,0.4-0.121,0.506-0.17L14.271,18.307z    M14.137,7.429c-0.353,0.328-0.778,0.492-1.275,0.492c-0.496,0-0.924-0.164-1.28-0.492c-0.354-0.328-0.533-0.727-0.533-1.193   c0-0.465,0.18-0.865,0.533-1.196c0.356-0.332,0.784-0.497,1.28-0.497c0.497,0,0.923,0.165,1.275,0.497   c0.353,0.331,0.53,0.731,0.53,1.196C14.667,6.703,14.49,7.101,14.137,7.429z" fill="#030104" data-original="#030104" class=""></path></svg> ';
                echo _e('This plugin has advanced settings that let you add head, body or footer scripts and give option to include wordpress functions like wp_head & wp_footer.','password-protected-store-for-woocommerce');
                echo '</p>';
                ?>
                
                <div class="ppws-section">
                    <?php do_settings_sections('ppws-general-advanced-settings-section'); ?>
                </div>
                <div class="ppws-section ppws-section-user">
                    <?php do_settings_sections('ppws-advanced-scripts-settings-section');  ?>
                </div>
                <div class="ppws-submit-btn">
                    <?php submit_button('Save Setting'); ?>
                </div>
            </form>
            <?php
        }


        public function ppws_advanced_settings_init()
        {
            register_setting(
                'ppws-settings-options',
                'ppws_advanced_settings',
                array($this, 'sanitize_settings')
            );

            /* General Settings Start */
            add_settings_section(
                'ppws_advanced_general_settings_section',
                __('General Settings', 'password-protected-store-for-woocommerce'),
                array(),
                'ppws-general-advanced-settings-section'
            );

            add_settings_field(
                'ppws_enable_isolation_mode_checkbox',
                __(
                    'Isolation Mode',
                    'password-protected-store-for-woocommerce'
                ),
                array($this, 'ppws_advanced_settings'),
                'ppws-general-advanced-settings-section',
                'ppws_advanced_general_settings_section',
                ['type' => 'checkbox', 'label_for' => 'enable_isolation_field_checkbox', 'description' => 'Isolation Mode prevents two WordPress hooks from running called wp_head and wp_footer. This will prevent conflicts with your theme or other plugins. While it prevents conflicts, it also means other plugins would not run on the page such as SEO and analytics plugins.']
            );

            add_settings_field(
                'ppws_enable_rest_api_protection_checkbox',
                __(
                    'REST API Protection',
                    'password-protected-store-for-woocommerce'
                ),
                array($this, 'ppws_advanced_settings'),
                'ppws-general-advanced-settings-section',
                'ppws_advanced_general_settings_section',
                ['type' => 'checkbox', 'label_for' => 'enable_rest_api_protection_checkbox', 'description' => 'This option allows users to hide protected data on the REST API. By default, REST API protection will be enabled.']
            );

            /* Scripts Settings Start */
            add_settings_section(
                'ppws_advanced_scripts_settings_section',
                __('Scripts Settings', 'password-protected-store-for-woocommerce'),
                array(),
                'ppws-advanced-scripts-settings-section'
            );

            add_settings_field(
                'header_script_content',
                __(
                    'Header Scripts',
                    'password-protected-store-for-woocommerce'
                ),
                array($this, 'ppws_advanced_settings'),
                'ppws-advanced-scripts-settings-section',
                'ppws_advanced_scripts_settings_section',
                ['type' => 'textarea', 'label_for' => 'ppws_header_script_content', 'description' => 'This code will be rendered before the closing </head> tag.', 'extra_label' => 'Scripts in Header']
            );

            add_settings_field(
                'body_scripts_content',
                __(
                    'Body Scripts',
                    'password-protected-store-for-woocommerce'
                ),
                array($this, 'ppws_advanced_settings'),
                'ppws-advanced-scripts-settings-section',
                'ppws_advanced_scripts_settings_section',
                ['type' => 'textarea', 'label_for' => 'ppws_body_scripts_content', 'description' => 'The code will be rendered after the <body> tag.', 'extra_label' => 'Scripts in Body']
            );

            add_settings_field(
                'footer_scripts_content',
                __(
                    'Footer Scripts',
                    'password-protected-store-for-woocommerce'
                ),
                array($this, 'ppws_advanced_settings'),
                'ppws-advanced-scripts-settings-section',
                'ppws_advanced_scripts_settings_section',
                ['type' => 'textarea', 'label_for' => 'ppws_footer_scripts_content', 'description' => 'The code will be rendered before the closing </body> tag.', 'extra_label' => 'Scripts in Footer']
            );
        }

        public function ppws_advanced_settings($args) {
            global $ppws_advanced_settings;

            if ($args['type'] == 'checkbox') {
                $value = isset($ppws_advanced_settings[$args['label_for']]) ? $ppws_advanced_settings[$args['label_for']] : '';
                $default_on_fields = array( "enable_isolation_field_checkbox", "enable_rest_api_protection_checkbox" );
                if(!isset($ppws_advanced_settings[$args['label_for']]) && in_array( $args['label_for'], $default_on_fields ))   $value = 'on';
                ?>
                <!-- Checkbox -->
                <label class="ppws-switch">
                    <input type="checkbox" class="ppws-checkbox" name="ppws_advanced_settings[<?php esc_attr_e( $args[ 'label_for' ] ) ?>]" id="<?php esc_attr_e( $args[ 'label_for' ] ) ?>" value="on" <?php if($value == "on"){ esc_attr_e('checked'); } ?>>
                    <span class="ppws-slider ppws-round"></span>
                </label>
                <p class="ppws-note"> 
                    <?php 
                      $allowed_html = array( 'br'     => array(), );
                    echo wp_kses( $args['description'], $allowed_html ); ?> 
                </p>
            <?php
            } elseif ($args['type'] == 'textarea') {
                $value = isset($ppws_advanced_settings[$args['label_for']]) ? htmlspecialchars_decode($ppws_advanced_settings[$args['label_for']]) : '';
            ?>
                <!-- Textarea -->
                <?php if(isset($args['extra_label'])) { ?>
                    <label for="<?php esc_attr_e( $args['label_for'] ); ?>"><strong><?php esc_html_e( $args['extra_label'] );  ?></strong></label>
                <?php } ?>
                <textarea id="<?php esc_attr_e( $args['label_for'] ); ?>" name="ppws_advanced_settings[<?php esc_attr_e( $args['label_for'] ); ?>]" rows="7" class="ppws-header-textarea ppws-script"><?php esc_html_e($value,'password-protected-store-for-woocommerce'); ?></textarea>
                <p class="ppws-note"><?php esc_attr_e($args['description'],'password-protected-store-for-woocommerce') ?></p>
            <?php
            }
        }

        public function sanitize_settings($input)
        {
            $new_input = array();

            if(isset($input['enable_isolation_field_checkbox']) && $input['enable_isolation_field_checkbox'] == 'on') {
                $new_input['enable_isolation_field_checkbox'] = 'on';
            }else{
                $new_input['enable_isolation_field_checkbox'] = '';
            }

            if(isset($input['enable_rest_api_protection_checkbox']) && $input['enable_rest_api_protection_checkbox'] == 'on') {
                $new_input['enable_rest_api_protection_checkbox'] = 'on';
            }else{
                $new_input['enable_rest_api_protection_checkbox'] = '';
            }

            if( isset( $input['ppws_header_script_content'] ))
                $new_input['ppws_header_script_content'] = (!isset($input['ppws_header_script_content'])) ? htmlspecialchars($input['ppws_header_script_content']) : $input['ppws_header_script_content'] ;

            if( isset( $input['ppws_body_scripts_content'] ) )
                $new_input['ppws_body_scripts_content'] = (!isset($input['ppws_body_scripts_content'])) ? htmlspecialchars($input['ppws_body_scripts_content']) : $input['ppws_body_scripts_content'] ;

            if( isset( $input['ppws_footer_scripts_content'] ) )
                $new_input['ppws_footer_scripts_content'] = (!isset($input['ppws_footer_scripts_content'])) ? htmlspecialchars($input['ppws_footer_scripts_content']) : $input['ppws_footer_scripts_content'] ;

            return $new_input;
        }
    }
}
?>
