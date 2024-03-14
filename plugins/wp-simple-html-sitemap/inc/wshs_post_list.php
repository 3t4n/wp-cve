<?php
if (!function_exists('wshs_post_list')) {

    function wshs_post_list() {
        global $wpdb;
        $atts = array();
        $default_title = 'Post sitemap';
        if(isset($_GET['id']) && $_GET['id'] != '') {
			$sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}".WSHS_SAVED_CODE_TABLE." WHERE id = %d", $_GET['id']) ;
            $row = $wpdb->get_row($sql);
            if($row){
                $default_title = $row->title;
                $atts = shortcode_parse_atts($row->attributes);
            }
        }

        ?>
        <script> var existing_atts = <?php echo json_encode($atts); ?>; </script>
        <div class="wrap wtl-main">
            <h1 class="wp-heading-inline">WordPress Simple HTML Sitemap</h1>
            <hr class="wp-header-end">
            <div id="post-body" class="metabox-holder columns-3">
                <!-- Top Navigation -->
                <div class="sitemap-wordpress">
                    <h2 class="nav-tab-wrapper">
                        <a href="?page=wshs_page_list" class="nav-tab">Pages</a>
                        <a href="?page=wshs_post_list" class="nav-tab nav-tab-active">Posts</a>
                        <a href="?page=wshs_saved" class="nav-tab ">Saved Shortcodes</a>
                        <a href="?page=wshs_documentation" class="nav-tab">Documentation</a>
                    </h2>
                    <div class="sitemap-pages">
                        <div class="shortcode-container">
                            <!-- Get all registered post types -->
                            <?php $allposttypes = get_post_types(array('show_in_nav_menus' => 1)); ?>
                            <!-- Admin page sitemap -->
                            <div class="admin-field-section admin-post">
                                <fieldset>
                                    <label>1. Select Post Type</label>
                                    <select name="wshs_select_type" class="wshs_select_type wshs-field" id="wshs_select_type">
                                        <option value="">Select post type</option>
                                        <?php
                                        $postsarray = array();
                                        foreach ($allposttypes as $slug => $title):
                                            $postdetails = get_post_type_object($slug);
                                            $postsarray[$slug] = array('title' => $postdetails->label, 'hierarchical' => $postdetails->hierarchical);
                                            if ($title != 'page') {
                                                ?>
                                                <option data-title="<?php echo ucfirst($slug); ?> Sitemap" value="<?php echo $slug; ?>"><?php echo $postdetails->label; ?></option>	
                                            <?php } ?>
                                        <?php endforeach; ?>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>2. Order by</label>
                                    <select name="wshs_select_order" class="wshs-field" id="wshs_select_order" disabled>
                                        <option value="date">Date</option>	
                                        <option value="title">Title</option>	
                                        <option value="menu_order">Menu Order</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>3. Order</label>
                                    <select name="wshs_select_order_asc" class="wshs-field" id="wshs_select_order_asc" disabled>
                                        <option value="asc">ASC</option>	
                                        <option value="desc">DESC</option>	
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>4. Taxonomy list</label>
                                    <select name="wshs_taxonomy_list" class="wshs-field" id="wshs_taxonomy_list" disabled>
                                        <option value="">Select Texonomy</option>
                                    </select>
                                </fieldset>	
                                <fieldset>
                                    <label>5. Taxonomy terms of</label>
                                    <select name="wshs_taxonomy_list_chield" class="wshs-field" id="wshs_taxonomy_list_chield" disabled>
                                        <option value="">Select Texonomy Terms</option>
                                    </select>
                                </fieldset>
                                <fieldset>
                                    <label>6. Column layout</label>
                                    <select name="wshs_select_column_post" class="wshs-field" id="wshs_select_column_post" disabled>
                                        <option value="" data-title="">Select column</option>
                                        <option data-title="single-column" value="full">Single column</option>
                                        <option data-title="two-columns" value="half">Two columns</option>
                                    </select>
                                </fieldset>
                                <fieldset class="position-post">
                                    <label>Column position</label>
                                    <select name="wshs_select_column_position_post" class="wshs-field" id="wshs_select_column_position_post">
                                        <option value="">Select position</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </fieldset>
                                <fieldset class="field-checkbox">
                                    <label>6. Display image </label>
                                    <input type="checkbox" name="wshs_display_image" id="wshs_display_image" disabled>
                                    <div class="wshs_image_size">
                                        <input type = "number" min="60" name="wshs_image_width" placeholder="Width"> * <input placeholder="Height"  type = "number" min="60" name="wshs_image_height">
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label>7. Display excerpt </label>
                                    <input type="checkbox" name="wshs_display_excerpt" id="wshs_display_excerpt" disabled>
                                    <div class="wshs_excerpt_limit">
                                        <input min="10" type = "number" name="wshs_excerpt_length" value = "100">
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label>8. Show date </label>
                                    <input type="checkbox" name="wshs_display_date" id="wshs_display_date" disabled>
                                    <div class="wshs_show_date_view">
                                        <select name="wshs_show_date_format" class="wshs-field-select" id="wshs_show_date_format">
                                            <option value="F j, Y">November 6, 2010</option>
                                            <option value="F, Y">November, 2010</option>
                                            <option value="l, F jS, Y">Saturday, November 6th, 2010</option>
                                            <option value="F j, Y g:i a">November 6, 2010 12:50 am</option>
                                            <option value="M j, Y">Nov 6, 2010</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <div class="loading-sitemap">
                                    <img src="<?php echo WSHS_PLUGIN_URL . '/images/loader.gif'; ?>">
                                </div>
                            </div>
                            <div class="short-code-main">
                                <div id="wshs_shortcode"></div>
                                <div class="short-code-action">
                                <input type="text" id="wshs_code_title" name="wshs_code_title" value="<?php echo esc_html($default_title); ?>">
                                    <a href="javascript:void(0);" class="short-code-save-btn button" data-type="post" data-id="<?php echo (isset($_GET['id']) && ($_GET['id']) != '')? $_GET['id'] :0; ?>">Save</a>
                                    <a href="javascript:void(0);" class="short-code-copy-btn button">Copy</a>                                    
                                </div>
                            </div>
                            <div id="wshs_admin_post_list">
                                <div class="sitemap-exclude-post">Exclude</div>
                                <ul></ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <!-- Sidebar Advertisement -->
                    <?php require_once WSHS_PLUGIN_PATH . '/inc/wshs_sidebar.php'; ?>
                    <!-- Sidebar Advertisement -->
                </div>
            </div> 
        </div> 
        <script type="text/javascript">
        <?php if (esc_html($_GET['page']) == 'wshs_post_list') { ?>
                jQuery(document).ready(function() {
                    jQuery('#toplevel_page_wshs_page_list').addClass('current');
                });
        <?php } ?>
        </script>
        <?php
    }

}
