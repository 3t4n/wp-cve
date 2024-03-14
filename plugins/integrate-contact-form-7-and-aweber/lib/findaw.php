<?php
/**
 *	Copyright 2013-2015 Renzo Johnson (email: renzojohnson at gmail.com)
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *  *1: By default the key label for the name must be FNAME
 *  *2: parse first & last name
 *  *3: ensure we set first and last name exist
 *  *4: otherwise user provided just one name
 *  *5: By default the key label for the name must be FNAME
 *  *6: check if subscribed
 *  *bh: email_type
 *  *aw: double_optin
 *  *xz: update_existing
 *  *rd: replace_interests
 *  *gr: send_welcome
 * Tool Aweber for WordPress
 *
 * @author    Renzo Johnson (email: renzo.johnson at gmail.com)
 * @link      http://renzojohnson.com/
 * @copyright 2015 Renzo Johnson (email: renzo.johnson at gmail.com)
 *
 * @package AweberMail
 */
add_action( 'wp_ajax_aweber_logreset',  'aweber_logreset' );
add_action( 'wp_ajax_no_priv_aweber_logreset',  'aweber_logreset' );

add_action( 'wp_ajax_aweber_logload',  'aweber_logload' );
add_action( 'wp_ajax_no_priv_aweber_logload',  'aweber_logload' );


function awb_html_selected_tag ($nomfield,$listatags,$cf7_awb,$filtro) {

    if ( $nomfield != 'email' )  {
        $r = array_filter( $listatags, function( $e ) use ($filtro) {
              return $e['basetype'] == $filtro or $e['basetype'] == 'textarea'  ;
            });
    } else {
      $r = array_filter( $listatags, function( $e ) use ($filtro) {
              return $e['basetype'] == $filtro ;
            });
    }
    
    $listatags =   $r ;
    
    
      $ggCustomValue = ( isset( $cf7_awb[$nomfield] ) ) ? $cf7_awb[$nomfield] : ' ' ;
    
    
      $ggCustomValue = ( ( $nomfield =='email' && $ggCustomValue == ' ' )  ? '[your-email]':$ggCustomValue   );
    
         ?>
          <select class="awb-select" id="wpcf7-Aweber-cf-<?php echo $nomfield; ?>"
                    name="wpcf7-Aweber[<?php echo $nomfield; ?>]" style="width:95%">
                    <?php if ( $nomfield != 'email'  ) { ?>
                        <option value=" "
                        <?php  if ( $ggCustomValue == ' ' ) { echo 'selected="selected"'; } ?>>
                        <?php echo (($nomfield=='email') ? 'Required by Aweber': 'Choose.. ') ?></option>
             <?php
                       }
                foreach ( $listatags as $listdos ) {
                  $vfield = '['. trim( $listdos['name'] ) . ']' ;
                  if ( 'opt-in' != trim( $listdos['name'] )  && '' != trim( $listdos['name'] ) ) {
                  ?>
                    <option value="<?php echo $vfield ?>" <?php if (  trim( $ggCustomValue ) == $vfield ) { echo 'selected="selected"'; } ?>>
                      <?php echo '['.$listdos['name'].']' ?> <span class="awb-type"><?php echo ' - type :'.$listdos['basetype'] ; ?></span>
                   </option>
                    <?php
                  }
               }
                ?>
             </select>
            <?php
}

function get_logaw_array () {

    $default = array() ;
    $log = get_option ('awb_db_issues_log', $default  ) ;

    $awb_log = '' ;

    foreach ( $log as $item ) {

      $awb_log .= "\n" . '[' . $item['datetxt'] . ' UTC]';
      $awb_log .= $item ['content'] .  "\n";
      $awb_log .= print_r ( $item ['object'],true ).  "\n\n\n\n";

    }

    echo $awb_log;

}


function aweber_logreset () {

    global $wpdb;

    $awb_db_logdb = new awb_db_log( 'awb_db_issues', 1,'api' );
    $res = $awb_db_logdb->awb_log_delete_db() ;

    $awb_log = 'Your Log is clean now!';
    $awb_log .= get_logaw_array () ;

    echo $awb_log;

    wp_die();

}

function aweber_logload () {

    global $wpdb;

    get_logaw_array () ;

    wp_die();

}