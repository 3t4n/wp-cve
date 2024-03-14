<?php

namespace WpifyWooDeps\Wpify\Core\Repositories;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractPostTypeRepository;
use WpifyWooDeps\Wpify\Core\Cpt\AttachmentPostType;
class AttachmentRepository extends AbstractPostTypeRepository
{
    public function post_type()
    {
        $post_type = $this->plugin->create_component(AttachmentPostType::class);
        $post_type->init();
        return $post_type;
    }
}
