<?php

namespace Memsource\Service\Content;

use Memsource\Dao\MetaDao;
use Memsource\Dto\MetaKeyDto;
use Memsource\Exception\NotFoundException;
use Memsource\Service\CustomFields\CustomFieldsDecodeService;
use Memsource\Service\CustomFields\CustomFieldsService;
use Memsource\Service\FilterService;
use Memsource\Service\LanguageService;
use Memsource\Service\OptionsService;
use Memsource\Service\TransformService;
use Memsource\Service\TranslationPlugin\ITranslationPlugin;
use Memsource\Service\TranslationWorkflowService;
use Memsource\Utils\ArrayUtils;
use Memsource\Utils\AuthUtils;
use Memsource\Utils\LogUtils;
use Memsource\Utils\StringUtils;

abstract class AbstractPostService extends AbstractContentService implements IContentService
{
    /** @var OptionsService */
    protected $optionsService;

    /** @var FilterService */
    private $filterService;

    /** @var CustomFieldsService */
    private $customFieldsService;

    /** @var TranslationWorkflowService */
    private $translationWorkflowService;

    /** @var MetaDao */
    private $metaDao;

    public function __construct(
        OptionsService $optionsService,
        TransformService $transformService,
        FilterService $filterService,
        LanguageService $languageService,
        ITranslationPlugin $translationPlugin,
        CustomFieldsService $customFieldsService,
        CustomFieldsDecodeService $customFieldsDecodeService,
        TranslationWorkflowService $translationWorkflowService,
        MetaDao $metaDao
    ) {
        parent::__construct($languageService, $translationPlugin, $transformService, $customFieldsDecodeService);
        $this->filterService = $filterService;
        $this->optionsService = $optionsService;
        $this->customFieldsService = $customFieldsService;
        $this->translationWorkflowService = $translationWorkflowService;
        $this->metaDao = $metaDao;
    }

    /**
     * @inheritdoc
     */
    public function getBaseType(): string
    {
        return MetaKeyDto::TYPE_POST;
    }

    /**
     * @inheritdoc
     */
    public function isFolder(): bool
    {
        return true;
    }

    /**
     * @param $args array
     * @return array
     */
    public function getItems(array $args): array
    {
        // turn off paging to return all posts
        $queryArgs = ['post_type' => $this->getType(), 'nopaging' => true, 'post_status' => $this->optionsService->getListStatuses()];
        $postsQuery = new \WP_Query();
        $this->filterService->addQueryFilters($postsQuery, true);
        $queryResult = $postsQuery->query($queryArgs);
        $posts = [];

        foreach ($queryResult ?: [] as $post) {
            // get last revision
            $originalPostId = $post->ID;
            $post = $this->getLastRevision($post);
            if (
                !empty($post->post_title)
                &&
                (
                    !empty($post->post_content)
                    || $this->customFieldsService->calculateCustomFieldsSize($post->ID) > 0
                    || $this->customFieldsService->calculateCustomFieldsSize($post->post_parent) > 0
                )
                &&
                $this->translationWorkflowService->isPostListable($originalPostId)
            ) {
                $posts[] = $this->getPostJson($post, $originalPostId);
            }
        }
        return $posts;
    }

    /**
     * @param $args array
     * @return array|null
     */
    public function getItem(array $args)
    {
        // get post
        $originalPost = get_post($args['id']);

        if (!$originalPost || $originalPost->post_type !== $args['type']) {
            return null;
        }

        // get last revision
        $post = $this->getLastRevision($originalPost);

        // convert shortcodes and blocks to html
        $result = $this->transformService->encodePost($post, $originalPost->ID);

        // prepare response
        $response = $this->getPostJsonWithOriginalData($post, $originalPost);
        $response['content'] = $result[TransformService::RESULT_TRANSFORMED_CONTENT];
        $response['transformedSourceId'] = $result[TransformService::RESULT_TRANSFORMED_SOURCE_ID];

        // update custom field according to translation workflow config
        $this->translationWorkflowService->setPostSubmitted($post);

        return $response;
    }

