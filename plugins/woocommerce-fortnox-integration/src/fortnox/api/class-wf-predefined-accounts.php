<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;

class WF_Predefined_Accounts {

    /** Fetches all predefined accounts from Fortnox
     * @return array
     * @throws \Exception
     */
    public static function get_predefined_accounts(){
        $response = WF_Request::get( '/predefinedaccounts'  );

        if( $response->PreDefinedAccounts ){

            $func = function ( $predefined_account ){
                return [
                    'account_number'    => $predefined_account->Account,
                    'name'              => $predefined_account->Name
                ];
            };

            $accounts = array_map( $func , $response->PreDefinedAccounts );
            update_option( 'fortnox_predefined_accounts', $accounts );
            return $accounts;

        }
        return [];
    }

    /**
     * @param $name
     * @return integer
     */
    public static function get_predefined_account_by_name( $name ){

        $accounts = get_option( 'fortnox_predefined_accounts'  );
        if( empty( $accounts ) ){
            return;
        }

        foreach ( $accounts as $account ) {
            if( $name === $account['name'] ){
                return $account['account_number'];
            }
        }

    }
}