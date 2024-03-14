<?php
namespace Shop_Ready\extension\header_footer;

/**
 * Elementor Header Footer
 * @since 1.0
 * @author quomodosoft.com
 */

class HF_Helper {
		/**
		 * The singleton instance
		 */
		static private $instance = null;
		static private $product = null;

		/**
		 * No initialization allowed
		 */
		private function __construct() {
            
		}

		/**
		 * No cloning allowed
		 */
		private function __clone() {
		}

		static public function getInstance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

        /**
         * Get header Footer templates
         * 
         * @return bool  
         */
        public static function get_templates() {
         	
			static $list = [];
			if(empty($list)){

				$args = array(
					'post_type'           => 'woo-ready-hf-tpl',
					'orderby'             => 'id',
					'order'               => 'DESC',
					'posts_per_page'      => -1,
					'ignore_sticky_posts' => 1,
					
				);
			 
				$data = get_posts($args);
		
				$list['--'] = esc_html__( 'None', 'shopready-elementor-addon' );
				
				foreach($data as $item){
				   $list[$item->ID] = $item->post_title;
				}
			}
			
			return $list;
          
        }
 

    }       