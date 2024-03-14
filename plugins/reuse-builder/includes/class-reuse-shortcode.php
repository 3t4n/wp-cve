<?php
/**
 *
 */

namespace Reuse\Builder;

class Reuse_Builder_Shortcode {
    public function __construct() {
      $shortcode_templates = array(
        'image_grid',
        'post_content',
        'single_post_title',
        'post_thumbnail',
        'post_taxonomy',
        'post_meta',
        'column',
        'row',
        'post_title_link',
        'post_bundle_meta',
      );

      foreach( $shortcode_templates as $template ) {
        $this->require_shortcode_files( $template );
      }

    }
    // require shortcode files
    public function require_shortcode_files( $template ) {
        require_once( REUSE_BUILDER_SHORTCODE_PATH . $template . '.php' );
    }

}
