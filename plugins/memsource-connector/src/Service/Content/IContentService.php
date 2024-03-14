<?php

namespace Memsource\Service\Content;

use InvalidArgumentException;

interface IContentService
{
    /**
     * Get a list of items of a given content type.
     *
     * @param array $args
     * @return array
     */
    public function getItems(array $args): array;

    /**
     * Get a single item by its type and id.
     *
     * @param array $args
     * @return array|null
     * @throws InvalidArgumentException
     */
    public function getItem(array $args);

    /**
     * Insert or update a translation.
     *
     * @param array $args
     * @return int Id of a created or updated content
     * @throws InvalidArgumentException
     */
    public function saveTranslation(array $args): int;

    /**
     * Return base WP content type (post or term).
     *
     * @return string
     */
    public function getBaseType(): string;

    /**
     * Get content type passed to TMS.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get visible lable for the content type.
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * If type of content is folder for show its tree of items in TMS.
     *
     * @return bool
     */
    public function isFolder(): bool;
}
