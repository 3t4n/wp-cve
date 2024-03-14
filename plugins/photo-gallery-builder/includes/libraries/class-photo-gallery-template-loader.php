<?php

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  require PHOTO_GALLERY_BUILDER_LIBRARIES . 'class-gamajo-template-loader.php';
}

/**
 *
 * Template loader for Photo Gallery.
 *
 * Only need to specify class properties here.
 *
 */
class Photo_Gallery_Template_Loader extends Gamajo_Template_Loader {

  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'photo-gallery-builder';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'photo-gallery-builder';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * In this case, `PHOTO_GALLERY_PATH` would be defined in the root plugin file as:
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_directory = PHOTO_GALLERY_BUILDER_DIR;

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