<?php defined( 'ABSPATH' ) || exit;
/**
 * Creates a open tag 
 *
 * @package         iCopyDoc Plugins (ICPD)
 * @subpackage      
 * 
 * @version         0.1.0 (27-07-2023)
 * @author          Maxim Glazunov
 * @link            https://icopydoc.ru/
 * @see				
 *
 * @depends         classes:    Get_Closed_Tag
 *                  traits:     
 *                  methods:    
 *                  functions:  
 *                  constants:  
 *                  actions:    
 *                  filters:    
 * Usage example:
 *                  new Get_Open_Tag('offer', array('id' => 's-52'), true);
 */

if ( ! class_exists( 'Get_Open_Tag' ) ) {
	class Get_Open_Tag extends Get_Closed_Tag {
		/**
		 * Summary of attr_tag_arr
		 * @var 
		 */
		protected $attr_tag_arr;
		/**
		 * Closing slash or empty string
		 * 
		 * @var string
		 */
		protected $closing_slash = '';

		/**
		 * Initialize open tag
		 * 
		 * @param string $name_tag - Required - tag name
		 * @param array $attr_tag_arr - Optional - tag attributes array ['attr_name' => 'attr_val']
		 * @param bool $closing_slash_flag - Optional - add a closing slash?
		 */
		public function __construct( $name_tag, array $attr_tag_arr = [], $closing_slash_flag = false ) {
			parent::__construct( $name_tag );

			if ( ! empty( $attr_tag_arr ) ) {
				$this->attr_tag_arr = $attr_tag_arr;
			}

			if ( true === $closing_slash_flag ) {
				$this->closing_slash = '/';
			}
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
				return sprintf( "<%1\$s%2\$s%3\$s>",
					$this->get_name_tag(),
					$this->get_attr_tag(),
					$this->get_closing_slash()
				) . PHP_EOL;
			}
		}

		/**
		 * Get tag attributes
		 * 
		 * @return string
		 */
		public function get_attr_tag() {
			$res_string = '';
			if ( ! empty( $this->attr_tag_arr ) ) {
				foreach ( $this->attr_tag_arr as $key => $value ) {
					$res_string .= ' ' . $key . '="' . $value . '"';
				}
			}
			return $res_string;
		}

		/**
		 * Get closing slash
		 * 
		 * @return string
		 */
		private function get_closing_slash() {
			return $this->closing_slash;
		}
	}
}