<?php
/**
 *
 * @author Nick Martianov
 *
 **/
namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class UserDetailsTrigger extends TriggerBase{
    public function __construct() {
        parent::__construct('User-Details');
    }

    public function handle($trigger_data){
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();

        $details_type = $rule['user-details-type'];

        if($details_type === 'user-reg-before'){
            if(is_user_logged_in()){
                $relationship = $rule['user-reg-before-relationship'];
                $before = (int) $rule['user-reg-before'];

                $user_data = get_userdata(get_current_user_id());
                $now = new \DateTime();
                $registered_on = new \DateTime($user_data->user_registered);
                $reg_interval = (int) $now->diff($registered_on)->format('%a%');

                if($relationship === '>' && $reg_interval > $before)
                    return $content;

                if($relationship === '<' && $reg_interval < $before)
                    return $content;

                if($relationship === '=' && $reg_interval === $before)
                    return $content;
            }


        }

        return false;
    }



}