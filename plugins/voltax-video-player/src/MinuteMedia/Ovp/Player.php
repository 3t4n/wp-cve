<?php

namespace MinuteMedia\Ovp;

class Player
{
    /**
     * @param int $postId
     * @return bool|string
     */
    public static function create($postId = null)
    {
        $featuredVideo = false;
        $postId = !empty($postId) ? $postId : \get_the_ID();

        $output = "";

        if ($postId) {
            $featuredVideo = FeaturedVideo::getVideo($postId);
            if (!empty($featuredVideo['payload_id'])) {
                $videoId = $featuredVideo['payload_id'];
                $output = self::getEmbedCode($videoId);
            }
        }
        return $output;
    }

    /**
     * @param $videoId
     * @param $playlist_id
     * @param $player_id
     * @param null $image
     * @return string
     */
    public static function getEmbedCode($videoId, $playlist_id, $player_id, $image=null) {
        $player = $player_id !== 'none' ? $player_id : Plugin::getPlayerId();
        if (!$player_id) {
            $player = Plugin::getPlayerId();
        }

        $playlist = $playlist_id !== 'none' ? $playlist_id : '';
        if (!$playlist_id) {
            $playlist = '';
        }

        if($image) {
            $image = urldecode($image);
            $placeholder = "<img src='{$image}' class='mm-video-embed hide-on-front' data-player-id='" . $player ."' ".
                "data-content-id='{$videoId}' data-extra-content-id='{$playlist}' style='max-width:480px' />";
        }
        else {
            $scriptSrc = Constants::ENDPOINT_EMBED . '/' . $player . '.js';
            $placeholder = "<script src='{$scriptSrc}' data-content-id='{$videoId}' data-extra-content-id='{$playlist}'></script>";
        }
        return $placeholder;
    }
}
