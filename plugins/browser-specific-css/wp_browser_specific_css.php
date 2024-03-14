<?php
/*
Plugin Name: Browser Specific CSS
Plugin URI: http://adrian3.com/projects/wordpress-plugins/browser-specific-css-wordpress-theme/
Description: The Browser Specific CSS plugin is a tool that allows developers to easily target different browsers straight from the stylesheet. Browser Specific CSS adds a short javascript to the head of your page that enables you to use browser specific css selectors. For example, targeting Internet Explorer 7 from your stylesheet is just a matter of defining styles with a ".ie7" selector. Every major browser is supported including code for targeting Macs, PCs, and Linux operating systems.
Author: Adrian Hanft
Version: 0.3
Author URI: http://adrian3.com
*/   
 
   
/*  Copyright 2009  
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* Guess the wp-content and plugin urls/paths
*/
// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


if (!class_exists('wp_browser_specific_css')) {
    class wp_browser_specific_css {
        //This is where the class variables go, don't forget to use @var to tell what they're for
        /**
        * @var string The options string name for this plugin
        */
        var $optionsName = 'wp_browser_specific_css_options';
        
        /**
        * @var string $localizationDomain Domain used for localization
        */
        var $localizationDomain = "wp_browser_specific_css";
        
        /**
        * @var string $pluginurl The path to this plugin
        */ 
        var $thispluginurl = '';
        /**
        * @var string $pluginurlpath The path to this plugin
        */
        var $thispluginpath = '';
            
        /**
        * @var array $options Stores the options for this plugin
        */
        var $options = array();
        
        //Class Functions
        /**
        * PHP 4 Compatible Constructor
        */
        function wp_browser_specific_css(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
            //Language Setup
            $locale = get_locale();
            $mo = dirname(__FILE__) . "/languages/" . $this->localizationDomain . "-".$locale.".mo";
            load_textdomain($this->localizationDomain, $mo);

            //"Constants" setup
            $this->thispluginurl = PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->thispluginpath = PLUGIN_PATH . '/' . dirname(plugin_basename(__FILE__)).'/';
            
            //Initialize the options
            //This is REQUIRED to initialize the options when the plugin is loaded!
            $this->getOptions();
            
            //Actions        
            add_action("admin_menu", array(&$this,"admin_menu_link"));
            add_action("wp_head", array(&$this,"css_browser_selector"));
			add_action("wp_head", array(&$this,"css_browser_url"));
            
            //Widget Registration Actions
            
            /*
            add_action("wp_head", array(&$this,"add_css"));
            add_action('wp_print_scripts', array(&$this, 'add_js'));
            */
            
            //Filters
            /*
            add_filter('the_content', array(&$this, 'filter_content'), 0);
            */
        }
        
function css_browser_selector() {
if ($this->options['wp_browser_specific_css_on_off'] == 'on') {

echo '
<!-- Browser Specific CSS -->
<script src="';
echo get_bloginfo('wpurl');

echo '/wp-content/plugins/browser-specific-css/css_browser_selector.js" type="text/javascript"></script>
'
; }}        


function css_browser_url() {
         if ($this->options['wp_browser_specific_css_url'] != '') {
	echo '<link rel="stylesheet" href="';
echo $this->options['wp_browser_specific_css_url']; 	

echo '" type="text/css" media="screen" />
'
; }}        
        
        /**
        * Retrieves the plugin options from the database.
        * @return array
        */
        function getOptions() {
            //Don't forget to set up the default options
            if (!$theOptions = get_option($this->optionsName)) {
                $theOptions = array(
	
	'wp_browser_specific_css_url'=>'',
	'wp_browser_specific_css_on_off'=>'on'	

	);
                update_option($this->optionsName, $theOptions);
            }
            $this->options = $theOptions;
            
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //There is no return here, because you should use the $this->options variable!!!
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }
        /**
        * Saves the admin options to the database.
        */
        function saveAdminOptions(){
            return update_option($this->optionsName, $this->options);
        }
        
        /**
        * @desc Adds the options subpanel
        */
        function admin_menu_link() {
            //If you change this from add_options_page, MAKE SURE you change the filter_plugin_actions function (below) to
            //reflect the page filename (ie - options-general.php) of the page your plugin is under!
            add_options_page('Browser Specific CSS', 'Browser Specific CSS', 10, basename(__FILE__), array(&$this,'admin_options_page'));
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
        }
        
        /**
        * @desc Adds the Settings link to the plugin activate/deactivate page
        */
        function filter_plugin_actions($links, $file) {
           //If your plugin is under a different top-level menu than Settiongs (IE - you changed the function above to something other than add_options_page)
           //Then you're going to want to change options-general.php below to the name of your top-level page
           $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
           array_unshift( $links, $settings_link ); // before other links

           return $links;
        }
        
        /**
        * Adds settings/options page
        */
        function admin_options_page() { 
            if($_POST['wp_browser_specific_css_save']){
                if (! wp_verify_nonce($_POST['_wpnonce'], 'wp_browser_specific_css-update-options') ) die('Whoops! There was a problem with the data you posted. Please go back and try again.'); 

                $this->options['wp_browser_specific_css_on_off'] = $_POST['wp_browser_specific_css_on_off'];
                $this->options['wp_browser_specific_css_url'] = $_POST['wp_browser_specific_css_url'];
                                        
                $this->saveAdminOptions();
                
                echo '<div class="updated"><p>Success! Your changes were sucessfully saved!</p></div>';
            }
?>                                   
                <div class="wrap">
<h1>Browser Specific CSS Plugin</h1>
                <form method="post" id="wp_browser_specific_css_options">
                <?php wp_nonce_field('wp_browser_specific_css-update-options'); ?>


<p>The Browser Specific CSS plugin is a tool that allows developers to easily target different browsers straight from the stylesheet. Browser Specific CSS adds a short javascript to the head of your page that enables you to use browser specific css selectors. For example, targeting Internet Explorer 7 from your stylesheet is just a matter of defining styles with a ".ie7" selector. Every major browser is supported including code for targeting Macs, PCs, and Linux operating systems. Please visit this plugins homepage at <a href="http://adrian3.com/projects/wordpress-plugins/browser-specific-css-wordpress-theme/">adrian3.com</a> with any bugs, suggestions, or questions. Thanks for using Browser Specific CSS, and I hope you like this plugin. <a href="http://adrian3.com/" title="-Adrian 3">-Adrian3</a></p>
<hr><br /><br />

 
<h2>1. On/Off</h2>
                  
<p>
<?php _e('Turn this plugin on or off here:', $this->localizationDomain); ?>
<select name="wp_browser_specific_css_on_off" id="wp_browser_specific_css_on_off">
  <option selected="selected"><?php echo $this->options['wp_browser_specific_css_on_off'] ;?></option>
  <option>on</option>
  <option>off</option>
</select>
                          </p>

<hr />

<h2>2. Link To a Different Stylesheet (Optional)</h2>
                                          <p>
  <?php _e('URL of your stylesheet', $this->localizationDomain); ?>
  <input name="wp_browser_specific_css_url" type="text" id="wp_browser_specific_css_url" value="<?php echo $this->options['wp_browser_specific_css_url'] ;?>"/>
                                          </p>

<hr />




<p><input type="submit" name="wp_browser_specific_css_save" value="Save" /></p>


                </form>

<h1>Help</h1>
<h2>Selector Chart:</h2>


<table width="300" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="150"><strong>Browser</strong></td>
    <td width="124" valign="top"><strong>CSS Selector</strong></td>
  </tr>
  <tr>
    <td>Internet Explorer (all versions)</td>
    <td valign="top">.ie</td>
  </tr>
  <tr>
    <td>Internet Explorer 8.x</td>
    <td valign="top">.ie8</td>
  </tr>
  <tr>
    <td>Internet Explorer 7.x</td>
    <td valign="top">.ie7</td>
  </tr>
  <tr>
    <td>Internet Explorer 6.x</td>
    <td valign="top">.ie6</td>
  </tr>
  <tr>
    <td>Internet Explorer 5.x</td>
    <td valign="top">.ie5</td>
  </tr>
  <tr>
    <td>Firefox (all versions)</td>
    <td valign="top">.gecko</td>
  </tr>
  <tr>
    <td>Firefox 2</td>
    <td valign="top">.ff2</td>
  </tr>
  <tr>
    <td>Firefox 3</td>
    <td valign="top">.ff3</td>
  </tr>
  <tr>
    <td>Firefox 3.5</td>
    <td valign="top">.ff3_5</td>
  </tr>
  <tr>
    <td>Camino</td>
    <td valign="top">.gecko</td>
  </tr>
  <tr>
    <td>Opera (all versions)</td>
    <td valign="top">.opera</td>
  </tr>
  <tr>
    <td>Opera 8.x</td>
    <td valign="top">.opera8</td>
  </tr>
  <tr>
    <td>Opera 9.x</td>
    <td valign="top">.opera9</td>
  </tr>
  <tr>
    <td>Opera 10.x</td>
    <td valign="top">.opera10</td>
  </tr>
  <tr>
    <td>Konqueror</td>
    <td valign="top">.konqueror</td>
  </tr>
  <tr>
    <td>Safari</td>
    <td valign="top">.webkit</td>
  </tr>
  <tr>
    <td>Safari 3.x</td>
    <td valign="top">.sarari3</td>
  </tr>
  <tr>
    <td>Webkit (Safari, NetNewsWire, OmniWeb, Shiira, Google Chrome)</td>
    <td valign="top">.webkit</td>
  </tr>
  <tr>
    <td>Chrome</td>
    <td valign="top">.chrome</td>
  </tr>
  <tr>
    <td>SRWare Iron</td>
    <td valign="top">.iron</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="300" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="150"><strong>Operating System</strong></td>
    <td width="124" valign="top"><strong>CSS Selector</strong></td>
  </tr>
  <tr>
    <td>Windows</td>
    <td valign="top">.win</td>
  </tr>
  <tr>
    <td>Mac</td>
    <td valign="top">.mac</td>
  </tr>
  <tr>
    <td>Linux</td>
    <td valign="top">.linux</td>
  </tr>
  <tr>
    <td>iPhone</td>
    <td valign="top">.iphone</td>
  </tr>
  <tr>
    <td>iPod Touch</td>
    <td valign="top">.ipod</td>
  </tr>
  <tr>
    <td>WebTV</td>
    <td valign="top">.webtv</td>
  </tr>
  <tr>
    <td>Mobile J2ME Devices (Opera mini)</td>
    <td valign="top">.mobile</td>
  </tr>
  <tr>
    <td>FreeBSD</td>
    <td valign="top">.freebsd</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="300" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td width="150"><strong>Extras</strong></td>
    <td width="124"><strong>CSS Selector</strong></td>
  </tr>
  <tr>
    <td>Javascript Enabled</td>
    <td>.js</td>
  </tr>
</table>


<h2>1. Browser Specific CSS Plugin On/Off</h2>
<p>If you don't want to use this plugin you can set this setting to "Off"</p>

<h2>2. Link To a Different Stylesheet (Optional)</h2>
<p>If you would like to use a separate stylesheet just for browser specific styles, you can specify the address of your stylesheet here. By default, you can specify browser specific styles in any stylesheet that is linked to your webpage. This option just allows you to keep your browser specific css separate from your other stylesheets. </p>

<h2>Save Button</h2>
<p>The "save" button will save your settings. Any time you make a change remember to click "Save."</

<h2>Example CSS</h2>
<p>This plugin comes with an example stylesheet that shows you how to target specific browsers. Look for the file named "example.css" in this plugin's folder.</p>

<h2>Credits</h2>
<p>This plugin uses the javascript written by <a href="http://rafael.adm.br" title="Rafael Lima">Rafael Lima</a>. No changes have been made to his code, and all the credit goes to him. Thanks, Rafael!
</p>



                <?php
        }
}}

//instantiate the class
if (class_exists('wp_browser_specific_css')) {
    $wp_browser_specific_css_var = new wp_browser_specific_css();
}
?>