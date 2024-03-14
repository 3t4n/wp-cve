<?php

namespace Memsource\Service\TranslationPlugin;

use Exception;
use Memsource\Utils\LogUtils;
use SitePress;
use WP_Error;

class WPMLPlugin implements ITranslationPlugin
{
    public function getName(): string
    {
        return 'WPML';
    }

    public function isPluginActive(): bool
    {
        return class_exists('SitePress');
    }

    public function supportsAdminMenu(): bool
    {
        return true;
    }

    public function supportsNetworkAdminMenu(): bool
    {
        return false;
    }

    public function getSourceLanguage(): string
    {
        return wpml_get_default_language();
    }

    public function getTargetLanguages(): array
    {
        $sourceLanguage = $this->getSourceLanguage();
        $result = [];

        foreach ($this->getActiveLanguagesDetails() as $language) {
            if ($language['code'] !== $sourceLanguage) {
                $result[] = $language['code'];
            }
        }

        return $result;
    }

    public function getActiveLanguages(): array
    {
        return [$this->getSourceLanguage()] + $this->getTargetLanguages();
    }

    public function getActiveLanguagesDetails(): array
    {
        return $this->sitepress()->get_languages(false, true) ?: [];
    }

    public function storePostTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, array $content): int
    {
        $pluginContentType = $this->convertPostTypeToWpmlType($wpContentType);
        $translationId = $this->getTranslationId($pluginContentType, $sourceContentId, $targetLanguage);

        // save
        if ($translationId === null) {
            $trid = $this->sitepress()->get_element_trid($sourceContentId, $pluginContentType);

            if (!$trid) {
                throw new Exception("Call get_element_trid($pluginContentType, $sourceContentId) failed.");
            }

            $targetPostId = wp_insert_post($content);

            if ($targetPostId instanceof WP_Error) {
                throw new Exception('Call wp_insert_post() failed: ' . $targetPostId->get_error_message());
            } elseif ($targetPostId === 0) {
                throw new Exception('Call wp_insert_post() returned 0.');
            }

            $result = $this->sitepress()->set_element_language_details($targetPostId, $pluginContentType, $trid, $targetLanguage);

            if (!$result) {
                throw new Exception(
                    "Call set_element_language_details($targetPostId, $pluginContentType, $trid, $targetLanguage) failed: " .
                    LogUtils::toStr($result)
                );
            }

            if ($this->getTranslationId($pluginContentType, $sourceContentId, $targetLanguage) === null) {
                LogUtils::debug("Translated content wasn't properly linked to the source, going to remove orphans (" .
                                "pluginContentType: $pluginContentType, sourceContentId: $sourceContentId, targetLanguage: $targetLanguage)");
                $this->sitepress()->delete_orphan_element($sourceContentId, $pluginContentType, $targetLanguage);
                $result = $this->sitepress()->set_element_language_details($targetPostId, $pluginContentType, $trid, $targetLanguage);
                if (!$result) {
                    throw new Exception(
                        "Second call set_element_language_details($targetPostId, $pluginContentType, $trid, $targetLanguage) failed: " .
                        LogUtils::toStr($result)
                    );
                }
            }
        } else {
            $content['ID'] = $translationId;
            $targetPostId = wp_update_post($content);

            if ($targetPostId instanceof WP_Error) {
                throw new Exception('Call wp_update_post() failed: ' . $targetPostId->get_error_message());
            } elseif ($targetPostId === 0) {
                throw new Exception('Call wp_update_post() returned 0.');
            }
        }

        return $targetPostId;
    }

    public function transferPostTerms(string $taxonomy, array $sourceTermsIds, int $targetPostId, string $targetLanguage)
    {
        $defaultSourceCategory = null;
        if ($taxonomy === 'category') {
            $defaultSourceCategory = get_option('default_category');
        }
        $pluginContentType = $this->convertTermTypeToWpmlType($taxonomy);
        $targetTerms = [];

        foreach ($sourceTermsIds as $sourceTermId) {
            $translationId = $this->getTranslationId($pluginContentType, $sourceTermId, $targetLanguage);

            if ($translationId !== null) {
                $targetTerms[] = $translationId;
            }
        }

        if (!empty($targetTerms)) {
            wp_set_post_terms($targetPostId, $targetTerms, $taxonomy);
        } elseif (is_numeric($defaultSourceCategory)) {
            $translationId = $this->getTranslationId($pluginContentType, $defaultSourceCategory, $targetLanguage);

            if ($translationId !== null) {
                wp_set_post_categories($targetPostId, [$translationId]);
            }
        }
    }

    public function copyPostThumbnail(int $sourcePostId, int $targetPostId, string $targetLanguage)
    {
        $postThumbnailId = get_post_thumbnail_id($sourcePostId);

        if ($postThumbnailId !== false) {
            set_post_thumbnail($targetPostId, $postThumbnailId);
        } elseif (get_post_thumbnail_id($targetPostId) !== false) {
            delete_post_thumbnail($targetPostId);
        }
    }

    public function convertInternalLinks(string $content, string $targetLanguage): string
    {
        preg_match_all('/(?:href=["\']?([^"\'>]+)["\']?)|(?:"url":["\']?([^"\'>]+)["\']?)/sm', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $regexMatch = $match[0];
            $url = empty($match[2]) ? $match[1] : $match[2];

            $sourcePostId = url_to_postid($url);

            if ($sourcePostId > 0) {
                $sourcePost = get_post($sourcePostId);

                if ($sourcePost === null || !isset($sourcePost->post_type)) {
                    continue;
                }

                $contentType = $this->convertPostTypeToWpmlType($sourcePost->post_type);
                $targetPostId = $this->getTranslationId($contentType, $sourcePostId, $targetLanguage);

                if (is_numeric($targetPostId) && $targetPostId > 0) {
                    $replacement = str_replace($url, get_permalink($targetPostId), $regexMatch);
                    $content = str_replace($regexMatch, $replacement, $content);
                }
            }
        }

        return $content;
    }

    public function storeTermTranslation(
        string $wpContentType,
        int $sourceContentId,
        string $targetLanguage,
        string $title,
        string $description,
        int $parentTranslationId
    ): int {

        $pluginContentType = $this->convertTermTypeToWpmlType($wpContentType);
        $translationId = $this->getTranslationId($pluginContentType, $sourceContentId, $targetLanguage);

        // save
        if ($translationId === null) {
            $trid = $this->sitepress()->get_element_trid($sourceContentId, $pluginContentType);

            if (!$trid) {
                throw new Exception("Call get_element_trid($sourceContentId, $pluginContentType) failed.");
            }

            $response = wp_insert_term($title, $wpContentType, [
                'description' => $description,
                'slug' => sanitize_title("$title-$targetLanguage"),
                'parent' => $parentTranslationId,
            ]);

            if ($response instanceof WP_Error) {
                throw new Exception(
                    "Call wp_insert_term($title, $wpContentType, [... $parentTranslationId]) failed: " .
                    $response->get_error_message()
                );
            }

            $targetTermId = $response['term_id'];

            $result = $this->sitepress()->set_element_language_details($targetTermId, $pluginContentType, $trid, $targetLanguage);

            if (!$result) {
                throw new Exception(
                    "Call set_element_language_details($targetTermId, $pluginContentType, $trid, $targetLanguage) failed: " .
                    LogUtils::toStr($result)
                );
            }
        } else {
            remove_filter('get_term', [$this->sitepress(), 'get_term_adjust_id'], 1);

            $defaultLang = apply_filters('wpml_current_language', null);
            do_action('wpml_switch_language', $targetLanguage);

            $response = wp_update_term($translationId, $wpContentType, [
                'name' => $title,
                'description' => $description,
            ]);

            do_action('wpml_switch_language', $defaultLang);

            if ($response instanceof WP_Error) {
                throw new Exception(
                    "Call wp_update_term($translationId, $wpContentType, [$title...]) failed: " .
                    $response->get_error_message()
                );
            }

            $targetTermId = $response['term_id'];
        }

        return $targetTermId;
    }

    public function getTermTranslationId(string $wpContentType, int $sourceContentId, string $targetLanguage)
    {
        return $this->getTranslationId(
            $this->convertTermTypeToWpmlType($wpContentType),
            $sourceContentId,
            $targetLanguage
        );
    }

    private function getTranslationId(string $pluginContentType, int $sourceContentId, string $targetLanguage)
    {
        $translation = wpml_get_content_translation($pluginContentType, $sourceContentId, $targetLanguage);

        if (in_array($translation, [WPML_API_CONTENT_NOT_FOUND, WPML_API_TRANSLATION_NOT_FOUND], true)) {
            return null;
        }

        if (is_int($translation)) {
            throw new Exception(
                "Call wpml_get_content_translation($pluginContentType, $sourceContentId, $targetLanguage) returned code $translation."
            );
        }

        if (!isset($translation[$targetLanguage])) {
            return null;
        }

        return (int) $translation[$targetLanguage];
    }

    public function switchToSiteByTargetLang(string $language): int
    {
        return get_current_blog_id();
    }

    public function switchToSite(int $siteId): int
    {
        return get_current_blog_id();
    }

    private function convertTermTypeToWpmlType(string $type): string
    {
        return $type === 'tag' ? 'tax_post_tag' : "tax_$type";
    }

    private function convertPostTypeToWpmlType(string $type): string
    {
        return "post_$type";
    }

    private function sitepress(): SitePress
    {
        global $sitepress;

        return $sitepress;
    }
}
