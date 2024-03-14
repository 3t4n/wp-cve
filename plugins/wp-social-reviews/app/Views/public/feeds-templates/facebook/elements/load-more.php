<?php
use WPSocialReviews\Framework\Support\Arr;

$feed_type = $feed_type ? $feed_type : '';
$feed_id =  Arr::get($feed, 'id', '');
$load_more_button_text = Arr::get($template_meta, 'pagination_settings.load_more_button_text');
echo '<button aria-label="'.esc_attr($load_more_button_text).'" class="wpsr-fb-load-more wpsr_more wpsr-load-more-default"
        id="wpsr-fb-load-more-btn-' . esc_attr($templateId) . '"
        data-paginate="' . intval($paginate) . '"
        data-template_id="' . intval($templateId) . '"
        data-template_type="' . esc_attr($layout_type) . '"
        data-platform="facebook_feed"
        data-page="1"
        data-feed_type="' . esc_attr($feed_type) . '"
        data-feed_id="' . esc_attr($feed_id) . '"
        data-total="' . intval($total) . '">
                '.$load_more_button_text.'
        <div class="wpsr-load-icon-wrapper">
            <span></span>
        </div>
    </button>';
?>