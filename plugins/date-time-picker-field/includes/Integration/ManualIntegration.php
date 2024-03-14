<?php

/**
 * 
 * Manual Integration.
 * This class is used to manually integrate the plugin with other plugins.
 * 
 * @package date-time-picker-field
 * @author InputWP <support@inputwp.com>
 * @link https://www.inputwp.com InputWP
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * 
 */

namespace CMoreira\Plugins\DateTimePicker\Integration;

use \WP_Query as WP_Query;

/**
 * Manual Intgegration helper class with forms
 */
class ManualIntegration {

  public function set_class($selector) {

    $meta = get_option( 'dtpicker' );
    if (is_array($meta)) {
      $meta['selector'] = $selector;
    }

    update_option( 'dtpicker', $meta );
  }
}
