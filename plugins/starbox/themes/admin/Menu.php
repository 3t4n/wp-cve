<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<div id="abh_settings" >
    <form id="abh_settings_form" name="settings" action="" method="post" enctype="multipart/form-data">
        <div id="abh_settings_title" ><?php _e('StarBox Settings', _ABH_PLUGIN_NAME_); ?></div>
        <div id="abh_settings_body">
            <div id="abh_settings_left" >

                <fieldset>

                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_inposts_on" type="radio" class="abh_switch-input" name="abh_inposts"  value="1" <?php echo ((ABH_Classes_Tools::getOption('abh_inposts') == 1) ? "checked" : '') ?> />
                            <label for="abh_inposts_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_inposts_off" type="radio" class="abh_switch-input" name="abh_inposts" value="0" <?php echo ((!ABH_Classes_Tools::getOption('abh_inposts')) ? "checked" : '') ?> />
                            <label for="abh_inposts_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php _e('Visible in <strong>posts</strong>', _ABH_PLUGIN_NAME_); ?></span>
                        <div class="abh_option_strictposts"><input name="abh_strictposts" type="checkbox" value="1"  <?php echo ((ABH_Classes_Tools::getOption('abh_strictposts') == 1) ? "checked" : '') ?> /><label for="abh_strictposts"><?php _e('Hide Author Box from custom posts types', _ABH_PLUGIN_NAME_); ?></label></div>

                    </div>

                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_inpages_on" type="radio" class="abh_switch-input" name="abh_inpages"  value="1" <?php echo ((ABH_Classes_Tools::getOption('abh_inpages') == 1) ? "checked" : '') ?> />
                            <label for="abh_inpages_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_inpages_off" type="radio" class="abh_switch-input" name="abh_inpages" value="0" <?php echo ((!ABH_Classes_Tools::getOption('abh_inpages')) ? "checked" : '') ?> />
                            <label for="abh_inpages_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php _e('Visible in <strong>pages</strong>', _ABH_PLUGIN_NAME_); ?></span>
                    </div>

                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_ineachpost_on" type="radio" class="abh_switch-input" name="abh_ineachpost"  value="1" <?php echo ((ABH_Classes_Tools::getOption('abh_ineachpost') == 1) ? "checked" : '') ?> />
                            <label for="abh_ineachpost_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_ineachpost_off" type="radio" class="abh_switch-input" name="abh_ineachpost" value="0" <?php echo ((!ABH_Classes_Tools::getOption('abh_ineachpost')) ? "checked" : '') ?> />
                            <label for="abh_ineachpost_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php _e('Show the Starbox with Top Star theme <strong>in the global feed of your blog</strong> (eg. "/blog" page) under each title of every post', _ABH_PLUGIN_NAME_); ?></span>
                    </div>

                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_showopengraph_on" type="radio" class="abh_switch-input" name="abh_showopengraph"  value="1" <?php echo ((ABH_Classes_Tools::getOption('abh_showopengraph') == 1) ? "checked" : '') ?> />
                            <label for="abh_showopengraph_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_showopengraph_off" type="radio" class="abh_switch-input" name="abh_showopengraph" value="0" <?php echo ((!ABH_Classes_Tools::getOption('abh_showopengraph')) ? "checked" : '') ?> />
                            <label for="abh_showopengraph_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php echo sprintf(__('Show the <strong>Open Graph</strong> Profile in meta for each author %sdetails%s (useful for rich snippets)', _ABH_PLUGIN_NAME_), '<a href="http://ogp.me/#type_profile" target="_blank">', '</a>'); ?></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php _e('Theme setting:', _ABH_PLUGIN_NAME_); ?></legend>
                    <div class="abh_option_content">

                        <div class="abh_select">
                            <select name="abh_position">
                                <option value="up" <?php echo ((ABH_Classes_Tools::getOption('abh_position') == 'up') ? 'selected="selected"' : '') ?>>Up</option>
                                <option value="down" <?php echo ((ABH_Classes_Tools::getOption('abh_position') == 'down') ? 'selected="selected"' : '') ?>>Down</option>
                            </select>
                        </div>
                        <span><?php _e('The Author Box <strong>position</strong> (Topstar and Topstar-round are always on shown on top)', _ABH_PLUGIN_NAME_); ?></span>
                    </div>

                    <div class="abh_option_content">
                        <div class="abh_select">
                            <select id="abh_theme_select" name="abh_theme">
                                <?php
                                foreach (ABH_Classes_Tools::getOption('abh_themes') as $name) {
                                    echo '<option value="' . $name . '" ' . ((ABH_Classes_Tools::getOption('abh_theme') == $name) ? 'selected="selected"' : '') . ' >' . ucfirst($name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span><?php _e('Choose the default theme to be displayed <strong>inside each blog article</strong>', _ABH_PLUGIN_NAME_); ?></span>
                    </div>

                    <div class="abh_option_content">
                        <div class="abh_select">
                            <select id="abh_titlefontsize_select" name="abh_titlefontsize">
                                <?php
                                foreach (ABH_Classes_Tools::getOption('abh_titlefontsizes') as $name) {
                                    echo '<option value="' . $name . '" ' . ((ABH_Classes_Tools::getOption('abh_titlefontsize') == $name) ? 'selected="selected"' : '') . ' >' . $name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span><?php _e('Choose the size of the name', _ABH_PLUGIN_NAME_); ?></span>
                    </div>
                    <div class="abh_option_content">
                        <div class="abh_select">
                            <select id="abh_descfontsize_select" name="abh_descfontsize">
                                <?php
                                foreach (ABH_Classes_Tools::getOption('abh_descfontsizes') as $name) {
                                    echo '<option value="' . $name . '" ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') == $name) ? 'selected="selected"' : '') . ' >' . $name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span><?php _e('Choose the size of the description', _ABH_PLUGIN_NAME_); ?></span>
                    </div>


                    <div id="abh_box_preview_title"><?php _e('Preview mode for the default theme', _ABH_PLUGIN_NAME_); ?></div>
                    <div id="abh_box_preview"><?php
                        if (file_exists(_ABH_ALL_THEMES_DIR_ . ABH_Classes_Tools::getOption('abh_theme') . '/js/frontend.js'))
                            echo '<script type="text/javascript" src="' . _ABH_ALL_THEMES_URL_ . ABH_Classes_Tools::getOption('abh_theme') . '/js/frontend.js?ver=' . ABH_VERSION . '"></script>';
                        echo '<link rel="stylesheet"  href="' . _ABH_ALL_THEMES_URL_ . ABH_Classes_Tools::getOption('abh_theme') . '/css/frontend.css?ver=' . ABH_VERSION . '" type="text/css" media="all" />';
                        global $current_user;
                        echo ABH_Classes_ObjController::getController('ABH_Controllers_Frontend')->showBox($current_user->ID);
                        ?>
                    </div>
                    <input type="text" style="display: none;" value="<?php echo $current_user->ID ?>" size="1" id="user_id" >
                    <br /><br />
                    <div class="abh_option_content">
                        <div class="abh_select">
                            <select name="abh_achposttheme">
                                <?php
                                foreach (ABH_Classes_Tools::getOption('abh_achpostthemes') as $name) {
                                    echo '<option value="' . $name . '" ' . ((ABH_Classes_Tools::getOption('abh_achposttheme') == $name) ? 'selected="selected"' : '') . ' >' . ucfirst($name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <span><?php _e('Choose the theme to be displayed in your <strong>global list of posts</strong> (eg. /blog)', _ABH_PLUGIN_NAME_); ?></span>
                    </div>



                    <div><br /><br /><?php _e('Add Starbox in the post content or widgets with the shortcode <strong>[starbox]</strong> or <strong>[starbox id=USER_ID]</strong>', _ABH_PLUGIN_NAME_); ?></div>
                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_shortcode_on" type="radio" class="abh_switch-input" name="abh_shortcode"  value="1" <?php echo ((ABH_Classes_Tools::getOption('abh_shortcode') == 1) ? "checked" : '') ?> />
                            <label for="abh_shortcode_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_shortcode_off" type="radio" class="abh_switch-input" name="abh_shortcode" value="0" <?php echo ((!ABH_Classes_Tools::getOption('abh_shortcode')) ? "checked" : '') ?> />
                            <label for="abh_shortcode_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php echo sprintf(__('Check for <strong>[starbox]</strong> shortcode in my blog. %sRead more >>%s', _ABH_PLUGIN_NAME_), '<a href="http://wordpress.org/plugins/starbox/faq/" target="_blank">', '</a>'); ?> </span>
                    </div>
                </fieldset>

            </div>
            <div id="abh_settings_right">
                <div class="panel-body f-gray-dark text-center b-b" style="    line-height: 1.5;">
                    <div style="text-align: center"><img src="https://ps.w.org/starbox/assets/icon-128x128.png?rev=1974206" style="margin: 10px auto; width: 150px;" alt="" /></div>
                    <h4>Thank you for choosing Starbox PRO, by Squirrly </h4>
                    <span style="display: block; line-height: 20px; font-size: 14px; margin-bottom: 10px;">Go to <a href="https://customer.digitalpackglobal.com/" target="_blank">Billing Information</a> to see more about your current license.</span>
                    <span style="display: block; line-height: 20px; font-size: 14px; margin-bottom: 10px;">There, you will also be able to reach out support form. Members of <a href="https://www.squirrly.co/more/" target="_blank">Squirrly</a> will help you out.</span>
                </div>
            </div>
            <div id="abh_settings_submit">
                <p><?php _e('Click "go to user settings" to setup the author box for each author you have ( including per author Google Authorship)', _ABH_PLUGIN_NAME_); ?></p>
                <?php wp_nonce_field('abh_settings_update', 'abh_nonce'); ?>
                <input type="hidden" name="action" value="abh_settings_update" />
                <input type="submit" class="abh_button" value="<?php _e('Save settings', _ABH_PLUGIN_NAME_) ?> &raquo;" />
                <a href="profile.php#abh_settings" class="abh_button"><?php _e('Go to user settings', _ABH_PLUGIN_NAME_) ?></a>
            </div>

            <div><br /><br /><?php echo sprintf(__('Use the Google Tool to check rich snippets %sclick here%s', _ABH_PLUGIN_NAME_), '<a href="http://www.google.com/webmasters/tools/richsnippets?url=' . get_bloginfo('url') . '" target="_blank">', '</a>'); ?></div>

        </div>
    </form>
</div>