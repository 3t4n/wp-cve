<?php
/*
Plugin Name: Reservit Hotel
Plugin URI: http://www.reservit.com/hebergement
Description: A plugin for live display of your hotel's room best price by Reservit
Version: 2.1
Date: 16/05/2019
Author: Pascal ALBERTO for Reservit
Text Domain: reservit-hotel
Domain Path: /languages
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class Reservit_Hotel_Plugin
{
    public function __construct()
    {
	    function rsvit_register_session(){
            if( !session_id() ){
                session_start();
            }
        }
        
        include_once plugin_dir_path( __FILE__ ).'/reservit-hotel-best-price.php';
        
        new Reservit_Hotel_Bestprice();
        //add_action( 'plugins_loaded', 'rsvit_register_session' );
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
    }
    
    
    //Add menu page
    public function add_admin_menu()
    {
        add_menu_page('Reservit hotel config', 'Reservit hotel', 'manage_options', 'reservitHotel', array($this, 'accueil_reservit_hotel_html'),'dashicons-store');
        add_submenu_page('reservitHotel', esc_html__('Home','reservit-hotel'), esc_html__('Home','reservit-hotel'), 'manage_options', 'reservitHotel', array($this, 'accueil_reservit_hotel_html'));
    }
    
    //Homepage
    public function accueil_reservit_hotel_html()
    {
    echo '<h1>'.get_admin_page_title().'</h1>';
    echo '<div id="reservit_home_header">';
    echo '<img src="'.plugins_url('reservit.png', __FILE__ ).'">';
    echo '</div>';
?>
    <h2><i class="fa fa-hand-spock-o" aria-hidden="true"></i> <?= esc_html_e('Welcome to the Reservit Hotel Best Price plugin\'s homepage','reservit-hotel'); ?></h2>
    <p><?= esc_html_e('Reservit makes available to its hotels subscribers to the online booking service a plungin to display the best available rate for their hotel according to different parameters such as the period, the number of adults and / or children.','reservit-hotel'); ?></p>
    <h3><i class="fa fa-sliders" aria-hidden="true"></i> <?= esc_html_e('Features','reservit-hotel');?></h3>
    <ul class="ul_option">
        <li><?= esc_html_e('Settings interface available in French and English','reservit-hotel');?></li>
        <li><?= esc_html_e('The plungin allows to place the widget in any widget area of your site because the positioning of the elements is fixed above the rest of the content of the site','reservit-hotel');?></li>
        <li><?= esc_html_e('Items can retain the default properties of your theme','reservit-hotel');?></li>
        <li><?= esc_html_e('Button that can be set in font size and font color, background color, font-weight, border radius, border color and thickness, hover','reservit-hotel');?></li>
        <li><?= esc_html_e('Button text configurable in 6 languages (FR, EN, IT, ES, PT, DE)','reservit-hotel');?></li>
        <li><?= esc_html_e('Dynamic display of the button text in the user\'s language (default is English if the visitor\'s language is not one of the programmed languages)','reservit-hotel');?></li>
        <li><?= esc_html_e('Ability to display or not an icon on the button','reservit-hotel');?></li>
        <li><?= esc_html_e('Window allowing the visitor to choose the parameters dates, number of adults, number of children','reservit-hotel');?></li>
        <li><?= esc_html_e('Responsive window on mobile with change of orientation management','reservit-hotel');?></li>
        <li><?= esc_html_e('The window is displayed in one of the 6 programmed languages(default is English if the visitor\'s language is not one of the programmed languages)','reservit-hotel');?></li>
        <li><?= esc_html_e('Setting the appearance of the window close button','reservit-hotel');?></li>
        <li><?= esc_html_e('Ability to display the price of a partner site in the window only if this one is higher than your\'s','reservit-hotel');?></li>
    </ul>
    <hr/>
    <h3><i class="fa fa-cogs" aria-hidden="true"></i> <?= esc_html_e('Install');?></h3>
    <p><?= esc_html_e('Proceed as for any other plugins to install and activate in the extension tab of admin menu','reservit-hotel');?></p>
    <p><?= esc_html_e('A Reservit Hotel menu will appear in the admin menu side bar','reservit-hotel');?></p>
    <p><?= esc_html_e('Go to the admin menu appearance>widgets tab and slide the Reservit Hotel widget into any widget zone available in your theme : footer widget zone is a good choise. Any way, the display position of the widget elements is automatic.','reservit-hotel');?></p>
    <p><?= esc_html_e('Set the resevit plugin options in each tab of the Reservit Hotel menu','reservit-hotel');?></p>
    <p><?= esc_html_e('That\'s it!','reservit-hotel');?></p>
    <hr/>
    <h3><i class="fa fa-code-fork" aria-hidden="true"></i> <?= esc_html_e('Version');?></h3>
    <h4>V 1.9 <?= esc_html_e('May 15 2019','reservit-hotel');?></h4>
    <h4><?= esc_html_e('What does this new version bring?','reservit-hotel'); ?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('session_start removed','reservit-hotel');?></li>
        <li><?= esc_html_e('use of a cookie','reservit-hotel');?></li>
    </ul>
    <h4>V 1.7 <?= esc_html_e('May 2nd 2019','reservit-hotel');?></h4>
    <h4><?= esc_html_e('What does this new version bring?','reservit-hotel'); ?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Styles and scripts enqueuing too early is now fixed','reservit-hotel');?></li>
    </ul>
    <h4>V 1.6 <?= esc_html_e('February 26th 2019','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Compatibility with php versions before 5.5 fixed in file reservit-hotel-bestprice-widget.php','reservit-hotel');?></li>
    </ul>
    <h4>V 1.5 <?= esc_html_e('February 22nd 2019','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Translation update','reservit-hotel');?></li>
    </ul>
    <h4>V 1.4 <?= esc_html_e('February 21st 2019','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Language detection improvement','reservit-hotel');?></li>
        <li><?= esc_html_e('Compatibility with php versions before 5.5 fixed (Fatal error message on activation at line 232)','reservit-hotel');?></li>
    </ul>
    <h4>V 1.3 <?= esc_html_e('March 29th 2018','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Bug fixed on CSS page by using the latest version of colorpicker','reservit-hotel');?></li>
    </ul>
    <h4>V 1.2 <?= esc_html_e('October 27rd 2017','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('French and English translation of settings interface','reservit-hotel');?></li>
        <li><?= esc_html_e('Alpha channel added for transparency in colorpicker','reservit-hotel');?></li>
        <li><?= esc_html_e('Major changes in CSS setting options','reservit-hotel');?></li>
        <li><?= esc_html_e('Optimisation of language selection','reservit-hotel');?></li>
        <li><?= esc_html_e('Size and position of pop-up window and button are now automatic','reservit-hotel');?></li>
        <li><?= esc_html_e('On wide enought screen, the pop-up window is shown at the initialization instead of the button','reservit-hotel');?></li>
        <li><?= esc_html_e('Use of session variable to display the button instead of the pop-up window if the user has already closed the pop-up window one time in the session','reservit-hotel');?></li>
        <li><?= esc_html_e('Minor changes in JS','reservit-hotel');?></li>
        <li><?= esc_html_e('Custom CSS issue fixed','reservit-hotel');?></li>
        <li><?= esc_html_e('Validation and sanitization of options fields and $_POST','reservit-hotel');?></li>
        <li><?= esc_html_e('Output escaped for security','reservit-hotel');?></li>
    </ul>
    <h3><i class="fa fa-code-fork" aria-hidden="true"></i> <?= esc_html_e('Changelog','reservit-hotel');?></h3>
    <h4>V 1.1 <?= esc_html_e('June 19th 2017','reservit-hotel');?></h4>
    <ul class="ul_option">
        <li><?= esc_html_e('Display of the button after complete loading of the page to avoid the appearance of the button in its widget zone','reservit-hotel');?></li>
        <li><?= esc_html_e('Minor bug correction','reservit-hotel');?></li>
        <li><?= esc_html_e('Cleaning options when uninstalling the plugin','reservit-hotel');?></li>
    </ul>
    <hr/>
    <h3><i class="fa fa-user-secret" aria-hidden="true"></i> <?php esc_html_e('Who can use this plugin?','reservit-hotel');?></h3>
    <p><?php esc_html_e('For the plugin to work properly you must have subscribed to the reservit online hotel reservation service.','reservit-hotel');?></p>
    <p>
        <span><?php esc_html_e('Are you interested by reservit services?','reservit-hotel');?><br/>
        <a href="http://www.reservit.com/demander-un-devis/" target="_blank"><?php esc_html_e('Ask for a quote','reservit-hotel');?></a>
        </span>
    </p>
    
<?php
    
    }
}

new Reservit_Hotel_Plugin();
