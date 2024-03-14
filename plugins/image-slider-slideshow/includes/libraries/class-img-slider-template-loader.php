<?php

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require IMG_SLIDER_LIBRARIES . 'class-gamajo-template-loader.php';
}

/**
 *
 * Template loader for Image Slider.
 *
 * Only need to specify class properties here.
 *
 */
class Img_Slider_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'img-slider';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'image-slider';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * In this case, `IMG_SLIDER_PATH` would be defined in the root plugin file as:
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_directory = IMG_SLIDER_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'includes/public/templates';
}