<?php

class Wwm_Ssl_Action_Create_Challenge_File extends Wwm_Migration_Action {
    public static $action_key = 'create_challenge_file';

    public function do_action()
    {
        // get parameter
        $file_name = $this->get_parameter( 'file_name', true );
        $data = $this->get_parameter( 'data', true );
        $ssl_type = $this->get_parameter( 'ssl_type', false );

        // check directory traversal
        if ( preg_match( '/(\.\.\/|\/|\.\.\\\\)/', $file_name ) ) {
            return array(
                'message' => 'File write failure!',
                'success' => false
            );
        }

        $ssl_type_list = array( 'lets', 'alpha' );
        if ( !isset( $ssl_type ) ) {
            $ssl_type = 'lets';
        }

        if ( !in_array( $ssl_type, $ssl_type_list ) ) {
            return array(
                'message' => 'Not allowed ssl_type!',
                'success' => false
            );
        }

        if ( $ssl_type == 'lets' ) {
            $file_dir = WWM_DOCUMENT_ROOT . '.well-known' . DIRECTORY_SEPARATOR . 'acme-challenge';
        }
        else if ( $ssl_type == 'alpha' ) {
            $file_dir = WWM_DOCUMENT_ROOT . '.well-known' . DIRECTORY_SEPARATOR . 'pki-validation';
        }
        $file_path = $file_dir .DIRECTORY_SEPARATOR . $file_name;

        if ( ! file_exists( $file_dir ) ) {
            @mkdir( $file_dir, 0755, true );
        }

        if ( file_put_contents( $file_path, $data ) ) {
            return array(
                'message' => 'File write success!',
                'success' => true
            );
        }
        return array(
            'message' => 'File write failure!',
            'success' => false
        );
    }
}