<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class ContentFilter
{
    public static function init(): void
    {
        add_filter('the_content', [TypeConverter::class, 'convertToString']);
        add_filter('the_content', [static::class, 'addRelatedItems']);

        add_action('plugins_loaded', [static::class, 'registerContentFilter']);
    }

    public static function registerContentFilter(): void
    {
        $cross_linker_priority = Options::get('cross_linker_priority') == 'before_shortcodes' ? 10.5 : 15;

        $arr_content_filter = [
            # Post contents
            'the_content',

            # bbPress
            'bbp_get_forum_content',
            'bbp_get_topic_content',
            'bbp_get_reply_content',

            # WooCommerce
            'woocommerce_attribute',
            'woocommerce_short_description'
        ];

        foreach ($arr_content_filter as $filter) {
            add_filter($filter, [TypeConverter::class, 'convertToString'], $cross_linker_priority);
            add_filter($filter, [static::class, 'addCrossLinksToPostContent'], $cross_linker_priority);
        }

        add_filter('widget_text', [Core::class, 'addCrossLinks']);
    }

    public static function addRelatedItems(string $content): string
    {
        global $post;

        # If this is outside the loop we leave
        if (empty($post->ID) || empty($post->post_type)) return $content;

        if ($post->post_type == PostType::post_type_name && is_Single($post->ID)) {
            if (!has_Shortcode($content, 'encyclopedia_related_items') && Options::get('related_items') != 'none' && !post_password_required()) {
                $attributes = ['max_items' => Options::get('number_of_related_items')];

                if (Options::get('related_items') == 'above')
                    $content = Shortcodes::Related_Items($attributes) . $content;
                else
                    $content .= Shortcodes::Related_Items($attributes);
            }
        }

        return $content;
    }

    public static function addCrossLinksToPostContent(string $content): string
    {
        global $post;

        static $processed_contents = [];

        # first trivial solution
        if (empty($content))
            return '';

        # If this is outside the loop we leave
        if (empty($post->post_type))
            return $content;

        # If this is for the excerpt we leave
        if (doing_Filter('get_the_excerpt'))
            return $content;

        # If we have already processed this post we leave
        $content_hash = md5($content);
        if (isset($processed_contents[$content_hash]))
            return $content;

        # Check if Cross-Linking is activated for this post
        if (apply_Filters('encyclopedia_link_items_in_post', true, $post)) {
            $content = Core::addCrossLinks($content, $post);
            $content_hash = md5($content);
            $processed_contents[$content_hash] = true;
        }

        return $content;
    }
}

ContentFilter::init();
