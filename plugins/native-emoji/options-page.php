<?php
/*
* Plugin Name Native Emoji
* Version 3.0.1
* Author Daniel Brandenburg
*/
?>
<div class="wrap">
    
    <h1><?php _e( 'Native Emoji', 'native-emoji' );?></h1>
    
    <h2 class="nav-tab-wrapper">
		<a class="nav-tab nep-nav-tab-right nep-nav-tab-donate" href="//paypal.me/danybranding" target="_blank" rel="noopener noreferrer">
            <span class="dashicons dashicons-heart"></span> <?php _e( 'Donate', 'native-emoji' );?>
        </a>
		<a class="nav-tab nep-nav-tab-right nep-nav-tab-review" href="//wordpress.org/support/plugin/native-emoji/reviews/" target="_blank" rel="noopener noreferrer">
            <span class="dashicons dashicons-star-filled"></span> <?php _e( 'Review', 'native-emoji' );?>
        </a>
        <a class="nav-tab nep-nav-tab-right nep-nav-tab-live-demo" href="//native-emoji.davabuu.net" target="_blank" rel="noopener noreferrer">
            <span class="dashicons dashicons-visibility"></span> <?php _e( 'Live Demo', 'native-emoji' );?>
        </a>
	</h2>
    
    <form method="post" action="options.php">
        
        <?php settings_fields( 'nep_native_emoji_settings' ); ?>
        <?php do_settings_sections( 'nep_native_emoji_settings' ); ?>
        
        <table class="form-table">   
            
            <tr>
                <th scope="row"><?php _e( 'Admin editor', 'native-emoji' );?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Enable plugin on admin editor', 'native-emoji' );?></span></legend>
                        <label>
                            <input type="checkbox" name="nep_plugin_admin_activation" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_admin_activation' ) ), '1', true );?>>
                            <?php _e( 'Enable', 'native-emoji' );?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php _e( 'Other admin editor settings', 'native-emoji' );?></th>
                <td>                                    
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Close panel after insert an emoji', 'native-emoji' );?></span></legend>
                        <label>
                            <input type="checkbox" name="nep_plugin_close_panel" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_close_panel' ) ), 1, true );?>>
                            <?php _e( 'Close panel after insert an emoji', 'native-emoji' );?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2"><hr></th>                
            </tr>
            <tr>
                <th scope="row"><span class="dashicons dashicons-warning"></span> <?php _e( 'Notices', 'native-emoji' );?></th>
                <td>
                    <p class="description"><?php _e( 'Make sure to check the box if your website uses jQuery, otherwise the plugin may cause errors in your website', 'native-emoji' );?></p>
                    
                    <p class="description"><?php _e( 'The plugin tries to preserve the css properties of the comments box, in case something has been omitted, use your custom css under the tag', 'native-emoji' );?> <strong>#nep_fake_textarea</strong></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Front end comments', 'native-emoji' );?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Enable plugin on front end comments', 'native-emoji' );?></span></legend>
                        <label for="users_can_register">
                            <input type="checkbox" name="nep_plugin_comments_activation" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_comments_activation' ) ), '1', true );?>>
                            <?php _e( 'Enable', 'native-emoji' );?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Other comments settings', 'native-emoji' );?></th>
                <td>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Does your website uses jQuery library?', 'native-emoji' );?></span></legend>
                        <label for="users_can_register">
                            <input type="checkbox" name="nep_plugin_site_use_jquery" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_site_use_jquery' ) ), '1', true );?>>
                            <?php _e( 'My website uses jQuery library', 'native-emoji' );?>
                        </label>                        
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Do you want to display the plugin on mobile devices?', 'native-emoji' );?></span></legend>
                        <label for="users_can_register">
                            <input type="checkbox" name="nep_plugin_show_on_mobile" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_show_on_mobile' ) ), 1, true );?>>
                            <?php _e( 'Display on mobile devices', 'native-emoji' );?>
                        </label>                        
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Close panel after insert an emoji', 'native-emoji' );?></span></legend>
                        <label>
                            <input type="checkbox" name="nep_plugin_close_panel_comments" value="1" <?php checked( esc_attr( get_option( 'nep_plugin_close_panel_comments' ) ), 1, true );?>>
                            <?php _e( 'Close panel after insert an emoji', 'native-emoji' );?>
                        </label>
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Comments emoji panel color', 'native-emoji' );?></span></legend>
                        <label><strong><?php _e( 'Panel color', 'native-emoji' );?></strong></label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_color" value="light" <?php checked( esc_attr( get_option( 'nep_plugin_panel_color', 'light' ) ), 'light', true );?>>
                            <?php _e('Light', 'native-emoji');?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_color" value="dark" <?php checked( esc_attr( get_option( 'nep_plugin_panel_color' ) ), 'dark', true );?>>
                            <?php _e( 'Dark', 'native-emoji' );?>
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Comments emoji panel position', 'native-emoji');?></span></legend>
                        <label><strong><?php _e( 'Panel position', 'native-emoji' );?></strong></label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_position" value="right_bottom" <?php checked( esc_attr( get_option( 'nep_plugin_panel_position', 'right_bottom' ) ), 'right_bottom', true );?>>
                            <?php _e('Right Bottom', 'native-emoji');?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_position" value="right_top" <?php checked( esc_attr( get_option( 'nep_plugin_panel_position' ) ), 'right_top', true );?>>
                            <?php _e( 'Right Top', 'native-emoji' );?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_position" value="left_bottom" <?php checked( esc_attr( get_option( 'nep_plugin_panel_position' ) ), 'left_bottom', true );?>>
                            <?php _e( 'Left Bottom', 'native-emoji' );?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="nep_plugin_panel_position" value="left_top" <?php checked( esc_attr( get_option( 'nep_plugin_panel_position' ) ), 'left_top', true );?>>
                            <?php _e( 'Left Top', 'native-emoji' );?>
                        </label>
                    </fieldset>
                </td>
            </tr>            
        </table>

        <?php submit_button(); ?>

    </form>
</div>