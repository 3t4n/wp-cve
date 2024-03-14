<?php

namespace CTXFeed\V5\Structure;

/**
 * Class StructureFactory
 *
 * The StructureFactory class is responsible for creating instances of Structure based on a given feed template.
 * It dynamically determines the appropriate structure class to instantiate based on the template provided in the configuration.
 *
 * @package CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */

class StructureFactory {

	/**
	 * Get Structure Instance
	 *
	 * This method creates an instance of the Structure class based on the provided configuration.
	 * It dynamically determines the class to instantiate based on the feed template from the configuration.
	 *
	 * @param Config $config The configuration object containing the feed template information.
	 *
	 * @return Structure An instance of the Structure class.
	 */
	public static function get( $config ) {
		$template = $config->get_feed_template();
		$class    = "\CTXFeed\V5\Structure\\" . \ucfirst( $template ) . "Structure";

		// Check if the class exists, if not, fallback to a default CustomStructure.
		if ( \class_exists( $class ) ) {
			return new Structure(new $class($config));
		}

		return new Structure(new CustomStructure($config));
	}
}
