<?php

namespace Dev4Press\Plugin\GDPOL\Admin;

use Dev4Press\v43\Core\Admin\Menu\Plugin as BasePlugin;
use Dev4Press\Plugin\GDPOL\Basic\Plugin as CorePlugin;
use Dev4Press\Plugin\GDPOL\Basic\Settings as CoreSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {
	public $plugin = 'gd-topic-polls';
	public $plugin_prefix = 'gdpol';
	public $plugin_menu = 'Topic Polls';
	public $plugin_title = 'GD Topic Polls Pro for bbPress';

	public $has_widgets = true;
	public $auto_mod_interface_colors = true;
	public $per_page_options = array(
		'gdpol_polls_rows_per_page',
		'gdpol_votes_rows_per_page',
	);

	public $enqueue_wp = array( 'dialog' => true, 'color_picker' => true );

	public function constructor() {
		$this->url  = GDPOL_URL;
		$this->path = GDPOL_PATH;

		bbPress::instance();
	}

	public function svg_icon() : string {
		return gdpol()->svg_icon;
	}

	public function after_setup_theme() {
		$this->setup_items = array(
			'install' => array(
				'title' => __( 'Install', 'gd-topic-polls' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin installation was successful.', 'gd-topic-polls' ),
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Install',
			),
			'update'  => array(
				'title' => __( 'Update', 'gd-topic-polls' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin was successfully updated.', 'gd-topic-polls' ),
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Update',
			),
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( 'Overview', 'gd-topic-polls' ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Dashboard',
			),
			'about'     => array(
				'title' => __( 'About', 'gd-topic-polls' ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\About',
			),
			'polls'     => array(
				'title' => __( 'Polls', 'gd-topic-polls' ),
				'icon'  => 'ui-chart-bar',
				'table' => true,
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Polls',
			),
			'votes'     => array(
				'title' => __( 'Votes', 'gd-topic-polls' ),
				'icon'  => 'ui-users',
				'table' => true,
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Votes',
			),
			'settings'  => array(
				'title' => __( 'Settings', 'gd-topic-polls' ),
				'icon'  => 'ui-cog',
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Settings',
			),
			'tools'     => array(
				'title' => __( 'Tools', 'gd-topic-polls' ),
				'icon'  => 'ui-wrench',
				'class' => '\\Dev4Press\\Plugin\\GDPOL\\Admin\\Panel\\Tools',
			),
		);
	}

	public function run_getback() {
		new GetBack( $this );
	}

	public function run_postback() {
		new PostBack( $this );
	}

	public function message_process( $code, $msg ) {
		switch ( $code ) {
			case 'vote-delete-failed':
				$msg['message'] = __( 'Deletion operation failed.', 'gd-topic-polls' );
				$msg['color']   = 'error';
				break;
			case 'poll-disable-failed':
			case 'poll-enable-failed':
			case 'poll-delete-failed':
			case 'poll-empty-failed':
				$msg['message'] = __( 'Operation failed, specified poll is invalid.', 'gd-topic-polls' );
				$msg['color']   = 'error';
				break;
			case 'poll-disable-ok':
				$msg['message'] = __( 'Poll is disabled.', 'gd-topic-polls' );
				break;
			case 'poll-enable-ok':
				$msg['message'] = __( 'Poll is enabled.', 'gd-topic-polls' );
				break;
			case 'poll-delete-ok':
				$msg['message'] = __( 'Poll is deleted.', 'gd-topic-polls' );
				break;
			case 'poll-empty-ok':
				$msg['message'] = __( 'Poll votes are all removed.', 'gd-topic-polls' );
				break;
			case 'vote-delete-ok':
				$msg['message'] = __( 'Votes deletion completed.', 'gd-topic-polls' );
				break;
		}

		return $msg;
	}

	public function settings() : CoreSettings {
		return gdpol_settings();
	}

	public function plugin() : CorePlugin {
		return gdpol();
	}

	public function settings_definitions() : Settings {
		return Settings::instance();
	}

	public function register_scripts_and_styles() {
		$this->enqueue->register( 'css', 'gdpol-admin',
			array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => true,
				'ver'  => $this->settings()->file_version(),
				'src'  => 'plugin',
			) )->register( 'js', 'gdpol-admin',
			array(
				'path' => 'js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'ver'  => $this->settings()->file_version(),
				'src'  => 'plugin',
			) );
	}

	public function enqueue_scripts( $hook ) {
		parent::enqueue_scripts( $hook );

		if ( $this->page ) {
			$this->enqueue->css( 'gdpol-admin' );
			$this->enqueue->js( 'gdpol-admin' );
		}
	}

	public function wizard() {
		return null;
	}
}
