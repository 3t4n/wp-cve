<?php
defined('ABSPATH') or die('No script kiddies please!');
if (!class_exists('TTWSHORTCODE')) {
    class TTWSHORTCODE
    {
        function __construct()
        {
            add_shortcode('TTW_TERMS', array($this, 'ttw_terms_list_callback'));
            add_shortcode('TTW_POST_TERMS_ICON', array($this, 'ttw_post_terms_icon_list_callback'));
        }

        /**
         * Script For Show Data using shortcode
         **/
        public function ttw_terms_list_callback($atts)
        {
            ob_start();
            $atts = shortcode_atts(array(
                'taxonomy' => 'category',
                'class' => 'ttw_term_list',
                'type' => 'list',
                'hide_empty' => true,
                'exclude' => '',
                'number' => get_option('posts_per_page'),
                'offset' => '',
            ), $atts);

            $taxonomies = explode(',', $atts['taxonomy']);
            $excludes = explode(',', $atts['exclude']);
            $per_page = $atts['number'];

            $getTaxCountArgs = array(
                'hide_empty' => $atts['hide_empty'],
                'exclude' => $excludes,
                'cache_domain' => 'core'
            );

            $page = (get_query_var('page')) ? get_query_var('page') : 1;
            $offset = ($page > 0) ? $per_page * ($page - 1) : 1;
            $totalterms = wp_count_terms($taxonomies, $getTaxCountArgs);
            $totalpages = ceil($totalterms / $per_page);
            $getTaxArgs = array(
                'taxonomy' => $taxonomies,
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => $atts['hide_empty'],
                'exclude' => $excludes,
                'exclude_tree' => array(),
                'include' => array(),
                'number' => $per_page,
                'fields' => 'all',
                'slug' => '',
                'parent' => '',
                'hierarchical' => true,
                'child_of' => 0,
                'get' => '',
                'name__like' => '',
                'pad_counts' => false,
                'offset' => $offset,
                'search' => '',
                'cache_domain' => 'core'
            );
            $taxData = get_terms($getTaxArgs);
            if (is_array($taxData)) {
                $class = $atts['class'];
                echo " <div class='ttw-container'><ul class='ttw-term-container " . $class . "'>";
                foreach ($taxData as $taxterm) {
                    $termID = intval($taxterm->term_id);
                    $taxonomyName = wp_kses_data($taxterm->taxonomy);
                    $termName = wp_kses_data($taxterm->slug);
                    if ($taxonomyName == 'product_cat') {
                        $imageID = get_woocommerce_term_meta($termID, 'thumbnail_id', true);
                    } else {
                        $imageID = get_term_meta($termID, 'taxonomy_thumb_id', true);
                    }
                    $thumbnailSrc = wp_get_attachment_image_src($imageID, 'full');
                    $thumbnailSrc = ($thumbnailSrc == '') ? TTWTHUMB_URL : $thumbnailSrc[0];
                    $getTermLink = get_term_link($termID, $taxonomyName);
                    ?>
                    <li class="ttw_term_box term-<?php echo $termID; ?>">
                        <a class="ttw-term-link" title="<?php echo $taxterm->name; ?>"
                           href="<?php echo $getTermLink; ?>">
                            <div class="img-container">
                                <img class="wp-post-image ttw_image" src="<?php echo $thumbnailSrc; ?>"
                                     alt="<?php echo $termName; ?>">
                            </div>
                            <h3 class="ttw-term-title" itemprop="alternativeHeadline"><?php echo $taxterm->name; ?></h3>
                        </a>
                    </li>
                    <?php
                }
                echo "</ul></div>";
            }
            printf('<nav class="ttw_pagination">%s</nav>', $this->ttw_term_navigation($totalpages, $page, 5, 0));

            return ob_get_clean();
        }

        /**
         * Script For Show Data using shortcode
         **/
        public function ttw_post_terms_icon_list_callback($atts)
        {
            ob_start();
            $atts = shortcode_atts(array(
                'taxonomy' => 'post_tag',
                'class' => 'ttw_post_term_icon_list',
                'type' => 'list',
                'hide_empty' => true,
                'post_id' => '',
            ), $atts);

            $taxonomies = $atts['taxonomy'];

            if (trim($atts['post_id']) == '') {
                global $post;
                $ttwpostID = $post->ID;
            } else {
                $ttwpostID = trim($atts['post_id']);
            }


            $taxData = get_the_terms($ttwpostID, $taxonomies);

            if (is_array($taxData)) {
                $class = $atts['class'];
                echo " <div class='ttw-icon-container'><ul class='ttw-term-icon-container " . $class . "'>";
                foreach ($taxData as $taxterm) {
                    $termID = intval($taxterm->term_id);
                    $taxonomyName = wp_kses_data($taxterm->taxonomy);
                    $termName = wp_kses_data($taxterm->slug);
                    if ($taxonomyName == 'product_cat') {
                        $imageID = get_woocommerce_term_meta($termID, 'thumbnail_id', true);
                    } else {
                        $imageID = get_term_meta($termID, 'taxonomy_thumb_id', true);
                    }
                    $thumbnailSrc = wp_get_attachment_image_src($imageID, 'full');
                    $thumbnailSrc = ($thumbnailSrc == '') ? TTWTHUMB_URL : $thumbnailSrc[0];
                    $getTermLink = get_term_link($termID, $taxonomyName);
                    ?>
                    <li class="ttw_term_icon_box term-<?php echo $termID; ?>">
                        <a class="ttw-term-link" title="<?php echo $taxterm->name; ?>"
                           href="<?php echo $getTermLink; ?>">
                            <div class="icon-img-container">
                                <img class="wp-post-image ttw_icon_image" src="<?php echo $thumbnailSrc; ?>"
                                     alt="<?php echo $termName; ?>">
                            </div>
                            <h3 class="ttw-term-icon-title"
                                itemprop="alternativeHeadline"><?php echo $taxterm->name; ?></h3>
                        </a>
                    </li>
                    <?php
                }
                echo "</ul></div>";
            }


            return ob_get_clean();
        }

        public function ttw_term_navigation($totalpages, $page, $end_size, $mid_size)
        {
            $ttwNum = 999999999;
            if ($totalpages <= 1 || $page > $totalpages) return;
            return paginate_links(array(
                'base' => str_replace($ttwNum, '%#%', esc_url(get_pagenum_link($ttwNum))),
                'format' => '',
                'current' => max(1, $page),
                'total' => $totalpages,
                'prev_text' => 'Prev',
                'next_text' => 'Next',
                'type' => 'list',
                'show_all' => false,
                'end_size' => $end_size,
                'mid_size' => $mid_size
            ));
        }
    }

    $ttwshortcode = new TTWSHORTCODE();
}
?>