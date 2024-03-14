<?php

$enablePlugin = esc_attr(get_option('wpsaio_enable_plugin', 1));

$widgetPosition = esc_attr(get_option('wpsaio_widget_position', 'right'));

$style = esc_attr(get_option('wpsaio_style', 'redirect'));

$tooltip = esc_attr(get_option('wpsaio_tooltip', 'appname'));

$bottomDistance = esc_attr(get_option('wpsaio_bottom_distance', 30));

$buttonIcon = esc_attr(get_option('wpsaio_button_icon'));

$buttonImage = esc_attr(get_option('wpsaio_button_image', 'contain'));

$buttonColor = esc_attr(get_option('wpsaio_button_color'));

?>

<div class="wrap-content-box">
    <p><?php echo __('Setting style for the floating widget.', WP_SAIO_LANG_PREFIX) ?></p>
    <form action="options.php" method="post">
        <?php settings_fields('wpsaio'); ?>
        <?php do_settings_sections('wpsaio'); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="wpsaio-enable-plugin-switch"><?php _e('Enable plugin', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <div class="wpsaio-switch-control">
                        <input type="checkbox" name="wpsaio_enable_plugin" value="1" id="wpsaio-enable-plugin-switch" class="" <?php echo checked($enablePlugin, 1) ?> />
                        <label for="wpsaio-enable-plugin-switch" class="green"></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaioWidgetPosition"><?php echo __('Widget position', WP_SAIO_LANG_PREFIX) ?></label></th>
                <td>
                    <div class="setting align">
                        <div class="button-group button-large" data-setting="align">
                            <button class="button btn-widget-position btn-left <?php echo $widgetPosition == 'left' ? 'active' : '' ?>" value="left" type="button">
                                <?php echo __('Left', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                            <button class="button btn-widget-position btn-right <?php echo $widgetPosition == 'right' ? 'active' : '' ?>" value="right" type="button">
                                <?php echo __('Right', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                        </div>
                        <input name="wpsaio_widget_position" id="wpsaioWidgetPosition" class="hidden" value="<?php echo $widgetPosition ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaioStyle"><?php _e('Style', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <div class="setting align">
                        <div class="button-group button-large" data-setting="align">
                            <button class="button btn-style btn-redirect <?php echo $style == 'redirect' ? 'active' : '' ?>" value="redirect" type="button">
                                <?php echo __('Redirect', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                            <button class="button btn-style btn-popup <?php echo $style == 'popup' ? 'active' : '' ?>" value="popup" type="button">
                                <?php echo __('Popup', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                        </div>
                        <input name="wpsaio_style" id="wpsaioStyle" class="hidden" value="<?php echo $style ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaioTooltip"><?php _e('Tooltip', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <div class="setting align">
                        <div class="button-group button-large" data-setting="align">
                            <button class="button btn-tooltip btn-appname <?php echo $tooltip == 'appname' ? 'active' : '' ?>" value="appname" type="button">
                                <?php echo __('App Name', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                            <button class="button btn-tooltip btn-appcontent <?php echo $tooltip == 'appcontent' ? 'active' : ''  ?>" value="appcontent" type="button">
                                <?php echo __('App Content', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                        </div>
                        <input name="wpsaio_tooltip" id="wpsaioTooltip" class="hidden" value="<?php echo $tooltip ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaio_bottom_distance"><?php _e('Padding from bottom', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <div class="range" style='--min:0; --max:500; --value:<?php echo get_option('wpsaio_bottom_distance', 30) ?>; --text-value:"<?php echo $bottomDistance ?>";'>
                        <input id="wpsaio_bottom_distance" name="wpsaio_bottom_distance" type="range" min="0" max="500" value="<?php echo $bottomDistance ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
                        <output></output>
                        <div class='range__progress'></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaio_button_icon"><?php _e('Custom icon/avatar', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <input type="text" name="wpsaio_button_icon" id="wpsaio_button_icon" value="<?php echo $buttonIcon ?>" class="regular-text" />
                    <a href="javascript:void(0)" class="button wp_saio_choose_image_btn" data-target="#wpsaio_button_icon"><?php _e('Choose Image', WP_SAIO_LANG_PREFIX); ?></a>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaioButtonImage"><?php _e('Button style', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <div class="setting align">
                        <div class="button-group button-large" data-setting="align">
                            <button class="button btn-button-image btn-contain <?php echo $buttonImage == 'contain' ? 'active' : '' ?>" value="contain" type="button">
                                <?php echo __('Contain', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                            <button class="button btn-button-image btn-cover <?php echo $buttonImage == 'cover' ? 'active' : '' ?>" value="cover" type="button">
                                <?php echo __('Cover', WP_SAIO_LANG_PREFIX) ?>
                            </button>
                        </div>
                        <input name="wpsaio_button_image" id="wpsaioButtonImage" class="hidden" value="<?php echo $buttonImage ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaio_button_color"><?php _e('Button\'s color', WP_SAIO_LANG_PREFIX); ?></label></th>
                <td>
                    <input type="text" name="wpsaio_button_color" value="<?php echo $buttonColor ?>" id="wpsaio_button_color" class="regular-text wp_saio_colorpicker" />
                </td>
            </tr>
        </table>
        <div class="wp_saio_panel_btn-wrap">
            <button class="wpsaio-save button button-primary button-design-settings"><?php echo __('Save Changes', WP_SAIO_LANG_PREFIX) ?><i class="dashicons dashicons-update-alt"></i></button>
        </div>
    </form>
</div>
<?php
$icon_bg_color = get_option('wpsaio_button_color', '');
$btn_icon = get_option('wpsaio_button_icon', '');
$btn_image = get_option('wpsaio_button_image', 'contain');
$data = array(
    'buttons' => WpSaio::generateFrontendButtons(),
    'contents' => do_shortcode(implode('', WpSaio::renderShortcodes())),
    'icon_bg_color' => $icon_bg_color,
    'btn_icon' => $btn_icon,
    'btn_image' => $btn_image
);
echo WpSaioView::load('home.main', $data);
?>