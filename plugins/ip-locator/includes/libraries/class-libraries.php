<?php
/**
 * Libraries handling
 *
 * Handles all libraries (vendor) operations and versioning.
 *
 * @package Libraries
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   1.0.0
 */

namespace IPLocator\Library;

use IPLocator\System\L10n;

/**
 * Define the libraries functionality.
 *
 * Handles all libraries (vendor) operations and versioning.
 *
 * @package System
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   1.0.0
 */
class Libraries {

	/**
	 * The array of PSR-4 libraries used by the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array    $libraries    The PSR-4 libraries used by the plugin.
	 */
	private static $psr4_libraries;

	/**
	 * The array of mono libraries used by the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array    $libraries    The mono libraries used by the plugin.
	 */
	private static $mono_libraries;

	/**
	 * Initializes the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		self::init();
	}

	/**
	 * Defines all needed libraries.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::$psr4_libraries                = [];
		self::$psr4_libraries['flagiconcss'] = [
			'name'    => 'Flag-Icon-CSS',
			'prefix'  => 'Flagiconcss',
			'base'    => IPLOCATOR_VENDOR_DIR . 'flagiconcss/',
			'version' => '3.4.6',
			// phpcs:ignore
			'author'  => sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Panayiotis Lipiridis' ),
			'url'     => 'https://github.com/lipis/flag-icon-css',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['feather']     = [
			'name'    => 'Feather',
			'prefix'  => 'Feather',
			'base'    => IPLOCATOR_VENDOR_DIR . 'feather/',
			'version' => '4.24.1',
			// phpcs:ignore
			'author'  => sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Cole Bemis' ),
			'url'     => 'https://feathericons.com',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['markdown']    = [
			'name'    => 'Markdown Parser',
			'prefix'  => 'cebe\markdownparser',
			'base'    => IPLOCATOR_VENDOR_DIR . 'markdown/',
			'version' => '1.2.1',
			// phpcs:ignore
			'author'  => sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Carsten Brandt' ),
			'url'     => 'https://github.com/cebe/markdown',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['psr-03']      = [
			'name'    => 'PSR-3',
			'prefix'  => 'Psr\\Log',
			'base'    => IPLOCATOR_VENDOR_DIR . 'psr/log/',
			'version' => '',
			'author'  => 'PHP Framework Interop Group',
			'url'     => 'https://www.php-fig.org/',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['psr-07']      = [
			'name'    => 'PSR-7',
			'prefix'  => 'Psr\\Http\\Message',
			'base'    => IPLOCATOR_VENDOR_DIR . 'psr/http-message/',
			'version' => '',
			'author'  => 'PHP Framework Interop Group',
			'url'     => 'https://www.php-fig.org/',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['psr-18']      = [
			'name'    => 'PSR-18',
			'prefix'  => 'Psr\\Http\\Client',
			'base'    => IPLOCATOR_VENDOR_DIR . 'psr/http-client/',
			'version' => '',
			'author'  => 'PHP Framework Interop Group',
			'url'     => 'https://www.php-fig.org/',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$psr4_libraries['decalog-sdk'] = [
			'name'    => 'DecaLog SDK',
			'prefix'  => 'DecaLog',
			'base'    => IPLOCATOR_VENDOR_DIR . 'decalog-sdk/',
			'version' => '3.0.0',
			'author'  => 'Pierre Lannoy',
			'url'     => 'https://github.com/Pierre-Lannoy/wp-decalog-sdk',
			'license' => 'mit',
			'langs'   => 'en',
		];
		self::$mono_libraries                = [];
	}

	/**
	 * Get PSR-4 libraries.
	 *
	 * @return  array   The list of defined PSR-4 libraries.
	 * @since 1.0.0
	 */
	public static function get_psr4() {
		return self::$psr4_libraries;
	}

	/**
	 * Get mono libraries.
	 *
	 * @return  array   The list of defined mono libraries.
	 * @since 1.0.0
	 */
	public static function get_mono() {
		return self::$mono_libraries;
	}

