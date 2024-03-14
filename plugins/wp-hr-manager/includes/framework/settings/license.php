<?php

use WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * Integration class
 */
class WPHR_License_Settings extends WPHR_Settings_Page {
    /**
     * Class constructor
     */
    function __construct() {
        $this->id    = 'wphr-license';
        $this->label = __( 'Licenses', 'wphr' );

        add_action( 'wphr_admin_field_licenses', [ $this, 'integrations' ] );
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public function get_settings() {
        $fields = [
            [
                'title' => __( 'License Manager', 'wphr' ),
                'desc'  => sprintf( __( 'Enter your extension license keys here to receive updates for purchased extensions. Visit <a href="%s" target="_blank">your account</a> page.', 'wphr' ), 'https://wphrmanager.com/my-account/' ),
                'type'  => 'title',
                'id'    => 'integration_settings'
            ],

            [ 'type' => 'licenses' ],
            [ 'type' => 'sectionend', 'id' => 'script_styling_options' ],

        ]; // End general settings

        return apply_filters( 'wphr_integration_settings', $fields );
    }

    /**
     * Display integrations settings.
     *
     * @return void
     */
    function integrations() {
        $licenses = wphr_addon_licenses();
        ?>
        <tr valign="top">
            <td class="wphr-settings-table-wrapper" colspan="2">
                <table class="wphr-settings-table widefat" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                                $columns = apply_filters( 'wphr_license_setting_columns', array(
                                    'name'    => __( 'Extension', 'wphr' ),
                                    'version'    => __( 'Version', 'wphr' ),
                                    'license' => __( 'License Key', 'wphr' ),
                                ) );

                                foreach ( $columns as $key => $column ) {
                                    echo '<th class="wphr-settings-table-' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ( $licenses as $addon ) {
                            echo '<tr>';

                            foreach ( $columns as $key => $column ) {
                                switch ( $key ) {
                                    case 'name' :
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            <strong>' . $addon['name'] . '</strong>
                                        </td>';
                                        break;

                                    case 'version':
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            ' . $addon['version'] . '
                                        </td>';
                                        break;

                                    case 'license':
                                        echo '<td class="wphr-settings-table-' . esc_attr( $key ) . '">
                                            <input type="text" name="' . esc_attr( $addon['id'] ) .'" value="' . esc_attr( $addon['license'] ) .'" class="regular-text" />';
                                        echo wphr_get_license_status( $addon );
                                        echo '</td>';
                                        break;
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <style>
        table.wphr-settings-table th.wphr-settings-table-name, table.wphr-settings-table td.wphr-settings-table-name {
            width: 35%;
        }
        </style>
        <?php
    }

    /**
     * Save the settings.
     *
     * @param  boolean $section (optional)
     *
     * @return void
     */
    function save( $section = false ) { }
}

//return new WPHR_License_Settings();
