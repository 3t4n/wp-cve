<?php
if (!defined('ABSPATH')) exit;  // if direct access


add_action('job_category_add_form_fields', 'add_form_fields', 12);
add_action('job_category_edit_form_fields', 'edit_form_fields', 12);

add_action('edited_job_category', 'save_update_taxonomy', 12);
add_action('create_job_category', 'save_update_taxonomy', 12);

add_action('job_tag_add_form_fields', 'add_form_fields', 12);
add_action('job_tag_edit_form_fields', 'edit_form_fields', 12);

add_action('edited_job_tag', 'save_update_taxonomy', 12);
add_action('create_job_tag', 'save_update_taxonomy', 12);


function save_update_taxonomy($term_id)
{






  $option_value = '';

  $job_bm_thumb = isset($_POST['job_bm_thumb']) ? $_POST['job_bm_thumb'] : '';

  if (is_array($job_bm_thumb)) {
    $job_bm_thumb = serialize($job_bm_thumb);
  }



  update_term_meta($term_id, 'job_bm_thumb', $job_bm_thumb);
}


function edit_form_fields($term)
{

  $term_id = $term->term_id;

?>
  <?php

  $option_value        = get_term_meta($term_id,  'job_bm_thumb', true);
  wp_enqueue_media();
  //var_dump($option);

  $field_name = 'job_bm_thumb';
  $id = 'job_bm_thumb';

  $media_url  = wp_get_attachment_url($option_value);
  $media_type  = get_post_mime_type($option_value);
  $media_title = get_the_title($option_value);
  $media_url = !empty($media_url) ? $media_url : '';

  ?>
  <tr class="form-field">
    <th scope="row" valign="top">
      <label for="<?php //echo $option['id']; 
                  ?>">
        <?php echo __('Thumbnail', 'job-board-manager') ?>

      </label>
    </th>
    <td>


      <div id="field-wrapper-<?php echo $id; ?>" class=" field-wrapper field-media-wrapper
            field-media-wrapper-<?php echo $id; ?>">
        <div class='media_preview' style='width: 150px;margin-bottom: 10px;background: #eee;padding: 5px;    text-align: center;'>
          <?php

          if ("audio/mpeg" == $media_type) {
          ?>
            <div id='media_preview_$id' class='dashicons dashicons-format-audio' style='font-size: 70px;display: inline;'></div>
            <div><?php echo $media_title; ?></div>
          <?php
          } else {
          ?>
            <img id='media_preview_<?php echo $id; ?>' src='<?php echo $media_url; ?>' style='width:100%' />
          <?php
          }
          ?>
        </div>
        <input type='hidden' name='<?php echo $field_name; ?>' id='media_input_<?php echo $id; ?>' value='<?php echo $option_value; ?>' />
        <div class='button upload' id='media_upload_<?php echo $id; ?>'><?php echo __('Upload', 'pickplugins-options-framework'); ?></div>
        <div class='button clear' id='media_clear_<?php echo $id; ?>'><?php echo __('Clear', 'pickplugins-options-framework'); ?></div>
        <div class="error-mgs"></div>
      </div>

      <script>
        jQuery(document).ready(function($) {
          $('#media_upload_<?php echo $id; ?>').click(function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            wp.media.editor.send.attachment = function(props, attachment) {
              $('#media_preview_<?php echo $id; ?>').attr('src', attachment.url);
              $('#media_input_<?php echo $id; ?>').val(attachment.id);
              wp.media.editor.send.attachment = send_attachment_bkp;
            }
            wp.media.editor.open($(this));
            return false;
          });
          $('#media_clear_<?php echo $id; ?>').click(function() {
            $('#media_input_<?php echo $id; ?>').val('');
            $('#media_preview_<?php echo $id; ?>').attr('src', '');
          })

        });
      </script>

      <p class="description"> <?php echo __('Set the thumbnail', 'job-board-manager') ?>
      </p>
    </td>
  </tr>
<?php


}


function add_form_fields($term)
{



  wp_enqueue_media();
  //var_dump($option);

  $field_name = 'job_bm_thumb';
  $id = 'job_bm_thumb';

  $media_type = '';


?>
  <?php


  ?>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="<?php //echo $option['id']; 
                                              ?>">
        <?php echo __('Thumbnail', 'job-board-manager') ?>
      </label></th>
    <td>


      <div id="field-wrapper-<?php echo $id; ?>" class=" field-wrapper field-media-wrapper
            field-media-wrapper-<?php echo $id; ?>">
        <div class='media_preview' style='width: 150px;margin-bottom: 10px;background: #eee;padding: 5px;    text-align: center;'>
          <?php

          if ("audio/mpeg" == $media_type) {
          ?>
            <div id='media_preview_$id' class='dashicons dashicons-format-audio' style='font-size: 70px;display: inline;'></div>
            <div></div>
          <?php
          } else {
          ?>
            <img id='media_preview_<?php echo $id; ?>' src='' style='width:100%' />
          <?php
          }
          ?>
        </div>
        <input type='hidden' name='<?php echo $field_name; ?>' id='media_input_<?php echo $id; ?>' value='' />
        <div class='button upload' id='media_upload_<?php echo $id; ?>'><?php echo __('Upload', 'pickplugins-options-framework'); ?></div>
        <div class='button clear' id='media_clear_<?php echo $id; ?>'><?php echo __('Clear', 'pickplugins-options-framework'); ?></div>
        <div class="error-mgs"></div>
      </div>

      <script>
        jQuery(document).ready(function($) {
          $('#media_upload_<?php echo $id; ?>').click(function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            wp.media.editor.send.attachment = function(props, attachment) {
              $('#media_preview_<?php echo $id; ?>').attr('src', attachment.url);
              $('#media_input_<?php echo $id; ?>').val(attachment.id);
              wp.media.editor.send.attachment = send_attachment_bkp;
            }
            wp.media.editor.open($(this));
            return false;
          });
          $('#media_clear_<?php echo $id; ?>').click(function() {
            $('#media_input_<?php echo $id; ?>').val('');
            $('#media_preview_<?php echo $id; ?>').attr('src', '');
          })

        });
      </script>

      <p class="description"><?php echo __('Set the thumbnail', 'job-board-manager') ?></p>
    </td>
  </tr>
<?php


}
