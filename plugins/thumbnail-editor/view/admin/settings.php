<table width="100%" border="0" class="ap-table">
  <tr>
    <td colspan="2">
    <div class="ap-tabs">
        <div class="ap-tab"><?php _e('General','wp-easy-poll-afo');?></div>
    </div>

     <div class="ap-tabs-content">
          <div class="ap-tab-content">
            <form name="f" method="post" action="">
            <input type="hidden" name="th_edit_option" value="thumb_editor_save_settings" />
            <?php wp_nonce_field( 'thumb_editor_afo_save_action', 'thumb_editor_afo_save_action_field' ); ?>
            <table width="100%" border="0">
              <tr>
                <td width="300" valign="top"><strong><?php _e('Disable SRCSET','thumbnail-editor');?></strong></td>
                <td><input type="checkbox" name="thep_disable_srcset" value="Yes" <?php echo ($thep_disable_srcset == 'Yes'?'checked':''); ?>><i><?php _e('Remove srcset from thumbnail images','thumbnail-editor');?></i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
              <tr>
                <td width="300" valign="top"><strong><?php _e('Disable Width and Height','thumbnail-editor');?></strong></td>
                <td><input type="checkbox" name="thep_disable_wh" value="Yes" <?php echo ($thep_disable_wh == 'Yes'?'checked':''); ?>>
                <i><?php _e('Remove hard coded thumbnail image dimensions','thumbnail-editor');?></i>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
              <tr>
              <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="<?php _e('Save','thumbnail-editor');?>" class="button button-primary button-large button-ap-large" /></td>
              </tr>
            </table>
            </form>
        </div>
    </div> 
    </td>
  </tr>
  <tr>
    <td colspan="2"><p>Goto <a href="upload.php"><strong><?php _e('Media Library','thumbnail-editor');?></strong></a> <?php _e('and select','thumbnail-editor');?> <strong><?php _e('Crop Thumbnail','thumbnail-editor');?></strong> <?php _e('link to modify image thumbnails','thumbnail-editor');?></p></td>
  </tr>
</table>