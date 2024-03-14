<?php
$value = get_option('maintenance_settings');
$roles = isset($value['roles'])?$value['roles']:array();

if (isset($value['status']) && ($value['status']==1)) {

    //if user not login page is redirect on coming soon template page

    if (!is_user_logged_in()) {

        //get path of our coming soon display page and redirecting
        
        $file = RCSM_PLUGIN_PATH . 'index.php';
        include($file);
        exit();
    } else {
        $user_role = $this->get_user_role();
        if (!in_array($user_role, $roles)) {
            $file = RCSM_PLUGIN_PATH . 'index.php';            
            include($file);
            exit();
        }
    }
}