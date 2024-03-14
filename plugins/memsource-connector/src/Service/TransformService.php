<?php

namespace Memsource\Service;

use Memsource\Dto\MetaKeyDto;
use Memsource\Parser\ExcerptParser;
use Memsource\Parser\ParserResult;
use Memsource\Parser\ShortcodeParser;
use Memsource\Parser\BlockParser;
use Memsource\Service\CustomFields\CustomFieldsDecodeService;
use Memsource\Service\CustomFields\CustomFieldsEncodeService;
use Memsource\Service\ExternalPlugin\ElementorPlugin;
use Memsource\Utils\AuthUtils;
use Memsource\Utils\DatabaseUtils;
use Memsource\Utils\LogUtils;
use WP_Post;
use WP_Term;

class TransformService
{
    public const STORED_CONTENT_DELIMITER = '---MEMSOURCE_STORED_CONTENT_DELIMITER---';
    public const RESULT_POST_CONTENT = 'POST_CONTENT';
    public const RESULT_CUSTOM_FIELDS = 'CUSTOM_FIELDS';
    public const RESULT_TRANSFORMED_CONTENT = 'TRANSFORMED_CONTENT';
    public const RESULT_TRANSFORMED_SOURCE_ID = 'TRANSFORMED_SOURCE_ID';
    public const RESULT_EXCERPT = 'EXCERPT';

    /** @var ShortcodeService */
    private $shortcodeService;

    /** @var ShortCodeParser */
    private $shortcodeParser;

    /** @var BlockParser */
    private $blockParser;

    /** @var ElementorPlugin */
    private $elementorPlugin;

    /** @var CustomFieldsDecodeService */
    private $customFieldsDecodeService;

    /** @var CustomFieldsEncodeService */
    private $customFieldsEncodeService;

    /** @var AuthUtils */
    private $authUtils;

    /** @var PlaceholderService */
    private $placeholderService;

    /** @var ExcerptParser */
    private $excerptParser;

    public function __construct(
        ShortcodeService $shortcodeService,
        ShortcodeParser $shortcodeParser,
        BlockParser $blockParser,
        ElementorPlugin $elementorPlugin,
        CustomFieldsDecodeService $customFieldsDecodeService,
        CustomFieldsEncodeService $customFieldsEncodeService,
        AuthUtils $authUtils,
        PlaceholderService $placeholderService,
        ExcerptParser $excerptParser
    ) {
        $this->shortcodeService = $shortcodeService;
        $this->shortcodeParser = $shortcodeParser;
        $this->blockParser = $blockParser;
        $this->elementorPlugin = $elementorPlugin;
        $this->customFieldsDecodeService = $customFieldsDecodeService;
        $this->customFieldsEncodeService = $customFieldsEncodeService;
        $this->authUtils = $authUtils;
        $this->placeholderService = $placeholderService;
        $this->excerptParser = $excerptParser;
    }

    /**
     * @param WP_Post $post
     * @return array
     */
    public function encodePost(WP_Post $post, int $originalPostId): array
    {
        $postId = $post->ID;
        $content = $post->post_content;

        LogUtils::debug("Original content:\n" . LogUtils::toStr($content) . "\n");

        $contentResult = new ParserResult;
        $transformedSourceId = null;

        $content = $this->excerptParser->encode($content, $post);

        if (!$this->elementorPlugin->isPostCreatedByElementorPlugin($postId)) {
            // convert blocks and shortcodes to html tags
            $contentResult = $this->shortcodeParser->encode(
                $this->blockParser->encode($content),
                $this->shortcodeService->getShortcodes()
            );
        }

        // convert custom fields and append them to the result
        $customFieldsResult = $this->customFieldsEncodeService->encodeCustomFields(MetaKeyDto::TYPE_POST, $originalPostId);

        // join partial results - post content
        $transformedContent = $contentResult->getTransformationResult() . $customFieldsResult->getTransformationResult();

        LogUtils::debug("Transformed content:\n" . LogUtils::toStr($transformedContent) . "\n");

        // convert HTML comments into placeholders
        $htmlComments = [];
        $this->placeholderService->convertHtmlCommentsToPlaceholders($transformedContent, $htmlComments);

        // restore HTML comments found in gutenberg block values
        $transformedContent = $this->blockParser->stripBlockComments($transformedContent);

        // prepare temporary content to be stored
        $preparedToStore = $contentResult->getPreparedToStore();

        // append placeholders to the stored content
        $placeholders = $contentResult->getPlaceholders() + $customFieldsResult->getPlaceholders() + $htmlComments;

        if (!empty($placeholders)) {
            $preparedToStore .= self::STORED_CONTENT_DELIMITER;
            $preparedToStore .= json_encode($placeholders);
        }

        // store transformed content
        if (!empty($preparedToStore)) {
            $transformedSourceId = $this->authUtils->generateRandomToken();
            $this->storeContent($transformedSourceId, $originalPostId, $preparedToStore);
        }

        return [
            self::RESULT_TRANSFORMED_CONTENT => $transformedContent,
            self::RESULT_TRANSFORMED_SOURCE_ID => $transformedSourceId,
        ];
    }

