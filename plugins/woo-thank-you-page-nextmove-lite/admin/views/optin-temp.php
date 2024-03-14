<?php
defined( 'ABSPATH' ) || exit;

global $current_user;

$user_instance = 'Hey';
if ( is_object( $current_user ) ) {
	$user_instance .= ' ' . $current_user->display_name . ',';
}
$non_sensitive_page_link = esc_url( 'https://xlplugins.com/non-sensitive-usage-tracking/?utm_source=nextmove-lite&utm_campaign=optin&utm_medium=text-click&utm_term=non-sensitive' );
$accept_link             = esc_url( wp_nonce_url( add_query_arg( array(
	'xl-optin-choice' => 'yes',
	'ref'             => filter_input( INPUT_GET, 'page' ),
) ), 'xl_optin_nonce', '_xl_optin_nonce' ) );
$skip_link               = esc_url( wp_nonce_url( add_query_arg( 'xl-optin-choice', 'no' ), 'xl_optin_nonce', '_xl_optin_nonce' ) );
?>
<div id="xlo-wrap" class="wrap">
    <div class="xlo-logos">
        <img class="xlo-plugin-icon" width="80" height="80" src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/wc.png'; ?>"/>
        <i class="dashicons dashicons-plus xlo-first"></i>
        <img class="xlo-wrap-logo" width="80" height="80" src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/xlplugins.png'; ?>"/>
    </div>
    <div class="xlo-content">
        <p><?php echo $user_instance; ?><br></p>
        <h2>Thank you for choosing NextMove!</h2>
        <p>We are constantly improving the plugin and building in new features.</p>
        <p>Never miss an update! Opt in for security, feature updates and non-sensitive diagnostic tracking. Click Allow &amp; Continue'!</p>
    </div>
    <div class="xlo-actions" data-source="NextMove">
        <a href="<?php echo $skip_link; ?>" class="button button-secondary" data-status="no">Skip</a>
        <a href="<?php echo $accept_link; ?>" class="button button-primary" data-status="yes">Allow &amp; Continue</a>
        <div style="display: none" class="xlo_loader">&nbsp;</div>
    </div>
    <div class="xlo-error-boundary"></div>
    <div class="xlo-permissions">
        <a class="xlo-trigger" href="#" tabindex="1">What permissions are being granted?</a>
        <ul>
            <li id="xlo-permission-profile" class="xlo-permission xlo-profile">
                <i class="dashicons dashicons-admin-users"></i>
                <div>
                    <span>Your Profile Overview</span>
                    <p>Name and email address</p>
                </div>
            </li>
            <li id="xlo-permission-site" class="xlo-permission xlo-site">
                <i class="dashicons dashicons-admin-settings"></i>
                <div>
                    <span>Your Site Overview</span>
                    <p>Site URL, WP version, PHP info, plugins &amp; themes</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="xlo-terms">
        <a href="<?php echo $non_sensitive_page_link; ?>" target="_blank">Non-Sensitive Usage Tracking</a>
    </div>
</div>
