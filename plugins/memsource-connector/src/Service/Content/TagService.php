<?php

namespace Memsource\Service\Content;

class TagService extends AbstractTermService
{
    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Tags';
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function getWpType(): string
    {
        return 'post_tag';
    }
}
