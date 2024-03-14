<?php
namespace Ari_Cf7_Button\Helpers;

use Ari\Utils\Array_Helper as Array_Helper;
use Ari_Cf7_Button\Forms\Settings as Settings_Form;
use Ari_Cf7_Button\Helpers\Settings as Settings_Helper;

final class Helper {
    private static $system_args = array(
        'action',

        'msg',

        'msg_type',

        'noheader',
    );

    public static function build_url( $add_args = array(), $remove_args = array(), $remove_system_args = true, $encode_args = true ) {
        if ( $remove_system_args ) {
            $remove_args = array_merge( $remove_args, self::$system_args );
        }

        if ( $encode_args )
            $add_args = array_map( 'rawurlencode', $add_args );

        return add_query_arg( $add_args, remove_query_arg( $remove_args ) );
    }

    static public function get_cf7_forms() {
        $forms = null;

        if ( ! class_exists( 'WPCF7_ContactForm' ) )
            return $forms;

        $settings = Settings::instance();

        $cf7_forms = \WPCF7_ContactForm::find(
            array(
                'orderby' => $settings->get_option( 'order_by' ),

                'order' => $settings->get_option( 'order_dir' ),
            )
        );

        if ( is_array( $cf7_forms ) ) {
            $forms = array();

            foreach ( $cf7_forms as $cf7_form ) {
                $form_id = $cf7_form->id();

                $forms[] = array(
                    'id' => $form_id,

                    'title' => $cf7_form->title(),
                );
            }
        }

        return $forms;
    }

    public static function get_settings_form() {
        $form = new Settings_Form();
        $form->bind( Array_Helper::to_flat_array( Settings_Helper::instance()->full_options() ) );

        return $form;
    }
}
