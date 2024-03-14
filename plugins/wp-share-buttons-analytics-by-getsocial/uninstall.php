<?php

/**
 * Trigger Uninstall process only if WP_UNINSTALL_PLUGIN is defined
 */

//if uninstall not called from WordPress exit
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
    exit();
}

if (defined('WP_UNINSTALL_PLUGIN')) {

	// delete any options or other data stored in the database here
  delete_site_option('gs-alert-cta');
  delete_site_option('gs-alert-msg');
  delete_site_option('gs-alert-utm');
  delete_site_option('gs-api-key');
  delete_site_option('gs-apps');
  delete_site_option('gs-ask-review');
  delete_site_option('gs-has-subscriptions');
  delete_site_option('gs-identifier');
  delete_site_option('gs-lang');
  delete_site_option('gs-place');
  delete_site_option('gs-place-follow');
  delete_site_option('gs-posts-page');
  delete_site_option('gs-pro');
  delete_site_option('gs-user-email');
  delete_site_option('gs-popup-showed');
}
