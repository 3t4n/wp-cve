<?php
if (!function_exists('wshs_page_list')) {

    function wshs_page_list() {
        global $wpdb;
        $atts = array();
        $default_title = 'Page sitemap';

        // Sanitize and validate input
        $get_id = (isset($_POST['id']) && is_numeric($_POST['id'])) ? intval($_POST['id']) : 0;
        if ($get_id) {
            $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}" . WSHS_SAVED_CODE_TABLE . " WHERE id = %d", $get_id);
            $row = $wpdb->get_row($sql);
            if ($row) {
                $default_title = $row->title;
                $atts = shortcode_parse_atts($row->attributes);
            }
        }
        ?>
        <script> var existing_atts = <?php echo json_encode($atts); ?>; </script>
        <div class="wrap wtl-main">
            <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
            <hr class="wp-header-end">
            <div id="post-body" class="metabox-holder columns-3">
                <!-- Top Navigation -->
                <div class="sitemap-wordpress">
                    <h2 class="nav-tab-wrapper">
                        <a href="?page=wshs_page_list" class="nav-tab nav-tab-active">Pages</a>
                        <a href="?page=wshs_post_list" class="nav-tab ">Posts</a>
                        <a href="?page=wshs_saved" class="nav-tab ">Saved Shortcodes</a>
                        <a href="?page=wshs_documentation" class="nav-tab">Documentation</a>
                    </h2>
                    <div class="sitemap-pages">
                        <div class="shortcode-container">
                            <!-- Get all registered post types -->
                            <?php $allposttypes = get_post_types(array('show_in_nav_menus' => 1)); ?>
                            <!-- Admin page sitemap -->
                            <div class="admin-field-section admin-page">
                                <fieldset>
                                    <label>1. Select page</label>
                                    <select name="wshs_select_type" class="wshs_select_type_page wshs-field" id="wshs_select_type">
                                        <option value="">Select page</option>
                                        <?php
                                        $postsarray = array();
                                        foreach ($allposttypes as $slug => $title) :
                                            $postdetails = get_post_type_object($slug);
                                            $postsarray[$slug] = array('title' => $postdetails->label, 'hierarchical' => $postdetails->hierarchical);
                                            if ($title == 'page') :
                                                ?>
                                                <option data-title="Page Sitemap" value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($postdetails->label); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>2. Order by</label>
                                    <select name="wshs_select_order" class="wshs-field" id="wshs_select_order_page" disabled>
                                        <option value="date">Date</option>
                                        <option value="title">Title</option>
                                        <option value="menu_order">Menu Order</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>3. Order</label>
                                    <select name="wshs_select_order_asc" class="wshs-field" id="wshs_select_order_asc_page" disabled>
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>4. Display only childs of</label>
                                    <select name="wshs_select_parent" class="wshs-field" id="wshs_select_parent" disabled>
                                        <option value="">Select parent</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>5. Display depth</label>
                                    <select name="wshs_select_depth" class="wshs_select_depth wshs-field" id="wshs_select_depth" disabled>
                                        <option value="">Select depth</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>6. Column layout</label>
                                    <select name="wshs_select_column_page" class="wshs_select_column_page wshs-field" id="wshs_select_column_page" disabled>
                                        <option value="" data-title="">Select column</option>
                                        <option data-title="single-column" value="full">Single column</option>
                                        <option data-title="two-columns" value="half">Two columns</option>
                                    </select>
                                </fieldset>
                                <fieldset class="position-page">
                                    <label>Column position</label>
                                    <select name="wshs_select_column_position_page" class="wshs_select_column_position_page wshs-field" id="wshs_select_column_position_page">
                                        <option value="">Select position</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </fieldset>
                                <fieldset class="field-checkbox">
                                    <label>6. Display image </label>
                                    <input type="checkbox" name="wshs_display_image" id="wshs_display_image_page" disabled>
                                    <div class="wshs_image_size_page">
                                        <input type="number" min="60" name="wshs_image_width_page" placeholder="Width"> * <input placeholder="Height" type="number" min="60" name="wshs_image_height_page">
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label>7. Display excerpt </label>
                                    <input type="checkbox" name="wshs_display_excerpt_page" id="wshs_display_excerpt_page" disabled>
                                    <div class="wshs_excerpt_limit_page">
                                        <input min="10" type="number" name="wshs_excerpt_length_page" value="100">
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label>8. Show date </label>
                                    <input type="checkbox" name="wshs_display_date_page" id="wshs_display_date_page" disabled>
                                    <div class="wshs_show_date_view_page">
                                        <select name="wshs_show_date_format_page" class="wshs-field-select" id="wshs_show_date_format_page">
                                            <option value="F j, Y">November 6, 2010</option>
                                            <option value="F, Y">November, 2010</option>
                                            <option value="l, F jS, Y">Saturday, November 6th, 2010</option>
                                            <option value="F j, Y g:i a">November 6, 2010 12:50 am</option>
                                            <option value="M j, Y">Nov 6, 2010</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <div class="loading-sitemap">
                                    <img src="<?php echo esc_url(WSHS_PLUGIN_URL . '/images/loader.gif'); ?>">
                                </div>
                            </div>
                            <div class="short-code-main">
                                <div id="wshs_shortcode"></div>
                                <div class="short-code-action">
                                    <input type="text" id="wshs_code_title" name="wshs_code_title" value="<?php echo esc_attr(htmlspecialchars($default_title)); ?>">
                                    <a href="javascript:void(0);" class="short-code-save-btn button" data-type="page" data-id="<?php echo esc_attr($get_id); ?>">Save</a>
                                    <a href="javascript:void(0);" class="short-code-copy-btn button">Copy</a>
                                </div>
                            </div>
                            <div id="wshs_admin_post_list">
                                <div class="sitemap-exclude-post">Exclude</div>
                                <ul></ul>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- Sidebar Advertisement -->
                    <?php require_once WSHS_PLUGIN_PATH . '/inc/wshs_sidebar.php'; ?>
                    <!-- Sidebar Advertisement -->
                </div>
            </div>
        </div>
        <?php
    }
}