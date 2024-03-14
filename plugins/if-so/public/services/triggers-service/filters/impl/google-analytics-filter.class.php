<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

require_once( plugin_dir_path ( __DIR__ ) . 'filter-base.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'analytics-service/analytics-service.class.php' );

use IfSo\PublicFace\Services\AnalyticsService;


class GoogleAnalyticsFilter extends FilterBase {
    public function change_text($text,$trigger_data=null) {
        $an_service = AnalyticsService\AnalyticsService::get_instance();
        if(!empty($trigger_data) && $an_service->isOn && $an_service->allow_counting){
            $trigger_id = $trigger_data->get_trigger_id();
            $version = $trigger_data->get_version_index();
            $version_name = !empty($trigger_data->get_data_rules()[$version]['version_name']) ? $trigger_data->get_data_rules()[$version]['version_name'] : null;
            $text .= $an_service->render_google_analytics_event_element(['trigger'=>$trigger_id,'version'=>$version,'version_name'=>$version_name]);
        }

        return $text;
    }

    public function before_apply(){}
    public function after_apply(){}
}