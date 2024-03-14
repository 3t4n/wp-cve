<?php
use WPSocialReviews\Framework\Support\Arr;

echo '</div>'; // row end

if( $layout_type === 'carousel' && defined('WPSOCIALREVIEWS_PRO')) {
    echo '</div>'; // swiper container end
    echo '<div class="wpsr-swiper-carousel-wrapper">';
    if( $feed_settings['carousel_settings']['navigation'] === 'arrow' || $feed_settings['carousel_settings']['navigation'] === 'both') {
        echo '<div class="wpsr-swiper-prev-next wpsr-swiper-next swiper-button-next"></div>
              <div class="wpsr-swiper-prev-next wpsr-swiper-prev swiper-button-prev"></div>';
    }
    if( $feed_settings['carousel_settings']['navigation'] === 'dot' || $feed_settings['carousel_settings']['navigation'] === 'both') {
        echo '<div class="wpsr-swiper-pagination swiper-pagination" aria-label="Pagination"></div>';
    }
    echo '</div>';
}

$mt_30 = $column_gaps === 'no_gap' ? 'wpsr-mt-20' : '';
echo '<div class="wpsr-fb-feed-footer wpsr-fb-feed-follow-button-group wpsr-row ' . esc_attr($mt_30) . '">';
//pagination
$feed_type = Arr::get($feed_settings, 'source_settings.feed_type', '');
if (count($feeds) > $paginate && $layout_type !== 'carousel' && $pagination_type === 'load_more' && $feed_type !== 'album_feed') {
    do_action('wpsocialreviews/load_more_button', $feed_settings, $templateId, $paginate, $layout_type, $total, $feed_type);
}

if (Arr::get($feed_settings, 'share_button_settings.share_button_position') !== 'header') {

    /**
     * facebook_feed_like_button hook.
     *
     * @hooked render_facebook_feed_like_button_html 10
     * */
    if (Arr::get($feed_settings, 'like_button_settings.like_button_position') !== 'header') {
        do_action('wpsocialreviews/facebook_feed_like_button', $feed_settings, $header);
    }

    /**
     * facebook_feed_share_button hook.
     *
     * @hooked render_facebook_feed_share_button_html 10
     * */
    if (Arr::get($feed_settings, 'share_button_settings.share_button_position') !== 'header') {
        do_action('wpsocialreviews/facebook_feed_share_button', $feed_settings, $header);
    }
}
echo '</div>';

echo '</div>'; // wpsr-fb-feed-wrapper-inner end

echo '</div>'; // wpsr-container end
echo '</div>'; // wpsr-fb-feed-wrapper end