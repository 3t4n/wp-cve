<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $pdckl_plugin_version;

$active = get_option('pdckl_active');
$purchase = get_option('pdckl_purchase');
$jquery = get_option('pdckl_jquery');
$links = get_option('pdckl_links');
$price = get_option('pdckl_price');
$price_extra = explode(" ", get_option('pdckl_price_extra'));
$showform = get_option('pdckl_showform');
$api_username = get_option('pdckl_api_username');
$api_password = get_option('pdckl_api_password');
$api_signature = get_option('pdckl_api_signature');
$wd_token = get_option('pdckl_wd_token');

$table_check = $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "pdckl_links'");

$requirements = pdckl_curl_check() && $table_check == $wpdb->prefix . "pdckl_links" && $active ? true : false;

if($requirements) {
  $requirements_notice = '<span style="color: green; font-weight: bold;">SPLNĚNO</span>';
} else {
  if(!pdckl_curl_check()) {
    $requirements_item = '<abbr title="Napište na podporu hostingu a nechce si povolit funkci cURL">Váš server nepodporuje cURL</abbr>';
  } elseif(!$active) {
    $requirements_item = 'Aktivujte plugin';
  } else {
    $requirements_item = 'Nenainstalovala se tabulka pluginu';
  }
  $requirements_notice = '<span style="color: #ff0000; font-weight: bold;">'.$requirements_item.'</span>';
}

if(1 == 1) {
  _e($pdckl_lang['start_list']);
} else {
?>

<div class="form-table padding-top-0">
  <table class="table" width="100%">
    <tr>
      <td>
        <h3>Prvotní nastavení v krocích</h3>
        <ol>
          <li>Kontrola požadavků: <?php _e($requirements_notice); ?></li>
          <?php
            if($requirements) {
          ?>
          <li>
              Cena odkazu: <input type="text" name="price" value="50" style="width: 50px;"> Kč
          </li>
          <li>
              Zadejte klíč z Copywriting.cz:<br />
              <input type="text" name="wd_token" value="" class="regular-text"> <a href="https://www.copywriting.cz/" class="button button-secondary">Získat klíč</a>
          </li>
          <li><input type="submit" name="turnon" value="Zapnout plugin" class="button button-primary"></li>
          <?php
            } else {
          ?>
          <li>Nelze nainstalovat plugin dokud nejsou všechny požadavky splněny.</li>
          <?php
            }
          ?>
        </ol>
      </td>
    </tr>
  </table>
</div>

<h3><?php echo $pdckl_lang['settings_overview']; ?></h3>
<table class="form-table padding-top-0 margin-top-0" style="width: 100%;">
   <tbody>
      <tr>
          <th><?php echo $pdckl_lang['settings_active']; ?></th>
          <td>
              <?php
              if($active == 1)
              {
              ?>
                  <?php echo pdckl_show_help('h_settings_status_on'); ?> <span style="color:green; font-weight:bold;"><?php echo $pdckl_lang['settings_active_on']; ?></span>
              <?php
              }
              else
              {
              ?>
                  <?php echo pdckl_show_help('h_settings_status_off'); ?> <span style="color:red; font-weight:bold;"><?php echo $pdckl_lang['settings_active_off']; ?></span>
              <?php } ?>
          </td>
          <th><?php echo $pdckl_lang['settings_version']; ?></th>
          <td>
          <?php
          $ch = curl_init('https://api.copywriting.cz/podclankova-inzerce/version.php');
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
              'Content-Type: application/json',
              'Content-Length: ' . strlen($request)
          ]);

          $result = curl_exec($ch);
          curl_close($ch);

          $json = json_decode($result);

          if($pdckl_plugin_version == $json->version)
          {
          ?>
               <?php echo pdckl_show_help('h_settings_version_act'); ?> <span style="color:green; font-weight:bold;"><?php _e($pdckl_plugin_version . ' '); ?><?php echo $pdckl_lang['settings_version_actual']; ?></span>
          <?php
          }
          else
          {
            $version_compare = version_compare($pdckl_plugin_version, $json->version);
            $version_compare = $version_compare == -1 ? '<span style="color:red; font-weight:bold;">'.$pdckl_lang['settings_version_old'][0].'</span>' : '<span style="color:#ff9900; font-weight:bold;">'.$pdckl_lang['settings_version_old'][3].'</span>';
          ?>
              <?php echo pdckl_show_help('h_settings_version_old') . ' ' . $version_compare; ?><br />
              &nbsp; &nbsp; &nbsp; <i><?php echo $pdckl_lang['settings_version_old'][1]; ?> <?php _e($json->version); ?> - <a target="_blank" href="<?php _e(PDCKL_HOMEPAGE) ?>"><?php echo $pdckl_lang['settings_version_old'][2]; ?></a>
          <?php
          } ?>
          </td>
      </tr>
      <tr>
          <th><?php echo $pdckl_lang['settings_curl']; ?></th>
          <td>
              <?php
              if(pdckl_curl_check())
              {
              ?>
                  <?php echo pdckl_show_help('h_settings_curl_on'); ?> <span style="color:green; font-weight:bold;"><?php echo $pdckl_lang['settings_curl_on']; ?></span>
              <?php
              }
              else
              {
                  update_option("pdckl_active", 0);
              ?>
                  <?php echo pdckl_show_help('h_settings_curl_off'); ?> <span style="color:red; font-weight:bold;"><?php echo $pdckl_lang['settings_curl_off']; ?></span>
              <?php
              }
              ?>
          </td>
          <th><?php echo $pdckl_lang['settings_tables']; ?></label></th>
          <td>
              <?php
              $table_check = $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "pdckl_links'");
              if($table_check == $wpdb->prefix . "pdckl_links")
              {
              ?>
                  <?php echo pdckl_show_help('h_settings_tables_ok'); ?> <span style="color:green; font-weight:bold;"><?php echo $pdckl_lang['settings_tables_found']; ?></span>
              <?php
              }
              else
              {
                  update_option("pdckl_active", 0);
              ?>
                  <?php echo pdckl_show_help('h_settings_tables_err'); ?> <span style="color:red; font-weight:bold;"><?php echo $pdckl_lang['settings_tables_error'][0]; ?></span>
                  <p style="color:red;">
                      1) <?php echo $pdckl_lang['settings_tables_error'][1]; ?> <br />
                      2) <?php echo $pdckl_lang['settings_tables_error'][2]; ?> <br />
                      3) <?php echo $pdckl_lang['settings_tables_error'][3]; ?> - <a target="_blank" href="<?php echo PDCKL_HOMEPAGE; ?>"><?php echo $pdckl_lang['settings_tables_error'][4]; ?></a>
                  </p>
              <?php } ?>
          </td>
      </tr>
   </tbody>
</table>
<?php
}
?>
