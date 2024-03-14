<?php


namespace rnpdfimporter\core\Integration;


class UserIntegration
{
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetCurrentUserId(){
        return get_current_user_id();
    }

    public function GetUserNameById($user_id)
    {
        if($user_id==0)
            return '';

        $user=get_userdata($user_id);
        if($user===false)
            return '';

        return $user->user_nicename;

    }
    public function GetCurrentUserRoles(){
        $user=\wp_get_current_user();
        if($user==null)
            return array();

        $rolesToReturn=array();
        foreach($user->roles as $key=>$role)
        {
            $rolesToReturn[]=$role;
        }

        return $rolesToReturn;

    }

    public function GetRoles(){
        global $wp_roles;

        $rolesToReturn=array();

        foreach($wp_roles->roles as $key=>$role)
        {
            $rolesToReturn[]=new Role($key,$role['name']);
        }

        return $rolesToReturn;

    }

    /**
     * @param $user_id
     * @return UserInfo
     */
    public function GetUserInfoById($user_id)
    {
        if($user_id==0)
            return '';

        $user=get_userdata($user_id);
        if($user===false)
            return null;

        return new UserInfo($user->ID, $user->nickname,$user->user_email);

    }

    public function GetUserEmailById($user_id)
    {
        if($user_id==0)
            return '';

        $user=get_userdata($user_id);
        if($user===false)
            return '';

        return $user->user_email;

    }

}

class Role{
    public $Id;
    public $Label;

    public function __construct($id,$label)
    {
        $this->Id=$id;
        $this->Label=$label;
    }


}

class UserInfo{
    public $Name;
    public $Email;
    public $Id;
    public function __construct($id,$name, $email)
    {
        $this->Id=$id;

        $this->Name = $name;
        $this->Email = $email;
    }
}