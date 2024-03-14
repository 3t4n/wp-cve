<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;
require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'services/geolocation-service/geolocation-service.class.php');
class UserIpAddress extends TriggerBase {
    public function __construct() {
        parent::__construct('UserIp');
    }

    public function handle($trigger_data) {
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();
        $relationship = trim($rule['ip-values']);
        $trigger_ip =  trim($rule['ip-input']);
        $user_ip = \IfSo\Services\GeolocationService\GeolocationService::get_instance()->get_user_ip();

        if($relationship==='is' && $user_ip===$trigger_ip)
            return $content;
        if($relationship==='is-not' && $user_ip!==$trigger_ip)
            return $content;
        if($relationship==='contains' && strpos($user_ip,$trigger_ip)!==false)
            return $content;
        if($relationship==='not-contains' && strpos($user_ip,$trigger_ip)===false)
            return $content;

        return false;
    }
}