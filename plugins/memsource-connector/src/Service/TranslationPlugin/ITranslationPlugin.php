<?php

namespace Memsource\Service\TranslationPlugin;

use Exception;

interface ITranslationPlugin
{
    /**
     * Get readable name of the plugin.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Is the plugin active?
     *
     * @return bool
     */
    public function isPluginActive(): bool;

    /**
     * Does the plugin support admin menu on each site?
     *
     * @return bool
     */
    public function supportsAdminMenu(): bool;

    /**
     * Does the plugin support menu in network administration?
     *
     * @return bool
     */
    public function supportsNetworkAdminMenu(): bool;

    /**
     * Get the source language code.
     *
     * @return string For example: 'en'
     */
    public function getSourceLanguage(): string;

    /**
     * Get an array of target language codes.
     *
     * @return string[] For example: ['de', 'cs']
     */
    public function getTargetLanguages(): array;

    public function getActiveLanguages(): array;

    /**
     * Return all the active languages (source and target) with additional details.
     *
     * @return array For example:
     *     [
     *         ['id' => 1, 'code' => 'en', 'native_name' => 'English'],
     *         ...
     *     ]
     */
    public function getActiveLanguagesDetails(): array;

    /**
     * Insert or update post/page/custom post translation.
     *
     * @param string $wpContentType WordPress content type, for example 'post'
     * @param int $sourceContentId ID of the source content
     * @param string $targetLanguage Target language code, for example 'de'
     * @param array $content Post details to be stored
     *
     * @return int Post ID
     *
     * @throws Exception in case of error
     */
    public function storePostTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, array $content): int;

    /**
     * If possible, assign proper categories to the translation.
     *
     * @param string $taxonomy Taxonomy name, for example 'category' or 'post_tag'
     * @param int[] $sourceTermsIds Categories of a source post
     * @param int $targetPostId Translated post ID
     * @param string $targetLanguage Target language
     *
     * @return mixed
     */
    public function transferPostTerms(string $taxonomy, array $sourceTermsIds, int $targetPostId, string $targetLanguage);

    /**
     * Copy post thumbnail (featured image) from source post to the target.
     *
     * @param int $sourcePostId
     * @param int $targetPostId
     */
    public function copyPostThumbnail(int $sourcePostId, int $targetPostId, string $targetLanguage);

    /**
     * Insert or update term/category/custom taxonomy translation.
     *
     * @param string $wpContentType WordPress content type, for example 'category'
     * @param int $sourceContentId ID of the source content
     * @param string $targetLanguage Target language code, for example 'de'
     * @param string $title Title
     * @param string $description Description
     * @param int $parentTranslationId ID of the parent translation
     *
     * @return int Term ID
     *
     * @throws Exception in case of error
     */
    public function storeTermTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, string $title, string $description, int $parentTranslationId): int;

    /**
     * @param string $wpContentType WordPress content type
     * @param int $sourceContentId ID of the source content
     * @param string $targetLanguage Target language code
     *
     * @return int|null
     *
     * @throws Exception in case of error
     */
    public function getTermTranslationId(string $wpContentType, int $sourceContentId, string $targetLanguage);

    /**
     * Switch to a site (blog) asociated with given language.
     *
     * @param string $language Language code
     *
     * @return int Previous site ID
     */
    public function switchToSiteByTargetLang(string $language): int;

    /**
     * Switch to another site (blog).
     *
     * @param int $siteId Site (blog) ID
     *
     * @return int Previous site ID
     */
    public function switchToSite(int $siteId): int;

    /**
     * Convert internal links to proper language versions. For example '/?p=1' will be converted to '/?p=2&lang=de'
     *
     * @param string $content Post content
     *
     * @return string Updated post content
     */
    public function convertInternalLinks(string $content, string $targetLanguage): string;
}
