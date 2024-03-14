<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
 * Plugin Name:       Podamibe Simple Footer Widget Area
 * Plugin URI:        http://podamibenepal.com/wordpress-plugins/
 * Description:       Add footer areas according to your requirement and design or develop your own footer for your website, for theme developement or for an existing theme. It helps you create footer that includes website informations or organisation information along with copyright information.
 * Version:           2.0.8
 * Author:            Podamibe Nepal
 * Author URI:        http://podamibenepal.com/
 * License:           GPLv2 or later
 * Domain Path:       /languages/
 * Text Domain:       pn-sfwarea
 * Requires at least: 2.0.7
 * Tested up to:      5.0
 *
 * @package  Podamibe Simple Footer Widget Area
 * @author   Podamibe Nepal
 * @category Core
 */

  define('SFWA_URL', plugins_url('', __FILE__));
  define('SFWA_DIR', plugin_dir_path(__FILE__));
  define('SFWA_VERSION','2.0.8');
  define('SFWA_widget_DIR', SFWA_DIR . 'widgets/');
  !define('SFWA_TEXT_DOMAIN','pn-sfwarea')?define('SFWA_TEXT_DOMAIN','pn-sfwarea'):NULL;

  /* Widgets */
  require SFWA_widget_DIR . 'sfwa-footer-widget.php';
  require SFWA_widget_DIR . 'sfwa-button-widget.php';
  require SFWA_widget_DIR . 'sfwa-social-widget.php';
  require SFWA_widget_DIR . 'sfwa-ads.php';
  require SFWA_widget_DIR . 'sfwa-contact.php';
  require SFWA_widget_DIR . 'sfwa-credential.php';
  require SFWA_widget_DIR . 'sfwa-google-map-widget.php';


  class SFWA_plugin {
    
    function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action( 'widgets_init', array($this, 'footer1') );
        add_shortcode( 'sfwafooter', array($this, 'results') );
        add_action('widgets_init',array($this, 'register_footerwidget'));
        add_action('admin_init', array($this, 'update_options'));
        add_action('after_setup_theme', array($this, 'after_themesetup'));
        add_action( 'wp_enqueue_scripts', array($this, 'sfwa_enqueue'));
        add_action( 'admin_enqueue_scripts', array($this, 'sfwa_backend_enqueue') );
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'action_links') );
        add_action('wp_head',array($this, 'sfwarea_hook_css'), 20);

        add_filter("plugin_row_meta", array($this, 'get_extra_meta_links'), 10, 4);
    }
    
    public function admin_menu() {
     add_options_page(__('SFWA Setting', 'SFWA_TEXT_DOMAIN'), __('SFWA Setting', SFWA_TEXT_DOMAIN), 'manage_options', 'sfwarea-settings', array($this, 'admin_page'));
 }

 public function admin_page() {
    require SFWA_DIR . 'settings.php';
}

public function footer1() {
    $sfwa_widget_setting = get_option('sfwa_widget_setting');
    $count = $sfwa_widget_setting['number_of_widgets_area'];
    for($i = 1; $i<=$count; $i++){
       register_sidebar( array(
          'name'          => __( 'SFWA Footer '.$i, SFWA_TEXT_DOMAIN ),
          'id'            => 'footer-'.$i,
          'before_widget' => '<aside id="%1$s" class="widget %2$s">',
          'after_widget'  => '</aside>',
          'before_title'  => '<h3 class="widget-title">',
          'after_title'   => '</h3>',
          ) );
   }
   if($sfwa_widget_setting['creditibility-footer'] == 'on'){
    register_sidebar( array(
        'name'          => __( 'SFWA Credibility Footer', SFWA_TEXT_DOMAIN ),
        'id'            => 'footer-credibility',
        'description'   => __('Custom footer section for copyright, trademarks, etc', SFWA_TEXT_DOMAIN),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
        ) );
}
}

