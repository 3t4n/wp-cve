<?php
$storageInfo = sirv_getStorageInfo();
?>

<h2>Synchronization</h2>
<p class="sirv-options-desc">Copy your WordPress media library to Sirv, for supreme optimization and fast CDN delivery.</p>
<div class="sirv-optiontable-holder">
  <table class="optiontable form-table">
    <?php if (get_option('SIRV_ENABLE_CDN') != 1) { ?>
      <tr>
        <th class="no-padding" colspan="2">
          <div class="sirv-message warning-message">
            <span style="font-size: 15px;font-weight: 800;">Note:</span> <a class="sirv-show-settings-tab">network status</a> is currently Disabled.
          </div>
        </th>
      </tr>
    <?php } ?>
    <tr>
      <th class="sirv-sync-messages no-padding" colspan="2">
        <?php if ($error) echo '<div id="sirv-sync-message" class="sirv-message error-message">' . $error . '</div>'; ?>
      </th>
    </tr>
    <tr>
      <td colspan="2">
        <h3>Status</h3>
        <p class="sirv-options-desc">Images are copied to Sirv the first time they are viewed, which can take 1-2 seconds per image. To perform a full synchronization now, click Sync images:</p>
      </td>
    </tr>
    <tr class="small-padding">
      <th colspan="2">
        <div class="sirv-sync-images-progress-block">
          <div class="sirv-progress">
            <div class="sirv-progress__text">
              <div class="sirv-progress__text--percents"><?php echo $cacheInfo['progress'] . '%'; ?></div>
              <div class="sirv-progress__text--complited"><span><?php echo $cacheInfo['q_s'] . ' out of ' . $cacheInfo['total_count_s']; ?></span> images completed</div>
            </div>
            <!-- <div class="sirv-progress__bar <?php if ($isAllSynced) echo 'sirv-failed-imgs-bar'; ?>"> -->
            <div class="sirv-progress__bar">
              <div class="sirv-progress__bar--line-complited sirv-complited" style="width: <?php echo $cacheInfo['progress_complited'] . '%;'; ?>"></div>
              <div class="sirv-progress__bar--line-queued sirv-queued" style="width: <?php echo $cacheInfo['progress_queued'] . '%;'; ?>"></div>
              <div class="sirv-progress__bar--line-failed sirv-failed" style="width: <?php echo $cacheInfo['progress_failed'] . '%;'; ?>"></div>
            </div>
          </div>
          <?php if (!$isMuted) { ?>
            <!-- <div class="sirv-sync-button-container">
              <input type="button" name="sirv-sync-images" class="button-primary sirv-sync-images" value="<?php echo $sync_button_text; ?>" <?php echo $is_sync_button_disabled; ?> />
            </div> -->
          <?php } ?>
        </div>
        <?php
        $syncedClearCacheActionShow = $isSynced ? '' : 'display: none;';
        $failedClearCacheActionShow = $isFailed ? '' : 'display: none;';
        ?>
        <table class="sirv-progress-data">
          <tbody>
            <tr class="sirv-progress-data-first-row">
              <td>
                <div class="sirv-progress-data__label sirv-complited"></div>
              </td>
              <td>Synced</td>
              <td>
                <div class="sirv-progress-data__complited--text"><?php echo $cacheInfo['q_s']; ?></div>
              </td>
              <td>
                <div class="sirv-progress-data__complited--size"><?php echo $cacheInfo['size_s']; ?></div>
              </td>
              <td>
                <div class="sirv-synced-clear-cache-action" style="<?php echo $syncedClearCacheActionShow; ?>">
                  <a href="#" class="sirv-clear-cache" data-type="synced">Clear cache</a>
                  <span class="sirv-traffic-loading-ico" style="display: none;"></span>
                </div>
              </td>
            </tr>
            <tr class="sirv-progress-data-second-row">
              <td>
                <div class="sirv-progress-data__label sirv-queued"></div>
              </td>
              <td>
                <div style="display: flex; align-content: center;">
                  <span>Queued</span>&nbsp;
                  <div class="sirv-tooltip">
                    <!-- <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i> -->
                    <span style="cursor: pointer;">( ? )</span>
                    <span class="sirv-tooltip-text sirv-no-select-text">
                      Images waiting to be synced. Up to 6 attempts will be made, either as a pre-sync or as visitors browse your site.
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <div class="sirv-progress-data__queued--text"><?php echo $cacheInfo['queued_s']; ?></div>
              </td>
              <td></td>
              <td></td>
            </tr>
            <tr class="sirv-progress-data-third-row">
              <td>
                <div class="sirv-progress-data__label sirv-failed"></div>
              </td>
              <td>Failed</td>
              <td>
                <div class="sirv-progress-data__failed--text"><?php echo $cacheInfo['FAILED']['count_s']; ?></div>&nbsp;&nbsp;&nbsp;
              </td>
              <td>
                <div class="failed-images-block" style="<?php echo $is_show_failed_block; ?>">
                  <span class=" sirv-traffic-loading-ico" style="display: none;"></span><a href="#">Show</a>
                </div>
              </td>
              <td>
                <div class="sirv-failed-clear-cache-action" style="<?php echo $failedClearCacheActionShow; ?>">
                  <a href="#" class="sirv-clear-cache" data-type="failed">Clear cache</a>
                  <span class="sirv-traffic-loading-ico" style="display: none;"></span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </th>
    </tr>
    <tr class="sync-errors-wrap">
      <th colspan="2">
        <div class="sync-errors">
          <table style="width:830px;" class="optiontable form-table sirv-form-table">
            <thead>
              <tr>
                <td style="width: 65%;"><b>Error message</b></td>
                <td><b>Count</b></td>
                <td></td>
              </tr>
            </thead>
            <tbody class='sirv-fetch-errors'></tbody>
          </table>
        </div>
      </th>
    </tr>
    <?php if (!$isMuted) {
      $fetch_limit = isset($storageInfo['limits']['fetch:file']['limit']) ? $storageInfo['limits']['fetch:file']['limit'] : 2000;
    ?>
      <tr class="sirv-processing-message" style='display: none;'>
        <td colspan="2">
          <span class="sirv-traffic-loading-ico"></span><span class="sirv-queue">Processing (1/3): calculating folders...</span>
          <p style="margin: 10px 0 !important; font-weight: bold; color: #8a6d3b;">
            Keep this page open until synchronisation reaches 100%. Your account can sync <?php echo $fetch_limit; ?> images per hour (<a target="_blank" href="admin.php?page=<?php echo SIRV_PLUGIN_RELATIVE_SUBDIR_PATH ?>submenu_pages/account.php">check current usage</a>).
            If sync stops, refresh this page and resume the sync.
            </?php>
        </td>
      </tr>
      <?php
      $g_show = $isGarbage ? '' : 'style="display: none;"';
      ?>
      <tr class="sirv-discontinued-images" <?php echo $g_show; ?>>
        <td class="no-padding" colspan="2">
          <div class="sirv-message warning-message">
            <span style="font-size: 15px;font-weight: 800;">Recommendation:</span> <span class="sirv-old-cache-count"><?php echo $cacheInfo['garbage_count'] ?></span> images in plugin database no longer exist.&nbsp;&nbsp;
            <input type="button" name="optimize_cache" class="button-primary sirv-clear-cache" data-type="garbage" value="Clean up" />&nbsp;
            <span class="sirv-traffic-loading-ico" style="display: none;"></span>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="sirv-sync-controls">
            <?php if (!$isMuted) { ?>
              <input type="button" name="sirv-sync-images" class="button-primary sirv-sync-images" value="<?php echo $sync_button_text; ?>" <?php echo $is_sync_button_disabled; ?> />
              <!-- <input type="button" name="sirv-check-cache" class="button-secondary" value="Check cache" /> -->
            <?php } ?>
          </div>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>

<!-- Diff settings block END -->
<div class="sirv-optiontable-holder sirv-sync-delete-settings-wrapper">
  <table class="optiontable form-table">
    <tbody>
      <tr>
        <td colspan="2">
          <h3>Settings</h3>
          <!-- <p class="sirv-options-desc">Some description here</p> -->
        </td>
      </tr>
      <tr>
        <th>
          Authentication
        </th>
        <td>
          <label><input type="checkbox" name="SIRV_HTTP_AUTH_CHECK" value="1" <?php checked('1', get_option('SIRV_HTTP_AUTH_CHECK'), true);  ?>>HTTP authentication</label><br>
          <span class="sirv-option-responsive-text">In case your server requires HTTP authentication.</span>
          <div class="sirv-http-auth-credentials">
            <label>User<input type="text" placeholder="Server username" name="SIRV_HTTP_AUTH_USER" value="<?php echo get_option('SIRV_HTTP_AUTH_USER'); ?>"></label><br>
            <label>Password<input type="text" placeholder="Server password" name="SIRV_HTTP_AUTH_PASS" value="<?php echo get_option('SIRV_HTTP_AUTH_PASS'); ?>"></label>
          </div>
        </td>
      </tr>
      <tr>
        <th>
          <label>Sync images on upload</label>
        </th>
        <td>
          <label>
            <input type="radio" name="SIRV_SYNC_ON_UPLOAD" value='on' "<?php checked('on', get_option('SIRV_SYNC_ON_UPLOAD'), true);  ?>">Enable
          </label><br>
          <label>
            <input type="radio" name="SIRV_SYNC_ON_UPLOAD" value='off' "<?php checked('off', get_option('SIRV_SYNC_ON_UPLOAD'), true);  ?>">Disable
          </label><br>
          <span class="sirv-option-responsive-text">Sync images to Sirv immediately once uploaded to WordPress media library.</span>
        </td>
      </tr>
      <tr>
        <th></th>
        <td>
          <input type="submit" name="submit" class="button-primary sirv-save-settings" value="<?php _e('Save settings') ?>" />
        </td>
      </tr>
    </tbody>
  </table>
</div>
<!-- Diff settings block END -->
<div class="sirv-optiontable-holder sirv-sync-delete-settings-wrapper">
  <table class="optiontable form-table">
    <tbody>
      <tr>
        <td colspan="2">
          <h3>Image deletion<sup><span style="color: orange;">beta</span></sup></h3>
          <p class="sirv-options-desc">WordPress generates thumbnails on upload, which you may not need. Deselect the unwanted thumbs to save processing power and storage space.</p>
        </td>
      </tr>
      <tr>
        <th>
          Manage WP thumbnails
        </th>
        <td>
          <?php
          $wp_sizes = sirv_get_image_sizes(false);
          $wp_sizes_count = count(resizeHelper::getUniqueSizes($wp_sizes));
          ksort($wp_sizes);
          ?>
          <label>
            <input type="radio" name="SIRV_PREVENT_CREATE_WP_THUMBS" value='on' "<?php checked('on', get_option('SIRV_PREVENT_CREATE_WP_THUMBS'), true);  ?>">Enable
          </label><br>
          <label>
            <input type="radio" name="SIRV_PREVENT_CREATE_WP_THUMBS" value='off' "<?php checked('off', get_option('SIRV_PREVENT_CREATE_WP_THUMBS'), true);  ?>">Disable
          </label><br><br>
          <span style="display:block;" class="sirv-option-responsive-text">Your library has approximately <?php echo $cacheInfo['SYNCED']['count'] *  $wp_sizes_count; ?> thumbnails of <?php echo $wp_sizes_count; ?> different sizes, from <?php echo $cacheInfo['SYNCED']['count']; ?> master images, identified by the Sirv plugin.</span><br>
          <span style="display:block;" class="sirv-option-responsive-text">Choose which thumbnails you would like to delete:</span>
        </td>
      <tr>
        <th></th>
        <td colspan="2">
          <div class="sirv-crop-row sirv-check-all-thumbs">
            <span class="sirv-crop-row__title">Thumbnail</span>
            <div style="min-width: 150px; margin-right: 10px;">
              <label>
                <input type="checkbox" class="sirv-thumb-size-delete-all">
                <span class="sirv-crop-row__title">Delete/Create all</span>
              </label>
            </div>
          </div>
          <div class="sirv-scrollbox-parent">
            <div class="sirv-shadow sirv-shadow-top"></div>
            <div class="sirv-shadow sirv-shadow-bottom"></div>
            <div class="sirv-thumbs-sizes sirv-scrollbox">
              <?php
              $prevented_sizes = json_decode(get_option('SIRV_PREVENTED_SIZES'), true);
              //$critical_sizes = ['medium', 'thumbnail'];
              $critical_sizes = [];
              //$critical_msg = "Strongly do not recomended delete this size as it is using in admin area";

              foreach ($wp_sizes as $size_name => $size) {
                $msg = in_array($size_name, $critical_sizes) ? ' (<span style="display: initial;color: red;">' . $critical_msg . '</span>)' : '';
                $is_prevent_size = isset($prevented_sizes[$size_name]) ? 'delete' : 'keep';

                $size_str = $size_name . $msg .  "<span>" . $size['width'] . "x" . $size['height'] . "</span>";
              ?>
                <div class="sirv-crop-row">
                  <span class="sirv-crop-row__title"><?php echo $size_str; ?></span>
                  <div class="sirv-crop-row__checkboxes">
                    <input type="radio" class="sirv-thumb-size" data-size="<?php echo $size['width'] . "x" . $size['height']; ?>" data-name="<?php echo $size_name; ?>" name="<?php echo $size_name; ?>_prevent" id="<?php echo $size_name; ?>_prevent_1" value="delete" <?php checked('delete', $is_prevent_size, true); ?>><label class="fchild" for="<?php echo $size_name; ?>_prevent_1">Delete</label>
                    <input type="radio" class="sirv-thumb-size" data-size="<?php echo $size['width'] . "x" . $size['height']; ?>" data-name="<?php echo $size_name; ?>" name="<?php echo $size_name; ?>_prevent" id="<?php echo $size_name; ?>_prevent_2" value="keep" <?php checked('keep', $is_prevent_size, true); ?>><label for="<?php echo $size_name; ?>_prevent_2">Create</label>
                  </div>
                </div>
              <?php } ?>
              <input type="hidden" id="sirv-prevented-sizes-hidden" name="SIRV_PREVENTED_SIZES" value="<?php echo htmlspecialchars(json_encode($prevented_sizes, JSON_FORCE_OBJECT)); ?>">
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th>
        </th>
        <td>
          <div class="sirv-save-prevented-sizes-wrap">
            <input type="button" class="button-primary sirv-save-prevented-sizes" value="Save updated settings">
          </div>
        </td>
      </tr>
      <tr>
        <th class="sirv-thumb-messages no-padding" colspan="2">
        </th>
      </tr>
      <tr>
        <th></th>
        <td>
          <?php
          $isPreventOptionDisabled = get_option('SIRV_PREVENT_CREATE_WP_THUMBS') === 'off' ? true : false;
          $thumbs_data = json_decode(get_option('SIRV_THUMBS_DATA'), true);
          $isOperationContinue = in_array($thumbs_data['status'], array('processing', 'pause')) ? true : false;
          $disable_thumbs_run_button = $isOperationContinue || $isPreventOptionDisabled ? ' disabled="disabled" ' : '';
          $showProgresBlock = $isOperationContinue ? '' : 'style="display: none;"';
          $contunue_msg_show = $isOperationContinue ? '' : 'style="display: none;"';
          $type = empty($thumbs_data['type']) ? 'Operation not set' : $thumbs_data['type'];
          $operationed_txt = $type == 'delete' ? 'Deleted' : 'Regenerated';

          ?>
          <div class="sirv-progress-wrapper" <?php echo $showProgresBlock; ?>>
            <div class="sirv-progress-wrapper-text">
              <div class="sirv-progress-wrapper-text__percents"><span class="sirv-thumbs-progress-percents"><?php echo $thumbs_data['percent_finished']; ?></span>%</div>
              <div class="sirv-progress-wrapper-text__complited">
                <span>
                  <span class="sirv-thumbs-processed_ids"><?php echo $thumbs_data['processed_ids']; ?></span>
                  out of <span class="sirv-thumbs-cached_ids"><?php echo $thumbs_data['ids_count']; ?></span>
                </span> images processed
              </div>
            </div>
            <div class="sirv-progress-wrapper-progressbar">
              <div class="sirv-progress-wrapper-progressbar_bar sirv-thumbs-progressbar" style="width: <?php echo $thumbs_data['percent_finished'] ?>%"></div>
            </div>
            <div class="sirv-progress-wrapper-bottom-text">
              <span> <span class="sirv-thumbs-operation-type"><?php echo $operationed_txt; ?></span>: <span class="sirv-thumbs-processed-files-count"><?php echo $thumbs_data['files_count']; ?></span> thumbs</span>
            </div>
          </div>
          <input type="button" name="sirv-delete-wp-thumbs" data-pause="false" data-type="delete" class="button-primary sirv-delete-wp-thumbs" value="Delete unwanted thumbnails" <?php echo $disable_thumbs_run_button; ?>>
          <input type="button" name="sirv-regenetate-wp-thumbs" data-pause="false" data-type="regenerate" class="button-primary sirv-regenerate-wp-thumbs" value="Regenerate wanted thumbnails" <?php echo $disable_thumbs_run_button; ?>><br><br>
          <span class="sirv-option-responsive-text">Thumbnails marked "Delete" will not be created when you upload new images to your WordPress media library.</span><br>
        </td>
      </tr>

      <tr class="sirv-processing-thumb-images-msg" <?php echo $contunue_msg_show ?>>
        <td class="no-padding" colspan="2">
          <div class="sirv-message warning-message">
            <span style="font-size: 15px;font-weight: 800;">Notice:</span> Plugin detect that you did not finish <?php echo $thumbs_data['type']; ?> operation. You may continue it or cancel.<br>
            <div style="padding-top: 10px;">
              <input type="button" name="sirv-thumbs-continue-processing" class="button-primary sirv-thumbs-continue-processing" data-type="<?php echo $thumbs_data['type']; ?>" value="Continue operation" />&nbsp;
              <input type="button" name="sirv-thumbs-cancel-processing" class="button-primary sirv-thumbs-cancel-processing" value="Cancel" />&nbsp;
            </div>
            <!-- <span class="sirv-traffic-loading-ico" style="display: none;"></span> -->
          </div>
        </td>
      </tr>
      <tr>
        <th>
          <label>Delete from Sirv</label>
        </th>
        <td>
          <label>
            <input type="radio" name="SIRV_DELETE_FILE_ON_SIRV" value='1' "<?php checked('1', get_option('SIRV_DELETE_FILE_ON_SIRV'), true);  ?>">Enable
          </label><br>
          <label>
            <input type="radio" name="SIRV_DELETE_FILE_ON_SIRV" value='2' "<?php checked('2', get_option('SIRV_DELETE_FILE_ON_SIRV'), true);  ?>">Disable
          </label><br>
          <span class="sirv-option-responsive-text">If master image deleted from WordPress Media Library, delete from Sirv.</span>
        </td>
      </tr>
      <tr>
        <th></th>
        <td>
          <input type="submit" name="submit" class="button-primary sirv-save-settings" value="<?php _e('Save settings') ?>" />
        </td>
      </tr>
    </tbody>
  </table>
</div>
<!-- Diff settings block END -->

<?php
$css_sync_data = json_decode(get_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA'), true);
$scan_type = isset($css_sync_data['scan_type']) ? $css_sync_data['scan_type'] : 'theme';
$custom_path = isset($css_sync_data['custom_path']) ? $css_sync_data['custom_path'] : '';
$isCustomPathShow = $scan_type == 'custom' ? ' style="display: table-row;"' : '';
$images_info = sirv_show_css_images_info($css_sync_data);
$skip_images_str = '';
$hide_skip_data_block = 'style="display:none;"';
if (!empty($images_info['skip_data'])) {
  $skip_images_str = sirv_skipped_images_to_str($css_sync_data);
  $hide_skip_data_block = '';
}
?>
<div class="sirv-optiontable-holder sirv-sync-css-images-wrapper">
  <table class="optiontable form-table">
    <tbody>
      <tr>
        <td colspan="2">
          <h3>Sync CSS images<sup><span style="color: orange;">beta</span></sup></h3>
          <p class="sirv-options-desc">Use Sirv to deliver any CSS background images located on your domain.</p>
        </td>
      </tr>
      <tr>
        <th>
          <label>CSS location</label>
        </th>
        <td>
          <label class="">
            <input class="sirv-custom-backcss-path-rb" type="radio" name="css_location" value="theme" <?php checked('theme', $scan_type, true) ?>><b>Active theme</b> - your CSS is normally part of your theme.
          </label>
          <br>
          <label class="sirv-ec-all-item">
            <input class="sirv-custom-backcss-path-rb" type="radio" name="css_location" value="custom" <?php checked('custom', $scan_type, true) ?>><b>Folder</b> - enter path to your CSS, if outside your theme.
          </label>
        </td>
      </tr>
      <tr class="sirv-custom-backcss-path-text-tr" <?php echo $isCustomPathShow; ?>>
        <th></th>
        <td colspan="2">
          <div class="sirv-custom-backcss-path-text-wrap">
            <div>
              <input type="text" name="" id="sirv-custom-backcss-path-text" value="<?php echo $custom_path; ?>" placeholder="Enter path to CSS folder">
              <span class="sirv-input-const-text"><?php echo wp_normalize_path(ABSPATH); ?></span>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th></th>
        <td colspan="2">
          <input type="button" name="sync_css" class="button-primary sync-css" value="Scan CSS for images" />&nbsp;
          <span class="sirv-traffic-loading-ico" style="display: none;"></span>
          <span class="sirv-show-empty-view-result" style="display: none;"></span>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table class="sirv-css-images-sync-data small-padding" style="width: 100%;">
            <tbody>
              <tr>
                <th>
                  <label>Scanned theme/folder</label>
                </th>
                <td>
                  <textarea disabled class="sirv-css-sync-data-theme" value="<?php echo htmlspecialchars($css_sync_data['theme']); ?>"><?php echo $css_sync_data['theme']; ?></textarea>
                </td>
              </tr>
              <tr>
                <th>
                  <label>Image domain</label>
                </th>
                <td>
                  <span class="sirv-css-sync-data-domain"><?php echo $css_sync_data['img_domain']; ?></span>
                </td>
              </tr>
              <tr>
                <th>
                  <label>Last scan</label>
                </th>
                <td>
                  <span class="sirv-css-sync-data-date"><?php echo $css_sync_data['last_sync_str']; ?></span>
                </td>
              </tr>
              <tr>
                <th>
                  <label>Images synced</label>
                </th>
                <td>
                  <!-- <span class="sirv-css-sync-data-img-count"><?php echo $css_sync_data['img_count']; ?></span> -->
                  <span class="sirv-css-sync-data-img-count"><?php echo $images_info['sync_data']; ?></span>
                  <div class="sirv-skipped-images-wrap" <?php echo $hide_skip_data_block; ?>>
                    <span class="sirv-css-sync-data-img-count-skipped">
                      <?php echo $images_info['skip_data']; ?>
                    </span>
                    <div class="sirv-tooltip">
                      <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
                      <span class="sirv-tooltip-text sirv-no-select-text">Images were either:<br>
                        - On another domain<br>
                        - Inaccessible<br>
                        - Link couldn't be followed</span>
                    </div>
                    <a <?php //echo $hide_skip_data_block;
                        ?> class="sirv-hide-show-a sirv-show-skip-data-list" data-status="false" data-selector=".sirv-skip-images-list" data-show-msg="Show list" data-hide-msg="Hide list" data-icon-show="dashicons dashicons-arrow-right-alt2" data-icon-hide="dashicons dashicons-arrow-down-alt2">
                      <span class="dashicons dashicons-arrow-right-alt2"></span>
                      Show list
                    </a>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <textarea class="sirv-font-monospace sirv-skip-images-list" value="<?php echo $skip_images_str; ?>" rows="5" readonly><?php echo $skip_images_str; ?></textarea>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <p style="margin-bottom: 5px;">All images found will be served by Sirv. <a class="sirv-hide-show-a" data-status="false" data-selector=".sirv-css-sync-bg-img-txtarea-wrap" data-show-msg="Show CSS code" data-hide-msg="Hide CSS code" data-icon-show="dashicons dashicons-arrow-right-alt2" data-icon-hide="dashicons dashicons-arrow-down-alt2"><span class="dashicons dashicons-arrow-right-alt2"></span>Show CSS code</a></p>
          <div class="sirv-css-sync-bg-img-txtarea-wrap">
            <textarea class="sirv-font-monospace" name="SIRV_CSS_BACKGROUND_IMAGES" rows="10" value="<?php echo htmlspecialchars(get_option('SIRV_CSS_BACKGROUND_IMAGES')); ?>"><?php echo get_option('SIRV_CSS_BACKGROUND_IMAGES'); ?></textarea>
            <input type="submit" name="submit" class="sirv-save-css-code button-primary sirv-save-settings" value="<?php _e('Save CSS code') ?>" disabled />
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
