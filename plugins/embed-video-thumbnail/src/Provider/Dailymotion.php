<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;

class Dailymotion extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $regex = '(?:(?:https:|http:)?(?:\/\/)?(?:www\.)?)(?:dailymotion.com|dai.ly)(?:\/embed)?(?:\/video|hub)?\/([a-z0-9]+)[^\#<>]*(?:\#video=([a-z0-9]+))?';

    /**
     * @var string
     */
    private $api = 'https://api.dailymotion.com/video/%ID%?fields=title,description,thumbnail_url';

    public function getName()
    {
        return 'dailymotion';
    }

    /**
     * @param $id
     *
     * @return array|mixed|string
     */
    public function apiCall($id)
    {
        $url = str_replace('%ID%', $id, $this->api);
        $data = file_get_contents($url);
        if (false !== $data) {
            $data = json_decode($data, true);
        }

        return $data;
    }

    protected function getEmbedURL($id)
    {
        return '//www.dailymotion.com/embed/video/' . $id . '?autoplay=1&';
    }

    public function buildData($id, $queryString = '')
    {
        $data = $this->apiCall($id);

        if (!empty($data)) {
            $thumb = $data['thumbnail_url'];
            $title = $data['title'];
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

    public function getRegex()
    {
        return '#(?:<iframe.*src=\"|[^"\[]|\[embed\])?' . $this->regex . '(?:\".*><\/iframe>|[^"\[<>]|\[\/embed\])?([^<>]?)#i';
    }
}
