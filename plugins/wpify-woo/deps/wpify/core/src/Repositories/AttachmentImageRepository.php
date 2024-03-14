<?php

namespace WpifyWooDeps\Wpify\Core\Repositories;

use WpifyWooDeps\Wpify\Core\Cpt\AttachmentImagePostType;
class AttachmentImageRepository extends AttachmentRepository
{
    public function post_type()
    {
        $post_type = $this->plugin->create_component(AttachmentImagePostType::class);
        $post_type->init();
        return $post_type;
    }
}
