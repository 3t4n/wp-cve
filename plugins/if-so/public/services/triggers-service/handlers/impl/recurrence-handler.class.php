<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once( plugin_dir_path ( dirname(__DIR__) ) . 'filters/trigger-filter.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'recurrence-service/recurrence-service.class.php' );

use IfSo\PublicFace\Services\TriggersService\Filters;
use IfSo\Services\LicenseService;
use IfSo\PublicFace\Services\RecurrenceService;
use IfSo\PublicFace\Services\TriggersService;

class RecurrenceHandler extends ChainHandlerBase {
	public function handle($context) {
		$trigger_id = $context->get_trigger_id();
		$data_versions = $context->get_data_versions();
		$data_rules = $context->get_data_rules();
		$is_license_valid = LicenseService\LicenseService::get_instance()->is_license_valid();

        // for "single session" recurrence type
		if (isset($_COOKIE["ifso_recurrence_session_" . $trigger_id])) {
            $recurrence_type = 'all-session';
            $recurrence_expiration_date = '';
			$session_cookie_for_trigger_id_json = stripslashes($_COOKIE["ifso_recurrence_session_" . $trigger_id]);
            $session_cookie_for_trigger_id = json_decode($session_cookie_for_trigger_id_json, true);

            if($session_cookie_for_trigger_id!==null){
                $recurrence_version_index = $session_cookie_for_trigger_id['version_index'];
                $recurrence_version_type = $session_cookie_for_trigger_id['trigger_type'];

                if (RecurrenceService\RecurrenceService::get_instance()->is_recurrence_valid($data_rules, $recurrence_version_index, $recurrence_version_type, $recurrence_type, $is_license_valid, $recurrence_expiration_date))
                    $rule_with_recurrence = $data_rules[$recurrence_version_index];
            }
		}

        // for "always" and "custom" recurrence types
		if ( isset($_COOKIE['ifso_recurrence_data']) ) {
			$recurrence_data_json = stripslashes($_COOKIE['ifso_recurrence_data']);
			$recurrence_data = json_decode($recurrence_data_json, true);
			// pull out the data for the current trigger (if exists)
			if (array_key_exists($trigger_id, $recurrence_data)) {
				$trigger_recurrence_data = $recurrence_data[$trigger_id];

				/* recurrence structure:
				 * {expiration_date: <timestamp>, version_index: <version_index>,	trigger_type: <trigger_type>}
				 */

				$recurrence_expiration_date = array_key_exists('expiration_date', $trigger_recurrence_data) ? $trigger_recurrence_data['expiration_date'] :  '';
				$recurrence_version_index =array_key_exists('version_index', $trigger_recurrence_data) ? $trigger_recurrence_data['version_index'] : '';
				$recurrence_version_type = array_key_exists('trigger_type', $trigger_recurrence_data) ? $trigger_recurrence_data['trigger_type'] :  '';
				$recurrence_type = array_key_exists('recurrence_type', $trigger_recurrence_data) ? $trigger_recurrence_data['recurrence_type'] : '';

				if (RecurrenceService\RecurrenceService::get_instance()->is_recurrence_valid($data_rules, $recurrence_version_index, $recurrence_version_type, $recurrence_type, $is_license_valid, $recurrence_expiration_date))
					$rule_with_recurrence = $data_rules[$recurrence_version_index];
			}
		}

        if(isset($rule_with_recurrence) && isset($recurrence_version_index)){
            if ( !isset( $rule_with_recurrence['freeze-mode'] ) || $rule_with_recurrence['freeze-mode'] != "true" ){
                $recurr_version = $data_versions[$recurrence_version_index];
                $context->set_rendering_recurrence_version($recurrence_version_index);

                $overriding_versions = [];
                foreach ($data_rules as $key=>$value){
                    if(isset($value['recurrence-override']) && $value['recurrence-override']==true)
                        $overriding_versions[] = $key;
                }

                if(count($overriding_versions)>0){
                    $context->set_new_default($recurr_version);
                    $context->clear_context(array_merge($overriding_versions,[$recurrence_version_index]));
                    return $this->handle_next($context);
                }
                else{
                    return Filters\TriggerFilter::get_instance()->apply_filters_and_hooks($recurr_version,TriggersService\TriggerData::createFromContext($context,$recurrence_version_index),$context->get_extra_opts());
                }
            }
        }

		return $this->handle_next($context);
	}
}