<?php
/**
 * Posts loop meta template
 */

$show_metadata = $this->get_settings_for_display('show_meta');
$metadata = $this->get_settings_for_display('metadata1');

$text_swap = [
	'upcoming'      => esc_html__('Up Coming', 'lastudio-kit'),
	'past'          => esc_html__('Past', 'lastudio-kit'),
	'cancelled'     => esc_html__('Cancelled', 'lastudio-kit'),
	'sold_out'      => esc_html__('Sold Out', 'lastudio-kit'),
];

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
	            $meta_value = get_the_term_list(get_the_ID(), 'la_event_type', '', '<span class="cspr">, </span>');
                $item_type_class = 'post__cat';
                break;
            case 'start_date':
	            $_date = get_post_meta( get_the_ID(), 'event_start_date', true );
	            if( lastudio_kit_helper()->validate_date($_date, 'Y-m-d') ){
		            $meta_value = date(get_option( 'date_format', 'M j, Y'), strtotime($_date));
	            }
	            $item_type_class = 'post__date';
                break;
			case 'end_date':
	            $_date = get_post_meta( get_the_ID(), 'event_end_date', true );
	            if( lastudio_kit_helper()->validate_date($_date, 'Y-m-d') ){
		            $meta_value = date(get_option( 'date_format', 'M j, Y'), strtotime($_date));
	            }
	            $item_type_class = 'post__date';
                break;
            case 'date':
	            $_date1 = get_post_meta( get_the_ID(), 'event_start_date', true );
	            $_date2 = get_post_meta( get_the_ID(), 'event_end_date', true );
	            $_tmp = [];
	            if( lastudio_kit_helper()->validate_date($_date1, 'Y-m-d') ){
		            $_tmp[] = date(get_option( 'date_format', 'M j, Y'), strtotime($_date1));
	            }
				if( lastudio_kit_helper()->validate_date($_date2, 'Y-m-d') ){
					$_tmp[] = date(get_option( 'date_format', 'M j, Y'), strtotime($_date2));
	            }
				if(!empty($_tmp)){
					$meta_value = join('-', $_tmp);
				}
                $item_type_class = 'post__date';
                break;
	        case 'status':
		        $_val = get_post_meta( get_the_ID(), 'event_status', true );
		        $meta_value = $text_swap[$_val] ?? '';
		        $item_type_class = 'post__status';
		        break;
			case 'location':
		        $_val = get_post_meta( get_the_ID(), 'event_location', true );
		        $meta_value = $_val;
		        $item_type_class = 'post__loc';
		        break;
			case 'stage':
		        $_val = get_post_meta( get_the_ID(), 'event_stage', true );
		        $meta_value = $_val;
		        $item_type_class = 'post__stage';
		        break;
			case 'organized_by':
		        $_val = get_post_meta( get_the_ID(), 'event_organized_by', true );
		        $meta_value = $_val;
		        $item_type_class = 'post__organized_by';
		        break;
        }

        $meta_value = apply_filters('lastudio-kit/lakit-events/metadata/output', $meta_value, $item_type, get_the_ID());

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