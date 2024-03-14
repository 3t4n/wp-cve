<?php

namespace WpifyWooDeps\Wpify\Core\Models;

class AttachmentImageModel extends AttachmentModel
{
    private $details;
    public function get_html($size = 'full', $icon = \false, $attr = array())
    {
        return wp_get_attachment_image($this->get_id(), $size, $icon, $attr);
    }
    public function get_src($size = 'full')
    {
        $image = $this->get_image($size);
        return $image[0];
    }
    public function get_image($size = 'full')
    {
        return wp_get_attachment_image_src($this->get_id(), $size);
    }
    public function get_details()
    {
        if ($this->details) {
            return $this->details;
        }
        $uploads_baseurl = wp_upload_dir()['baseurl'];
        if (!$this->id) {
            return array();
        }
        $data = wp_get_attachment_metadata($this->id);
        $prepared = array('mime_type' => get_post_mime_type($this->id), 'src' => $uploads_baseurl . '/' . $data['file'], 'sizes' => array(), 'height' => $data['height'], 'width' => $data['width']);
        foreach ($data['sizes'] as $size => $size_info) {
            $prepared['sizes'][$size] = array('src' => $uploads_baseurl . '/' . $size_info['file'], 'height' => $size_info['height'], 'width' => $size_info['width']);
        }
        $this->details = $prepared;
        return $this->details;
    }
}