    public function saveTranslation(array $args): int
    {
        $this->checkArgsBeforeSaveTranslation($args);
        ArrayUtils::checkKeyExists($args, ['type', 'id', 'lang', 'title', 'content']);

        $postStatus = $this->optionsService->getInsertStatus();
        $postType = $args['type'];
        $sourcePostId = $args['id'];
        $language = $args['lang'];
        $title = $args['title'];
        $content = $args['content'];
        $transformedSourceId = $args['transformedSourceId'] ?? null; // send this optional parameter from the connector (would be extracted from <title id="transformedSourceId">

        $data = $this->addOrUpdateTranslation($postType, $postStatus, $sourcePostId, $language, $title, $content, $transformedSourceId);

        if (isset($args['raw']) && ($args['raw'] === '1')) {
            $customFields = $this->metaDao->findMetaKeysByType('post', $data['translation_id']);
            echo "=> translation id: " . $data['translation_id'] . "\n" .
                 "=> title: " . $data['title'] . "\n" .
                 "=> content:\n" . $data['content'] . "\n" .
                 "=> custom fields:\n" . print_r($customFields, true) .
                 "\n\n-------------\n\n";
        }

        return $data['id'];
    }

    public function getPost($originalPostId)
    {
        $originalPost = get_post($originalPostId);
        return $this->getLastRevision($originalPost);
    }

