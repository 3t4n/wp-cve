<?php
use MSMoMDP\Std\Core\Arr;


add_action('rest_api_init', 'dpmsbomAdminRegisterRoute');

function dpmsbomAdminRegisterRoute()
{
    register_rest_route('dpmonarchsidebarminimizedonmobile/v1', 'update-dp-basic-options', [
        'methods' => WP_REST_SERVER::ALLMETHODS,
        'callback' => 'dpmsbom_update_dp_options'
    ]);

    register_rest_route('dpmonarchsidebarminimizedonmobile/v1', 'update-dp-cookie', [
        'methods' => WP_REST_SERVER::ALLMETHODS,
        'callback' => 'dpmsbom_update_cookie'
    ]);
    
}


function dpmsbom_update_dp_options($data){
    $adminator_nonce = $_REQUEST['adminator_nonce'];
    if (wp_verify_nonce($adminator_nonce, 'adminator_nonce'))
    {
        $parameters = $data->get_params();
        
        if ($parameters)
        {   
            unset($parameters['adminator_nonce']); 
            $options = get_option('dp_msmom_basic_options', []); 
            $options = array_merge($options, $parameters);
            update_option('dp_msmom_basic_options', $options); 
        }
        return true;
    }
    wp_die(__("Unautorized! Try it somewhere elese!", 'gg-monarch-sidebar-minimized-on-mobile'));
}

function dpmsbom_update_cookie($data){
    $adminator_nonce = $_REQUEST['adminator_nonce'];
    if (wp_verify_nonce($adminator_nonce, 'adminator_nonce'))
    {
        $parameters = $data->get_params();
        
        if ($parameters)
        {   
            if (key_exists('cookie_name', $parameters)){
                
            }
        }
        return true;
    }
    wp_die(__("Unautorized! Try it somewhere elese!", 'gg-monarch-sidebar-minimized-on-mobile'));
}



