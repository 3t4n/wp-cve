<?php

if ( ! class_exists( 'KcSeoWPSchema' ) ) {

	class KcSeoWPSchema {
		/*
		 * Options
		 */
		public $options;
		/**
		 * @var string
		 */
		public $KcSeoPrefix;
		/**
		 * @var string
		 */
		public $incPath;
		/**
		 * @var string
		 */
		public $functionsPath ;

		/**
		 * @var string
		 */
		public $classesPath  ;
		/**
		 * @var string
		 */
		public $viewsPath;
		/**
		 * @var string
		 */
		public $assetsUrl;

		/**
		 * @var string
		 */
		public $modelPath;

		/**
		 * @var string
		 */
		public $objects;

		/**
		 * Store the singleton object.
		 */
		private static $singleton = false;

		/**
		 * Fetch an instance of the class.
		 */
		public static function getInstance() {
			if ( self::$singleton === false ) {
				self::$singleton = new self();
			}

			return self::$singleton;
		}

		function __construct() {
			$this->KcSeoPrefix = "_schema_";
			$this->options     = array(
				'main_settings'     => 'kcseo_wp_schema_settings',
				'settings'          => 'kcseo_wp_schema',
				'installed_version' => 'kcseo_wp_installed_version',
				'version'           => KCSEO_WP_SCHEMA_VERSION,
				'1_2_fix'           => "kcseo_wp_1_2_data_fix"
			);

			$this->incPath       = dirname( __FILE__ );
			$this->functionsPath = $this->incPath . '/functions/';
			$this->classesPath   = $this->incPath . '/classes/';
			$this->viewsPath     = $this->incPath . '/views/';
			$this->assetsUrl     = KCSEO_WP_SCHEMA_URL . '/assets/';
			$this->modelPath     = $this->incPath . '/models/';

			$this->KeSeoLoadFunctions( $this->functionsPath );
			$this->KcSeoLoadModel( $this->modelPath );
			$this->KcSeoLoadClass( $this->classesPath );

		}

		function KcSeoLoadClass( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}
			$classes = array();
			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
					$className = str_replace( ".php", "", $item );
					$classes[] = new $className;
				}
			}

			if ( $classes ) {
				foreach ( $classes as $class ) {
					$this->objects[] = $class;
				}
			}
		}

		function KcSeoLoadModel( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}
			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
				}
			}
		}

		function KeSeoLoadFunctions( $dir ) {
			if ( ! file_exists( $dir ) ) {
				return;
			}
			foreach ( scandir( $dir ) as $item ) {
				if ( preg_match( "/.php$/i", $item ) ) {
					require_once( $dir . $item );
				}
			}
		}

		function render( $viewName, $args = array() ) {
			global $KcSeoWPSchema;
			$path     = str_replace( ".", "/", $viewName );
			$viewPath = $KcSeoWPSchema->viewsPath . $path . '.php';
			if ( ! file_exists( $viewPath ) ) {
				return;
			}

			if ( $args ) {
				extract( $args );
			}
			$pageReturn = include $viewPath;
			if ( $pageReturn AND $pageReturn <> 1 ) {
				return $pageReturn;
			}
		}

		/**
		 * Dynamicaly call any  method from models class
		 * by pluginFramework instance
		 */
		function __call( $name, $args ) {
			if ( ! is_array( $this->objects ) ) {
				return;
			}
			foreach ( $this->objects as $object ) {
				if ( method_exists( $object, $name ) ) {
					$count = count( $args );
					if ( $count == 0 ) {
						return $object->$name();
					} elseif ( $count == 1 ) {
						return $object->$name( $args[0] );
					} elseif ( $count == 2 ) {
						return $object->$name( $args[0], $args[1] );
					} elseif ( $count == 3 ) {
						return $object->$name( $args[0], $args[1], $args[2] );
					} elseif ( $count == 4 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3] );
					} elseif ( $count == 5 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4] );
					} elseif ( $count == 6 ) {
						return $object->$name( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );
					}
				}
			}
		}
	}

	global $KcSeoWPSchema;
	if ( ! is_object( $KcSeoWPSchema ) ) {
		$KcSeoWPSchema = kcseo();
	}
}

function kcseo() {
	return KcseoWPSchema::getInstance();
}
