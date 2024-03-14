<?php

defined('ABSPATH') or die('No script kiddies please!');

$error = '';

$sirvAPIClient = sirv_getAPIClient();
$isMuted = $sirvAPIClient->isMuted();
if ($isMuted) {
  $error = $sirvAPIClient->getMuteError();
}

$sirvStatus = $sirvAPIClient->preOperationCheck();

if ($sirvStatus) {
  $isMultiCDN = false;
  //$is_direct = get_option('SIRV_NETWORK_TYPE') == "2" ? true : false;

  $accountInfo = $sirvAPIClient->getAccountInfo();
  if (!empty($accountInfo)) {

    $isMultiCDN = count((array) $accountInfo->aliases) > 1 ? true : false;
    //$is_direct = (isset($accountInfo->aliases->{$accountInfo->alias}->cdn) && $accountInfo->aliases->{$accountInfo->alias}->cdn) ? false : true;
    $sirvCDNurl = get_option('SIRV_CDN_URL');


    update_option('SIRV_ACCOUNT_NAME', $accountInfo->alias);
    //update_option('SIRV_NETWORK_TYPE', (isset($accountInfo->aliases->{$accountInfo->alias}->cdn) && $accountInfo->aliases->{$accountInfo->alias}->cdn) ? 1 : 2);
    //update_option( 'SIRV_NETWORK_TYPE', (isset($accountInfo->cdnURL) ? 1 : 2) );
    update_option('SIRV_FETCH_MAX_FILE_SIZE', $accountInfo->fetching->maxFilesize);
    if (empty($sirvCDNurl) || !$isMultiCDN) {
      update_option('SIRV_CDN_URL', isset($accountInfo->cdnURL) ? $accountInfo->cdnURL : $accountInfo->alias . '.sirv.com');
    }

    $storageInfo = sirv_getStorageInfo();
  }
}
?>

