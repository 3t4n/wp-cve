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
class FFBackupTab implements LATab {
	public function __construct() {
	}

	public function id() {
		return 'backup-tab';
	}

	public function flaticon() {
		return 'flaticon-data';
	}

	public function title() {
		return 'Database';
	}

	public function includeOnce( $context ) {
		$context['backups'] = [];
		/** @noinspection PhpIncludeInspection */
		include_once($context['root']  . 'views/backup.php');
	}
}