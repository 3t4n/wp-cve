<?php
if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require TIMELINE_WP_DIR . 'includes/libraries/class-gamajo-template-loader.php';
}
/**
 *
 * Template loader for Timeline_WP.
 *
 * Only need to specify class properties here.
 *
 */
class Timeline_WP_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'timeline_wp';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'timeline_wp';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * In this case, `TIMELINE_WP_DIR` would be defined in the root plugin file as:
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_directory = TIMELINE_WP_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'includes/public/templates';
}