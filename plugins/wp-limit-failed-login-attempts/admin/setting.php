<?php
if (!class_exists('WPLFLA_admin_setting_PRO')) {
    class WPLFLA_admin_setting_PRO
    {

        public function __construct()
        {
            add_action('admin_init', array($this, 'WPLFLA_settings_init'));
        }

        public function validate_options($input)
        {
            $validated_input = $input; 

            if (!empty($validated_input['WPLFLA_allowed']) && is_numeric($validated_input['WPLFLA_allowed']) && intval($validated_input['WPLFLA_allowed']) >= 1) {
                return $validated_input;
            } else {
                add_settings_error(
                    'WPLFLA_messages',
                    'WPLFLA_message',
                    __('The value of Allowed retries must be a positive integer greater than or equal to 1.', 'codepressFailed_pro'),
                    'error'
                );
                
                return get_option('WPLFLA_options');
            }
        }


        public function WPLFLA_settings_init()
        {
            register_setting('WPLFLA', 'WPLFLA_options');
            add_settings_section(
                'WPLFLA_section_developers',
                'Main Options<hr>',
                '',
                'WPLFLA'
            );
            add_settings_field(
                'WPLFLA_status',
                __('Plugin Status', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_checkbox'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_status',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => 'custom',
                    'desc' => 'enable or disable all failed login attempts using this option'
                ]
            );

            add_settings_field(
                'WPLFLA_send_mail_status',
                __('Email Notification', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_checkbox'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_send_mail_status',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => 'custom',
                    'desc' => 'if enabled, you will receive email if any user exceeded the max allowed retries'
                ]
            );
            add_settings_field(
                'WPLFLA_save_password_status',
                __('Save Passwords', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_checkbox_img'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_save_password_status',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => 'custom',
                    'desc' => 'if enabled, We will save the password for each attempt'
                ]
            );


            add_settings_field(
                'WPLFLA_allowed',
                __('Allowed retries', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_text'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_allowed',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => '3',
                    'desc' => 'Number of maximum allowed retries before lockout, Default is 3'
                ]
            );

            // Register the setting and specify the validation callback.
            register_setting('WPLFLA', 'WPLFLA_options', array($this, 'validate_options'));




            add_settings_field(
                'WPLFLA_url',
                __('Redirect URL', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_url'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_url',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => home_url(),
                    'desc' => 'Redirect the user to a specific URL in case the country or IP is blocked'
                ]
            );

            add_settings_field(
                'WPLFLA_email',
                __('Email Address', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_email'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_email',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => 'custom',
                    'desc' => 'Email used to receive alerts'
                ]
            );
            add_settings_field(
                'WPLFLA_min',
                __('Lockout Minutes', 'codepressFailed_pro'),
                array($this, 'WPLFLA_field_type_text'),
                'WPLFLA',
                'WPLFLA_section_developers',
                [
                    'label_for' => 'WPLFLA_min',
                    'class' => 'WPLFLA_row',
                    'WPLFLA_custom_data' => '3',
                    'desc' => 'Minutes untill retiries are reset, Default is 30'
                ]
            );
        }



        public function WPLFLA_field_type_url($args)
        {
            $options = get_option('WPLFLA_options');
?>
            <input type="url" class="WPLFLA_inp" value="<?php echo isset($options[$args['label_for']]) ? esc_url($options[$args['label_for']]) : esc_attr($args['WPLFLA_custom_data']); ?>" id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['WPLFLA_custom_data']); ?>" name="WPLFLA_options[<?php echo esc_attr($args['label_for']); ?>]">
            <p class="description">
                <?php esc_html_e($args['desc'], 'codepressFailed_pro'); ?>
            </p>
        <?php
        }

        public function WPLFLA_field_type_text($args)
        {
            $options = get_option('WPLFLA_options');

        ?>
            <input type="number" class="WPLFLA_inp" min="1" value="<?php echo esc_attr($options[$args['label_for']]); ?>" id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['WPLFLA_custom_data']); ?>" name="WPLFLA_options[<?php echo esc_attr($args['label_for']); ?>]">
            <p class="description">
                <?php esc_html_e($args['desc'], 'codepressFailed_pro'); ?>
            </p>

        <?php
        }
        public function WPLFLA_field_type_email($args)
        {
            $options = get_option('WPLFLA_options');

        ?>
            <input type="email" class="WPLFLA_inp" value="<?php echo esc_attr($options[$args['label_for']]); ?>" id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['WPLFLA_custom_data']); ?>" name="WPLFLA_options[<?php echo esc_attr($args['label_for']); ?>]">
            <p class="description">
                <?php esc_html_e($args['desc'], 'codepressFailed_pro'); ?>
            </p>

        <?php
        }

        public function WPLFLA_field_type_checkbox($args)
        {
            $options = get_option('WPLFLA_options');

        ?>
            <span class="on_off "><?php esc_html_e('OFF', 'codepressFailed_pro'); ?></span>
            <label class="switch">
                <input type="checkbox" value="1" id="<?php esc_attr_e($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['WPLFLA_custom_data']); ?>" name="WPLFLA_options[<?php echo esc_attr($args['label_for']); ?>]" <?php echo isset($options[$args['label_for']]) ? (checked($options[$args['label_for']], '1', false)) : (''); ?>>
                <span class="slider round"></span>
            </label>
            <span class="on_off "><?php esc_html_e('ON', 'codepressFailed_pro'); ?></span>
            <p class="description">
                <?php esc_html_e($args['desc'], 'codepressFailed_pro'); ?>
            </p>

        <?php
        }
        public function WPLFLA_field_type_checkbox_img($args)
        {
        ?>
            <a target="_blank" href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">
                <img src="<?php echo esc_url(WPLFLA_PLUGIN_URL . '/assets/images/save_password.jpg'); ?>" style="width: 342px;">
            </a>

        <?php
        }

        public static function WPLFLA_options_page_html()
        {
            if (!current_user_can('manage_options')) {
                return;
            }

            // Check if there are any settings errors.
            $errors = get_settings_errors('WPLFLA_allowed');

            if (isset($_GET['settings-updated'])) {
                add_settings_error('WPLFLA_messages', 'WPLFLA_message', __('Settings Saved', 'codepressFailed_pro'), 'updated');
            }

            // Display the settings errors, if any.
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<div class="error notice"><p>' . esc_html($error['message']) . '</p></div>';
                }
            }

            settings_errors('WPLFLA_messages');

        ?>

            <h2>&nbsp;&nbsp;<?php _e('Failed login attempts', 'codepressFailed_pro'); ?></h2>
            <div id="">
                <div id="dashboard-widgets" class="metabox-holder">
                    <div id="" class="">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="dashboard_quick_press" class="postbox ">
                                <h2 class="hndle ui-sortable-handle">
                                    <span>
                                        <span class="hide-if-no-js"><?php esc_html_e(get_admin_page_title()); ?></span>
                                        <span class="hide-if-js"><?php esc_html_e(get_admin_page_title()); ?></span>
                                    </span>
                                </h2>
                                <div class="inside">

                                    <form action="options.php" method="post">

                                        <div class="input-text-wrap" id="title-wrap">
                                            <?php
                                            settings_fields('WPLFLA');
                                            do_settings_sections('WPLFLA');
                                            ?>
                                        </div>
                                        <p class="submit">
                                            <?php
                                            submit_button('Save Settings');
                                            ?>
                                            <br class="clear">
                                        </p>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }


    $admin_setting_login_plugin = new WPLFLA_admin_setting_PRO();
}
if (!function_exists('WPLFLA_hkdc_admin_stylespro')) {
    function WPLFLA_hkdc_admin_stylespro($page)
    {
        if (isset($_GET['page']) && ($_GET['page'] == 'WPLFLASettings' || $_GET['page'] == 'blockippage' || $_GET['page'] == 'WPLFLALOG' || $_GET['page'] == 'blockip' || $_GET['page'] == 'logapp')) {
            wp_enqueue_style('failed_admin-pro-css', WPLFLA_PLUGIN_URL . '/assets/css/admin-css.css?re=1.2');
            wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
        }
    }
}
add_action('admin_print_styles', 'WPLFLA_hkdc_admin_stylespro');
