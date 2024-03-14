<?php
/**
 * Posts loop meta template
 */

$show_metadata = $this->get_settings_for_display('show_meta');
$metadata = $this->get_settings_for_display('metadata1');

$form_id = get_the_ID();
$form = new Give_Donate_Form($form_id);

$goal_progress_stats = give_goal_progress_stats($form);

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
                if( taxonomy_exists('give_forms_category') ){
                    $meta_value = get_the_term_list(get_the_ID(), 'give_forms_category', '', '<span class="cspr">, </span>');
                }
                $item_type_class = 'post__cat';
                break;
            case 'tags':
            case 'tag':
                if( taxonomy_exists('give_forms_tag') ) {
                    $meta_value = get_the_term_list(get_the_ID(), 'give_forms_tag', '', '<span class="cspr">, </span>');
                }
                $item_type_class = 'post__tag';
                break;
            case 'goal_amount':
                $meta_value = $goal_progress_stats['actual'] ?? '';
	            $item_type_class = 'post__amount';
                break;
			case 'amount_raised':
                $meta_value = $goal_progress_stats['goal'] ?? '';
                $item_type_class = 'post__amount';
                break;
            case 'number_donations':
	            $meta_value = $form->get_sales();
                $item_type_class = 'post__amount';
                break;
        }

        $meta_value = apply_filters('lastudio-kit/lakit-give-form-grid/metadata/output', $meta_value, $item_type, get_the_ID());

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