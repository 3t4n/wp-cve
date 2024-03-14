<?php
/**
 * Factory Classes
 * @author KG
 * @package Forms
 */

if ( ! class_exists( 'AbstractFactoryFlipperCode' ) ) {

	/**
	 * Factory Class Abstract
	 * @author KG
	 * @version 1.0.0
	 * @package Forms
	 */
	abstract class AbstractFactoryFlipperCode {
		/**
		 * Abstrct create object
		 * @param  string $object Object Type.
		 * @return object         Return class object.
		 */
		abstract public function create_object($object);
	}
}
