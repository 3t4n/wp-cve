<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    require_once("index.php");

    $ConveyThis = new ConveyThis();
    $variables = new Variables();
    $ConveyThisCache = new ConveyThisCache();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['api_key'] === $variables->api_key) {
        $url = '//' . $_SERVER['HTTP_HOST'] . $_POST['url'];
        $source = $_POST['source'];
        $target = $_POST['target'];

        $url_plugin = "/" . $target . $_POST['url'];

        $page_id = null;
        $pages = get_posts($url_plugin);
        if ($pages) {
            $page_id = $pages[0]->ID;
        }

        $ConveyThisCache::clearPageCache($url_plugin, $page_id);

        $result = $ConveyThisCache->clear_cached_translations(false, $url, $source, $target);

        echo json_encode(["action" => "success"]);
    }
    else
    {
        echo json_encode(["action" => "error"]);
    }

?>