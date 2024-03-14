<?php
/**
 * HootKit Config
 *
 * @package Hootkit
 */

namespace HootKit\Inc;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\HootKit\Inc\Helper_Config' ) ) :

	class Helper_Config {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Config
		 */
		public static $config = null;

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( null === self::$config ) {
				self::$config = self::defaults();
				add_action( 'after_setup_theme', array( $this, 'default_presets' ), 5 ); // Uses hootkit()->get_string : If used directly in self::defaults(), it would have lead to looping (since this file is called during HootKit Constructor method) making the constructor run additional number of times => Hence add presets to self::config by hooking to 'after_setup_themes'
			}

			add_action( 'after_setup_theme', array( $this, 'themeregister' ), 90 );
			add_action( 'after_setup_theme', array( $this, 'setactivemodules' ), 93 );

		}

		/**
		 * Register theme config
		 */
		public function themeregister() {

			$themeconfig = apply_filters( 'hootkit_register', array() );
			$themeconfig = $this->maybe_restructure( $themeconfig );
			self::$config = wp_parse_args( $themeconfig, self::$config );
			$this->sanitizeconfig();

		}

		/**
		 * Restructure config array from theme if needed
		 */
		private function maybe_restructure( $themeconfig ) {
			if ( !empty( $themeconfig['modules'] ) ) {
				if ( !\is_array( $themeconfig['modules'] ) ) {
					unset( $themeconfig['modules'] );
				} else {
					// 1. Rename slider slugs
					if ( !empty( $themeconfig['modules']['sliders'] ) ) {
						foreach ( $themeconfig['modules']['sliders'] as $slkey => $name ) {
							if ( \in_array( $name, array( 'image', 'postimage' ) ) )
								$themeconfig['modules']['sliders'][$slkey] = 'slider-' . $name;
						}
					}
					// 2. Restructure to new format
					$modules = wp_parse_args( $themeconfig['modules'], array(
						'widget' => array(),
						'block'  => array(),
						'misc'   => array(),
						) );
					if ( !empty( $themeconfig['modules']['widgets'] ) )
						$modules['widget'] = array_merge( $modules['widget'], $themeconfig['modules']['widgets'] );
					if ( !empty( $themeconfig['modules']['sliders'] ) )
						$modules['widget'] = array_merge( $modules['widget'], $themeconfig['modules']['sliders'] );
					if ( !empty( $themeconfig['modules']['woocommerce'] ) )
						$modules['widget'] = array_merge( $modules['widget'], $themeconfig['modules']['woocommerce'] );
					$themeconfig['modules'] = array(
						'widget' => $modules['widget'],
						'block'  => $modules['block'],
						'misc'   => $modules['misc'],
						);
				}
			}
			return $themeconfig;
		}

		/**
		 * Sanitize config values from theme and/or the default config values
		 */
		public function sanitizeconfig() {

			/* Sanitize Theme Supported Modules against HootKit modules and arrange in order of hootkitmods */
			/* Dont add woocommerce modules if plugin is inactive */
			$wc = class_exists( 'WooCommerce' );
			self::$config['wc-inactive'] = array(
				'widget' => array(),
				'block' => array(),
				'misc' => array(),
			);
			if ( !empty( self::$config['modules'] ) && \is_array( self::$config['modules'] ) ) {
				foreach ( self::$config['modules'] as $type => $modules ) {
					if ( !empty( self::$config['modules'][ $type ] ) ) {
						$store = array();
						$hkmodules = hootkit()->get_modtype( $type );
						foreach ( $hkmodules as $modname => $modatts ) {
							if ( \in_array( $modname, self::$config['modules'][ $type ] ) ) {
								if ( isset( $modatts['requires'] ) && \in_array( 'woocommerce', $modatts['requires'] ) ) {
									if ( $wc ) { $store[] = $modname; }
									else { self::$config['wc-inactive'][ $type ][] = $modname; }
								} else {
									$store[] = $modname;
								}
							}
						}
						self::$config['modules'][ $type ] = $store;
					}
				}
			} else {
				self::$config['modules'] = array(
					'widget' => array(),
					'block' => array(),
					'misc' => array(),
				);
			}

			$oldhoot = ( class_exists( 'Hoot_Theme' ) || class_exists( 'Hootubix_Theme' ) || class_exists( 'Maghoot_Theme' ) || class_exists( 'Dollah_Theme' ) );
			if (
				/* Sanitize and remove modules for older hoot themes */
				( $oldhoot && !apply_filters( 'hootkit_forceload_deprecated', false ) )
				||
				/* Sanitize and remove modules for non hoot themes */
				( self::$config['nohoot'] && !apply_filters( 'hootkit_forceload_nohoot', true ) )
			)
				self::$config['modules'] = ( !empty( self::$config['modules']['block'] ) ) ? array(
					'widget' => array(),
					'block' => self::$config['modules']['block'],
					'misc' => array(),
					) : array();

			/* Sanitize Theme Supported Premium Modules against HootKit modules */
			$themeslug = ( function_exists( 'hoot_data' ) ) ? strtolower( preg_replace( '/[^a-zA-Z0-9]+/', '-', trim( hoot_data( 'template_name' ) ) ) ) : '';
			if ( !empty( $themeslug ) && \in_array( $themeslug, self::$config['themelist'] ) ) {
				if ( !empty( self::$config['premium'] ) && \is_array( self::$config['premium'] ) ) {
					$hkmodules = hootkit()->get_mods( 'modules' );
					foreach ( self::$config['premium'] as $modkey => $modname ) {
						if ( !array_key_exists( $modname, $hkmodules ) )
							unset( self::$config['premium'][$modkey] );
					}
				}
			} else {
				self::$config['premium'] = array();
			}

			/* Sanitize Theme specific supported settings against HootKit supported settings */
			if ( !empty( self::$config['supports'] ) && \is_array( self::$config['supports'] ) ) {
				$hksupports = hootkit()->get_mods( 'supports' );
				foreach ( self::$config['supports'] as $skey => $support ) {
					if ( !in_array( $support, $hksupports ) )
						unset( self::$config['supports'][ $skey ] );
				}
			}

		}

		/**
		 * Set User Activated modules
		 *
		 * @since  1.1.0
		 */
		public function setactivemodules() {

			$dbvalue = get_option( 'hootkit-activemods', false );
			$store = array(
				'widget' => array(),
				'block' => array(),
				'misc' => array(),
			);
			$disabled = ( \is_array( $dbvalue ) && !empty( $dbvalue[ 'disabled' ] ) && \is_array( $dbvalue[ 'disabled' ] ) ) ? $dbvalue[ 'disabled' ] : array();

			foreach ( array( 'widget', 'block', 'misc' ) as $type ) {

				// User has modified any default settings yet
				if ( $dbvalue !== false ) {
					if ( \in_array( $type, $disabled ) ) {
						$store[ $type ] = array();
					} elseif ( !empty( $dbvalue[ $type ] ) && \is_array( $dbvalue[ $type ] ) ) {
						// Default: $dbvalue stores only active mods as serialize() does with checkboxes
						// Issue:   User saves settings => new mods available (new or premium) => new mods inactive
						//          Hence, $dbvalue should store both 1 and 0 statuses.
						//          New available mods will be !isset in $dbvalue[$type]
						//          However user deactivated are isset in $dbvalue[$type], but they are empty i.e. 0
						foreach ( hootkit()->get_config( 'modules', $type ) as $check ) {
							if ( !isset( $dbvalue[ $type ][ $check ] ) || $dbvalue[ $type ][ $check ] == 'yes' )
								$store[ $type ][] = $check;
						}
					} else {
					// This condition should never occur! as long as 'hootkit-activemods' has been properly stored in db
					// i.e. we always have $dbvalue[$type] with disabled mods set to 'no' (so $dbvalue[$type] is not empty even when all disabled)
						$store[ $type ] = hootkit()->get_config( 'modules', $type );
					}
				}
				
				// User hasn't modified any default settings yet
				// Hence set default active modules => all deactive if (bool) false ; all active if empty
				else {
					if ( self::$config['activemods'] === false ||
						 ( isset( self::$config['activemods'][ $type ] ) && self::$config['activemods'][ $type ] === false )
						)
						$store[ $type ] = array();
					elseif ( !empty( self::$config['activemods'][ $type ] ) && \is_array( self::$config['activemods'][ $type ] ) )
						$store[ $type ] = self::$config['activemods'][$type];
					else
						$store[ $type ] = hootkit()->get_config( 'modules', $type );
				}

			}

			self::$config['activemods'] = apply_filters( 'hootkit_active_modules', $store );

			/* Sanitize Active Modules against HootKit modules and arrange in order of hootkitmods */
			/* Dont add woocommerce modules if plugin is inactive (Case: User sets settings with WC active; later disabled WC) */
			$store = array();
			$wc = class_exists( 'WooCommerce' );
			foreach ( array( 'widget', 'block', 'misc' ) as $type ) {
				$store[ $type ] = array();
				if ( !empty( self::$config['activemods'][ $type ] ) ) {
					$hkmodules = hootkit()->get_modtype( $type );
					foreach ( $hkmodules as $modname => $modatts ) {
						if ( \in_array( $modname, self::$config['activemods'][ $type ] ) ) {
							if ( isset( $modatts['requires'] ) && \in_array( 'woocommerce', $modatts['requires'] ) ) {
								if ( $wc ) { $store[ $type ][] = $modname; }
							} else {
								$store[ $type ][] = $modname;
							}
						}
					}
				}
			}

			self::$config['activemods'] = $store;
			self::$config['disabledmodtypes'] = $disabled;

		}

		/**
		 * Config Structure (Defaults)
		 */
		public static function defaults() {
			return array(
				// Set true for all non wphoot themes
				'nohoot'    => true,
				// If theme is loading its own css, hootkit wont load its own default styles
				'theme_css' => false,
				// Theme Supported Modules
				// @todo 'ticker' width bug: css width percentage does not work inside table/flex layout => theme should remove support if theme markup does not explicitly support this (i.e. max-width provided for ticker boxes inside table cells)
				'modules'   => array(
					'widget' => array( 'slider-image', 'slider-postimage', 'announce', 'content-blocks', 'content-posts-blocks', 'cta', 'icon', 'post-grid', 'post-list', 'social-icons', /*'ticker',*/ 'content-grid', 'cover-image' ),
					'block' => array(),
					'misc' => array(),
				),
				// Extracted list from 'modules' if WC is inactive ; else NULL
				'wc-inactive' => array(
					'widget' => array(),
					'block' => array(),
					'misc' => array(),
				),
				// Premium modules list
				'premium' => array(),
				// Active Modules (user settings)
				// Optional: Themes can pass an array here to set them as defaults (before user settings saved)
				//           Set to empty or boolean true for all active by default, boolean false for all deactive by default
				'activemods' => array(
					'widget' => array(),
					'block' => array(),
					'misc' => array(),
				),
				// Misc theme specific settings
				// JNES@deprecated <= Unos v2.7.1 @12.18
				'settings' => array(),
				// Misc theme specific settings
				'supports' => array(),
				// wpHoot Themes
				'themelist' => array(
					'chromatic',		'dispatch',			'responsive-brix',
					'brigsby',			'creattica',
					'metrolo',			'juxter',			'divogue',
					'hoot-ubix',		'magazine-hoot',	'dollah',
					'hoot-business',	'hoot-du',
					'unos',				'unos-publisher',	'unos-magazine-vu',
					'unos-business',	'unos-glow',		'unos-magazine-black',
					'unos-store-bell',	'unos-minima-store','unos-news',	'unos-bizdeck',
					'nevark',			'neux',				'magazine-news-byte',
					'hoot-porto',
				),
				// Default Styles
				'presets'   => array(),
				// Default Styles
				'presetcombo'   => array(),
			);
		}

		/**
		 * Config Structure (Defaults)
		 * >> after hootkit() is available (constructor executed)
		 */
		public static function default_presets() {
			self::$config['presets'] = array(
				'white'  => hootkit()->get_string('white'),
				'black'  => hootkit()->get_string('black'),
				'brown'  => hootkit()->get_string('brown'),
				'blue'   => hootkit()->get_string('blue'),
				'cyan'   => hootkit()->get_string('cyan'),
				'green'  => hootkit()->get_string('green'),
				'yellow' => hootkit()->get_string('yellow'),
				'amber'  => hootkit()->get_string('amber'),
				'orange' => hootkit()->get_string('orange'),
				'red'    => hootkit()->get_string('red'),
				'pink'   => hootkit()->get_string('pink'),
			);
			self::$config['presetcombo'] = array(
				'white'        => hootkit()->get_string('white'),
				'black'        => hootkit()->get_string('black'),
				'brown'        => hootkit()->get_string('brown'),
				'brownbright'  => hootkit()->get_string('brownbright'),
				'blue'         => hootkit()->get_string('blue'),
				'bluebright'   => hootkit()->get_string('bluebright'),
				'cyan'         => hootkit()->get_string('cyan'),
				'cyanbright'   => hootkit()->get_string('cyanbright'),
				'green'        => hootkit()->get_string('green'),
				'greenbright'  => hootkit()->get_string('greenbright'),
				'yellow'       => hootkit()->get_string('yellow'),
				'yellowbright' => hootkit()->get_string('yellowbright'),
				'amber'        => hootkit()->get_string('amber'),
				'amberbright'  => hootkit()->get_string('amberbright'),
				'orange'       => hootkit()->get_string('orange'),
				'orangebright' => hootkit()->get_string('orangebright'),
				'red'          => hootkit()->get_string('red'),
				'redbright'    => hootkit()->get_string('redbright'),
				'pink'         => hootkit()->get_string('pink'),
				'pinkbright'   => hootkit()->get_string('pinkbright'),
			);
		}

		/**
		 * Returns the instance
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}

	Helper_Config::get_instance();

endif;