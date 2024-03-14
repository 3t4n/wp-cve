<?php
namespace IfSo\PublicFace\Services\GroupsService;

require_once('groups-service.class.php');


class GroupsHandler {
    private static $instance;
    protected $groups_service;

    private function __construct() {
        $this->groups_service =  GroupsService::get_instance();
    }

    public static function get_instance() {
        if ( NULL == self::$instance )
            self::$instance = new GroupsHandler();

        return self::$instance;
    }

    private function set_action_notice_cookie($notice){
        setcookie('ifso-group-action-notice',$notice,time() + 3600,'/');
    }

    public function handle(){
        if(check_ajax_referer('ifso-groups-action-nonce') && current_user_can('publish_posts') && isset($_REQUEST['ifso_groups_action']) && !empty($_REQUEST['ifso_groups_action']) && isset($_REQUEST['group_name']) && !empty($_REQUEST['group_name'])){
            try{
                $groupName =  stripslashes($_REQUEST['group_name']);
                switch($_REQUEST['ifso_groups_action']){
                    case 'add_group':
                        $this->set_action_notice_cookie('successfully-added');
                        $this->groups_service->add_group($groupName);
                        break;
                    case 'remove_group':
                        $this->set_action_notice_cookie('successfully-removed');
                        $this->groups_service->remove_group($groupName);
                        break;
                }
            }
            catch (\Exception $e){
                $this->set_action_notice_cookie($e->getMessage());
            }

        }
        if(empty($_REQUEST['group_name'])){
            $this->set_action_notice_cookie('no-name-to-add');
        }

        wp_redirect(wp_get_referer());
        exit();
        wp_die();
    }
}