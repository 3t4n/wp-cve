<?php
global $rsc_settings, $wp_rewrite, $rsc;
$tab = isset( $_GET[ 'tab' ] ) ? sanitize_key( $_GET[ 'tab' ] ) : ( rsc_iw_is_wl() ? 'general' : 'welcome' );

$general_settings = new RC_Settings_General();
$section = $general_settings->get_settings_sections( $tab );
$section = isset( $section[ 0 ] ) ? $section[ 0 ] : array();

if ( isset( $_POST[ 'save_rsc_settings' ] ) ) {

    if ( check_admin_referer( 'save_settings' ) ) {
        if ( current_user_can( 'manage_options' ) || current_user_can( 'save_settings_cap' ) ) {

            $rsc_settings = get_option( 'rsc_settings', array() );
            $settings_to_save = array_merge( $rsc_settings, $_POST[ 'rsc_settings' ] );
            update_option( 'rsc_settings', rsc_sanitize_array2( $settings_to_save, true ) );

            do_action( 'rsc_save_rsc_settings' );
            $message = __( 'Settings data has been successfully saved.', 'rsc' );

        } else {
            $message = __( 'You do not have required permissions for this action.', 'rsc' );
        }
    }
}
$rsc_settings = get_option( 'rsc_settings', false );
?>
<div class="wrap rc_wrap">
    <?php if ( isset( $message ) ) { ?>
        <div id="message" class="updated fade"><p><?php echo rsc_esc_html( $message ); ?></p></div>
    <?php } ?>
    <div id="poststuff" class="metabox-holder tc-settings">
        <?php
        $general_setting_url = add_query_arg( array(
            'page' => sanitize_key( $_GET[ 'page' ] ),
            'tab' => $tab,
        ), admin_url( 'admin.php' ) );
        ?>
        <form id="tc-restricted-content-settings" method="post" action="<?php echo esc_url( $general_setting_url ); ?>">
            <?php if ( $tab !== 'welcome' ) { ?>
                <div class="rsc-options-header">
                    <p><?php echo isset( $section[ 'header' ] ) ? rsc_esc_html( $section[ 'header' ] ) : ''; ?></p>
                    <?php if ( ! rsc_iw_is_wl() ) { ?>
                        <img src="<?php echo esc_url( $rsc->plugin_url ); ?>/assets/images/general-header@2x.png" width="399"/>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="rsc-options-wrap <?php echo esc_attr( 'rc-current-' . $tab ); ?>">
                <?php
                wp_nonce_field( 'save_settings' );
                $general_settings = new RC_Settings_General();
                $sections = $general_settings->get_settings_sections( $tab );
                if ( $tab == 'welcome' ) {
                    include( $rsc->plugin_dir . 'includes/settings/welcome-page.php' );
                }

                foreach ( $sections as $section ) {

                    if ( $tab == 'shortcodes' || $tab == 'login_form' ) { ?>
                        <div class="rsc-fullwidth-wrap">
                            <?php if ( isset( $section[ 'description' ] ) && ! empty( $section[ 'description' ] ) ) { ?>
                                <span class="rsc_section_description"><?php echo rsc_esc_html( $section[ 'description' ] ); ?></span><?php
                            }

                            $fields = $general_settings->get_settings_general_fields();
                            foreach ( $fields as $field ) {

                                if ( $tab == 'login_form' && ( ! isset( $field[ 'function' ] ) || ( isset( $field[ 'function' ] ) && $field[ 'function' ] !== 'rsc_generate_admin_box_content' ) ) ) {
                                    $rsc_class = 'rsc-halfwidth-field-wrap';

                                } else {
                                    $rsc_class = 'rsc-fullwidth-field-wrap';
                                }
                                if ( isset( $field[ 'section' ] ) && $field[ 'section' ] == $section[ 'name' ] ) {
                                    do_action( 'rsc_before_settings_general_field_type_check', $field ); ?>
                                    <div class="<?php echo esc_attr( $rsc_class ); ?>">
                                        <h3><?php echo rsc_esc_html( $field[ 'field_title' ] ); ?>
                                            <?php if ( $tab == 'shortcodes' ) { ?>
                                                <a title="<?php echo esc_attr( $field[ 'tooltip' ] ); ?>" class="rsc_tooltip_hover"></a>
                                            <?php } ?>
                                        </h3><?php
                                        RSC_Fields::render_field( $field, 'rsc_settings' );
                                        if ( $tab == 'login_form' && ! empty( $field[ 'tooltip' ] ) ) { ?>
                                            <div class="rsc-tooltip-wrap">
                                                <div class="rsc-tooltip"></div>
                                                <div class="rsc-tooltip-text">
                                                    <p><?php echo rsc_esc_html( $field[ 'tooltip' ] ); ?></p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php do_action( 'rsc_after_settings_general_field_type_check', $field ); ?>
                                    </div> <!-- .rsc-fullwidth-field-wrap -->
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div id="<?php echo esc_attr( $section[ 'name' ] ); ?>" class="postbox">
                            <div class="inside">
                                <?php if ( isset( $section[ 'description' ] ) && ! empty( $section[ 'description' ] ) && $tab == 'bot_exclusion' ) { ?>
                                    <span class="rsc_section_description"><?php echo rsc_esc_html( $section[ 'description' ] ); ?></span>
                                <?php } else { ?>
                                    <span class="description"><?php echo rsc_esc_html( $section[ 'description' ] ); ?></span>
                                <?php } ?>
                                <table class="form-table">
                                    <?php
                                    $fields = $general_settings->get_settings_general_fields();
                                    foreach ( $fields as $field ) {
                                        if ( isset( $field[ 'section' ] ) && $field[ 'section' ] == $section[ 'name' ] ) { ?>
                                            <tr valign="top" id="<?php echo esc_attr( $field[ 'field_name' ] . '_holder' ); ?>" <?php RSC_Fields::conditionals( $field ); ?>>
                                                <th scope="row" class="rsc-options-info">
                                                    <label for="<?php echo esc_attr( $field[ 'field_name' ] ); ?>">
                                                        <h3><?php echo rsc_esc_html( $field[ 'field_title' ] ); ?></h3>
                                                        <div class="rsc-tooltip-wrap">
                                                            <div class="rsc-tooltip"></div>
                                                            <div class="rsc-tooltip-text">
                                                                <p><?php echo rsc_esc_html( $field[ 'tooltip' ] ); ?></p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </th>
                                                <td>
                                                    <?php do_action( 'rsc_before_settings_general_field_type_check', $field ); ?>
                                                    <?php RSC_Fields::render_field( $field, 'rsc_settings' ); ?>
                                                    <?php do_action( 'rsc_after_settings_general_field_type_check', $field ); ?>
                                                </td>
                                            </tr><?php
                                        }
                                    } ?>
                                </table>
                            </div>
                        </div><?php
                    }
                } ?>
            </div>
            <?php
            if ( isset( $sections[ 0 ][ 'has_save_button' ] ) && $sections[ 0 ][ 'has_save_button' ] == true ) {
                submit_button( __( 'Save Settings' ), 'primary', 'save_rsc_settings' );
            }
            ?>
        </form>
    </div>
</div>
