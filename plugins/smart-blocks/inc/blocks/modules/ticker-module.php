<?php

namespace Smart_Blocks;

class Smart_Blocks_Ticker_Module {

    public $attributes = array();

    /** Render Layout */
    public function render($attributes) {
        $this->attributes = $attributes;
        $content_rendered = '';

        $ticker_pause = $this->attributes['pause'];

        $parameters = array(
            'pause' => intval($ticker_pause),
            'autoplay' => $this->attributes['autoplay'] == 'yes' ? true : false,
        );

        $parameters_json = json_encode($parameters);


        $args = $this->query_args();
        $query = new \WP_Query($args);
        $content_rendered .= '<div id="' . $this->attributes['id'] . '">';
        if ($query->have_posts()):
            $content_rendered .= '<div ' . get_block_wrapper_attributes(['class' => 'sb-ticker wp-block-smart-blocks']) . '>';
            $content_rendered .= '<span class="sb-ticker-title">';
            $ticker_title = isset($this->attributes['tickerTitle']) ? $this->attributes['tickerTitle'] : null;
            if ($ticker_title) {
                $content_rendered .= esc_html($ticker_title);
            }
            $content_rendered .= '</span>';
            $content_rendered .= '<div class="sb-ticker-posts"><div class="owl-carousel" data-params=' . $parameters_json . '>';
            while ($query->have_posts()): $query->the_post();
                $content_rendered .= '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
            endwhile;
            wp_reset_postdata();
            $content_rendered .= '</div></div>';
            $content_rendered .= '</div>';
        endif;
        $content_rendered .= '</div>';
        return $content_rendered;
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
        $args['posts_per_page'] = $this->attributes['noOfPosts'];
        $args['post__not_in'] = isset($this->attributes['excludePosts']) && $this->attributes['excludePosts'] ? $this->attributes['excludePosts'] : [];

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

}
