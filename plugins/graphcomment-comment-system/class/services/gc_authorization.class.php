<?php

require_once(__DIR__.'/../services/gc_params_service.class.php');
require_once(__DIR__.'/../templates/authorization.template.php');

if (!class_exists('GcAuthorization')) {
  class GcAuthorization
  {
    /**
     * If logged
     *  - Return true
     * Else
     *  - Print the authorization popup and return false
     *
     * @return Boolean if the user is logged.
     */
    public static function checkOrPrintOauthIframe()
    {
      if (!GcParamsService::getInstance()->graphcommentOAuthIsLogged() && !GcParamsService::getInstance()->graphcommentRenewToken()) {
        GcParamsService::getInstance()->graphcommentOAuthInitConnection();
        $oauth_client_key = GcParamsService::getInstance()->graphcommentGetClientKey();
        $oauth_redirect_uri = GcParamsService::getInstance()->graphcommentGetRedirectUri();
        if (!empty($oauth_client_key) && !empty($oauth_redirect_uri)) {
          GcAuthorizationTemplate::getTemplate();
        } else {
          GcAuthorizationTemplate::getErrorTemplate($oauth_client_key, $oauth_redirect_uri);
        }
        return false;
      }
      return true;
    }

  }
}