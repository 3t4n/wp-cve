<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;
require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'groups-service/groups-service.class.php' );

use IfSo\PublicFace\Services\GroupsService;

class GroupTrigger extends TriggerBase{
    public function __construct() {
        parent::__construct('Groups');

    }

    public function handle($trigger_data) {
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();
        $groups_service = GroupsService\GroupsService::get_instance();

        if(isset($rule['group-name']) && isset($rule['user-group-relation'])){
            $group_name = $rule['group-name'];
            $group_relation = $rule['user-group-relation'];

            if(($group_relation == 'in' &&  $groups_service->is_user_in_group($group_name)) || ($group_relation =='out' && !$groups_service->is_user_in_group($group_name)) )
                return $content;

        }
        return false;
    }
}