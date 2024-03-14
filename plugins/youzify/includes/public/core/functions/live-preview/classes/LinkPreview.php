<?php
/**
 * Copyright (c) 2015 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */

include_once 'Media.php';
include_once 'Regex.php';
include_once 'SetUp.php';
include_once 'Url.php';
include_once 'Content.php';
include_once 'Json.php';

class LinkPreview {

    function __construct() {}

    function joinAll( $matching, $number, $url, $content ) {
        for ($i = 0; $i < count($matching[$number]); $i++) {
            $imgSrc = $matching[$number][$i] . $matching[$number + 1][$i];
            $src = "";
            $pathCounter = substr_count($imgSrc, "../");
            if (!preg_match(Regex::$HTTP_REGEX, $imgSrc)) {
                $src = Url::getImageUrl($pathCounter, Url::canonicalLink($imgSrc, $url));
            }
            if ($src . $imgSrc != $url) {
                if ($src == "")
                    array_push($content, $src . $imgSrc);
                else
                    array_push($content, $src);
            }
        }
        return $content;
    }

    function crawl($text, $imageQuantity, $header)
    {

        if (preg_match(Regex::$URL_REGEX, $text, $match)) {

            $title = "";
            $description = "";
            $videoIframe = "";
            $video = false;

            if (strpos($match[0], " ") === 0)
                $match[0] = "http://" . substr($match[0], 1);

            $finalUrl = $match[0];
            $pageUrl = $finalUrl;

            if (Content::isImage($pageUrl)) {
                $images = [$pageUrl];
            } else {
                $urlData = $this->getPage($pageUrl);
                if (!$urlData["content"] && strpos($pageUrl, "//www.") === false) {
                    if (strpos($pageUrl, "http://") !== false)
                        $pageUrl = str_replace("http://", "http://www.", $pageUrl);
                    elseif (strpos($pageUrl, "https://") !== false)
                        $pageUrl = str_replace("https://", "https://www.", $pageUrl);

                    $urlData = $this->getPage($pageUrl);
                }

                $pageUrl = $finalUrl = $urlData["url"];
                $raw = $urlData["content"];
                $header = $urlData["header"];

                $metaTags = Content::getMetaTags($raw);

                $tempTitle = Content::extendedTrim($metaTags["title"]);
                if ( $tempTitle != '' )
                    $title = $tempTitle;

                if ( $title == '' ) {
                    if ( preg_match(Regex::$TITLE_REGEX, str_replace("\n", " ", $raw), $matching ) )
                        $title = $matching[2];
                }

                $tempDescription = Content::extendedTrim( $metaTags['description'] );


                $description = $tempDescription != '' ? $tempDescription : Content::crawlCode( $raw );

                $descriptionUnderstood = false;

                if ( $description != '' ) {
                    $descriptionUnderstood = true;
                }

                if ( ( $descriptionUnderstood == false && strlen( $title ) > strlen( $description ) && ! preg_match( Regex::$URL_REGEX, $description ) && $description != '' && !preg_match('/[A-Z]/', $description ) ) || $title == $description ) {
                    $title = $description;
                    $description = Content::crawlCode( $raw );
                }

                if ( Content::isJson( $title ) ) {
                    $title = '';
                }
                if ( Content::isJson( $description ) ) {
                    $description = '';
                }

                $media = $this->getMedia( $pageUrl );
                $images = count( $media ) == 0 || $media[0] == '' ? array( Content::extendedTrim( $metaTags['image'] ) ) : array( $media[0] );
                $videoIframe = $media[1];

                if ( count( $images ) == 0 || $images[0] === '' ) {
                    $images = Content::getImages( $raw, $pageUrl, $imageQuantity );
                }

                if ( $media != null && $media[1] != '' ) {
                    $video = true;
                }

                $title = Content::extendedTrim( $title );
                $pageUrl = Content::extendedTrim( $pageUrl );
                $description = Content::extendedTrim( $description );
                $description = preg_replace( Regex::$SCRIPT_REGEX, '', $description );

            }

            $finalLink = explode("&", $finalUrl);
            $finalLink = $finalLink[0];

            $description = strip_tags($description);

            $videoIframe = $videoIframe == null ? "" : $videoIframe;

            $answer = array(
                'title' => $title,
                'link' => $finalLink,
                'pageUrl' => $finalUrl,
                'site' => Url::canonicalPage($pageUrl),
                'description' => $description,
                'image' => $images[0],
                'images' => $images,
                'video' => $video,
                'videoIframe' => $videoIframe
            );

            $result_json = Json::jsonSafe($answer, $header);
            $result_json_decoded = json_decode($result_json);

            $flagged = false;

            if ( ! isset( $result_json_decoded->title ) ) {
                $title = utf8_encode( $title );
                $flagged = true;
            }

            if ( ! isset( $result_json_decoded->description ) ) {
                $description = utf8_encode( $description );
                $flagged = true;
            }

            if ( $flagged ) {

                $answer = array(
                    'title' => $title,
                    'link' => $finalLink,
                    'pageUrl' => $finalUrl,
                    'site' => Url::canonicalPage($pageUrl),
                    'description' => $description,
                    'image' => $images[0],
                    'images' => $images,
                    'video' => $video,
                    'videoIframe' => $videoIframe
                );

                return Json::jsonSafe($answer, $header);
            } else {

                return $result_json;
            }

        }
        return null;
    }

    /**
     * Get Page.
     */
    function getPage( $url ) {

        // Get Preview Args
        $args = array(
            'timeout'     => 120,
            'redirection' => 10,
            'headers' => array(
                'user-agent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2)'
            )
        );

        // Get URL Data.
        $response = wp_remote_get( $url, apply_filters( 'youzify_live_preview_request_args', $args ) );

        // Return Result.
        return array( 'url' => $url, 'content' => wp_remote_retrieve_body( $response ), 'header' => wp_remote_retrieve_header( $response, 'last-modified' ) );

    }

    /**
     * Get Media
     */
    function getMedia( $pageUrl ) {
        $media = array();

        if ( strpos( $pageUrl, 'youtube.com' ) !== false ) {
            $media = Media::mediaYoutube( $pageUrl );
        } elseif ( strpos( $pageUrl, 'ted.com') !== false ) {
            $media = Media::mediaTED( $pageUrl );
        } elseif ( strpos( $pageUrl, 'vimeo.com') !== false) {
            $media = Media::mediaVimeo( $pageUrl );
        } elseif ( strpos( $pageUrl, 'vine.co') !== false) {
            $media = Media::mediaVine( $pageUrl );
        } elseif ( strpos( $pageUrl, 'metacafe.com') !== false) {
            $media = Media::mediaMetacafe( $pageUrl );
        } elseif ( strpos( $pageUrl, 'dailymotion.com') !== false) {
            $media = Media::mediaDailymotion( $pageUrl );
        } elseif ( strpos( $pageUrl, 'collegehumor.com') !== false) {
            $media = Media::mediaCollegehumor( $pageUrl );
        } elseif ( strpos( $pageUrl, 'blip.tv' ) !== false) {
            $media = Media::mediaBlip( $pageUrl );
        } elseif ( strpos( $pageUrl, 'funnyordie.com' ) !== false) {
            $media = Media::mediaFunnyordie( $pageUrl );
        }

        return $media;
    }

}
