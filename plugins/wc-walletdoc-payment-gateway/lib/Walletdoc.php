<?php

/**

* Walletdoc

* used to manage Walletdoc API calls

*

*/

include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'ValidationException.php';

Class Walletdoc {

    private $api_endpoint;

    private $auth_headers;

    private $access_token;

    private $client_id;

    private $client_secret;

    function __construct( $client_id, $client_secret, $test_mode ) {

        $this->client_id = $client_id;

        $this->client_secret = $client_secret;

        if ( $test_mode == 'yes' )

        $this->api_endpoint = 'https://www.walletdoc.com/v1/';

        else

        $this->api_endpoint = 'https://www.walletdoc.com/v1/';

        $this->getAccessToken();

    }

    public function getAccessToken() {

        $data = array();

        $data[ 'client_id' ] = $this->client_id;

        $data[ 'client_secret' ] = $this->client_secret;

        $data[ 'scopes' ] = 'all';

        $data[ 'grant_type' ] = 'client_credentials';

        $this->access_token = $this->client_secret;

        $php_version = PHP_VERSION;

        $uname_disabled = $this->Walletdoc_isDisabled( \ini_get( 'disable_functions' ), 'php_uname' );

        $uname = $uname_disabled ? '(disabled)' : \php_uname();

        $plugin_info = [

            'name' => 'Walletdoc Payment Gateway for WooCommerce',

            'platform' => 'Woocommerce',

            'version' => '1.5.1'

        ];

        $Clientinfo =  [

            'language' => 'PHP',

            'language_version'=> $php_version,

            'uname' => $uname,

            'plugin_info' => $plugin_info,

        ];

        $this->auth_headers =   array( 'Content-Type' => 'application/json',

        'Authorization' =>'Basic '.$this->access_token,

        'Accept'=> 'application/json',

        'X-Walletdoc-Client-App' => json_encode( $Clientinfo ) );

    }

    public function Walletdoc_isDisabled( $disableFunctionsOutput, $functionName )
 {

        $disabledFunctions = \explode( ',', $disableFunctionsOutput );

        foreach ( $disabledFunctions as $disabledFunction ) {

            if ( \trim( $disabledFunction ) === $functionName ) {

                return true;

            }

        }

        return false;

    }

    public function createOrderPayment( $data ) {

        $endpoint = $this->api_endpoint . 'transactions/checkout';

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            if ( isset( $result->redirect ) ) {

                return $result;

            } else {

                $errors = array();

                if ( isset( $result->error->message ) )

                $msg = $result->error->message;

                throw new WalletdocWcValidationException( "Validation Error with message:$msg", array( $msg ), $result );

                foreach ( $result as $k => $v ) {

                    if ( is_array( $v ) )

                    $errors[] = $v[ 0 ];

                }

                if ( $errors )

                throw new WalletdocWcValidationException( 'Validation Error Occured with following Errors : ', $errors, $result );

            }

        }

    }

    public function createCustomer( $data ) {

        $endpoint = $this->api_endpoint . 'customers';

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            if ( isset( $result->customer_id ) ) {

                return $result;

            } else {

                return $result->error;

            }

        }

    }

    public function updateCustomer( $userid, $data ) {

        $endpoint = $this->api_endpoint . "customers/$userid";

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            if ( isset( $result->customer_id ) ) {

                return $result;

            } else {

                if ( isset( $result->error->code ) && $result->error->code == '1112' ) {

                    return  self::createCustomer( $data );

                }

            }

        }

    }

    public function createRefund( $transaction_id, $data ) {

        $endpoint = $this->api_endpoint . "transactions/$transaction_id/refunds";

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     =>60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            if ( isset( $result->customer_id ) ) {

                return $result;

            } else {

                return $result;

            }

        }

    }

    public function getOrderById($id, $max_attempts = 3)

