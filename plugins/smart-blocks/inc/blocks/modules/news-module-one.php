<?php

namespace Smart_Blocks;

class Smart_Blocks_News_Module_One {

    public $attributes = array();

    /** Render Layout */
    public function render($attributes) {
        $this->attributes = $attributes;
        $featured_post_image_size = $this->attributes['featuredImageSize'];
        $side_post_image_size = $this->attributes['sideImageSize'];
        $content_rendered = "";
        $content_rendered .= '<div id="' . $this->attributes['id'] . '">';
        $content_rendered .= '<div ' . get_block_wrapper_attributes(['class' => 'sb-news-module-one wp-block-smart-blocks']) . '>';
        $content_rendered .= $this->render_header();
        $content_rendered .= '<div class="sb-news-module-one-wrap">';
        $args = $this->query_args();
        $query = new \WP_Query($args);
        while ($query->have_posts()): $query->the_post();
            $index = $query->current_post + 1;
            $image_size = ($index == 1) ? $featured_post_image_size : $side_post_image_size;
            $title_class = $index == 1 ? 'sb-large-title ' . smart_blocks_get_font_class($this->attributes['featuredTypography']) : 'sb-big-title ' . smart_blocks_get_font_class($this->attributes['sideTypography']);
            $image_height = $index == 1 ? $this->attributes['featuredImageHeight'] : $this->attributes['sideImageHeight'];
            $content_rendered .= '<div class="sb-post-item">';
            $content_rendered .= '<div class="sb-post-thumb">';
            $content_rendered .= '<a href="' . get_the_permalink() . '">';
            $content_rendered .= '<div class="sb-thumb-container">';
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), $image_size);
                $content_rendered .= '<img alt="' . the_title_attribute(array('echo' => false)) . '" src="' . esc_url($image[0]) . '">';
            }
            $content_rendered .= '</div>';
            $content_rendered .= '</a>';
            if ($index == 1) {
                if ($this->attributes['featuredPostCategory'] == 'yes')
                    $content_rendered .= get_the_category_list();
            } else {
                if ($this->attributes['sidePostCategory'] == 'yes')
                    $content_rendered .= smart_blocks_get_the_primary_category();
            }
            $content_rendered .= '</div>';
            $content_rendered .= '<div class="sb-post-content">';
            $content_rendered .= '<h3 class="sb-post-title ' . esc_attr($title_class) . '">';
            $content_rendered .= '<a href="' . get_the_permalink() . '">';
            $content_rendered .= get_the_title();
            $content_rendered .= '</a>';
            $content_rendered .= '</h3>';
            $content_rendered .= $this->get_post_meta($index);
            $content_rendered .= $this->get_post_excerpt($index);
            $content_rendered .= '</div>';
            $content_rendered .= '</div>';
        endwhile;
        wp_reset_postdata();
        $content_rendered .= '</div>';
        $content_rendered .= '</div>';
        $content_rendered .= '</div>';
        return $content_rendered;
    }

    /** Render Header */
    public function render_header() {
        $content = '';

        if (isset($this->attributes['headerTitle']) && $this->attributes['headerTitle']) {
            $content .= '<h2 class="sb-block-title ' . esc_attr($this->attributes['headerStyle']) . ' ' . smart_blocks_get_font_class($this->attributes['headerTitleTypography']) . '">';
            $content .= '<span>';
            $content .= $this->attributes['headerTitle'];
            $content .= '</span>';
            $content .= '</h2>';
        }
        return $content;
    }

    /** Query Args */
    public function query_args() {
        $post_type = $args['post_type'] = $this->attributes['postsPostType'];
        $args['orderby'] = $this->attributes['orderBy'];
        $args['order'] = $this->attributes['order'];
        $args['ignore_sticky_posts'] = 1;
        $args['post_status'] = 'publish';
        if (isset($this->attributes['offset']))
            $args['offset'] = $this->attributes['offset'];
        $args['posts_per_page'] = 3;
        $args['post__not_in'] = isset($this->attributes['excludePosts']) && isset($this->attributes['excludePosts']) && $this->attributes['excludePosts'] ? $this->attributes['excludePosts'] : [];

        $args['tax_query'] = [];

        if (isset($this->attributes['categories']) && $this->attributes['categories']) {
            foreach ($this->attributes['categories'] as $taxonomy => $terms) {
                if (sb_is_taxonomy_assigned_to_post_type($this->attributes['postsPostType'], $taxonomy) && !empty($terms)) {
                    $args['tax_query'][] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $terms,
                    ];
                }
            }
        }
        return $args;
    }

    /** Get Post Excerpt */
    public function get_post_excerpt($count) {
        $excerpt_length = $count == 1 ? $this->attributes['featuredExcerptLength'] : $this->attributes['sideExcerptLength'];
        if ($excerpt_length) {
            return '<div class="sb-excerpt">' . smart_blocks_custom_excerpt($excerpt_length) . '</div>';
        }
    }

    /** Get Post Metas */
    public function get_post_meta($count) {
        $post_author = $count == 1 ? $this->attributes['featuredPostAuthor'] : $this->attributes['sidePostAuthor'];
        $post_date = $count == 1 ? $this->attributes['featuredPostDate'] : $this->attributes['sidePostDate'];
        $post_comment = $count == 1 ? $this->attributes['featuredPostComments'] : $this->attributes['sidePostComments'];
        $content = '';

        if ($post_author == 'yes' || $post_date == 'yes' || $post_comment == 'yes') {
            $content .= '<div class="sb-post-meta">';
            if ($post_author == 'yes') {
                $content .= smart_blocks_author_name();
            }
            if ($post_date == 'yes') {
                $date_format = $this->attributes['dateFormat'];

                if ($date_format == 'relative_format') {
                    $content .= smart_blocks_time_ago();
                } else if ($date_format == 'default') {
                    $content .= smart_blocks_post_date();
                } else if ($date_format == 'custom') {
                    $format = $this->attributes['customDateFormat'];
                    $content .= smart_blocks_post_date($format);
                }
            }

            if ($post_comment == 'yes') {
                $content .= smart_blocks_comment_count();
            }
            $content .= '</div>';
        }
        return $content;
    }

}
