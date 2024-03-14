<?php
/**
 * Plugin Name: NextGen Gallery Import to Photo Gallery
 * Plugin URI: http://web-dorado.com/products/wordpress-photo-gallery-plugin.html
 * Description: This addon integrates NextGen with Photo Gallery allowing to import images and related data from NextGen to use with Photo Gallery.
 * Version: 1.0.5
 * Author: WebDorado
 * Author URI: http://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('PGI_IMPORT_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('PGI_IMPORT_URL', plugins_url(plugin_basename(dirname(__FILE__))));

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Plugin menu.
function pgi_import_options_panel() {
  $pgi_import_page = add_menu_page(__('PG import', 'pgi'), __('PG import', 'pgi'), 'manage_options', 'import_pgi', 'photo_gallery_import', PGI_IMPORT_URL . '/images/icon-16x16.png');
  add_action('admin_print_scripts-' . $pgi_import_page, 'pgi_scripts');
}
add_action('admin_menu', 'pgi_import_options_panel'); 

function photo_gallery_import() {
  $page = "import_pgi" ;	
  require_once(PGI_IMPORT_DIR . '/admin/controllers/PGIController' . ucfirst(strtolower($page)) . '.php');
  $controller_class = 'PGIController' . ucfirst(strtolower($page));
  $controller = new $controller_class();
  $controller->execute();
}

// Plugin scripts.
function pgi_scripts() {
  wp_enqueue_script('pgi', PGI_IMPORT_URL . '/js/pgi.js', array(), '', true);
  wp_enqueue_script('jquery');
  wp_enqueue_style('pgi_main', PGI_IMPORT_URL . '/css/pgi_main.css');
  wp_localize_script('pgi', 'pgi_objectL10n', array(
    'pgi_checkbox_required'  =>  __('You must select at least one item.', 'pgi')
  ));
}
