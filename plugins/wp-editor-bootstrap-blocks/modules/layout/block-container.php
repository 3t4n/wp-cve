<?php 
/**
 * Bootstrap Blocks for WP Editor Layout.
 *
 * @version 1.5.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2021-05-15
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'GUTENBERGBOOTSTRAP_VERSION' ) ) {
	exit;
}

register_block_type( 'gtb-bootstrap/container', array(
   'editor_script'   => 'gtb-bootstrap-editor',
   'editor_style'    => 'gtb-bootstrap-editor',
   'render_callback' => 'gtb_bootstrap_render_container',
   'category' => 'bootstrap'
) );


function gtb_bootstrap_render_container( $attributes, $content = '')
{
   $cls = 'container';
   $style = '';

   if (!empty($attributes['bgColor'])) { $style .='background-color: '.$attributes['bgColor'].';';}

   $container = '   <div class="'.$cls.'"'.(!empty($style)?' style="'.$style.'"':'').'>
   '.$content.'
   </div>
';

   return $container;
}
