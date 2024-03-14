<?php include WOBEL_VIEWS_DIR . "layouts/header.php"; ?>

<div id="wobel-body">
    <div class="wobel-tabs wobel-tabs-main">
        <div class="wobel-tabs-navigation">
            <nav class="wobel-tabs-navbar">
                <ul class="wobel-tabs-list" data-type="url" data-content-id="wobel-main-tabs-contents">
                    <?php echo sprintf('%s', apply_filters('wobel_top_navigation_buttons', '')); ?>
                </ul>
            </nav>

            <div class="wobel-top-nav-filters-per-page">
                <select id="wobel-quick-per-page" title="The number of products per page">
                    <?php
                    if (!empty($count_per_page_items)) :
                        foreach ($count_per_page_items as $count_per_page_item) :
                    ?>
                            <option value="<?php echo intval($count_per_page_item); ?>" <?php if (isset($current_settings['count_per_page']) && $current_settings['count_per_page'] == intval($count_per_page_item)) : ?> selected <?php endif; ?>>
                                <?php echo esc_html($count_per_page_item); ?>
                            </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="wobel-items-pagination"></div>
        </div>

        <div class="wobel-tabs-contents" id="wobel-main-tabs-contents">
            <div class="wobel-wrap">
                <div class="wobel-tab-middle-content">
                    <div class="wobel-table" id="wobel-items-table">
                        <p style="width: 100%; text-align: center; padding: 10px 0;"><img src="<?php echo WOBEL_IMAGES_URL . 'loading.gif'; ?>" width="30" height="30"></p>
                    </div>
                    <div class="wobel-items-count"></div>
                </div>
            </div>
        </div>

        <div class="wobel-created-by">
            <a href="https://ithemelandco.com" target="_blank">Created by iThemelandCo</a>
        </div>
    </div>
</div>

<?php include_once  WOBEL_VIEWS_DIR . "layouts/footer.php"; ?>