<?php

function qem_download_files()
{
    global  $qem_fs ;
    
    if ( isset( $_POST['qem_download_csv'] ) ) {
        if ( !isset( $_POST['_qem_download_form_nonce'] ) || !wp_verify_nonce( $_POST['_qem_download_form_nonce'], 'qem_download_form' ) ) {
            wp_die( esc_html__( 'Invalid Nonce, sorry something went wrong', 'quick-event-manager' ) );
        }
        $event = (int) $_POST['qem_download_form'];
        // get slug of $event
        $title = get_post_field( 'post_name', $event );
        //$register = qem_get_stored_register();
        $register = get_custom_registration_form( $event );
        $payment = qem_get_stored_payment();
        $sort = explode( ',', $register['sort'] );
        $filename = rawurlencode( strtolower( str_replace( ' ', '-', $title ) ) . '.csv' );
        if ( !$title ) {
            $filename = rawurlencode( 'default.csv' );
        }
        header( 'Content-Description: File Transfer' );
        header( "Pragma: public" );
        header( "Expires: 0" );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header( "Cache-Control: private", false );
        header( "Content-Type: application/octet-stream" );
        header( "Content-Disposition: attachment; filename=\"{$filename}\";" );
        header( "Content-Transfer-Encoding: binary" );
        $message = get_option( 'qem_messages_' . $event );
        if ( !is_array( $message ) ) {
            $message = array();
        }
        $headerrow = array();
        foreach ( $sort as $name ) {
            switch ( $name ) {
                case 'field1':
                    if ( $register['usename'] ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourname' ) );
                    }
                    break;
                case 'field2':
                    if ( qem_get_element( $register, 'usemail' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'youremail' ) );
                    }
                    break;
                case 'field3':
                    if ( qem_get_element( $register, 'useattend' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourattend' ) );
                    }
                    break;
                case 'field4':
                    if ( qem_get_element( $register, 'usetelephone' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourtelephone' ) );
                    }
                    break;
                case 'field5':
                    if ( qem_get_element( $register, 'useplaces' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourplaces' ) );
                    }
                    if ( qem_get_element( $register, 'usemorenames' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'morenames' ) );
                    }
                    break;
                case 'field6':
                    if ( qem_get_element( $register, 'usemessage' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourmessage' ) );
                    }
                    break;
                case 'field9':
                    if ( qem_get_element( $register, 'useblank1' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourblank1' ) );
                    }
                    break;
                case 'field10':
                    if ( qem_get_element( $register, 'useblank2' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourblank2' ) );
                    }
                    break;
                case 'field11':
                    if ( qem_get_element( $register, 'usedropdown' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourdropdown' ) );
                    }
                    break;
                case 'field14':
                    if ( qem_get_element( $register, 'useselector' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yourselector' ) );
                    }
                    break;
                case 'field12':
                    if ( qem_get_element( $register, 'usenumber1' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'yournumber1' ) );
                    }
                    break;
                case 'field16':
                    if ( qem_get_element( $register, 'usechecks' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'checkslabel' ) );
                    }
                    break;
                case 'field17':
                    if ( qem_get_element( $register, 'usedonation' ) ) {
                        array_push( $headerrow, qem_get_element( $register, 'donation' ) );
                    }
                    break;
            }
        }
        array_push( $headerrow, 'Date Sent' );
        $row = '';
        foreach ( $headerrow as $header ) {
            // add commas except last one
            $row .= ( next( $headerrow ) ? qem_csv_a_cell( $header ) . ',' : qem_csv_a_cell( $header ) );
        }
        echo  qem_wp_kses_post( $row ) . PHP_EOL ;
        foreach ( $message as $value ) {
            $cells = array();
            $value['morenames'] = preg_replace( "/\r|\n/", ", ", qem_get_element( $value, 'morenames' ) );
            foreach ( $sort as $name ) {
                switch ( $name ) {
                    case 'field1':
                        if ( $register['usename'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourname' ) );
                        }
                        break;
                    case 'field2':
                        if ( $register['usemail'] ) {
                            array_push( $cells, qem_get_element( $value, 'youremail' ) );
                        }
                        break;
                    case 'field3':
                        if ( $register['useattend'] ) {
                            array_push( $headerrow, qem_get_element( $value, 'notattend' ) );
                        }
                        break;
                    case 'field4':
                        if ( $register['usetelephone'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourtelephone' ) );
                        }
                        break;
                    case 'field5':
                        if ( $register['useplaces'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourplaces' ) );
                        }
                        if ( $register['usemorenames'] ) {
                            array_push( $cells, qem_get_element( $value, 'morenames' ) );
                        }
                        break;
                    case 'field6':
                        if ( $register['usemessage'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourmessage' ) );
                        }
                        break;
                    case 'field9':
                        if ( $register['useblank1'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourblank1' ) );
                        }
                        break;
                    case 'field10':
                        if ( $register['useblank2'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourblank2' ) );
                        }
                        break;
                    case 'field11':
                        if ( $register['usedropdown'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourdropdown' ) );
                        }
                        break;
                    case 'field14':
                        if ( $register['useselector'] ) {
                            array_push( $cells, qem_get_element( $value, 'yourselector' ) );
                        }
                        break;
                    case 'field12':
                        if ( $register['usenumber1'] ) {
                            array_push( $cells, qem_get_element( $value, 'yournumber1' ) );
                        }
                        break;
                    case 'field16':
                        if ( $register['usechecks'] ) {
                            array_push( $cells, qem_get_element( $value, 'checkslist' ) );
                        }
                        break;
                    case 'field17':
                        if ( $register['usedonation'] ) {
                            array_push( $cells, qem_get_element( $value, 'donation_amount' ) );
                        }
                        break;
                }
            }
            array_push( $cells, qem_get_element( $value, 'sentdate' ) );
            $row = '';
            $i = 0;
            foreach ( $cells as $cell ) {
                $i++;
                // add commas except last one
                $row .= qem_csv_a_cell( $cell );
                if ( $i < count( $cells ) ) {
                    $row .= ',';
                }
            }
            echo  qem_wp_kses_post( $row ) . PHP_EOL ;
        }
        exit;
    }

}

/**
 * wrap a cell with commas and quotes for csv
 *
 * @param $cell
 *
 * @return mixed|string
 */
function qem_csv_a_cell( $cell )
{
    if ( strpos( $cell, ',' ) !== false || strpos( $cell, '"' ) !== false ) {
        $cell = '"' . $cell . '"';
    }
    return $cell;
}
