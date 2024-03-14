<?php

namespace Memsource\Service\ExternalPlugin;

use Elementor\Plugin;
use Memsource\Dto\MetaKeyDto;

/**
 * Elementor plugin stores data as a JSON within a custom field called `_elementor_data`.
 * This class is reponsible for converting from and to this field.
 */
class ElementorPlugin
{
    private const ELEMENTOR_DATA_CUSTOM_FIELD_NAME = '_elementor_data';

    private const ELEMENTOR_DATA_PATTERN_ENCODE = '<div data-type="memsource_elementor_data" data-id="%s" data-key="%s">%s</div>';
    private const ELEMENTOR_DATA_PATTERN_DECODE = '/<div data-type="memsource_elementor_data" data-id="([a-z0-9]+)" data-key="([_a-z]+)">(|.+?)<\/div>/sm';

    private const ELEMENTOR_RESULT_PATTERN_ENCODE = '<!--_elementor_data_%s-->';
    private const ELEMENTOR_RESULT_PATTERN_DECODE = '|.+</div><!--_elementor_data_(.+)-->|sm';

    private const ELEMENTOR_TRANSLATABLE_FIELDS = [
        // animated-headline
        'before_text',
        'highlighted_text',
        'rotating_text',
        'after_text',

        // blockquote
        'blockquote_content',
        'author_name',
        'tweet_button_label',
        'user_name',

        // call-to-action
        'title',
        'description',
        'button',
        'ribbon_title',

        // reviews
        'name',
        'title',
        'content',

        // code-highlight
        'highlight_lines',

        // countdown
        'label_days',
        'label_hours',
        'label_minutes',
        'label_seconds',
        'message_after_expire',

        // custom-attributes
        '_attributes',

        // contact-url
        'mail_subject',
        'mail_body',
        'username',
        'event_title',
        'event_description',
        'event_location',

        // flip-box
        'title_text_a',
        'description_text_a',
        'title_text_b',
        'description_text_b',
        'button_text',

        // discord
        'discord_title',
        'discord_content',

        // email
        'email_subject',
        'email_content',

        // slack
        'slack_pretext',
        'slack_title',
        'slack_text',

        // acceptance
        'acceptance_text',

        // step
        'previous_button',
        'next_button',

        // form
        'field_label',
        'placeholder',
        'field_html',
        'field_value',
        'step_next_label',
        'step_previous_label',
        'button_text',
        'success_message',
        'error_message',
        'required_field_message',
        'invalid_message',

        // login
        'button_text',
        'user_label',
        'user_placeholder',
        'password_label',
        'password_placeholder',

        // gallery
        'gallery_title',
        'show_all_galleries_label',

        // hotspot
        'hotspot_label',

        // lottie
        'caption',

        // payment-button
        'product_name',
        'product_sku',
        'error_message_global',
        'error_message_payment',

        // skin-base
        'read_more_text',

        // button-widget-trait
        'text',

        // posts-base
        'load_more_no_posts_custom_message',

        // price-list
        'title',
        'item_description',

        // price-table
        'heading',
        'sub_heading',
        'currency_symbol_custom',
        'period',
        'item_text',
        'button_text',
        'footer_additional_info',
        'ribbon_title',

        // share-buttons
        'text',

        // slides
        'heading',
        'description',
        'button_text',

        // table-of-contents
        'title',
        'container',

        // archive-posts
        'nothing_found_message',

        // post-excerpt
        'excerpt',

        // author box
        'author_name',
        'author_bio',
        'link_text',

        // post-info
        'text_prefix',
        'string_no_comments',
        'string_one_comment',
        'string_comments',
        'custom_text',

        // post-navigation
        'prev_label',
        'prev_label',

        // search-form
        'placeholder',
        'button_text',

        // sitemap
        'sitemap_title',

        // video-playlist
        'playlist_title',
        'title',
        'duration',
        'inner_tab_title_1',
        'inner_tab_title_2',
        'inner_tab_label_show_more',
        'inner_tab_label_show_less',

        // product-sale
        'text',

        // archive-products
        'nothing_found_message',

        // product-meta
        'category_caption_single',
        'category_caption_plural',
        'tag_caption_single',
        'tag_caption_plural',
        'sku_caption',
        'sku_missing_caption',

        // document
        'post_title',

        // page-base
        'post_excerpt',

        // alert
        'alert_title',
        'alert_description',

        // counter
        'prefix',
        'suffix',
        'title',

        // image
        'caption',

        // progress
        'title',
        'inner_text',

        // testimonial
        'testimonial_content',
        'testimonial_name',
        'testimonial_job',

        // global-colors, global-typography, heading
        'title',

        // accordion, tabs, toggle
        'tab_title',
        'tab_content',

        // button, divider, icon-list, star-rating, text-path
        'text',

        // icon-box, image-box
        'title_text',
        'description_text',

        // widget
        'editor',
    ];

