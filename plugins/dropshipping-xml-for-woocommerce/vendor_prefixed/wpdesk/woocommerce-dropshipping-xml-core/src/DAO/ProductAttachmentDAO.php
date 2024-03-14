<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException;
use WP_Post;
/**
 * Class ProductAttachmentDAO
 *
 * @package WPDesk\Library\DropshippingXmlCore\DAO
 */
class ProductAttachmentDAO
{
    const ATTACHMENT_META_URL = 'url';
    public function get_attachment_id_by_url(string $url) : \WP_Post
    {
        $attachments = \get_posts(['posts_per_page' => 1, 'post_type' => 'attachment', 'meta_query' => [['key' => self::ATTACHMENT_META_URL, 'value' => $url, 'compare' => '=']]]);
        if (empty($attachments)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException('Error, attachment not found');
        }
        $attachment = \reset($attachments);
        return $attachment;
    }
}
