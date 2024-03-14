<?php
/**
 * Class Template Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */

namespace CTXFeed\V5\Template;

use CTXFeed\V5\Override\Heureka_skTemplate;
use CTXFeed\V5\Override\Zbozi_czTemplate;
use CTXFeed\V5\Structure\CustomStructure;

/**
 * Class Template Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */
class TemplateFactory {

	/**
	 * @param array                      $ids    Product Ids.
	 * @param \CTXFeed\V5\Utility\Config $config Feed Config.
     * @return \CTXFeed\V5\Template\Template
	 */
	public static function make_feed( $ids, $config ) {
		// TODO: Remove this condition when class "OverrideFactory" initialized.
		if( 'heureka.sk' === $config->get_feed_template() ) {
			new Heureka_skTemplate();
		}

		if( 'zbozi.cz' === $config->get_feed_template() ) {
			new Zbozi_czTemplate();
		}

		$group_class = self::get_grouped_templates( $config->provider );
		$structure   = self::get_structure( $config, $ids );


		if ( $group_class ) {
			$class = '\CTXFeed\V5\Template\\' . ucfirst( $group_class ) . 'Template';
		} else {
			$class = '\CTXFeed\V5\Template\\' . ucfirst( $config->get_feed_template() ) . 'Template';
		}

		if ( class_exists( $class ) ) {
			return new Template( new $class( $ids, $config, $structure ) );
		}

		return new Template( new CustomTemplate( $ids, $config, $structure ) );
	}

	/**
	 * Get Feed Structure.
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed Config.
	 * @param array                      $ids    Product Ids.
     * @return mixed
	 */

	public static function get_structure( $config, $ids = array() ) {
		$template = $config->provider;

		if ( false !== ( $value = get_transient( 'ctx_feed_structure_transient' ) ) && 'Googlereview' !== $template  ) {
			return $value;
		}

		$class = self::get_grouped_templates( $config->provider );

		if ( $class ) {
			$template = $class;
		}

		$template = ucfirst( str_replace( array( '_', '.' ), '', $template ) );
		$class    = '\CTXFeed\V5\Structure\\' . $template . 'Structure';

		$method    = 'get_' . $config->feedType . '_structure';

		if ( 'Googlereview' === $template && class_exists( $class ) && method_exists( $class, $method ) ) {
			return ( new $class( $config, $ids ) )->$method();
		}

		if ( class_exists( $class ) && method_exists( $class, $method ) ) {
			return ( new $class( $config ) )->$method();
		}

		$structure = (new CustomStructure($config))->$method();

		set_transient('ctx_feed_structure_transient', $structure, HOUR_IN_SECONDS);

		return $structure;
	}

	/**
	 * Get Grouped Templates.
	 *
	 * @param string $provider Feed Template.
     * @return false|string
	 */
	public static function get_grouped_templates( $provider ) {
		$group_classes = array(
			'google'    => array( 'google_shopping_action', 'google_local', 'google_local_inventory' ),
			'custom2'   => array( 'custom2', 'admarkt', 'yandex_xml', 'glami' ),
			'pinterest' => array( 'pinterest', 'pinterest_rss' ),
		);

		foreach ( $group_classes as $class => $providers ) {
			if ( in_array( $provider, $providers, true ) ) {
				return $class;
			}
		}

		return false;
	}

}
