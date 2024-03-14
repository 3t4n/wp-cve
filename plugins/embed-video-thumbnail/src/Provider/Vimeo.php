<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;

class Vimeo extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $regex = '(?:(?:https:|http:)?(?:\/\/)?(?:www\.)?)(?:player\.)?vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*';

    /**
     * @var string
     */
    private $api = 'http://vimeo.com/api/v2/video/';

    public function getName()
    {
        return 'vimeo';
    }

    /**
     * @param $id
     *
     * @return array|mixed|string
     */
    public function apiCall($id)
    {
        return unserialize(file_get_contents($this->api . $id . '.php'));
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function getEmbedURL($id)
    {
        $url = '//player.vimeo.com/video/' . $id . '?autoplay=1&';

        if ($this->isLoopEnabled()) {
            $url .= 'loop=1&';
        }

        return $url;
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
