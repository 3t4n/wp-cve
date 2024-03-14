<table id="tab_pro_general" class="form-table aeiwooc-tab active">
    <!--List field here .....-->
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><?php esc_html_e("Hotline ring shake", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hotline", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            $field_rel = $field;
            ?>
            <input placeholder="Số điện thoại" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_shake_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>

            <br/>

            <?php 
            $field_name = 'is_zalo_shake_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input data-rel="<?php echo esc_html($field_rel); ?>" <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Zalo link?", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    jQuery("#<?php echo esc_html($field); ?>").click(function(event) {
                        var zalo_input_id = "#"+jQuery(this).data('rel');
                        if( jQuery(this).is(":checked") ){
                            jQuery(zalo_input_id).attr('placeholder', 'https://zalo.me/tbayvn');
                        }else{
                            jQuery(zalo_input_id).attr('placeholder', '0852080383');
                        }
                        
                    });
                });
            </script>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hotline position", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline_pos'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $shake_hotline_pos = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>">
                <option <?php if($shake_hotline_pos=='1') echo 'selected'; ?> value="1"><?php esc_html_e("Bottom left", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <option <?php if($shake_hotline_pos=='2') echo 'selected'; ?> value="2"><?php esc_html_e("Bottom right", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
            </select>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Bottom", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'shake_hotline_bottom';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="15, 20, ...." type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($val); ?>" /> px
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><?php esc_html_e("Icon Group", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Text contact", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'text_contact'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Liên hệ" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            
            <?php 
            $field_name = 'text_contact_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '#f58634';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Facebook messenger", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icon_fb_messenger'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="https://www.messenger.com/t/fanpage123" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'text_fb_messenger'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Nhắn tin Messenger" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_icon_fb_messenger'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
        	<br/>
            <small>Copy: https://www.messenger.com/t/tkwtbayvn</small>
        </td>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Zalo", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'company_zalo'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Số điện thoại Zalo" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'text_company_zalo'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Nhắn tin Zalo" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_company_zalo'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
        </td>
        </td>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Email", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'stt_email'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="email@gmail.com" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'text_stt_email'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Email: email@gmail.com" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_stt_email'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
        </td>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Hotline", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'stt_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'text_stt_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Gọi: 0852.080383" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_stt_hotline'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
        </td>
    </tr>
    
    <!-- ........................ -->
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Google", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icon_google_map'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Địa chỉ công ty" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'text_icon_google_map'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $link = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="Chỉ đường bản đồ" type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($link); ?>" />
            
            <?php 
            $field_name = 'hide_icon_google_map'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></label>
             
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Icon Group position", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_pos'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $icons_pos = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <select id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>">
                <option <?php if($icons_pos=='1') echo 'selected'; ?> value="1"><?php esc_html_e("Bottom right", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
                <option <?php if($icons_pos=='2') echo 'selected'; ?> value="2"><?php esc_html_e("Bottom left", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></option>
            </select>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Bottom", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'icons_bottom';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input placeholder="15, 20, ...." type="text" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" value="<?php echo esc_attr($val); ?>" /> px
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Icon group hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'hide_icons'; 
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $hide = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <input <?php if($hide=='on') echo 'checked'; ?> type="checkbox" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" /><label for="<?php echo esc_html($field); ?>"><?php esc_html_e("Icon group hide", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>?</label>
        </td>
    </tr>
    
    <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><?php esc_html_e("Talkto", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row">
            <?php esc_html_e("Talkto embed", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?>
        </th>
        <td>
            <?php 
            $field_name = 'talkto_embed';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
            ?>
            <textarea style="width:100%;" placeholder="" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>"><?php echo esc_attr($val); ?></textarea>
        </td>
    </tr>
    
     <tr valign="top">
        <th scope="row" style="padding-top:0; padding-bottom:0;" colspan="2">
            <h3 style="margin:0;color:#0055ab;"><?php esc_html_e("Advanced", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></h3>
        </th>
    </tr>
    
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Primary color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'primary_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '#00aff2';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Hover color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'hover_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '#272d6b';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><?php esc_html_e("Text color", WS247_AIO_CT_BUTTON_TEXTDOMAIN); ?></th>
        <td>
            <?php 
            $field_name = 'text_color';
            $field = Ws247_aio_ct_button::create_option_prefix($field_name);
            $val = Ws247_aio_ct_button::class_get_option($field_name);
			if(!$val) $val = '#fff';
            ?>
            <input value="<?php echo esc_attr($val); ?>" class="colorpicker" id="<?php echo esc_html($field); ?>" name="<?php echo esc_html($field); ?>" />
        </td>
    </tr>
    
</table>