<?php
/*  Copyright 2013-2021 Renzo Johnson (email: renzojohnson at gmail.com)

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

$awb_tool_autoupdate = get_option( 'Aweber-update') ;

if ( $awb_tool_autoupdate === '0' or  $awb_tool_autoupdate ==='1' ) {

    update_option( 'Aweber-update', $awb_tool_autoupdate );
    //var_dump ( 'existe : ' . $awb_tool_autoupdate  ) ;
} else {

  $deprecated = null;
  $autoload = 'no';
  add_option( 'Aweber-update', '1', $deprecated, $autoload );
  $awb_tool_autoupdate = 1;
}

?>

  <table class="form-table mt0 description">
    <tbody>

      <tr>
        <th scope="row">Custom Fields</th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Custom Fields</span></legend><label for="wpcf7-Aweber-cfactive">
          <input type="checkbox" id="wpcf7-Aweber-cf-active" name="wpcf7-Aweber[cfactive]" value="1"<?php echo ( isset($cf7_awb['cfactive']) ) ? ' checked="checked"' : ''; ?> />
          <?php echo esc_html( __( 'Send more fields to Aweber.com', 'wpcf7' ) ); ?>  <a href="<?php echo AWB_URL ?>/Aweber-custom-fields<?php echo vc_utmawb() ?>MC-custom-fields" class="helping-field" target="_blank" title="get help with Custom Fields"> Learn More </a></label>
          </fieldset>
        </td>
      </tr>


<!--       <tr>
        <th scope="row">Double Opt-in</th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Double Opt-in</span></legend><label for="wpcf7-Aweber-cfactive">
          <input type="checkbox" id="wpcf7-Aweber-conf-subs" name="wpcf7-Aweber[confsubs]" value="1"<?php echo ( isset($cf7_awb['confsubs']) ) ? ' checked="checked"' : ''; ?> />
          <?php echo esc_html( __( 'Enable', 'wpcf7' ) ); ?>  <a href="<?php echo AWB_URL ?>/Aweber-opt-in-checkbox<?php echo vc_utmawb() ?>MC-double-opt-in" class="helping-field" target="_blank" title="get help with Custom Fields"> Learn More </a></label>
          </fieldset>
        </td>
      </tr> -->



      <tr>
        <th scope="row">Required Acceptance</th>
        <td>
          <fieldset>
            <?php awb_html_selected_tag_optin ( $listatags,$cf7_awb ) ?>
            <p class="description">Required Acceptance Field.</p>
          </fieldset>
        </td>
      </tr>

<!--        <tr class="to-hide">
        <th scope="row"></th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Add As Unsubscribed</span></legend><label for="wpcf7-Aweber-cfactive">
          <input type="checkbox" id="wpcf7-Aweber-addunsubscr" name="wpcf7-Aweber[addunsubscr]" value="1"<?php echo ( isset($cf7_awb['addunsubscr']) ) ? ' checked="checked"' : ''; ?> />
          <?php echo esc_html( __( 'Add As Unsubscribed ', 'wpcf7' ) ); ?>  <a href="<?php echo AWB_URL ?>/Aweber-opt-in-addunsubscr<?php echo vc_utmawb() ?>MC-double-addunsubscr" class="helping-field" target="_blank" title="get help with Custom Fields"> Learn More </a></label>
          </fieldset>
        </td>
      </tr> -->




      <tr>
        <th scope="row">Debug Logger</th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Debug Logger</span></legend><label for="wpcf7-Aweber-cfactive">
          <input type="checkbox"
                 id="wpcf7-Aweber-logfileEnabled"
                 name="wpcf7-Aweber[logfileEnabled]"
                 value="1" <?php echo ( isset( $cf7_awb['logfileEnabled'] ) ) ? ' checked="checked"' : ''; ?>
          />
          Enable to troubleshoot issues with the extension.</label>
          </fieldset>
        </td>
      </tr>

      <tr>
        <th scope="row">Developer</th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Developer</span></legend><label for="wpcf7-Aweber-cfactive">
          <input type="checkbox" id="wpcf7-Aweber-cf-support" name="wpcf7-Aweber[cf-supp]" value="1"<?php echo ( isset($cf7_awb['cf-supp']) ) ? ' checked="checked"' : ''; ?> />
          A backlink to my site, not compulsory, but appreciated</label>
          </fieldset>
        </td>
      </tr>

      <tr>
        <th scope="row">Auto Update</th>
        <td>
          <fieldset><legend class="screen-reader-text"><span>Auto Update</span></legend><label for="wpcf7-Aweber-updates">
          <input type="checkbox" id="Aweber-update" name="Aweber-update" value="1"<?php echo ( $awb_tool_autoupdate == '1'  ) ? ' checked="checked"' : ''; ?> />
          Auto Update Aweber Lite</label>
          </fieldset>
        </td>
      </tr>

    </tbody>
  </table>