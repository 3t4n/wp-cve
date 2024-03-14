<?php
$global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
$options = $global_settings->ep_get_settings();
wp_enqueue_script(
    'ep-admin-license-page-js',
    EP_BASE_URL . '/includes/core/assets/js/ep-admin-license.js',
    array( 'jquery' ), EVENTPRIME_VERSION
);

wp_enqueue_style( 'ep-toast-css' );
wp_enqueue_script( 'ep-toast-js' );
wp_enqueue_script( 'ep-toast-message-js' );

$params = array(
    'ep_license_nonce' => wp_create_nonce( 'ep-license-nonce' ),
);

wp_localize_script( 'ep-admin-license-page-js', 'ep_admin_license_settings', $params );
        
// save license key
if( isset( $_POST['submit'] ) && ! empty( $_POST['submit'] ) ){
    $form_data = $_POST;
    $options->ep_premium_license_key  = ( isset( $form_data['ep_premium_license_key'] ) && ! empty( $form_data['ep_premium_license_key'] ) ) ? $form_data['ep_premium_license_key'] : '';
    $global_settings->ep_save_settings( $options );
}

$ep_premium_license_key = $options->ep_premium_license_key;
$ep_premium_license_status = $options->ep_premium_license_status;
$ep_premium_license_response = $options->ep_premium_license_response;
$ep_premium_license_option_value = $options->ep_premium_license_option_value;
$ext_list = ep_list_all_exts();
$is_any_paid_ext_activated = array();
foreach ( $ext_list as $ext ) {
    $ext_details = em_get_more_extension_data($ext);
    if( $ext_details['is_free'] == 0 ){
        $is_any_paid_ext_activated[] = $ext_details['is_activate'];
    }  
}?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Plugin License Options', 'eventprime-event-calendar-management' ); ?></h2>
    <p><strong><?php esc_html_e( 'Read about activating licenses ', 'eventprime-event-calendar-management' );?><a target="_blank" href="<?php echo esc_url( 'https://theeventprime.com/adding-license-keys-eventprime/' );?>"><?php esc_html_e( 'here', 'eventprime-event-calendar-management' ); ?></a></strong></p>
</div>

<table class="form-table">
    <tbody>
        <tr>
            <td class="ep-form-table-wrapper" colspan="2">
                <table class="ep-form-table-setting ep-setting-table widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Name', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'License Key', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Validity', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Action', 'eventprime-event-calendar-management' );?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr valign="top" class="ep_premium">
                            <td>
                                <div class="pg-purchase-selector">
                                    <select onchange="ep_on_change_bundle(this.value)">
                                        <option> <?php esc_html_e( 'Select Bundle', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_free" <?php if( $ep_premium_license_option_value == 'ep_free' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime Free','eventprime-event-calendar-management' );?></option>
                                        <option value="ep_premium" <?php if( $ep_premium_license_option_value == 'ep_premium' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime Business', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_professional" <?php if( $ep_premium_license_option_value == 'ep_professional' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime Professional', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_essential" <?php if( $ep_premium_license_option_value == 'ep_essential' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime Essential', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_metabundle" <?php if( $ep_premium_license_option_value == 'ep_metabundle' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime MetaBundle', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_metabundle_plus" <?php if( $ep_premium_license_option_value == 'ep_metabundle_plus' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime MetaBundle+', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_premium"><?php esc_html_e( 'EventPrime Premium', 'eventprime-event-calendar-management' );?></option>
                                        <option value="ep_premium_plus" <?php if( $ep_premium_license_option_value == 'ep_premium_plus' ){ echo 'selected'; } ?>><?php esc_html_e( 'EventPrime Premium+', 'eventprime-event-calendar-management' );?></option>
                                    </select>
                                    <span class="ep-tooltips" tooltip="<?php esc_html_e( 'If you have purchased a Bundle, please select the name of the Bundle and enter its license key in the corresponding input box', 'eventprime-event-calendar-management' );?>" tooltip-position="top"></span>
                                </div>
                            </td>
                            <td><input id="ep_premium_license_key" name="ep_premium_license_key" type="text" class="regular-text ep-box-wrap ep-license-block" data-prefix="<?php echo esc_attr( $ep_premium_license_option_value ); ?>" value="<?php echo ( ! empty( $ep_premium_license_key ) ? esc_attr( $ep_premium_license_key ) : '' ); ?>" placeholder="<?php esc_html_e( 'Enter Your License Key', 'eventprime-event-calendar-management' );?>" /></td>
                            <td>         
                                <span class="license-expire-date" style="padding-bottom:2rem;" >
                                    <?php
                                    if ( isset( $ep_premium_license_response->expires ) && ! empty( $ep_premium_license_response->expires ) && ! empty( $ep_premium_license_status ) && $ep_premium_license_status == 'valid' ) {
                                        if( $ep_premium_license_response->expires == 'lifetime' ){
                                            esc_html_e( 'Your License key is activated for lifetime', 'eventprime-event-calendar-management' );
                                        }else{
                                            echo sprintf(__( 'Your License Key expires on %s', 'eventprime-event-calendar-management' ), date( 'F d, Y', strtotime( $ep_premium_license_response->expires ) ) );
                                        }
                                    } elseif ( isset( $ep_premium_license_response->expires ) && ! empty( $ep_premium_license_response->expires )) {
                                        echo sprintf(__( 'Your License Key expired on %s', 'eventprime-event-calendar-management' ), date( 'F d, Y', strtotime( $ep_premium_license_response->expires ) ) );
                                    }?>
                                </span>
                            </td>
                            <td>
                                <span class="ep_premium-license-status-block">
                                    <?php if ( isset( $ep_premium_license_key ) && ! empty( $ep_premium_license_key ) ) {
                                        if ( isset( $ep_premium_license_status) && $ep_premium_license_status !== false && $ep_premium_license_status == 'valid' ) { ?>
                                            <button type="button" class="button action ep-my-2 ep_license_deactivate" data-prefix="ep_premium" name="ep_premium_license_deactivate" id="ep_premium_license_deactivate" value="<?php esc_html_e('Deactivate License', 'eventprime-event-calendar-management'); ?>" ><?php esc_html_e('Deactivate License', 'eventprime-event-calendar-management'); ?></button>
                                        <?php } elseif( ! empty(  $ep_premium_license_status ) &&  $ep_premium_license_status == 'invalid' ){ ?>
                                            <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="ep_premium" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?>" ><?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?></button>
                                        <?php }else{ ?>
                                            <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="ep_premium" name="ep_premium_license_activate" id="ep_premium_license_activate" style="<?php if ( empty( $ep_premium_license_key ) ){ echo 'display:none'; } ?>" value="<?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?>" ><?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?></button><?php 
                                        } 
                                    }else { ?>
                                        <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="ep_premium" name="ep_premium_license_activate" id="ep_premium_license_activate" style="display:none;" value="<?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?>" ><?php esc_html_e('Activate License', 'eventprime-event-calendar-management'); ?></button>
                                    <?php } ?>
                                </span>
                            </td>
                        </tr>
                        <?php do_action( 'ep_add_license_setting_blocks', $options ); ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>