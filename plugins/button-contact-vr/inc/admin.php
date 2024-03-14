<div class="wrap">
<h2>Button contact VR</h2>

<h2 class="nav-tab-wrapper">
    <a href="admin.php?page=contact_vr" class="nav-tab nav-tab-active">Button contact</a>
    <a href="admin.php?page=contact_vr_showroom" class="nav-tab">Showroom</a>
    <a href="admin.php?page=contact_vr_contact_form" class="nav-tab">Contact form (beta)</a>
    <a href="admin.php?page=contact_vr_all_in_one" class="nav-tab">All in one </a>
    <a href="admin.php?page=contact_vr_setting" class="nav-tab">Setting</a>
</h2>

<?php if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.') ?></strong></p>
    </div>
<?php } ?>

<form method="post" action="options.php">
    <?php settings_fields( 'pzf-settings-group' ); ?>
    <table class="form-table">     
        <tr valign="top">
            <th scope="row">Hotline</th>
            <td>
                <input placeholder="0123 456 789" type="text" name="pzf_phone" value="<?php echo get_option('pzf_phone'); ?>" />
                <label for="pzf_color_phone">
                    <input id="pzf_color_phone" class="my-color-field" name="pzf_color_phone" type="text" value="<?php echo get_option('pzf_color_phone'); ?>" />
                </label>           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Hotline 2</th>
            <td>
                <input placeholder="0123 456 789" type="text" name="pzf_phone2" value="<?php echo get_option('pzf_phone2'); ?>" />
                <label for="pzf_color_phone">
                    <input id="pzf_color_phone" class="my-color-field" name="pzf_color_phone2" type="text" value="<?php echo get_option('pzf_color_phone2'); ?>" />
                </label>           
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Hotline 3</th>
            <td>
                <input placeholder="0123 456 789" type="text" name="pzf_phone3" value="<?php echo get_option('pzf_phone3'); ?>" />
                <label for="pzf_color_phone">
                    <input id="pzf_color_phone" class="my-color-field" name="pzf_color_phone3" type="text" value="<?php echo get_option('pzf_color_phone3'); ?>" />
                </label>           
            </td>
        </tr>
        <tr valign="top" style=" border-bottom: 1px dashed #bfbfbf; ">
            <th scope="row" valign="top">Hotline bar (show/hide)</th>
            <td>
                <label for="pzf_phone_bar">
                    <input id="pzf_phone_bar" name="pzf_phone_bar" type="checkbox" value="1" <?php echo get_option('pzf_phone_bar') == '1' ? 'checked="checked"' : '' ?> />
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Zalo</th>
            <td><input placeholder="0123 456 789" type="text" name="pzf_zalo" value="<?php echo get_option('pzf_zalo'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Telegram</th>
            <td><input placeholder="Link telegram" type="text" name="pzf_telegram" value="<?php echo get_option('pzf_telegram'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Instagram</th>
            <td><input placeholder="Link instagram" type="text" name="pzf_instagram" value="<?php echo get_option('pzf_instagram'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Youtube</th>
            <td><input placeholder="Link youtube" type="text" name="pzf_youtube" value="<?php echo get_option('pzf_youtube'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Tiktok</th>
            <td><input placeholder="Link tiktok" type="text" name="pzf_tiktok" value="<?php echo get_option('pzf_tiktok'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Link fanpage</th>
            <td><input placeholder="Link fanpage" type="text" name="pzf_linkfanpage" value="<?php echo get_option('pzf_linkfanpage'); ?>" /></td>
        </tr> 
        <tr valign="top">
            <th scope="row">Whatsapp</th>
            <td><input placeholder="0123456789" type="text" name="pzf_whatsapp" value="<?php echo get_option('pzf_whatsapp'); ?>" /></td>
        </tr>  
        <tr valign="top">
            <th scope="row">Viber</th>
            <td><input placeholder="0123 456 789" type="text" name="pzf_viber" value="<?php echo get_option('pzf_viber'); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Link map</th>
            <td>
                <input placeholder="Link google map" type="text" name="pzf_linkggmap" value="<?php echo get_option('pzf_linkggmap'); ?>" />
                <label for="pzf_color_linkggmap">
                    <input id="pzf_color_linkggmap" class="my-color-field" name="pzf_color_linkggmap" type="text" value="<?php echo get_option('pzf_color_linkggmap'); ?>" />
                </label>
            </td>
        </tr> 
        <tr valign="top" style=" border-bottom: 1px dashed #bfbfbf; ">
            <th scope="row">Contact link</th>
            <td>
                <input placeholder="/lien-he/" type="text" name="pzf_contact_link" value="<?php echo get_option('pzf_contact_link'); ?>" />
                <label for="pzf_color_contact">
                    <input id="pzf_color_contact" class="my-color-field" name="pzf_color_contact" type="text" value="<?php echo get_option('pzf_color_contact'); ?>" />
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">ID fanpage</th>
            <td><input placeholder="" type="text" name="pzf_id_fanpage" value="<?php echo get_option('pzf_id_fanpage'); ?>" />
                <label for="pzf_color_fb">
                    <input id="pzf_color_fb" class="my-color-field" name="pzf_color_fb" type="text" value="<?php echo get_option('pzf_color_fb'); ?>" /> 
                </label><br>
                <i style=" margin-top: -20px; display: block; font-size: 13px; ">Find your Facebook ID? <a href="https://webvocuc.com/blog/cach-tim-id-fanpage-hieu-qua-va-nhanh-chong.html" target="_blank">help</a> or <a href="https://lookup-id.com/" target="_blank" rel="nofollow">click</a></i></td>
        </tr> 
        <tr valign="top">
            <th scope="row">Fanpage logged in greeting</th>
            <td><input style=" max-width: 300px; width: 100%; " placeholder="Hello! How can I assist you today?" type="text" name="logged_in_greeting" value="<?php echo get_option('logged_in_greeting'); ?>" /></td>
        </tr> 
    </table>
<?php _e( 'Note: If the messenger is not working please refer to the instructions <a href="https://webvocuc.com/blog/cach-tich-hop-facebook-messenger-chat-vao-wordpress-phien-ban-moi-cua-facebook.html" target="_blank">here</a>.', 'support_pzf' ); ?>
    <?php submit_button(); ?>
</form>

<hr />

<h2><?php esc_html_e( 'Support', 'support_pzf' ); ?></h2>
<p>
<?php _e( 'For submitting any support queries, feedback, bug reports or feature requests, please visit <a href="https://wordpress.org/plugins/button-contact-vr/" target="_blank">this link</a>.', 'support_pzf' ); ?>
</p>
<h2><?php esc_html_e( 'Help', 'support_pzf' ); ?></h2>
<p>
<?php _e( 'Please visit <a href="https://webvocuc.com/blog/tag/button-contact-vr" target="_blank">this link</a>.', 'help_pzf' ); ?>
</p>

</div>