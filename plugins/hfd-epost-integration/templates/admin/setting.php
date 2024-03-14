<?php
/**
 * Created by PhpStorm.
 * Date: 6/7/18
 * Time: 8:55 AM
 */
/* @var \Hfd\Woocommerce\Setting $setting */

$order_auto_sync = $setting->get( 'hfd_order_auto_sync' );
$hfd_auto_sync_status = $setting->get( 'hfd_auto_sync_status' );
$hfd_auto_sync_time = $setting->get( 'hfd_auto_sync_time' );
if( empty( $order_auto_sync ) ){
	$order_auto_sync = 'no';
}
?>
<div id="ch2pho-general" class="wrap">
    <h2><?php echo esc_html( __( 'HFD Sync Settings', 'hfd-integration' ) ); ?></h2>
    <form method="post" action="admin-post.php">

        <input type="hidden" name="action" value="save_epost_setting"/>
        <!-- Adding security through hidden referrer field -->
        <?php wp_nonce_field( 'epost_setting' ); ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Layout', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="betanet_epost_layout" class="regular-text">
                            <option value="map"><?php esc_html_e( 'Map', 'hfd-integration' ); ?></option>
                            <option value="list" <?php if ( $setting->get('betanet_epost_layout') == 'list' ) : ?>selected<?php endif ?>><?php esc_html_e( 'List', 'hfd-integration' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Google map API key', 'hfd-integration' ); ?></th>
                    <td>
                        <input type="text" name="betanet_epost_google_api_key" class="regular-text"
                               value="<?php echo esc_html( $setting->get( 'betanet_epost_google_api_key' ) ); ?>"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?php esc_html_e( 'HFD Configuration', 'hfd-integration' ); ?></h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Enable HFD Integration', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="betanet_epost_hfd_active" class="regular-text">
                            <option value="0"><?php esc_html_e( 'No', 'hfd-integration' ); ?></option>
                            <option value="1" <?php if ( $setting->get( 'betanet_epost_hfd_active' ) ) : ?>selected<?php endif ?>><?php esc_html_e( 'Yes', 'hfd-integration' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Authorization token', 'hfd-integration' ); ?></th>
                    <td>
                        <input type="text" name="betanet_epost_hfd_auth_token" class="regular-text"
                           value="<?php echo esc_html( $setting->get( 'betanet_epost_hfd_auth_token' ) ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <?php
                        $allowShippingMethods = $setting->get('betanet_epost_hfd_shipping_method');
                        if (!$allowShippingMethods) {
                            $allowShippingMethods = array();
                        }
                    ?>
                    <th scope="row"><?php esc_html_e( 'Allow shipping methods', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="betanet_epost_hfd_shipping_method[]" class="regular-text" multiple>
                            <?php foreach (WC()->shipping->load_shipping_methods() as $method) : ?>
                                <option value="<?php echo esc_attr($method->id) ?>" <?php if (in_array($method->id, $allowShippingMethods)) : ?>selected<?php endif ?>>
                                    <?php echo esc_attr($method->get_method_title()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Company name', 'hfd-integration' ); ?></th>
                    <td>
                        <input type="text" name="betanet_epost_hfd_sender_name" class="regular-text" value="<?php echo esc_html( $setting->get( 'betanet_epost_hfd_sender_name' ) ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Customer number', 'hfd-integration' ); ?></th>
                    <td>
                        <input type="text" name="betanet_epost_hfd_customer_number" class="regular-text"
                               value="<?php echo esc_html( $setting->get( 'betanet_epost_hfd_customer_number' ) ); ?>"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Api Debug', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="betanet_epost_hfd_debug" class="regular-text">
                            <option value="0"><?php esc_html_e( 'No', 'hfd-integration' ); ?></option>
                            <option value="1" <?php if ( $setting->get( 'betanet_epost_hfd_debug' ) ) : ?>selected<?php endif ?>><?php esc_html_e( 'Yes', 'hfd-integration' ); ?></option>
                        </select>
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Order auto sync', 'hfd-integration' ); ?></th>
                    <td>
						<div>
							<input type="radio" name="hfd_order_auto_sync" value="yes" <?php checked( $order_auto_sync, 'yes' ); ?> /> <?php esc_html_e( 'Yes', 'hfd-integration' ); ?>
						</div>
						<div>
							<input type="radio" name="hfd_order_auto_sync" value="no" <?php checked( $order_auto_sync, 'no' ); ?> /> <?php esc_html_e( 'No', 'hfd-integration' ); ?>
						</div>
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Auto sync status', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="hfd_auto_sync_status" class="regular-text">
							<option value=""><?php esc_html_e( '--Select--', 'hfd-integration' ); ?></option>
                            <?php
							if( function_exists( 'wc_get_order_statuses' ) ){
								$statuses = wc_get_order_statuses();
								if( $statuses ){
									foreach( $statuses as $statusCode => $status ){
										?>
										<option value="<?php echo esc_html( $statusCode ); ?>" <?php selected( $hfd_auto_sync_status, $statusCode ); ?>><?php echo esc_html( $status ); ?></option>
										<?php
									}
								}
							}
							?>
                        </select>
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Auto sync time', 'hfd-integration' ); ?></th>
                    <td>
                        <select name="hfd_auto_sync_time" class="regular-text">
							<option value=""><?php esc_html_e( '--Select--', 'hfd-integration' ); ?></option>
							<option value="15" <?php selected( $hfd_auto_sync_time, 15 ); ?>>15</option>
							<option value="30" <?php selected( $hfd_auto_sync_time, 30 ); ?>>30</option>
							<option value="60" <?php selected( $hfd_auto_sync_time, 60 ); ?>>60</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="<?php esc_html_e( 'Save Changes', 'hfd-integration' ); ?>" class="button-primary" />
    </form>
</div>
