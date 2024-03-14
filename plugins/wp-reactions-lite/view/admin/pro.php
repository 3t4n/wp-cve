<?php

use WP_Reactions\Lite\Helper;

global $wpra_lite;
?>
<div class="wpreactions primary-color-blue wpra-pro-page">
    <!-- top bar -->
	<?php Helper::getTemplate(
		'view/admin/components/top-bar',
		[
			"logo" => Helper::getAsset( 'images/wpj_logo.png' ),
		]
	); ?>
    <div class="wpra-banner wpra-white-box align-items-center mt-3 mb-3">
        <div class="wpra-banner-img">
            <img src="<?php echo Helper::getAsset( 'images/banners/rocket-and-man.png' ); ?>"  alt="14-days-back">
        </div>
        <div class="wpra-banner-content">
            <h3>Go Pro</h3>
            <p>Get more with WP Reactions PRO. Start engaging your audience with JoyPixels 3.5 animated emojis and more.</p>
            <a href="#" class="btn btn-purple">Upgrade to Pro</a>
        </div>
    </div>
    <div class="wpra-white-box">
        <div class="pro-features-table">
            <table>
                <thead>
                <tr>
                    <th>Features</th>
                    <th>Free</th>
                    <th>Pro</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php _e( '200 JoyPixels 3.0 Lottie animated emoji reactions', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( '200 JoyPixels 3.0 SVG emoji reactions', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Our emoji picker lets you mix, match and arrange', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Classic Reactions with extended features', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'The Reaction Button for maximum engagement', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Global Activation with extended features', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Generate shortcode easily and paste anywhere', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Loaded with the top social media platforms', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Manage your Shortcode easily with our Shortcode Editor', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Collect user reaction data on each page/post', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Endless emoji combinations and styling options', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Premium customer support', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td><?php _e( 'Automatic Updates you won’t want to miss', 'wpreactions-lite' ); ?></td>
                    <td><span class="dashicons dashicons-no-alt"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-right mt-3">
        <a href="https://wpreactions.com/pricing" class="btn btn-purple w-100" target="_blank">Upgrade to Pro</a>
    </div>
    <div class="wpra-banner align-items-center wpra-white-box mt-3">
        <img src="<?php echo Helper::getAsset( 'images/14days_money_back.png' ); ?>" class="wpra-banner-img" style="width: 160px;" alt="14-days-back">
        <div class="wpra-banner-content">
            <h3>14 Days Money Back</h3>
            <p>You are fully protected by our 100% Zero Risk Guarantee. If you are not fully satisfied for any reason, simply contact us within 14 days and we will happily refund 100% of your money. </p>
        </div>
    </div>
</div>
