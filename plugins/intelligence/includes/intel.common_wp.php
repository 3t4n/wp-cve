<?php

/**
 * @file
 * Common utility and helper functions specific to WP.
 *
 * The functions that are critical and need to be available even when serving
 * a cached page are instead located in bootstrap.inc.
 */

if ( !function_exists( 'intel_d' ) ) {
  function intel_d() {
    // check if user has access to this data
    static $access;
    if (!isset($access)) {
      $access = Intel_Df::user_access('debug intel');
    }
    if (!$access) {
      return;
    }

    static $kint_aliases;
    $_ = func_get_args();

    if (class_exists('Kint')) {
      if (!Kint::enabled()) {
        return '';
      }

      // add to static aliases so the function caller info translates
      if (empty($kint_aliases)) {
        $kint_aliases = Kint::$aliases;
        $kint_aliases['functions'][] = 'intel_d';
        $kint_aliases['functions'][] = 'intel_dd';
        $kint_aliases['functions'][] = 'intel_print_var';
        Kint::$aliases = $kint_aliases;
      }

      if ( class_exists( 'Debug_Bar' ) ) {
        ob_start( 'kint_debug_ob' );
        echo call_user_func_array( array( 'Kint', 'dump' ), $_ );
        ob_end_flush();
      } else {
        $output = call_user_func_array( array( 'Kint', 'dump' ), $_ );
        if (intel()->is_intel_admin_page()) {
          Intel_Df::drupal_set_message($output);
        }
        else {
          return $output;
        }
      }
    }
    else {
      if (intel()->is_intel_admin_page()) {
        Intel_Df::drupal_set_message(json_encode($_[0]));
      }
      else {
        print json_encode($_[0]);
      }
    }
  }
}