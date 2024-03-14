<?php
/*
Plugin Name: CATS Job Listings
Description: Add your open jobs to any page on your site. Works with the CATS Applicant Tracking System.
Version:     2.0.6
Author:      catssoft (dev@catsone.com)
Author URI:  http://www.catsone.com
License:     GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html
PHP:         5.3+
*/

if (version_compare(phpversion(), '5.3.10', '<')) {
    exit(sprintf('CATS Job Listings Plugin requires PHP 5.3.10 or higher. You’re still on %s.', PHP_VERSION));
}

/* On Plugin Activation */
function CATSJobListingActivationHook() {
    $alreadyInstalled = get_option('cats-options');
    if (!$alreadyInstalled) {
        add_option('cats-version', '2');
    }

    add_option('cats-options', array(
        'url' => array(
            'domain' => 'catsone.com',
            'subdomain' => ''
        ),
        'portal-id' => ''
    ));
}
register_activation_hook(__FILE__, 'CATSJobListingActivationHook');

/* On Plugin Load */
include 'CatsJobListingAdmin.php';
include 'CATSJobListingUser.php';

new CATSJobListingUser();
if (is_admin()) {
    new CATSJobListingAdmin();
}



