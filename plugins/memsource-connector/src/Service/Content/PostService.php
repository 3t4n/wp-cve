<?php

namespace Memsource\Service\Content;

class PostService extends AbstractPostService
{
    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Posts';
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'post';
    }
}
