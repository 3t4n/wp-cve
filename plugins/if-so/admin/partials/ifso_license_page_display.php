<?php
	if ( ! defined( 'ABSPATH' ) ) exit;

    use IfSo\Services\GeolocationService;

	$license = get_option( 'edd_ifso_license_key' );
	$status  = get_option( 'edd_ifso_license_status' );
	$expires = get_option( 'edd_ifso_license_expires' );
	$item_id = get_option( 'edd_ifso_license_item_id' );
    $dummy_license = make_dummy_license($license);

    $geo_license = get_option( 'edd_ifso_geo_license_key' );
    $geo_status  = get_option( 'edd_ifso_geo_license_status' );
    $geo_expires = get_option( 'edd_ifso_geo_license_expires' );
    $geo_dummy_license = make_dummy_license($geo_license);

	function is_license_valid($status) {
		return ( $status !== false && $status == 'valid' );
	}
    function make_dummy_license($key){
        $dummy_license = $key;
        $dummy_license = substr($dummy_license ,18);
        $dummy_license = "❊❊❊❊-❊❊❊❊-❊❊❊❊-❊❊❊".$dummy_license;
        return $dummy_license;
    }
    function get_date_i18n($date,$shorten_month=false) {
        $month_format = $shorten_month ? 'M' : 'F';
        return date_i18n( "{$month_format} j, Y", strtotime( $date, current_time( 'timestamp' ) ) );
    }
?>

