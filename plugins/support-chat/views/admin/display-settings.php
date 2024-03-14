<?php

$showOnDesktop = esc_attr(get_option('wpsaio_show_on_desktop', 1));

$showOnMobile = esc_attr(get_option('wpsaio_show_on_mobile', 1));

$displayCondition = esc_attr(get_option('wpsaio_display_condition', 'allPages'));

$includePages = get_option('wpsaio_includes_pages', []);

$excludePages = get_option('wpsaio_excludes_pages', []);

$getPagesQuery = new \WP_Query(array("posts_per_page" => -1, "post_type" => "page", "post_status" => "publish"));

?>
<div class="wrap-content-box">
    <table class="form-table">
        <p><?php echo __('Setting text and style for the floating widget.', WP_SAIO_LANG_PREFIX) ?></p>
        <tbody>
            <tr>
                <th scope="row"><label for="wpsaio-show-desktop-switch"><?php echo __('Show on desktop', WP_SAIO_LANG_PREFIX) ?></label></th>
                <td>
                    <div class="wpsaio-switch-control">
                        <input type="checkbox" id="wpsaio-show-desktop-switch" value="1" name="showOnDesktop" <?php checked($showOnDesktop, 1) ?>>
                        <label for="wpsaio-show-desktop-switch" class="green"></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="wpsaio-show-mobile-switch"><?php echo __('Show on mobile', WP_SAIO_LANG_PREFIX) ?></label></th>
                <td>
                    <div class="wpsaio-switch-control">
                        <input type="checkbox" id="wpsaio-show-mobile-switch" value="1" name="showOnMobile" <?php checked($showOnMobile, 1) ?>>
                        <label for="wpsaio-show-mobile-switch" class="green"></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="displayCondition"><?php echo __('Display', WP_SAIO_LANG_PREFIX) ?></label></th>
                <td>
                    <select name="displayCondition" id="displayCondition">
                        <option <?php selected($displayCondition, 'allPages'); ?> value="allPages"><?php echo __("Show on all pages", WP_SAIO_LANG_PREFIX) ?></option>
                        <option <?php selected($displayCondition, 'includePages'); ?> value="includePages"><?php echo __("Show on these pages...", WP_SAIO_LANG_PREFIX) ?></option>
                        <option <?php selected($displayCondition, 'excludePages'); ?> value="excludePages"><?php echo __("Hide on these pages...", WP_SAIO_LANG_PREFIX) ?></option>
                    </select>
                    <!-- <p class="description"><?php //_e("Please select 'Show on all pages except' if you want to display the widget on WooCommerce pages.", WP_SAIO_LANG_PREFIX) 
                                                ?></p> -->
                </td>
            </tr>
            <th scope="row">
                <!-- <label for="widget_show_on_pages">
                <?php // echo __('Select pages', WP_SAIO_LANG_PREFIX) 
                ?>
            </label> -->
            </th>
            <td class="nta-wa-pages-content include-pages <?php echo esc_attr($displayCondition == 'includePages' ? '' : 'hide-select') ?>">
                <input type="checkbox" id="include-pages-checkall" />
                <label for="include-pages-checkall">All</label>
                <ul id="nta-wa-display-pages-list">
                    <?php
                    $array_includes = $includePages;
                    if (!$array_includes) {
                        $array_includes = array();
                    }
                    while ($getPagesQuery->have_posts()) : $getPagesQuery->the_post();
                    ?>
                        <li>
                            <input <?php if (in_array(get_the_ID(), $array_includes)) {
                                        echo 'checked="checked"';
                                    } ?> name="includePages[]" class="includePages" type="checkbox" value="<?php esc_attr(the_ID()) ?>" id="nta-wa-hide-page-<?php esc_attr(the_ID()) ?>" />
                            <label for="nta-wa-hide-page-<?php esc_attr(the_ID()) ?>"><?php esc_html(the_title()) ?></label>
                        </li>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </ul>
            </td>

            <td class="nta-wa-pages-content exclude-pages <?php echo esc_attr($displayCondition == 'excludePages' ? '' : 'hide-select') ?>">
                <input type="checkbox" id="exclude-pages-checkall" />
                <label for="exclude-pages-checkall">All</label>
                <ul id="nta-wa-display-pages-list">
                    <?php
                    $array_excludes = $excludePages;
                    if (!$array_excludes) {
                        $array_excludes = array();
                    }
                    while ($getPagesQuery->have_posts()) : $getPagesQuery->the_post();
                    ?>
                        <li>
                            <input <?php if (in_array(get_the_ID(), $array_excludes)) {
                                        echo 'checked="checked"';
                                    } ?> name="excludePages[]" class="excludePages" type="checkbox" value="<?php esc_attr(the_ID()) ?>" id="nta-wa-show-page-<?php esc_attr(the_ID()) ?>" />
                            <label for="nta-wa-show-page-<?php esc_attr(the_ID()) ?>"><?php esc_html(the_title()) ?></label>
                        </li>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </ul>
            </td>
            </tr>
        </tbody>
    </table>
    <div class="wp_saio_panel_btn-wrap">
        <button class="wpsaio-save button button-primary button-display-settings"><?php echo __('Save Changes', WP_SAIO_LANG_PREFIX) ?><i class="dashicons dashicons-update-alt"></i></button>
    </div>
</div>