<div class="tab-pane main-tabpane" id="advanced" role="tabpanel" aria-labelledby="advanсed-tab">
    <div class='main-inner-content shadowed'>

        <div class="advanced-page">
            <div class="advanced-tab-top">
                <h2><?php _e('Advanced settings', 'mpg'); ?></h2>
            </div>

            <section>
                <!-- Update database tables structure -->
                <p class="mpg-subtitle"><?php _e('Update tables structure', 'mpg'); ?></p>
                <p style="margin-top: 1rem;"><?php _e("This section allows to set up hooks which MPG will fire on, update (actualize) structure of MPG's tables after an update. The same result can be achieved by activating and deactivating the plugin", 'mpg'); ?></p>


                <button id="mpg_update_tables_structure" class="btn btn-primary"><?php _e("Update", 'mpg'); ?></button>
            </section>


            <!-- Hooks -->

            <section>
                <p class="mpg-subtitle"><?php _e('Page Builders Compatibility', 'mpg'); ?></p>
                <p style="margin-top: 1rem;"><?php _e('Use these settings only if the generated page is displayed incorrectly, there is no header, footer, or non-replaced shortcodes. MPG has a universal default setting, but sometimes it does not work properly, because different users have different plugins, builders, versions of Wordpress, and so on. Therefore, if you see problems with the pages - change these options to achieve the desired effect.', 'mpg'); ?></p>

                <p style="margin-top: 1rem;"><?php _e('As we noticed, the next configuration is working, but if no - feel free to change it to make generated pages working for you:', 'mpg'); ?></p>

                <ul style="font-size: 13px">
                    <li><?php _e('Native text editor (Guttenberg): hook name - "template_redirect", priority - high', 'mpg'); ?></li>
                    <li><?php _e('Thrive Architect: hook name - "posts_selection", priority - normal', 'mpg'); ?></li>
                    <li><?php _e('Divi pagebuilder: hook name - "posts_selection", priority - high', 'mpg'); ?></li>
                    <li><?php _e('Elementor Pro: hook name - "pre_handle_404", priority - high', 'mpg'); ?></li>
                </ul>

                <form class="mpg-hooks-block">
                    <select id="mpg_hook_name" required="required">
                        <option disabled="true" value="" selected><?php _e('Hook', 'mpg'); ?></option>
                        <option value="pre_handle_404">pre_handle_404</option>
                        <option value="posts_selection">posts_selection</option>
                        <option value="template_redirect">template_redirect</option>
                    </select>

                    <select id="mpg_hook_priority" required="required">
                        <option disabled="true" value="" selected><?php _e('Priority', 'mpg'); ?></option>
                        <option value="1"><?php _e('High', 'mpg'); ?></option>
                        <option value="10"><?php _e('Normal', 'mpg'); ?></option>
                        <option value="100"><?php _e('Low', 'mpg'); ?></option>
                    </select>

                    <button type="submit" class="btn btn-primary"><?php _e('Update', 'mpg'); ?></button>
                </form>
            </section>

            <!-- ABSPATH -->
            <section>
                <p class="mpg-subtitle"><?php _e('WordPress base path', 'mpg'); ?></p>
                <p style="margin-top: 1rem;"><?php _e('Use these settings only if problems occur when generating sitemaps. Some hosting providers change value of ABSPATH constant due to security reasons, however, this prevents the plugin from working correctly.', 'mpg'); ?></p>


                <form class="mpg-path-block">
                    <select required="required">
                        <option value="abspath">ABSPATH</option>
                        <option value="wp-content">Path based on wp-content folder location</option>
                    </select>

                    <button type="submit" class="btn btn-primary"><?php _e('Update', 'mpg'); ?></button>
                </form>
            </section>


            <section>
                <p class="mpg-subtitle"><?php _e('Cache hooks', 'mpg'); ?></p>
                <p style="margin-top: 1rem;"><?php _e('Use these settings only if the generated page with enabled caching in MPG is displayed incorrectly. MPG has a universal default setting, but sometimes it does not work properly, because different users have different plugins, builders, versions of Wordpress, and so on. Therefore, if you see problems with the pages after few visits as guest - change these options to achieve the desired effect.', 'mpg'); ?></p>

                <form class="mpg-cache-hooks-block">
                    <select id="mpg_cache_hook_name" required="required">
                        <option disabled="true" value="" selected><?php _e('Hook', 'mpg'); ?></option>
                        <option value="get_footer">get_footer</option>
                        <option value="wp_footer">wp_footer</option>
                        <option value="wp_print_footer_scripts">wp_print_footer_scripts</option>
                    </select>

                    <select id="mpg_cache_hook_priority" required="required">
                        <option disabled="true" value="" selected><?php _e('Priority', 'mpg'); ?></option>
                        <option value="1"><?php _e('High', 'mpg'); ?></option>
                        <option value="10"><?php _e('Normal', 'mpg'); ?></option>
                        <option value="100"><?php _e('Low', 'mpg'); ?></option>
                        <option value="10000"><?php _e('Very low', 'mpg'); ?></option>
                    </select>

                    <button type="submit" class="btn btn-primary"><?php _e('Update', 'mpg'); ?></button>
                </form>
            </section>

            <!-- Branding position -->
            <?php if (!mpg_app()->is_premium()) { ?>
                <section>
                    <p class="mpg-subtitle"><?php _e('Branding position', 'mpg'); ?></p>
                    <p style="margin-top: 1rem;"><?php _e('Use this setting if you want to move branding block to another side of a page ', 'mpg'); ?></p>

                    <form class="mpg-branding-position-block">
                        <select id="mpg_change_branding_position" required="required">
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                        </select>

                        <button type="submit" class="btn btn-primary"><?php _e('Update', 'mpg'); ?></button>
                    </form>
                </section>
            <?php } ?>
        </div>

    </div>
</div>

<div class="sidebar-container">
    <?php require_once('sidebar.php') ?>
</div>
</div>