<?php

use WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * Email settings class
 */
class WPHR_Email_Settings extends WPHR_Settings_Page {

    function __construct() {
        $this->id       = 'wphr-email';
        $this->label    = __( 'Emails', 'wphr' );
        $this->sections = $this->get_sections();

        add_action( 'wphr_admin_field_notification_emails', [ $this, 'notification_emails' ] );
        add_action( 'wphr_admin_field_smtp_test_connection', [ $this, 'smtp_test_connection' ] );
        add_action( 'wphr_admin_field_imap_test_connection', [ $this, 'imap_test_connection' ] );
        add_action( 'wphr_admin_field_imap_status', [ $this, 'imap_status' ] );

        add_action( 'wphr_update_option', [ $this, 'cron_schedule' ] );
    }

    /**
     * Get registered tabs
     *
     * @return array
     */
    public function get_sections() {
        $sections = [
            'general' => __( 'General', 'wphr' ),
            'smtp'    => __( 'SMTP', 'wphr' ),
            'imap'    => __( 'IMAP/POP3', 'wphr' ),
        ];

        return apply_filters( 'wphr_settings_email_sections', $sections );
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {

        $fields['general'][] = [
            'title' => __( 'Email Sender Options', 'wphr' ),
            'type'  => 'title',
            'desc'  => __( 'Email notification settings for wphr. Customize the look and feel of outgoing emails.', 'wphr' )
        ];

        $fields['general'][] = [
            'title'   => __( '"From" Name', 'wphr' ),
            'id'      => 'from_name',
            'type'    => 'text',
            'default' => get_bloginfo( 'name' ),
            'tooltip' => true,
            'desc' => __( 'The senders name appears on the outgoing emails', 'wphr' )
        ];

        $fields['general'][] = [
            'title'   => __( '"From" Address', 'wphr' ),
            'id'      => 'from_email',
            'type'    => 'text',
            'default' => get_option( 'admin_email' ),
            'tooltip' => true,
            'desc'    => __( 'The senders email appears on the outgoing emails', 'wphr' )
        ];

        $fields['general'][] = [
            'title'             => __( 'Header Image', 'wphr' ),
            'id'                => 'header_image',
            'type'              => 'text',
            'desc'              => __( 'Upload a logo/banner and provide the URL here.', 'wphr' ),
            'tooltip'           => true,
            'custom_attributes' => [
                'placeholder' => 'http://example.com/path/to/logo.png'
            ]
        ];

        $fields['general'][] = [
            'title'   => __( 'Footer Text', 'wphr' ),
            'id'      => 'footer_text',
            'type'    => 'textarea',
            'css'     => 'min-width:300px;',
            'tooltip' => true,
            'default' => sprintf( '%s  - Powered by WPHR Manager', get_bloginfo( 'name' ) ),
            'desc'    => __( 'The text apears on each emails footer area.', 'wphr' )
        ];

        $fields['general'][] = [
            'type' => 'sectionend', 'id' => 'script_styling_options'
        ];

        $fields['general'][] = [
            'title' => __( 'Notification Emails', 'wphr' ),
            'desc'  => __( 'Email notifications sent from WPHR Manager are listed below. Click on an email to configure it.', 'wphr' ),
            'type'  => 'title',
            'id'    => 'email_notification_settings'
        ];

        $fields['general'][] = [
            'type' => 'notification_emails'
        ];

        $fields['general'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];
        // End general settings

        $fields['smtp'][] = [
            'title' => __( 'SMTP Options', 'wphr' ),
            'type'  => 'title',
            'desc'  => __( 'Email outgoing settings for wphr.', 'wphr' )
        ];

        $fields['smtp'][] = [
            'title'   => __( 'Enable SMTP', 'wphr' ),
            'id'      => 'enable_smtp',
            'type'    => 'radio',
            'options' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'default' => 'no'
        ];

        $fields['smtp'][] = [
            'title'             => __( 'Mail Server', 'wphr' ),
            'id'                => 'mail_server',
            'type'              => 'text',
            'custom_attributes' => [
                'placeholder'   => 'smtp.gmail.com'
            ],
            'desc'              => __( 'SMTP host address.', 'wphr' ),
        ];

        $fields['smtp'][] = [
            'title' => __( 'Port', 'wphr' ),
            'id'    => 'port',
            'type'  => 'text',
            'desc'  => __( 'SSL: 465<br> TLS: 587', 'wphr' ),
        ];

        $fields['smtp'][] = [
            'title'   => __( 'Authentication', 'wphr' ),
            'id'      => 'authentication',
            'type'    => 'select',
            'desc'    => __( 'Authentication type.', 'wphr' ),
            'options' => [ '' => __( 'None', 'wphr'), 'ssl' => __( 'SSL', 'wphr' ), 'tls' => __( 'TLS', 'wphr') ],
        ];

        $fields['smtp'][] = [
            'title'             => __( 'Username', 'wphr' ),
            'id'                => 'username',
            'type'              => 'text',
            'custom_attributes' => [
                'placeholder'   => 'email@example.com'
            ],
            'desc'              => __( 'Your email id.', 'wphr' ),
        ];

        $fields['smtp'][] = [
            'title' => __( 'Password', 'wphr' ),
            'id'    => 'password',
            'type'  => 'password',
            'desc'  => __( 'Your email password.', 'wphr' )
        ];

        $fields['smtp'][] = [
            'title'   => __( 'Debug', 'wphr' ),
            'id'      => 'debug',
            'type'    => 'radio',
            'options' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'default' => 'no'
        ];

        $fields['smtp'][] = [
            'type' => 'smtp_test_connection',
        ];

        $fields['smtp'][] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];
        // End SMTP settings

        $fields['imap'] = $this->get_imap_settings_fields();
        // End IMAP settings

        $fields = apply_filters( 'wphr_settings_email_section_fields', $fields, $section );

        $section = $section === false ? $fields['general'] : $fields[$section];

        return $section;
    }

