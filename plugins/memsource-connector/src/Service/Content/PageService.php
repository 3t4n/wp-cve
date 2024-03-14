<?php

namespace Memsource\Service\Content;

class PageService extends AbstractPostService
{
    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Pages';
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'page';
    }
}
