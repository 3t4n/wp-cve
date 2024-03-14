<table id="tab_pro_general" class="form-table aeiwooc-tab active">

    <tr valign="top">
        <th scope="row"><?php _e("Hover Effecs", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'hover_effect';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
			$arr_effects = array(	
									''=> __("Fade ", WS247_PIEW_TEXTDOMAIN),
									'effect-scale'=> __("Scale", WS247_PIEW_TEXTDOMAIN), 
									'effect-right-to-left' => __("Right to left", WS247_PIEW_TEXTDOMAIN), 
									'effect-left-to-right' => __("Left to right", WS247_PIEW_TEXTDOMAIN), 
									'effect-top-to-bottom' => __("Top to bottom", WS247_PIEW_TEXTDOMAIN),
									'effect-rotate-left' => __("Left rotate", WS247_PIEW_TEXTDOMAIN),
									'effect-rotate-right' => __("Right rotate", WS247_PIEW_TEXTDOMAIN),
									'effect-description' => __("Short description", WS247_PIEW_TEXTDOMAIN),
									'effect-overflow' => __("Overflow Background", WS247_PIEW_TEXTDOMAIN)
									
									);
            ?>
            <select id="<?php echo $field; ?>" name="<?php echo $field; ?>" class="ws247-piew-js-group-effect-show">
                <?php 
				foreach($arr_effects as $effect => $name){
					$selected = ''; $checked = '';
					if($effect == $val){
						$selected = 'selected';
						$checked = 'checked';
					}
				?>
                	<option <?php echo $selected;?> value="<?php echo $effect; ?>"  data-group="#<?php echo $field; ?>-<?php echo $effect;?>"><?php echo $name;?></option>
                <?php
				}
				?>
            </select>
        </td>
    </tr>
    <?php require_once WS247_PIEW_PLUGIN_INC_DIR . '/tabs/group_effect.php';  ?>
    
    <tr valign="top">
        <th scope="row"><?php _e("Gallery show", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
        	<?php 
            $field_name = 'gallery_show';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
			$checked = '';
			if($val=='on'){
				$checked = 'checked';
			}
            ?>
           <input type="checkbox" <?php echo $checked;?>  id="<?php echo $field; ?>" name="<?php echo $field; ?>" class="ws247-piew-js-group-show" data-group="#ws247-piew-gallery" />
        </td>
    </tr>
    <?php require_once WS247_PIEW_PLUGIN_INC_DIR . '/tabs/group_gallery.php';  ?>
    
     <tr valign="top">
        <th scope="row"><?php _e("Product border", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'product_border';
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
        <th scope="row"><?php _e("Product border color", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'product_border_color';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
            ?>
            <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Product shadow", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'product_shadow';
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
        <th scope="row"><?php _e("Product border radius", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'product_border_radius';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
			if($val==''){
				$val = 0;
			}
            ?>
           <input type="number" value="<?php echo $val;?>"  id="<?php echo $field; ?>" name="<?php echo $field; ?>" /> px
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Padding bottom", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'product_pad_bottom';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
			if($val==''){
				$val = 0;
			}
            ?>
           <input type="number" value="<?php echo $val;?>"  id="<?php echo $field; ?>" name="<?php echo $field; ?>" /> px
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><u><?php _e("ADD TO CART STYLE", WS247_PIEW_TEXTDOMAIN); ?></u></th>
        <td></td>
    </tr>
    
     <tr valign="top">
        <th scope="row"><?php _e("Add to cart background color", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'add_to_cart_bg';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
            ?>
            <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Add to cart background color hover", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'add_to_cart_bg_hover';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
            ?>
            <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Add to cart color", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'add_to_cart_color';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
            ?>
            <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Add to cart color hover", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'add_to_cart_color_hover';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
            ?>
            <input value="<?php echo $val; ?>" class="colorpicker" id="<?php echo $field; ?>" name="<?php echo $field; ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php _e("Add to cart radius", WS247_PIEW_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'add_to_cart_radius';
            $field = Ws247_piew::create_option_prefix($field_name);
            $val = Ws247_piew::class_get_option($field_name);
			if($val==''){
				$val = 0;
			}
            ?>
           <input type="number" value="<?php echo $val;?>"  id="<?php echo $field; ?>" name="<?php echo $field; ?>" /> px
        </td>
    </tr>
    
     
    
    
   
</table>