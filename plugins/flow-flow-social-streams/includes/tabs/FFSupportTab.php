<?php namespace flow\tabs;
if ( ! defined( 'WPINC' ) ) die;

use la\core\tabs\LATab;

/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFSupportTab implements LATab {
	public function __construct() {
	}

	public function id() {
		return 'support-tab';
	}

	public function flaticon() {
		return 'flaticon-data';
	}

	public function title() {
		return 'Support';
	}

	public function includeOnce( $context ) {
		include_once($context['root']  . 'views/support.php');
	}
}