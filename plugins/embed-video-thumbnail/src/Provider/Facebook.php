<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;

class Facebook extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $regex = '(?:(?:https:|http:)?(?:\/\/)?(?:www\.)?)facebook\.com\/facebook\/videos\/(?:.*\/)?([0-9]+)\/[?]?.*';

    /**
     * @var string
     */
    private $api = 'https://graph.facebook.com/%s/picture?redirect=false';

    public function getName()
    {
        return 'facebook';
    }

    /**
     * @param $id
     */
    public function apiCall($id)
    {
        $json = file_get_contents(sprintf($this->api, $id));

        if (empty($json)) {
            return null;
        }

        return json_decode($json, true);
    }

    protected function getEmbedURL($id)
    {
        return '//www.facebook.com/video/embed?video_id=' . $id . '&autoplay=1&';
    }

    /**
     * @param $id
     * @param mixed $queryString
     *
     * @return array
     */
    public function buildData($id, $queryString = '')
    {
//        $data = $this->apiCall($id);
        $data = [];

        if (!empty($data)) {
            $thumb = $data['data']['url'];

            $highResolutionThumb = str_replace(['_t', '_n'], '_b', $thumb);

            if (false !== file_get_contents($highResolutionThumb)) {
                $thumb = $highResolutionThumb;
            }
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
        ;

        return $videoData;
    }
}
