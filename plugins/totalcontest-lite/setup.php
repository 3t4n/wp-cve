<?php

if ( ! function_exists( 'TotalContest' ) ):
	/**
	 * TotalContest global function.
	 *
	 * @param null  $component
	 * @param array $args
	 *
	 * @return \TotalContestVendors\TotalCore\Application|mixed
	 */
	function TotalContest( $component = null, $args = array() ) {
		$instance = $GLOBALS['TotalContestApplication'];

		if ( $component !== null ):
			$instance = $instance->container( $component, $args );
		endif;

		return $instance;
	}
endif;

if ( ! class_exists( 'TotalContestSetup' ) ):
	/**
	 * Class TotalContestSetup
	 */
	class TotalContestSetup {
		protected $environment = array();
		protected $loader;
		protected static $loaded = false;

		/**
		 * TotalContestSetup constructor.
		 *
		 * @param $env
		 */
		public function __construct( $env ) {
			$this->environment = $env;

			$phpCompatible = version_compare( $this->environment['versions']['php'], $this->environment['requirements']['php'], '>=' );
			$wpCompatible  = version_compare( $this->environment['versions']['wp'], $this->environment['requirements']['wp'], '>=' );
			// Check version requirements
			if ( $phpCompatible && $wpCompatible ):
				$this->load();
			else:
				$this->disable();
			endif;
		}

		/**
		 * Load autoloader and bootstrap plugin.
		 */
		public function load() {
			if ( self::$loaded ):
				return;
			endif;

			$this->loader = require $this->environment['autoload']['loader'];
			foreach ( $this->environment['autoload']['psr4'] as $namespace => $paths ):
				$this->loader->addPsr4( $namespace, $paths );
			endforeach;

			// Quick workaround for php 5.2 syntax error
			$application = "{$this->environment['namespace']}Vendors\\TotalCore\\Application";

			// Initiate
			$GLOBALS["{$this->environment['namespace']}Application"] = new $application( $this->environment );

			// Let's bootstrap plugin (Quirky code to avoid php 5.2 syntax error)
			$plugin = "\\{$this->environment['namespace']}\\Plugin";
			$GLOBALS["{$this->environment['namespace']}Application"]->bootstrap( new $plugin() );

			self::$loaded = true;
		}

		/**
		 * Disable the plugin.
		 */
		public function disable() {
			// Attach to appropriate hooks.
			add_action( 'admin_init', array( $this, '_deactivate' ) );
			add_action( 'admin_notices', array( $this, '_warning' ) );
		}

		// Self deactivation (to prevent any unwanted behaviors).
		public function _deactivate() {
			deactivate_plugins( $this->environment['basename'] );
		}

		// Warning message.
		public function _warning() {
			$message = sprintf(
				__( '%1$s requires PHP %2$s+ and WordPress %3$s+ to function properly. Please contact your host to upgrade PHP and WordPress. %1$s has been auto-deactivated.', $this->environment['textdomain'] ),
				$this->environment['name'],
				$this->environment['requirements']['php'],
				$this->environment['requirements']['wp']
			);
			printf( '<div class="error"><p>%s</p></div>', $message );
		}
	}
endif;