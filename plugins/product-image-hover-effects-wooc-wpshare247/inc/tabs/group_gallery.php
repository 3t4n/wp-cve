<tr id="ws247-piew-gallery" class="ws247-piew-group parent-<?php echo $checked;?>">
    <td colspan="2" style="padding:0;">
        <table class="form-table sub-table">
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Gallery radius", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'gallery_radius';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    $checked = '';
                    if($val=='on'){
                        $checked = 'checked';
                    }
                    ?>
                   <input type="checkbox" <?php echo $checked;?>  id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Gallery border color", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'gallery_border_color';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    ?>
                    <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><span class="dashicons dashicons-arrow-right-alt2"></span> <?php _e("Gallery location", WS247_PIEW_TEXTDOMAIN); ?></th>
                <td>
                    <?php 
                    $field_name = 'gallery_location';
                    $field = Ws247_piew::create_option_prefix($field_name);
                    $val = Ws247_piew::class_get_option($field_name);
                    $arr_locations = array(	
                                            ''=> __("Bottom", WS247_PIEW_TEXTDOMAIN),
                                            'on-right'=> __("Right", WS247_PIEW_TEXTDOMAIN),
                                            'on-left'=> __("Left", WS247_PIEW_TEXTDOMAIN)
                                            );
                    ?>
                    <select id="<?php echo $field; ?>" name="<?php echo $field; ?>">
                        <?php 
                        foreach($arr_locations as $location => $name){
                            $selected = ''; 
                            if($location == $val){
                                $selected = 'selected';
                            }
                        ?>
                            <option <?php echo $selected;?> value="<?php echo $location; ?>"><?php echo $name;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </td>
</tr>