<?php

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require RESP_ACCORDION_SLIDER_LIBRARIES_PATH . 'class-gamajo-template-loader.php';
}

/**
 *
 * Template loader for Image Slider.
 *
 * Only need to specify class properties here.
 *
 */
class Resp_Accordion_Slider_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 1.0.1
   *
   * @var string
   */
  protected $filter_prefix = 'responsive-accordion-slider';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.1
   *
   * @var string
   */
  protected $theme_template_directory = 'responsive-accordion-slider';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * In this case, `RESP_ACCORDION_SLIDER_DIR_PATH` would be defined in the root plugin file as:
   *
   * @since 1.0.1
   *
   * @var string
   */
  protected $plugin_directory = RESP_ACCORDION_SLIDER_DIR_PATH;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * @since 1.0.1
   *
   * @var string
   */
  protected $plugin_template_directory = 'includes/public/templates';
}