<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use Dev4Press\Plugin\GDPOL\bbPress\Integrate;
use Dev4Press\v43\Core\Plugins\Core;
use Dev4Press\v43\Core\Shared\Enqueue;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public $svg_icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNTEyIj48cGF0aCBmaWxsPSIjMDAwMDAwIiBkPSJNMzEuMzI1LDI1NkwxNzUuNjYyLDZMNDY0LjMzOCw2TDYwOC42NzUsMjU2TDQ2NC4zMzgsNTA2TDE3NS42NjIsNTA2TDMxLjMyNSwyNTZaTTU1Ljk1OCwyNTZMMTg3Ljk3OSw0ODQuNjY3TDQ1Mi4wMjEsNDg0LjY2N0w1ODQuMDQyLDI1Nkw0NTIuMDIxLDI3LjMzM0wxODcuOTc5LDI3LjMzM0w1NS45NTgsMjU2Wk01MDEuMTY3LDM4MS41NDNMNTAxLjE2NywyMTEuMzMzQzUwMS4xNjcsMjExLjMzMyA0NTMuODMzLDIxMS4zMzMgNDUzLjgzMywyMTEuMzMzQzQ1My44MzMsMjExLjMzMyA0NTMuODMzLDQ0OC42NjcgNDUzLjgzMyw0NDguNjY3TDQ2Mi40MTMsNDQ4LjY2N0w0NTIuMDIxLDQ2Ni42NjdMNDMyLjUsNDY2LjY2N0w0MzIuNSwxOTBMNTIyLjUsMTkwTDUyMi41LDM0NC41OTNMNTAxLjE2NywzODEuNTQzWk00MTcuNSwyNTBMNDE3LjUsNDcwTDMyNy41LDQ3MEwzMjcuNSwyNTBMNDE3LjUsMjUwWk0zOTYuMTY3LDI3MS4zMzNMMzQ4LjgzMywyNzEuMzMzQzM0OC44MzMsMzIyLjYzIDM0OC44MzMsNDQ4LjY2NyAzNDguODMzLDQ0OC42NjdMMzk2LjE2Nyw0NDguNjY3TDM5Ni4xNjcsMjcxLjMzM1pNMTE3LjUsMzQ0LjU5M0wxMTcuNSwzMDkuMzIzTDIwNy41LDMwOS4zMjNMMjA3LjUsNDY2LjY2N0wxODcuOTc5LDQ2Ni42NjdMMTc3LjE5Niw0NDcuOTlMMTg2LjE2Nyw0NDcuOTlMMTg2LjE2NywzMzAuNjU2TDEzOC44MzMsMzMwLjY1NkwxMzguODMzLDM4MS41NDNMMTE3LjUsMzQ0LjU5M1pNMzEyLjUsMTEwTDMxMi41LDQ3MEwyMjIuNSw0NzBMMjIyLjUsMTEwTDMxMi41LDExMFpNMjkxLjE2NywxMzEuMzMzTDI0My44MzMsMTMxLjMzM0MyNDMuODMzLDEzMS4zMzMgMjQzLjgzMyw0NDguNjY3IDI0My44MzMsNDQ4LjY2N0wyOTEuMTY3LDQ0OC42NjdMMjkxLjE2NywxMzEuMzMzWiIvPjwvc3ZnPg==';

	public $plugin = 'gd-topic-polls';

	private $_poll = null;

	private $_objects = null;
	private $_bbpress = null;
	private $_query = null;

	private $_buttons = array();

	public $theme_package = 'default';

	public function __construct() {
		$this->url = GDPOL_URL;

		parent::__construct();
	}

	public function s() {
		return gdpol_settings();
	}

	public function f() {
		return null;
	}

	public function run() {
		do_action( 'gdpol_load_settings' );

		$this->_objects = new Registration();

		if ( get_option( '_bbp_theme_package_id' ) == 'quantum' ) {
			$this->theme_package = 'quantum';
		}

		add_action( 'init', array( $this, 'register_objects' ), 2 );
		add_action( 'init', array( $this, 'plugin_init' ), 20 );

		add_filter( 'bbp_get_caps_for_role', array( $this, 'add_caps_to_roles' ), 10, 2 );

		if ( ! is_admin() ) {
			Enqueue::init();

			add_action( 'd4plib_shared_enqueue_prepare', array( $this, 'register_css_and_js' ) );
		}
	}

	public function register_css_and_js() {
		Enqueue::i()->add_css( 'gdpol-topic-polls', array(
			'lib'  => false,
			'url'  => GDPOL_URL . 'templates/' . $this->theme_package . '/css/',
			'file' => 'topic-polls',
			'ver'  => gdpol_settings()->file_version(),
			'ext'  => 'css',
			'min'  => true,
		) );

		Enqueue::i()->add_js( 'gdpol-topic-polls', array(
			'lib'      => false,
			'url'      => GDPOL_URL . 'templates/default/js/',
			'file'     => 'topic-polls',
			'ver'      => gdpol_settings()->file_version(),
			'ext'      => 'js',
			'min'      => true,
			'footer'   => true,
			'localize' => true,
		) );
	}

	public function after_setup_theme() {
		require_once( GDPOL_PATH . 'core/functions/templates.php' );

		$this->_buttons = array(
			'remove' => apply_filters( 'gdpol_response_template_button_remove', '&times;' ),
			'down'   => apply_filters( 'gdpol_response_template_button_down', '&darr;' ),
			'up'     => apply_filters( 'gdpol_response_template_button_up', '&uarr;' ),
		);

		do_action( 'gdpol_after_setup_theme' );
	}

	public function add_caps_to_roles( $caps, $role ) {
		if ( in_array( $role, gdpol_settings()->get( 'global_user_roles' ) ) ) {
			$caps['gdpol_create_poll'] = true;
		} else {
			$caps['gdpol_create_poll'] = false;
		}

		return $caps;
	}

	public function get_button_text( $name ) {
		if ( isset( $this->_buttons[ $name ] ) ) {
			return $this->_buttons[ $name ];
		}

		return '&middot;';
	}

	public function register_objects() {
		do_action( 'gdpol_register_objects' );
	}

	public function plugin_init() {
		$this->_query = new Query();

		if ( ! is_admin() ) {
			$this->_bbpress = new Integrate();
		}

		do_action( 'gdpol_plugin_init' );
	}

	public function post_type_poll() {
		return apply_filters( 'gdpol_post_type_name_poll', 'gd-topic-poll' );
	}

	public function objects() : ?Registration {
		return $this->_objects;
	}

	public function bbpress() : ?Integrate {
		return $this->_bbpress;
	}

	public function query() : ?Query {
		return $this->_query;
	}

	public function poll() : ?Poll {
		return $this->_poll;
	}

	public function empty_poll() {
		$this->_poll = new Poll();
	}

	public function init_poll( $poll_id ) {
		$this->_poll = Poll::load( $poll_id );
	}

	public function set_poll( Poll $poll ) {
		$this->_poll = $poll;
	}

	public function b() {
		return null;
	}
}
