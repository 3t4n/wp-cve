<?php

namespace WpifyWooDeps\Wpify\Core\Cpt;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractPostType;
use WpifyWooDeps\Wpify\Core\Models\AttachmentModel;
class AttachmentPostType extends AbstractPostType
{
    protected $register_cpt = \false;
    public function post_type_args() : array
    {
        return array();
    }
    /**
     * @inheritDoc
     */
    public function post_type_name() : string
    {
        return 'attachment';
    }
    /**
     * @inheritDoc
     */
    public function model() : string
    {
        return AttachmentModel::class;
    }
}
