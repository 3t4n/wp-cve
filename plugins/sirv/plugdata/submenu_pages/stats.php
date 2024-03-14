<?php
$sirvAPIClient = sirv_getAPIClient();
$sirvStatus = $sirvAPIClient->preOperationCheck();

if ($sirvStatus) {
  $storageInfo = sirv_getStorageInfo();
} else {
  wp_safe_redirect(add_query_arg(array('page' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php'), admin_url('admin.php')));
}

if ($sirvStatus && !empty($storageInfo)) { ?>
  <div class="sirv-tab-content sirv-tab-content-active" id="sirv-stats">
    <div class="sirv-stats-container">
      <div class="sirv-stats-messages"></div>
      <h1>Stats</h1>
      <p class="sirv-options-desc">Check the storage and CDN transfer of your Sirv account.</p>
      <div class="sirv-storage-traffic-wrapper">
        <div class="sirv-optiontable-holder">
          <table class="optiontable form-table sirv-form-table">
            <tr>
              <td colspan="2">
                <h3>Storage</h3>
              </td>
            </tr>
            <tr class="small-padding">
              <th><label>Allowance</label></th>
              <td><span class="sirv-allowance"><?php if (isset($storageInfo)) echo $storageInfo['storage']['allowance_text']; ?></span></td>
            </tr>
            <tr class="small-padding">
              <th><label>Used</label></th>
              <td><span class="sirv-st-used"><?php if (isset($storageInfo)) echo $storageInfo['storage']['used_text']; ?><span> (<?php if (isset($storageInfo)) echo $storageInfo['storage']['used_percent']; ?>%)</span></span></td>
            </tr>
            <tr class="small-padding">
              <th><label>Available</label></th>
              <td><span class="sirv-st-available"><?php if (isset($storageInfo)) echo $storageInfo['storage']['available_text']; ?><span> (<?php if (isset($storageInfo)) echo $storageInfo['storage']['available_percent']; ?>%)</span></span></td>
            </tr>
            <tr class="small-padding">
              <th><label>Files</label></th>
              <td><span class="sirv-st-files"><?php if (isset($storageInfo)) echo $storageInfo['storage']['files']; ?></span></td>
            </tr>
          </table>
        </div>

        <div class="sirv-optiontable-holder">
          <table class="optiontable form-table sirv-form-table">
            <tr>
              <td>
                <h3>Transfer</h3>
              </td>
            </tr>
            <tbody cellspacing="0" class="optiontable form-table sirv-form-table traffic-wrapper">
              <tr class="small-padding">
                <th><label>Allowance</label></th>
                <td colspan="2"><span style="" class="sirv-trf-month"><?php if (isset($storageInfo)) echo $storageInfo['traffic']['allowance_text']; ?></span></td>
              </tr>
              <?php
              if (isset($storageInfo['traffic']['traffic'])) {
                foreach ($storageInfo['traffic']['traffic'] as $label => $text) {
              ?>
                  <tr class="small-padding">
                    <th><label><?php echo $label; ?></label></th>
                    <td><span><?php echo $text['size_text']; ?></span></td>
                    <td>
                      <div class="sirv-progress-bar-holder">
                        <div class="sirv-progress-bar">
                          <div>
                            <div style="width: <?php echo $text['percent_reverse']; ?>%;"></div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
              <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <h2>API usage</h2>
      <!-- <p class="sirv-options-desc">Check how much sirv api requests is using.</p> -->
      <p class="sirv-options-desc">Last update: <span class='sirv-stat-last-update'><?php echo $storageInfo['lastUpdate']; ?></span>&nbsp;&nbsp;<a class="sirv-stat-refresh" href="#">Refresh</a></p>
      <div class="sirv-api-usage">
        <div class="sirv-optiontable-holder">
          <table class="optiontable form-table sirv-form-table">
            <thead>
              <tr>
                <td><b>Type</b></td>
                <td><b>Limit</b></td>
                <td><b>Used</b></td>
                <td><b>Next reset</b></td>
              </tr>
            </thead>
            <tbody class='sirv-api-usage-content'>
              <?php foreach ($storageInfo['limits'] as $limit) {
                $is_limit_reached = ((int) $limit['count'] >= (int) $limit['limit']) ? 'style="color: red;"' : '';
              ?>
                <tr <?php echo $is_limit_reached; ?>>
                  <td><?php echo $limit['type'] ?></td>
                  <td><?php echo $limit['limit'] ?></td>
                  <?php if ($limit['count'] > 0) { ?>
                    <td><?php echo $limit['count'] . ' (' . $limit['used'] . ')'; ?></td>
                    <!-- <td><span class="sirv-limits-reset" data-timestamp="<?php echo $limit['reset_timestamp']; ?>"><?php echo $limit['reset_str']; ?></span></td> -->
                    <td><span class="sirv-limits-reset" data-timestamp="<?php echo $limit['reset_timestamp']; ?>"><?php echo $limit['count_reset_str']; ?> <span class="sirv-grey">(<?php echo $limit['reset_str']; ?>)</span></span></td>
                  <?php } else { ?>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                  <?php } ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
