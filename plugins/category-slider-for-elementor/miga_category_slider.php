<?php
/**
* Plugin Name
*
* @package           PluginPackage
* @author            Michael Gangolf
* @copyright         2022 Michael Gangolf
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Category Slider for Elementor
* Plugin URI:        https://wordpress.org/plugins/category-slider-for-elementor/
* Description:       Creates a simple Swiper slider for all your categories
* Version:           1.2.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Michael Gangolf
* Author URI:        https://www.migaweb.de/
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       miga_category_slider
*/

use Elementor\Plugin;

add_action('init', static function () {
    if (! did_action('elementor/loaded')) {
        return false;
    }

    require_once(__DIR__ . '/widgets/CategorySlider.php');
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_category_slider());
});

function miga_category_slider_load_media()
{
    wp_enqueue_media();
}


  function miga_category_slider_add_category_image($taxonomy) { ?>
   <div class="form-field term-group">
     <label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
     <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
     <div id="category-image-wrapper"></div>
     <p>
       <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Add Image', 'hero-theme'); ?>" />
       <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Remove Image', 'hero-theme'); ?>" />
    </p>
   </div>
 <?php
 }

  function miga_category_slider_save_category_image($term_id, $tt_id)
  {
      if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
          $image = $_POST['category-image-id'];
          add_term_meta($term_id, 'category-image-id', $image, true);
      }
  }


  function miga_category_slider_update_category_image($term, $taxonomy) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
     </th>
     <td>
       <?php $image_id = get_term_meta($term -> term_id, 'category-image-id', true); ?>
       <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
       <div id="category-image-wrapper">
         <?php if ($image_id) { ?>
           <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
         <?php } ?>
       </div>
       <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Add Image', 'hero-theme'); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Remove Image', 'hero-theme'); ?>" />
       </p>
     </td>
   </tr>
 <?php
 }


  function miga_category_slider_updated_category_image($term_id, $tt_id)
  {
      if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
          $image = $_POST['category-image-id'];
          update_term_meta($term_id, 'category-image-id', $image);
      } else {
          update_term_meta($term_id, 'category-image-id', '');
      }
  }


  function miga_category_slider_add_script() { ?>
   <script>
     jQuery(document).ready( function($) {
       function ct_media_upload(button_class) {
         var _custom_media = true,
         _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if ( _custom_media ) {
               $('#category-image-id').val(attachment.id);
               $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open(button);
         return false;
       });
     }
     ct_media_upload('.ct_tax_media_button.button');
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#category-image-id').val('');
       $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#category-image-wrapper').html('');
         }
       }
     });
   });
 </script>
 <?php }

 function miga_category_slider_add_col1()
 {
     ?>
     <div class="form-field">
         <label for="term_meta[color_start]">Color start:</label>
         <input type="text" name="term_meta[color_start]" id="term_meta[color_start]" value="">
     </div>
 <?php
 }

 function miga_category_slider_add_col2()
 {
     ?>
     <div class="form-field">
         <label for="term_meta[color_end]">Color end:</label>
         <input type="text" name="term_meta[color_end]" id="term_meta[color_end]" value="">
     </div>
 <?php
 }

 add_action('category_edit_form_fields', 'miga_category_slider_edit_col1', 10, 2);
 add_action('category_edit_form_fields', 'miga_category_slider_edit_col2', 10, 2);

 function miga_category_slider_edit_col1($term)
 {

     // put the term ID into a variable
     $t_id = $term->term_id;

     // retrieve the existing value(s) for this meta field. This returns an array
     $term_meta = get_option("taxonomy_$t_id");

     $val = $term_meta['color_start'] ? esc_attr($term_meta['color_start']) : ''; ?>
     <tr class="form-field">
     <th scope="row" valign="top"><label for="term_meta[color_start]">Color start</label></th>
         <td>
             <input type="text" name="term_meta[color_start]" id="term_meta[color_start]" value="<?=$val?>">
         </td>
     </tr>
 <?php
 }

 function miga_category_slider_edit_col2($term)
 {

     // put the term ID into a variable
     $t_id = $term->term_id;

     // retrieve the existing value(s) for this meta field. This returns an array
     $term_meta = get_option("taxonomy_$t_id");
     $val = $term_meta['color_end'] ? esc_attr($term_meta['color_end']) : ''; ?>
     <tr class="form-field">
     <th scope="row" valign="top"><label for="term_meta[color_end]">Color start</label></th>
         <td>
             <input type="text" name="term_meta[color_end]" id="term_meta[color_end]" value="<?=$val?>">
         </td>
     </tr>
 <?php
 }

 function miga_category_slider_save_col($term_id)
 {
     if (isset($_POST['term_meta'])) {
         $t_id = $term_id;
         $term_meta = get_option("taxonomy_$t_id");
         $cat_keys = array_keys($_POST['term_meta']);
         foreach ($cat_keys as $key) {
             if (isset($_POST['term_meta'][$key])) {
                 $term_meta[$key] = $_POST['term_meta'][$key];
             }
         }
         update_option("taxonomy_$t_id", $term_meta);
     }
 }

add_action('edited_category', 'miga_category_slider_save_col', 10, 2);
add_action('created_category', 'miga_category_slider_save_col', 10, 2);
add_action('category_add_form_fields', 'miga_category_slider_add_col1', 10, 2);
add_action('category_add_form_fields', 'miga_category_slider_add_col2', 10, 2);
add_action('category_add_form_fields', 'miga_category_slider_add_category_image', 10, 2);
add_action('created_category', 'miga_category_slider_save_category_image', 10, 2);
add_action('category_edit_form_fields', 'miga_category_slider_update_category_image', 10, 2);
add_action('edited_category', 'miga_category_slider_updated_category_image', 10, 2);
add_action('admin_enqueue_scripts', 'miga_category_slider_load_media');
add_action('admin_footer', 'miga_category_slider_add_script');
