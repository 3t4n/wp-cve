<?php

class Zwt_wp_linkpreviewer_URL_Fetcher
{
    public function maybeFetchUrlRaw($url)
    {
        return $this->maybeFetchUrl($url, false);
    }

    public function maybeFetchUrl($url, $escapeResult = true)
    {
        $url = esc_url($url);
        if (!$url) {
            return null;
        }

        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        $result = $dbInstance->getEntry($url);
        if (!$result) {
            $this->fetchUrl($dbInstance, $url);
            $result = $dbInstance->getEntry($url);
        }

        if (!$escapeResult) {
            return $result;
        }

        return $this->escapeResult($result);
    }

    public function fetchUrlForHash($hash_md5)
    {
        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        $result = $dbInstance->getEntryForHash($hash_md5);
        if ($result) {
            return $this->escapeResult($result);
        }
        return null;
    }

    private function fetchUrl($dbInstance, $url)
    {
        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        require_once plugin_dir_path(__FILE__) . 'class-zwt_wp_linkpreviewer-contentfetcher.php';
        Zwt_wp_linkpreviewer_Utils::fetchUrl($dbInstance, $url);
        return null;
    }

    private function escapeResult($fetchResult)
    {
        $urlHost = parse_url($fetchResult->url, PHP_URL_HOST);

        $dto = new stdClass;

        $dto->url = esc_url($fetchResult->url);
        $dto->urlHost = esc_html($urlHost);
        $dto->hashMd5 = esc_html($fetchResult->hash_md5);
        $dto->title = esc_html($fetchResult->title ? : $urlHost);
        $dto->description = esc_html($fetchResult->description);
        $dto->hasImgFull = $fetchResult->img_full_len > 0;
        $dto->hasImgCompact = $fetchResult->img_compact_len > 0;

        return $dto;
    }
}
