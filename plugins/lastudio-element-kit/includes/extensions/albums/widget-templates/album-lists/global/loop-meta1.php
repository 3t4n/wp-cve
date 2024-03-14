<?php
/**
 * Posts loop meta template
 */

$show_metadata = $this->get_settings_for_display('show_meta');
$metadata = $this->get_settings_for_display('metadata1');

$output = '';

if (filter_var($show_metadata, FILTER_VALIDATE_BOOLEAN) && !empty($metadata)) {
    foreach ($metadata as $meta) {
        $item_type = isset($meta['item_type']) ? $meta['item_type'] : '';
        $meta_icon = $this->_get_icon_setting($meta['item_icon'], '<span class="meta--icon">%s</span>', '', false);
        $meta_label = !empty($meta['item_label']) ? sprintf('<span class="meta--label">%s</span>', $meta['item_label']) : '';
        $meta_value = '';
        $item_type_class = '';

        switch ($item_type) {
            case 'genres':
                $meta_value = get_the_term_list(get_the_ID(), 'la_album_genre', '', '<span class="cspr">, </span>');
                $item_type_class = 'post__genres';
                break;
            case 'artists':
	            $meta_value = get_the_term_list(get_the_ID(), 'la_album_artist', '', '<span class="cspr">, </span>');
                $item_type_class = 'post__artists';
                break;
			case 'labels':
	            $meta_value = get_the_term_list(get_the_ID(), 'la_album_label', '', '<span class="cspr">, </span>');
                $item_type_class = 'post__labels';
                break;
            case 'release_date':
				$_date = get_post_meta( get_the_ID(), 'album_release_date', true );
				if( lastudio_kit_helper()->validate_date($_date, 'Y-m-d') ){
					$meta_value = date('M j, Y', strtotime($_date));
				}
                $item_type_class = 'post__date';
                break;
            case 'people':
                $meta_value = get_post_meta( get_the_ID(), 'album_people', true );
                $item_type_class = 'post__people';
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