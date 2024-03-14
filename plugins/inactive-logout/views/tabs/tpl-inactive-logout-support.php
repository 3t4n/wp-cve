<?php
/**
 * Template for Basic settings page.
 *
 * @package inactive-logout
 */

use Codemanas\InactiveLogout\Helpers;
?>

<div class="ina-settings-admin-wrap ina-settings-admin-support">
	<?php if ( ! Helpers::is_pro_version_active() ) { ?>
        <div class="ina-settings-admin-support-bg">
            <h3>Need more features ?</h3>
            <p>Among many other features/enhancements, inactive logout pro comes with a few additional features if you feel like you need it. <span class="dashicons dashicons-external"></span> <a target="_blank" href="https://www.inactive-logout.com/">Check out the pro version here</a> to download.</p>
            <ol>
                <li>Auto browser close logout.</li>
                <li>Multiple tab sync.</li>
                <li>More options to role based settings.</li>
                <li>Override multiple login priority.</li>
                <li>Login Redirections - Redirect on login.</li>
                <li>Logout Redirections - Redirect on Logout.</li>
                <li>Login/Logout time - Last login and logout time.</li>
                <li>Last Active Timestamp - When was user last active.</li>
                <li>Online Status - Is user currently active or inactive.</li>
                <li>Force logout by admin on any users.</li>
                <li>Disable inactive logout for specified pages according to your need.</li>
                <li>Track User Session and Logout individually.</li>
                <li>Logout popups for manually revoked user sessions.</li>
            </ol>
            And more..
        </div>

        <div class="ina-settings-admin-support-bg">
            <p>If you encounter any issues or have any queries please use the <span class="dashicons dashicons-external"></span> <a href="https://wordpress.org/support/plugin/inactive-logout" target="_blank">support forums</a> or <span class="dashicons dashicons-external"></span> <a target="_blank" href="https://www.imdpen.com/contact" target="_blank">send a support mail</a>. I will reply to you at the earliest possible.</p>
        </div>
	<?php } else { ?>
        <div class="ina-settings-admin-support-bg">
            <h3>Premium Support Ticket</h3>
            <p>Create a ticket from <span class="dashicons dashicons-external"></span> <a target="_blank" href="https://inactive-logout.com/support/">Support forum</a>. Check <span class="dashicons dashicons-external"></span> <a target="_blank" href="https://inactive-logout.com/changelogs/">site</a> for recent change logs and updates.</p>
        </div>
	<?php } ?>

    <div class="ina-settings-admin-support-bg">
        <h3>Rate Inactive Logout</h3>
        <p>We really appreciate if you can spare a minute to <span class="dashicons dashicons-external"></span> <a href="https://wordpress.org/support/plugin/inactive-logout/reviews/?filter=5#new-post" target="_blank">rate the plugin.</a></p>
    </div>

    <div class="ina-settings-admin-support-bg">
        <h3>Developer</h3>
        <p>Feel free to reach me from <span class="dashicons dashicons-external"></span> <a href="https://www.imdpen.com/contact" target="_blank">Here</a>, if you have any questions or queries.</p>
    </div>
</div>
