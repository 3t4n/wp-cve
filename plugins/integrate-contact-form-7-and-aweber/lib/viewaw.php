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

function vc_utmawb() {

    global $wpdb;

    $utms  = '?utm_source=ChimpmaticLite';
    $utms .= '&utm_campaign=w' . get_bloginfo( 'version' ) . '-' . awb_difer_dateact_date() . 'c' . WPCF7_VERSION . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . '';
    $utms .= '&utm_medium=cme-' . SPARTAN_AWB_VERSION . '';
    $utms .= '&utm_term=P' . PHP_VERSION . 'Sq' . $wpdb->db_version() . '-';
    // $utms .= '&utm_content=';
    return $utms;
}

function hmtl_mapeocampos( $numelemen, $cf7_awb, $listacampos, $listatags, $apivalid, $awb_tool_unpolluted ) {
	if ( isset( $apivalid ) && '1' == $apivalid  /*&& count($listacampos)!=0*/ && ( 1 == $awb_tool_unpolluted  or 2 == $awb_tool_unpolluted ) ) {
		?>

        <div class="mystery">

            <div class="awb-custom-fields">
                <div class="mail-field md-half">
                    <label for="wpcf7-Aweber-cf-email"><?php echo esc_html( __( 'Subscriber Email:', 'wpcf7' ) ); ?> <span class="awb-required" > Required</span></label><br />
                    <?php awb_html_selected_tag ('email',$listatags,$cf7_awb,'email') ;  ?>
                    <small class="description dbl">MUST be an email tag <a href="<?php echo AWBHELP_URL ?>/aweber-required-email<?php echo vc_utmawb() ?>AWB-email" class="helping-field" target="_blank" title="get help with Subscriber Email:"> Learn More</a></small>
                </div>

                <div class="mail-field md-half">
                    <label for="wpcf7-Aweber-cf-name"><?php echo esc_html( __( 'Subscriber Name:', 'wpcf7' ) ); ?></label><br />
                    <?php awb_html_selected_tag ('name',$listatags,$cf7_awb,'text') ; ?>
                    <small class="description dbl"> This may be sent as Name <a href="<?php echo AWBHELP_URL ?>/aweber-subscriber-name<?php echo vc_utmawb() ?>MC-name" class="helping-field" target="_blank" title="get help with Subscriber name:"> Learn More</a></small>
                </div>
            </div>

        </div>

		<?php
	}
}



function awb_html_selected_tag_optin ($listatags,$cf7_awb) {

    $filtro = 'checkbox';
    /*echo ('<pre>') ;
      var_dump ( $listatags ) ;
    echo ('</pre>');*/

    $r = array_filter( $listatags, function( $e ) use ($filtro) {
            return ( $e['basetype'] == $filtro or $e['basetype'] == 'acceptance' )  ;
          });

    $listatags = $r ;
    $accept = ( isset( $cf7_awb[ 'accept' ] )   ) ? $cf7_awb[ 'accept' ] : ' ' ;

    ?>

      <select class="awb-select" id="wpcf7-Aweber-accept"
        name="wpcf7-Aweber[accept]" style="width:35%" >
          <span> Required Acceptance Field   <a href="<?php echo AWB_URL ?>/aweber-opt-in-checkbox" class="helping-field" target="_blank" title="get help with Required Acceptance Field - Opt-in"> Help <span class="red-icon dashicons dashicons-sos"></span>
          <option value=" "
              <?php if ( $accept == ' ' ) { echo 'selected="selected"'; } ?>>
              <?php echo 'Choose.. ' ?></option>
         <?php
            foreach ( $listatags as $listdos ) {
              if ( 'opt-in' != trim( $listdos['name'] )  && '' != trim( $listdos['name'] ) ) {
              ?>
                <option value="<?php echo $listdos['name'] ?>"
                  <?php if ( $accept == $listdos['name'] ) { echo 'selected="selected"'; } ?>>
                  <?php echo '&#91;'.$listdos['name'].'&#93; ' ?> <span class="awb-type"><?php echo ' - type :'.$listdos['basetype'] ; ?></span> </option>
                <?php
              }
           }
        ?>

      </select>

  <?php
  }