<?php

namespace WpifyWooDeps\Wpify\Core\Models;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractPostTypeModel;
/**
 * @package Wpify\Core
 */
class AttachmentModel extends AbstractPostTypeModel
{
    public function get_type()
    {
    }
    public function get_url()
    {
        return wp_get_attachment_url($this->get_id());
    }
}
