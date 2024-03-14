<div class="tab-pane main-tabpane" id="sitemap" role="tabpanel" aria-labelledby="sitemap-tab">
    <div class='main-inner-content shadowed'>

        <div class="sitemap-page">
            <div class="sitemap-tab-top">
                <h2><?php _e('Sitemap', 'mpg'); ?></h2>
            </div>

            <form method="post" id="sitemap-form">
                <div class="sub-section">
                    <div class="block-with-tooltip" style="margin-bottom:20px">
                        <p><?php _e('File name', 'mpg'); ?></p>
                        <input type="text" name="sitemap_filename_input" required style="width: 100%" placeholder="multipage-sitemap" value="multipage-sitemap">
                        <div class="tooltip-circle" data-tippy-content="<?php _e('Name your file list. MPG will append .xml at the end.', 'mpg');?>"><i class="fa fa-question"></i></div>
                    </div>

                    <div class="block-with-tooltip" style="margin-bottom:20px">
                        <p><?php _e('Max URLs per sitemap file', 'mpg'); ?></p>
                        <input type="number" min="1" step="1" value="50000" required name="sitemap_max_urls_input" style="width: 100%">
                        <div class="tooltip-circle" data-tippy-content="<?php _e('This allows you to break a very large sitemap file into a main sitemap with submaps. Typically not required though some SEOs have different preferences.', 'mpg');?>"><i class="fa fa-question"></i></div>
                    </div>

                    <div class="block-with-tooltip" style="margin-bottom:20px">
                        <p><?php _e('Frequency', 'mpg'); ?></p>
                        <select name="sitemap_frequency_input" style="width: 100%" required>
                            <option value="always"><?php _e('Always', 'mpg'); ?></option>
                            <option value="hourly"><?php _e('Hourly', 'mpg'); ?></option>
                            <option value="daily"><?php _e('Daily', 'mpg'); ?></option>
                            <option value="weekly"><?php _e('Weekly', 'mpg'); ?></option>
                            <option value="monthly"><?php _e('Monthly', 'mpg'); ?></option>
                            <option value="yearly"><?php _e('Yearly', 'mpg'); ?></option>
                            <option value="never"><?php _e('Never', 'mpg'); ?></option>
                        </select>
                        <div class="tooltip-circle" data-tippy-content="<?php _e('Tell search engine how frequently you expect to update the pages. This setting typically doesn’t carry a lot of wait unless the content is cornerstone.', 'mpg');?>"><i class="fa fa-question"></i></div>
                    </div>

                    <div class="block-with-tooltip" style="margin-bottom:20px">
                        <p><?php esc_html_e( 'Priority', 'mpg' ); ?></p>
                        <input type="text" name="sitemap_priority" value="1" style="width: 100%;">

                        <div class="tooltip-circle" data-tippy-content="<?php esc_attr_e( 'This allows you to set the priority attribute value.', 'mpg' );?>"><i class="fa fa-question"></i></div>
                    </div>

                    <div class="block-with-tooltip" style="margin-bottom:20px">
                        <p><?php _e('Add sitemap to robots.txt', 'mpg'); ?></p>
                        <input type="checkbox" name="sitemap_robot" value="1" style="width: 15px; height: 16px; flex: 0 0 15px; margin-right: 400px">

                        <div class="tooltip-circle" data-tippy-content="<?php _e('MPG can automatically add the sitemap file location to your robots.txt to make it easier for search engines to find.', 'mpg');?>"><i class="fa fa-question"></i></div>
                    </div>

                </div>
                <div class="save-changes-block">
                    <input type="submit" class="generate-sitemap btn btn-primary"
                        value="<?php _e('Save and generate', 'mpg'); ?>" />
                    <div class="sitemap-status">
                        <?php _e('Current sitemap:', 'mpg'); ?> <span id="mpg_sitemap_url"></span>
                    </div>

                </div>

            </form>

        </div>
    </div>
    <!--.col-md-6 -->
    <div class="sidebar-container">
        <?php require_once('sidebar.php') ?>
    </div>
</div>