<?php
/**
 * Free White Label functions.
 *
 * @package White Label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add White Label Pro features upsell to Upgrade section.
 *
 * @return void
 */
function white_label_free_upgrade()
{
    echo '
    <div class="white-label-subsection white-label-upgrade-tab">
        <h1><a href="href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=upgrade_tab" target="_blank"">Upgrade to White Label Pro</a></h1>

        <p>
        Give your clients and users an even better WordPress experience with <a href="https://whitewp.com/pro?utm_source=plugin_white_label&utm_content=upgrade_tab" target="_blank">White Label Pro</a>.
        <br>Unlock the following set of features when you upgrade:
        </p>

        <div class="white-label-grid">
            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/rename-wordpress-admin-menus/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/04/featured-rename-wordpress-admin-menus-300x158.png">
                </a>
                <h3>Rename Admin Menus</h3>
                <p>Change the text for any of the sidebar menu items shown on the admin side of your WordPress installation.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/rename-wordpress-admin-menus/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/change-wordpress-admin-menu-icon/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/12/featured-change-wordpress-admin-menu-icon-300x158.png">
                </a>
                <h3>Change Admin Menu Icons</h3>
                <p>Replace any icon in the sidebar menu with one of the 300 Dashicons available in WordPress.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/change-wordpress-admin-menu-icon/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/change-wordpress-plugin-details/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/06/featured-change-wordpress-plugin-details-300x158.png">
                </a>
                <h3>Change Plugin Details</h3>
                <p>Change individual plugin details (name, description, links, etc.) that display on the Plugins screen.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/change-wordpress-plugin-details/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/hide-wordpress-admin-menus/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/04/featured-hide-wordpress-admin-menus-300x158.png">
                </a>
                <h3>Hide Admin Bar Items</h3>
                <p>Hide individual front-end or back-end menu items from the admin bar at the top of your site.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/hide-wordpress-admin-menus/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/rename-wordpress-admin-menus/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/04/featured-rename-wordpress-admin-menus-300x158.png">
                </a>
                <h3>Rename Admin Bar Items</h3>
                <p>Rename any of the front-end or back-end menu items from the admin bar at the top of your site.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/rename-wordpress-admin-menus/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/remove-wordpress-admin-bar/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/12/featured-remove-wordpress-admin-bar-300x158.png">
                </a>
                <h3>Remove Front End Admin Bar</h3>
                <p>Remove the admin bar from the front end of your WordPress installation for non-administrators and other users.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/remove-wordpress-admin-bar/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/remove-wordpress-dashboard-widgets/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/04/featured-remove-wordpress-dashboard-widgets-300x158.png">
                </a>
                <h3>Remove Individual Dashboard Widgets</h3>
                <p>Pick and choose individual dashboard widgets to remove from your dashboard.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/remove-wordpress-dashboard-widgets/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/hide-wordpress-update-nags/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/04/featured-hide-wordpress-update-nags-300x158.png">
                </a>
                <h3>Hide Update Notifications & Nags</h3>
                <p>Hide WordPress update notification alerts and nags from non-White Label Administrators.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/hide-wordpress-update-nags/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-admin-notifications/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/06/featured-wordpress-admin-notifications-300x158.png">
                </a>
                <h3>Remove Admin Notifications</h3>
                <p>Prevent all admin notifications from being displayed to users who are not White Label Administrators.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-admin-notifications/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-screen-options-button/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/06/featured-wordpress-screen-options-button-300x158.png">
                </a>
                <h3>Remove Screen Options Button</h3>
                <p>Remove the Screen Options button from the top of every screen in the WordPress admin.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-screen-options-button/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-help-button/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/06/featured-wordpress-help-button-300x158.png">
                </a>
                <h3>Remove Help Button</h3>
                <p>Remove the Help button from the top of every screen in the WordPress admin.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-help-button/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-login-redirect/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/05/featured-wordpress-login-redirect-300x158.png">
                </a>
                <h3>Login Redirect</h3>
                <p>Redirect users to a specific URL after they have successfully logged in to the WordPress admin.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-login-redirect/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-default-email-settings/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/06/featured-wordpress-default-email-settings-300x158.png">
                </a>
                <h3>Change Default Email Settings</h3>
                <p>Change the default WordPress email settings to your own business name and email address.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-default-email-settings/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/wordpress-admin-email-verification-screen/">
                    <img src="https://whitewp.com/wp-content/uploads/2022/07/featured-wordpress-admin-email-verification-screen-300x158.png">
                </a>
                <h3>Disable Administration Email Verification</h3>
                <p>Disable the Administration Email Verification screen when administrators log into WordPress.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/wordpress-admin-email-verification-screen/">Learn More</a>
            </div>

            <div>
                <a target="_blank" href="https://whitewp.com/documentation/article/add-css-to-wordpress-admin/">
                    <img src="https://whitewp.com/wp-content/uploads/2023/04/featured-add-css-to-wordpress-admin-300x158.png">
                </a>
                <h3>Insert Custom CSS</h3>
                <p>Add your own custom CSS rules to the WordPress admin for a deeper level of customization.</p>
                <a class="white-label-learn-more" target="_blank" href="https://whitewp.com/documentation/article/add-css-to-wordpress-admin/">Learn More</a>
            </div>
        </div>
	</div>
    ';
}

add_action('white_label_form_bottom_white_label_upgrade', 'white_label_free_upgrade');
