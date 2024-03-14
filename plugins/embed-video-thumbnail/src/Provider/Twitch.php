<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;

class Twitch extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $regex = '(?:(?:https:|http:)?(?:\/\/)?(?:www\.)?)twitch\.tv/videos/([0-9]+)[?]?.*';
    /**
     * @var string
     */
    private $api = 'https://api.twitch.tv/kraken/videos/c';

    public function getName()
    {
        return 'twitch';
    }

    public function apiCall($id)
    {
        return (string) json_decode(file_get_contents($this->api . $id));
    }

    protected function getEmbedURL($id)
    {
        return '//player.vimeo.com/video/' . $id . '?autoplay=1&';
    }

    public function buildData($id, $queryString = '')
    {
        $data = $this->apiCall($id);

        if (!empty($data)) {
            $thumb = $data[0]['thumbnail_large'];
            $title = $data[0]['title'];
        }

        if (isset($thumb)) {
            if ($this->isThumbCopyEnabled()) {
                $thumb = $this->copyThumb($id, $thumb);
            }
            $thumb = str_replace(['http:', 'https:'], '', $thumb);
        }

        $videoData = new VideoData($this->getName(), $id, $this->getEmbedURL($id));
        $videoData
            ->setThumbnail(isset($thumb) ? $thumb : '')
            ->setTitle(isset($title) ? $title : '')
        ;

        return $videoData;
    }
}
