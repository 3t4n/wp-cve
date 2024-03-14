<?php 

/*
 * Add Options Page
 */
function epa_options()
{
    add_submenu_page('edit.php?post_type=epapopup', 'Options', 'Options', 'manage_options', 'epaoptions','epa_global_custom_options');

    // Register Settings
    add_action( 'admin_init', 'epa_register_settings' );
}
add_action('admin_menu', 'epa_options');

function epa_register_settings() {
    register_setting('epa-settings', 'epa_enable');
    register_setting('epa-settings', 'epa_default_id');
    register_setting('epa-settings', 'epa_bgcolor');
    register_setting('epa-settings', 'epa_popup_padding');
    register_setting('epa-settings', 'epa_popup_delay');
    register_setting('epa-settings', 'epa_expire');

}

function epa_global_custom_options()
{
?>
    <div class="epa_options_page wrap">
        <h2><?php _e('Easy Popup Options Page', 'easy-popup-announcement') ?></h2>

        <?php if($_REQUEST['settings-updated'] == TRUE): ?>
        	<div id="message" class="updated"><p><?php _e('Options Saved.', 'easy-popup-announcement' ); ?></p></div>
    	<?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'epa-settings' ); ?>
            <h3><?php _e('Popup Options', 'easy-popup-announcement'); ?></h3>
            <p><?php _e('General Setting for popup, please rate this simple plugin five star.','easy-popup-announcement');?></p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="popup_bg"><?php _e('Enable default popup globally?', 'easy-popup-announcement'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <select name="epa_enable">
                                <option value="yes" <?php selected( get_option('epa_enable'), 'yes'); ?>>Yes</option>
                                <option value="no" <?php selected( get_option('epa_enable'), 'no'); ?>>No</option>
                            </select>
                        </fieldset>
                        <cite><?php _e('Option to disable default popup, if disabled popup will use shortcode to initialize', 'easy-popup-announcement'); ?></cite>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="popup_bg"><?php _e('Default Popup', 'easy-popup-announcement'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <?php get_epa_cpt_id(); ?>
                        </fieldset>
                        <cite><?php _e('If you choose none, shortcode applied to a post / page will be used', 'easy-popup-announcement'); ?></cite>
                    </td>
                </tr>
				<tr valign="top">
					<th scope="row"><label for="popup_bg"><?php _e('Popup Background Color', 'easy-popup-announcement'); ?></label></th>
					<td>
						<fieldset>
                            <input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,0.45)" name="epa_bgcolor" value="<?php echo get_option('epa_bgcolor'); ?>"/>
						</fieldset>
					</td>
				</tr>
                <tr valign="top">
                    <th scope="row"><label for="popup-pading"><?php _e('Popup Padding', 'easy-popup-announcement'); ?></label></th>
                    <td>
                        <fieldset>
                             <input type="text" name="epa_popup_padding" value="<?php echo get_option('epa_popup_padding'); ?>" /> pixels
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="popup-pading"><?php _e('Popup Delay', 'easy-popup-announcement'); ?></label></th>
                    <td>
                        <fieldset>
                             <input type="text" name="epa_popup_delay" value="<?php echo get_option('epa_popup_delay'); ?>" /> fill 5000 for 5 seconds.
                        </fieldset>
                    </td>
                </tr>
                <!-- <tr valign="top">
                    <th scope="row"><label for="popup_transition"><?php _e('Transition', 'easy-popup-announcement'); ?></label></th>
                    <td>
                        <fieldset>
                            <select name="epa_effect">
                                <option value="">No Effect</option>
                                <option value="fade" <?php selected( get_option('epa_effect'), 'fade'); ?>>Fade</option>
                                <option value="fade_scale" <?php selected( get_option('epa_effect'), 'fade_scale'); ?>>Fade and Scale</option>
                                <option value="scrolldown" <?php selected( get_option('epa_effect'), 'scrolldown'); ?>>Scroll Down</option>
                            </select>
                        </fieldset>
                    </td>
                </tr> -->
                <!-- <tr valign="top">
                    <th scope="row"><label for="wphub_use_api"><?php _e('Dimension', 'easy-popup-announcement'); ?></label></th>
                    <td>
                        <fieldset><label for="" class="epa_label_block">Max Width</label> <input type="text" name="epa_width" size="5" class="epa_input" value="<?php echo get_option('epa_width'); ?>" /> em</fieldset>
                        <cite>Set maximum width of the popup, leave blank for default</cite>
                    </td>
                </tr> -->
			</table>

            <h3><?php _e('Cookie Options', 'easy-popup-announcement'); ?></h3>
            <p><?php _e('Set default value for cookie, it can be overidden in individual shortcode attributes','easy-popup-announcement');?></p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="popup_bg"><?php _e('Cookie Expire', 'easy-popup-announcement'); ?></label></th>
                    <td>
                        <fieldset>
                            <input type="text" name="epa_expire" value="<?php echo get_option('epa_expire'); ?>" /> Second
                        </fieldset>
                        <cite>0 = Show everytime browser reload, 60 = 1 minute, 86400 = 1 day, 604800 = 1 week</cite>
                    </td>
                </tr>
            </table>

            <p><input type="submit" name="Submit" value="<?php _e('Save Options','easy-popup-announcement'); ?>" class="button button-primary" /></p>
            
        </form>
    </div>
<?php
}
