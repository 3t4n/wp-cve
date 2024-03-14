<?php defined( 'ABSPATH' ) || exit;
/**
 * Creates a paired tag 
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
 *                  new Get_Paired_Tag('price', 1500, array('from' => 'true'));
 */

if ( ! class_exists( 'Get_Paired_Tag' ) ) {
	class Get_Paired_Tag extends Get_Closed_Tag {
		/**
		 * Tag value
		 * @var 
		 */
		protected $val_tag;
		/**
		 * Summary of attr_tag_arr
		 * @var 
		 */
		protected $attr_tag_arr;

		/**
		 * Initialize paired tag
		 * 
		 * @param string $name_tag - Required - tag name
		 * @param mixed $val_tag - Optional - tag value
		 * @param array $attr_tag_arr - Optional - tag attributes array ['attr_name' => 'attr_val']
		 */
		public function __construct( $name_tag, $val_tag = '', array $attr_tag_arr = [] ) {
			parent::__construct( $name_tag );

			if ( ! empty( $val_tag ) ) {
				$this->val_tag = $val_tag;
			} else if ( $val_tag === (float) 0 || $val_tag === (int) 0 ) {
				// если нужно передать нулевое значение в качестве value
				$this->val_tag = $val_tag;
			}

			if ( ! empty( $attr_tag_arr ) ) {
				$this->attr_tag_arr = $attr_tag_arr;
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
				return sprintf( "<%1\$s%3\$s>%2\$s</%1\$s>",
					$this->get_name_tag(),
					$this->get_val_tag(),
					$this->get_attr_tag()
				) . PHP_EOL;
			}
		}

		/**
		 * Get tag value
		 * 
		 * @return string
		 */
		public function get_val_tag() {
			return $this->val_tag;
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
	}
}