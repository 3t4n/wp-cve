<?php

namespace WpifyWooDeps\Wpify\Core\Cpt;

use WpifyWooDeps\Wpify\Core\Models\AttachmentImageModel;
class AttachmentImagePostType extends AttachmentPostType
{
    /**
     * @inheritDoc
     */
    public function model() : string
    {
        return AttachmentImageModel::class;
    }
}
