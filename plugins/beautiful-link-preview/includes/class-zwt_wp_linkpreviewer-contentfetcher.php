<?php

class Zwt_wp_linkpreviewer_Content_Fetcher
{

    const KEY_TITLE = 'title';
    const KEY_DESCRIPTION = 'description';
    const KEY_IMG_URL = 'imgUrl';

    public function fetchContent($url)
    {
        if ($this->isUrlValid($url)) {
            $content = $this->fetchUrl($url);
            $document = new DOMDocument();
            libxml_use_internal_errors(true);
            @$document->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            libxml_use_internal_errors(false);
            $metaTags = $this->getMetaTags($document);
            return array(
                self::KEY_TITLE => sanitize_text_field($this->getTitle($metaTags, $document)),
                self::KEY_DESCRIPTION => sanitize_text_field($this->getDescription($metaTags)),
                self::KEY_IMG_URL => esc_url_raw($this->isUrlValid($this->getImgUrl($metaTags, $document))),
            );
        }
        return null;
    }

    public function fetchImg($img_url)
    {
        if (!empty($img_url)) {
            $img_data = $this->fetchUrl($img_url);
            if ($img_data) {
                $mime_type = $this->get_mimetype($img_data);
                if ($this->check_mime_type($mime_type)) {
                    require_once plugin_dir_path(__FILE__) . 'class-zwt_wp_linkpreviewer-imgtool.php';
                    $imgTool = new Zwt_wp_linkpreviewer_Img_Tool();
                    return array(
                        'img_full' => $imgTool->resize($img_data, true),
                        'img_compact' => $imgTool->resize($img_data, false),
                        'img_url' => $img_url
                    );
                }
            }
        }
        return null;
    }

    private function get_mimetype($imgData)
    {
        $f_info = new finfo(FILEINFO_MIME_TYPE);
        return $f_info->buffer($imgData);
    }

    private function check_mime_type($mimeType)
    {
        return $mimeType && $this->startsWith($mimeType, "image/");
    }

    private function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    private function getImgUrl($metaTags)
    {
        return $this->returnIfPresent($this->getIfExists($metaTags, "og:image"),
            $this->getIfExists($metaTags, "twitter:image:src"));
    }

    private function fetchUrl($url)
    {
        $response = wp_remote_get($url, array(
            'timeout' => 120,
            'User-Agent' => self::handleUserAgent()
        ));
        if (is_array($response)) {
            return wp_remote_retrieve_body($response);
        }
        return null;
    }

    private static function handleUserAgent(){
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (strlen($ua) == 0){
            return "UA-WP-BEAUTIFUL-LINK-PREVIEW";
        }
        return $ua;
    }

    private function getTitle($metaTags, $document)
    {
        return $this->returnIfPresent($this->getIfExists($metaTags, "og:title"),
            $this->returnIfPresent($this->getIfExists($metaTags, self::KEY_TITLE),
                $this->returnIfPresent($this->getDocTitle($document), $this->getFirstHeading($document))));
    }

    private function getDescription($metaTags)
    {
        return $this->returnIfPresent($this->getIfExists($metaTags, "og:description"),
            $this->returnIfPresent($this->getIfExists($metaTags, self::KEY_DESCRIPTION),
                null));
    }

    private function getDocTitle($document)
    {
        $nodes = $document->getElementsByTagName(self::KEY_TITLE);
        if (is_object($nodes->item(0))) {
            return $nodes->item(0)->nodeValue;
        }
        return null;
    }

    private function getFirstHeading($document)
    {
        $nodes = $document->getElementsByTagName('h1');
        if (is_object($nodes->item(0))) {
            return $nodes->item(0)->nodeValue;
        }
        return null;
    }

    private function returnIfPresent($value, $else)
    {
        if ($value) {
            return $value;
        }
        return $else;
    }

    private function getIfExists($metaTags, $key)
    {
        if (array_key_exists($key, $metaTags)) {
            return $metaTags[$key];
        }
        return null;
    }

    private function getMetaTags($document)
    {
        $domTags = $document->getElementsByTagName('meta');

        $parsedTags = array();
        for ($i = 0; $i < $domTags->length; $i++) {
            $meta = $domTags->item($i);
            $name = $meta->getAttribute('name');
            if (empty($name)) {
                $name = $meta->getAttribute('property');
            }
            if (!empty($name) && empty($parsedTags[$name])) {
                $parsedTags[$name] = $meta->getAttribute('content');
            }
        }
        return $parsedTags;
    }

    private function isUrlValid($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        return null;
    }

}