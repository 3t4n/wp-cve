<?php
/*
 * @link       http://www.apoyl.com/
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/admin
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Apoyl_Baidupush_Admin {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		

	}
	
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/apoyl-baidupush-admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/apoyl-baidupush-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function links($alinks){
	   
	 
	       $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=apoyl-baidupush-settings') ) .'">'.__('settingsname','apoyl-baidupush').'</a>';
           $alinks=array_merge($links,$alinks);
	
	    return $alinks;
	}
	public function menu(){
	    add_options_page(__('settings','apoyl-baidupush'),  __('settings','apoyl-baidupush'), 'manage_options','apoyl-baidupush-settings', array($this,'settings_page'));
	}
	public function settings_page(){
	    require_once plugin_dir_path(__FILE__).'partials/apoyl-baidupush-admin-display.php';
	}

    public function push($post_ID)
    {
        global $wpdb;
        $arr = get_option('apoyl-baidupush-settings');
        $file=apoyl_baidupush_file('adminautopush');
        if($file){
            include $file;
        }
       
    }
	

}
