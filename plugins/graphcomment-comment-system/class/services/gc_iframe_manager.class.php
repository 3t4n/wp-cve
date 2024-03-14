<?php

if (!class_exists('GcIframeManager')) {
  class GcIframeManager
  {
    const GRAPHCOMMENT_ADMIN_IFRAME = 0;
    const GRAPHCOMMENT_CONNEXION_IFRAME = 1;
    const GRAPHCOMMENT_DISCONNEXION_IFRAME = 2;

    public static function getIframeUrl($iframe_name)
    {
      $gcParams = GcParamsService::getInstance();
      $pluginData = get_plugin_data(dirname(__FILE__) . '/../../graphcomment.php');

      switch ($iframe_name) {
        case self::GRAPHCOMMENT_CONNEXION_IFRAME :
          return constant('API_URL') . '/oauth/authorize?response_type=code&client_id=' . $gcParams->graphcommentGetClientKey() . '&redirect_uri=' . urlencode($gcParams->graphcommentGetRedirectUri()) . '&scope=*';
        case self::GRAPHCOMMENT_DISCONNEXION_IFRAME :
          return constant('ADMIN_URL') . '/#/logout';
        case self::GRAPHCOMMENT_ADMIN_IFRAME :
        default :
          $url = isset($_GET['url']) ? $_GET['url'] : '';

          return (
            constant('ADMIN_URL') .
            '/#/website/' .
            (($gcParams->graphcommentHasWebsites() && $gcParams->graphcommentIsWebsiteChoosen())
              ? $gcParams->graphcommentGetWebsiteId() . '/graphcomment'
              : 'new'
            ) .
            $url .
            '?iframe=wp&pluginVersion=' .
            $pluginData['Version']
          );
      }
    }
  }
}

