<?php defined('ABSPATH') || die('Cheatin\' uh?'); if(!isset($view)) return;?>
<div id="abh_settings" >
    <a id="abh_settings"></a>
    <div id="abh_settings_title" ><?php echo esc_html__('Starbox Settings for this Author', _ABH_PLUGIN_NAME_); ?></div>
    <div id="abh_settings_body">
        <div id="abh_settings_left" >
            <fieldset >
                <div class="abh_option_content">
                    <div class="abh_switch">
                        <input id="abh_use_on" type="radio" class="abh_switch-input" name="abh_use"  value="1" <?php echo (($view->author['abh_use'] == 1) ? "checked" : '') ?> />
                        <label for="abh_use_on" class="abh_switch-label abh_switch-label-off"><?php echo esc_html__('Yes', _ABH_PLUGIN_NAME_); ?></label>
                        <input id="abh_use_off" type="radio" class="abh_switch-input" name="abh_use" value="0" <?php echo ((!$view->author['abh_use'] == 1) ? "checked" : '') ?> />
                        <label for="abh_use_off" class="abh_switch-label abh_switch-label-on"><?php echo esc_html__('No', _ABH_PLUGIN_NAME_); ?></label>
                        <span class="abh_switch-selection"></span>
                    </div>
                    <span><?php echo esc_html__('Show the StarBox for this author', _ABH_PLUGIN_NAME_); ?></span>
                </div>

            </fieldset>
            <fieldset>
                <legend><?php echo esc_html__('Change the Profile Image', _ABH_PLUGIN_NAME_); ?></legend>
                <div class="abh_gravatar">
                    <p>
                        <?php echo esc_html__('File types: JPG, JPEG, GIF and PNG. Ideal image size is: 80x80', _ABH_PLUGIN_NAME_); ?>
                    </p>
                    <p><span class="sq_settings_info"><?php echo ((defined('ABH_MESSAGE_FAVICON')) ? esc_attr(ABH_MESSAGE_FAVICON) : '') ?></span></p>
                    <div>
                        <?php if (isset($view->author['abh_gravatar']) && $view->author['abh_gravatar'] <> '' && file_exists(_ABH_GRAVATAR_DIR_ . $view->author['abh_gravatar'])) { ?>
                            <img src="<?php echo esc_url(_ABH_GRAVATAR_URL_) . esc_attr($view->author['abh_gravatar']) . '?' . esc_attr(time()) ?>" width="<?php echo esc_attr(ABH_IMAGESIZE)?>" class="photo" />
                            <?php
                        } else {

                            echo get_avatar($view->user->ID, ABH_IMAGESIZE);
                        }
                        ?>
                        <div class="abh_upload">
                            <input type="file" name="abh_gravatar" autocomplete="off" />
                            <input type="submit"  id="abh_gravatar_update" name="abh_update" value="<?php echo esc_attr__('Upload', _ABH_PLUGIN_NAME_) ?>" />
                            <div class="abh_upload_reset"><label for="abh_resetgravatar"><?php echo esc_html__('Reset the uploaded image', _ABH_PLUGIN_NAME_); ?></label><input name="abh_resetgravatar" type="checkbox" value="1" /></div>
                            <span class="abh_settings_info"><?php echo sprintf(esc_attr__('You can also set your image on %shttps://en.gravatar.com/%s for your email address', _ABH_PLUGIN_NAME_), '<a href="https://en.gravatar.com/" target="_blank">', '</a>'); ?></span>
                        </div>
                    </div>
                </div>

            </fieldset>
            <fieldset>
                <legend><?php echo esc_html__('Theme settings:', _ABH_PLUGIN_NAME_); ?></legend>
                <div class="abh_option_content">
                    <div class="abh_select">
                        <select name="abh_position">
                            <?php
                            if (isset($view->author['abh_position']))
                                $position = $view->author['abh_position'];
                            else
                                $position = 'default';
                            ?>
                            <option value="default" <?php echo (($position == 'default') ? 'selected="selected"' : '') ?>><?php echo esc_html__('Default', _ABH_PLUGIN_NAME_); ?></option>
                            <option value="up" <?php echo (($position == 'up') ? 'selected="selected"' : '') ?>><?php echo esc_html__('Up', _ABH_PLUGIN_NAME_); ?></option>
                            <option value="down" <?php echo (($position == 'down') ? 'selected="selected"' : '') ?>><?php echo esc_html__('Down', _ABH_PLUGIN_NAME_); ?></option>
                        </select>
                    </div>
                    <span><?php echo esc_html__('The Author Box position', _ABH_PLUGIN_NAME_); ?></span>
                </div>

                <div class="abh_option_content">

                    <div class="abh_select">
                        <select id="abh_theme_select" name="abh_theme">
                            <?php
                            if (isset($view->author['abh_theme']))
                                $theme = $view->author['abh_theme'];
                            else
                                $theme = 'default';

                            foreach ($view->themes as $name) {
                                echo '<option value="' . esc_attr($name) . '" ' . (($theme == $name) ? 'selected="selected"' : '') . ' >' . esc_html(ucfirst($name)) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <span><?php echo esc_html__('This Author\'s theme', _ABH_PLUGIN_NAME_); ?></span>

                </div>

                <div class="abh_option_content" style="display: none">
                    <div class="abh_select">
                        <select id="abh_titlefontsize_select" name="abh_titlefontsize">
                            <?php
                            foreach (ABH_Classes_Tools::getOption('abh_titlefontsizes') as $name) {
                                echo '<option value="' . esc_attr($name) . '" ' . ((ABH_Classes_Tools::getOption('abh_titlefontsize') == $name) ? 'selected="selected"' : '') . ' >' . esc_html($name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <span><?php echo esc_html__('Choose the size of the name', _ABH_PLUGIN_NAME_); ?></span>

                    <div class="abh_select">&nbsp;
                        <select id="abh_descfontsize_select" name="abh_descfontsize">
                            <?php
                            foreach (ABH_Classes_Tools::getOption('abh_descfontsizes') as $name) {
                                echo '<option value="' . esc_attr($name) . '" ' . ((ABH_Classes_Tools::getOption('abh_descfontsize') == $name) ? 'selected="selected"' : '') . ' >' . esc_html($name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <span><?php echo esc_html__('Choose the size of the description', _ABH_PLUGIN_NAME_); ?></span>
                </div>

                <div id="abh_box_preview_title"><?php echo esc_html__('Preview mode (change the theme)', _ABH_PLUGIN_NAME_); ?></div>
                <div id="abh_box_preview"><?php
                    if ($theme == 'default')
                        $theme = ABH_Classes_Tools::getOption('abh_theme');
                    if (file_exists((_ABH_ALL_THEMES_DIR_ . $theme . '/js/frontend.js')))
                        echo '<script type="text/javascript" src="' . esc_url(_ABH_ALL_THEMES_URL_ . $theme . '/js/frontend.js?ver=' . ABH_VERSION) . '"></script>';
                    echo '<link rel="stylesheet"  href="' . esc_url(_ABH_ALL_THEMES_URL_ . $theme . '/css/frontend.css?ver=' . ABH_VERSION) . '" type="text/css" media="all" />';

                    echo ABH_Classes_ObjController::getController('ABH_Controllers_Frontend')->showBox($view->user->ID);
                    ?></div>
            </fieldset>
            <fieldset>
                <legend><?php echo esc_html__('Job settings:', _ABH_PLUGIN_NAME_); ?></legend>
                <div>
                    <p><span><?php echo esc_html__('Job Title:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_title" value="<?php echo esc_attr($view->author['abh_title']); ?>" size="30" /></p>
                    <p><span><?php echo esc_html__('Company:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_company" value="<?php echo esc_attr($view->author['abh_company']); ?>" size="30" /></p>
                    <p><span><?php echo esc_html__('Company URL:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_company_url" value="<?php echo esc_url($view->author['abh_company_url']); ?>" size="30" /></p>
                    <p class="abh_description_author"></p>
                    <p class="abh_show_extra_description" <?php echo (($view->author['abh_extra_description'] == '') ? '' : 'style="display: none"'); ?>><?php echo esc_html__('add custom author bio >>', _ABH_PLUGIN_NAME_); ?></p>
                    <p class="abh_extra_description" <?php echo (($view->author['abh_extra_description'] <> '') ? '' : 'style="display: none"'); ?>>
                        <span> </span><span style="font-size:12px; font-weight: normal; margin-left: 15px; font-style: italic;"><?php echo esc_html__('By adding text here, you will replace the above description with this one', _ABH_PLUGIN_NAME_); ?></span>
                        <br style="clear:both;" />
                        <span><?php echo esc_html__('Author BIO:', _ABH_PLUGIN_NAME_); ?></span> <textarea id="abh_extra_description" name="abh_extra_description"  ><?php echo esc_html($view->author['abh_extra_description']); ?></textarea>
                        <br style="clear:both;" />
                        <span> </span><a href="javascript:void(0);" onclick="jQuery('#abh_extra_description').val('')" style="font-size:12px; font-weight: normal; margin-left: 15px;"><?php echo esc_html__('Clear the custom description and show the default description', _ABH_PLUGIN_NAME_); ?></a>
                    </p>
                </div>
            </fieldset>
            <fieldset >
                <legend><?php echo esc_html__('Social settings:', _ABH_PLUGIN_NAME_); ?></legend>

                <div id="abh_option_social" >
                    <p class="abh_social_text" style="height:30px; line-height: 30px;">
                        <span><?php echo esc_html__('Social text (12 chars):', _ABH_PLUGIN_NAME_); ?></span>
                        <span ><input name="abh_socialtext" value="<?php echo esc_attr($view->author['abh_socialtext']); ?>" size="30" maxlength="12" style="min-width: 100px; width: 100px;" /></span>
                        <span style="font-size:12px; font-weight: normal; font-style: italic; margin-left: 5px;"><?php echo esc_html__('eq. "Follow me"', _ABH_PLUGIN_NAME_); ?></span>
                    </p>
                    <p><span class="abh_social_settings abh_twitter"></span><span><?php echo esc_html__('Twitter:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_twitter" value="<?php echo esc_attr($view->author['abh_twitter']); ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_facebook"></span><span><?php echo esc_html__('Facebook:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_facebook" value="<?php echo esc_attr($view->author['abh_facebook']); ?>" size="30" /></p>
                    <p style="font-size: 18px; color: red;"><?php echo sprintf(esc_html__('Need more Socials Links and High Priority support? Visit %sStarbox PRO%s', _ABH_PLUGIN_NAME_), '<a href="http://starbox.squirrly.co/product/starbox-the-author-box-for-humans/" target="_blank">', '</a>'); ?></p>
                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_nofollow_social_on" type="radio" class="abh_switch-input" name="abh_nofollow_social"  value="1" <?php echo ((!$view->author['abh_nofollow_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_nofollow_social_on" class="abh_switch-label abh_switch-label-off"><?php echo esc_html__('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_nofollow_social_off" type="radio" class="abh_switch-input" name="abh_nofollow_social" value="0" <?php echo (($view->author['abh_nofollow_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_nofollow_social_off" class="abh_switch-label abh_switch-label-on"><?php echo esc_html__('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php echo esc_html__('Add rel="nofollow" to Social links and user URL', _ABH_PLUGIN_NAME_); ?></span>
                    </div>
                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_noopener_social_on" type="radio" class="abh_switch-input" name="abh_noopener_social"  value="1" <?php echo ((!$view->author['abh_noopener_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_noopener_social_on" class="abh_switch-label abh_switch-label-off"><?php echo esc_html__('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_noopener_social_off" type="radio" class="abh_switch-input" name="abh_noopener_social" value="0" <?php echo (($view->author['abh_noopener_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_noopener_social_off" class="abh_switch-label abh_switch-label-on"><?php echo esc_html__('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php echo esc_html__('Add rel="noopener" to Social links and user URL', _ABH_PLUGIN_NAME_); ?></span>
                    </div>
                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_noreferrer_social_on" type="radio" class="abh_switch-input" name="abh_noreferrer_social"  value="1" <?php echo ((!$view->author['abh_noreferrer_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_noreferrer_social_on" class="abh_switch-label abh_switch-label-off"><?php echo esc_html__('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_noreferrer_social_off" type="radio" class="abh_switch-input" name="abh_noreferrer_social" value="0" <?php echo (($view->author['abh_noreferrer_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_noreferrer_social_off" class="abh_switch-label abh_switch-label-on"><?php echo esc_html__('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php echo esc_html__('Add rel="noreferrer" to Social links and user URL', _ABH_PLUGIN_NAME_); ?></span>
                    </div>

                </div>
            </fieldset>

            <div id="abh_settings_title" >&nbsp;</div>
        </div>


    </div>
</div>