<?php
/**
 * Plugin Name:       Masonry Image Gallery Block
 * Description:       <strong>Masonry Image Gallery</strong> is a custom <strong>Gutenberg Block</strong> built with <strong>Gutenberg Native Components</strong>. You can easily create an image gallery in Gutenberg Editor with this block.
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           2.2.0
 * Author:            Zakaria Binsaifullah
 * Author URI:        https://makegutenblock.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       masonry-image-gallery
 *
 * @package           @wordpress/create-block
 */

/**
 * @package Zero Configuration with @wordpress/create-block
 *  [migb] && [MIGB] ===> Prefix
 */

// Stop Direct Access
if (!defined("ABSPATH")) {
  exit();
}

require_once plugin_dir_path(__FILE__) . "admin/admin.php";

// app sero sdk 

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_mgb_masonry_image_gallery() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( 'b5b2274a-2896-409c-8504-b6f43b9d8a2a', 'Masonry Image Gallery Gutenberg Block', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_mgb_masonry_image_gallery();


/**
 * Blocks Final Class
 */

final class MIGB_BLOCKS_CLASS
{
  public function __construct()
  {
    // define constants
    $this->migb_define_constants();

    // load textdomain
    add_action("plugins_loaded", [$this, "migb_load_textdomain"]);

    // block initialization
    add_action("init", [$this, "migb_blocks_init"]);

    // enqueue block assets
    add_action("enqueue_block_assets", [$this, "migb_external_libraries"]);

    // admin page
    add_action("activated_plugin", [$this, "migb_user_redirecting"]);

    // // admin page after updating complete
    // add_action("upgrader_process_complete", [
    //   $this,
    //   "migb_user_redirecting_after_updating",
    // ]);
  }

  /**
   * Initialize the plugin
   */

  public static function init()
  {
    static $instance = false;
    if (!$instance) {
      $instance = new self();
    }
    return $instance;
  }

  /**
   * Textdomain Loader
   */
  public function migb_load_textdomain()
  {
    load_plugin_textdomain(
      "masonry-image-gallery",
      false,
      dirname(plugin_basename(__FILE__)) . "/languages/"
    );
  }

  // Admin Page Redirecting
  public function migb_user_redirecting($plugin)
  {
    if (plugin_basename(__FILE__) === $plugin) {
      wp_redirect(admin_url("tools.php?page=migb-gallery"));
      die();
    }
  }

  // Redirect to admin page after updating complete
  // public function migb_user_redirecting_after_updating(
  //   $upgrader_object,
  //   $options
  // ) {
  //   $our_plugin = plugin_basename(__FILE__);
  //   if ($options["action"] == "update" && $options["type"] == "plugin") {
  //     foreach ($options["plugins"] as $plugin) {
  //       if ($plugin == $our_plugin) {
  //         wp_redirect(admin_url("tools.php?page=migb-gallery"));
  //         die();
  //       }
  //     }
  //   }
  // }

  /**
   * Define the plugin constants
   */
  private function migb_define_constants()
  {
    define("MIGB_VERSION", "2.2.0");
    define("MIGB_URL", plugin_dir_url(__FILE__));
    define("MIGB_LIB_URL", MIGB_URL . "lib/");
  }

  // render inline css
  public function migb_render_inline_css($handle, $css)
  {
    wp_register_style($handle, false);
    wp_enqueue_style($handle);
    wp_add_inline_style($handle, $css);
  }

  /**
   * Blocks Registration
   */

  public function migb_register_block($name, $options = [])
  {
    register_block_type(__DIR__ . "/build/blocks/" . $name, $options);
  }

  /**
   * Blocks Initialization
   */
  public function migb_blocks_init()
  {
    // register single block
    $this->migb_register_block("masonry-gallery", [
      "render_callback" => [$this, "migb_render_block"],
    ]);
  }

  // render function
  public function migb_render_block($attributes, $content)
  {
    require_once __DIR__ . "/templates/migb.php";
    $handle = "migb-" . $attributes["galleryId"];
    $this->migb_render_inline_css($handle, migb_callback($attributes));
    return $content;
  }

  /**
   * Enqueue Block Assets
   */
  public function migb_external_libraries()
  {
    // admin css
    if (is_admin()) {
      wp_enqueue_style("migb-admin-editor", MIGB_URL . "admin/css/editor.css");
    }

    if (has_block("migb/masonry-gallery")) {
      // frontend css
      wp_enqueue_style(
        "migb-magnific-css",
        MIGB_LIB_URL . "css/magnific-popup.css",
        [],
        MIGB_VERSION,
        "all"
      );
      // enqueue JS
      wp_enqueue_script(
        "migb-magnific-popup",
        MIGB_LIB_URL . "js/jquery.magnific-popup.min.js",
        ["jquery"],
        MIGB_VERSION,
        true
      );
      wp_enqueue_script(
        "migb-lib",
        MIGB_LIB_URL . "js/lightbox.js",
        ["jquery"],
        MIGB_VERSION,
        true
      );
    }
  }
}

/**
 * Kickoff
 */

MIGB_BLOCKS_CLASS::init();