{

    $endpoint = $this->api_endpoint . "transactions/$id";
    $attempt = 0;

    while ($attempt < $max_attempts) {
        $response = wp_remote_get("$endpoint", array(
            'headers' => $this->auth_headers,
        ));

        if (is_wp_error($response)) {
            $attempt++;
            $error_message = $response->get_error_message();
         
            WC_Walletdoc_log("Attempt $attempt: Encountered error with message: $error_message");
            sleep(1);  // Add a delay of 1 second
        } else {
            $result = json_decode(wp_remote_retrieve_body($response));

            if (isset($result->id) && $result->id) {
                return $result;
            } else {
                $attempt++;
                WC_Walletdoc_log("Attempt $attempt: Unable to fetch Payment Request id: '$id'");
                sleep(1);  // Add a delay of 1 second
            }
        }
    }

    WC_Walletdoc_log("Exceeded maximum attempts to fetch Payment Request id: '$id'");
    return null;
}

    // public function getOrderById( $id ) {

    //     $endpoint = $this->api_endpoint . "transactions/$id";

    //     $result = wp_remote_get( $endpoint, array(

    //         'headers'     => $this->auth_headers,

    //     ) );

    //     $result = json_decode( wp_remote_retrieve_body( $result ) );

    //     if ( is_wp_error( $result ) ) {

    //         $error_message = $response->get_error_message();

    //         throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

    //     } else {

    //         if ( isset( $result->id ) and $result->id ) {

    //             return $result;

    //         } else {

    //             return $result;

    //             // throw new Exception( "Unable to Fetch Payment Request id:'$id' Server Responds " . print_r( $result, true ) );

    //         }

    //     }

    // }

    public function getPaymentStatus( $payment_id, $payments ) {

        if ( $payments->id == $payment_id ) {

            return $payments->status;

        }

    }

    function get_user_by( $field, $value ) {

        $userdata = WP_User::get_data_by( $field, $value );

        if ( ! $userdata ) {

            return false;

        }

        $user = new WP_User;

        $user->init( $userdata );

        return $user;

    }

    //capture transaction

    public function captureTransactionProcess( $transaction_id, $data ) {

        $endpoint = $this->api_endpoint . "transactions/$transaction_id/process";

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            return $result;

        }

    }

    public function createTransaction( $data ) {

        $endpoint = $this->api_endpoint . 'transactions';

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        if ( !is_wp_error( $result ) ) {

            $result = json_decode( wp_remote_retrieve_body( $result ) );

            if ( !is_wp_error( $result ) ) {

                if ( !isset( $result->id ) ) {

                    $errors = array();

                    if ( isset( $result->error->message ) )

                    $msg = $result->error->message;

                    throw new WalletdocWcValidationException( "Validation Error with message:$msg", array( $msg ), $result );

                    foreach ( $result as $k => $v ) {

                        if ( is_array( $v ) )

                        $errors[] = $v[ 0 ];

                    }

                    if ( $errors )

                    throw new WalletdocWcValidationException( 'Validation Error Occured with following Errors : ', $errors, $result );

                }

            }

        }

        return $result;

    }

    public function processTransaction( $id, $data ) {

        $endpoint = $this->api_endpoint . "transactions/$id/process";

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        return $result;

        // if ( is_wp_error( $result ) ) {

        //     $error_message = $result->get_error_message();

        //     // throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        //     return $result;

        // } else {

        // if ( isset( $result->id ) and $result->id ) {

        //     return $result;

        // } else {

        //     return $result;

        //     // throw new Exception( "Unable to Fetch Payment Request id:'$id' Server Responds " . print_r( $result, true ) );

        // }

        // }

    }

    public function getCustomerPaymentMethod( $userid, $txnId ) {

        $endpoint = $this->api_endpoint . "customers/$userid/payment_methods/$txnId";

        $result = wp_remote_get( $endpoint, array(

            'headers'     => $this->auth_headers,

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( is_wp_error( $result ) ) {

            $error_message = $response->get_error_message();

            throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        } else {

            if ( isset( $result->id ) and $result->id ) {

                return $result;

            } else {

                return $result;

                // throw new Exception( "Unable to Fetch Payment Request id:'$id' Server Responds " . print_r( $result, true ) );

            }

        }

    }

    public function createPlan( $data ) {

        $endpoint = $this->api_endpoint . 'plans';

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        // if ( is_wp_error( $result ) ) {

        //     $error_message = $response->get_error_message();

        //     throw new WalletdocWcValidationException( "Validation Error with message:$error_message", array( $error_message ), $result );

        // } else {

        // if ( isset( $result->plan_id ) ) {

        //     return $result;

        // } else {

        return isset( $result->error ) ? $result->error : $result ;

        // }

        // }

    }

    public function getCustomerToken( $userid, $data ) {

        $endpoint = $this->api_endpoint . "customers/$userid/payment_methods";

        $result = wp_remote_post( $endpoint, array(

            'headers'     => $this->auth_headers,

            'body'        => json_encode( $data ),

            'method'      => 'POST',

            'data_format' => 'body',

            'timeout'     => 60

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        return $result;

    }

    public function getCustomerTokenList( $userid ) {

        $endpoint = $this->api_endpoint . "customers/$userid/payment_methods";

        $result = wp_remote_get( $endpoint, array(

            'headers'     => $this->auth_headers,

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        return $result;

    }

    public function getPublicKey() {

        $endpoint = $this->api_endpoint . 'keys';

        $result = wp_remote_get( $endpoint, array(

            'headers'     => $this->auth_headers,

        ) );

        $result = json_decode( wp_remote_retrieve_body( $result ) );

        return $result;

    }

    public function deleteCustomerToken( $userid, $paymentmethodId ) {

        $endpoint = $this->api_endpoint . "customers/$userid/payment_methods/$paymentmethodId";

        $args =  array(

            'headers'     => $this->auth_headers,

            'method'      => 'DELETE',

            'timeout'     => 60

        );

        $response = wp_remote_request( $endpoint, $args );

        $result = json_decode( wp_remote_retrieve_body( $response ) );

        return $result;

    }

}

