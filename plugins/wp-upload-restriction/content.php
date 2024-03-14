<?php if(!defined('ABSPATH')){ exit(); } ?>
<h4><?php esc_html_e('Allowed File Types', 'wp_upload_restriction'); ?></h4>
<p><?php esc_html_e('Files with selected types will be allowed for uploading.', 'wp_upload_restriction'); ?></p>
<div class="check-uncheck-links"><a class="check" href="#"><?php esc_html_e('Check all', 'wp_upload_restriction'); ?></a> | <a class="uncheck" href="#"><?php esc_html_e('Uncheck all', 'wp_upload_restriction'); ?></a></div>
<div class="list">
<?php
    $i = 1;
            
    foreach($wp_mime_types as $ext => $type){
        $checked = $check_all ? 'checked="checked"' : (isset($selected_mimes[$ext]) ? 'checked="checked"' : '');
?>
    <div>
        <label for="ext_<?php esc_attr_e($i); ?>">
            <input id="ext_<?php esc_attr_e($i); ?>" type="checkbox" name="types[]" class="chk-mime-types" <?php esc_html_e($checked); ?> value="<?php esc_attr_e($ext); ?>::<?php esc_attr_e($type); ?>"> <?php echo $this->processExtention($ext); ?>
        </label>
    </div>
<?php    
        $i++;
    }
?>
</div>
<p>&nbsp;</p>
<h4><?php esc_html_e('Allowed Upload Size', 'wp_upload_restriction'); ?>:</h4>
<p><?php esc_html_e('Check the box below and enter value in the field to restrict upload size for the selected role.', 'wp_upload_restriction'); ?></p>
<input type="checkbox" name="restrict_upload_size" value="1" <?php esc_attr_e($restrict_upload_size ? 'checked="checked"' : ''); ?>> <lable for="restrict_upload_size"><?php esc_html_e('Restrict upload size to', 'wp_upload_restriction'); ?></lable> 
<label>
    <input type="text" maxlength="5" size="6" name="upload_size" value="<?php esc_attr_e($upload_size); ?>">
    <select name="upload_size_unit">
        <option value="KB" <?php esc_attr_e($upload_size_unit == 'KB' ? 'selected="selected"' : ''); ?>>KB</option>
        <option value="MB" <?php esc_attr_e(empty($upload_size_unit) || $upload_size_unit == 'MB' ? 'selected="selected"' : ''); ?>>MB</option>
    </select>
</label>