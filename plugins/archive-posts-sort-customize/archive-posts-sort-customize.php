<?php
/*
Plugin Name: Archive Posts Sort Customize
Plugin URI: http://wordpress.org/extend/plugins/archive-posts-sort-customize/
Description: Customize the posts order of the list of Frontend Archive Posts.
Version: 1.6.1
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=apsc&utm_campaign=1_6_1
Text Domain: apsc
Domain Path: /languages
*/

/*  Copyright 2012 gqevu6bsiz (email : gqevu6bsiz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



if ( !class_exists( 'APSC' ) ) :

final class APSC
{

	public $name;
	public $ver;
	public $main_slug;
	public $ltd;
	public $plugin_dir;
	public $plugin_url;
	public $plugin_slug;

	public $Plugin;
	public $Cap;
	public $Form;
	public $Site;
	public $Env;
	public $User;
	public $ThirdParty;
	public $Link;

	public $Api;
	public $Helper;

    public function __construct()
    {
		
		$this->Plugin     = new stdClass;
		$this->Cap        = new stdClass;
		$this->Form       = new stdClass;
		$this->Site       = new stdClass;
		$this->Env        = new stdClass;
		$this->User       = new stdClass;
		$this->ThirdParty = new stdClass;
		$this->Link       = new stdClass;

		$this->Api        = new stdClass;
		$this->Helper     = new stdClass;
		
	}

	public function init()
	{
		
		add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) , 20 );
		add_action( 'setup_theme' , array( $this , 'setup_theme' ) , 20 );
		add_action( 'after_setup_theme' , array( $this , 'after_setup_theme' ) , 20 );
		add_action( 'init' , array( $this , 'wp_init' ) , 20 );
		add_action( 'wp_loaded' , array( $this , 'wp_loaded' ) , 20 );
		
	}

	public function plugins_loaded()
	{
		
		$this->define_constants();
		$this->includes();

		do_action( $this->main_slug . '_plugins_loaded' );

		add_action( $this->main_slug . '_screen' , array( $this , 'screen' ) );
		
	}

	private function define_constants()
	{
		
		$this->name        = 'Archive Post Sort Customize';
		$this->ver         = '1.6.1';
		$this->main_slug   = 'apsc';
		$this->ltd         = 'apsc';
        $this->plugin_dir  = plugin_dir_path( __FILE__ );
        $this->plugin_url  = plugin_dir_url( __FILE__ );
		$this->plugin_slug = str_replace( '.php' , '' , basename( dirname( __FILE__ ) ) );

		load_plugin_textdomain( $this->ltd , false , $this->plugin_slug . '/languages' );

		include_once( $this->plugin_dir . 'core/api.php' );
		include_once( $this->plugin_dir . 'core/helper.php' );

		$this->Api    = new APSC_Api();
		$this->Helper = new APSC_Helper();
		
	}

	private function includes()
	{

		$includes = array(
			'core/init.php',
			'core/init_add.php',
			'third-party/third-party.php',
			'admin/master.php',
			'front/master.php',
		);
		
		$this->Helper->includes( $includes );
		
	}

	public function setup_theme()
	{
		
		do_action( $this->main_slug . '_setup_theme' );

	}
	
	public function after_setup_theme()
	{
		
		do_action( $this->main_slug . '_after_setup_theme' );

	}
	
	public function wp_init()
	{
		
		do_action( $this->main_slug . '_init' );
		
	}
	
	public function wp_loaded()
	{
	
		if( $this->Env->is_cron ) {
			
			do_action( $this->main_slug . '_cron' );
			
		} else {
			
			do_action( $this->main_slug . '_after_init' );

			if( $this->Env->is_ajax ) {
	
				do_action( $this->main_slug . '_ajax' );
	
			} else {
				
				do_action( $this->main_slug . '_screen' );
	
			}
			
		}

	}
	
	public function screen()
	{
		
		if( !empty( $this->ThirdParty->debug_bar ) ) {
			
			add_filter( 'debug_bar_panels' , array( $this , 'debug_bar_panels' ) );
			
		}

	}
	
	public function debug_bar_panels( $panels )
	{
		
		if ( !class_exists( 'Debug_Bar_Panel' ) ) {
			
			return $panels;
			
		}

		$this->Helper->includes( 'controller/debug-bar.php' );
		
		$panels[] = new APSC_Debug_Panel();
		
		return $panels;

	}
	
}

$GLOBALS['APSC'] = new APSC();
$GLOBALS['APSC']->init();

endif;

