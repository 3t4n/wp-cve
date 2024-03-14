<?php
    /*
		Plugin Name: Podčlánková inzerce
		Plugin URI: https://www.podclankovainzerce.cz
		Description: Podčlánková inzerce je jednoduchý plugin pro redakční systém WordPress, který umožňuje okamžitý prodej přímých odkazů pod jednotlivými články
		Author: WebDeal s.r.o.
		Version: 2.4.0
		Author URI: https://www.firma-webdeal.cz
	  */

    /*
    Copyright 2014 - 2022  WebDeal s.r.o.  (email : info@firma-webdeal.cz)

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

    if(!defined( 'ABSPATH' )) exit; // Exit if accessed directly

    global $pdckl_db_version, $pdckl_plugin_version;
    $pdckl_db_version = "2.4.0";
    $pdckl_plugin_version = $pdckl_db_version;

    define("PDCKL_ADMIN_LINK", "options-general.php?page=podclankova-inzerce");
    define("PDCKL_PLUGIN_DIR", plugin_dir_path(__FILE__));
    define("PDCKL_GATEWAY_LINK", plugins_url('gateway.php', __FILE__));
    define("PDCKL_HOMEPAGE", "https://www.podclankovainzerce.cz");

    add_action('plugins_loaded', 'pdckl_db_check');
    add_action('admin_head', 'pdckl_admin_head');
    add_action('admin_head', 'pdckl_header');
    add_action('admin_menu', 'pdckl_admin_menu');
    add_action('wp_head', 'pdckl_header');
    add_action('wp_footer', 'pdckl_footer');

    wp_enqueue_style('podclankova-inzerce', plugin_dir_url(__FILE__) . "assets/css/podclankova-inzerce.min.css", [], time());

    register_activation_hook(__FILE__, 'pdckl_update_v1_to_v2');
    register_activation_hook(__FILE__, 'pdckl_options_install');
    register_activation_hook(__FILE__, 'pdckl_db_install');
    register_uninstall_hook(__FILE__, 'pdckl_options_uninstall');
    register_uninstall_hook(__FILE__, 'pdckl_db_uninstall');

    if(get_option('WPLANG') == 'sk_SK') {
      require_once(dirname(__FILE__) . '/lang/sk.php');
    } else {
      require_once(dirname(__FILE__) . '/lang/cz.php');
    }
    require_once(dirname(__FILE__) . '/functions.php');

    function pdckl_content()
    {
        global $wpdb, $pdckl_lang;
        $spage = isset($_GET['s']) ? $_GET['s'] : null;
    ?>

        <div class="wrap">

           <?php require_once(dirname(__FILE__) . '/actions.php'); ?>

			     <h2><? echo $pdckl_lang['main_title']; ?></h2>
           <p class="desc_main"><?php echo $pdckl_lang['main_description']; ?></p>

           <ul class="pdckl_admin_menu">
              <li><a <?php if($spage == '') { echo 'id="pdckl_admin_menu_active"'; } ?> href="<?php echo wp_nonce_url(PDCKL_ADMIN_LINK); ?>"><i class="fa fa-cogs"></i> <?php echo $pdckl_lang['main_menu_settings']; ?></a></li>
              <li><a <?php if($spage == 'orders') { echo 'id="pdckl_admin_menu_active"'; } ?> href="<?php echo wp_nonce_url(PDCKL_ADMIN_LINK.'&s=orders'); ?>"><i class="fa fa-list"></i> <?php echo $pdckl_lang['main_menu_orders']; ?></a></li>
              <li><a <?php if($spage == 'css') { echo 'id="pdckl_admin_menu_active"'; } ?> href="<?php echo wp_nonce_url(PDCKL_ADMIN_LINK.'&s=css'); ?>"><i class="fa fa-edit"></i> <?php echo $pdckl_lang['main_menu_css']; ?></a></li>
              <li><a <?php if($spage == 'help') { echo 'id="pdckl_admin_menu_active"'; } ?> href="<?php echo wp_nonce_url(PDCKL_ADMIN_LINK.'&s=help'); ?>"><i class="fa fa-support"></i> <?php echo $pdckl_lang['main_menu_help']; ?></a></li>
           </ul>

           <?php
           switch(htmlspecialchars($spage))
           {
              case "orders":  require_once(dirname(__FILE__) . '/pages/orders.php');    break;
              case "css":     require_once(dirname(__FILE__) . '/pages/css.php');       break;
              case "help":    require_once(dirname(__FILE__) . '/pages/help.php');      break;
              default:        require_once(dirname(__FILE__) . '/pages/settings.php');  break;
           }
           ?>
        </div>
    <?php }
?>
