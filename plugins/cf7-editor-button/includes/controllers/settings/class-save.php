<?php
namespace Ari_Cf7_Button\Controllers\Settings;

use Ari\Controllers\Controller as Controller;
use Ari\Utils\Response as Response;
use Ari\Utils\Request as Request;
use Ari_Cf7_Button\Helpers\Helper as Helper;
use Ari_Cf7_Button\Helpers\Settings as Settings;

class Save extends Controller {
    public function execute() {
        $data = stripslashes_deep( Request::get_var( ARICF7BUTTON_SETTINGS_NAME ) );
        $result = Settings::instance()->save( $data );

        if ( $result ) {
            Response::redirect(
                Helper::build_url(
                    array(
                        'page' => 'contact-form-7-editor-button',

                        'action' => 'display',

                        'msg' => __( 'Settings are saved successfully.', 'contact-form-7-editor-button' ),

                        'msg_type' => 'success',
                    )
                )
            );
        } else {
            Response::redirect(
                Helper::build_url(
                    array(
                        'page' => 'contact-form-7-editor-button',

                        'action' => 'display',

                        'msg' => __( 'The settings are not saved. Probably data are corrupted or a database connection is broken.', 'contact-form-7-editor-button' ),

                        'msg_type' => 'error',
                    )
                )
            );
        }
    }
}