	/**
	 * Get the full license name.
	 *
	 * @param  string $license     The license id.
	 * @return  string     The full license name.
	 * @since 1.0.0
	 */
	private function license_name( $license ) {
		switch ( $license ) {
			case 'mit':
				$result = esc_html__( 'MIT license', 'ip-locator' );
				break;
			case 'apl2':
				$result = esc_html__( 'Apache license, version 2.0', 'ip-locator' );
				break;
			case 'gpl2':
				$result = esc_html__( 'GPL-2.0 license', 'ip-locator' );
				break;
			case 'gpl3':
				$result = esc_html__( 'GPL-3.0 license', 'ip-locator' );
				break;
			default:
				$result = esc_html__( 'unknown license', 'ip-locator' );
				break;
		}
		return $result;
	}

	/**
	 * Get the libraries list.
	 *
	 * @param   array $attributes  'style' => 'html'.
	 * @return  string  The output of the shortcode, ready to print.
	 * @since 1.0.0
	 */
	public function sc_get_list( $attributes ) {
		$_attributes = shortcode_atts(
			[
				'style' => 'html',
			],
			$attributes
		);
		$style       = $_attributes['style'];
		$result      = '';
		$list        = [];
		foreach ( array_merge( self::get_psr4(), self::get_mono() ) as $library ) {
			$item            = [];
			$item['name']    = $library['name'];
			$item['version'] = $library['version'];
			$item['author']  = $library['author'];
			$item['url']     = $library['url'];
			$item['license'] = $this->license_name( $library['license'] );
			$item['langs']   = L10n::get_language_markup( explode( ',', $library['langs'] ) );
			$list[]          = $item;
		}
		$item            = [];
		$item['name']    = 'Plugin Boilerplate';
		$item['version'] = '1.0.0';
		$item['author']  = 'Pierre Lannoy';
		$item['url']     = 'https://github.com/Pierre-Lannoy/wp-' . 'plugin-' . 'boilerplate';
		$item['license'] = $this->license_name( 'gpl3' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item            = [];
		$item['name']    = 'Action Scheduler';
		$item['version'] = '3.5.2';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Automattic' );
		$item['url']     = 'https://actionscheduler.org';
		$item['license'] = $this->license_name( 'gpl3' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item            = [];
		$item['name']    = 'Date Range Picker';
		$item['version'] = '3.0.5';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Dan Grossman' );
		$item['url']     = 'https://github.com/dangrossman/daterangepicker';
		$item['license'] = $this->license_name( 'mit' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item            = [];
		$item['name']    = 'Moment';
		$item['version'] = '2.29.4';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Tim Wood' );
		$item['url']     = 'https://github.com/moment/moment';
		$item['license'] = $this->license_name( 'mit' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item['name']    = 'SVG-Loaders';
		$item['version'] = '1.0.2';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Sam Herbert' );
		$item['url']     = 'https://github.com/SamHerbert/SVG-Loaders';
		$item['license'] = $this->license_name( 'mit' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item['name']    = 'Chartist-JS';
		$item['version'] = '0.11.4';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Gion Kunz' );
		$item['url']     = 'https://github.com/gionkunz/chartist-js';
		$item['license'] = $this->license_name( 'mit' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		$item['name']    = 'jVectorMap';
		$item['version'] = '2.0.3';
		$item['author']  = sprintf( esc_html__( '%s & contributors', 'ip-locator' ), 'Kirill Lebedev' );
		$item['url']     = 'https://github.com/bjornd/jvectormap';
		$item['license'] = $this->license_name( 'gpl3' );
		$item['langs']   = L10n::get_language_markup( [ 'en' ] );
		$list[]          = $item;
		usort(
			$list,
			function ( $a, $b ) {
				return strcmp( strtolower( $a['name'] ), strtolower( $b['name'] ) );
			}
		);
		if ( 'html' === $style ) {
			$items = [];
			foreach ( $list as $library ) {
				/* translators: as in the sentence "Product W version X by author Y (license Z)" */
				$items[] = sprintf( __( '<a href="%1$s">%2$s %3$s</a>%4$s by %5$s (%6$s)', 'ip-locator' ), $library['url'], $library['name'], $library['version'], $library['langs'], $library['author'], $library['license'] );
			}
			$result = implode( ', ', $items );
		}
		return $result;
	}

}

Libraries::init();
