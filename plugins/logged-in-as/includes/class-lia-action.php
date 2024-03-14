<?php
namespace logged_in_as;
/**
 * class-lia-action.php
 *
 * Performs the menu-item text manipulation to the target menu-item
 * Adds a css class to the target menu item
 *
 * @version 1.0
 * @author Jerry Stewart
 */

// Prevent direct file access
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Logged_In_As_Action' ) ) {

	class Logged_in_As_Action {
		
		function __construct() {
			
			add_filter( 'wp_nav_menu_args', array( $this, 'modify_nav_menu_args' ) );

			add_filter( 'nav_menu_css_class', array( $this, 'menu_css_class_handler' ), 10, 4 ); 		

			add_action( 'check_admin_referer', array( $this, 'logout_without_confirm' ), 10, 2);

		}
		
		function logout_without_confirm( $action, $result )
		{
		    /**
		     * Allow logout without confirmation
		     */
		    if ( $action == "log-out" && !isset( $_GET[ '_wpnonce' ] ) ) {
		    	
		        $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : get_permalink( wp_logout_url() );
		        $location = str_replace( '&amp;', '&', wp_logout_url( $redirect_to ) );
		        header( "Location: $location" );
		        die;
		    }
		}		
		
		/**
		 * Add a css class to the target menu item
		 */
		function menu_css_class_handler( $classes, $item, $args, $depth ) {
			
			$target_menu_array = $target_menu_id = get_option( Logged_in_As_Options::$target_menu_id_option );
			if ( !is_array( $target_menu_array ) )
				$target_menu_array = [ $target_menu_id ];
			
			if ( is_user_logged_in() && in_array( $item->ID, $target_menu_array ) ) {

				if ( !in_array( LIA_ICON_CSS_CLASS, $classes ) )
					$classes[] = LIA_ICON_CSS_CLASS;
			}
			
			return $classes;			
		}
		
		/**
		 * If the current user is logged in, modify the required menu item to the required replacement value.
		 * Target menu and replacement value are set up in wordpress admin options
		 */
		function modify_nav_menu_args( $args ) {

			$target_menu_array = $target_menu_id = get_option( Logged_in_As_Options::$target_menu_id_option );
			if ( !is_array( $target_menu_array ) )
				$target_menu_array = [ $target_menu_id ];

			$menu_format = get_option( Logged_in_As_Options::$menu_name_format_option );
			
			// add a menu walker.. is this a vulnerability? .. what if another plugin has added a walker?
			$args[ 'walker' ] = new class( $target_menu_array, $menu_format ) extends \Walker_Nav_Menu {

				private $target_menu_array, $menu_format;

				public function __construct( $target_menu_array, $menu_format ) {
					$this->target_menu_array = $target_menu_array;
					$this->menu_format = $menu_format;
				}
				
		    // Displays start of an element. E.g '<li> Item Name'
		    // @see Walker::start_el()
		    function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {

					// is this a target menu item?
					if ( is_user_logged_in() && in_array( $item->ID, $this->target_menu_array ) ) {
							
						// use the custom replacement value
						$item->title = $this->menu_format;
						
						// --- replace [metadata] values in the replacement string with the actual metadata
						// look for opening option escape char: '['
						while( false !== $start_pos = strpos( $item->title, '[' ) ) {
							
							// found opening
							$end_pos = strpos( $item->title, ']', $start_pos );
							
							if ( false !== $end_pos ) {
								
								// found closing
								$meta_key = substr( $item->title, $start_pos+1, $end_pos-$start_pos-1 );

								$substitution = 'NO USER';

				    		$user = wp_get_current_user();
				    		if ( $user->exists() ) {
				    			$substitution = get_user_meta( $user->ID, $meta_key, true );
				    			
				    			// if missing or unknown user meta, use 'USER {ID}'
					    		if ( $substitution === false || $substitution === '' )
					    			$substitution = 'USER ' . $user->ID;
								}
												    			
				    		$item->title = //get_avatar( wp_get_current_user()->user_email, 30 ) . ' ' .
				    									 substr( $item->title, 0, $start_pos ) .
				    									 ' ' . $substitution . ' ' .
				    									 substr( $item->title, $end_pos+1 );
							}
						}
					}
		    	
		    	parent::start_el( $output, $item, $depth, $args, $current_object_id );
		    }

			};
		
			return $args;
		}

	} // end class Logged_In_As_Action
} // end !class_exists...