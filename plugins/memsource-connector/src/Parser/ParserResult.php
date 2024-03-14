<?php

namespace Memsource\Parser;

class ParserResult
{
    /** @var string */
    private $transformationResult;

    /** @var array */
    private $placeholders;

    /** @var string */
    private $preparedToStore;

    public function __construct(string $transformationResult = '', array $placeholders = [], string $preparedToStore = '')
    {
        $this->transformationResult = $transformationResult;
        $this->placeholders = $placeholders;
        $this->preparedToStore = $preparedToStore;
    }

    public function getTransformationResult(): string
    {
        return $this->transformationResult;
    }

    public function getPlaceholders(): array
    {
        return $this->placeholders;
    }

    public function getPreparedToStore(): string
    {
        return $this->preparedToStore;
    }
}
