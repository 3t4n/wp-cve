<?php 
/* 
Plugin Name: jQuery Dropdown Menu
Plugin URI: http://www.phpinterviewquestion.com/jquery-dropdown-menu-plugin/ 
Description: A plugin to create Jquery Drop Down Menu with fully customization.To show menu Add d<code>&lt;?php jquery_drop_down_menu('HOME') ?&gt;</code> on your </a></strong>.
Tags: dropdown, menu, css, css-dropdown, navigation, widget, dropdown-menu, customization, theme, jquery, template, multi-color, theme, responsive, mobile menu
Author: Sana Ullah
Version: 3.0
Author URI: http://www.phpinterviewquestion.com/
Copyright 2009 - phpinterviewquestion.com
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
if( ! defined( 'ABSPATH' ) ) {
die();}
//echo __FILE__;
define( 'JDDM_FILE', __FILE__ );
define('JDDM_VERSION', '2.5');

//  When activate
register_activation_hook( __FILE__, 'jquery_dropdown_install' );

function jquery_dropdown_install() {
add_option('home_link', '1');
add_option('include', '1');
add_option('fadein', '100');
add_option('fadeout', '100');
add_option('fadein1', '150');
add_option('fadeout1', '150');
add_option('sort_by', 'menu_order');
add_option('sort_order', 'ASC');
add_option('depth', '0');
update_option('home_link', 1);
update_option('pluginactive', 'yes');
update_option('include', 1);
update_option('fadein', 100);
update_option('fadeout', 100);
update_option('sort_by', 'menu_order');
update_option('sort_order', 'ASC');
update_option('depth', 0);
update_option('custom_menu', 0);
$jddm_options = get_option('jddm_options');
$jddm_options['responivennable']=1;
$jddm_options['jddm_theme']='blue-style.css';
update_option('jddm_options', $jddm_options);
update_option('custom_menu_value', "<li>menu1
<ul><li><a href='#'>submenu1</a></li>
<li><a href='#'> submenu2</a></li>
<li><a href='#'>submenu3</a></li></ul></li>");
update_option('custom_menu_include', "0");
}

// Install CSS and JS for website
add_action('wp_head', 'jquery_drop_down_menu_style');

//Install Admin Option
add_action('admin_menu', 'jquery_drop_down_adminpage');

function jquery_drop_down_adminpage(){
if( isset($_POST['action']) && $_POST['save']=='Save Changes' && $_GET['page']=='jquery_drop_down_menu' ){
$pages = $_POST['pageexclude'];
$count=1;
if(count($pages)) {
foreach ($pages as $pagg) {
if($count==1){
$exclude=$pagg;
}
else{$exclude.=",".$pagg;
}
$count++;
}}
update_option('exclude_pages', $exclude);
update_option('home_link', $_POST['home_link']);
update_option('include', $_POST['include']);
update_option('fadein', $_POST['fadein']);
update_option('fadeout', $_POST['fadeout']);
update_option('fadein1', $_POST['fadein1']);
update_option('jddm_theme', $_POST['jddm_theme']);
update_option('fadeout1', $_POST['fadeout1']);
update_option('sort_by', $_POST['sort_by']);
update_option('sort_order', $_POST['sort_order']);
update_option('depth', $_POST['depth']);
update_option('custom_menu', $_POST['custom_menu']);
update_option('custom_menu_value', $_POST['custom_menu_value']);
update_option('custom_menu_include', $_POST['custom_menu_include']);
update_option('jddm_options', $_POST['jddm_options']);
}
$jddm_options = get_option('jddm_options');
wp_enqueue_style( 'jddmcss' );
wp_enqueue_style( "jquery_dropdown-admin", plugins_url( '/css/jquery_dropdown-admin.css' , __FILE__ ) , false, "1.0", "all");
wp_enqueue_style( 'menustyle' );
wp_enqueue_style( "jquery_dropdown-menu", plugins_url( '/themes/'.$jddm_options['jddm_theme'] , __FILE__ ) , false, "1.0", "all");
wp_enqueue_script('down-menu-plugin', plugins_url('/js/noConflict.js', __FILE__), $deps = array('jquery'));
add_options_page('Menu Management', 'Dropdown Menu', 'edit_plugins', "jquery_drop_down_menu",'jquery_drop_down_menu_admin');
}

/****-----------Menu Style --------------*****/
function jquery_drop_down_menu_admin() {
$available_menustyle = array('None' => '',
'Black' => 'menu_style.css',
'Blue Style' => 'blue-style.css',
'Orange Menu' => 'orange-menu.css',
'Rounded Corners' => 'rounded-corners-newstyle.css',
'Bubble Menu' => 'bubble-menu.css',
'Flat Tabbed' => 'Flat-Tabbed-Menu.css',
'Indented Horizontal' => 'Indented-Horizontal-Menu-Bar.css',
);
$available_codemenutype = array(
'pages' => 'Pages',
'categories' => 'Categories',);
include_once("jddm_adminpage.php");
}
add_filter('widget_text','do_shortcode');
add_shortcode( 'jdd_menu_style', 'jquery_drop_down_menu' );
add_shortcode( 'jquery_drop_down_menu', 'jquery_drop_down_menu' );
wp_enqueue_script('down-menu-plugin', $src = WP_CONTENT_URL.'/plugins/jquery-drop-down-menu-plugin/js/noConflict.js', $deps = array('jquery'));

