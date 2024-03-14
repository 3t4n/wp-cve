<?php
namespace logged_in_as;
/**
 * class-lia-options.php
 *
 * Creates the Options page in the Wordpress Dashboard
 *
 * @version 1.0
 * @author Jerry Stewart
 */

// Prevent direct file access
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Logged_In_As_Options' ) ) {

	class Logged_in_As_Options {
		
		// WordPress 'options' saved to the options table
		public static $target_menu_id_option = LIA_PREFIX . 'target-menu-item';
		public static $menu_name_format_option = LIA_PREFIX . 'menu-name-format';
		public static $avatar_size_option = LIA_PREFIX . 'avatar_size';
	
		// text input max size
		private static $menu_name_format_field_max_length = 50;
		private static $avatar_size_field_max_length = 5;

		private $menu_name_location = array();
		private $settings_group = LIA_PREFIX . 'options';
		
	
		function __construct() {
			
			// add the menu item
			add_action( 'admin_menu', array( $this, 'lia_register_options_page' ) );
			
			// register settings
			add_action( 'admin_init', array( $this, 'settings_api_init' ) );
			
			// add link to the settings page
			add_filter( 'plugin_action_links', array( $this, 'add_plugin_link' ), 10, 2 );
		}

		/**
		 *	Adds a link directly to the settings page from the plugin page
		 */
		function add_plugin_link( $links, $file ) {
		
			$admin_url = is_network_admin() ? network_admin_url('admin.php') : admin_url('admin.php');
		
			/* create link */
			if ( ( $file == 'logged-in-as/logged-in-as.php' ) or ( $file == 'logged-in-as-multisite/logged-in-as-multisite.php' ) ) {
				array_unshift( $links, '<a href="'.$admin_url.'?page=logged-in-as'.'">'. __( 'Settings', 'logged-in-as' ) . '</a>' );
			}
		
		return $links;
		} // end function add_plugin_link( ...
		

		/**
		 * Get a map of Menu Items and which Menu they belong to
		 */
		private function get_menu_name_locations() {
			if ( $this->menu_name_location == null ) {
	
				$this->menu_name_location = array();
				
				$menu_locations = get_nav_menu_locations();
	
				$menus = wp_get_nav_menus();
				foreach( $menus as $menu ) {
					$term_id = $menu->term_id;
					$menu_location = array_search( $term_id, $menu_locations );
					
					$this->menu_name_location[ $menu->name ] = $menu_location;
				}
			}
			
			return $this->menu_name_location;
		}

		/**
		 * Settings api housekeeping
		 */
		function lia_register_options_page() {
			
	    $page_title = 'Logged In As';
	    $menu_title = 'Logged In As';
	    $capability = 'manage_options';
	    $menu_slug = 'logged-in-as';
	    $render_function_name = 'render_lia_options';
	    $position = 24;
	
	    add_options_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, $render_function_name ), $position );
		}
		
		/**
		 *
		 */
		function settings_api_init() {
			// create/register setting
	    register_setting( $this->settings_group, self::$target_menu_id_option );
	    register_setting( $this->settings_group, self::$menu_name_format_option, array( $this, 'validate_menu_name' ) ); // with a sanitize callback
	    register_setting( $this->settings_group, self::$avatar_size_option );
	    
			// Create section of Page
			$settings_section_select_menu = LIA_PREFIX . 'section-select-menu';
			$settings_section_menu_name_format = LIA_PREFIX . 'section-menu-name-format';
			$settings_section_avatar = LIA_PREFIX . 'section-avatar-size';
			$page = $this->settings_group;
			
			//-------------- Select Menu section ------------------------ 
			add_settings_section( 
				$settings_section_select_menu,
				'Menu Select',
				array( $this, 'select_menu_section_text' ),
				$page );
			 
			add_settings_field(
			 self::$target_menu_id_option,
			 'Available Menus Items',
			 array( $this, 'select_menu_item_input_renderer' ),
			 $page,
			 $settings_section_select_menu );	    
	
			//-------------- Name Format section ------------------------
			add_settings_section( 
				$settings_section_menu_name_format,
				'Menu Format',
				array( $this, 'menu_name_format_section_text' ),
				$page );
			 
			// Add fields to that section 
			add_settings_field(
			 self::$menu_name_format_option,
			 'Menu Name Format',
			 array( $this, 'select_menu_format_input_renderer' ),
			 $page,
			 $settings_section_menu_name_format );	
			     
			//-------------- Avatar Size section ------------------------
			add_settings_section( 
				$settings_section_avatar,
				'Avatar',
				array( $this, 'avatar_section_text' ),
				$page );
			 
			// Add field to that section 
			add_settings_field(
			 self::$avatar_size_option,
			 'Avatar size',
			 array( $this, 'avatar_size_input_renderer' ),
			 $page,
			 $settings_section_avatar );	
			     
		}	// end settings_api_init(...
	
		
		//--------------- Select Target Menu implementation ------------------------------		
		function select_menu_section_text() {
			echo '<p>Select which menu item to target.</p>';
		}
		
		function select_menu_item_input_renderer() {
	
			// get the db value, used to preselect the dropdown
			$db_value = get_option( self::$target_menu_id_option, '' );
			$db_values = $db_value;
			if ( !is_array( $db_value ) )
				$db_values = [ $db_value ];
				
			// select multiple items from a dropdown list
			$html = '<select ' .
							 'id="' . self::$target_menu_id_option . '" ' .
							 'name="' . self::$target_menu_id_option . '[]" ' .
							 'multiple size="8">';
	
			$menu_name_locations = $this->get_menu_name_locations();
			
			// the <options> of the <select>, pre-selecting the ones that match from the retreived db data
			$menus = wp_get_nav_menus();	
			foreach( $menus as $menu ) {
				
				$menu_name = $menu->name;
				$items = wp_get_nav_menu_items( $menu->term_id );
				
				foreach( $items as $item ) {
					
					// eg. <option value="666" selected>Menu Title</option>
					$html .= '<option value="' . $item->ID . '"';
					if ( in_array( $item->ID, $db_values ) )
						$html .= ' selected';
						
					$html .= '>' . $item->title . '     [ ' . $menu_name . ' ( ' . $menu_name_locations[ $menu_name ] . ' ) ]' . '</option>';
				}
			}
	
			$html .= '</select>';
			
			echo $html;
			
		} // end select_menu_item_input_renderer( ..
		
	
		//--------------- Name Format implementation ------------------------------		
		
		function menu_name_format_section_text() {
			
			$html = "<p><b>Enter the replacement menu text for a logged in user</b></p>";
			$html .= "<p>Add user metadata like this: <b>[meta_key]</b></p>";
			$html .= '<p>Example: <b>logged in as [first_name] [last_name]</b>. This will replace the target menu text with "Logged in as Fred Smith"</p>';
			$html .= "<p>Useful meta_key include: <b>[first_name], [last_name], [nickname]</b></p>";
			
			echo $html;
		}
		
		function select_menu_format_input_renderer() {
	
			// db values
			$db_value = get_option( self::$menu_name_format_option, '' );
	
			// text input, assumes non malicious input (admin)
			$html = '<input ' .
							 'id="' . self::$menu_name_format_option . '" ' .
							 'name="' . self::$menu_name_format_option . '" ' . 
							 'value="' . $db_value . '" ' .
							 'size="' . self::$menu_name_format_field_max_length . '" ' .
							 '></input>';
			
			echo $html;
		}
		
		function validate_menu_name( $input ) {
			
			// sanitize and make sure it's not longer than the field length
			// just a tad paranoid since this is an admin area, but hey
			return substr( sanitize_text_field( $input ), 0, self::$menu_name_format_field_max_length );
		}
		
		
		
		
				//--------------- Avatar implementation ------------------------------		
		
		function avatar_section_text() {
			
			$html = "<p>Avatar is shown if a user is logged in <i>and</i> there is an avatar for that user <i>and</i> avatar size option is at least 10.</p>";
			$html .= "<p>If avatar size is less than 10, no avatars will be shown. Maximum size is 99.</p>";
			
			echo $html;
		}
		
		function avatar_size_input_renderer() {
	
			// db values
			$db_value = get_option( self::$avatar_size_option, '' );
	
			$html = '<input ' .
							 'type="number"' .
							 'id="' . self::$avatar_size_option . '" ' .
							 'name="' . self::$avatar_size_option . '" ' . 
							 'value="' . $db_value . '" ' .
							 'size="' . self::$avatar_size_field_max_length . '" ' .
							 'min="0" max="99" ' .
							 '></input>';
			
			echo $html;
		}
		
	
		
		/**
		 * Render the options page
		 */
		function render_lia_options() {
			?>
			<div class="wrap">
			    <h2>Logged-In-As Options</h2>
			    <form action="options.php" method="post">
			        <?php settings_fields( $this->settings_group ); ?>
			        <?php do_settings_sections( $this->settings_group ); ?>
			        <?php submit_button(); ?>
			    </form>
			</div>
			<?php
		}
		
	} // end class Logged_In_As_Options {...
} // end if ( exists( Logged_In_As ...	

