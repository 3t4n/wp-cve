<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_Blocklogin extends RKMW_Classes_BlockController {

    public $message;

    public function init() {
        /* If logged in, then return */
        if (RKMW_Classes_Helpers_Tools::getOption('api') <> '') {
            return;
        }

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('login');
        echo $this->getView('Blocks/Login');
    }

    /**
     * Called for rkmw_login on Post action
     * Login or register a user
     */
    public function action() {
        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {
            //login action
            case 'rkmw_login':
                //if email is set
                if (RKMW_Classes_Helpers_Tools::getIsset('token')) {
                    //get the token
                    $args['token'] = RKMW_Classes_Helpers_Tools::getValue('token');

                    //get the responce from server on login call
                    /** @var bool|WP_Error $responce */
                    $responce = RKMW_Classes_RemoteController::login($args);

                    /**  */
                    if (is_wp_error($responce)) {
                        switch ($responce->get_error_message()) {
                            case 'badlogin':
                                RKMW_Classes_Error::setError(esc_html__("Wrong Api Key!", RKMW_PLUGIN_NAME));
                                break;
                            default:
                                RKMW_Classes_Error::setError(esc_html__("An error occurred.", RKMW_PLUGIN_NAME) . ':' . $responce->get_error_message());
                                break;
                        }

                    } elseif (isset($responce->token)) { //check if token is set and save it
                        RKMW_Classes_Helpers_Tools::saveOptions('api', $responce->token);
                        $url = RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_dashboard');
                        $url = add_query_arg( 'msg', esc_html__('Your website is now connected to Rank My WP Cloud.'), $url ) ;
                        //redirect users to onboarding if necessary
                        wp_redirect($url);
                        die();

                    } else {
                        //if unknown error
                        if (!RKMW_Classes_Error::isError()) {
                            //if unknown error
                            RKMW_Classes_Error::setError(sprintf(esc_html__("Error: Couldn't connect to host :( . Please contact your site's web-host (or webmaster) and request them to add %s to their IP whitelist.", RKMW_PLUGIN_NAME), RKMW_API_URL));
                        }
                    }

                } else {
                    RKMW_Classes_Error::setError(esc_html__("Both fields are required.", RKMW_PLUGIN_NAME));
                }
                break;
        }
    }


}