function jquery_drop_down_menu_style() {
ob_start();
$jddm_options = get_option('jddm_options');
$include = get_option('include');
if($jddm_options['effecttype']=='toggle'){
$Jquerycode ='noCon("#jquery-dropmenu li").hover(function(){
noCon(this).find("ul:first").stop(true,true).slideToggle("'.$jddm_options['speed'].'");
},
function(){
noCon(this).find("ul:first").stop(true,true).slideUp("'.$jddm_options['speed'].'");
});';
}
else{
$Jquerycode ='noCon("#jquery-dropmenu li").hover(function(){
noCon(this).find("ul:first").stop(true,true).fadeIn("'.$jddm_options['speed'].'");
},
function(){
noCon(this).find("ul:first").stop(true,true).fadeOut("'.$jddm_options['speed'].'");
});';
}
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect_Core;
if($detect->isMobile() && $jddm_options['responivennable'])
{
echo'
<style type="text/css" media="all">#jquery-dropmenu{display:none!important}</style>
<script type="text/javascript">jQuery(document).ready(function(a){jQuery(".jquerymobilenav").stellarNav({theme:"light",})});</script>
' ;
}
else
{
echo'
<script type="text/javascript">noCon(document).ready(function(){noCon("#jquery-dropmenu ul").css({display:"none"});noCon("#jquery-dropmenu li:has(ul)").addClass("parent");noCon("#jquery-dropmenu li > ul > a > span").text("");'.$Jquerycode.'});</script>
' ;
}
$data = ob_get_contents();
return $data;
}

function jddm_nav_filter( $args ){
$jddm_options = get_option('jddm_options');
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect_Core;
if($jddm_options['jddm_location_enabled']=="on" && $jddm_options['jddm_location']==$args['theme_location'] && !$detect->isMobile() )
{
$args['items_wrap'] = '<ul id="jquery-dropmenu" class="%2$s">%3$s</ul>';
$args['container_id'] = 'jquerymenuid';
/*$args['container_class'] = 'jquerymobilenav';*/
$args['fallback_cb'] = '';
$args['walker'] = '';
}
return $args;
}
add_filter('wp_nav_menu_args', 'jddm_nav_filter', 90, 1);

function jquery_drop_down_menu($home='Home') {
jquery_drop_down_menu_style();
$gdd_wp_url = get_bloginfo('wpurl') . "/";
$home_link = get_option('home_link');
$include = get_option('include');
$fadein = get_option('fadein');
$fadeout = get_option('fadeout');
$sort_by = get_option('sort_by');
$sort_order = get_option('sort_order');
$depth = get_option('depth');
$exclude_pages = get_option('exclude_pages');
$custom_menu = get_option('custom_menu');
$custom_menu_value = get_option('custom_menu_value');
$custom_menu_include = get_option('custom_menu_include');
$parameters='title_li=';
if($sort_by)
$parameters.='&sort_column='.$sort_by.'';
if($sort_order)
$parameters.='&sort_order='.$sort_order.'';
$parameters.='&depth='.$depth.'';
if($exclude_pages)
{
$parameters.='&exclude='.$exclude_pages.'';
}
echo '<ul id="jquery-dropmenu">';
if($home_link)
{
echo '<li><a href="'.$gdd_wp_url.'" title="'.$home.'">Home</a></li>';
}
if($custom_menu==1 && $custom_menu_include==1)
{
echo stripslashes($custom_menu_value);
}
wp_list_pages($parameters);
if($custom_menu==2 && $custom_menu_include==1)
{
echo stripslashes($custom_menu_value);
}
echo '</ul>';
}
add_action('wp_head', 'down_menu_plugin_scripts');

function down_menu_plugin_scripts() {
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect_Core;
$jddm_options = get_option('jddm_options');
// if we registered the style before:
if(!is_admin()){
echo "\n<link rel=\"stylesheet\" href=\"". plugins_url( '/css/font-awesome.min.css' , __FILE__ ) . "\" type=\"text/css\" />";
if($detect->isMobile() && $jddm_options['responivennable'])
{
echo "\n<link rel=\"stylesheet\" href=\"". plugins_url( '/mobilemenu/jquerymobilenav.min.css' , __FILE__ ) . "\" type=\"text/css\" />";
wp_enqueue_script('down-menu-plugin-stellarnav', plugins_url('/js/jquerymobilenav.min.js', __FILE__), $deps = array('jquery'));
}
else {
echo "\n<link rel=\"stylesheet\" href=\"". plugins_url( '/themes/'.$jddm_options['jddm_theme'] , __FILE__ ) . "\" type=\"text/css\" />";
}
if($jddm_options['customcss'])
echo'<style type="text/css" media="all">'.stripslashes($jddm_options['customcss']).'</style>';


}
}

function jquery_dropdown_add_settings_link($links) {
$settings_link = '<a href="options-general.php?page=jquery_drop_down_menu">Settings</a>';
array_push( $links, $settings_link );
return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'jquery_dropdown_add_settings_link' );
// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');
add_action('wp_footer', 'add_resposnivemenu',9);

function add_resposnivemenu() {
$jddm_options = get_option('jddm_options');
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect_Core;
if($jddm_options['jddm_location_enabled']=="on" && $jddm_options['responivennable'] && $detect->isMobile() )
wp_nav_menu( array('theme_location' => $jddm_options['jddm_location'],'menu_class' => 'nav navbar-nav','container_class' => 'jquerymobilenav'));
}