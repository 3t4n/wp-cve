<?php
/**
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class UserRolesTrigger extends TriggerBase{
    public function __construct() {
        parent::__construct('userRoles');
    }

    public function handle($trigger_data){
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();

        $roles = $rule['user-role'];
        $relarionship = $rule['user-role-relationship'];

        if($relarionship === 'is' && $this->user_role_is_in_list([$roles]))
            return $content;
        if($relarionship === 'is-not' && !$this->user_role_is_in_list([$roles]))
            return $content;

        return false;
    }

    private function user_role_is_in_list($list){
        if(is_user_logged_in()){
            $user = wp_get_current_user();
            $user_roles = $user->roles;
            foreach($user_roles as $role){
                if(in_array($role,$list)){
                    return true;
                }
            }
        }
        return false;
    }


}