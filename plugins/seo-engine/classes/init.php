<?php

if ( class_exists( 'MeowPro_SeoEngineCore' ) && class_exists( 'Meow_SeoEngineCore' ) ) {
	function seo_engine_thanks_admin_notices() {
		echo '<div class="error"><p>' . __( 'Thanks for installing the Pro version of SEO Kiss :) However, the free version is still enabled. Please disable or uninstall it.', 'media-cleaner' ) . '</p></div>';
	}
 
	add_action( 'admin_notices', 'seo_engine_thanks_admin_notices' );
	return;
}

spl_autoload_register(function ( $class ) {
  try {
    $necessary = true;
    $file = null;
    if ( strpos( $class, 'Meow_SeoEngine' ) !== false ) {
      $file = SEOENGINE_PATH . '/classes/' . str_replace( 'meow_seoengine', '', strtolower( $class ) ) . '.php';
    }
    else if ( strpos( $class, 'MeowCommon_' ) !== false ) {
      $file = SEOENGINE_PATH . '/common/' . str_replace( 'meowcommon_', '', strtolower( $class ) ) . '.php';
    }
    else if ( strpos( $class, 'MeowCommonPro_' ) !== false ) {
      $file = SEOENGINE_PATH . '/common/premium/' . _str_replace( 'meowcommonpro_', '', strtolower( $class ) ) . '.php';
    }
    else if ( strpos( $class, 'MeowPro_SeoEngine' ) !== false ) {
      $necessary = false;
      $file = SEOENGINE_PATH . '/premium/' . str_replace( 'meowpro_seoengine', '', strtolower( $class ) ) . '.php';
    }
    else if ( strpos( $class, 'Meow_Modules' ) !== false ) {
      $file = SEOENGINE_PATH . '/classes/modules/' . str_replace( 'meow_modules_seoengine_', '', strtolower( $class ) ) . '.php';
    }
    if ( $file ) {
      if ( !$necessary && !file_exists( $file ) ) {
        return;
      }
      require( $file );
    }
  }
  catch ( Exception $e ) {
    error_log( 'AI Engine: ' . $e->getMessage() );
  }
});

//require_once( SEOENGINE_PATH . '/classes/api.php');
require_once( SEOENGINE_PATH . '/common/helpers.php');


global $SeoEngineCore;
$SeoEngineCore = new Meow_SeoEngineCore();

?>