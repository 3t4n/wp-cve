<?php
/**
 * Model Factory Class
 * @author KG
 * @package Forms
 */

if ( ! class_exists( 'FactoryModelWPE' ) ) {

	/**
	 * Model Factory Class
	 * @author KG
	 * @version 1.0.0
	 * @package Forms
	 */
	class FactoryModelWPE extends AbstractFactoryFlipperCode{
		/**
		 * FactoryModel constructer.
		 */
		public function __construct() {

		}
		/**
		 * Create model object by passing object type.
		 * @param  string $objectType Object Type.
		 * @return object         Return class object.
		 */
		public function create_object($objectType) {
			switch ( $objectType ) {

				default:
					require_once( WPE_Model.$objectType.'/model.'.$objectType.'.php' );
					$object = 'WPE_Model_'.$objectType;

				return new $object();
				break;
			}

		}

	}
}
