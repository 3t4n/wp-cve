<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;

class Youtube extends AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $regex =
        '(?:' .
        '(?:https:|http:)?(?:\/\/)?(?:www\.)?)' .
        '(?:youtube(?:-nocookie)?\.com\/' .
        '(?:[^\/]+\/(?:(?!watch))*\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)' .
        '([a-zA-Z0-9-_]{11})' .
        '(?:\?|&)?([\w-]+(=[\w-]*)?(&[\w-]+(=[\w-]*)?)*)?'
    ;

    /**
     * @var string
     */
    private $api = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id=%ID%&key=%APIKEY%';

    public function getName()
    {
        return 'youtube';
    }

    /**
     * @param $id
     *
     * @return array|mixed|string
     */
    public function apiCall($id)
    {
        if (empty($this->options['youtube']['api']['key'])) {
            return [];
        }

        global $wp;

        $url = str_replace(
            ['%ID%', '%APIKEY%'],
            [$id, $this->options['youtube']['api']['key']],
            $this->api
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_REFERER, home_url(add_query_arg([], $wp->request)));
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, true);
    }

    protected function getEmbedURL($id, $queryString = '')
    {
        $host = 'youtube';
        if ($this->hasNoCookie()) {
            $host .= '-nocookie';
        }

        $url = sprintf('//www.%s.com/embed/%s?%s', $host, $id, $queryString);
        $url = rtrim($url, '&');

        $parameters = [
            'controls' => $this->areControlsDisabled() ? 0 : 1,
            'autoplay' => 1,
            'enablejsapi' => 1,
            'rel' => (int) $this->isRelEnabled(),
        ];

        if ($this->isLoopEnabled()) {
            $parameters['loop'] = 1;
        }

        if ($this->isModestEnabled()) {
            $parameters['modestbranding'] = 1;
        }

        if (!empty($queryString)) {
            $url .= '&';
        }

        $url .= http_build_query($parameters);

        return $url;
    }

    public function buildData($id, $queryString = '')
    {
        //retrieve real video ID from channel playlist ID
        if ('videoseries' === $id) {
            $url = $this->getEmbedURL($id, $queryString);
            $realId = $this->getIdFromChannelPlaylistUrl($url);
            if (false !== $realId) {
                $id = $realId;
            }
        }

        $data = $this->apiCall($id);

        if (!empty($data['items'][0]['snippet']['thumbnails']['high']['url'])) {
            $thumb = $data['items'][0]['snippet']['thumbnails']['high']['url'];
            $title = $data['items'][0]['snippet']['title'];
        } else {
            $thumb = 'http://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }

        if (isset($thumb)) {
            if ($this->isThumbCopyEnabled()) {
                $thumb = $this->copyThumb($id, $thumb);
            }
            $thumb = str_replace(['http:', 'https:'], '', $thumb);
        }

        $videoData = new VideoData($this->getName(), $id, $this->getEmbedURL($id, $queryString));
        $videoData
            ->setThumbnail(isset($thumb) ? $thumb : '')
            ->setTitle(isset($title) ? $title : '')
        ;

        return $videoData;
    }

    /**
     * @param $url
     *
     * @return bool
     */
    private function getIdFromChannelPlaylistUrl($url)
    {
        $urlSource = @file_get_contents('https:' . $url);

        preg_match(
            "#playlist_iurlhq\":\"https:\\\\/\\\\/i\.ytimg\.com\\\\/vi\\\\/([a-zA-Z0-9-_]{11})\\\\/hqdefault\.jpg\"#",
            $urlSource,
            $matches
        );

        if (!empty($matches[1])) {
            return $matches[1];
        }

        return false;
    }
}
