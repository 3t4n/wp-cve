<?php

namespace Memsource\Service\Content;

class ReusableBlockService extends AbstractPostService
{
    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'wp_block';
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Reusable Blocks';
    }
}