    public function isElementorCustomField($contentType, $contentId, $metaKey): bool
    {
        return $contentType === MetaKeyDto::TYPE_POST && $this->isPostCreatedByElementorPlugin($contentId) && $this->isElementorDataField($metaKey);
    }

    public function isPostCreatedByElementorPlugin($postId): bool
    {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }

        $document = Plugin::$instance->documents->get($postId);

        return $document && $document->is_built_with_elementor();
    }

    public function isElementorDataField($metaKey): bool
    {
        return $metaKey === self::ELEMENTOR_DATA_CUSTOM_FIELD_NAME;
    }

    public function commentElementorDataField($metaValue): string
    {
        return sprintf(self::ELEMENTOR_RESULT_PATTERN_ENCODE, $metaValue);
    }

    public function encodeElementorDataField($metaValue): string
    {
        $html = '';
        $decodedMetaValue = json_decode($metaValue, true);
        $this->processElementorElements($html, $decodedMetaValue);

        return $html;
    }

    private function processElementorElements(string &$result, array $items)
    {
        foreach ($items as $item) {
            foreach ($item['elements'] as $element) {
                $this->processElementorElements($result, $element['elements']);
            }

            foreach ($item['settings'] as $fieldName => $fieldValue) {
                if (is_array($fieldValue)) {
                    foreach ($fieldValue as $nested) {
                        if (is_array($nested)) {
                            foreach ($nested as $nestedName => $nestedValue) {
                                if (in_array($nestedName, self::ELEMENTOR_TRANSLATABLE_FIELDS, true)) {
                                    $result .= sprintf(self::ELEMENTOR_DATA_PATTERN_ENCODE, $nested['_id'], $nestedName, $nestedValue);
                                }
                            }
                        }
                    }
                } else {
                    if (in_array($fieldName, self::ELEMENTOR_TRANSLATABLE_FIELDS, true)) {
                        $result .= sprintf(self::ELEMENTOR_DATA_PATTERN_ENCODE, $item['id'], $fieldName, $fieldValue);
                    }
                }
            }
        }
    }

    public function decodeElementorDataField($translations): string
    {
        preg_match(self::ELEMENTOR_RESULT_PATTERN_DECODE, $translations, $json);
        $elementorData = json_decode($json[1], true);

        preg_match_all(self::ELEMENTOR_DATA_PATTERN_DECODE, $translations, $matches, PREG_SET_ORDER);

        foreach ($matches ?: [] as $match) {
            $this->replaceTranslationInElementorData($elementorData, $match[1], $match[2], $match[3]);
        }

        $encoded = wp_json_encode($elementorData, JSON_UNESCAPED_UNICODE);

        // prevent wp_unslash() called during add_post_meta()/update_meta()
        $result = str_replace('\\', '\\\\', $encoded);

        return $result;
    }

    private function replaceTranslationInElementorData(&$elementorData, $translationId, $translationKey, $translation)
    {
        foreach ($elementorData ?? [] as $itemKey => $item) {
            foreach ($item['elements'] as $elementKey => $element) {
                $this->replaceTranslationInElementorData(
                    $elementorData[$itemKey]['elements'][$elementKey]['elements'],
                    $translationId,
                    $translationKey,
                    $translation
                );
            }

            foreach ($item['settings'] as $fieldName => $fieldValue) {
                if (is_array($fieldValue)) {
                    foreach ($fieldValue as $nestedKey => $nested) {
                        if (isset($nested['_id']) && ($nested['_id'] === $translationId)) {
                            $elementorData[$itemKey]['settings'][$fieldName][$nestedKey][$translationKey] = $translation;
                        }
                    }
                } else {
                    if ($item['id'] === $translationId && $fieldName === $translationKey) {
                        $elementorData[$itemKey]['settings'][$fieldName] = $translation;
                    }
                }
            }
        }
    }

    public function refreshPost($postId)
    {
        if ($this->isPostCreatedByElementorPlugin($postId)) {
            Plugin::$instance->db->save_plain_text($postId);
        }
    }
}