<div class="sirv-tab-content sirv-tab-content-active">
  <?php if ($isMuted || $sirvStatus) { ?>
    <h1>Account info</h1>
    <div class="sirv-s3credentials-wrapper">
      <div class="sirv-optiontable-holder" style="<?php if ($error) echo 'width: 700px;'; ?>">
        <div class="sirv-error"><?php if ($error) echo '<div id="sirv-account" class="sirv-message error-message">' . $error . '</div>'; ?></div>
        <?php if ($sirvStatus) { ?>
          <table class="optiontable form-table">
            <tr>
              <th><label>Account</label></th>
              <td><span><?php echo $storageInfo['account']; ?></span></td>
            </tr>
            <tr>
              <th><label>Plan</label></th>
              <td><span><?php echo $storageInfo['plan']['name']; ?>&nbsp;&nbsp;</span><a target="_blank" href="https://my.sirv.com/#/account/billing/plan">Upgrade plan</a></td>
            </tr>
            <tr>
              <th><label>Allowance</label></th>
              <td><span><?php echo $storageInfo['storage']['allowance_text'] . ' storage, ' . $storageInfo['plan']['dataTransferLimit_text'] . ' monthly transfer'; ?></span></td>
            </tr>
            <tr>
              <th><label>User</label></th>
              <td><span><?php echo htmlspecialchars(get_option('SIRV_ACCOUNT_EMAIL')); ?> </span>&nbsp;&nbsp;<a class="sirv-disconnect" href="#">Disconnect</a></td>
            </tr>
            <tr>
              <th><label>Domain</label></th>
              <td>
                <span><?php echo htmlspecialchars(get_option('SIRV_CDN_URL')); ?></span>
              </td>
            </tr>
            <!--  <tr>
              <th>Calc images storage size:</th>
              <td>
                <style>
                  .div-flex {
                    display: flex;
                    flex-direction: column;
                  }

                  .tst-row {
                    display: flex;
                    flex-direction: row;
                  }

                  .tst-row .val {
                    margin-left: 5px;
                  }
                </style>
                <div class="div-flex">
                  <div class="tst-row">
                    <div class="lab">Time: </div>
                    <div class="val v-time"></div>
                  </div>
                  <div class="tst-row">
                    <div class="lab">Count: </div>
                    <div class="val v-count"></div>
                  </div>
                  <div class="tst-row">
                    <div class="lab">Size: </div>
                    <div class="val v-size"></div>
                  </div>
                  <div style="width: 95px;" class="button-primary storage-size-test">Run calc size</div>
                </div>
              </td>
            </tr> -->
          </table>
        <?php } ?>
      </div>
    </div>
  <?php } else { ?>
    <h2>Connect your Sirv account</h2>
    <div class="sirv-connect-account-wrapper">
      <div class="sirv-optiontable-holder">
        <div class="sirv-error"></div>
        <table class="optiontable form-table">
          <tr class="sirv-field">
            <th colspan="2">
              <label class="sirv-acc-label">Don't have an account?</label> <a href="#" class="sirv-switch-acc-login">Create account</a>
            </th>
            <!-- <td>
                  <input class="sirv-switch" type="checkbox" id="switch" checked /><label for="switch">Toggle</label>
                </td> -->
          </tr>
          <tr class="sirv-block-hide sirv-field">
            <th><label class="required">First & last Name</label></th>
            <td><input class="regular-text" type="text" name="SIRV_NAME" value="" placeholder="Firstname Lastname"></td>
          </tr>
          <tr class="sirv-field">
            <th><label class="required">Email</label></th>
            <td><input class="regular-text" type="text" name="SIRV_EMAIL" value="" placeholder="email@example.com"></td>
          </tr>
          <tr class="sirv-field">
            <th><label class="required sirv-pass-field">Password</label></th>
            <td style="position: relative;">
              <input class="regular-text input password-input sirv-pass" type="password" name="SIRV_PASSWORD" value="">
              <button type="button" class="sirv-toogle-pass button" data-toggle="0" aria-label="Show password">
                <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
              </button>
            </td>
          </tr>
          <tr class="sirv-block-hide sirv-field">
            <th><label class="required sirv-acc-field">Account name</label></th>
            <td>
              <input class="regular-text" type="text" name="SIRV_ACCOUNT_NAME" value="" placeholder="yourcompanyname">
              <span>Pick a name to suit your business. At least 6 characters. Hyphens allowed.</span>
            </td>
          </tr>
          <tr class="sirv-select" style="display:none">
            <th><label class="required">Select account</label></th>
            <td>
              <select name="sirv_account">
                <option value="none">Choose account...</option>
              </select>
            </td>
          </tr>
          <tr class="sirv-otp-code" style="display:none">
            <th><label class="required">2FA code</label></th>
            <td><input class="regular-text" type="text" name="SIRV_OTP_TOKEN" value="" placeholder="6 digit authenticator code"></td>
          </tr>
          <tr>
            <th></th>
            <td>
              <div class="sirv-account-button-wrap">
                <input type="button" class="button-primary sirv-login-action sirv-init" value="Connect account">
                <div class="sirv-load-acc-spinner">
                  <span class="sirv-traffic-loading-ico"></span>
                  <span> Connecting to <span class="sirv-loading-acc-name"></span> account...</span>
                </div>
              </div>
            </td>
          </tr>
          <tr class="sirv-block-hide">
            <th></th>
            <td colspan="2">
              <!-- <span class="sirv-new-acc-text">Start a 30 day free trial, with 5GB storage & 20GB transfer.
                Then autoswitch to a free plan or upgrade to a <a href="https://sirv.com/pricing/">paid plan</a>.</span> -->
              <span class="sirv-new-acc-text">
                No credit card needed. Enjoy 5GB free storage & 20GB transfer for 30 days. Then choose a <a target="_blank" href="https://sirv.com/pricing/">free or paid plan</a>.
                By signing up, you agree to our <a target="_blank" href="https://sirv.com/terms/">Terms of Service</a>.
              </span>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <br>
  <?php } ?>

  <?php if ($sirvStatus && !empty($storageInfo)) { ?>
    <div class="sirv-tab-content sirv-tab-content-active" id="sirv-stats">
      <div class="sirv-stats-container">
        <div class="sirv-stats-messages"></div>
        <h2>Stats</h2>
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
                <?php
                //sirv_debug_msg($storageInfo['limits']);
                foreach ($storageInfo['limits'] as $limit) {
                  $is_limit_reached = ((int) $limit['count'] >= (int) $limit['limit'] && (int) $limit['limit'] > 0) ? 'style="color: red;"' : '';
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
</div>
