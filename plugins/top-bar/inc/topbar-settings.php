<?php

// create custom plugin settings menu
add_action('admin_menu', 'tpbr_create_menu');
function tpbr_create_menu()
{
    // Makes sure PRO is there.
    if (!is_plugin_active('topbar-pro/topbar_pro.php')) {
        // create new top-level menu
        add_menu_page('Top Bar', 'Top Bar', 'administrator', 'topbar-options-menu', 'tpbr_settings_page');

        // call register settings function
        add_action('admin_init', 'register_tpbr_settings');
    }
}

function register_tpbr_settings()
{
    // register our settings
    register_setting('tpbr-settings-group', 'tpbr_fixed', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_guests_or_users', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_status', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_yn_button', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_color', ['sanitize_callback' => 'sanitize_hex_color']);
    register_setting('tpbr-settings-group', 'tpbr_message', ['sanitize_callback' => 'wp_kses_post']);
    register_setting('tpbr-settings-group', 'tpbr_btn_text', ['sanitize_callback' => 'wp_kses_post']);
    register_setting('tpbr-settings-group', 'tpbr_btn_url', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_btn_behavior', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('tpbr-settings-group', 'tpbr_detect_sticky', ['sanitize_callback' => 'sanitize_text_field']);
    $roles = get_editable_roles();
    foreach ($roles as $key => $value) {
        register_setting('tpbr-settings-group', 'tpbr_role_'.$key);
    }
}

function tpbr_settings_page()
{ ?>

<div class="tpbr_wrap">
    <h1>Top Bar settings
        <img class="tpbr_logo"
            src="<?php echo plugins_url('../images/darko.png', __FILE__); ?>"
            draggable="false" style="-moz-user-select: none;" />
    </h1>
    <div class="tpbr_inner">

        <form method="post" action="options.php">
            <?php settings_fields('tpbr-settings-group'); ?>
            <?php do_settings_sections('tpbr-settings-group'); ?>

            <div class="tpbr_section_box">
                <h3 class="no-b-top">
                    <?php echo __('General', 'top-bar'); ?>
                </h3>

                <div class="tpbr_settings_box">
                    <div class="tpbr_settings_item one-third startbit">
                        <h4><?php echo __('Status', 'top-bar'); ?>
                        </h4>
                        <?php $current_status = esc_attr(get_option('tpbr_status')); ?>
                        <select name="tpbr_status">
                            <?php if ('active' == $current_status) { ?>
                            <option value="active" selected>
                                <?php echo __('Active', 'top-bar'); ?>
                            </option>
                            <option value="inactive">
                                <?php echo __('Inactive', 'top-bar'); ?>
                            </option>
                            <?php } elseif ('inactive' == $current_status) { ?>
                            <option value="inactive" selected>
                                <?php echo __('Inactive', 'top-bar'); ?>
                            </option>
                            <option value="active">
                                <?php echo __('Active', 'top-bar'); ?>
                            </option>
                            <?php } else { ?>
                            <option value="active" selected>
                                <?php echo __('Active', 'top-bar'); ?>
                            </option>
                            <option value="inactive">
                                <?php echo __('Inactive', 'top-bar'); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <p><?php echo __('If inactive, this prevents the Top Bar from displaying on your site (takes over all other settings).', 'top-bar'); ?>
                        </p>
                    </div>

                    <div class="tpbr_settings_item one-third">
                        <h4><?php echo __('Position', 'top-bar'); ?>
                        </h4>
                        <?php $current_fixed = esc_attr(get_option('tpbr_fixed')); ?>
                        <select class="tpbr_fixed" name="tpbr_fixed">
                            <?php if ('fixed' == $current_fixed) { ?>
                            <option value="notfixed">
                                <?php echo __('Standard', 'top-bar'); ?>
                            </option>
                            <option value="fixed" selected>
                                <?php echo __('Fixed', 'top-bar'); ?>
                            </option>
                            <?php } else { ?>
                            <option value="notfixed" selected>
                                <?php echo __('Standard', 'top-bar'); ?>
                            </option>
                            <option value="fixed">
                                <?php echo __('Fixed', 'top-bar'); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <p><?php echo __('If set to Fixed, your Top Bar will remain visible as the user scrolls.', 'top-bar'); ?>
                        </p>
                    </div>

                    <div class="tpbr_settings_item one-third endbit">
                        <h4><?php echo __('Detect fixed navigation', 'top-bar'); ?>
                            <span class="tpbr_new">[NEW]</span> <span class="tpbr_beta">[BETA]</span>
                        </h4>
                        <?php $current_detect_sticky = esc_attr(get_option('tpbr_detect_sticky')); ?>
                        <select class="tpbr_detect_sticky" name="tpbr_detect_sticky">
                            <?php if ('0' == $current_detect_sticky) { ?>
                            <option value="1">
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <option value="0" selected>
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <?php } elseif ('1' == $current_detect_sticky) { ?>
                            <option value="1" selected>
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <option value="0">
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <?php } else { ?>
                            <option value="0" selected>
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <option value="1">
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <p><?php echo __('If enabled, the plugin will try to check for sticky elements in your site\'s header that may cover or be covered by the Top Bar and fix these issues.', 'top-bar'); ?>
                        </p>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>

            <div class="tpbr_section_box">
                <h3><?php echo __('Content', 'top-bar'); ?>
                </h3>

                <div class="tpbr_settings_box">
                    <div class="tpbr_settings_item two-third startbit">
                        <h4><?php echo __('Message', 'top-bar'); ?>
                        </h4>
                        <input class='tpbr_tx_field' type="text" name="tpbr_message"
                            placeHolder="<?php echo __('eg. Check out our new product right now!', 'top-bar'); ?>"
                            value="<?php echo esc_attr(get_option('tpbr_message')); ?>" />
                        <p><?php echo __('Message to show in your Top Bar.', 'top-bar'); ?>
                        </p>
                    </div>

                    <div class="tpbr_settings_item one-third endbit">
                        <h4><?php echo __('Button', 'top-bar'); ?>
                        </h4>
                        <?php $current_status = esc_attr(get_option('tpbr_yn_button')); ?>
                        <select class="tpbr_yn_button" name="tpbr_yn_button">
                            <?php if ('button' == $current_status) { ?>
                            <option value="button" selected>
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <option value="nobutton">
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <?php } elseif ('nobutton' == $current_status) { ?>
                            <option value="nobutton" selected>
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <option value="button">
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <?php } else { ?>
                            <option value="nobutton" selected>
                                <?php echo __('Disabled', 'top-bar'); ?>
                            </option>
                            <option value="button">
                                <?php echo __('Enabled', 'top-bar'); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <p><?php echo __('Adds a button to your Top Bar.', 'top-bar'); ?>
                        </p>
                    </div>
                    <div style="clear:both;"></div>

                    <div class='tpbr_button_box'>

                        <h3><?php echo __('Button settings', 'top-bar'); ?>
                        </h3>

                        <div class="tpbr_settings_item one-third startbit">
                            <h4><?php echo __('Button text', 'top-bar'); ?>
                            </h4>
                            <input class='tpbr_tx_field' type="text" name="tpbr_btn_text"
                                placeHolder="<?php echo __('eg. See product', 'top-bar'); ?>"
                                value="<?php echo esc_attr(get_option('tpbr_btn_text')); ?>" />
                            <p><?php echo __('Text inside the button.', 'top-bar'); ?>
                            </p>
                        </div>
                        <div class="tpbr_settings_item one-third">
                            <h4><?php echo __('Button URL', 'top-bar'); ?>
                            </h4>
                            <input class='tpbr_tx_field' type="text" name="tpbr_btn_url"
                                placeHolder="<?php echo __('eg. https://wpdarko.com', 'top-bar'); ?>"
                                value="<?php echo esc_attr(get_option('tpbr_btn_url')); ?>" />
                            <p><?php echo __('Link used when the button is clicked.', 'top-bar'); ?>
                            </p>
                        </div>
                        <div class="tpbr_settings_item one-third endbit">
                            <h4><?php echo __('Link behavior', 'top-bar'); ?>
                                <span class="tpbr_new">[NEW]</span>
                            </h4>
                            <?php $current_behavior = esc_attr(get_option('tpbr_btn_behavior')); ?>
                            <select class="tpbr_btn_behavior" name="tpbr_btn_behavior">
                                <?php if ('newwindow' == $current_behavior) { ?>
                                <option value="newwindow" selected>
                                    <?php echo __('New window', 'top-bar'); ?>
                                </option>
                                <option value="samewindow">
                                    <?php echo __('Same window', 'top-bar'); ?>
                                </option>
                                <?php } else { ?>
                                <option value="samewindow" selected>
                                    <?php echo __('Same window', 'top-bar'); ?>
                                </option>
                                <option value="newwindow">
                                    <?php echo __('New window', 'top-bar'); ?>
                                </option>
                                <?php } ?>
                            </select>
                            <p><?php echo __('Button behavior when clicked.', 'top-bar'); ?>
                            </p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>

            <div class="tpbr_section_box">
                <h3><?php echo __('Visibility & Styling', 'top-bar'); ?>
                </h3>

                <div class="tpbr_settings_box">

                    <div class="tpbr_settings_item one-third startbit">
                        <h4><?php echo __('User visibility', 'top-bar'); ?>
                        </h4>

                        <?php $current_status = esc_attr(get_option('tpbr_guests_or_users')); ?>
                        <select class="tpbr_guests_or_users" name="tpbr_guests_or_users">
                            <?php if ('all' == $current_status) { ?>
                            <option value="all" selected>
                                <?php echo __('Everyone', 'top-bar'); ?>
                            </option>
                            <option value="guests">
                                <?php echo __('Guests', 'top-bar'); ?>
                            </option>
                            <option value="users">
                                <?php echo __('Registered users', 'top-bar'); ?>
                            </option>
                            <?php } elseif ('guests' == $current_status) { ?>
                            <option value="all">
                                <?php echo __('Everyone', 'top-bar'); ?>
                            </option>
                            <option value="guests" selected>
                                <?php echo __('Guests', 'top-bar'); ?>
                            </option>
                            <option value="users">
                                <?php echo __('Registered users', 'top-bar'); ?>
                            </option>
                            <?php } elseif ('users' == $current_status) { ?>
                            <option value="all" selected>
                                <?php echo __('Everyone', 'top-bar'); ?>
                            </option>
                            <option value="guests">
                                <?php echo __('Guests', 'top-bar'); ?>
                            </option>
                            <option value="users" selected>
                                <?php echo __('Registered users', 'top-bar'); ?>
                            </option>
                            <?php } else { ?>
                            <option value="all" selected>
                                <?php echo __('Everyone', 'top-bar'); ?>
                            </option>
                            <option value="guests">
                                <?php echo __('Guests', 'top-bar'); ?>
                            </option>
                            <option value="users">
                                <?php echo __('Registered users', 'top-bar'); ?>
                            </option>
                            <?php } ?>
                        </select>
                        <p><?php echo __('Defines who can see the Top Bar.', 'top-bar'); ?>
                        </p>
                    </div>

                    <div class="tpbr_settings_item two-third endbit">
                        <?php $current_color = esc_attr(get_option('tpbr_color')); ?>
                        <h4 style="margin-bottom:5px;">
                            <?php echo __('Color', 'top-bar'); ?>
                        </h4>
                        <input class="dmb_color_picker dmb_field dmb_color_of_tabs" name="tpbr_color" type="text"
                            value="<?php echo (!empty($current_color)) ? $current_color : '#12bece'; ?>" />
                        <p><?php echo __('Used for the Top Bar\'s background and button.', 'top-bar'); ?>
                        </p>
                    </div>

                    <div style="clear: both;"></div>

                </div>
            </div>

            <div class="tpbr_section_box no-m-bot">
                <h3><?php echo __('PRO version', 'top-bar'); ?>
                </h3>

                <div class="tpbr_settings_box">
                    <div class="tpbr_settings_item full-w startbit endbit">

                        <h4>Unlock the power of PRO now!</h4>
                        <p>
                            This is a free plugin and it is not limited. PRO features can be seen above or at
                            WPDarko.com.
                        </p>
                        <a class="tpbr_pro_button" target="_blank" href="https://wpdarko.com/items/top-bar-pro/">See all
                            the PRO features</a>

                    </div>

                    <div style="clear:both;"></div>
                </div>
            </div>

            <?php submit_button(); ?>

        </form>
    </div>
</div>
<?php }
