<?php

/**
 * Leave no trace...
 *
 */

// Only proceed if called via WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  die;
}

// Clear any options that have been set
$options = array(
  'memberspace_display_banner',
  'memberspace_extra_security',
  'memberspace_last_updated',
  'memberspace_public_key',
  'memberspace_rules',
  'memberspace_site_contact',
  'memberspace_site_ID',
  'memberspace_subdomain',
  'memberspace_last_sync_successful'
);

foreach ( $options as $option ) {
  delete_option( $option );
}
