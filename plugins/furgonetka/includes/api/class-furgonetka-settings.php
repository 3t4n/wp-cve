<?php

class Furgonetka_Settings
{
    /**
     * @param WP_REST_Request $request
     */
    public function updateSettings( $request )
    {
        $data  = $request->get_json_params();
        $admin = new Furgonetka_Admin( FURGONETKA_PLUGIN_NAME, FURGONETKA_VERSION );
        $admin->update_checkout_options(
            isset( $data['uuid'] ) ? $data['uuid'] : null,
            isset( $data['isActive'] ) ? $data['isActive'] : null,
            isset( $data['isTestMode'] ) ? $data['isTestMode'] : null,
            isset( $data['details'] ) ? $data['details'] : null
        );

        return new WP_REST_Response( true, 200 );
    }

    public function authorize_callback( $request )
    {
        $data = $request->get_json_params();
        $admin = new Furgonetka_Admin( FURGONETKA_PLUGIN_NAME, FURGONETKA_VERSION );
        $admin->store_temporary_api_credentials( $data['consumer_key'], $data['consumer_secret'] );

        return new WP_REST_Response( true, 200 );
    }
}
