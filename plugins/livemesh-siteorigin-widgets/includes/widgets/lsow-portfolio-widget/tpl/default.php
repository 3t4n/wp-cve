<?php
/**
 * @var $settings
 */

if (!empty($instance['title']))
    echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_posts_grid_' . $this->id . '_settings', $settings);

$taxonomies = array();

$query_args = siteorigin_widget_post_selector_process_query($settings['posts']);

$query_args = apply_filters('lsow_posts_grid_' . $this->id . '_query_args', $query_args, $settings);

// Use the processed post selector query to find posts.
$loop = new WP_Query($query_args);

// Loop through the posts and do something with them.
if ($loop->have_posts()) :

    // Check if any taxonomy filter has been applied
    list($chosen_terms, $taxonomies) = lsow_get_chosen_terms($query_args);

    if (empty($chosen_terms))
        $taxonomies[] = $settings['taxonomy_filter'];

    $output = '<div class="lsow-portfolio-wrap lsow-gapless-grid">';

    if (!empty($settings['heading']) || $settings['filterable']):

        $header_class = (trim($settings['heading']) === '') ? ' lsow-no-heading' : '';

        $grid_header = '<div class="lsow-portfolio-header ' . $header_class . '">';

        if (!empty($settings['heading'])) :

            $grid_header .= '<h3 class="lsow-heading">' . wp_kses_post($settings['heading']) . '</h3>';

        endif;

        if ($settings['filterable'])
            $grid_header .= lsow_get_taxonomy_terms_filter($taxonomies, $chosen_terms);

        $grid_header .= '</div>';

        $output .= apply_filters('lsow_posts_grid_header', $grid_header, $settings);

    endif;

    $output .= '<div id="lsow-portfolio-' . uniqid()
        . '" class="lsow-portfolio js-isotope lsow-' . esc_attr($settings['layout_mode']) . ' lsow-grid-container ' . lsow_get_grid_classes($settings)
        . '" data-isotope-options=\'{ "itemSelector": ".lsow-portfolio-item", "layoutMode": "' . esc_attr($settings['layout_mode']) . '"}\'>';

    $current_page = get_queried_object_id();

    while ($loop->have_posts()) : $loop->the_post();

        $post_id = get_the_ID();

        if ($post_id === $current_page)
            continue; // skip current page since we can run into infinite loop when users choose All option in build query

        $style = '';

        foreach ($taxonomies as $taxonomy) {

            $terms = get_the_terms($post_id, $taxonomy);

            if (!empty($terms) && !is_wp_error($terms)) {

                foreach ($terms as $term) {
                    $style .= ' term-' . $term->term_id;
                }
            }
        }

        $entry_output = '<div data-id="id-' . $post_id . '" class="lsow-grid-item lsow-portfolio-item ' . $style . '">';

        $entry_output .= '<article id="post-' . $post_id . '" class="' . join(' ', get_post_class('', $post_id)) . '">';

        if ($thumbnail_exists = has_post_thumbnail()):

            $entry_image = '<div class="lsow-project-image">';

            $image_attrs = array(
                'class' => 'lsow-image ' . lsow_disable_lazy_load_classes(),
                'data-no-lazy' => 1,
                'loading' => 'eager'
            );

            if ($settings['image_linkable']):

                $thumbnail_html = '<a href="' . get_the_permalink()
                    . '" target="' . $settings['link_target']
                    . '">' . get_the_post_thumbnail($post_id, $settings['image_size'], $image_attrs)
                    . '</a>';

            else:

                $thumbnail_html = get_the_post_thumbnail($post_id, $settings['image_size'], $image_attrs);

            endif;

            $entry_image .= apply_filters('lsow_posts_grid_thumbnail_html', $thumbnail_html, $post_id, $settings);

            if ($settings['display_title_on_thumbnail'] || $settings['display_taxonomy_on_thumbnail']):

                $image_info = '<div class="lsow-image-info">';

                $image_info .= '<div class="lsow-entry-info">';

                if ($settings['display_title_on_thumbnail']):

                    $image_info .= '<h3 class="lsow-post-title">';

                    $image_info .= '<a href="' . get_permalink()
                        . '" title="' . get_the_title()
                        . '" target="' . $settings["link_target"]
                        . '" rel="bookmark">' . get_the_title()
                        . '</a>';

                    $image_info .= '</h3>';

                endif;

                if ($settings['display_taxonomy_on_thumbnail']):

                    $image_info .= lsow_get_info_for_taxonomies($taxonomies);

                endif;

                $image_info .= '</div>';

                $image_info .= '</div><!-- .lsow-image-info -->';

                $entry_image .= apply_filters('lsow_posts_grid_image_info', $image_info, $post_id, $settings);

            endif;

            $entry_image .= '</div>';

            $entry_output .= apply_filters('lsow_posts_grid_entry_image', $entry_image, $post_id, $settings);

        endif;

        if (($settings['display_title']) || ($settings['display_summary'])) :

            $entry_text = '<div class="lsow-entry-text-wrap ' . ($thumbnail_exists ? '' : ' nothumbnail') . '">';

            if ($settings['display_title']) :

                $entry_title = '<h3 class="entry-title">';

                $entry_title .= '<a href="' . get_permalink()
                    . '" title="' . get_the_title()
                    . '" target="' . $settings["link_target"]
                    . '" rel="bookmark">' . get_the_title()
                    . '</a>';

                $entry_title .= '</h3>';

                $entry_text .= apply_filters('lsow_posts_grid_entry_title', $entry_title, $post_id, $settings);

            endif;

            if (($settings['post_meta']['display_post_date']) || ($settings['post_meta']['display_author']) || ($settings['post_meta']['display_taxonomy'])) :

                $entry_meta = '<div class="lsow-entry-meta">';

                if ($settings['post_meta']['display_author']):

                    $entry_meta .= lsow_entry_author();

                endif;

                if ($settings['post_meta']['display_post_date']):

                    $entry_meta .= lsow_entry_published();

                endif;

                if ($settings['post_meta']['display_taxonomy']):

                    $entry_meta .= lsow_get_info_for_taxonomies($taxonomies);

                endif;

                $entry_meta .= '</div>';

                $entry_text .= apply_filters('lsow_posts_grid_entry_meta', $entry_meta, $post_id, $settings);

            endif;

            if ($settings['display_summary']) :

                $excerpt = '<div class="entry-summary">';

                $excerpt .= get_the_excerpt();

                $excerpt .= '</div>';

                $entry_text .= apply_filters('lsow_posts_grid_entry_excerpt', $excerpt, $post_id, $settings);

            endif;

            if ($settings['display_read_more']) :

                $read_more_text = $settings['read_more_text'];

                $read_more = '<div class="lsow-read-more">';

                $read_more .= '<a href="' . get_the_permalink() . '" target="' . $settings["link_target"] . '">' . $read_more_text . '</a>';

                $read_more .= '</div>';

                $entry_text .= apply_filters('lsow_posts_grid_read_more_link', $read_more, $post_id, $settings);

            endif;

            $entry_text .= '</div>';

            $entry_output .= apply_filters('lsow_posts_grid_entry_text', $entry_text, $post_id, $settings);

        endif;

        $entry_output .= '</article><!-- .hentry -->';

        $entry_output .= '</div>';

        $output .= apply_filters('lsow_posts_grid_entry_output', $entry_output, $post_id, $settings);

    endwhile;

    wp_reset_postdata();

    $output .= '</div><!-- .lsow-portfolio -->';

    $output .= '</div><!-- .lsow-portfolio-wrap -->';

    echo apply_filters('lsow_posts_grid_output', $output, $settings);

endif;