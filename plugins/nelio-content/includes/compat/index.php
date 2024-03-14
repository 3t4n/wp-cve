<?php
/**
 * This file defines some additional hooks to make Nelio Content compatible with third-party plugins and themes.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/compat
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/divi.php';
require_once dirname( __FILE__ ) . '/mailpoet.php';
require_once dirname( __FILE__ ) . '/pagefrog.php';
require_once dirname( __FILE__ ) . '/the-events-calendar.php';
require_once dirname( __FILE__ ) . '/nelio-ab-testing.php';
require_once dirname( __FILE__ ) . '/user-submitted-posts.php';
require_once dirname( __FILE__ ) . '/wpml.php';
