<?php

class Wwm_Ssl_Action_Delete_Challenge_File extends Wwm_Migration_Action {
    public static $action_key = 'delete_challenge_file';

    public function do_action()
    {
        // get parameter
        $ssl_type = $this->get_parameter( 'ssl_type', false );

        $ssl_type_list = array( 'lets', 'alpha' );
        if ( !isset( $ssl_type ) ) {
            $ssl_type = 'lets';
        }

        $success = false;
        $well_known_dir = WWM_DOCUMENT_ROOT . '.well-known';
        if ( $ssl_type == 'lets' ) {
            $file_dir = $well_known_dir . DIRECTORY_SEPARATOR . 'acme-challenge';
        }
        else if ( $ssl_type == 'alpha' ) {
            $file_dir = $well_known_dir . DIRECTORY_SEPARATOR . 'pki-validation';
        }

        if ( file_exists( $file_dir ) ) {
            // acme-challengeディレクトリ以下のファイルを全て削除する
            foreach ( glob( $file_dir . DIRECTORY_SEPARATOR . '*' ) as $file ) {
                if ( is_file($file) ) {
                    unlink( $file );
                }
            }

            rmdir( $file_dir );
            rmdir( $well_known_dir );
            $success = true;
        }

        return array(
            'success' => $success
        );
    }
}