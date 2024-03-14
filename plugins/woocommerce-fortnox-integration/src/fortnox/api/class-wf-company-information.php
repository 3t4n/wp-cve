<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;

class WF_Company_Information
{
    /**
     * Returns organization registration number
     * @return string
     */
    public static function get_organization_number()
    {
        try{
            $response = WF_Request::get("/settings/company/", false );
            return $response->CompanySettings->OrganizationNumber;
        }
        catch( \Exception $e ){
            return false;
        }

    }

}