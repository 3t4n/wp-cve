<?php
if ( ! function_exists( 'plz_check_classic_editor' ) ) :
  function plz_check_classic_editor() {
    $gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );
    $block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

    if ( ! $gutenberg && ! $block_editor ) :
      return true;
    endif;

    if ( plz_is_classic_editor_plugin_active() ) {
      $editor_option = get_option( 'classic-editor-replace' );
      $classic_editor_active = array( 'classic' );

      return in_array( $editor_option, $classic_editor_active, true );
   }

   return false;
  }
endif;

if ( ! function_exists( 'plz_is_classic_editor_plugin_active' ) ) :
  function plz_is_classic_editor_plugin_active() {
    if ( ! function_exists( 'is_plugin_active' ) ) :
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    endif;

   if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) :
     return true;
   endif;

   return false;
  }
endif;

if ( ! function_exists( 'plz_register_button' ) ) :
  function plz_register_button( $buttons ) {
    if ( plz_check_classic_editor() ) :
      array_push( $buttons, 'plezi_form' );
    endif;

    return $buttons;
  }
endif;

if ( ! function_exists( 'plz_register_tinymce_javascript' ) ) :
  function plz_register_tinymce_javascript( $plugin_array ) {
    if ( plz_check_classic_editor() ) :
      $plugin_array['plezi_form'] = plugin_dir_url( __DIR__ ) . 'js/plz-mce-button.js';
    endif;

    return $plugin_array;
  }
endif;

if ( ! function_exists( 'plz_tinymce_plugin_add_locale' ) ) :
  function plz_tinymce_plugin_add_locale( $locales ) {
    if ( plz_check_classic_editor() ) :
      $locales['plezi_form'] = plugin_dir_path( __DIR__ ) . 'includes/plz-tinymce-plugin-langs.php';
    endif;

    return $locales;
  }
endif;