public function register_footerwidget(){
    register_widget('sfwa_footer_Widget');
    register_widget('sfwa_button_Widget');
    register_widget('sfwa_social_Widget');
    register_widget('sfwa_ads');
    register_widget('sfwa_contact_widget');
    register_widget('sfwa_credential');
    register_widget('sfwa_google_map_Widget');
}

public function update_options(){
    if(isset($_POST['sfwa_submit'])){
        check_admin_referer('sfwa_page');
        
            //Options of setting section being saved
        $input_options['number_of_widgets_area'] = isset($_POST['number_of_widgets_area']) ? sanitize_text_field($_POST['number_of_widgets_area']) : '';
        $input_options['creditibility-footer'] = isset($_POST['creditibility-footer']) ? sanitize_text_field($_POST['creditibility-footer']) : '';
        $input_options['footer-hook'] = isset($_POST['footer-hook']) ? sanitize_text_field($_POST['footer-hook']) : '';
        update_option( 'sfwa_widget_setting', $input_options);
    }elseif(isset($_POST['sfwa_layout_submit'])){
        check_admin_referer('sfwa_layout');
        $sfwa_layout_setting = get_option('sfwa_layout_setting');
        
        
            //Options of layout section being saved
        $layout_options['title-color'] = isset($_POST['title-color']) ? sanitize_text_field($_POST['title-color']) : '';
        $layout_options['text-color'] = isset($_POST['text-color']) ? sanitize_text_field($_POST['text-color']) : '';
        $layout_options['anchor-color'] = isset($_POST['anchor-color']) ? sanitize_text_field($_POST['anchor-color']) : '';
        $layout_options['hover-anchor-color'] = isset($_POST['hover-anchor-color']) ? sanitize_text_field($_POST['hover-anchor-color']) : '';
        $layout_options['full_width_footer'] = isset($_POST['full_width_footer']) ? sanitize_text_field($_POST['full_width_footer']): '';
        
            //background styles
        $layout_options['footer-color'] = isset($_POST['footer-color']) ? sanitize_text_field($_POST['footer-color']) : '';
        $layout_options['footer_background'] = isset($_POST['footer_background']) ? sanitize_text_field($_POST['footer_background']) : '';
        $layout_options['credibility-footer-color'] = isset($_POST['credibility-footer-color']) ? sanitize_text_field($_POST['credibility-footer-color']) : '';
        $layout_options['credit_background'] = isset($_POST['credit_background']) ? sanitize_text_field($_POST['credit_background']) : '';
        
            //border styles
        $layout_options['footer-border-color'] = isset($_POST['footer-border-color']) ? sanitize_text_field($_POST['footer-border-color']) : '';
        $layout_options['credibility-border-color'] = isset($_POST['credibility-border-color']) ? sanitize_text_field($_POST['credibility-border-color']) : '';
        $layout_options['footer_border_style'] = isset($_POST['footer_border_style']) ? sanitize_text_field($_POST['footer_border_style']): '';
        $layout_options['credibility_border_style'] = isset($_POST['credibility_border_style']) ? sanitize_text_field($_POST['credibility_border_style']): '';
        $layout_options['footer-border-radius'] = isset($_POST['footer-border-radius']) ? sanitize_text_field($_POST['footer-border-radius']): '';
        $layout_options['credibility-border-radius'] = isset($_POST['credibility-border-radius']) ? sanitize_text_field($_POST['credibility-border-radius']): '';
            //Margin styles
        foreach($_POST['margin'] as $key=>$margin){
            if(is_numeric($margin) || empty($margin)){
                $layout_options[$key] = sanitize_text_field($margin);
            }else{
                $layout_options[$key] = $sfwa_layout_setting[$key];
            }
        }
        foreach($_POST['border'] as $key1=>$border){
            if(is_numeric($border) || empty($border)){
                $layout_options[$key1] = sanitize_text_field($border);
            }else{
                $layout_options[$key1] = $sfwa_layout_setting[$key1];
            }
        }
        foreach($_POST['padding'] as $key2=>$padding){
            if(is_numeric($padding) || empty($padding) ){
                $layout_options[$key2] = sanitize_text_field($padding);
            }else{
                $layout_options[$key2] = $sfwa_layout_setting[$key2];
            }
        }
        update_option( 'sfwa_layout_setting', $layout_options);
    }else{
        return false;
    }
}
public function after_themesetup(){
    $hook_or_not = get_option('sfwa_widget_setting');
    $hook_or_not = $hook_or_not['footer-hook'];
    if($hook_or_not == 'on'){
        
    }else{
        add_action('wp_footer', array($this, 'results'));
    }
}
public function sfwa_enqueue() {
   wp_enqueue_style( 'sfwa_cdn_fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), SFWA_VERSION );
   wp_enqueue_style( 'sfwa_style', SFWA_URL . '/assets/css/sfwa.css', array(), SFWA_VERSION );
}
function sfwa_backend_enqueue(){
    global $pagenow;
    if( is_admin() ) {
            // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' );
            // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'assets/js/custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
    if($pagenow == 'widgets.php'){
        wp_enqueue_media();
        wp_enqueue_script( 'media-uploader', plugins_url( 'assets/js/media-uploader.js', __FILE__ ), array(), false, true ); 
        wp_enqueue_style( 'sfwa_style', SFWA_URL . '/assets/css/sfwa-admin.css', array(), SFWA_VERSION );
    }
    if ( $pagenow == 'options-general.php' && $_GET['page'] == 'sfwarea-settings'){ 
        wp_enqueue_style( 'sfwa_style', SFWA_URL . '/assets/css/sfwa-admin.css', array(), SFWA_VERSION );
        wp_enqueue_media();
        wp_enqueue_script( 'media-uploader', plugins_url( 'assets/js/media-uploader.js', __FILE__ ), array(), false, true ); 
    }
}
public function results($atts){
$sfwa_widget_setting = get_option('sfwa_widget_setting');
$sfwa_layout_setting = get_option('sfwa_layout_setting');
$count = $sfwa_widget_setting['number_of_widgets_area'];
$widths = array( 'contained', 'fullwidth');
$container = 'sfwa_'.$widths[0];
foreach($widths as $width){
    if($sfwa_layout_setting['full_width_footer']==$width ){
        $container = 'sfwa_'.$width;
    }
}
if($count == 1){
$class_name = 'sfwa_grid_one';
}elseif($count == 2){
    $class_name = 'sfwa_grid_two';
}
elseif($count == 3){
    $class_name = 'sfwa_grid_three';
}
elseif($count == 4){
    $class_name = 'sfwa_grid_four';
}
elseif($count == 5){
    $class_name = 'sfwa_grid_five';
}
elseif($count == 6){
    $class_name = 'sfwa_grid_six';
}
elseif($count == 7){
    $class_name = 'sfwa_grid_7';
}
else{
    $class_name = '';
}

echo '<footer id="sfwa_footer" class="'.$class_name.'">';
echo '<div class="footer-information">';
echo '<div class="'.$container.'">';
echo '<div class="sfwa_row">';
for( $i = 1; $i <= $count; $i++ ){
    if ( is_active_sidebar( 'footer-'. $i ) ){
      echo '<div class="sfwa_footer_area">';
      dynamic_sidebar( 'footer-'. $i );
      echo '</div>';
    }else{
        echo '<div class="sfwa_footer_area">';
        echo '<aside class="widget"><h3 class="widget-title">';
        echo esc_html_e('Widget Area '.$i.'', SFWA_TEXT_DOMAIN);
        echo '</h3>';
        echo esc_html_e('Add some widgets from admin panel', SFWA_TEXT_DOMAIN);;
        echo '</aside>';
        echo '</div>';
  }
}
echo '</div>';
echo '</div>';
echo '</div>';
if ( is_active_sidebar( 'footer-credibility' ) ){
    echo '<div class="footer-creditibility">';
    echo '<div class="'.$container.'">';
    echo '<div class="sfwa_row">';
    echo '<div class="sfwa_footer_area">';
    dynamic_sidebar( 'footer-credibility');
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}else{
    if($sfwa_widget_setting['creditibility-footer'] == 'on'){
        echo '<div class="footer-creditibility">';
        echo '<div class="'.$container.'">';
        echo '<div class="sfwa_row">';
        echo '<div class="sfwa_footer_area">';
        echo '<aside class="widget"><h3 class="widget-title">';
        echo esc_html_e('Credential Widget Area', SFWA_TEXT_DOMAIN);
        echo '</h3>';
        echo '<div style="padding:10px 0px;text-align:center;">Copyright 2017 | All Rights Reserved | Powered by ';
        echo '<a href="http://wordpress.org/" target="_blank">';
        echo esc_html_e('Wordpress', SFWA_TEXT_DOMAIN);
        echo '</a> | ';
        echo '<a href="http://podamibenepal.com" target="_blank">';
        echo esc_html_e('Podamibenepal', SFWA_TEXT_DOMAIN);
        echo '</a></div>';
        echo '</aside>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
echo '</footer>';
}
public function sfwarea_hook_css() {
    $sfwa_layout_setting = get_option('sfwa_layout_setting');
    
    $output = '<style type="text/css" id="sfwarea-plugins-css">';
    if($sfwa_layout_setting["footer-color"] || $sfwa_layout_setting["footer_background"]){
        $output .= '.footer-information { ';
        if($sfwa_layout_setting["footer-color"]){
            $output .= 'background-color : '.$sfwa_layout_setting["footer-color"].';';
        }
        if($sfwa_layout_setting["footer_background"]){
            $output .= 'background-image:url('.$sfwa_layout_setting["footer_background"].');background-repeat:no-repeat;';
        }
        $output .= '}';
    }
    if($sfwa_layout_setting["credibility-footer-color"] || $sfwa_layout_setting["credit_background"]){
        $output .= '.footer-creditibility{ background-color : '.$sfwa_layout_setting["credibility-footer-color"].';background-image:url('.$sfwa_layout_setting["credit_background"].') }';
        
        $output .= '.footer-creditibility { ';
        if($sfwa_layout_setting["credibility-footer-color"]){
            $output .= 'background-color : '.$sfwa_layout_setting["credibility-footer-color"].';';
        }
        if($sfwa_layout_setting["credit_background"]){
            $output .= 'background-image:url('.$sfwa_layout_setting["credit_background"].');background-repeat:no-repeat;';
        }
        $output .= '}';
    }
    if($sfwa_layout_setting["anchor-color"]){
        $output .= '#sfwa_footer a{ color : '.$sfwa_layout_setting["anchor-color"].' }';
    }
    if($sfwa_layout_setting["hover-anchor-color"]){
        $output .= '#sfwa_footer a:hover{ color : '.$sfwa_layout_setting["hover-anchor-color"].' }';
    }
    if($sfwa_layout_setting["title-color"]){
        $output .= '#sfwa_footer .widget .widget-title{ color : '.$sfwa_layout_setting["title-color"].' }';
    }
    if($sfwa_layout_setting["text-color"]){
        $output .= '#sfwa_footer{ color : '.$sfwa_layout_setting["text-color"].' }';
    }
    //design option of footer
    if($sfwa_layout_setting['footer-margin-top'] || $sfwa_layout_setting['footer-margin-right'] || $sfwa_layout_setting['footer-margin-bottom'] || $sfwa_layout_setting['footer-margin-left'] || $sfwa_layout_setting['footer-padding-top'] || $sfwa_layout_setting['footer-padding-right'] || $sfwa_layout_setting['footer-padding-bottom'] || $sfwa_layout_setting['footer-padding-left'] || $sfwa_layout_setting['footer-border-top'] || $sfwa_layout_setting['footer-border-right'] || $sfwa_layout_setting['footer-border-bottom'] || $sfwa_layout_setting['footer-border-left']){
        $output .= '.footer-information{ ';
        if($sfwa_layout_setting['footer-margin-top']){
            $output .= 'margin-top : '.$sfwa_layout_setting['footer-margin-top'].'px;';
        }
        if($sfwa_layout_setting['footer-margin-right']){
            $output .= 'margin-right : '.$sfwa_layout_setting['footer-margin-right'].'px; ';
        }
        if($sfwa_layout_setting['footer-margin-bottom']){
            $output .= 'margin-bottom : '.$sfwa_layout_setting['footer-margin-bottom'].'px; ';
        }
        if($sfwa_layout_setting['footer-margin-left']){
            $output .= 'margin-left : '.$sfwa_layout_setting['footer-margin-left'].'px; ';
        }
        if($sfwa_layout_setting['footer-padding-top']){
            $output .= 'padding-top : '.$sfwa_layout_setting['footer-padding-top'].'px;';
        }
        if($sfwa_layout_setting['footer-padding-right']){
            $output .= 'padding-right : '.$sfwa_layout_setting['footer-padding-right'].'px; ';
        }
        if($sfwa_layout_setting['footer-padding-bottom']){
            $output .= 'padding-bottom : '.$sfwa_layout_setting['footer-padding-bottom'].'px; ';
        }
        if($sfwa_layout_setting['footer-padding-left']){
            $output .= 'padding-left : '.$sfwa_layout_setting['footer-padding-left'].'px; ';
        }
        if($sfwa_layout_setting['footer-border-top']){
            $output .= 'border-top-width : '.$sfwa_layout_setting['footer-border-top'].'px;';
        }
        if($sfwa_layout_setting['footer-border-right']){
            $output .= 'border-right-width : '.$sfwa_layout_setting['footer-border-right'].'px; ';
        }
        if($sfwa_layout_setting['footer-border-bottom']){
            $output .= 'border-bottom-width : '.$sfwa_layout_setting['footer-border-bottom'].'px; ';
        }
        if($sfwa_layout_setting['footer-border-left']){
            $output .= 'border-left-width : '.$sfwa_layout_setting['footer-border-left'].'px; ';
        }
        if($sfwa_layout_setting['footer-border-top'] || $sfwa_layout_setting['footer-border-right'] || $sfwa_layout_setting['footer-border-bottom'] || $sfwa_layout_setting['footer-border-left']){
            if($sfwa_layout_setting['footer_border_style']){
                $output .= 'border-style:'.$sfwa_layout_setting['footer_border_style'].';';
            }
        }
        if($sfwa_layout_setting['footer-border-color']){
            $output .= 'border-color:'.$sfwa_layout_setting['footer-border-color'].';';
        }
        if($sfwa_layout_setting['footer-border-radius']){
            $output .= 'border-radius:'.$sfwa_layout_setting['footer-border-radius'].'px;';
        }
        
        $output .= '}';
    }
    
        //design option of credibility footer
    if($sfwa_layout_setting['credibility-margin-top'] || $sfwa_layout_setting['credibility-margin-right'] || $sfwa_layout_setting['credibility-margin-bottom'] || $sfwa_layout_setting['credibility-margin-left'] || $sfwa_layout_setting['credibility-padding-top'] || $sfwa_layout_setting['credibility-padding-right'] || $sfwa_layout_setting['credibility-padding-bottom'] || $sfwa_layout_setting['credibility-padding-left'] || $sfwa_layout_setting['credibility-border-top'] || $sfwa_layout_setting['credibility-border-right'] || $sfwa_layout_setting['credibility-border-bottom'] || $sfwa_layout_setting['credibility-border-left']){
        $output .= '.footer-creditibility{ ';
        if($sfwa_layout_setting['credibility-margin-top']){
            $output .= 'margin-top : '.$sfwa_layout_setting['credibility-margin-top'].'px;';
        }
        if($sfwa_layout_setting['credibility-margin-right']){
            $output .= 'margin-right : '.$sfwa_layout_setting['credibility-margin-right'].'px; ';
        }
        if($sfwa_layout_setting['credibility-margin-bottom']){
            $output .= 'margin-bottom : '.$sfwa_layout_setting['credibility-margin-bottom'].'px; ';
        }
        if($sfwa_layout_setting['credibility-margin-left']){
            $output .= 'margin-left : '.$sfwa_layout_setting['credibility-margin-left'].'px; ';
        }
        if($sfwa_layout_setting['credibility-padding-top']){
            $output .= 'padding-top : '.$sfwa_layout_setting['credibility-padding-top'].'px;';
        }
        if($sfwa_layout_setting['credibility-padding-right']){
            $output .= 'padding-right : '.$sfwa_layout_setting['credibility-padding-right'].'px; ';
        }
        if($sfwa_layout_setting['credibility-padding-bottom']){
            $output .= 'padding-bottom : '.$sfwa_layout_setting['credibility-padding-bottom'].'px; ';
        }
        if($sfwa_layout_setting['credibility-padding-left']){
            $output .= 'padding-left : '.$sfwa_layout_setting['credibility-padding-left'].'px; ';
        }
        if($sfwa_layout_setting['credibility-border-top']){
            $output .= 'border-top-width : '.$sfwa_layout_setting['credibility-border-top'].'px;';
        }
        if($sfwa_layout_setting['credibility-border-right']){
            $output .= 'border-right-width : '.$sfwa_layout_setting['credibility-border-right'].'px; ';
        }
        if($sfwa_layout_setting['credibility-border-bottom']){
            $output .= 'border-bottom-width : '.$sfwa_layout_setting['credibility-border-bottom'].'px; ';
        }
        if($sfwa_layout_setting['credibility-border-left']){
            $output .= 'border-left-width : '.$sfwa_layout_setting['credibility-border-left'].'px; ';
        }
        if($sfwa_layout_setting['credibility-border-top'] || $sfwa_layout_setting['credibility-border-right'] || $sfwa_layout_setting['credibility-border-bottom'] || $sfwa_layout_setting['credibility-border-left']){
            if($sfwa_layout_setting['credibility_border_style']){
                $output .= 'border-style:'.$sfwa_layout_setting['credibility_border_style'].';';
            }
        }
        if($sfwa_layout_setting['credibility-border-color']){
            $output .= 'border-color:'.$sfwa_layout_setting['credibility-border-color'].';';
        }
        if($sfwa_layout_setting['credibility-border-radius']){
            $output .= 'border-radius:'.$sfwa_layout_setting['credibility-border-radius'].'px;';
        }
        
        $output .= '}';
    }
    $output .= '</style>';
    echo $output;
}
public function action_links( $links ) {
 $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=sfwarea-settings') ) .'">'.__('Settings', SFWA_TEXT_DOMAIN).'</a>';
 $links[] = '<a href="http://podamibenepal.com/wordpress-plugins/" target="_blank">'.__('More plugins by Podamibe', SFWA_TEXT_DOMAIN).'</a>';
 return $links;
}


/**
 * Adds extra links to the plugin activation page
 */
public function get_extra_meta_links($meta, $file, $data, $status) {

    if (plugin_basename(__FILE__) == $file) {
        $meta[] = "<a href='http://shop.podamibenepal.com/forums/forum/support/' target='_blank'>" . __('Support', 'pn-sfwarea') . "</a>";
        $meta[] = "<a href='http://shop.podamibenepal.com/downloads/podamibe-simple-footer-widget-area/' target='_blank'>" . __('Documentation  ', 'pn-sfwarea') . "</a>";
        $meta[] = "<a href='https://wordpress.org/support/plugin/podamibe-simple-footer-widget-area/reviews#new-post' target='_blank' title='" . __('Leave a review', 'pn-sfwarea') . "'><i class='ml-stars'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg></i></a>";
    }
    return $meta;
}



}

$sfwa_plugin = new SFWA_plugin();

?>