    public function getLastRevision($post)
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'revision' AND post_status = 'inherit' ORDER BY ID desc LIMIT 1",
            $post->ID
        );
        $result = $wpdb->get_var($query);

        if ($result !== null) {
            return get_post($result);
        }

        return $post;
    }

    private function addOrUpdateTranslation($postType, $postStatus, $sourcePostId, $language, $title, $content, $transformedSourceId = 0): array
    {
        // if the $language does not exist, discard the translation
        if (!$this->languageService->isValidTargetLanguage($language)) {
            throw new NotFoundException(sprintf('Target language \'%s\' not found.', $language));
        }

        // get source content
        $sourcePost = get_post($sourcePostId);
        if (!$sourcePost) {
            throw new NotFoundException(sprintf('Content with id \'%s\' of type \'%s\' not found.', $sourcePostId, $postType));
        }

        // decode shortcodes and blocks in a post content
        $decodeResult = $this->transformService->decodePost($content, $transformedSourceId);
        $content = $decodeResult[TransformService::RESULT_POST_CONTENT];

        // convert internal links
        if ($this->optionsService->isUrlRewriteEnabled()) {
            $content = $this->translationPlugin->convertInternalLinks($content, $language);
        }

        // prevent wp_unslash() called during wp_insert_post()/wp_update_post()
        $content = str_replace('\\', '\\\\', $content);

        // prepare target post
        $postArgs = [
            'post_author' => $sourcePost->post_author,
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => $postStatus,
            'post_type' => $postType,
        ];
        if ($sourcePost->page_template) {
            $postArgs['page_template'] = $sourcePost->page_template;
        }

        // bypass selected filters used during wp_insert_post() and wp_update_post()
        add_filter('wp_kses_allowed_html', [$this, 'allowStyleTag'], 10, 2); // HTML tag '<style>'
        remove_filter('content_save_pre', 'wpb_remove_custom_html'); // WpBakery shortcodes [vc_raw_html] and [vc_raw_js]

        // switch to admin user so that wp_insert_post() and wp_update_post() behaves as executed by the administrator
        wp_set_current_user($this->optionsService->getAdminUser());

        // create or update target post
        $targetPostId = $this->translationPlugin->storePostTranslation($postType, $sourcePostId, $language, $postArgs);

        // do not use editors provided by 'WPML Translation Management' plugin
        update_post_meta($sourcePostId, '_wpml_post_translation_editor_native', 'yes');

        // transfer all terms from source to target
        $this->transferTermsFromSourceToTarget($sourcePostId, $targetPostId, $language);

        // copy featured image to target
        $this->translationPlugin->copyPostThumbnail($sourcePostId, $targetPostId, $language);

        // switch to target WP site
        $mainSiteId = $this->translationPlugin->switchToSiteByTargetLang($language);

        // copy permalink from source to target
        $this->copyPermalinkIfEnabled($sourcePostId, $sourcePost->post_name, $targetPostId);

        // prepare response (translated post)
        $targetPost = $this->getPost($targetPostId);
        LogUtils::debug("Stored target post:\n" . LogUtils::toStr($targetPost));
        $json = $this->getPostJson($targetPost, $sourcePostId, true);
        $json['translation_id'] = $targetPostId;

        if ($decodeResult[TransformService::RESULT_CUSTOM_FIELDS]) {
            $this->customFieldsDecodeService->savePostCustomFields($targetPostId, $decodeResult[TransformService::RESULT_CUSTOM_FIELDS]);
        }

        // switch back to the main WP site
        $this->translationPlugin->switchToSite($mainSiteId);

        // update post according to translation workflow configuration
        $this->translationWorkflowService->setPostTranslated($sourcePostId, $targetPostId, $language);

        if ($decodeResult[TransformService::RESULT_EXCERPT]) {
            LogUtils::debug("Saving excerpt:\n" . LogUtils::toStr($decodeResult[TransformService::RESULT_EXCERPT]) . "\n");
            $thePost = [
                'ID' => $targetPostId,
                'post_excerpt' => $decodeResult[TransformService::RESULT_EXCERPT],
            ];
            wp_update_post($thePost);
        }

        return $json;
    }

    private function copyPermalinkIfEnabled($sourcePostId, $sourcePostName, $targetPostId) {
        if (!$this->optionsService->isCopyPermalinkEnabled()) {
            return;
        }

        $permalink = $sourcePostName;

        if (empty($permalink)) {
            $permalink = get_sample_permalink($sourcePostId)[1] ?? '';
        }

        if (!empty($permalink)) {
            wp_update_post(['ID' => $targetPostId, 'post_name' => $permalink]);
            LogUtils::debug("Updated permalink: " . LogUtils::toStr($permalink));
        }
    }

    /**
     * By default, during wp_insert_post() and wp_update_post() potentially dangerous code is filtered out. However,
     * using WP editors, user can store <style></style> tags directly into post. Then, when using Memsource to translate
     * the post, these tags would be filtered out.
     *
     * In this function we explicitly allow to store <style></style> tags in translations.
     *
     * @param array $allowed List of allowed HTML tags.
     * @param string $context
     *
     * @return array Modified list of allowed HTML tags.
     */
    public function allowStyleTag($allowed, $context)
    {
        if ($context !== 'post') {
            return $allowed;
        }

        $allowed['style'] = true;

        return $allowed;
    }

    public function getPostJson($post, $originalPostId, $addContent = false): array
    {
        $json = [
            'id' => $originalPostId,
            'revision_id' => $post->ID,
            'date' => $post->post_date,
            'date_gmt' => $post->post_date_gmt,
            'modified' => $post->post_modified,
            'modified_gmt' => $post->post_modified_gmt,
            'password' => $post->post_password,
            'slug' => $post->post_name,
            'status' => $post->post_status,
            'type' => $post->post_type,
            'link' => $this->getPreviewPostLink($originalPostId),
            'title' => $post->post_title,
            'size' => $this->calculatePostSize($post),
            'targetLangs' => $this->translationWorkflowService->getTargetLanguages($post->ID),
        ];

        if ($addContent) {
            $json['content'] = $post->post_content;
        }

        return $json;
    }

    private function getPreviewPostLink($originalPostId)
    {
        if (get_post_status($originalPostId) === 'publish') {
            return get_permalink($originalPostId);
        }

        return get_preview_post_link($originalPostId, ['token' => AuthUtils::getTokenFromRequest()]);
    }

    /**
     * Calculate post sice incl. meta/custom fields.
     *
     * @param \WP_Post $post
     * @return int
     */
    private function calculatePostSize($post): int
    {
        return StringUtils::size($post->post_content) + $this->customFieldsService->calculateCustomFieldsSize($post->ID) + $this->customFieldsService->calculateCustomFieldsSize($post->post_parent);
    }

    private function getPostJsonWithOriginalData($post, $originalPost): array
    {
        $json = $this->getPostJson($post, $originalPost->ID, true);

        $json['originalContent'] = $post->post_content;
        $json['meta'] = get_metadata($originalPost->post_type, $originalPost->ID);

        return $json;
    }

    /**
     * Go through all taxonomies and add already translated terms to the translated post.
     *
     * @param int $sourcePostId Source post ID
     * @param int $targetPostId Target post ID
     * @param string $language Translation language
     *
     * @return void
     */
    private function transferTermsFromSourceToTarget(int $sourcePostId, int $targetPostId, string $language)
    {
        foreach (get_taxonomies() as $taxonomy) {
            $sourceTerms = wp_get_post_terms($sourcePostId, $taxonomy, ['fields' => 'ids']);
            if (is_array($sourceTerms)) {
                $this->translationPlugin->transferPostTerms($taxonomy, $sourceTerms, $targetPostId, $language);
            }
        }
    }
}
