<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Instagram;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteMedia;

/*
* Instagram Media Class
*/
class RemoteMedia extends AbstractRemoteMedia
{

    public function __construct($metadata = array())
    {
        $this->metadata = $metadata;

        $this->type = 'image';

        if (!empty($this->metadata->is_video) ||
            (is_array($this->metadata) && !empty($this->metadata['is_video']) &&
            $this->metadata['is_video'] != 'false')
        ) {
            $this->type = 'embed';
        }
    }

    /**
     * Prepares a media object for JS, where it is expected
     * to be JSON-encoded and fit into an Attachment model.
     *
     * @return array Array of attachment details.
     */
    public function toMediaManagerAttachment()
    {
        // print_r($this->metadata);
        $this->metadata->ocs_link = 'https://www.instagram.com/p/'.$this->metadata->shortcode;

        $caption = '';
        if (isset($this->metadata->edge_media_to_caption) &&
            isset($this->metadata->edge_media_to_caption->edges) &&
            isset($this->metadata->edge_media_to_caption->edges[0]) &&
            isset($this->metadata->edge_media_to_caption->edges[0]->node) &&
            isset($this->metadata->edge_media_to_caption->edges[0]->node->text)
        ) {
            //limit word count?
            $caption = trim($this->metadata->edge_media_to_caption->edges[0]->node->text);
        }
        $attachment = array_merge(
            $this->getBasicAttachment(),
            array(
                'id'          => $this->metadata->id,
                'title'       => $this->metadata->shortcode,
                'filename'    => $this->metadata->shortcode,
                'url'         => $this->metadata->ocs_link,
                'link'        => $this->metadata->ocs_link,
                'alt'         => '',
                'author'      => isset($this->metadata->ocs_user_full_name) ? $this->metadata->ocs_user_full_name : '',
                'description' => '',
                'caption'     => $caption,
                'name'        => $this->metadata->shortcode,
                'status'      => 'inherit',
                'uploadedTo'  => 0,
                'date'        => $this->metadata->taken_at_timestamp * 1000,
                'modified'    => $this->metadata->taken_at_timestamp * 1000,
                'menuOrder'   => 0,
                'mime'        => 'remote/instagram',
                'subtype'     => $this->type,
                'icon'        => $this->metadata->thumbnail_src,
                'dateFormatted' => mysql2date(get_option('date_format'), $this->metadata->taken_at_timestamp),
                'nonces'      => array(
                    'update' => false,
                    'delete' => false,
                ),
                'editLink'   => false,
            )
        );
        
        if ($this->type === 'image') {
            $attachment['url'] = $this->metadata->display_url;

            $attachment['width'] = intval($this->metadata->dimensions->width);
            $attachment['height'] = intval($this->metadata->dimensions->height);

            $attachment['sizes'] = $this->getImageSizes(
                $attachment['width'],
                $attachment['height'],
                $attachment['url']
            );

            if (!empty($this->metadata->thumbnail_resources)) {
                if (!empty($this->metadata->thumbnail_resources[0]) &&
                    !empty($this->metadata->thumbnail_resources[0]->src)
                ) {
                    $attachment['sizes']['thumbnail']['url'] = $this->metadata->thumbnail_resources[0]->src;
                }

                if (!empty($this->metadata->thumbnail_resources[2]) &&
                    !empty($this->metadata->thumbnail_resources[2]->src)
                ) {
                    $attachment['sizes']['medium']['url'] = $this->metadata->thumbnail_resources[2]->src;
                }

                if (!empty($this->metadata->thumbnail_resources[4]) &&
                    !empty($this->metadata->thumbnail_resources[4]->src)
                ) {
                    $attachment['sizes']['large']['url'] = $this->metadata->thumbnail_resources[4]->src;
                }
            }
        }

        $attachment['remotedata'] = $this->metadata;
        return $attachment;
    }
}
