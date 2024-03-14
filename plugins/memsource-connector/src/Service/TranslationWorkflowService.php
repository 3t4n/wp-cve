<?php

namespace Memsource\Service;

use Memsource\Service\TranslationPlugin\ITranslationPlugin;
use ReflectionClass;
use WP_Post;

class TranslationWorkflowService
{
    public const FIELD_NAME = 'field-name';
    public const FIELD_VALUE_LIST = 'field-value-list';
    public const FIELD_VALUE_SUBMIT = 'field-value-submit';
    public const FIELD_VALUE_TRANSLATE_SOURCE = 'field-value-translate-source';
    public const FIELD_VALUE_TRANSLATE_TARGET = 'field-value-translate-target';
    public const FIELD_NAME_TARGET_LANGUAGES = 'field-name-target-languages';

    /** @var OptionsService */
    private $optionsService;

    /** @var array|null */
    private $options = null;

    /** @var bool|null */
    private $customFieldExists = null;

    /** @var ITranslationPlugin */
    private $translationPlugin;

    public function __construct(OptionsService $optionsService, ITranslationPlugin $translationPlugin)
    {
        $this->optionsService = $optionsService;
        $this->translationPlugin = $translationPlugin;
    }

    /**
     * Validate and store translation workflow settings.
     *
     * @param array $params
     */
    public function storeWorkflowSettings(array $params)
    {
        $config = [];

        $fields = (new ReflectionClass(TranslationWorkflowService::class))->getConstants();

        foreach ($params as $key => $value) {
            $sanitizedKey = sanitize_text_field($key);
            $sanitizedValue = sanitize_text_field($value);

            if ($sanitizedKey !== '' && $sanitizedValue !== '' && in_array($sanitizedKey, $fields, true)) {
                $config[$sanitizedKey] = $sanitizedValue;
            }
        }

        $this->optionsService->updateTranslationWorkflow($config);
    }

    /**
     * Get value of given field.
     *
     * @param string $field
     *
     * @return string
     */
    public function getValue(string $field): string
    {
        if ($this->options === null) {
            $this->options = $this->optionsService->getTranslationWorkflow();
        }

        return $this->options[$field] ?? '';
    }

    /**
     * @param int $postId
     *
     * @return bool
     */
    public function isPostListable($postId): bool
    {
        $fieldName = $this->getValue(self::FIELD_NAME);
        $expectedFieldValue = $this->getValue(self::FIELD_VALUE_LIST);

        if ($fieldName === '' || $expectedFieldValue === '' || !$this->customFieldExists($fieldName)) {
            return true;
        }

        $currentFieldValue = get_post_meta($postId, $fieldName, true);

        return $currentFieldValue === $expectedFieldValue;
    }

    /**
     * Check that custom field exists in WP.
     *
     * @param string $customField
     * @param bool $useCache
     *
     * @return bool
     */
    private function customFieldExists(string $customField, bool $useCache = true): bool
    {
        if ($useCache && $this->customFieldExists !== null) {
            return $this->customFieldExists;
        }

        global $wpdb;

        // get total number of posts containing selected custom field
        $result = intval(
            $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = %s", $customField)
            )
        );

        if ($result <= 0) {
            // check that given custom field is defined via ACF plugin
            $result = intval(
                $wpdb->get_var(
                    $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'acf-field' AND post_excerpt = %s", $customField)
                )
            );
        }

        $this->customFieldExists = $result > 0;

        return $this->customFieldExists;
    }

    /**
     * Set post submitted to Memsource.
     *
     * @param WP_Post $post
     */
    public function setPostSubmitted($post)
    {
        $fieldName = $this->getValue(self::FIELD_NAME);

        if ($fieldName !== '') {
            $fieldValue = $this->getValue(self::FIELD_VALUE_SUBMIT);
            update_post_meta($post->ID, $fieldName, $fieldValue);
        }
    }

    /**
     * Set post translated (both source and target).
     *
     * @param int $sourcePostId
     * @param int $targetPostId
     * @param string $language Target language
     */
    public function setPostTranslated($sourcePostId, $targetPostId, $language)
    {
        $fieldName = $this->getValue(self::FIELD_NAME);

        if ($fieldName !== '') {
            // source post
            $fieldValueSource = $this->getValue(self::FIELD_VALUE_TRANSLATE_SOURCE);
            update_post_meta($sourcePostId, $fieldName, $fieldValueSource);

            // target post
            $fieldValueTarget = $this->getValue(self::FIELD_VALUE_TRANSLATE_TARGET);
            $mainSiteId = $this->translationPlugin->switchToSiteByTargetLang($language);
            update_post_meta($targetPostId, $fieldName, $fieldValueTarget);
            $this->translationPlugin->switchToSite($mainSiteId);
        }
    }

    /**
     * Get list of target languages for APC (if available).
     *
     * @param int $postId
     *
     * @return null|array
     */
    public function getTargetLanguages($postId)
    {
        $fieldName = $this->getValue(self::FIELD_NAME_TARGET_LANGUAGES);
        $languages = get_post_meta($postId, $fieldName);

        if (isset($languages['0']) && !empty($languages['0'])) {
            return $languages['0'];
        }

        return null;
    }

    /**
     * Check the availability of the ACF plugin.
     *
     * @return bool
     */
    public function isAcfEnabled(): bool
    {
        global $acf;
        return isset($acf);
    }
}
