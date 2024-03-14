<?php

/**
 * Handle all activations for BC plugins
 * The name is added for uniqueness
 *
 * Always put this in a subfolder of the plugin root
 */
namespace DataPeen\FaceAuth;
class Activation
{

    private static function is_activated($key_check_option)
    {
        return get_transient($key_check_option) === 'valid';
    }
    public static function activate($key_check_option)
    {

        if (self::is_activated($key_check_option))
        {
            $to_user_data['status'] = 'success';
            $to_user_data['message'] = 'License was successfully activated';

            return json_encode($to_user_data);

        }
        $license_data = self::get_license_details();

        if (count($license_data) == 0)
        {
            $to_user_data['status'] = 'error';
            $to_user_data['message'] = 'No license is found. Please download the package again or contact the developer';

            return json_encode($to_user_data);

        }


        if (
            !isset($license_data['license_url']) ||
            !isset($license_data['pp_id']) ||
            !isset($license_data['key']) ||
            !isset($license_data['version']) ||
            !isset($license_data['email'])
        ) {
            $to_user_data['status'] = 'error';
            $to_user_data['message'] = 'You have a broken license file. Please download the plugin again or contact the developer.';

            return json_encode($to_user_data);
        }

        /**
         * 'email' => $_REQUEST['bc_uatc_email'],
        'key' => $_REQUEST['bc_uatc_license_key'],
        'machine_name' => get_site_url(),
        'version' => 1,
        'product_id' => BC_ULTIMATE_ATC_PRODUCT_ID
         *
         *
        'key' => $key_string,
        653         'email' => $user_email,
        654         'pp_id' => $paypal_product_id,
        655         'license_url' => 'https://api.binarycarpenter.com/check-license',
        656         'version' => 1
         */


        $query_string = sprintf('%1$s?key=%2$s&machine_name=%3$s&product_id=%4$s&version=%5$s&email=%6$s',
            $license_data['license_url'],
            $license_data['key'],
            get_site_url(),
            $license_data['pp_id'],
            $license_data['version'],
            $license_data['email']

        );
        ;
        $response = wp_remote_get( $query_string );
        if ( is_array( $response ) ) {

            $body = $response['body']; // use the content
            $response_code = $response['response']['code'];

            if ($response_code != 200)
            {
                $to_user_data['status'] = 'error';
                $to_user_data['message'] = 'HTTP Error: '. $response['response']['code'] . ': ' . $response['response']['message'];

                return json_encode($to_user_data);
            }

            $response_data = json_decode($body);


            if ($response_data->error_code == '000000')
            {
                set_transient($key_check_option, 'valid', 1806400);
                $to_user_data['status'] = 'success';
                $to_user_data['message'] = $response_data->message;

            } else
            {
                $to_user_data['status'] = 'error';
                $to_user_data['message'] = $response_data->message;
            }
        } else
        {
            $to_user_data['status'] = 'error';
            $to_user_data['message'] = 'There are errors contacting the license server' . $response->get_error_message();

        }
        return json_encode($to_user_data);
    }

    /**
     * Get the license details from the local license file
     * @return array|mixed|object
     */
    private static function get_license_details()
    {
        global $wp_filesystem;
        require_once (ABSPATH . '/wp-admin/includes/file.php');

        WP_Filesystem();

        if ($wp_filesystem->exists(self::get_license_file()))
            return json_decode($wp_filesystem->get_contents(self::get_license_file()), true);
        return array();
    }

    /**
     * Retrieve the path of key.lic
     * Return an empty string if not available
     * @return string
     */
    private static function get_license_file()
    {
        $license_file = '';
        if (file_exists(plugin_dir_path(__FILE__). 'key.lic'))
            $license_file =plugin_dir_path(__FILE__). 'key.lic';
        else if ( file_exists(plugin_dir_path(__FILE__). '../key.lic') )
        {
            $license_file = plugin_dir_path(__FILE__). '../key.lic';
        }
        return $license_file;
    }

}