    public function decodePost($content, $transformedSourceId): array
    {
        // find the text in the database
        $storedContent = $this->getStoredContent($transformedSourceId);
        LogUtils::debug("Transformed content found:\n" . LogUtils::toStr($storedContent) . "\n");

        // split post content and placeholders
        $exploded = explode(self::STORED_CONTENT_DELIMITER, $storedContent);
        $storedContent = $exploded[0];
        $storedPlaceholders = $exploded[1] ?? null;

        //  get excerpt from content
        $excerpt = $this->excerptParser->decode($content);
        LogUtils::debug("Excerpt decode result:\n" . LogUtils::toStr($excerpt) . "\n");

        // process stored placeholders
        $content = $this->placeholderService->restorePlaceholders($storedPlaceholders, $content);

        // extract custom fields from content
        $customFields = $this->customFieldsDecodeService->getCustomFieldsFromString($content);
        $content = $this->customFieldsDecodeService->cleanStringFromCustomFields($content);

        // convert html tags to wp shortcodes
        $result = $this->shortcodeParser->decode($content, $storedContent);
        LogUtils::debug("HTML to shortcodes result:\n" . LogUtils::toStr($result) . "\n");

        // convert html content to blocks
        $result = $this->blockParser->decode($content, $result);
        LogUtils::debug("HTML to blocks result:\n" . LogUtils::toStr($result) . "\n");

        // restore HTML comments found in gutenberg block values
        $result = $this->blockParser->stripBlockComments($result);

        return [
            self::RESULT_POST_CONTENT => $result,
            self::RESULT_CUSTOM_FIELDS => $customFields,
            self::RESULT_EXCERPT => $excerpt,
        ];
    }

    /**
     * @param WP_Post $post
     * @return array
     */
    public function encodeTerm(WP_Term $term): array
    {
        $preparedToStore = '';
        $transformedSourceId = null;

        // convert custom fields and append them to the result
        $customFieldsResult = $this->customFieldsEncodeService->encodeCustomFields(MetaKeyDto::TYPE_TERM, $term->term_id);
        $placeholders = $customFieldsResult->getPlaceholders();

        if (!empty($placeholders)) {
            $preparedToStore .= self::STORED_CONTENT_DELIMITER;
            $preparedToStore .= json_encode($placeholders);
        }

        // store transformed content
        if (!empty($preparedToStore)) {
            $transformedSourceId = $this->authUtils->generateRandomToken();
            $this->storeContent($transformedSourceId, $term->term_id, $preparedToStore);
        }

        // join partial results - post content
        $transformedContent = $term->description . $customFieldsResult->getTransformationResult();

        return [
            self::RESULT_TRANSFORMED_CONTENT => $transformedContent,
            self::RESULT_TRANSFORMED_SOURCE_ID => $transformedSourceId,
        ];
    }

    public function decodeTerm($content, $transformedSourceId): array
    {
        // find the text in the database
        $storedContent = $this->getStoredContent($transformedSourceId);
        LogUtils::debug("Transformed content found:\n" . LogUtils::toStr($storedContent) . "\n");

        // split term content and placeholders
        $exploded = explode(self::STORED_CONTENT_DELIMITER, $storedContent);
        $storedPlaceholders = $exploded[1] ?? null;

        // process stored placeholders
        $content = $this->placeholderService->restorePlaceholders($storedPlaceholders, $content);

        // extract custom fields from content
        $customFields = $this->customFieldsDecodeService->getCustomFieldsFromString($content);
        $content = $this->customFieldsDecodeService->cleanStringFromCustomFields($content);

        LogUtils::debug("decodeTerm result:\n" . LogUtils::toStr($content) . "\n");

        return [
            self::RESULT_POST_CONTENT => $content,
            self::RESULT_CUSTOM_FIELDS => $customFields,
        ];
    }

    /**
     * Store temporary content into DB.
     *
     * @param $transformedSourceId
     * @param $originalPostId
     * @param $content
     */
    private function storeContent($transformedSourceId, $originalPostId, $content)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSFORMED_CONTENT;
        $wpdb->insert($tableName, [
            'uuid' => $transformedSourceId,
            'post_id' => $originalPostId,
            'content' => $content,
        ]);
    }

    /**
     * Find already stored temporary content.
     *
     * @param $transformedSourceId
     *
     * @return string|null
     */
    private function getStoredContent($transformedSourceId)
    {
        global $wpdb;
        $table = $wpdb->prefix . DatabaseUtils::TABLE_TRANSFORMED_CONTENT;
        return $wpdb->get_var(
            $wpdb->prepare(
                "select `content` from ${table} where `uuid` = %s",
                $transformedSourceId
            )
        );
    }
}
