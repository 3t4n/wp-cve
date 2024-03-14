<?php
namespace Enteraddons\Admin;

/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists( 'Admin_Templates_Map' ) ) {

	class Admin_Templates_Map {

		use Header;
		use Tabs;
		use Button;
		use General;
		use Elements;
		use Extensions;
		use Integration;
		use Support;
		use Premium;

		function __construct() {}
		
		public function admin_page_init() {
			$this->set_header_content();
			$this->set_tabs();
			$this->set_support_content();
			$this->set_premium_content();
			$this->admin_page_maping();
		}

		public function admin_page_maping() {

			echo '<div class="enteraddons-wrapper"><form id="enteraddons_settings_from" action="" method="post">';

	            // check if the user have submitted the settings
                if ( isset( $_GET['settings-updated'] ) ) {
                // add settings saved message with the class of "updated"
                add_settings_error( 'enteraddons_messages', 'enteraddons_message', esc_html__( 'Settings Saved', 'enteraddons' ), 'updated' );
                }
                //
                settings_fields( 'enteraddons_settings_option_group' ); 
                //
                do_settings_sections( 'enteraddons_settings_option_group' ); 

                // show error/update messages
                settings_errors( 'enteraddons_messages' );

				// Header
				$this->header_area();
				// Tabs
				$this->admin_tabs();
				// Tab Content

				echo '<div class="tab-content">';
					$this->general_tab_content();
					$this->elements_tab_content();
					$this->extensions_tab_content();
					$this->integration_tab_content();
					$this->support_tab_content();
					$this->premium_tab_content();
				echo '</div>';
				// Save Buton
				$this->save_button();
			echo '</form></div>';

		}

		public function set_header_content() {

			$content = array(
				'logourl' 	=> ENTERADDONS_DIR_URL.'assets/logo.png',
				'title' 	=> '',
				'desc' 		=> esc_html__( 'Enter Addons - Preferred Addons For Elementor And WordPress.', 'enteraddons' ),
			);

			$this->header_content( $content );
		}

		public function set_tabs() {

			$tabs = array(
				'general' 	 => array(
					'name' 	 => esc_html__( 'General', 'enteraddons' ),
					'icon' 	 => 'fa fa-cog',
					'is_active' => true,
					'show_in'	=> 'ALL'
				),
				'elements' 	 => array(
					'name' 	 => esc_html__( 'Elements', 'enteraddons' ),
					'icon' 	 => 'fa fa-th-large',
					'is_active' => false,
					'show_in'	=> 'ALL'

				),
				'extensions' => array(
	                'name'   => esc_html__( 'Extensions', 'enteraddons' ),
	                'icon'   => 'fa fa-superpowers',
	                'is_active' => false,
	                'show_in'   => 'ALL'
            	),
				'integration' => array(
					'name' 	 => esc_html__( 'Integration', 'enteraddons' ),
					'icon' 	 => 'fa fa-plug',
					'is_active' => false,
					'show_in'	=> 'ALL'
				),
				'support' 	 => array(
					'name' 	 => esc_html__( 'Support', 'enteraddons' ),
					'icon' 	 => 'fa fa-support',
					'is_active' => false,
					'show_in'	=> 'ALL'
				),
				'premium' 	 => array(
					'name' 	 => esc_html__( 'Go Premium', 'enteraddons' ),
					'icon' 	 => 'fa fa-diamond',
					'is_active' => false,
					'show_in' => 'LITE'
				),
			);

			$this->tabs_items( apply_filters( 'ea_admin_tabs', $tabs ) );
		}

		public function set_support_content() {

			$content = array(

				array(
					'title' => esc_html__( 'Documentation', 'enteraddons' ),
					'icon' 	=> 'fa fa-wpforms',
					'url' 	=> 'https://enteraddons.com/documentation/',
					'desc' 	=> esc_html__( 'See the Full Documentation to Understand how it works.', 'enteraddons' )
				),
				array(
					'title' => esc_html__( 'Support Forum', 'enteraddons' ),
					'icon' 	=> 'fa fa-video-camera',
					'url' 	=> 'https://support.themelooks.com/',
					'desc' 	=> esc_html__( 'Contact Our Support Team and Get the Workaround Instantly.', 'enteraddons' )
				)

			);

			$this->getSupports( $content );
		}

		public function set_premium_content() {
			
			$content = array(
				'pro_link' => 'https://enteraddons.com/',
				'statistic' => \Enteraddons\Admin\Admin_Helper::enteraddons_pro_features_statistic()
			);

			$this->getStatistic( $content );
		}

	}

}
