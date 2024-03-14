<?php

namespace Memsource\Service\Content;

class CategoryService extends AbstractTermService
{
    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Categories';
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'category';
    }
}
