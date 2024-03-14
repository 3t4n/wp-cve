<?php
if (!function_exists('wshs_documentation')) {

    function wshs_documentation() {
        wp_enqueue_style('wshs_front_css', WSHS_PLUGIN_CSS . 'wshs_style.css');
        wp_enqueue_style('wshs_fancybox_css', WSHS_PLUGIN_CSS . 'jquery.fancybox.css');
        wp_enqueue_script('wshs_fancybox_js', WSHS_PLUGIN_JS . 'jquery.fancybox.min.js');
        ?>
        <div class="wrap wtl-main">
            <h1 class="wp-heading-inline">WordPress Simple HTML Sitemap</h1>
            <hr class="wp-header-end">
            <div id="post-body" class="metabox-holder columns-3">
                <!-- Top Navigation -->
                <div class="sitemap-wordpress">
                    <!-- Pages sitemap -->
                    <h2 class="nav-tab-wrapper">
                        <a href="?page=wshs_page_list" class="nav-tab">Pages</a>
                        <a href="?page=wshs_post_list" class="nav-tab">Posts</a>
                        <a href="?page=wshs_saved" class="nav-tab ">Saved Shortcodes</a>
                        <a href="?page=wshs_documentation" class="nav-tab nav-tab-active">Documentation</a>
                    </h2>
                    <div class="sitemap-pages">
                        <div id="postbox-container-1" class="postbox-container">
                            <div class="row column-layout">
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/page-sitemap.jpg" data-fancybox="images" title="Page sitemap" data-caption='[wshs_list post_type="page" name="Page Sitemap" order_by="date"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/page-sitemap.jpg" alt="Page sitemap" />
                                    </a>
                                    <h2>Page Sitemap</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/post-sitemap.jpg" data-fancybox="images" title="Post sitemap" data-caption='[wshs_list post_type="post" name="Post Sitemap" order_by="date"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/post-sitemap.jpg" alt="Post sitemap" />
                                    </a>
                                    <h2>Post Sitemap</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/horizontal-sitemap.jpg" data-fancybox="images" title="Horizontal view sitemap" data-caption='[wshs_list post_type="page" name="Page Sitemap" order_by="date" horizontal="true" separator="|"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/horizontal-sitemap.jpg" alt="Horizontal view sitemap" />
                                    </a>
                                    <h2>Sitemap With Horizontal View</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/column-sitemap.jpg" data-fancybox="images" title="Column layout sitemap" data-caption='[wshs_list post_type="page" name="Page Sitemap" order_by="date" layout="Two columns" position="left"]<br /> [wshs_list post_type="post" name="Post Sitemap" order_by="date" layout="Two columns" position="right"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/column-sitemap.jpg" alt="Column layout sitemap" />
                                    </a>
                                    <h2>Two Column Layout Sitemap</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/page-with-image.jpg" data-fancybox="images" title="Page sitemap with image, excerpt and date" data-caption='[wshs_list post_type="page" name="Page Sitemap" order_by="date" show_image="true" image_width="60" image_height="60" content_limit="100"  show_date="true" date_format="F j, Y"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/page-with-image.jpg" alt="Page sitemap with image, excerpt and date" />
                                    </a>
                                    <h2>Page Sitemap With Image, Excerpt And Date</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/post-with-image.jpg" data-fancybox="images" title="Post sitemap with image, excerpt and date" data-caption='[wshs_list post_type="post" name="Post Sitemap" order_by="date" show_image="true" image_width="60" image_height="60" content_limit="100"  show_date="true" date_format="F j, Y"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/post-with-image.jpg" alt="Post sitemap with image, excerpt and date" />
                                    </a>
                                    <h2>Post Sitemap With Image, Excerpt And Date</h2>
                                </div>

                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/custom-post-type.jpg" data-fancybox="images" title="Custom Post Type and Taxanomy sitemap" data-caption='[wshs_list post_type="portfolio" name="Portfolio Sitemap" order_by="date"] <br> [wshs_list post_type="portfolio" name="Website Design Project" order_by="date" taxonomy="portfolio_category" terms="website-design"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/custom-post-type.jpg" alt="Custom Post Type and Taxanomy sitemap" />
                                    </a>
                                    <h2>CPT And Taxonomy Sitemap</h2>
                                </div>
                                <div class="col">
                                    <a class="fancybox" href="<?php echo WSHS_PLUGIN_URL; ?>/images/category-sitemap.jpg" data-fancybox="images" title="Category sitemap" data-caption='[wshs_list post_type="post" name="Post Sitemap" order_by="date"] <br> [wshs_list post_type="post" name="Free Download" order_by="date" taxonomy="category" terms="free-download"]'>
                                        <img src="<?php echo WSHS_PLUGIN_URL; ?>/images/category-sitemap.jpg" alt="Category sitemap" />
                                    </a>
                                    <h2>Category Sitemap</h2>
                                </div>
                            </div> 
                        </div> 

                        <div class="documentation">
                            <div class="wp-heading-main">
                                <h2 class="wp-heading-inline">Documentation</h2>
                            </div>
                            <div class="instruction">
                                <p>Note: Default values are always used for missing shortcode attributes. i.e. Override only the values you want to change.</p>
                                <div class="instruction-shortcode">
                                    <ul>
                                        <li><strong>[wshs_list post_type="page" name="Page Sitemap" order_by="date"]</strong></li>
                                        <li><strong>[wshs_list post_type="post" name="Post Sitemap" order_by="date"]</strong></li>
                                        <li><strong>[wshs_list post_type="page" name="Page Sitemap" order_by="date" horizontal="true" separator="|"]</strong></li>
                                        <li><strong>[wshs_list post_type="post" name="Post Sitemap" order_by="date" horizontal="true" separator="|"]</strong></li>
                                    </ul>
                                </div>
                                <ul class="shortcode-attribute">
                                    <li><code>post_type="page"</code> - A list of pages, in the order entered.</li>
                                    <li><code>post_type="post"</code> - A list of posts for each post type specified, in the order entered.</li>
                                    <li><code>name="Post Sitemap"</code> - Display post type title.</li>
                                    <li><code>child_of=""</code> - To specify the parent page by adding parent page ID</li>                                    
                                    <li><code>orderby="title"</code> - Pages and Posts will be ordered by title alphabetically in ascending order</li>                                    
                                    <li><code>depth="2"</code> -  Using this option one can control what level of sub-pages should be included in the sitemap.</li>
                                    <li><code>show_image="true"</code> - Optionally show the post or page featured image.</li>
                                    <li><code>image_width="30" image_height="30"</code> - Optionally show the post or page featured image set height and width.</li>
                                    <li><code>content_limit="140"</code> - Optionally show a post excerpt (if defined) under each sitemap item.</li>
                                    <li><code>show_date="true"</code> - Set to "true" to display sitemap items in post created date.</li>
                                    <li><code>date="created"</code> - Display sitemap items in post created date.</li>
                                    <li><code>date_format="F j, Y"</code> - Display sitemap items date format.</li>  
                                    <li><code>layout="single-column"</code> - To show the sitemap in Single column or in Two column.</li>
                                    <li><code>position="left"</code> - For Two column layout, you can choose to show sitemap in left or right column.</li>                   
                                    <li><code>taxonomy="category"</code> - List of post type for each post type specific taxonomy post list.</li>
                                    <li><code>terms="wordpress-plugins"</code> - List of post type for each post type specific taxonomy by terms post list.</li>
                                    <li><code>horizontal="true"</code> - Set to "true" to display sitemap items in a flat horizontal list. Great for adding a sitemap to the footer!</li>
                                    <li><code>separator=" |"</code> - The character(s) used to separate sitemap items. Use with the 'horizontal' attribute.</li>
                                    <li><code>exclude="100,122,155"</code> - Comma separated list of post IDs to exclude from the sitemap.</li>
                                </ul> 
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar Advertisement -->
                    <?php $sidebar_file = WSHS_PLUGIN_PATH . '/inc/wshs_sidebar.php';
                        if (file_exists($sidebar_file)) {
                            require_once $sidebar_file;
                        } ?>
                    <!-- Sidebar Advertisement -->
                </div>
            </div> 
        </div>  
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.fancybox').fancybox({
                    beforeShow: function() {
                        this.title = this.title + " <br/> " + jQuery(this.element).data("caption");
                    }
                });
            });
        <?php if (sanitize_text_field($_GET['page']) == 'wshs_documentation') { ?>
                jQuery(document).ready(function() {
                    jQuery('#toplevel_page_wshs_page_list').addClass('current');
                });
        <?php } ?>
        </script>
        <?php
    }

}