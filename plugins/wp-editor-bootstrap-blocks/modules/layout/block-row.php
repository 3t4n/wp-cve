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



register_block_type( 'gtb-bootstrap/row', array(
   'editor_script'   => 'gtb-bootstrap-editor',
   'editor_style'    => 'gtb-bootstrap-editor',
   'render_callback' => 'gtb_bootstrap_render_row',
   'category' => 'bootstrap'
) );


function gtb_bootstrap_render_row( $attributes, $content = '')
{
   $options = get_option('gtbbootstrap_options');
   $gridsize = !empty($options['gridsize'])?$options['gridsize']:12;
   $cols = substr_count($content,'%%|');
   $cols = $cols + substr_count($content,'|%%');
   $col = $cols > 0?floor($gridsize/$cols):$gridsize;
   if ($col < 1) $col =1;
   $content= str_replace(array('%%|','|%%'),array('','col-md-'.$col),$content);

   $cls = 'row';

   if (!empty($attributes['align'])):
      switch($attributes['align']):
         case 'left': $cls .= ' justify-content-start';break;
         case 'center': $cls .= ' justify-content-center';break;
         case 'right': $cls .= ' justify-content-end';break;
      endswitch;
   endif;


   return '<div class="'.trim($cls).'">
      '.$content.'
   </div>';
}

