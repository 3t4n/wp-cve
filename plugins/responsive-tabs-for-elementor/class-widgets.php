<?php
/**
 * Widgets class.
 *
 * @category   Class
 * @package    ResponsiveTabsForElementor
 * @subpackage WordPress
 * @author
 * @copyright
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link
 * @since      7.0.0
 * php version 7.4.1
 */

namespace ResponsiveTabsForElementor;

// Security Note: Blocks direct access to the plugin PHP files.
use Elementor\Plugin;

defined('ABSPATH') || die();

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 7.0.0
 */
class Widgets
{

  /**
   * Instance
   *
   * @since  7.0.0
   * @access private
   * @static
   *
   * @var Plugin The single instance of the class.
   */
  private static $instance = null;

  /**
   * Instance
   *
   * Ensures only one instance of the class is loaded or can be loaded.
   *
   * @return Plugin An instance of the class.
   * @since  7.0.0
   * @access public
   *
   */
  public static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Include Widgets files
   *
   * Load widgets files
   *
   * @since  7.0.0
   * @access private
   */
  private function include_widgets_files()
  {
    require_once 'widgets/class-responsive-tabs-with-icons.php';
    require_once 'widgets/class-responsive-tabs-with-small-images.php';
    require_once 'widgets/class-responsive-tabs-with-big-image.php';
    require_once 'widgets/class-responsive-accordion.php';
    require_once 'widgets/class-responsive-simple-tabs-with-icons.php';
    require_once 'widgets/class-responsive-vertical-accordion.php';
    require_once 'widgets/class-responsive-testimonials-tabs.php';
    require_once 'widgets/class-responsive-accordion-with-counter.php';
  }

  /**
   * Register Widgets
   *
   * Register new Elementor widgets.
   *
   * @since  7.0.0
   * @access public
   */
  public function register_widgets()
  {
    // It's now safe to include Widgets files.
    $this->include_widgets_files();

    // Register the plugin widget classes.
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Tabs_With_Icons());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Tabs_With_Small_Images());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Tabs_With_Big_Image());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Accordion());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Simple_Tabs_With_Icons());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Vertical_Accordion());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Testimonials_Tabs());
    Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Responsive_Accordion_With_Counter());
  }


  /**
   *  Plugin class constructor
   *
   * Register plugin action hooks and filters
   *
   * @since  7.0.0
   * @access public
   */
  public function __construct()
  {
    // Register the widgets.
    add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
  }
}

// Instantiate the Widgets class.
Widgets::instance();
