<?php

namespace Memsource\Service\Content;

use Exception;
use Memsource\Dto\MetaKeyDto;
use Memsource\Exception\NotFoundException;
use Memsource\Service\TransformService;
use Memsource\Utils\ArrayUtils;
use Memsource\Utils\StringUtils;
use WP_Term;

abstract class AbstractTermService extends AbstractContentService implements IContentService
{
    /**
     * @inheritdoc
     */
    public function getBaseType(): string
    {
        return MetaKeyDto::TYPE_TERM;
    }

    /**
     * @inheritdoc
     */
    public function isFolder(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getItems(array $args): array
    {
        $terms = get_terms([
            'taxonomy' => $this->getWpType(),
            'order' => 'ASC',
            'hide_empty' => false,
        ]);

        $result = [];

        foreach ($terms ?: [] as $term) {
            $result[] = $this->createApiResponse($term);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getItem(array $args)
    {
        ArrayUtils::checkKeyExists($args, ['id']);
        $term = $this->getTerm($args['id']);
        $result = $this->transformService->encodeTerm($term);
        $response = $this->createApiResponse($term);
        $response['content'] = $this->toMemsourceHTML($term->term_id, $result[TransformService::RESULT_TRANSFORMED_CONTENT]);
        $response['transformedSourceId'] = $result[TransformService::RESULT_TRANSFORMED_SOURCE_ID];
        return $response;
    }

    /**
     * Create an API response.
     */
    protected function createApiResponse(WP_Term $term): array
    {
        return [
            'id' => $term->term_id,
            'revision_id' => null,
            'date' => null,
            'date_gmt' => null,
            'modified' => null,
            'modified_gmt' => null,
            'password' => null,
            'slug' => null,
            'status' => null,
            'type' => $this->getType(),
            'link' => null,
            'title' => $term->name,
            'size' => StringUtils::size($term->name) + StringUtils::size($term->description),
        ];
    }

    /**
     * Create a format of item for Memsource converter.
     *
     * @param $id int
     * @param $description string
     * @return string
     */
    protected function toMemsourceHTML($id, $description)
    {
        return sprintf('<div id="%d"><div id="description">%s</div></div>', $id, $description);
    }

    /**
     * @inheritdoc
     */
    public function saveTranslation(array $args): int
    {
        $this->checkArgsBeforeSaveTranslation($args);
        ArrayUtils::checkKeyExists($args, ['id', 'title', 'content']);
        $this->getTerm($args['id']); // check if term exists
        $args['lang'] = strtolower($args['lang']);

        $decodeResult = $this->transformService->decodeTerm(
            $this->parseDescriptionFromMemsourceHTML($args['content']),
            $args['transformedSourceId'] ?? null
        );

        $targetTermId = $this->translationPlugin->storeTermTranslation(
            $this->getWpType(),
            $args['id'],
            $args['lang'],
            $args['title'],
            $decodeResult[TransformService::RESULT_POST_CONTENT],
            $this->getParentTranslationId($args['id'], $args['lang'])
        );

        if ($decodeResult[TransformService::RESULT_CUSTOM_FIELDS]) {
            $this->customFieldsDecodeService->saveTermCustomFields($targetTermId, $decodeResult[TransformService::RESULT_CUSTOM_FIELDS]);
        }

        return $targetTermId;
    }

    /**
     * Get type of content used by WP internally.
     *
     * @return string
     */
    protected function getWpType(): string
    {
        return $this->getType();
    }

    /**
     * Get data from special Memsource HTML format.
     *
     * @param string $html
     * @return string
     * @throws Exception
     */
    protected function parseDescriptionFromMemsourceHTML(string $html): string
    {
        $pattern = '/<div id="(\d+)"><div id="description">(|.+?)<\/div><\/div>/sU';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        if (!$matches || !isset($matches[0][2])) {
            throw new Exception('No result has been matched.');
        }

        return $matches[0][2];
    }

    /**
     * Get translation id of a parent term.
     *
     * @param int $termId
     * @param string $lang
     *
     * @return int
     */
    protected function getParentTranslationId(int $termId, string $lang): int
    {
        $term = $this->getTerm($termId);

        if ($term->parent) {
            $translationId = $this->translationPlugin->getTermTranslationId($this->getType(), $term->parent, $lang);

            if ($translationId !== null) {
                return $translationId;
            }
        }

        return 0;
    }

    /**
     * Get term by ID.
     *
     * @throws NotFoundException When term not found
     */
    protected function getTerm($id): WP_Term
    {
        $term = get_term($id, $this->getWpType());

        if (!$term instanceof WP_Term) {
            throw new NotFoundException('Term not found.');
        }

        return $term;
    }
}
