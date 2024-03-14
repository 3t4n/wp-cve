<?php
if (!defined('WPINC')) {
    die;
}

if (!class_exists('bmae_admin')) {

    class bmae_admin {

        public function __construct() {

            add_action('admin_init', array($this, 'bmae_settings_init'));
            add_action('admin_menu', array($this, 'bmae_add_admin_menu'));

            $this->setting_page_slug = BMAE;
            $this->plugin_text_domain = BMAE_TEXT_DOMAIN;
        }

        /**
         *
         */
        public function bmae_add_admin_menu() {

            add_submenu_page( BFSL_PLUGINS_DEV_GROUPE_ID,
                __('Notifications settings', $this->plugin_text_domain),
                __('Notifications', $this->plugin_text_domain),
                'manage_options',
                $this->setting_page_slug,
                array( $this, 'bmae_options_page' )
            );
        }

        /**
         *
         */
        function bmae_settings_init() {
            register_setting('bmaeGeneralSettings', 'bmae_settings');

            //general settings
            add_settings_section(
                'bmaeGeneralSettings_section',
                __('General settings option for Multiple admin emails by Brozzme', $this->plugin_text_domain),
                array($this, 'bmaeGeneralSettings_section_callback'),
                'bmaeGeneralSettings'
            );

            add_settings_field(
                'limit',
                __('Limit',$this->plugin_text_domain),
                array($this, 'bmae_limit_render'),
                'bmaeGeneralSettings',
                'bmaeGeneralSettings_section'
            );
            add_settings_field(
                'new_post_email',
                __('Send email when publishing',$this->plugin_text_domain),
                array($this, 'new_post_email_render'),
                'bmaeGeneralSettings',
                'bmaeGeneralSettings_section'
            );
            add_settings_field(
                'send_post_type',
                __('Send on post-type event',$this->plugin_text_domain),
                array($this, 'send_post_type_render'),
                'bmaeGeneralSettings',
                'bmaeGeneralSettings_section'
            );
            add_settings_field(
                'event_email',
                __('Event email',$this->plugin_text_domain),
                array($this, 'event_email_render'),
                'bmaeGeneralSettings',
                'bmaeGeneralSettings_section'
            );
            add_settings_field(
                'silent_mode',
                __('Silent mode',$this->plugin_text_domain),
                array($this, 'silent_mode_render'),
                'bmaeGeneralSettings',
                'bmaeGeneralSettings_section'
            );
        }

        /**
         *
         */
        public function bmaeGeneralSettings_section_callback() {
            echo '<h3>'.__('Select emails limit', $this->plugin_text_domain) .'</h3>';
            ?>
        <p style="width:50%;font-size:14px"><?php _e('Choose a maximum of 5 emails for the site notifications. It is better to sort emails by importance. If you change to a lower limit, the first emails will remain (new limit will be apply to the next sanitization)', $this->plugin_text_domain);?></p>
        <?php
        }

        /**
         *
         */
        public function bmae_limit_render() {
            $this->option = get_option('bmae_settings');
            ?>
            <select name="bmae_settings[limit]">
            <option value="1" <?php selected( $this->option['limit'], 1 ); ?>><?php _e( '1 email', $this->plugin_text_domain );?></option>
            <option value="2" <?php selected( $this->option['limit'], 2 ); ?>><?php _e( '2 emails', $this->plugin_text_domain );?></option>
            <option value="3" <?php selected( $this->option['limit'], 3 ); ?>><?php _e( '3 emails', $this->plugin_text_domain );?></option>
            <option value="4" <?php selected( $this->option['limit'], 4 ); ?>><?php _e( '4 emails', $this->plugin_text_domain );?></option>
            <option value="5" <?php selected( $this->option['limit'], 5 ); ?>><?php _e( '5 emails', $this->plugin_text_domain );?></option>
            </select>
            <?php

        }

        /**
         *
         */
        public function new_post_email_render(){

            $this->option = get_option('bmae_settings');
            ?>

            <select name="bmae_settings[new_post_email]">
            <option value="true" <?php selected( $this->option['new_post_email'], 'true' ); ?>><?php _e( 'Yes', $this->plugin_text_domain );?></option>
            <option value="false" <?php selected( $this->option['new_post_email'], 'false' ); ?>><?php _e( 'No', $this->plugin_text_domain );?></option>

            </select><?php
        }

        /**
         *
         */
        public function send_post_type_render(){
            $this->option = get_option('bmae_settings');
            ?>

            <select name="bmae_settings[send_post_type]">
            <option value="post" <?php selected( $this->option['send_post_type'], 'post' ); ?>><?php _e( 'Post', $this->plugin_text_domain );?></option>
            <option value="page" <?php selected( $this->option['send_post_type'], 'page' ); ?>><?php _e( 'Page', $this->plugin_text_domain );?></option>
            <option value="both" <?php selected( $this->option['send_post_type'], 'both' ); ?>><?php _e( 'Both', $this->plugin_text_domain );?></option>

            </select><?php
        }

        /**
         *
         */
        public function event_email_render(){

            // on publishing only
            // on pending
            // on draft
            // on demand (cancel sending in post)
            $this->option = get_option('bmae_settings');
            ?>

            <select name="bmae_settings[event_email]">
            <option value="publish" <?php selected( $this->option['event_email'], 'publish' ); ?>><?php _e( 'On Publish only', $this->plugin_text_domain );?></option>
            <option value="pending" <?php selected( $this->option['event_email'], 'pending' ); ?>><?php _e( 'On pending', $this->plugin_text_domain );?></option>
            <option value="draft" <?php selected( $this->option['event_email'], 'draft' ); ?>><?php _e( 'On draft', $this->plugin_text_domain );?></option>
            <option value="on_demand" <?php selected( $this->option['event_email'], 'on_demand' ); ?>><?php _e( 'On demand', $this->plugin_text_domain );?></option>

            </select><?php
        }

        /**
         *
         */
        public function silent_mode_render(){
            $this->option = get_option('bmae_settings');
            ?>

            <select name="bmae_settings[silent_mode]">
            <option value="true" <?php selected( $this->option['silent_mode'], 'true' ); ?>><?php _e( 'Yes', $this->plugin_text_domain );?></option>
            <option value="false" <?php selected( $this->option['silent_mode'], 'false' ); ?>><?php _e( 'No', $this->plugin_text_domain );?></option>

            </select>
            <p><?php _e('Silent mode hide notification meta box for non-registered admin email. This mode is only available if On Demand notification are enable. Note that when Event email is not set to On Demand, notification can not be reset.', $this->plugin_text_domain);?></p>
            <?php
        }

        /**
         *
         */
        public function bmae_options_page() {

                ?>
                <div class="wrap">
                    <h1>Brozzme Multiple Admin Emails</h1>
                    <form action='options.php' method='post'>
                        <?php
                        settings_fields('bmaeGeneralSettings');
                        do_settings_sections('bmaeGeneralSettings');
                        submit_button();
                        ?>
                    </form>
                </div>
                <?php
        }
    }
}

// -----------------------------------------------------------------------------
//
if (class_exists('bmae_admin')) {
    new bmae_admin;
}