<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license = get_option( 'wpvr_edd_license_key' );
$status  = get_option( 'wpvr_edd_license_status' );
?>
<!-- This file should display the admin pages -->
<div class="row">
    <div class="rex-onboarding" style="min-height: 250px !important;">
            <form method="post" action="options.php" style="width: 100%;">

                <?php settings_fields('wpvr_edd_license'); ?>

                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('License Key','wpvr'); ?>
                            </th>
                            <td>
                                <input id="wpvr_edd_license_key" name="wpvr_edd_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                                <label class="description" for="wpvr_edd_license_key"><?php _e('Enter your license key, save changes and activate.','wpvr'); ?></label>
                            </td>
                        </tr>
                        <?php if( false !== $license ) { ?>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Activate License','wpvr'); ?>
                                </th>
                                <td>
                                    <?php if( $status !== false && $status == 'valid' ) { ?>
                                        <span style="color:green;"><?php _e('active'); ?></span>
                                        <?php wp_nonce_field( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ); ?>
                                        <input type="submit" class="button-secondary" name="wpvr_edd_license_deactivate" value="<?php _e('Deactivate License','wpvr'); ?>"/>
                                    <?php } else {?>
                                        
                                        <?php wp_nonce_field( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ); ?>
                                        <input type="submit" class="button-secondary" name="wpvr_edd_license_activate" value="<?php _e('Activate License','wpvr'); ?>"/>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
    </div>
</div>

