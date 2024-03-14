<?php

namespace Smart_Blocks;

class Smart_Blocks_Single_News_Two {

    public $attributes = array();

    /** Render Layout */
    public function render($attributes) {
        $this->attributes = $attributes;
        $content_rendered = '';
        $content_rendered .= '<div id="' . $this->attributes['id'] . '">';
        $content_rendered .= '<div ' . get_block_wrapper_attributes(['class' => 'sb-single-post wp-block-smart-blocks']) . '>';

        $args = $this->query_args();
        $post_query = new \WP_Query($args);

        if ($post_query->have_posts()) {
            $content_rendered .= '<div class="sb-single-post-two">';
            while ($post_query->have_posts()) {
                $post_query->the_post();
                $image_size = $this->attributes['postImageSize'];
                $excerpt_length = $this->attributes['postExcerptLength'];

                $content_rendered .= '<div class="sb-post-image sb-post-graident-title">';
                $content_rendered .= '<div class="sb-post-thumb">';
                $content_rendered .= '<a href="' . get_the_permalink() . '">';
                $content_rendered .= '<div class="sb-thumb-container">';
                if (has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), $image_size);
                    $content_rendered .= '<img alt="' . the_title_attribute(array('echo' => false)) . '" src="' . esc_url($image[0]) . '">';
                }
                $content_rendered .= '</div>';
                $content_rendered .= '</a>';
                $content_rendered .= '</div>';

                $content_rendered .= '<div class="sb-post-content sb-align-' . $this->attributes['contentAlignment'] . '">';
                $content_rendered .= '<h3 class="sb-post-title ' . smart_blocks_get_font_class($this->attributes['postTypography']) . '"><a href="' . get_the_permalink() . '">' . esc_html(get_the_title()) . '</a></h3>';

                $content_rendered .= $this->get_post_meta();

                if ($excerpt_length) {
                    $content_rendered .= '<div class="sb-excerpt">' . smart_blocks_custom_excerpt($excerpt_length) . '</div>';
                }
                $content_rendered .= '</div>';
                $content_rendered .= '</div>';
            }
            wp_reset_postdata();
            $content_rendered .= '</div>';
        }
        $content_rendered .= '</div>';
        $content_rendered .= '</div>';
        return $content_rendered;
    }

    /** Get Post Metas */
    public function get_post_meta() {
        $content = '';
        $post_author = $this->attributes['postPostAuthor'];
        $post_date = $this->attributes['postPostDate'];
        $post_comment = $this->attributes['postPostComments'];

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

    /** Query Args */
    public function query_args() {

        $filter_option = $this->attributes['filterOption'];
        if ($filter_option == 'single-post') {
            if (!empty($this->attributes['postId'])) {
                $args['p'] = $this->attributes['postId'];
            }
        } elseif ($filter_option == 'categories') {
            if (!empty($this->attributes['categories'])) {
                $args['tax_query'][] = [
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $this->attributes['categories'],
                ];
            }
        } elseif ($filter_option == 'tags') {
            if (!empty($this->attributes['tags'])) {
                $args['tax_query'][] = [
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $this->attributes['tags'],
                ];
            }
        }

        if (isset($this->attributes['offset'])) {
            $args['offset'] = $this->attributes['offset'];
        }

        $args['ignore_sticky_posts'] = 1;
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = 1;

        return $args;
    }

}
