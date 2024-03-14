<?php 
/**
 * Bootstrap Blocks for WP Editor Layout.
 *
 * @version 1.5.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2023-04-18
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'GUTENBERGBOOTSTRAP_VERSION' ) ) {
	exit;
}



register_block_type( 'gtb-bootstrap/column', array(
   'editor_script'   => 'gtb-bootstrap-editor',
   'editor_style'    => 'gtb-bootstrap-editor',

   'render_callback' => 'gtb_bootstrap_render_column',
   'category' => 'bootstrap'
) );


function gtb_bootstrap_render_column( $attributes, $content = '')
{
   $cls = '';
   $has_xs = false;
   $has_col = false;
   $has_hidden = false;
   foreach(array( 'sm', 'md', 'lg') as $s):
      if (!empty($attributes['size_'.$s]))
      {
         $has_col = true;
         if ($s == 'xs')
         {
            $has_xs = $s;
         }else{
            $cls.= ' col-'.$s.'-'.$attributes['size_'.$s];
         }
      }

      if (!empty($attributes['hidden_'.$s]))
      {
         $cls.= ($s==='xs'?'d-none':' d-'.$s.'-none');
         $has_hidden = true;
      }else if ($has_hidden)
      {
         $cls.= ($s==='xs'?'d-block':' d-'.$s.'-block');
      }

      if (!empty($attributes['order_'.$s]))
      {
         $cls.= ($s==='xs'? 'order-'.$attributes['order_'.$s] : ' order-'.$s.'-'.$attributes['order_'.$s]);
      }


   endforeach;

   if (!empty($attributes['className'])) $cls .= ' '.$attributes['className'];

   if($has_xs) $cls = 'col-'.$attributes['size_'.$has_xs].' '.$cls;

   if ($has_col):
         $cls = '%%| '.$cls;
   else:
      $cls = '|%% '.$cls;
   endif;

   $stle = !empty($attributes['bgColor'])?' style="background-color: '.$attributes['bgColor'].'"':'';

   return '<div class="'.$cls.'"'.$stle.'>
      '.$content.'
   </div>';
}

