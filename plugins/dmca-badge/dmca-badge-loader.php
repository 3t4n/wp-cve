<?php
/*
 * This code is run after Imperative validates
 * the required libraries are available and loaded.
 */
define( 'DMCA_BADGE_DIR', dirname( __FILE__ ) );
define( 'DMCA_BADGE_VER', '1.8' );
define( 'DMCA_BADGE_MIN_PHP', '5.2.4' );
define( 'DMCA_BADGE_MIN_WP', '3.2' );

require( DMCA_BADGE_DIR . '/classes/class-list-pages.php');
require( DMCA_BADGE_DIR . '/classes/class-plugin.php');
require( DMCA_BADGE_DIR . '/classes/class-widget.php');
if ( WP_DEBUG ) {
  require( DMCA_BADGE_DIR . '/classes/class-test-page.php' );
}