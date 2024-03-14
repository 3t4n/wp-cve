<tr id="<?php echo $field; ?>-effect-overflow" class="ws247-piew-group parent-<?php echo $checked;?> <?php echo $field; ?>">
    <td colspan="2" style="padding:0;">
        <table class="form-table sub-table">
           
             <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Effect background color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'effect_bg_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Effect text color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'effect_text_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Add to cart background color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'atc_bg_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Add to cart color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'atc_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Add to cart border color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'atc_border_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            
        </table>
    </td>
</tr>