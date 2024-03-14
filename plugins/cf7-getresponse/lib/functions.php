<?php
/*  Copyright 2013-2017 Renzo Johnson (email: renzojohnson at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$plugins = get_option('active_plugins');

add_filter( 'wpcf7_editor_panels', 'show_vcgr_metabox' );
add_action( 'wpcf7_after_save', 'wpcf7_vcgr_save_getresponse' );
add_filter('wpcf7_form_response_output', 'spartan_vcgr_author_wpcf7', 40,4);
add_action( 'wpcf7_before_send_mail', 'wpcf7_vcgr_subscribe' );
add_filter( 'wpcf7_form_class_attr', 'spartan_vcgr_class_attr' );


resetlogfile_gr(); //para resetear


function wpcf7_vcgr_add_getresponse( $args ) {

  $cf7_vcgr_defaults = array();
  $cf7_gr = get_option( 'cf7_vcgr_'.$args->id(), $cf7_vcgr_defaults );

  $host = esc_url_raw( $_SERVER['HTTP_HOST'] );
  $url = $_SERVER['REQUEST_URI'];
  $urlactual = $url;

  include SPARTAN_VCGR_PLUGIN_DIR . '/lib/view.php';

}



function resetlogfile_gr() {

  if ( isset( $_REQUEST['vcgr_reset_log'] ) ) {

    $vcgr_debug_logger = new vcgr_Debug_Logger();

    $vcgr_debug_logger->reset_vcgr_log_file( 'log.txt' );
    $vcgr_debug_logger->reset_vcgr_log_file( 'log-cron-job.txt' );
    echo '<div id="message" class="updated is-dismissible"><p>Debug log files have been reset!</p></div>';

  }

}



function wpcf7_vcgr_save_getresponse( $args ) {

  if (!empty($_POST)){

    update_option( 'cf7_vcgr_'.$args->id(), $_POST['wpcf7-getresponse'] );

  }

}



function show_vcgr_metabox ( $panels ) {

  $new_page = array(
    'Getresponse-Extension' => array(
      'title' => __( 'Getresponse!', 'contact-form-7' ),
      'callback' => 'wpcf7_vcgr_add_getresponse'
    )
  );

  $panels = array_merge( $panels, $new_page );

  return $panels;

}



function spartan_vcgr_author_wpcf7( $vcgr_supps, $class, $content, $args ) {

  $cf7_vcgr_defaults = array();
  $cf7_gr = get_option( 'cf7_vcgr_'.$args->id(), $cf7_vcgr_defaults );
  $cfsupp = ( isset( $cf7_gr['cf-supp'] ) ) ? $cf7_gr['cf-supp'] : 0;

  if ( 1 == $cfsupp ) {

    $vcgr_supps .= vcgr_referer();
    $vcgr_supps .= vcgr_author();

  } else {

    $vcgr_supps .= vcgr_referer();
    $vcgr_supps .= '<!-- Getresponse extension by Renzo Johnson -->';
  }
  return $vcgr_supps;

}



function cf7_vcgr_tag_replace( $pattern, $subject, $posted_data, $html = false ) {

  if( preg_match( $pattern, $subject, $matches ) > 0)
  {

    if ( isset( $posted_data[$matches[1]] ) ) {
      $submitted = $posted_data[$matches[1]];

      if ( is_array( $submitted ) )
        $replaced = join( ', ', $submitted );
      else
        $replaced = $submitted;

      if ( $html ) {
        $replaced = strip_tags( $replaced );
        $replaced = wptexturize( $replaced );
      }

      $replaced = apply_filters( 'wpcf7_mail_tag_replaced', $replaced, $submitted );

      return stripslashes( $replaced );
    }

    if ( $special = apply_filters( 'wpcf7_special_mail_tags', '', $matches[1] ) )
      return $special;

    return $matches[0];
  }
  return $subject;

}


if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}



function wpcf7_vcgr_subscribe($obj) {

  $cf7_gr = get_option( 'cf7_vcgr_'.$obj->id() );

  $submission = WPCF7_Submission::get_instance();

  $logfileEnabled = isset($cf7_gr['logfileEnabled']) && !is_null($cf7_gr['logfileEnabled']) ? $cf7_gr['logfileEnabled'] : false;


  if( $cf7_gr ) {
    $subscribe = false;

    $regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
    $callback = array( &$obj, 'cf7_vcgr_callback' );

    $email = cf7_vcgr_tag_replace( $regex, $cf7_gr['email'], $submission->get_posted_data() );
    $name = cf7_vcgr_tag_replace( $regex, $cf7_gr['name'], $submission->get_posted_data() );

    $lists = cf7_vcgr_tag_replace( $regex, $cf7_gr['list'], $submission->get_posted_data() );
    $listarr = explode(',',$lists);

    $merge_vars = array();

    if( isset($cf7_gr['accept']) && strlen($cf7_gr['accept']) != 0 ) {

      $accept = cf7_vcgr_tag_replace( $regex, $cf7_gr['accept'], $submission->get_posted_data() );
      if($accept != $cf7_gr['accept'])
      {
        if(strlen($accept) > 0)
          $subscribe = true;
      }

    } else {

      $subscribe = true;

    }

    for($i=1;$i<=20;$i++) {

      if( isset($cf7_gr['CustomKey'.$i]) && isset($cf7_gr['CustomValue'.$i]) && strlen(trim($cf7_gr['CustomValue'.$i])) != 0 ) {

        $CustomFields[] = array('Key'=>trim($cf7_gr['CustomKey'.$i]), 'Value'=>cf7_vcgr_tag_replace( $regex, trim($cf7_gr['CustomValue'.$i]), $submission->get_posted_data() ) );
        $NameField=trim($cf7_gr['CustomKey'.$i]);
        $NameField=strtr($NameField, "[", "");
        $NameField=strtr($NameField, "]", "");
        $merge_vars=$merge_vars + array($NameField=>cf7_vcgr_tag_replace( $regex, trim($cf7_gr['CustomValue'.$i]), $submission->get_posted_data() ) );

      }

    }


    if( isset($cf7_gr['confsubs']) && strlen($cf7_gr['confsubs']) != 0 ) {

      $vcgr_csu = 'pending';

    } else {

      $vcgr_csu = 'subscribed';

    }

    if($subscribe && $email != $cf7_gr['email']) {

      try {

        $api   = $cf7_gr['api'];


        $api_urlCampaings = 'https://api.getresponse.com/v3/campaigns';
        $api_urlCustomFields= 'https://api.getresponse.com/v3/custom-fields/';

        $headers = 'X-Auth-Token: api-key '. $api;

        $opts = array(
                'headers' => $headers,
                'user-agent' => 'getresponse by renzo.io'
                );

        $campain = wp_remote_request( $api_urlCampaings, $opts ); // con esto conecto
        $customfield = wp_remote_request( $api_urlCustomFields, $opts );;


        $campain = json_decode( $campain["body"], True );
        $campainArr = array_column( $campain, 'campaignId','name' );

        $customfield = json_decode( $customfield["body"], True );
        $custfieldArr = array_column( $customfield, 'customFieldId','name' );

        $lists = $campainArr[$lists];

        $cadMergeVar='[';
        $cadarray = array();

        foreach($merge_vars as $clave=>$valor) {

            $clave = $custfieldArr[$clave];
            $cadMergeVar= $cadMergeVar . ' { '. chr(13) . '"customFieldId" : "' . $clave . '",' . chr(13) .
                          '"value" : ["' . $valor . '"] ' . chr(13) . '},' ;

            $cadarray[] =  array( 'customFieldId' => $clave, 'value' => array($valor) ) ; //Faltaba el corchete para acumular el array

        }

        if ( strlen( $cadMergeVar )  > 1 ) {
            $cadMergeVar = substr( $cadMergeVar,0,strlen( $cadMergeVar ) - 1 );
            $cadMergeVar= $cadMergeVar . ']';
        }
        else {
            $cadMergeVar='';
        }

        $cadarray = json_encode( $cadarray ) ; //Transforma el array en json

        $list  = $lists;

        $body = '{
                   "name": "'.$name.'",
                   "email": "'.$email.'",
                   "dayOfCycle": "10",
                   "campaign": {
                       "campaignId":  "'.$list.'"
                    },
                    "customFieldValues":' . $cadarray . ' ' .
                '}';

        $api_urlContacts = 'https://api.getresponse.com/v3/contacts/';

        $headers = array("X-Auth-Token"=> 'api-key '.$api,
                            "Content-type" => "application/json") ;

        $opts = array(
                'method' => 'POST',
                'headers' => $headers,
                'body' => $body,
                'user-agent' => 'getresponse by renzo.io'
                    );

        $gresponse = wp_remote_post( $api_urlContacts, $opts ); // con esto conecto
        $respuesta = wp_remote_retrieve_response_message( $gresponse );
        $prespuesta = json_encode($gresponse);

        $vcgr_debug_logger = new vcgr_Debug_Logger();
        $vcgr_debug_logger->log_vcgr_debug( 'Contact Form 7 response: Mail sent OK | Getresponse.com response: ' . $respuesta . ' - ' . $prespuesta, 1 , $logfileEnabled );

      } // end try

       catch (Exception $e) {

        $vcgr_debug_logger = new vcgr_Debug_Logger();
        $vcgr_debug_logger->log_vcgr_debug( 'Contact Form 7 response: ' . $e->getMessage(), 4, $logfileEnabled );

      }  // end catch
     } //End If
    } // end $subscribe
}






function spartan_vcgr_class_attr( $class ) {

  $class .= ' getresponse-ext-' . SPARTAN_VCGR_VERSION;
  return $class;

}