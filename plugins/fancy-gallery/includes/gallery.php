<?php

namespace WordPress\Plugin\GalleryManager;

class Gallery
{
    private
        $gallery_id, # the post id of the gallery post
        $attributes = [];

    public function __construct(int $gallery_id = -1, array $attributes = [])
    {
        if ($gallery_id < 1) $gallery_id = get_The_Id();
        $this->setGalleryID($gallery_id);
        $this->setAttributes($attributes);
    }

    public function setGalleryID(int $gallery_id = -1): void
    {
        if ($gallery_id < 1) $gallery_id = get_The_Id();
        $this->gallery_id = $gallery_id;
    }

    public function setAttributes(array $arr_attributes = []): void
    {
        $arr_attributes = Array_Filter($arr_attributes);
        $this->attributes = $arr_attributes;
    }

    public function render(): string
    {
        $attributes = Array_Merge([
            'id' => $this->gallery_id,
        ], $this->attributes);

        return (string) Gallery_Shortcode($attributes);
    }

    public function getImages(array $parameters = []): array
    {
        $parameters = Array_Merge([
            'post_parent' => $this->gallery_id,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ], $parameters);

        $attachments = get_Children($parameters);

        foreach ($attachments as $index => &$attachment) {
            $image = WP_Get_Attachment_Image_Src($attachment->ID, 'full');

            if ($image) {
                list($url, $width, $height, $is_intermediate) = $image;
            } else {
                unset($attachments[$index]);
                continue;
            }

            $attachment->url = $url;
            $attachment->width = $width;
            $attachment->height = $height;
            $attachment->is_intermediate = $is_intermediate;

            list($url, $width, $height, $is_intermediate) = WP_Get_Attachment_Image_Src($attachment->ID, 'thumbnail');
            $attachment->thumbnail = (object) [
                'url' => $url,
                'width' => $width,
                'height' => $height,
                'is_intermediate' => $is_intermediate
            ];
        }

        return $attachments;
    }

    public function getPreviewImages(): array
    {
        $arr_images = $this->getImages([
            'numberposts' => (int) Options::get('preview_image_number'),
            'orderby' => 'rand'
        ]);

        return $arr_images;
    }

    public function renderPreview(): string
    {
        $arr_images = $this->getPreviewImages();
        if (empty($arr_images)) return '';

        $arr_image_ids = Array_Map(function ($image) {
            return $image->ID;
        }, $arr_images);

        $column_count = min(
            (int) Options::get('preview_columns'),
            (int) Options::get('preview_image_number')
        );

        return (string) Gallery_Shortcode([
            'id' => 0,
            'ids' => $arr_image_ids,
            'columns' => $column_count,
            'size' => Options::get('preview_thumb_size')
        ]);
    }

    public function setImages(array $arr_images): void
    {
        global $wpdb;

        $image_id_list = join(',', $arr_images);

        if (empty($image_id_list))
            $image_id_list = -1;

        # Update parent_id for all attachments which are NOT in the images array
        $stmt = sprintf(
            '
            UPDATE %s
            SET
                post_parent = NULL,
                menu_order = 0
            WHERE
                post_parent = "%u" AND
                post_type = "attachment" AND
                post_mime_type LIKE "image/%%" AND
                ID NOT IN (%s)',
            $wpdb->posts,
            $this->gallery_id,
            $image_id_list
        );
        $wpdb->query($stmt);

        # Update parent_id for all attachments which ARE in the images array
        $stmt = sprintf(
            '
            UPDATE %s
            SET
                post_parent = "%u"
            WHERE
                post_type = "attachment" AND
                post_mime_type LIKE "image/%%" AND
                ID IN (%s)',
            $wpdb->posts,
            $this->gallery_id,
            $image_id_list
        );
        $wpdb->query($stmt);

        # Update menu_order for all attachments which ARE in the images array
        foreach ($arr_images as $order_index => $attachment_id) {
            $wpdb->update(
                $wpdb->posts,
                ['menu_order' => $order_index],
                ['ID' => $attachment_id]
            );
        }
    }
}
