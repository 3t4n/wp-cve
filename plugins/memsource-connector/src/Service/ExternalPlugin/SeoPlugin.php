<?php

namespace Memsource\Service\ExternalPlugin;

/**
 * Support for "Yoast SEO" and "SEOPress" plugins.
 */
class SeoPlugin
{
    private const YOAST_SEO_TAG_START = '<yoast_seo_tag_';
    private const YOAST_SEO_TAG_END = '/>';
    private const YOAST_SEO_CUSTOM_FIELD_PREFIX = '_yoast_wpseo_';
    private const YOAST_SEO_CUSTOM_REPLACEMENT_PREFIX = 'yoast_seo_tag_';

    private const SEOPRESS_TAG_START = '<seo_press_tag_';
    private const SEOPRESS_TAG_END = '/>';
    private const SEOPRESS_CUSTOM_FIELD_PREFIX = '_seopress_';
    private const SEOPRESS_CUSTOM_REPLACEMENT_PREFIX = 'seo_press_tag_';

    private const CUSTOM_FIELD_NAMES = [
        '_yoast_wpseo_focuskw',
        '_yoast_wpseo_title',
        '_yoast_wpseo_metadesc',
        '_yoast_wpseo_bctitle',
        '_yoast_wpseo_opengraph-title',
        '_yoast_wpseo_opengraph-description',
        '_yoast_wpseo_twitter-title',
        '_yoast_wpseo_twitter-description',

        '_seopress_titles_title',
        '_seopress_titles_desc',
        '_seopress_social_fb_title',
        '_seopress_social_fb_desc',
        '_seopress_social_twitter_title',
        '_seopress_social_twitter_desc',
        '_seopress_robots_canonical',
        '_seopress_analysis_target_kw',
    ];

    public function isSeoPluginCustomField($customFieldName): bool
    {
        return in_array($customFieldName, self::CUSTOM_FIELD_NAMES, true);
    }

    /**
     * Convert Yoast and SEOPRESS variables to HTML comments (and tags in Memsource).
     *
     * @param string $customFieldName
     * @param string $customFieldValue
     *
     * @return string
     */
    public function encodeSeoPluginVariables(string $customFieldName, string $customFieldValue): string
    {
        if ((strpos($customFieldName, self::YOAST_SEO_CUSTOM_FIELD_PREFIX) === 0) && $customFieldValue) {
            $pattern = '|(%%[a-z_]*%%)|sm';
            preg_match_all($pattern, $customFieldValue, $matches);

            foreach ($matches[0] ?? [] as $match) {
                $replacement = self::YOAST_SEO_TAG_START . str_replace('%%', '', $match) . self::YOAST_SEO_TAG_END;
                $customFieldValue = str_replace($match, $replacement, $customFieldValue);
            }
        }

        if ((strpos($customFieldName, self::SEOPRESS_CUSTOM_FIELD_PREFIX) === 0) && $customFieldValue) {
            $pattern = '|(%%[a-z_]*%%)|sm';
            preg_match_all($pattern, $customFieldValue, $matches);

            foreach ($matches[0] ?? [] as $match) {
                $replacement = self::SEOPRESS_TAG_START . str_replace('%%', '', $match) . self::SEOPRESS_TAG_END;
                $customFieldValue = str_replace($match, $replacement, $customFieldValue);
            }
        }

        return $customFieldValue;
    }

    /**
     * Remove HTML comments around Yoast and SEOPRESS variables.
     *
     * @param string $customFieldValue
     * @return string
     */
    public function removeSeoPluginTags(string $customFieldValue): string
    {
        if (strpos($customFieldValue, self::YOAST_SEO_CUSTOM_REPLACEMENT_PREFIX) !== false || strpos($customFieldValue, self::SEOPRESS_CUSTOM_REPLACEMENT_PREFIX) !== false) {
            $pattern = '|<[^>]*>|sm';
            preg_match_all($pattern, $customFieldValue, $matches);

            foreach ($matches[0] ?? [] as $match) {
                $replacement = '';

                if (strpos($match, self::YOAST_SEO_CUSTOM_REPLACEMENT_PREFIX) !== false) {
                    $replacement = str_replace(self::YOAST_SEO_CUSTOM_REPLACEMENT_PREFIX, '', $match);
                }
                if (strpos($match, self::SEOPRESS_CUSTOM_REPLACEMENT_PREFIX) !== false) {
                    $replacement = str_replace(self::SEOPRESS_CUSTOM_REPLACEMENT_PREFIX, '', $match);
                }

                $replacement = '%%' . substr($replacement, 1, -2) . '%%';

                $customFieldValue = str_replace($match, $replacement, $customFieldValue);
            }
        }

        return $customFieldValue;
    }
}
