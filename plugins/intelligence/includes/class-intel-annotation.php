<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

include_once INTEL_DIR . 'includes/intel.annotation.php';

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Annotation extends Intel_Entity {


  public function label() {
    return intel_annotation_format_summary($this->message);
  }

  public function uri() {
    return 'annotation/' . $this->aid;
  }

}
