<?php

/**
 * This file will create Custom Rest API End Points.
 */
const CC_MAX_IPS = 5;
class WP_Rest_Route
{

  public function __construct()
  {
    add_action('wp_ajax_get_settings', [$this, 'get_settings'], -999);
    add_action('wp_ajax_update_whitelist', [$this, 'update_whitelist'], -999);
    add_action('wp_ajax_save_settings', [$this, 'save_settings'], -999);
    add_action('wp_ajax_updateInstallClickFraud', [$this, 'updateInstallClickFraud'], -999);
  }

  public function get_settings()
  {
    if ($this->save_settings_permission()) {
      $clickcease_api_key = get_option('clickcease_api_key', '');
      $clickcease_domain_key = get_option('clickcease_domain_key', '');
      $secret_key = get_option('clickcease_secret_key', '');
      $remove_tracking = get_option('clickcease_remove_tracking', '');
      $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
      $whitelist = get_option('clickcease_whitelist', []);
      $clientId = get_option('clickcease_client_id', null);
      if (!$clientId) {
        $rtiService = new RTI_Service();
        $clientId = $rtiService->auth_with_botzapping($clickcease_api_key, $clickcease_domain_key, $secret_key);
        if ($clientId)
          update_option('clickcease_client_id', $clientId);
      }
      $response = [
        'authKey' => $clickcease_api_key,
        'domainKey' => $clickcease_domain_key,
        'secretKey' => $secret_key,
        'installClickFraud' => !filter_var($remove_tracking, FILTER_VALIDATE_BOOLEAN),
        'botzappingAuth' => $botzappingAuth,
        'whitelist' => $whitelist,
        'maxWhitelistLength' => CC_MAX_IPS,
        'clientId' => $clientId
      ];

      // Send response to Ajax
      echo json_encode([
        "status" => 200,
        "settings" => $response,
      ]);
    }
    wp_die();
  }



  public function save_settings()
  {
    if ($this->save_settings_permission()) {
      $res = Utils::getHttpSuccessResponse();
      $deactivate = sanitize_text_field($_POST['deactivate']);
      if (!$deactivate || $deactivate === "undefined") {
        $formService = new FormService();
        $tag_hash_key = sanitize_text_field($_POST['domainKey']);
        $secret_key = sanitize_text_field($_POST['secretKey']);
        $api_key = sanitize_text_field($_POST['authKey']);
        $validAuth = true;
        $formService->validateDomainKey($tag_hash_key);


        $clientId = $formService->validateBotzappingAuth($api_key, $tag_hash_key, $secret_key);
        if (!$clientId) {
          header('Status: ' . HTTPCode::BAD_REQUEST);
          $res = Utils::getHttpErrorResponse(ResponseMessage::INVALID_KEYS);
          $validAuth = false;
        } else {
          update_option('clickcease_domain_key', $tag_hash_key);
          update_option('secret_checked', true);
          update_option('clickcease_client_id', $clientId);
          $res = Utils::getHttpSuccessResponse(['clientId' => $clientId]);
        }

        if ($validAuth) {
          update_option('clickcease_api_key', $api_key);
          update_option('clickcease_secret_key', $secret_key);
          LogService::logErrorCode(ErrorCodes::PLUGIN_INSTALL);
        }
      } else {
        $validAuth = !$deactivate;
        (new RTI_Service())->update_user_status(DomainState::BZ_PLUGIN_DEACTIVATED);
        LogService::logErrorCode(ErrorCodes::PLUGIN_REMOVE);
      }

      update_option('clickcease_bot_zapping_authenticated', $validAuth);
      update_option('cheq_invalid_secret', !$validAuth);
      echo $res;
    }

    wp_die();
  }

  public function updateInstallClickFraud()
  {
    if ($this->save_settings_permission()) {
      $installClickFraud = sanitize_text_field($_POST['installClickFraud']);
      $installClickFraud = filter_var($installClickFraud, FILTER_VALIDATE_BOOLEAN);
      update_option('clickcease_remove_tracking', !$installClickFraud);
    }
  }

  private function validateIP($accumulator, string $item)
  {
    if (filter_var($item, FILTER_VALIDATE_IP)) {
      array_push($accumulator, $item);
    }
    return $accumulator;
  }

  public function update_whitelist()
  {
    if ($this->save_settings_permission() && isset($_POST['whitelist'])) {
      $whitelist = explode(',', $_POST['whitelist']);
      $validatedIPs = array_reduce($whitelist, [$this, 'validateIP'], []);
      if (count($validatedIPs) <= CC_MAX_IPS) {
        update_option('clickcease_whitelist', $validatedIPs);
        echo json_encode([
          "status" => 200
        ]);
      } else
        echo json_encode([
          "status" => 400,
          "error" => "Max allowed entries is 5"
        ]);
    } else
      echo json_encode([
        "status" => 403
      ]);
    wp_die();
  }

  public function save_settings_permission()
  {
    return current_user_can('publish_posts');
  }
}
new WP_Rest_Route();

require_once clickcease_plugin_PLUGIN_PATH . '/classes/formService.php';
