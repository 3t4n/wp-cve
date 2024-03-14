<?php
/*
Plugin Name: Fixed And Sticky Header
Plugin URI: https://arjunthakur2.wordpress.com
Description: This plugin will made your header sticky or fixed
Author: Arjun Thakur
Author URI: https://profiles.wordpress.org/arjunthakur
Version: 1.5
License: GPLv2 or later
Text Domain: Fixed And Sticky Header
*/
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('fixedORsticky_class')):
class fixedORsticky_class
{ /*AutoLoad Hooks*/
  public function __construct(){
   register_activation_hook(__FILE__, array(&$this, 'fixedORsticky_Activation'));
   add_action('admin_menu',array(&$this, 'optionsPage_fixed'));
   add_action('wp_head', array(&$this, 'fixedmyscriptfx'));
   add_action('wp_head', array(&$this, 'myPlugincss'));
   }

  /*Install Function and fixed css*/
  public function fixedORsticky_Activation(){
   $plugindefaultstyle = array(
   'default_width_fixed' => '100%',
   'default_padding_fixed' => '0 0',
   'default_margin_fixed' => '0 auto',
   'default_scroll_fixed' => '100',);
   $mypluginoption_fx = get_option('pluginoptions_fx');
   update_option('pluginoptions_fx', $plugindefaultstyle);
   }

  /*Plugin on menu and on Title*/
  public function optionsPage_fixed(){
   add_options_page('Plugin Settings', 'Fixed Header', 'manage_options', 'myplugin_setting',array(&$this, 'myplugin_setting'));
  }

  /*Fixed Checking for userRole*/
  public function myplugin_setting(){
   if(is_admin()): include('dashboard-form.php');
   endif;
  }

  /*My Scripts*/
  public function fixedmyscriptfx(){ $myplugins_options = get_option("pluginoptions_fx"); ?>
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script type="text/javascript"> 
      var fixed_header_class   = '<?php echo $myplugins_options["class-addfixed-fx"]; ?>';
      var fixed_header_scroll   = '<?php echo $myplugins_options["fixed-scroll-fx"]; ?>';
      jQuery(window).scroll(function(){           
        if(jQuery(document).scrollTop() > fixed_header_scroll){
             jQuery(fixed_header_class).addClass("myfixedHeader");
           }else{
               jQuery(fixed_header_class).removeClass("myfixedHeader");	 
                }
   });</script> <?php
   } 

  
  /*Plugin css*/
   public function myPlugincss() {
    $myplugins_options = get_option("pluginoptions_fx");?><style type="text/css">
    .myfixedHeader{background-color: <?php echo $myplugins_options["class-addbackgroundcolor-fx"]; ?>!important;}
    .myfixedHeader, .myfixedHeader a { color: <?php echo $myplugins_options["class-textcolor-fx"]; ?>!important;}
	.myfixedHeader { height: <?php echo $myplugins_options["fixed-header-height-fx"]; ?>;}
	.myfixedHeader { padding: <?php echo $myplugins_options["fixed-header-padding-fx"]; ?>!important;}
    .myfixedHeader {margin: 0 auto !important; width:100% !important; position:fixed; z-index:99999; transition:all 0.7s ease; left:0; right:0; top:0; text-align:center !important; }
    <?php echo $myplugins_options["class-addfixed-fx"]; ?>{ transition:all 0.7s ease; }</style>	<?php }
   /*WP Url Redirect*/	
    }
    function myfixedurl($url){echo '<script>window.location.href="'.$url.'"</script>';}
new fixedORsticky_class;
endif;
?>