    function notification_emails() {
        $email_templates = wphr()->emailer->get_emails();
        ?>
        <tr valign="top">
            <td class="wphr-settings-table-wrapper" colspan="2">
                <table class="wphr-settings-table widefat" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                                $columns = apply_filters( 'wphr_email_setting_columns', array(
                                    'name'        => __( 'Email', 'wphr' ),
                                    'description' => __( 'Description', 'wphr' ),
                                    'actions'     => ''
                                ) );

                                foreach ( $columns as $key => $column ) {
                                    echo '<th class="wphr-settings-table-' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ( $email_templates as $email_key => $email ) {
                            echo '<tr>';

                            foreach ( $columns as $key => $column ) {
                                switch ( $key ) {
                                    case 'name' :
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            <a href="' . admin_url( 'admin.php?page=wphr-settings&tab=wphr-email&section=general&sub_section=' . strtolower( $email_key ) ) . '">' . $email->get_title() . '</a>
                                        </td>';
                                        break;

                                    case 'status':
                                    case 'module':
                                    case 'recipient':
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">

                                        </td>';
                                        break;

                                    case 'description':
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            <span class="help">' . $email->get_description() . '</span>
                                        </td>';
                                        break;

                                    case 'actions' :
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            <a class="button alignright" href="' . admin_url( 'admin.php?page=wphr-settings&tab=wphr-email&section=general&sub_section=' . strtolower( $email_key ) ) . '">' . __( 'Configure', 'wphr' ) . '</a>
                                        </td>';
                                        break;

                                    default :
                                        do_action( 'wphr_email_setting_column_' . $key, $email );
                                    break;
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php
    }

    /**
     * Display imap test connection button.
     *
     * @return void
     */
    public function smtp_test_connection() {
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                &nbsp;
            </th>
            <td class="forminp forminp-text">
                <input type="email" id="smtp_test_email_address" class="regular-text" value="<?php echo get_option( 'admin_email' ); ?>" /><br>
                <p class="description"><?php _e( 'An email address to test the connection.', 'wphr' ); ?></p>
                <a id="smtp-test-connection" class="button-secondary"><?php esc_attr_e( 'Send Test Email', 'wphr' ); ?></a>
                <span class="wphr-loader" style="display: none;"></span>
                <p class="description"><?php _e( 'Click on the above button before saving the settings.', 'wphr' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Display imap test connection button.
     *
     * @return void
     */
    public function imap_test_connection() {
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                &nbsp;
            </th>
            <td class="forminp forminp-text">
                <a id="imap-test-connection" class="button-secondary"><?php esc_attr_e( 'Test Connection', 'wphr' ); ?></a>
                <span class="wphr-loader" style="display: none;"></span>
                <p class="description"><?php _e( 'Click on the above button before saving the settings.', 'wphr' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Imap connection status.
     *
     * @return void
     */
    public function imap_status() {
        $options     = get_option( 'wphr_settings_wphr-email_imap', [] );
        $imap_status = (boolean) isset( $options['imap_status'] ) ? $options['imap_status'] : 0;
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <?php _e( 'Status', 'wphr' ); ?>
            </th>
            <td class="forminp forminp-text">
                <span class="dashicons dashicons-<?php echo ( $imap_status ) ? 'yes green' : 'no red' ?>"></span><?php echo ( $imap_status ) ? __( 'Connected', 'wphr' ) : __( 'Not Connected', 'wphr' ); ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Get IMAP Settings Fields.
     *
     * @return array
     */
    protected function get_imap_settings_fields() {
        if ( ! extension_loaded( 'imap' ) || ! function_exists( 'imap_open' ) ) {
            $fields[] = [
                'title' => __( 'IMAP/POP3 Options', 'wphr' ),
                'type'  => 'title',
                'desc'  => sprintf(
                    '%s' . __( 'Your server does not have PHP IMAP extension loaded. To enable this feature, please contact your hosting provider and ask to enable PHP IMAP extension.', 'wphr' ) . '%s',
                    '<section class="notice notice-warning"><p>',
                    '</p></section>'
                )
            ];

            return $fields;
        }

        $fields[] = [
            'title' => __( 'IMAP/POP3 Options', 'wphr' ),
            'type'  => 'title',
            'desc'  => __( 'Email incoming settings for wphr.', 'wphr' )
        ];

        $fields[] = [
            'type' => 'imap_status',
        ];

        $fields[] = [
            'title'   => __( 'Enable IMAP', 'wphr' ),
            'id'      => 'enable_imap',
            'type'    => 'radio',
            'options' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'default' => 'no'
        ];

        $schedules = wp_get_schedules();

        $cron_schedules = [];
        foreach( $schedules as $key => $value ) {
            $cron_schedules[$key] = $value['display'];
        }

        $fields[] = [
            'title'   => __( 'Cron Schedule', 'wphr' ),
            'id'      => 'schedule',
            'type'    => 'select',
            'desc'    => __( 'Interval time to run cron.', 'wphr' ),
            'options' => $cron_schedules,
            'default' => 'hourly',
        ];

        $fields[] = [
            'title'             => __( 'Mail Server', 'wphr' ),
            'id'                => 'mail_server',
            'type'              => 'text',
            'custom_attributes' => [
                'placeholder'   => 'imap.gmail.com'
            ],
            'desc'              => __( 'IMAP/POP3 host address.', 'wphr' ),
        ];

        $fields[] = [
            'title'             => __( 'Username', 'wphr' ),
            'id'                => 'username',
            'type'              => 'text',
            'desc'              => __( 'Your email id.', 'wphr' ),
            'custom_attributes' => [
                'placeholder'   => 'email@example.com'
            ]
        ];

        $fields[] = [
            'title' => __( 'Password', 'wphr' ),
            'id'    => 'password',
            'type'  => 'password',
            'desc'  => __( 'Your email password.', 'wphr' )
        ];

        $fields[] = [
            'title'   => __( 'Protocol', 'wphr' ),
            'id'      => 'protocol',
            'type'    => 'select',
            'desc'    => __( 'Protocol type.', 'wphr' ),
            'options' => [ 'imap' => __( 'IMAP', 'wphr' ), 'pop3' => __( 'POP3', 'wphr') ],
            'default' =>  'imap',
        ];

        $fields[] = [
            'title' => __( 'Port', 'wphr' ),
            'id'    => 'port',
            'type'  => 'text',
            'desc'  => __( 'IMAP: 993<br> POP3: 995', 'wphr' ),
        ];

        $fields[] = [
            'title'   => __( 'Authentication', 'wphr' ),
            'id'      => 'authentication',
            'type'    => 'select',
            'options' => [ 'ssl' => __( 'SSL', 'wphr' ), 'tls' => __( 'TLS', 'wphr'), 'notls' => __( 'None', 'wphr') ],
            'default' =>  'ssl',
            'desc'    => __( 'Authentication type.', 'wphr' ),
        ];

        $fields[] = [
            'type' => 'imap_test_connection',
        ];

        $fields[] = [
            'id'      => 'imap_status',
            'type'    => 'hidden',
            'default' => 0,
        ];

        $fields[] = [
            'type' => 'sectionend',
            'id'   => 'script_styling_options'
        ];

        return $fields;
    }

    /**
     * Set cron schedule event to check new inbound emails
     *
     * @return void
     */
    public function cron_schedule( $value ) {
        if ( ! isset( $_GET['section'] ) || ( sanitize_text_field( $_GET['section'] ) != 'imap' ) ) {
            return;
        }

        if ( ! isset( $value['id'] ) || ( $value['id'] != 'schedule' ) ) {
            return;
        }

        $recurrence = isset( $_POST['schedule'] ) ? sanitize_text_field($_POST['schedule']) : 'hourly';
        wp_clear_scheduled_hook( 'wphr_crm_inbound_email_scheduled_events' );
        wp_schedule_event( time(), $recurrence, 'wphr_crm_inbound_email_scheduled_events' );
    }

    /**
     * Output the settings.
     */
    public function output( $section = false ) {
        if ( ! isset( $_GET['sub_section'] ) ) {
            parent::output( $section );

            return;
        }

        $current_section = isset( $_GET['sub_section'] ) ? sanitize_key( $_GET['sub_section'] ) : false;

        // Define emails that can be customised here
        $email_templates = wphr()->emailer->get_emails();

        if ( $current_section ) {
            foreach ( $email_templates as $email_key => $email ) {
                if ( strtolower( $email_key ) == $current_section ) {
                    $email->admin_options();
                    break;
                }
            }
        } else {
            parent::output();
        }
    }

    function save( $section = false ) {
        if ( isset( $_POST['_wpnonce']) && wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'wphr-settings-nonce' ) ) {

            if ( ! isset( $_GET['sub_section'] ) ) {
                parent::save( $section );

                return;
            }

            $current_section = isset( $_GET['sub_section'] ) ? sanitize_key( $_GET['sub_section'] ) : false;


            // saving individual email settings
            if ( $current_section ) {
                $email_templates = wphr()->emailer->get_emails();

                foreach ( $email_templates as $email_key => $email ) {
                    if ( strtolower( $email_key ) == $current_section ) {

                        $settings       = $email->get_form_fields();
                        $update_options = array();

                        if ( $settings) {
                            foreach ($settings as $field) {
                                if ( ! isset( $field['id'] ) || ! isset( $_POST[ $field['id'] ] ) ) {
                                    continue;
                                }

                                $option_value = $this->parse_option_value( $field );

                                if ( ! is_null( $option_value ) ) {
                                    $update_options[ $field['id'] ] = $option_value;
                                }
                            }
                        }

                        update_option( $email->get_option_id(), $update_options );

                        break;
                    }
                }

            } else {
                parent::save();
            }
        }
    }
}

return new WPHR_Email_Settings();
