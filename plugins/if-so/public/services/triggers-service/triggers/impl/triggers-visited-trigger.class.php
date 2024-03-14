<?php
/**
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Services\TriggersService\Triggers;

use IfSo\PublicFace\Services\TriggersVisitedService\TriggersVisitedService;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-visited-service/triggers-visited-service.class.php';

class TriggersVisitedTrigger extends TriggerBase{
    public function __construct() {
        parent::__construct('TriggersVisited');
    }

    public function handle($trigger_data){
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();


        if(!empty($rule['triggers-visited-relationship']) && !empty($rule['triggers-visited-id'])){
            $relationship = $rule['triggers-visited-relationship'];
            $id = $rule['triggers-visited-id'];
            $visited_triggers_service = TriggersVisitedService::get_instance();
            $visited_triggers = $visited_triggers_service->get_visited(false);


            if($relationship==='is' && in_array($id,$visited_triggers))
                return $content;

            if($relationship==='is-not' && !in_array($id,$visited_triggers))
                return $content;
        }

        return false;
    }



}