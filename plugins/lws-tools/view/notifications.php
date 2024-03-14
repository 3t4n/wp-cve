<?php $arr = array('strong' => array()); ?>

<div class="lws_tk_table">
    <!-- Update WordPress -->
    <div
        class="lws_tk_tab_line lws_tk_tab_border <?php echo $up_to_date == "1" ? esc_attr("lws_tk_tab_border_red") : esc_attr("lws_tk_tab_border_green");?>">
        <?php if ($up_to_date == "1") : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
            <span
                class="lws_tk_basic"><?php echo wp_kses(__('Your current <strong>WordPress</strong> version is outdated and should be updated!', 'lws-tools'), $arr)?></span>
            <span
                class="lws_tk_bulle_version_bad"><?php esc_html($actual_version);?></span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                    <?php esc_html_e('A new version of WordPress is available', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php else : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class="lws_tk_uptodate"><?php echo wp_kses(__('Congrats, your <strong>WordPress</strong> is up to date!', 'lws-tools'), $arr) ?></span>
            <span
                class="lws_tk_bulle_version"><?php echo esc_html($actual_version);?></span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No updates available', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php endif ?>
    </div>

    <!-- Update Plugins -->
    <?php if (count($plugins_update)) : ?>
    <div class=" lws_tk_tab_border lws_tk_tab_border_red">
        <div class="lws_tk_tab_line">
            <div class="lws_tk_tab clickable" onclick="lws_tk_open_submenu(this.children[2])">
                <img class="lws_tk_image" width="25px" height="22px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
                <span
                    class=""><?php printf(wp_kses(__('<strong>%d plugin(s)</strong> in need of an update', 'lws-tools'), $arr), count($plugins_update)) ?></span>
                <img class="lws_tk_image_chevron" width="15px" height="8px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/chevron.svg')?>">
            </div>

            <div class="lws_tk_tab_button">
                <button class="lws_tk_update_button" name="lws_tk_update_all_plugins"
                    onclick="lws_tk_updateAllPlugin(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                        <?php printf(esc_html__('Update all plugins (%d)', 'lws-tools'), count($plugins_update)) ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                        <span><?php esc_html_e("Update in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Updated', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
        </div>
        <div class="lws_tk_tab_line_submenu">
            <?php foreach ($plugins_update as $key => $p_update) : ?>
            <div class="lws_tk_tab_submenu">
                <?php echo(esc_html($p_update['name'])); ?>
                <button class="lws_tk_update_element_button"
                    id="<?php echo "lws_tk_update_plugin_specific_" . $p_update['slug'] ?>"
                    name="lws_tk_update_plugin_specific"
                    value="<?php echo(esc_attr($p_update['package'])); ?>"
                    onclick="lws_tk_updatePlugin(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj_red.svg')?>">
                        <?php esc_html_e('Update this plugin', 'lws-tools')?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading_red.svg')?>">
                        <span><?php esc_html_e("Update in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_red.svg')?>">
                        <?php esc_html_e('Updated', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php else : ?>
    <div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_green">
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class=""><?php echo wp_kses(__('All your <strong>plugins</strong> are up to date', 'lws-tools'), $arr) ?></span>
        </div>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="lws_tk_update_all_plugins">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No updates available', 'lws-tools') ?>
                </span>
            </button>
        </div>
    </div>
    <?php endif ?>

    <!-- Update Themes -->
    <?php if (count($themes_update)) : ?>
    <div class=" lws_tk_tab_border lws_tk_tab_border_red">
        <div class="lws_tk_tab_line">
            <div class="lws_tk_tab clickable" onclick="lws_tk_open_submenu(this.children[2])">
                <img class="lws_tk_image" width="25px" height="22px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
                <span
                    class=""><?php printf(wp_kses(__('<strong>%d theme(s)</strong> in need of an update', 'lws-tools'), $arr), count($themes_update)) ?></span>
                <img class="lws_tk_image_chevron" width="15px" height="8px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/chevron.svg')?>">
            </div>

            <div class="lws_tk_tab_button">
                <button class="lws_tk_update_button" name="lws_tk_update_all_themes"
                    onclick="lws_tk_updateAllTheme(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                        <?php printf(esc_html__('Update all themes (%d)', 'lws-tools'), count($themes_update)) ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                        <span><?php esc_html_e("Update in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Updated', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
        </div>
        <div class="lws_tk_tab_line_submenu">
            <?php foreach ($themes_update as $key => $t_update) : ?>
            <div class="lws_tk_tab_submenu">
                <?php echo(esc_html($t_update['name'])); ?>
                <button class="lws_tk_update_element_button"
                    id="<?php echo esc_attr("lws_tk_update_theme_specific_" . $t_update['slug']) ?>"
                    name="lws_tk_update_theme_specific"
                    value="<?php echo(esc_attr($t_update['slug'])); ?>"
                    onclick="lws_tk_updateTheme(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj_red.svg')?>">
                        <?php esc_html_e('Update this theme', 'lws-tools')?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading_red.svg')?>">
                        <span><?php esc_html_e("Update in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_red.svg')?>">
                        <?php esc_html_e('Updated', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php else : ?>
    <div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_green">
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class=""><?php echo wp_kses(__('All your <strong>themes</strong> are up to date', 'lws-tools'), $arr) ?></span>
        </div>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="lws_tk_update_all_themes">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No updates available', 'lws-tools') ?>
                </span>
            </button>
        </div>
    </div>
    <?php endif ?>

    <!-- Unused Plugins -->
    <?php if (count($unused_plugins)) : ?>
    <div class=" lws_tk_tab_border lws_tk_tab_border_orange">
        <div class="lws_tk_tab_line">
            <div class="lws_tk_tab clickable" onclick="lws_tk_open_submenu(this.children[2])">
                <img class="lws_tk_image" width="25px" height="22px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/warning.svg')?>">
                <span
                    class=""><?php printf(wp_kses(__('<strong>%1$d plugin(s)</strong> are unused out of %2$d', 'lws-tools'), $arr), $inactive_plugins, $count_plugins) ?></span>
                <img class="lws_tk_image_chevron" width="15px" height="8px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/chevron.svg')?>">
            </div>

            <div class="lws_tk_tab_button">
                <button class="lws_tk_delete_button" name="lws_tk_delete_all_plugins"
                    onclick="lws_tk_deleteAllPlugin(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                        <?php printf(esc_html__('Delete all unused plugins (%d)', 'lws-tools'), count($unused_plugins)) ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                        <span><?php esc_html_e("Deletion in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Deleted', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
        </div>
        <div class="lws_tk_tab_line_submenu">
            <?php foreach ($unused_plugins as $key => $p_delete) : ?>
            <div class="lws_tk_tab_submenu lws_tk_warning">
                <?php echo(esc_html($p_delete['name'])); ?>
                <button class="lws_tk_update_element_button"
                    id="<?php echo "lws_tk_delete_plugin_specific_" . $p_delete['slug'] ?>"
                    name="lws_tk_delete_plugin_specific"
                    value="<?php echo(esc_attr($p_delete['package'])); ?>"
                    onclick="lws_tk_deletePlugin(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer_red.svg')?>">
                        <?php esc_html_e('Delete this plugin', 'lws-tools')?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading_red.svg')?>">
                        <span><?php esc_html_e("Deletion in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_red.svg')?>">
                        <?php esc_html_e('Deleted', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php else : ?>
    <div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_green">
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class=""><?php echo wp_kses(__('No unused <strong>plugins</strong> on this website', 'lws-tools'), $arr) ?></span>
        </div>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No unused plugins', 'lws-tools') ?>
                </span>
            </button>
        </div>
    </div>
    <?php endif ?>

    <!-- Unused Themes -->
    <?php if (count($unused_themes)) : ?>
    <div class=" lws_tk_tab_border lws_tk_tab_border_orange">
        <div class="lws_tk_tab_line">
            <div class="lws_tk_tab clickable" onclick="lws_tk_open_submenu(this.children[2])">
                <img class="lws_tk_image" width="25px" height="22px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/warning.svg')?>">
                <span
                    class=""><?php printf(wp_kses(__('<strong>%1$d theme(s)</strong> are unused out of %2$d', 'lws-tools'), $arr), $count_inactive_themes, $count_themes) ?></span>
                <img class="lws_tk_image_chevron" width="15px" height="8px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/chevron.svg')?>">
            </div>

            <div class="lws_tk_tab_button">
                <button class="lws_tk_delete_button" name="lws_tk_delete_all_themes"
                    onclick="lws_tk_deleteAllTheme(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer.svg')?>">
                        <?php printf(esc_html__('Delete all unused themes (%d)', 'lws-tools'), count($unused_themes)) ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                        <span><?php esc_html_e("Deletion in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Deleted', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
        </div>
        <div class="lws_tk_tab_line_submenu">
            <?php foreach ($unused_themes as $key => $t_delete) : ?>
            <div class="lws_tk_tab_submenu lws_tk_warning">
                <?php echo(esc_html($t_delete['name'])); ?>
                <button class="lws_tk_update_element_button"
                    id="<?php echo "lws_tk_delete_theme_specific_" . $t_delete['slug'] ?>"
                    name="lws_tk_delete_theme_specific"
                    value="<?php echo(esc_attr($t_delete['slug'])); ?>"
                    onclick="lws_tk_deleteTheme(this)">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/supprimer_red.svg')?>">
                        <?php esc_html_e('Delete this theme', 'lws-tools')?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading_red.svg')?>">
                        <span><?php esc_html_e("Deletion in progress...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_red.svg')?>">
                        <?php esc_html_e('Deleted', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php else : ?>
    <div class="lws_tk_tab_line lws_tk_tab_border lws_tk_tab_border_green">
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class=""><?php echo wp_kses(__('No unused <strong>themes</strong> on this website', 'lws-tools'), $arr) ?></span>
        </div>
        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No unused themes', 'lws-tools') ?>
                </span>
            </button>
        </div>
    </div>
    <?php endif ?>

    <!-- Update Translations -->
    <div
        class="lws_tk_tab_line lws_tk_tab_border <?php echo $translations_ready ? esc_attr("lws_tk_tab_border_orange") : esc_attr("lws_tk_tab_border_green");?>">
        <?php if ($translations_ready) : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/warning.svg')?>">
            <span
                class="lws_tk_basic"><?php echo wp_kses(__('You have <strong>translations</strong> in need of an update', 'lws-tools'), $arr)?></span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button" name="lws_tk_update_trad" id="lws_tk_update_trad"
                onclick="lws_tk_updateTrads(this)">
                <span class="" name="update">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                    <?php esc_html_e('Update translations', 'lws-tools') ?>
                </span>
                <span class="hidden" name="loading">
                    <img class="lws_tk_image" width="15px" height="15px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                    <span
                        id="loading_1"><?php esc_html_e("Update in progress...", "lws-tools");?></span>
                </span>
                <span class="hidden" name="validated">
                    <img class="lws_tk_image" width="18px" height="18px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                    <?php esc_html_e('Updated', 'lws-tools'); ?>
                </span>
            </button>
        </div>
        <?php else : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class="lws_tk_uptodate"><?php echo wp_kses(__('All <strong>translations</strong> are up to date', 'lws-tools'), $arr) ?></span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('No updates available', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php endif ?>
    </div>

    <!-- Update DB Prefix -->
    <div
        class="lws_tk_tab_line lws_tk_tab_border <?php echo $db_prefix ? esc_attr("lws_tk_tab_border_red") : esc_attr("lws_tk_tab_border_green");?>">
        <?php if ($db_prefix) : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
            <span
                class="lws_tk_basic"><?php echo wp_kses(__('You are using the default <strong>database prefix</strong>.', 'lws-tools'), $arr)?></span>

            <span class="lws_tk_tooltip_content">
                <img class="lws_tk_images_left_table" id="lws_tk_tooltip_prefix>" width="15px" height="15px"
                    style="vertical-align:middle"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/infobulle.svg')?>">
                <span>
                    <?php esc_html_e("A database prefix lets WordPress detect which table of a database are theirs to use. By default, it is 'wp_'. In those tables are stocked sensible informations such as your login details, for example, meaning it is the target of hackers. By changing it to a random chain of character, it makes it harder for hacker to access your website.", 'lws-tools');?>
                </span>
            </span>
        </div>

        <form method="POST">
            <?php wp_nonce_field( 'lws_tk_update_prefix', 'nonce_updating_prefix_nonce' ); ?>
            <div class="lws_tk_tab_button">
                <button class="lws_tk_update_button" name="lws_tk_update_prefix" onclick="lws_tk_changePrefix(this)"
                    type="submit">
                    <span class="" name="update">
                        <img class="lws_tk_image" width="19px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                        <?php esc_html_e('Modify prefix', 'lws-tools') ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_tk_image" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                        <span
                            id="loading_1"><?php esc_html_e("Modifying...", "lws-tools");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_tk_image" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Modified', 'lws-tools'); ?>
                    </span>
                </button>
            </div>
        </form>
        <?php else : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class="lws_tk_uptodate"><?php echo wp_kses(__('You are using a custom <strong>database prefix</strong>', 'lws-tools'), $arr) ?></span>
            <span class="lws_tk_tooltip_content">
                <img class="lws_tk_images_left_table" id="lws_tk_tooltip_prefix" width="15px" height="15px"
                    style="vertical-align:middle"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/infobulle.svg')?>">
                <span>
                    <?php esc_html_e("A database prefix lets WordPress detect which table of a database are theirs to use. By default, it is 'wp_'. In those tables are stocked sensible informations such as your login details, for example, meaning it is the target of hackers. By changing it to a random chain of character, it makes it harder for hacker to access your website.", 'lws-tools');?>
                </span>
            </span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('Secured prefix', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php endif ?>
    </div>

    <!-- SSL -->
    <div
        class="lws_tk_tab_line lws_tk_tab_border <?php echo $cert_invalid ? esc_attr("lws_tk_tab_border_red") : esc_attr("lws_tk_tab_border_green");?>">
        <?php if ($cert_invalid) : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce_bas.svg')?>">
            <span
                class="lws_tk_basic"><?php printf(wp_kses(__('Your <strong>SSL certificate</strong> has expired since %s. Your connexion is not secure.', 'lws-tools'), $arr), $expiration_ssl)?></span>
            <span class="lws_tk_tooltip_content">
                <img class="lws_tk_images_left_table" id="lws_tk_tooltip_SSL" width="15px" height="15px"
                    style="vertical-align:middle"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/infobulle.svg')?>">
                <span>
                    <?php esc_html_e('To secure your website, you need to use an HTTPS connection, which allows encoding of the data being exchanged between you and the website. You can get a free certificate easily or use the one provided by LWS with your hosting.', 'lws-tools');?>
                </span>
            </span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_update_button lws_tk_not_clickable" name="">
                <span class="" name="update">
                    <img class="lws_tk_image" width="19px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/maj.svg')?>">
                    <?php esc_html_e('Your website is in HTTP', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php else : ?>
        <div class="lws_tk_tab">
            <img class="lws_tk_image" width="25px" height="22px"
                src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/pouce.svg')?>">
            <span
                class="lws_tk_uptodate"><?php printf(wp_kses(__('<strong>SSL Certificate</strong> valid until %s', 'lws-tools'), $arr), $expiration_ssl)?></span>
            <span class="lws_tk_tooltip_content">
                <img class="lws_tk_images_left_table" id="lws_tk_tooltip_SSL" width="15px" height="15px"
                    style="vertical-align:middle"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/infobulle.svg')?>">
                <span>
                    <?php esc_html_e('To secure your website, you need to use an HTTPS connection, which allows encoding of the data being exchanged between you and the website. You can get a free certificate easily or use the one provided by LWS with your hosting.', 'lws-tools');?>
                </span>
            </span>
        </div>

        <div class="lws_tk_tab_button">
            <button class="lws_tk_green_update_button" name="">
                <span class="" name="">
                    <img class="lws_tk_image" width="17px" height="20px"
                        src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securiser.svg')?>">
                    <?php esc_html_e('Your website is in HTTPS', 'lws-tools') ?>
                </span>
            </button>
        </div>
        <?php endif ?>
    </div>
</div>


<script>
    function lws_tk_open_submenu(chevron) {
        chevron.parentNode.parentNode.nextElementSibling.classList.toggle('lws_tk_submenu_shown');
        chevron.classList.toggle('lws_tk_chevron_flip');
    }
</script>
<script>
    function lws_tk_updateAllPlugin(button) {
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button');
        button.setAttribute('disabled', true);
        jQuery("button[name^='lws_tk_update_plugin_specific']").prop('disabled', true);
        var data = {
            action: "lwstools_updateAllPlugin",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_update_every_plugin')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery("button[name^='lws_tk_update_all_plugins'");
            button.children()[0].classList.add('hidden');
            button.children()[1].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.addClass('lws_tk_validated_button');
            var childButton = jQuery("button[name^='lws_tk_update_plugin_specific']");
            childButton.each(function(i) {
                this.children[0].classList.add('hidden');
                this.children[2].classList.remove('hidden');
            });
        });
    }

    function lws_tk_updatePlugin(button) {
        var button_id = button.id;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lwstools_updatePlugin",
            lws_tk_update_plugin_specific: button.value,
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_update_one_plugin')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            //location.reload();
        });
    }

    function lws_tk_updateAllTheme(button) {
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button');
        button.setAttribute('disabled', true);
        jQuery("button[name^='lws_tk_update_theme_specific']").prop('disabled', true);
        var data = {
            action: "lwstools_updateAllTheme",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_update_all_theme')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery("button[name^='lws_tk_update_all_themes'");
            button.children()[0].classList.add('hidden');
            button.children()[1].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.addClass('lws_tk_validated_button');
            var childButton = jQuery("button[name^='lws_tk_update_themes_specific']");
            childButton.each(function(i) {
                this.children[0].classList.add('hidden');
                this.children[2].classList.remove('hidden');
            });
        });
    }

    function lws_tk_updateTheme(button) {
        var button_id = button.id;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lwstools_updateTheme",
            lws_tk_update_theme_specific: button.value,
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_update_one_theme')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            //location.reload();
        });
    }

    function lws_tk_deleteAllPlugin(button) {
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button');
        button.setAttribute('disabled', true);
        jQuery("button[name^='lws_tk_delete_plugin_specific']").prop('disabled', true);
        var data = {
            action: "lwstools_deleteAllPlugin",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_delete_all_plugin')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery("button[name^='lws_tk_delete_all_plugins'");
            button.children()[0].classList.add('hidden');
            button.children()[1].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.addClass('lws_tk_validated_button');
            var childButton = jQuery("button[name^='lws_tk_delete_plugin_specific']");
            childButton.each(function(i) {
                this.children[0].classList.add('hidden');
                this.children[2].classList.remove('hidden');
            });

        });
    }

    function lws_tk_deletePlugin(button) {
        var button_id = button.id;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lwstools_deletePlugin",
            lws_tk_delete_plugin_specific: button.value,
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_delete_one_plugin')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
        });
    }

    function lws_tk_deleteAllTheme(button) {
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button');
        button.setAttribute('disabled', true);
        jQuery("button[name^='lws_tk_delete_theme_specific']").prop('disabled', true);
        var data = {
            action: "lwstools_deleteAllTheme",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_delete_all_theme')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery("button[name^='lws_tk_delete_all_themes'");
            button.children()[0].classList.add('hidden');
            button.children()[1].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.addClass('lws_tk_validated_button');
            var childButton = jQuery("button[name^='lws_tk_delete_theme_specific']");
            childButton.each(function(i) {
                this.children[0].classList.add('hidden');
                this.children[2].classList.remove('hidden');
            });

        });
    }

    function lws_tk_deleteTheme(button) {
        var button_id = button.id;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lwstools_deleteTheme",
            lws_tk_delete_theme_specific: button.value,
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_delete_one_theme')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
        });
    }

    function lws_tk_updateTrads(button) {
        var button_id = button.id;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button');
        var data = {
            action: "lwstools_updateTrads",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_upgrade_tools_trad')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button');
        });
    }

    function lws_tk_updateWP(button) {
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
    }

    function lws_tk_changePrefix(button) {
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
    }
</script>