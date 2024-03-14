<?php
use WPSocialReviews\Framework\Support\Arr;

if (!empty($feeds) && is_array($feeds)) {
    $layout_type = isset($template_meta['layout_type']) && defined('WPSOCIALREVIEWS_PRO') ? $template_meta['layout_type'] : 'grid';
    foreach ($feeds as $index => $feed) {
        if ($index >= $sinceId && $index <= $maxId && (isset($feed['media_url']) || isset($feed['children']))) {
                $feedLink = Arr::get($feed, 'shoppable_options.show_shoppable') ? Arr::get($feed, 'shoppable_options.url_settings.url', '') : Arr::get($feed,'permalink', '');
                $target = (Arr::get($template_meta, 'post_settings.display_mode', '') === 'instagram') ? '_blank' : '';
                if(Arr::get($feed, 'shoppable_options')) {
                    $target = Arr::get($feed, 'shoppable_options.url_settings.open_in_new_tab') ? '_blank' : '';
                }
                if ($layout_type !== 'carousel') {
                    /**
                     * instagram_feed_template_item_wrapper_before hook.
                     *
                     * @hooked InstagramTemplateHandler::renderTemplateItemWrapper - 10 (outputs opening divs for the template item)
                     * */
                    do_action('wpsocialreviews/instagram_feed_template_item_wrapper_before', $template_meta);
                }
            ?>
            <div role="group" class="wpsr-ig-post <?php echo ($layout_type === 'carousel' && defined('WPSOCIALREVIEWS_PRO')) ? 'swiper-slide' : ''; ?>" data-user_name="<?php echo esc_attr(Arr::get($feed, 'username', ''));?>" data-post_id="<?php echo esc_attr(Arr::get($feed, 'id', ''));?>" data-image_size="<?php echo esc_attr(Arr::get($template_meta, 'post_settings.resolution', 'full'));?>">

                <a class="wpsr-ig-playmode" <?php echo ($template_meta['post_settings']['display_mode'] === 'instagram' && $feedLink ) ? 'href=' . esc_url($feedLink) . '' : ''; ?>
                   target="<?php echo esc_attr($target); ?>"
                   data-index="<?php echo esc_attr($index); ?>"
                   data-playmode="<?php echo isset($template_meta['post_settings']['display_mode']) ? esc_attr($template_meta['post_settings']['display_mode']) : 'instagram'; ?>"
                   data-template-id="<?php echo esc_attr($templateId); ?>"
                   rel="noopener noreferrer"
                >
                    <?php
                    /**
                     * instagram_post_media hook.
                     *
                     * @hooked InstagramTemplateHandler::renderPostMedia 10
                     * */
                    do_action('wpsocialreviews/instagram_post_media', $feed, $template_meta, $index);
                    ?>
                </a>
                <?php if (count($feed) > 6) {
                    ?>
                    <div class="wpsr-ig-post-info">
                        <?php
                        if(Arr::get($feed, 'shoppable_options.show_shoppable') && Arr::get($template_meta, 'post_settings.display_mode') !== 'popup'
                            && strlen(Arr::get($feed, 'shoppable_options.url_settings.text', ''))) {
                            do_action('wpsocialreviews/instagram_shoppable_button', $feed, $template_meta);
                        }
                        ?>
                        <?php
                        /**
                         * instagram_post_statistics hook.
                         *
                         * @hooked render_instagram_statistics_html 10
                         * */
                        if(!(Arr::get($feed, 'shoppable_options.show_shoppable') && Arr::get($template_meta, 'post_settings.display_mode') !== 'popup')) {
                            do_action('wpsocialreviews/instagram_post_statistics', $feed, $template_meta);
                        }

                        /**
                         * instagram_post_caption hook.
                         *
                         * @hooked InstagramTemplateHandler::renderPostCaption 10
                         * */
                        if(!(Arr::get($feed, 'shoppable_options.show_shoppable') && Arr::get($template_meta, 'post_settings.display_mode') !== 'popup')) {
                            do_action('wpsocialreviews/instagram_post_caption', $feed, $template_meta);
                        }
                        /**
                         * instagram_icon hook.
                         *
                         * @hooked InstagramTemplateHandler::renderIcon 10
                         * */
                        do_action('wpsocialreviews/instagram_icon');
                        ?>
                    </div>

                    <?php
                        if(Arr::get($feed, 'shoppable_options.show_shoppable') &&  Arr::get($template_meta, 'shoppable_settings.display_shoppable_icon') === 'true') {
                            do_action('wpsocialreviews/instagram_shoppable_icon');
                        }
                   ?>

                <?php } ?>
            </div>
            <?php if ($layout_type !== 'carousel') { ?>
                </div>
            <?php } ?>
            <?php
        }
    }
}