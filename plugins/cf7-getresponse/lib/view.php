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

global $wpdb;

function grvc_utm() {

  global $wpdb;

  $utms  = '?utm_source=Getresponse';
  $utms .= '&utm_campaign=w' . get_bloginfo( 'version' ) .'c' . WPCF7_VERSION . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . '';
  $utms .= '&utm_medium=cme-' . SPARTAN_VCGR_VERSION . '';
  $utms .= '&utm_term=F' . ini_get( 'allow_url_fopen' ) . 'C' . ( function_exists( 'curl_init' ) ? '1' : '0' ) . 'P' . PHP_VERSION . 'S' . $wpdb->db_version() . '';
  // $utms .= '&utm_content=';

  return $utms;

}

?>



<h2>Getresponse Extension <span class="mc-code"><?php echo SPARTAN_VCGR_VERSION . '.' . ini_get( 'allow_url_fopen' ) . '.' . ( function_exists( 'curl_init' ) ? '1' : '0' ) . '.' . WPCF7_VERSION . '.' . get_bloginfo( 'version' ) . '.' . PHP_VERSION . '.' . $wpdb->db_version() ?></span></h2>

<div class="vcgr-main-fields">


  <p class="mail-field">
    <label for="wpcf7-getresponse-api"><?php echo esc_html( __( 'Getresponse API Key:', 'wpcf7' ) ); ?> </label><br />
    <input type="text" id="wpcf7-getresponse-api" name="wpcf7-getresponse[api]" class="wide" size="70" placeholder=" " value="<?php echo (isset($cf7_gr['api']) ) ? esc_attr( $cf7_gr['api'] ) : ''; ?>" />
    <small class="description">9807ae265ae7a87810a310c28a4759e1 <-- A number like this <a href="<?php echo VCGR_URL ?>/getresponse-api-key<?php echo grvc_utm() ?>MC-api" class="helping-field" target="_blank" title="get help with Getresponse API Key:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <p class="mail-field">
    <label for="wpcf7-getresponse-list"><?php echo esc_html( __( 'Getresponse Campaign Name:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-getresponse-list" name="wpcf7-getresponse[list]" class="wide" size="70" placeholder=" " value="<?php echo (isset( $cf7_gr['list']) ) ?  esc_attr( $cf7_gr['list']) : '' ; ?>" />
    <small class="description">muppets <-- A name like this <a href="<?php echo VCGR_URL ?>/getresponse-list-id<?php echo grvc_utm() ?>MC-list-id" class="helping-field" target="_blank" title="get help with Getresponse List ID:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>

  <p class="mail-field">
    <label for="wpcf7-getresponse-email"><?php echo esc_html( __( 'Subscriber Email:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-getresponse-email" name="wpcf7-getresponse[email]" class="wide" size="70" placeholder="" value="<?php echo (isset ( $cf7_gr['email'] ) ) ? esc_attr( $cf7_gr['email'] ) : ''; ?>" />
    <small class="description"><?php echo vcgr_mail_tags(); ?> <-- you can use these mail-tags <a href="<?php echo VCGR_URL ?>/getresponse-contact-form<?php echo grvc_utm() ?>MC-email" class="helping-field" target="_blank" title="get help with Subscriber Email:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <p class="mail-field">
    <label for="wpcf7-getresponse-name"><?php echo esc_html( __( 'Subscriber Name:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-getresponse-name" name="wpcf7-getresponse[name]" class="wide" size="70" placeholder="" value="<?php echo (isset ($cf7_gr['name'] ) ) ? esc_attr( $cf7_gr['name'] ) : ''; ?>" />
    <small class="description"><?php echo vcgr_mail_tags(); ?>  <-- you can use these mail-tags <a href="<?php echo VCGR_URL ?>/getresponse-contact-form<?php echo grvc_utm() ?>MC-name" class="helping-field" target="_blank" title="get help with Subscriber name:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <div class="cme-container vcgr-support" style="display:none">

      <p class="mail-field mt0">
        <label for="wpcf7-getresponse-accept"><?php echo esc_html( __( 'Required Acceptance Field:', 'wpcf7' ) ); ?> </label><br />
        <input type="text" id="wpcf7-getresponse-accept" name="wpcf7-getresponse[accept]" class="wide" size="70" placeholder="[opt-in] <= Leave Empty if you are NOT using the checkbox or read the link above" value="<?php echo (isset($cf7_gr['accept'])) ? $cf7_gr['accept'] : '';?>" />
        <small class="description"><?php echo vcgr_mail_tags(); ?>  <-- you can use these mail-tags <a href="<?php echo VCGR_URL ?>/getresponse-opt-in-checkbox<?php echo grvc_utm() ?>MC-opt-in-checkbox" class="helping-field" target="_blank" title="get help with Subscriber name:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
      </p>

      <p class="mail-field">
        <input type="checkbox" id="wpcf7-getresponse-conf-subs" name="wpcf7-getresponse[confsubs]" value="1"<?php echo ( isset($cf7_gr['confsubs']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-getresponse-double-opt-in"><?php echo esc_html( __( 'Enable Double Opt-in (checked = true)', 'wpcf7' ) ); ?>  <a href="<?php echo VCGR_URL ?><?php echo grvc_utm() ?>MC-double-opt-in" class="helping-field" target="_blank" title="get help with Custom Fields"> Help <span class="red-icon dashicons dashicons-sos"></span></a></label>
      </p>


      <p class="mail-field">
        <input type="checkbox" id="wpcf7-getresponse-cf-active" name="wpcf7-getresponse[cfactive]" value="1"<?php echo ( isset($cf7_gr['cfactive']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-getresponse-cfactive"><?php echo esc_html( __( 'Use Custom Fields', 'wpcf7' ) ); ?>  <a href="<?php echo VCGR_URL ?>/getresponse-custom-fields<?php echo grvc_utm() ?>MC-custom-fields" class="helping-field" target="_blank" title="get help with Custom Fields"> Help <span class="red-icon dashicons dashicons-sos"></span></a></label>
      </p>


      <div class="getresponse-custom-fields">
        <p>In the following fields, you can use these mail-tags: <?php echo vcgr_mail_tags(); ?></p>

        <div>
          <?php for($i=1;$i<=10;$i++){ ?>

          <div class="col-6">
            <label for="wpcf7-getresponse-CustomValue<?php echo $i; ?>"><?php echo esc_html( __( 'Contact Form Value '.$i.':', 'wpcf7' ) ); ?></label><br />
            <input type="text" id="wpcf7-getresponse-CustomValue<?php echo $i; ?>" name="wpcf7-getresponse[CustomValue<?php echo $i; ?>]" class="wide" size="70" placeholder="[your-mail-tag]" value="<?php echo (isset( $cf7_gr['CustomValue'.$i]) ) ?  esc_attr( $cf7_gr['CustomValue'.$i] ) : '' ;  ?>" />
          </div>


          <div class="col-6">
            <label for="wpcf7-getresponse-CustomKey<?php echo $i; ?>"><?php echo esc_html( __( 'Getresponse Custom Field Name '.$i.':', 'wpcf7' ) ); ?></label><br />
            <input type="text" id="wpcf7-getresponse-CustomKey<?php echo $i; ?>" name="wpcf7-getresponse[CustomKey<?php echo $i; ?>]" class="wide" size="70" placeholder="EXAMPLE" value="<?php echo (isset( $cf7_gr['CustomKey'.$i]) ) ?  esc_attr( $cf7_gr['CustomKey'.$i] ) : '' ;  ?>" />
          </div>

          <?php } ?>

        </div>



      </div>


      <p class="mail-field">
        <input type="checkbox" id="wpcf7-getresponse-cf-support" name="wpcf7-getresponse[cf-supp]" value="1"<?php echo ( isset($cf7_gr['cf-supp']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-getresponse-cfactive"><?php echo esc_html( __( 'Developer Backlink', 'wpcf7' ) ); ?> <small><i>( If checked, a backlink to our site will be shown in the footer. This is not compulsory, but always appreciated <span class="spartan-blue smiles">:)</span> )</i></small></label>
      </p>


  </div>




    <table class="form-table mt0 description">
      <tbody>
        <tr>
          <th scope="row">Debug Logger</th>
          <td>
            <fieldset><legend class="screen-reader-text"><span>Debug Logger</span></legend><label for="wpcf7-getresponse-cfactive">
            <input type="checkbox"
                   id="wpcf7-getresponse-logfileEnabled"
                   name="wpcf7-getresponse[logfileEnabled]"
                   value="1" <?php echo ( isset( $cf7_gr['logfileEnabled'] ) ) ? ' checked="checked"' : ''; ?>
            />
            Enable to troubleshoot issues with the extension.</label>
            </fieldset>
            <p>- View debug log file by clicking <a href="<?php echo esc_textarea( SPARTAN_VCGR_PLUGIN_URL ). '/logs/log.txt'; ?>" target="_blank">here</a>. <br />
               - Reset debug log file by clicking <a href="<?php echo esc_textarea( $urlactual ). '&vcgr_reset_log=1'; ?>">here</a>.</p>

          </td>
        </tr>
      </tbody>
    </table>







    <p class="p-author"><a type="button" aria-expanded="false" class="vcgr-trigger a-support ">Show advanced settings</a> &nbsp; <a class="cme-trigger-sys a-support ">Get System Information</a></p>

  <script>
    jQuery(".cme-trigger-sys").click(function() {

      jQuery( "#toggle-sys" ).slideToggle(250);

    });

    function toggleDiv() {

      setTimeout(function () {
          jQuery(".vcgr-cta").slideToggle(450);
      }, 9000);

    }
    toggleDiv();

  </script>
    <?php include SPARTAN_VCGR_PLUGIN_DIR . '/lib/system.php'; ?>
    <!-- <hr class="p-hr"> -->

    <div class="dev-cta vcgr-cta welcome-panel" style="display: none;">

    <div class="welcome-panel-content">

      <p class="about-description">Hello. My name is Renzo, I <span alt="f487" class="dashicons dashicons-heart red-icon"> </span> WordPress and I develop this tiny FREE plugin to help users like you. I drink copious amounts of coffee to keep me running longer <span alt="f487" class="dashicons dashicons-smiley red-icon"> </span>. If you've found this plugin useful, please consider making a donation.</p><br>
      <p class="about-description">Would you like to <a class="button-primary" href="//www.paypal.me/renzojohnson" target="_blank">buy me a coffee?</a></p>

    </div>

    </div>



</div>





