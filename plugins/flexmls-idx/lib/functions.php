<?php

function flexmls_autoloader( $className, $dir = '' ){
    $className = ltrim($className, '\\');
    $fileName  = '';

    if ($lastNsPos = strrpos($className, '\\')) {
        $className = substr($className, $lastNsPos + 1);
    }

    $fileName .= $dir . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if(!file_exists ($fileName)) {
        return false;
    }

    require_once $fileName;
}

function updateUserOptions($auth_token){
    global $fmc_api;
    if (flexmlsConnect::has_api_saved() && $auth_token) {
        $api_my_account = $fmc_api->GetMyAccount();
        update_option('fmc_my_type', $api_my_account['UserType']);

        update_option('fmc_my_id', $api_my_account['Id']);

        $my_office_id = "";
        if ( is_array($api_my_account) && array_key_exists('OfficeId', $api_my_account) && !empty($api_my_account['OfficeId']) ) {
        $my_office_id = $api_my_account['OfficeId'];
        }
        update_option('fmc_my_office', $my_office_id);

        $my_company_id = "";
        if ( is_array($api_my_account) &&  array_key_exists('CompanyId', $api_my_account) && !empty($api_my_account['CompanyId']) ) {
        $my_company_id = $api_my_account['CompanyId'];
        }
        update_option('fmc_my_company', $my_company_id);
    }
}