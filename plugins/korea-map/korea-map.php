<?php
	/*
	Plugin Name: Korea Map
	Description: Puts korea map in post or page.
	Author: Icansoft
	Author URI: http://icansoft.com/product/korea-map
	Version: 0.1.3
	Text Domain: kimsmap
	Domain Path: /languages/
	License: GPL v3
	
	Korea Map
	Copyright (C) 2020 Icansoft - support@icansoft.com
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	  
	@package Korea_Map
	@category Core
	@author Icansoft
	*/
	
	if ( !class_exists( 'CKoreaMap' ) ) {
	
		class CKoreaMap {
		
			function __construct() {
			              
				$this->KimsPrepare();
				$this->KimsInclude();
				$this->KimsSetting();
				$this->KimsTemplete();
	     
				register_activation_hook( __FILE__, array( $this, 'KimsInstall' ) );
			}
			
			public function KimsPrepare() {

				if ( !defined( 'KIMS_INCLUDE_DIR' ) )	define( 'KIMS_INCLUDE_DIR', plugin_dir_path( __FILE__ ).'inc/' );
				if ( !defined( 'KIMS_TITLE' ) )				define('KIMS_TITLE', 'Korea Map');
				if ( !defined( 'KIMS_OPTION_NAME' ) )	define('KIMS_OPTION_NAME', 'kimsmap');
				if ( !defined( 'KIMS_TEXT_DOMAIN' ) )	define('KIMS_TEXT_DOMAIN', 'kimsmap');
			}
			
			public function KimsInclude() {
			
				require_once( KIMS_INCLUDE_DIR . 'functions.php' );	
			}
			
			public function KimsSetting() {
			
				global $g_arKimsOptions, $g_arKimsDefaultOptions;
				
				$g_arKimsOptions				= KimsGetOptions();
				$g_arKimsDefaultOptions	= KimsGetDefaultOptions();
			}
			
			public function KimsTemplete(){
				
				if( is_admin() ){
					require_once( KIMS_INCLUDE_DIR . 'admin.php' );
				}
				else{
					require_once( KIMS_INCLUDE_DIR . 'user.php' );
				}	
			}
			
			public function KimsInstall( ) {
			
				require_once( KIMS_INCLUDE_DIR . 'install.php' );
				KimsInstall();
			}
		}
		
		$GLOBALS['kims'] = new CKoreaMap();
	}
