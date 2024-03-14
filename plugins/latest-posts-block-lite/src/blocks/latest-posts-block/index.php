<?php
    
    function latest_posts_block_lite_render_block_core_latest_posts_list($attributes)
    {
        
        ob_start();
        $unq_class = mt_rand(100000,999999);
        $blockuniqueclass = '';
        
        if(!empty($attributes['uniqueClass'])){
            $blockuniqueclass = $attributes['uniqueClass'];
        }else{
            $blockuniqueclass = 'latest-posts-block-list-'.$unq_class;
        }
        
        
        $categories = isset($attributes['categories']) ? $attributes['categories'] : '';
        
        /* Setup the query */
        $grid_query = new WP_Query(
            array(
                'posts_per_page' => $attributes['postsToShow'],
                'post_status' => 'publish',
                'order' => $attributes['order'],
                'orderby' => $attributes['orderBy'],
                'cat' => $categories,
                'offset' => $attributes['offset'],
                'post_type' => $attributes['postType'],
                'ignore_sticky_posts' => 1,
            )
        );
        
        
        
        /* Start the loop */
        if ($grid_query->have_posts()) {
            
            /* Build the block classes */
            $class = "wp-block-latest-posts-block-latest-posts-block-latest-posts latest-posts-block-post-wrap align{$attributes['align']}";
            
            if (isset($attributes['className'])) {
                $class .= ' ' . $attributes['className'];
            }
            
            
            if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-grid') {
                $list_layout_class = $attributes['grid'];
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-tile') {
                $list_layout_class = $attributes['tile'];
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-express') {
                $list_layout_class = $attributes['express'];
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-full') {
                $list_layout_class = $attributes['full'];
            } else {
                if($attributes['enableTwoColumn']){
                    $list_layout_class = $attributes['design'].' list-col-2';
                }else{
                    $list_layout_class = $attributes['design'];
                }
                
                
            }
    
            $spotlightclass = '';

        if( $attributes['tile']!='latest-posts-block-tile-layout-1' || $attributes['postListingOption'] == 'latest-posts-block-latestpost-tile' || $attributes['postListingOption']=='latest-posts-block-latestpost-express'){
            if($attributes['displaySpotLightExceprt'] ==true ) {
                $spotlightclass = 'has-spotlight-excerpt';
            }
        }

          
            
            //$list_layout_class = $attributes['design'];
            
            
            if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-grid') {
                $listgridClass = 'latest-posts-block-is-grid' . " " . "column-" . $attributes['columns'];
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-express') {
                $listgridClass = 'latest-posts-block-is-express';
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-full') {
                $listgridClass = 'latest-posts-block-is-full';
            } else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-tile') {
                $listgridClass = 'latest-posts-block-is-tile' . " tile-item-gap-" . $attributes['tileGaps'];
            } else {
                $listgridClass = 'latest-posts-block-is-list';
            }
            
            /* Layout orientation class */
            $grid_class = $blockuniqueclass . ' latest-posts-block-latest-post-wrap '.$spotlightclass.' '  . $listgridClass . ' ' . $list_layout_class;
            
            $category_class = 'latest-posts-block-post-category';
            
            if($attributes['categoryLayoutOption'] =='none'){
                $category_class .= ' has-no-category-style';
            }
            
            ?>

            <section class="<?php echo esc_attr($class);?>">
                <div class="<?php echo esc_attr($grid_class);?>">
                    <?php while ($grid_query->have_posts()) {
                        $grid_query->the_post();
                        
                        /* Setup the post ID */
                        $post_id = get_the_ID();
                        
                        /* Setup the featured image ID */
                        $post_thumb_id = get_post_thumbnail_id($post_id);
                        
                        $has_img_class = '';
                        
                        if (!$post_thumb_id) {
                            $has_img_class = "post-has-no-image";
                        }
                        $contentOrderClass ='';
                        if($attributes['contentOrder']=='content-order-5'){
                            $contentOrderClass .= 'contentorderfive';
                        }
                        if( $attributes['contentOrder']=='content-order-6'){
                            $contentOrderClass .= 'contentordersix';
                        }
                        
                        /* Setup the post classes */
                        $post_classes = 'latest-posts-block-post-single '. $contentOrderClass;
                        
                        /* Add sticky class */
                        if (is_sticky($post_id)) {
                            $post_classes .= ' sticky';
                        } else {
                            $post_classes .= null;
                        }
                        
                        
                        
                        if ($attributes['enableBackgroundColor']) {
                            $post_classes .= ' has-background';
                        }?>

                        <div id="<?php echo esc_attr($post_id);?>" class="<?php echo esc_attr($post_classes).' '.$has_img_class; ?>">
                            <?php
                                if (isset($attributes['displayPostImage']) && $attributes['displayPostImage'] && $post_thumb_id) {
                                    if (!empty($attributes['imageSize'])) {
                                        $post_thumb_size = $attributes['imageSize'];
                                    }
                                    
                                    if (has_post_thumbnail($post_id)) {
                                        ?>
                                        <figure class="latest-posts-block-post-img">
                                            <a href="<?php echo esc_url(get_permalink($post_id));?>" rel="bookmark" aria-hidden="true" tabindex="-1" >
                                                <?php echo wp_kses_post(wp_get_attachment_image($post_thumb_id, $post_thumb_size)); ?>
                                            </a>
                                             <?php
                                                 
                                                 if ($attributes['displayPostCategory'] &&($attributes['contentOrder']=='content-order-5' || $attributes['contentOrder']=='content-order-6')) {?>
                                            <div class="<?php echo esc_attr($category_class);?>" >
                                                <?php
                                                $categories_list = get_the_category_list(' ', '', $post_id);
                                                if ( $categories_list ) {
                                                    /* translators: 1: list of categories. */
                                                    printf(  esc_html__( '%1$s', 'latest-posts-block-lite' ), $categories_list ); // WPCS: XSS OK.
                                                }
                                                ?>
                                            </div>
                                        
                                        <?php }?>
                                        </figure>
                                        <?php
                                    }
                                }
                            ?>
                            <div class="latest-posts-block-post-content <?php echo esc_attr($attributes['contentOrder']); ?>">
                                <div class="latest-posts-block-bg-overlay"></div>
                                <header class="latest-posts-block-post-grid-header">
                                    <?php
                                        if ($attributes['displayPostCategory'] ) {?>
                                            <div class="<?php echo esc_attr($category_class);?>" >
                                                <?php
                                                $categories_list = get_the_category_list(' ', '', $post_id);
                                                if ( $categories_list ) {
                                                    /* translators: 1: list of categories. */
                                                    printf(  esc_html__( '%1$s', 'latest-posts-block-lite' ), $categories_list ); // WPCS: XSS OK.
                                                }
                                                ?>
                                            </div>
                                        <?php }
                                        
                                        $title = get_the_title($post_id);
                                        
                                        if (!$title) {
                                            $title = __('Untitled', 'latest-posts-block-lite');
                                        }
                                        
                                        if(isset($attributes['displayPostTitle']) && $attributes['displayPostTitle']) { ?>
                                            <h4 class="latest-posts-block-post-grid-title">
                                                <a href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                                   class="latest-posts-block-title-link" rel="bookmark">
                                                    <span><?php echo esc_html($title); ?></span></a>
                                            </h4>
                                        
                                        <?php }
                                        if(isset($attributes['postType']) && ($attributes['postType'] === 'post') && (isset($attributes['displayPostAuthor']) || isset($attributes['displayPostDate']))) {?>
                                            <div class="latest-posts-block-post-grid-byline">
                                                <?php
                                                    if (isset($attributes['displayPostAuthor']) && $attributes['displayPostAuthor']) {?>
                                                        <div class="latest-posts-block-post-grid-author">
                                                            <a class="latest-posts-block-text-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')));?>" itemprop="url" rel="author">
                                                                <span itemprop="name"><i class="<?php echo esc_attr($attributes['authorIcon']);?>"></i><?php echo esc_html(get_the_author_meta('display_name', get_the_author_meta('ID')));?></span>
                                                            </a>
                                                        </div>
                                                    <?php }
                                                    
                                                    if(isset($attributes['displayPostDate']) && $attributes['displayPostDate']) {?>
                                                        <time datetime="<?php echo esc_attr(get_the_date('c', $post_id));?>" class="latest-posts-block-post-grid-date" itemprop="datePublished"><i class="<?php echo esc_attr($attributes['dateIcon']);?>"></i><?php echo esc_html(get_the_date('', $post_id));?></time>
                                                        <?php
                                                    }
                                                    if($attributes['enableComment']){?>
                                                    
                                                <span class="comment_count"><i class='<?php echo esc_attr($attributes['commentIcon']);?>'></i>
                                                    <?php echo esc_html(get_comments_number($post_id)); ?>
                                                </span>
                                                        <?php }?>
                                            </div>
                                            
                                        <?php }?>
                                    
                                </header>
                                
                                <?php
                                    /* Get the excerpt */
                                    
                                    $excerpt = apply_filters('the_excerpt',
                                        get_post_field(
                                            'post_excerpt',
                                            $post_id,
                                            'display'
                                        )
                                    );
                                    
                                    if(empty($excerpt) && isset($attributes['excerptLength'])) {
                                        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound, PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket  -- Running the_excerpt directly, Previous rule doesn't take without the_excerpt being moved up a line
                                        $excerpt = apply_filters('the_excerpt',
                                            wp_trim_words(
                                                preg_replace(
                                                    array(
                                                        '/\<figcaption>.*\<\/figcaption>/',
                                                        '/\[caption.*\[\/caption\]/',
                                                    ),
                                                    '',
                                                    get_the_content()
                                                ),
                                                $attributes['excerptLength']
                                            )
                                        );
                                    }
                                    
                                    if(!$excerpt) {
                                        $excerpt = null;
                                    }
                                    if($attributes['postListingOption']=='latest-posts-block-latestpost-full') {
    
                                        if ($attributes['full'] !== 'latest-posts-block-full-layout-5') {
        
                                            if ($attributes['displayPostExcerpt'] && $excerpt != null || isset($attributes['displayPostLink'])) { ?>
                                                <div class="latest-posts-block-post-grid-excerpt">
                                                <?php if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) { ?>
                                                    <div class="latest-posts-block-post-grid-excerpt-content"><?php echo wp_kses_post($excerpt); ?></div>
                                                <?php } ?>
        
        
                                            <?php }
        
                                            if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) { ?>
                                                <p>
                                                    <a class="latest-posts-block-post-grid-more-link latest-posts-block-text-link"
                                                       href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                                       rel="bookmark">
                                                        <span><?php echo esc_html($attributes['readMoreText']); ?></span></a>
                                                </p>
        
                                            <?php } ?>
                                            </div>
                                            <?php
                                        }
                                    }else{
        
                                            if ($attributes['displayPostExcerpt'] && $excerpt != null || isset($attributes['displayPostLink'])) { ?>
                                                <div class="latest-posts-block-post-grid-excerpt">
                                                <?php if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) { ?>
                                                    <div class="latest-posts-block-post-grid-excerpt-content"><?php echo wp_kses_post($excerpt); ?></div>
                                                <?php } ?>
        
        
                                            <?php }
        
                                            if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) { ?>
                                                <p>
                                                    <a class="latest-posts-block-post-grid-more-link latest-posts-block-text-link"
                                                       href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                                       rel="bookmark">
                                                        <span><?php echo esc_html($attributes['readMoreText']); ?></span></a>
                                                </p>
        
                                            <?php } ?>
                                            </div>
                                            <?php
                                        
                                    }?>
                            </div>
                            <?php
                                if($attributes['postListingOption']=='latest-posts-block-latestpost-full') {
                                    if ($attributes['full'] == 'latest-posts-block-full-layout-5') {
        
                                        if ($attributes['displayPostExcerpt']) {
                                            if ($excerpt != null || isset($attributes['displayPostLink'])) { ?>
                                                <div class="latest-posts-block-post-grid-excerpt">
                                                <?php if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) { ?>
                                                    <div class="latest-posts-block-post-grid-excerpt-content"><?php echo wp_kses_post($excerpt); ?></div>
                                                <?php } ?>
            
            
                                            <?php }
            
                                            if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) { ?>
                                                <p>
                                                    <a class="latest-posts-block-post-grid-more-link latest-posts-block-text-link"
                                                       href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                                       rel="bookmark">
                                                        <span><?php echo esc_html($attributes['readMoreText']); ?></span></a>
                                                </p>
            
                                            <?php } ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                }?>

                        </div>
                    <?php }?>
                </div>
            </section>
            <?php wp_reset_postdata();
            $data_content =  latest_posts_box_lite_style_control($blockuniqueclass ,$attributes);
            $data_content .= ob_get_clean();
            return   $data_content;
        }
    }
    
    /**
     * Registers the post grid block on server
     */
    function latest_posts_block_lite_register_block_core_latest_posts_list()
    {
    
        if (!function_exists('register_block_type')) {
            return;
        }
    
    
        ob_start();
        include LATEST_POSTS_BOX_LITE_PLUGIN_DIR . 'src/blocks/latest-posts-block/block.json';
        $metadata = json_decode(ob_get_clean(), true);
    
        /* Block attributes */
        register_block_type(
            'lpb/latest-posts-block-list',
            array(
                'attributes' =>$metadata['attributes'],
                'render_callback' => 'latest_posts_block_lite_render_block_core_latest_posts_list',
            )
        );
    }
    
    add_action('init', 'latest_posts_block_lite_register_block_core_latest_posts_list');
    
    
    /**
     * Create API fields for additional info
     */
    function latest_posts_block_lite_post_register_rest_fields()
    {
        
        register_rest_field('post', 'featured_image_urls',
            array(
                'get_callback' => 'latest_posts_block_lite_featured_image_urls',
                'update_callback' => null,
                'schema' => array(
                    'description' => __('Different sized featured images', 'latest-posts-block-lite'),
                    'type' => 'array',
                ),
            )
        );
        
        // Excer
        
        /* Add author info */
        register_rest_field(
            'post',
            'author_info',
            array(
                'get_callback' => 'latest_posts_block_lite_get_author_infos',
                'update_callback' => null,
                'schema' => null,
            )
        );
        
        /* Add author info */
        register_rest_field(
            'post',
            'category_info',
            array(
                'get_callback' => 'latest_posts_block_lite_get_category_infos',
                'update_callback' => null,
                'schema' => null,
            )
        );
        
        /* Add author info */
        register_rest_field(
            'post',
            'tag_info',
            array(
                'get_callback' => 'latest_posts_block_lite_get_tag_infos',
                'update_callback' => null,
                'schema' => null,
            )
        );
    
        register_rest_field(
            'post',
            'comment_count',
            array(
                'get_callback' => 'latest_posts_block_lite_get_comment_count',
                'update_callback' => null,
                'schema' => null,
            )
        );
    }
    
    add_action('rest_api_init', 'latest_posts_block_lite_post_register_rest_fields');
    
    
    /**
     * Get author info for the rest field
     *
     * @param String $object The object type.
     * @param String $field_name Name of the field to retrieve.
     * @param String $request The current request object.
     */
    function latest_posts_block_lite_get_author_infos($object, $field_name, $request)
    {
        if(!isset($object['author']))
            return;
        
        /* Get the author name */
        $author_data['display_name'] = get_the_author_meta('display_name', $object['author']);
        
        /* Get the author link */
        $author_data['author_link'] = get_author_posts_url($object['author']);
        
        /* Return the author data */
        return $author_data;
    }
    
    function latest_posts_block_lite_get_category_infos($object, $field_name, $request)
    {
        
        
        return get_the_category_list(' ', '', $object['id']);
        
    }
    
    function latest_posts_block_lite_get_tag_infos($object, $field_name, $request)
    {
        
        ob_start();
        $cate_name = '';
        if (!empty($object)) {
            foreach ($object['categories'] as $cat_id) {
                $cate_name = get_cat_name($cat_id);
            }
        }
        ob_clean();
        return $cate_name;
        
    }
    
    function latest_posts_block_lite_get_comment_count($object, $field_name, $reques){
        
        return  get_comments_number( $object['id'] );
        
    }
    
    if (!function_exists('latest_posts_block_lite_featured_image_urls')) {
        /**
         * Get the different featured image sizes that the blog will use.
         * Used in the custom REST API endpoint.
         *
         * @since 1.7
         */
        function latest_posts_block_lite_featured_image_urls($object, $field_name, $request)
        {
            return latest_posts_block_lite_featured_image_urls_from_url(!empty($object['featured_media']) ? $object['featured_media'] : '');
        }
    }
    
    
    if (!function_exists('latest_posts_block_lite_featured_image_urls_from_url')) {
        /**
         * Get the different featured image sizes that the blog will use.
         *
         * @since 2.0
         */
        function latest_posts_block_lite_featured_image_urls_from_url($attachment_id)
        {
            
            $image = wp_get_attachment_image_src($attachment_id, 'full', false);
            $sizes = get_intermediate_image_sizes();
            
            $imageSizes = array(
                'full' => is_array($image) ? $image : '',
            );
            
            foreach ($sizes as $size) {
                $imageSizes[$size] = is_array($image) ? wp_get_attachment_image_src($attachment_id, $size, false) : '';
            }
            
            return $imageSizes;
        }
    }
    
    function latest_posts_box_lite_style_control($blockuniqueclass ,$attributes){
    
    
        $block_content ='';
        $block_content .= '<style type="text/css">';
        $block_content .= ' .' . $blockuniqueclass . '.latest-posts-block-latest-post-wrap{
                margin-top:' . $attributes['marginTop'] . 'px;
                margin-bottom:' . $attributes['marginBottom'] . 'px;
                }';
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content{
                padding-top:' . $attributes['contentPaddingTop'] . 'px;
                padding-right:' . $attributes['contentPaddingRight'] . 'px;
                padding-bottom:' . $attributes['contentPaddingBottom'] . 'px;
                padding-left:' . $attributes['contentPaddingLeft'] . 'px;
                }';
    
    
        if($attributes['full']=='latest-posts-block-full-layout-5'){
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single > .latest-posts-block-post-grid-excerpt{
                padding-top:' . $attributes['contentPaddingTop'] . 'px;
                padding-right:' . $attributes['contentPaddingRight'] . 'px;
                padding-bottom:' . $attributes['contentPaddingBottom'] . 'px;
                padding-left:' . $attributes['contentPaddingLeft'] . 'px;
                }';
        }
    
        if ($attributes['categoryLayoutOption'] == 'solid') {
            
            if($attributes['postListingOption'] == 'latest-posts-block-latestpost-express'){
    
                if($attributes['express'] == 'latest-posts-block-express-layout-1' || $attributes['express'] == 'latest-posts-block-express-layout-3' || $attributes['express'] == 'latest-posts-block-express-layout-5') {
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-category a{
                        color:' . $attributes['spotCategoryTextColor'] . "!important" . ';
                        background-color:' . $attributes['spotCategoryBackgroundColor'] . "!important" . ';
                        border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                     }';
                }else{
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                        color:' . $attributes['categoryTextColor'] . "!important" . ';
                        background-color:' . $attributes['categoryBackgroundColor'] . "!important" . ';
                        border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                     }';
                }
    
                if($attributes['express'] == 'latest-posts-block-express-layout-6') {
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-category a{
                        color:' . $attributes['spotCategoryTextColor'] . "!important" . ';
                        background-color:' . $attributes['spotCategoryBackgroundColor'] . "!important" . ';
                        border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                     }';
                }else{
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                        color:' . $attributes['categoryTextColor'] . "!important" . ';
                        background-color:' . $attributes['categoryBackgroundColor'] . "!important" . ';
                        border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                     }';
                }
                
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                color:' . $attributes['categoryTextColor'] . "!important" . ';
                background-color:' . $attributes['categoryBackgroundColor'] . "!important" . ';
                border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
             }';
            
            }
            
            
            
            
            
           
        } else if ($attributes['categoryLayoutOption'] == 'border') {
    
            if($attributes['postListingOption'] == 'latest-posts-block-latestpost-express'){
                if($attributes['express'] == 'latest-posts-block-express-layout-1' || $attributes['express'] == 'latest-posts-block-express-layout-3' || $attributes['express'] == 'latest-posts-block-express-layout-5') {
    
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-category a{
                    color:' . $attributes['spotCategoryTextColor'] . "!important" . ';
                    background-color:' . "transparent" . ';
                    border:' . "1px solid" . $attributes['spotCategoryBorderColor'] . ';
                    border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                    border-width:' . $attributes['categoryBorderWidth'] . "px" . ';
                     }';
                }else{
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                    color:' . $attributes['categoryTextColor'] . "!important" . ';
                    background-color:' . "transparent" . ';
                    border:' . "1px solid" . $attributes['categoryBorderColor'] . ';
                    border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                    border-width:' . $attributes['categoryBorderWidth'] . "px" . ';
                }';
                }
    
                if($attributes['express'] == 'latest-posts-block-express-layout-6') {
        
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-category a{
                    color:' . $attributes['spotCategoryTextColor'] . "!important" . ';
                    background-color:' . "transparent" . ';
                    border:' . "1px solid" . $attributes['spotCategoryBorderColor'] . ';
                    border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                    border-width:' . $attributes['categoryBorderWidth'] . "px" . ';
                     }';
                }else{
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                    color:' . $attributes['categoryTextColor'] . "!important" . ';
                    background-color:' . "transparent" . ';
                    border:' . "1px solid" . $attributes['categoryBorderColor'] . ';
                    border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                    border-width:' . $attributes['categoryBorderWidth'] . "px" . ';
                }';
                }
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                color:' . $attributes['categoryTextColor'] . "!important" . ';
                background-color:' . "transparent" . ';
                border:' . "1px solid" . $attributes['categoryBorderColor'] . ';
                border-radius:' . $attributes['categoryBorderRadius'] . "px" . ';
                border-width:' . $attributes['categoryBorderWidth'] . "px" . ';
            }';
            }
            
            
        } else {
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category a{
                color:' . $attributes['categoryTextColor'] . "!important" . ';
                }';
        }
    
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-category{
                margin-top:' . $attributes['categoryMarginTop'] . 'px' . ';
                margin-bottom:' . $attributes['categoryMarginBottom'] . 'px' . ';
                }';
    
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-byline{
                
                 margin-top:' . $attributes['metaMarginTop'] . 'px' . ';
                margin-bottom:' . $attributes['metaMarginBottom'] . 'px' . ';
                }';
    
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link{
                 margin-top:' . $attributes['moreLinkMarginTop'] . 'px' . ';
                margin-bottom:' . $attributes['moreLinkMarginBottom'] . 'px' . ';
                }';
    
        if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-tile') {
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                color: ' . $attributes['tilePostTitleColor'] . ';
              
                 }';
        
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                color:' . $attributes['tilePostLinkColor'] . ';
                }';
        
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                color:' . $attributes['tilePostLinkColor'] . ';
                }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                color:' . $attributes['tilePostGeneralColor'] . ';
                }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content, '.' .' . $blockuniqueclass . ' .comment_count{
                color:' . $attributes['tilePostGeneralColor'] . ';
              
                }';
        
        }
        else if($attributes['postListingOption']=='latest-posts-block-latestpost-full'){
            if($attributes['full']==='latest-posts-block-full-layout-6' ) {
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single .latest-posts-block-post-content{
                        background-color:' . $attributes['backGroundColor'] . ';
                     
                        }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['postTitleColor'] . ';
                    
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                    color:' . $attributes['generalColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content, '.' .' . $blockuniqueclass . ' .comment_count{
                    color:' . $attributes['generalColor'] . ';
                  
                    }';
            }else if($attributes['full']==='latest-posts-block-full-layout-4'){
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['tilePostTitleColor'] . ';
                  
                     }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['tilePostLinkColor'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['tilePostLinkColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                    color:' . $attributes['tilePostGeneralColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content, '.' .' . $blockuniqueclass . ' .comment_count{
                    color:' . $attributes['tilePostGeneralColor'] . ';
                  
                    }';
            
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single{
                        background-color:' . $attributes['backGroundColor'] . ';
                     
                        }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['postTitleColor'] . ';
                    
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                    color:' . $attributes['generalColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content, '.' .' . $blockuniqueclass . ' .comment_count{
                    color:' . $attributes['generalColor'] . ';
                  
                    }';
            }
        }
        else if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-express') {
            if($attributes['express']==='latest-posts-block-express-layout-1' ||$attributes['express']==='latest-posts-block-express-layout-3' ||$attributes['express']==='latest-posts-block-express-layout-5' ) {
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['tilePostTitleColor'] . ';
                  
                     }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['expressLayout5TextOverLink'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['expressLayout5TextOverLink'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-date{
                    color:' . $attributes['expressLayout5TextOverGeneral'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-excerpt-content{
                    color:' . $attributes['expressLayout5TextOverGeneral'] . ';
                  
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .comment_count{
                    color:' . $attributes['expressLayout5TextOverGeneral'] . ';
                  
                    }';
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['postTitleColor'] . ';
                    
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                    color:' . $attributes['generalColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content, '.' .' . $blockuniqueclass . ' .comment_count{
                    color:' . $attributes['generalColor'] . ';
                  
                    }';
            }
        
            if($attributes['express']==='latest-posts-block-express-layout-6' ) {
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['tilePostTitleColor'] . ';
                  
                     }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['expressLayout5TextOverLink'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['expressLayout5TextOverLink'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-date{
                    color:' . $attributes['expressLayout5TextOverGeneral'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-excerpt-content,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .comment_count{
                    color:' . $attributes['expressLayout5TextOverGeneral'] . ';
                  
                    }';
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    color: ' . $attributes['postTitleColor'] . ';
                    
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                    color:' . $attributes['linkColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                    color:' . $attributes['generalColor'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content,'.' .' . $blockuniqueclass . '  .comment_count{
                    color:' . $attributes['generalColor'] . ';
                  
                    }';
            }
        
        }
        else {
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                color: ' . $attributes['postTitleColor'] . ';
                
                 }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-author a span{
                color:' . $attributes['linkColor'] . ';
                }';
        
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-more-link span{
                color:' . $attributes['linkColor'] . ';
                }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-date{
                color:' . $attributes['generalColor'] . ';
                }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content,'.' .' . $blockuniqueclass . '  .comment_count{
                color:' . $attributes['generalColor'] . ';
              
                }';
        }
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title{
                margin-top:' . $attributes['titleMarginTop'] . 'px' . ';
                margin-bottom:' . $attributes['titleMarginBottom'] . 'px' . ';
            }';
    
    
        $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-excerpt-content{
               margin-top:' . $attributes['exceprtMarginTop'] . 'px' . ';
                margin-bottom:' . $attributes['exceprtMarginBottom'] . 'px' . ';
                }';
    
    
    
    
        if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-list') {
        
            if ($attributes['design'] != 'latest-posts-block-list-layout-4' && $attributes['design'] != 'latest-posts-block-list-layout-5' && $attributes['design'] != 'latest-posts-block-list-layout-6') {
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single{
                    background-color:' . $attributes['backGroundColor'] . ';
                    box-shadow: ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
                    border-radius:' . $attributes['borderRadius'] . 'px' . ';
                    }';
            
            }
        }
    
        if($attributes['postListingOption']== 'latest-posts-block-latestpost-grid') {
        
            if ($attributes['grid'] != 'latest-posts-block-grid-layout-4' && $attributes['grid'] != 'latest-posts-block-grid-layout-5' && $attributes['grid'] != 'latest-posts-block-grid-layout-6') {
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single{
                    background-color:' . $attributes['backGroundColor'] . ';
                    box-shadow: ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
                    border-radius:' . $attributes['borderRadius'] . 'px' . ';
                    }';
            
                if ($attributes['grid'] === 'latest-posts-block-grid-layout-7') {
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content .latest-posts-block-bg-overlay{
                          background-color:' . $attributes['backGroundColor'] . ';
            
                         }';
                }
            }
        }
    
        if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-full') {
        
            if($attributes['full']!='latest-posts-block-full-layout-6') {
                if ($attributes['enableBoxShadow']) {
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single{
                    box-shadow: ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
                   border-radius:' . $attributes['borderRadius'] . 'px' . ';
                    }';
                }
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content{
                    box-shadow: ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
                   border-radius:' . $attributes['borderRadius'] . 'px' . ';
                    }';
            }
        
        }
    
        if ($attributes['postListingOption'] == 'latest-posts-block-latestpost-tile') {
        
            
                if ($attributes['enableBoxShadow']) {
                    $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single{
                    box-shadow: ' . $attributes['xOffset'] . 'px ' . $attributes['yOffset'] . 'px ' . $attributes['blur'] . 'px ' . $attributes['spread'] . 'px ' . $attributes['shadowColor'] . ';
                   border-radius:' . $attributes['borderRadius'] . 'px' . ';
                    }';
                }
            
        
        }
    
        //Font Settings
    
        if($attributes['postListingOption'] ==='latest-posts-block-latestpost-tile'){
            if($attributes['tile']=='latest-posts-block-tile-layout-2'){
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(4) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:first-child .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(4) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:first-child .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(4) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:first-child .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            
            
            }elseif($attributes['tile']=='latest-posts-block-tile-layout-3'){
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            }elseif($attributes['tile']=='latest-posts-block-tile-layout-4'){
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(3) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(3) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(3) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            
            }elseif($attributes['tile']=='latest-posts-block-tile-layout-5'){
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            
            }elseif($attributes['tile']=='latest-posts-block-tile-layout-6'){
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2n+5):nth-child(3n+2) .latest-posts-block-post-grid-title a span, '.' .' . $blockuniqueclass . '.latest-posts-block-post-single:nth-child(5n+2):nth-child(2n+3) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2n+5):nth-child(3n+2) .latest-posts-block-post-grid-title a span, '.' .' . $blockuniqueclass . '.latest-posts-block-post-single:nth-child(5n+2):nth-child(2n+3) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2n+5):nth-child(3n+2) .latest-posts-block-post-grid-title a span, '.' .' . $blockuniqueclass . '.latest-posts-block-post-single:nth-child(5n+2):nth-child(2n+3) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            
            }else{
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                      
                        font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                        font-family: ' . $attributes['titleFontFamily'] . ';
                        font-weight: ' . $attributes['titleFontWeight'] . ';
                         }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSize'] . $attributes['descriptionFontSizeType'] . ';
                        font-family: ' . $attributes['descriptionFontFamily'] . ';
                        font-weight: ' . $attributes['descriptionFontWeight'] . ';
                        }';
            
                $block_content .= '@media (max-width: 1025px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                        font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                        }';
                $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSizeTablet'].$attributes['descriptionFontSizeType'].'
                        }';
                $block_content .= '}';
            
            
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                        font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                        }';
                $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                    font-size:' . $attributes['descriptionFontSizeMobile'].$attributes['descriptionFontSizeType'].'
                    }';
                $block_content .= '}';
            }
        
        
        }else if($attributes['postListingOption'] ==='latest-posts-block-latestpost-express'){
            if($attributes['express']=='latest-posts-block-express-layout-1' || $attributes['express']=='latest-posts-block-express-layout-2' ||  $attributes['express']=='latest-posts-block-express-layout-3' || $attributes['express']=='latest-posts-block-express-layout-5'){
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
    
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSize'] . $attributes['descriptionFontSizeType'] . ';
                        font-family: ' . $attributes['descriptionFontFamily'] . ';
                        font-weight: ' . $attributes['descriptionFontWeight'] . ';
                        }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
    
                $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSizeTablet'].$attributes['descriptionFontSizeType'].'
                        }';
                
                
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            }else{
            
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
              
                    font-size: ' . $attributes['spostTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['spostTitleFontFamily'] . ';
                    font-weight: ' . $attributes['spostTitleFontWeight'] . ';
                     }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    
                    font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                    font-family: ' . $attributes['titleFontFamily'] . ';
                    font-weight: ' . $attributes['titleFontWeight'] . ';
                    }';
    
    
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSize'] . $attributes['descriptionFontSizeType'] . ';
                        font-family: ' . $attributes['descriptionFontFamily'] . ';
                        font-weight: ' . $attributes['descriptionFontWeight'] . ';
                        }';
            
            
            
                $block_content .= '@media (max-width: 1025px) { ';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
    
                $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                        font-size:' . $attributes['descriptionFontSizeTablet'].$attributes['descriptionFontSizeType'].'
                        }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
                $block_content .= '@media (max-width: 768px) { ';
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(1) .latest-posts-block-post-grid-title a span,'.' .' . $blockuniqueclass . ' .latest-posts-block-post-single:nth-child(2) .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['spostTitleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                    font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                    }';
            
                $block_content .= '}';
            
            }
        } else{
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
              
                font-size: ' . $attributes['postTitleFontSize'] . $attributes['titleFontSizeType'] . ';
                font-family: ' . $attributes['titleFontFamily'] . ';
                font-weight: ' . $attributes['titleFontWeight'] . ';
                 }';
        
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                font-size:' . $attributes['descriptionFontSize'] . $attributes['descriptionFontSizeType'] . ';
                font-family: ' . $attributes['descriptionFontFamily'] . ';
                font-weight: ' . $attributes['descriptionFontWeight'] . ';
                }';
        
            $block_content .= '@media (max-width: 1025px) { ';
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                font-size: ' . $attributes['titleFontSizeTablet'] . $attributes['titleFontSizeType'] . ';
                }';
            $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
                font-size:' . $attributes['descriptionFontSizeTablet'].$attributes['descriptionFontSizeType'].'
                }';
            $block_content .= '}';
        
        
            $block_content .= '@media (max-width: 768px) { ';
            $block_content .= ' .' . $blockuniqueclass . ' .latest-posts-block-post-grid-title a span{
                font-size: ' . $attributes['titleFontSizeMobile'] . $attributes['titleFontSizeType'] . ';
                }';
            $block_content .= ' .' .  $blockuniqueclass .' .latest-posts-block-post-content .latest-posts-block-post-grid-excerpt p{
            font-size:' . $attributes['descriptionFontSizeMobile'].$attributes['descriptionFontSizeType'].'
            }';
            $block_content .= '}';
        }
    
        $block_content .= '</style>';
        return $block_content;
    }
    
    
