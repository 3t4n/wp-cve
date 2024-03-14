<?php
/**
 * Posts loop meta template
 */

$show_metadata = $this->get_settings_for_display('show_meta');
$metadata = $this->get_settings_for_display('metadata1');
$show_author_avatar = $this->get_settings_for_display('show_author_avatar');

$output = '';

if (filter_var($show_metadata, FILTER_VALIDATE_BOOLEAN) && !empty($metadata)) {
    foreach ($metadata as $meta) {
        $item_type = isset($meta['item_type']) ? $meta['item_type'] : '';
        $meta_icon = $this->_get_icon_setting($meta['item_icon'], '<span class="meta--icon">%s</span>', '', false);
        $meta_label = !empty($meta['item_label']) ? sprintf('<span class="meta--label">%s</span>', $meta['item_label']) : '';
        $meta_value = '';
        $item_type_class = '';

        switch ($item_type) {
            case 'category':
                $meta_value = get_the_term_list(get_the_ID(), 'la_portfolio_category', '', '<span class="cspr">, </span>');
                $item_type_class = 'post__cat';
                break;
            case 'tag':
                $meta_value = get_the_tag_list('', '<span class="cspr">, </span>');
                $item_type_class = 'post__tag';
                break;
            case 'author':
                if(filter_var($show_author_avatar, FILTER_VALIDATE_BOOLEAN)){
                    $meta_icon = sprintf('<span class="meta--icon">%s</span>', get_avatar( get_the_author_meta( "ID" )));
                }
                $meta_value = sprintf('<a href="%1$s" class="posted-by__author" rel="author">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) );
                $item_type_class = 'post__author posted-by';
                break;
            case 'pdate':
                $meta_value = get_the_date();
                $item_type_class = 'post__date';
                break;
            case 'comment':
                $meta_value = sprintf('<a href="%1$s">%2$s</a>', esc_url( get_comments_link() ), esc_html( get_comments_number() ) );
                $item_type_class = 'post__comment';
                break;

            case 'description':
            case 'client':
            case 'date':
            case 'awards':
            case 'location':
            case 'designer':
            case 'custom_field_1':
            case 'custom_field_2':
            case 'custom_field_3':
                $meta_value = get_post_meta( get_the_ID(), '_pf_' . $item_type, true );
                $item_type_class = 'pf__' . $item_type;
                break;
        }

        if (!empty($meta_value)) {
            $meta_value = sprintf('<span class="meta--value">%s</span>', $meta_value);
        }

        if (!empty($meta_value)) {
            $output .= sprintf('<div class="lakit-posts__meta__item lakit-posts__meta__item--%4$s %5$s">%1$s%2$s%3$s</div>', $meta_icon, $meta_label, $meta_value, $item_type, $item_type_class);
        }

    }

    if (!empty($output)) {
        echo sprintf('<div class="lakit-posts__meta lakit-posts__meta1">%s</div>', $output);
    }
}