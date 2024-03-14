<?php defined( 'ABSPATH' ) || exit;
/**
 * Creates a closing tag 
 *
 * @package         iCopyDoc Plugins (ICPD)
 * @subpackage      
 * 
 * @version         0.1.0 (27-07-2023)
 * @author          Maxim Glazunov
 * @link            https://icopydoc.ru/
 * @see				
 *
 * @depends         classes:    
 *                  traits:     
 *                  methods:    
 *                  functions:  
 *                  constants:  
 *                  actions:    
 *                  filters:    
 * Usage example:
 *                  new Get_Closed_Tag('offers');
 */

if ( ! class_exists( 'Get_Closed_Tag' ) ) {
	class Get_Closed_Tag {
		/**
		 * Tag name
		 * @var 
		 */
		protected $name_tag;

		/**
		 * Initialize closed tag
		 * 
		 * @param string $name_tag - Required - tag name
		 */
		public function __construct( $name_tag ) {
			$this->name_tag = $name_tag;
		}

		/**
		 * Summary of __toString
		 * 
		 * @return string
		 */
		public function __toString() {
			if ( empty( $this->get_name_tag() ) ) {
				return '';
			} else {
				return sprintf( "</%1\$s>",
					$this->get_name_tag()
				) . PHP_EOL;
			}
		}

		/**
		 * Get tag name
		 * 
		 * @return string
		 */
		public function get_name_tag() {
			return $this->name_tag;
		}
	}
}