<div class="wrap">
    <h2><?php _e('License','if-so'); ?></h2>
	<div class="ifso-license-wrapper">
        <h1 style="margin-top:20px;"><?php _e('Pro License', 'if-so'); ?></h1>
        <p><?php _e("Activate a pro or a free trial license key to unlock all features. No session limit except for the geolocation condition. ", 'if-so'); ?><a href="https://www.if-so.com/plans?utm_source=Plugin&utm_medium=licensePage&utm_campaign=proLicense" target="_blank"><?php _e('Get a pro license.', 'if-so'); ?></a></p>
        <div class="ifso-license-tabs-wrapper">
            <div class="license-tab-wrapper">
            <?php if (!is_license_valid( $status )): ?>
                <?php echo '<div id="nolicense_message_target"></div>'; ?>
            <?php endif; ?>
            <form method="post" action="options.php" class="license-form">
                <?php settings_fields('edd_ifso_license'); ?>
                <table class="form-table license-tbl">
                    <tbody>
                        <tr valign="top">
                            <th class="licenseTable" scope="row" valign="top">
                                <?php _e('License Key','if-so'); ?>
                            </th>
                            <td>
                                <input id="edd_ifso_license_key" <?php echo ( is_license_valid( $status ) ) ? "readonly":""; ?>
                                name="edd_ifso_license_key" type="text" class="regular-text" placeholder=<?php echo ($license) ? $dummy_license : '&nbsp;';?>
                                value=<?php echo ($license) ? $dummy_license:"";?>>
                                <?php
                                    if ( $this->edd_ifso_is_in_activations_process() ) {
                                        // in activations process
                                        $error_message = $this->edd_ifso_get_error_message('pro');
                                        if ( $error_message ) {
                                ?>
                                            <span class="description license-error-message">
                                                <?php echo $error_message; ?>
                                            </span>
                                <?php
                                        }
                                    } else {
                                         if(!$status) : ?>
                                            <label class="description" for="edd_ifso_license_key"><?php _e('Enter your pro/free trial license key','if-so'); ?></label>
                                            <?php else: ?>
                                                <label class="description active"><?php _e('Active', 'if-so'); ?></label>
                                            <?php endif;?>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th class="licenseTable" scope="row" valign="top">
                                <!--<?php _e('Activate License'); ?>-->
                            </th>
                            <td>
                                <?php if( $status !== false && $status == 'valid' ) { ?>
                                    <?php wp_nonce_field( 'edd_ifso_nonce', 'edd_ifso_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="edd_ifso_license_deactivate" value="<?php _e('Deactivate License','if-so'); ?>"/>
                                <?php } else {
                                    wp_nonce_field( 'edd_ifso_nonce', 'edd_ifso_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="edd_ifso_license_activate" value="<?php _e('Activate License','if-so'); ?>"/>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a class="clear-license-link" style="margin-top:10px;float: right;" title="Click here if you are having trouble deactivating your license or if you want to purge the license data from the system completely." href="<?php echo $_SERVER['REQUEST_URI']; ?>&edd_ifso_license_clear=true&edd_ifso_nonce=<?php echo wp_create_nonce( "edd_ifso_nonce" );?>"><?php _e('Clear License','if-so'); ?></a>
                <?php $lifetime_license_message = __('Your license is Lifetime.','if-so');
                      $expires_license_message = __('Your license key expires on','if-so');
                ?>
                <!-- License key expiratiaton date -->
                <?php if ($status == 'valid' && $expires == 'lifetime') { ?>
                <div class="license_expires_message"></span><?php echo $lifetime_license_message;?></div>
                <?php } else if ( $status == 'valid' && $expires !== false ) { ?>
                <div class="license_expires_message"><?php echo $expires_license_message;?> <span class="expire_date"><?php echo date_i18n( 'F j, Y', strtotime( $expires, current_time( 'timestamp' ) ) ); ?>.</span></div>
                <?php } ?>
            </form>

            <?php if ($status !== false && $status == 'valid' ): ?>
                <div class="approved_license_message">
                    <?php _e('<strong>Thank you for using If-So Dynamic Content!</strong> Please feel free to contact our team with any questions you may have.','if-so') ?>
                </div>
            <?php endif; ?>

			</div> <!-- end of license-tab-wrapper -->
		</div> <!-- end of ifso-settings-tabs-wrapper -->
	</div>

    <div class="geo-license-section">
        <h1 style="margin-top:20px;"><?php _e('Geolocation License', 'if-so'); ?></h1>
        <p><?php _e('A geolocation license key allows you to upgrade your monthly geolocation session limit. A geolocation license can be activated alone or in addition to a pro license.', 'if-so'); ?> <a href="?https://www.if-so.com/plans/geolocation-plansutm_source=Plugin&utm_medium=licensePage&utm_campaign=geoLicense" target="_blank"><?php _e('Get a geolocation license.', 'if-so'); ?></a></p>
        <div class="ifso-license-tabs-wrapper">
            <form method="post" action="options.php" class="license-form">
                <?php settings_fields('edd_ifso_license'); ?>
                <table class="form-table license-tbl">
                    <tbody>
                    <tr valign="top">
                        <th class="licenseTable" scope="row" valign="top">
                            <?php _e('License Key'); ?>
                        </th>
                        <td>
                            <input id="edd_ifso_geo_license_key" <?php echo ( is_license_valid( $geo_status ) ) ? "readonly":""; ?>
                                   name="edd_ifso_geo_license_key" value="<?php echo ($geo_license) ? $geo_dummy_license:"";?>" type="text" class="regular-text" placeholder=<?php echo ($geo_license) ? $geo_dummy_license : '&nbsp;';?>>
                            <input type="hidden" name="for_geo_deactivation" value=<?php echo (strpos($geo_license, 'PR') !== false || strpos($geo_license, 'FE') !== false) ? "" : $geo_license; ?>>
                            <?php
                            if ( $this->edd_ifso_is_in_activations_process() ) {
                                // in activations process
                                $error_message = $this->edd_ifso_get_error_message('geo');
                                if ( $error_message ) {
                                    ?>
                                    <span class="description license-error-message"><?php echo $error_message; ?></span>
                                    <?php
                                }
                            } else {
                                ?>
                                <?php if(!$geo_status) : ?>
                                    <label class="description" for="edd_ifso_geo_license_key"><?php _e('Enter your license key', 'if-so'); ?></label>
                                <?php else: ?>
                                    <label class="description active"><?php _e('Active', 'if-so'); ?></label>
                                <?php endif;?>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="licenseTable" scope="row" valign="top">
                            <!--<?php _e('Activate License'); ?>-->
                        </th>
                        <td>
                            <?php if( $geo_status !== false && $geo_status == 'valid' ) { ?>
                                <?php wp_nonce_field( 'edd_ifso_nonce', 'edd_ifso_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="edd_ifso_geo_license_deactivate" value="<?php _e('Deactivate License', 'if-so'); ?>"/>
                            <?php } else {
                                wp_nonce_field( 'edd_ifso_nonce', 'edd_ifso_nonce' ); ?>
                                <input type="submit" class="button-secondary" name="edd_ifso_geo_license_activate" value="<?php _e('Activate License', 'if-so'); ?>"/>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <a class="clear-license-link" style="margin-top:10px;float: right;" title="Click here if you are having trouble deactivating your Geo license or if you want to purge the license data from the system completely." href="<?php echo $_SERVER['REQUEST_URI']; ?>&edd_ifso_geo_license_clear=true&edd_ifso_nonce=<?php echo wp_create_nonce( "edd_ifso_nonce" );?>"><?php _e('Clear Geo License','if-so'); ?></a>
            <!-- Geo License key expiratiaton date -->
            <?php if ($geo_status == 'valid' && $geo_expires == 'lifetime') { ?>
                <div class="license_expires_message"><?php _e('Your Geolocation License is Activated.', 'if-so'); ?></span></div>
            <?php } else if ( $geo_status == 'valid' && $geo_expires !== false ) { ?>
                <div class="license_expires_message"><?php _e('Your geolocation license key expires on ', 'if-so'); ?><span class="expire_date"><?php echo get_date_i18n($geo_expires); ?>.</span></div>
            <?php } ?>
        </div>
    </div>
</div>


<p class='ifso-privacy-policy-link'>
    <a target='_blank' href='https://www.if-so.com/privacy-policy/' style='text-decoration:underline;color:#3c434a;'>Privacy Policy</a>
</p>

<script>
	document.querySelector('.license-form').addEventListener('submit',function(e){
		var dael = document.createElement('input');
		dael.setAttribute('name','for_deactivation');
		dael.setAttribute('type','hidden');
		dael.setAttribute('value',"<?php echo (strpos($license, 'GE') === 0) ? '' : $license; ?>");
		e.target.append(dael);
	});
</script>