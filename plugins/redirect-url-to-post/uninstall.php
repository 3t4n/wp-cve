<?php

/**
* Delete options only if requested
*/
if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {

  delete_option( 'redirect-url-to-post-admin-notice' );

  delete_option( 'redirect-url-to-post-onboarding' );

}
