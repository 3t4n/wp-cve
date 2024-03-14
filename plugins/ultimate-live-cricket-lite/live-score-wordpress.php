<?php 
/*
Plugin Name: Ultimate Live Cricket Lite.
Plugin URI: http://ulcwp.com
Description: Upcoming,Completed and InProgress Series and Matches. More features are complete match details(Score card,Partnership,Graphs,Teams) and Single player stats.. Live score update automatically without refresh the page. ULCW also have widget to show  Upcoming,Completed and InProgress matches on sidebar. In short You have all the data to run full featured cricket website.
Version: 1.4.2
Author: Web Solutions Soft
Author URI: http://ulcwp.com
Text Domain: wss-live-score
Domain Path: lang
*/
/*
  *Exit if accessed directly
*/
if ( ! defined( 'ABSPATH' ) ) exit;
    if( ! defined( 'LCW_LIVE_SCORE_PLUGIN_NAME' )){

        define( 'LCW_LIVE_SCORE_PLUGIN_NAME', 'Ultimate Live Cricket' );
    }
    if( ! defined( 'LCW_LIVE_SCORE_VERSION' )){ 
        define( 'LCW_LIVE_SCORE_VERSION', '1.4.2');
    }  
    if( ! defined( 'LCW_LIVE_SCORE_ROOT_PATH' )){
        define( 'LCW_LIVE_SCORE_ROOT_PATH', dirname(__FILE__) );
    }  
    /*
     * Load the required classes 
    */
    include_once 'classes/wsl-base-class.php';  
    include_once 'classes/wsl-main-class.php';        
    $live_score = new LCW_Live_Score();    
    add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ),'lcw_action_links' );
    function lcw_action_links( $links ){
        $settings_link = array(
           '<a href="' . admin_url( '?page=lcw-score-settings' ) . '" style="color:#df003b;">Settings</a>',
           '<a href="http://ulcwp.com/pricing-plans/" style="color:#11967A;" target="_blank"> Upgrade To Pro </a>',
           '<a href="http://ulcwp.com/forums/forum/featured-requests-and-misc/" style="color:#11967A;" target="_blank"> Need Help </a>',
           );
        return array_merge( $links, $settings_link );
    }
register_activation_hook( __FILE__, array( $live_score, 'lcw_score_install_plugin' ) );
register_deactivation_hook( __FILE__, array( $live_score, 'lcw_score_uninstall_plugin' ) );