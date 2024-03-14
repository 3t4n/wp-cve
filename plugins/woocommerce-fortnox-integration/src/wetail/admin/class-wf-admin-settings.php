<?php
namespace src\wetail\admin;

use src\prototypes\WF_Singleton;
use src\wetail\WF_View;
use src\fortnox\WF_Plugin;


if( ! class_exists( __NAMESPACE__ . "\WF_Admin_Settings" ) ):
class WF_Admin_Settings extends WF_Singleton
{
	/**
	 * Add options page
	 *
	 * @param array $page
	 * @return mixed
	 */
	public static function add_page( $page )
	{
		$settings = self::getInstance();
		$defaults = [
			'slug' => "",
			'title' => "",
			'menu' => "",
			'tabs' => [],
		];
		$page = array_merge( $defaults, $page );

		if( ! isset( $settings->pages ) )
			$settings->pages = [];

		$settings->options = [];
		$settings->pages[ $page['slug'] ] = $page;

		$hook = add_options_page(
			$page['title'],
			$page['menu'],
			'manage_options',
			$page['slug'],
			[ __CLASS__, "display_page" ]
		);

		return $hook;
	}

	/**
	 * Add settings page tab
	 *
	 * @param array $tab
     	 * @return mixed
	 */
	public static function add_tab( $tab )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => "",
			'name' => "general",
			'title' => __( "General", WF_Plugin::TEXTDOMAIN ),
			'sections' => [],
			'saveButton' => __( 'Save changes',  WF_Plugin::TEXTDOMAIN ),
			//'header' => '<a href="https://wetail.io"><img src="' . PLUGIN_URL . 'assets/images/wetail_logo_red.png"><img src="' . PLUGIN_URL . 'assets/images/fortnox_logo_svart.svg"></a>',
			'class' => ""
		];
		$tab = array_merge( $defaults, $tab );

		if( ! isset( $settings->pages ) || ! isset( $tab['page'] ) )
			return false;

		$settings->pages[ $tab['page'] ]
			['tabs'][ $tab['name'] ] = $tab;
	}

	/**
	 * Add settings section
	 *
	 * @param array $section
         * @return mixed
	 */
	public static function add_section( $section )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => null,
			'tab' => "general",
			'name' => "",
			'title' => "",
			'description' => "",
			'fields' => []
		];
		$section = array_merge( $defaults, $section );

		if( ! isset( $settings->pages ) || ! isset( $section['page'] ) )
			return false;

		if( ! isset( $settings->pages[ $section['page'] ]['tabs'][ $section['tab'] ] ) ) {
			$section['tab'] = "general";

			self::add_tab( [ 'page' => $section['page'] ] );
		}

		$settings->pages[ $section['page'] ]
			['tabs'][ $section['tab'] ]
			['sections'][ $section['name'] ] = $section;

		add_settings_section(
			$section['name'],
			$section['title'],
			[ __CLASS__, "print_section_description" ],
			$section['page']
		);
	}

	public static function print_section_description() {}

	/**
	 * Add settings field
	 *
	 * @param array $setting
         * @return mixed
	 */
	public static function add_field( $setting )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => null,
			'tab' => "general",
			'section' => "",
			'name' => null,
			'title' => "",
			'type' => "text",
			'class' => "regular-text",
			'default' => null
		];
		$setting = array_merge( $defaults, $setting );

		if( ! isset( $settings->pages ) || ! isset( $setting['page'] ) )
			return false;

		if( $setting['name'] ) {
			register_setting( $setting['page'] . '-' . $setting['tab'], $setting['name'] );

			$settings->options[] = $setting['name'];
		}

		if( ! empty( $setting['options'] ) ) {
			foreach( $setting['options'] as $option ) {
				if( ! empty( $option['name'] ) ) {
					register_setting( $setting['page'] . '-' . $setting['tab'], $option['name'] );

					$settings->options[] = $option['name'];
				}
			}
		}

		$settings->pages[ $setting['page'] ]
			['tabs'][ $setting['tab'] ]
			['sections'][ $setting['section'] ]
			['fields'][] = $setting;

		if( isset( $setting['default'] ) && ! get_option( $setting['name'] ) )
			update_option( $setting['name'], $setting['default'] );
	}

	/**
	 * Display settings page
	 */
	public static function display_page(){
		$settings = self::getInstance();
		
		if( ! isset( $_REQUEST['page'] ) || empty( $settings->pages[ $_REQUEST['page'] ] ) )
			return;
		
		if( empty( $_REQUEST['tab'] ) )
			$_REQUEST['tab'] = "general";
		
		$title = $settings->pages[ $_REQUEST['page'] ]['title'];
		$currentTab = $settings->pages[ $_REQUEST['page'] ]['tabs'][ $_REQUEST['tab'] ];
		$saveButton = $currentTab['saveButton'];

		if( 1 < count( $settings->pages[ $_REQUEST['page'] ]['tabs'] ) ) {
			$hasTabs = true;
			$tabs = array_map( function( $tab ) use( $currentTab ) {
				$tab['selected'] = ( $tab['name'] == $currentTab['name'] );
				unset( $tab['sections'] );
				
				return compact( 'tab' );
			}, array_values( $settings->pages[ $_REQUEST['page'] ]['tabs'] ) );
		}
		else
			$tabs = false;
		
		$sections = array_map( function( $section ) {
			$section['fields'] = array_map( function( $field ) {
				if( ! empty( $field['name'] ) )
					$field['value'] = get_option( $field['name'] );
				
				if( ! empty( $field['options'] ) )
					$field['options'] = array_map( function( $option ) use( $field ) {
						if( isset( $option['name'] ) )
							$option['checked'] = ( 1 == get_option( $option['name'] ) );
						elseif( isset( $field['value'] ) )
							$option['selected'] =
							$option['checked'] = ( $option['value'] == $field['value'] );
						
						return compact( 'option' );
					}, $field['options'] );
				
				if( "checkbox" == $field['type'] )
					$field['checked'] = ( 1 == get_option( $field['name'] ) );
				
				if( empty( $field[ $field['type'] ] ) )
					$field[ $field['type'] ] = true;
				
				if( "table" == $field['type'] && is_callable( $field['table']['table']['rows'] ) )
					$field['table']['table']['rows'] = call_user_func( $field['table']['table']['rows'] );
				
				return compact( 'field' );
			}, $section['fields'] );
			
			return compact( 'section' );
		}, array_values( $currentTab['sections'] ) );
		
		ob_start();
		settings_fields( $_REQUEST['page'] . '-' . $_REQUEST['tab'] );
		
		$hidden = ob_get_clean();

		$buy = false;

        if( empty( get_option( 'fortnox_license_key' ) ) ){
            $buy = true;
        }

        $page = compact( 'title', 'hasTabs', 'tabs', 'hidden', 'sections', 'saveButton', 'buy' );
		WF_View::render( 'admin/settings', $page );
	}
}
endif;
