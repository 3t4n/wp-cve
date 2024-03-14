<?php
/**
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class UtmTrigger extends TriggerBase {
    public function __construct() {
        parent::__construct('Utm');
    }

    public function handle($trigger_data) {
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();

        $utm_type = 'utm_' . $rule['utm-type'];
        $relation = $rule['utm-relation'];
        $utm_val = $rule['utm-value'];
        $utm_param = $trigger_data->get_HTTP_request()->getParam($utm_type);

        if($relation == 'is' && $utm_param ==$utm_val)
            return $content;
        elseif($relation =='contains' && strpos($utm_param,$utm_val)!==false)
            return $content;
        elseif($relation == 'is-not' && $utm_param != $utm_val)
            return $content;

        return false;
    }
}