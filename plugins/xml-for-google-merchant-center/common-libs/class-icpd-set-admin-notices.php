<?php
/**
 * Creates a paired tag 
 *
 * @package                 iCopyDoc Plugins (ICPD)
 * @subpackage              
 * 
 * @version                 0.1.0 (29-08-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see				        
 *
 * @depends                 classes:    
 *                          traits:     
 *                          methods:    
 *                          functions:  
 *                          constants:  
 *                          actions:    
 *                          filters:    
 * Usage example:
 *                          new ICPD_Set_Admin_Notices('Logs were cleared', 'notice-success is-dismissible');
 */
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'ICPD_Set_Admin_Notices' ) ) {
	class ICPD_Set_Admin_Notices {
		/**
		 * The notice message value
		 * @var 
		 */
		protected $message;
		/**
		 * The notice message class
		 * @var 
		 */
		protected $class;

		/**
		 * Initialize notice
		 * 
		 * @param string $message - Required - notice message value
		 * @param string $class - Optional - notice message class. Default value: 'notice-info'
		 */
		public function __construct( $message, $class = 'notice-info' ) {
			$this->message = $message;
			$this->class = $class;
			$this->init_classes();
			$this->init_hooks();
		}

		/**
		 * Summary of __toString
		 * 
		 * @return string
		 */
		public function __toString() {
			if ( empty( $this->get_message() ) ) {
				return '';
			} else {
				return sprintf( '<div class="notice %1$s"><p>%2$s</p></div>',
					$this->get_message(),
					$this->get_class()
				) . PHP_EOL;
			}
		}

		/**
		 * Init classes
		 * 
		 * @return void
		 */
		public function init_classes() {
			return;
		}

		/**
		 * Init hooks
		 * 
		 * @return void
		 */
		public function init_hooks() {
			// наш класс, вероятно, вызывается не раньше срабатывания хука admin_menu.
			// admin_init - следующий в очереди срабатывания, на хуки раньше admin_menu нет смысла вешать
			// add_action('admin_init', [ $this, 'my_func' ], 10, 1);
			$message = $this->get_message();
			$class = $this->get_class();
			add_action( 'admin_notices', function () use ($message, $class) {
				$this->print_admin_notices( $message, $class );
			}, 10, 2 );

			return;
		}

		/**
		 * Print admin notice
		 * 
		 * @param string $message - Required - notice message value
		 * @param string $class - Required - notice message class
		 * 
		 * @return void
		 */
		private function print_admin_notices( $message, $class ) {
			printf( '<div class="notice %1$s"><p>%2$s</p></div>', $class, $message );
		}

		/**
		 * Get notice message value
		 * @return string
		 */
		public function get_message() {
			return $this->message;
		}

		/**
		 * Get notice message class
		 * 
		 * @return string
		 */
		public function get_class() {
			return $this->class;
		}
	}
}