<div class="sirv-network-wrapper">
  <h2>Serve WordPress media library from Sirv</h2>

  <p class="sirv-options-desc">
    Existing and future images in the WordPress media library will be copied to Sirv, optimized, resized and rapidly served from the CDN.
  </p>

  <?php
  $enableCDNOption = get_option('SIRV_ENABLE_CDN');
  $isEnableCDN = $enableCDNOption === '1' ? true : false;
  $disableParseOptions = $isEnableCDN ? '' : "disabled";
  ?>

  <div class="sirv-optiontable-holder">
    <div class="sirv-error"><?php if ($error) echo '<div id="sirv-settings-messages" class="sirv-message error-message">' . $error . '</div>'; ?></div>
    <table class="optiontable form-table">
      <tr>
        <th>
          <label>Serve WordPress media from Sirv</label>
        </th>
        <td>
          <label><input type="radio" name="SIRV_ENABLE_CDN" value='1' "<?php checked(1, $enableCDNOption, true); ?>">Enable</label>
          <label><input type="radio" name="SIRV_ENABLE_CDN" value='2' "<?php checked(2, $enableCDNOption, true); ?>">Disable</label>
        </td>
        <td>
          <span class="sirv-status <?php echo sirv_getStatus(); ?>"></span>
        </td>
      </tr>
      <tr>
        <th>
          <label>Parse static images</label>
        </th>
        <td>
          <label><input type="radio" name="SIRV_PARSE_STATIC_IMAGES" value='1' "<?php checked(1, get_option('SIRV_PARSE_STATIC_IMAGES'), true); ?>" <?php echo $disableParseOptions ?>>Enable</label>
          <label><input type="radio" name="SIRV_PARSE_STATIC_IMAGES" value='2' "<?php checked(2, get_option('SIRV_PARSE_STATIC_IMAGES'), true); ?>">Disable</label>
        </td>
        <td>
          <div class="sirv-tooltip">
            <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
            <span class="sirv-tooltip-text sirv-no-select-text">
              Deliver more images from Sirv. This setting looks for images in the HTML page, then serves them from Sirv. It adds some server load, so may be unsuitable for high-traffic websites.
            </span>
          </div>
        </td>
      </tr>
      <tr>
        <th>
          <label>Sync videos</label>
        </th>
        <td>
          <!-- <label>
            <input type="checkbox" name="SIRV_PARSE_VIDEOS" />Serve videos from Sirv CDN instead of WordPress.
          </label> -->
          <label><input type="radio" name="SIRV_PARSE_VIDEOS" value='on' "<?php checked("on", get_option('SIRV_PARSE_VIDEOS'), true); ?>" <?php echo $disableParseOptions ?>>Enable</label>
          <label><input type="radio" name="SIRV_PARSE_VIDEOS" value='off' "<?php checked("off", get_option('SIRV_PARSE_VIDEOS'), true); ?>">Disable</label>
          <span class="sirv-option-responsive-text">Serve videos from Sirv CDN instead of WordPress.</span>
        </td>
        <td>
          <div class="sirv-tooltip">
            <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
            <span class="sirv-tooltip-text sirv-no-select-text">
              Your video will be synced to Sirv. We recommend compressing your video before you upload it to WordPress. <a href="https://handbrake.fr/downloads.php" target="_blank">Handbrake</a> is very effective for compressing video.
            </span>
          </div>
        </td>
      </tr>
      <?php
      //if ($isMultiCDN && !empty($domains) && !$is_direct) {
      if (count($domains) > 1) {
      ?>
        <tr>
          <th><label>Domain</label></th>
          <td>
            <select id="sirv-choose-domain" name="SIRV_CDN_URL">
              <?php
              foreach ($domains as $domain) {
                $selected = '';
                if ($domain == $sirvCDNurl) {
                  $selected = 'selected';
                }
                echo '<option ' . $selected . ' value="' . $domain . '">' . $domain . '</option>';
              }
              ?>
            </select>
          </td>
        </tr>
      <?php } else { ?>
        <input type="hidden" id="sirv-choose-domain-hidden" name="SIRV_CDN_URL" value="<?php echo $sirvCDNurl; ?>">
      <?php } ?>
      <tr>
        <th>
          <label>Folder name on Sirv</label>
        </th>
        <td colspan="2" style="padding: 0;">
          <?php
          $sirv_folder = get_option('SIRV_FOLDER');
          ?>
          <p class="sirv-viewble-option"><span class="sirv--grey"><?php echo htmlspecialchars($sirvCDNurl); ?>/</span><?php echo htmlspecialchars($sirv_folder); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="sirv-option-edit" href="#">Change</a></p>
          <p class="sirv-editable-option" style="display: none;">
            <span class="sirv--grey"><?php echo htmlspecialchars($sirvCDNurl); ?>/</span><input class="regular-text" type="text" name="SIRV_FOLDER" value="<?php echo $sirv_folder; ?>">
          </p>
          <br>
          <div class="sirv-message warning-message sirv-hide sirv-warning-on-folder-change">
            <span style="font-size: 15px;font-weight: 800;">Important!</span><br>Changing folder name will clear the image cache, so images will re-synchronize on first request or use <a class="sirv-show-sync-tab">Sync Images</a> to pre-sync entire library.
          </div>
        </td>
      </tr>
      <tr>
        <th>
        </th>
        <td><input type="submit" name="submit" class="button-primary sirv-save-settings" value="<?php _e('Save Settings') ?>" /></td>
      </tr>
    </table>
  </div>
</div>
<div class="sirv-profiles-wrapper">
  <!-- profiles options-->
  <h2>Image settings</h2>
  <?php
  $useSirvResponsiveOption = get_option('SIRV_USE_SIRV_RESPONSIVE');
  $isShowPlaceholder = $useSirvResponsiveOption == '1' ? true : false;
  $showPlaceholderBlock = $isShowPlaceholder ? 'table-row' : 'none';
  ?>
  <div class="sirv-optiontable-holder">
    <table class="optiontable form-table">
      <tr>
        <th>
          <label style="padding-bottom: 10px;">Lazy loading</label>
        </th>
        <td>
          <label>
            <input type="radio" name="SIRV_USE_SIRV_RESPONSIVE" value='1' "<?php checked('1', $useSirvResponsiveOption);  ?>">Enable
          </label>
          <label>
            <input type="radio" name="SIRV_USE_SIRV_RESPONSIVE" value='2' "<?php checked('2', $useSirvResponsiveOption);  ?>">Disable
          </label>
          <span class="sirv-option-responsive-text">Load images on demand & scale them perfectly.</span>
          <div class="sirv-responsive-msg sirv-message warning-message">
            <div>
              Deactivate any other lazy loading plugins. After saving, check that your images display as expected.
            </div>
          </div>
        </td>
      </tr>
      <tr class="sirv-hide-placeholder" style="display:<?php echo $showPlaceholderBlock; ?>;">
        <th><label>Lazy placeholder</label></th>
        <td>
          <label><input type="radio" name="SIRV_RESPONSIVE_PLACEHOLDER" value='image' "<?php checked('image', get_option('SIRV_RESPONSIVE_PLACEHOLDER'), true); ?>"><b>Image</b> - best experience.</label>
          <label><input type="radio" name="SIRV_RESPONSIVE_PLACEHOLDER" value='grey_shape' "<?php checked('grey_shape', get_option('SIRV_RESPONSIVE_PLACEHOLDER'), true); ?>"><b>Grey background</b> - most efficient.</label>
          <label><input type="radio" name="SIRV_RESPONSIVE_PLACEHOLDER" value='blurred' "<?php checked('blurred', get_option('SIRV_RESPONSIVE_PLACEHOLDER'), true); ?>"><b>Blurred image</b> - popular effect.</label>
          <span class="sirv-option-responsive-text">Display background while image loads.</span>
        </td>
      </tr>
      <tr>
        <th>
          <label>Image profile</label>
        </th>
        <td>
          <!-- <span class="sirv-traffic-loading-ico sirv-shortcodes-profiles"></span> -->
          <select id="sirv-cdn-profiles">
            <?php if (isset($profiles)) echo sirv_renderProfilesOptopns($profiles); ?>
          </select>
          <input type="hidden" id="sirv-cdn-profiles-val" name="SIRV_CDN_PROFILES" value="<?php echo get_option('SIRV_CDN_PROFILES'); ?>">
        </td>
        <td>
          <div class="sirv-tooltip">
            <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
            <span class="sirv-tooltip-text sirv-no-select-text">Style your images with watermarks, text and other customizations using one of <a target="_blank" href="https://my.sirv.com/#/profiles/">your profiles</a>. Learn <a target="_blank" href="https://sirv.com/help/articles/dynamic-imaging/profiles/">about profiles</a></span>
          </div>
        </td>
      </tr>
      <tr>
        <th>
          <label>Sirv shortcode profile</label>
        </th>
        <td>
          <!-- <span class="sirv-traffic-loading-ico sirv-shortcodes-profiles"></span> -->
          <select id="sirv-shortcodes-profiles">
            <?php if (isset($profiles)) echo sirv_renderProfilesOptopns($profiles); ?>
          </select>
          <input type="hidden" id="sirv-shortcodes-profiles-val" name="SIRV_SHORTCODES_PROFILES" value="<?php echo get_option('SIRV_SHORTCODES_PROFILES'); ?>">
        </td>
        <td>
          <div class="sirv-tooltip">
            <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
            <span class="sirv-tooltip-text sirv-no-select-text">Apply one of <a target="_blank" href="https://my.sirv.com/#/profiles/">your profiles</a> for watermarks, text and other image customizations. Learn <a target="_blank" href="https://sirv.com/help/articles/dynamic-imaging/profiles/">about profiles</a>.</span>
          </div>
        </td>
      </tr>
      <tr>
        <th><label>Crop images</label></th>
        <td>
          <a class="sirv-hide-show-a" data-status="false" data-selector=".sirv-crop-wrap" data-show-msg="Show crop options" data-hide-msg="Hide crop options" data-icon-show="dashicons dashicons-arrow-right-alt2" data-icon-hide="dashicons dashicons-arrow-down-alt2"><span class="dashicons dashicons-arrow-right-alt2"></span>Show crop options</a>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="sirv-crop-wrap" style="display: none;">
            <div class="sirv-crop-wrap__desc">
              <span>Show consistently sized images either via crop or adding background.</span>
              <div class="sirv-crop-wrap__img">
                <img src="https://sirv.sirv.com/website/screenshots/wordpress/crop-example.jpg">
              </div>

            </div>
            <?php
            $crop_data = json_decode(get_option('SIRV_CROP_SIZES'), true);
            if (empty($crop_data)) {
              $encoded_default_crop = sirv_get_default_crop();
              update_option('SIRV_CROP_SIZES', $encoded_default_crop);
              $crop_data = json_decode($encoded_default_crop, true);
            }
            $wp_sizes = sirv_get_image_sizes(false);
            ksort($wp_sizes);

            foreach ($wp_sizes as $size_name => $size) {
              $size_str = $size_name . "<span>" . $size['width'] . "x" . $size['height'] . "</span>";
              $cropMethod = isset($crop_data[$size_name]) ? $crop_data[$size_name] : 'none';
            ?>
              <div class="sirv-crop-row">
                <span class="sirv-crop-row__title"><?php echo $size_str; ?></span>
                <div class="sirv-crop-row__checkboxes">
                  <input type="radio" class="sirv-crop-radio" name="<?php echo $size_name; ?>" id="<?php echo $size_name; ?>1" value="none" <?php checked('none', $cropMethod, true); ?>><label class="fchild" for="<?php echo $size_name; ?>1">No crop</label>
                  <input type="radio" class="sirv-crop-radio" name="<?php echo $size_name; ?>" id="<?php echo $size_name; ?>2" value="wp_crop" <?php checked('wp_crop', $cropMethod, true); ?>><label for="<?php echo $size_name; ?>2">Crop</label>
                  <input type="radio" class="sirv-crop-radio" name="<?php echo $size_name; ?>" id="<?php echo $size_name; ?>3" value="sirv_crop" <?php checked('sirv_crop', $cropMethod, true); ?>><label for="<?php echo $size_name; ?>3">Uniform</label>
                </div>
              </div>
            <?php } ?>
            <input type="hidden" id="sirv-crop-sizes" name="SIRV_CROP_SIZES" value="<?php echo htmlspecialchars(get_option('SIRV_CROP_SIZES')); ?>">
          </div>
        </td>
      </tr>
      <tr>
        <th>
        </th>
        <td><input type="submit" name="submit" class="button-primary sirv-save-settings" value="<?php _e('Save Settings') ?>" /></td>
      </tr>
    </table>
  </div>
</div>


<div class="sirv-miscellaneous-wrapper">
  <h2>Miscellaneous</h2>
  <div class="sirv-optiontable-holder">
    <table class="optiontable form-table">
      <tr>
        <th>
          <label>Include Sirv JS</label>
        </th>
        <td>
          <label><input type="radio" name="SIRV_JS" value="2" <?php checked(2, get_option('SIRV_JS'), true); ?>><b>Detect</b> - add script only to pages that require it.</label>
          <label><input type="radio" name="SIRV_JS" value="1" <?php checked(1, get_option('SIRV_JS'), true); ?>><b>All pages</b> - always add script (select this if images are not loading).</label>
          <label><input type="radio" name="SIRV_JS" value="3" <?php checked(3, get_option('SIRV_JS'), true); ?>><b>No pages</b> - don't add script (may break shortcodes & responsive images).</label>
        </td>
      </tr>
      <tr>
        <th>Sirv JS features</th>
        <td>
          <?php

          function sirv_get_js_modules_sizes_str($fileSizes)
          {
            $sizes_str = '';

            if (!is_null($fileSizes['error'])) {
              $sizes_str = "Error: {$fileSizes['error']}";
            } else {
              $sizes_str = "{$fileSizes['compressed_s']} (unzipped {$fileSizes['uncompressed_s']})";
            }

            return $sizes_str;
          }


          $modules = array();
          $loadModules = get_option('SIRV_JS_MODULES');
          if (!empty($loadModules)) {
            $modules = explode(',', $loadModules);
          }
          $url = getValue::getOption('SIRV_JS_FILE');

          $compressedSizeStr = "No module choosed. Please choose any module(s)";
          if (!empty($modules)) {
            $fileSizes = sirv_get_js_compressed_size($url);
            //$compressedSizeStr = !is_null($fileSize['compressed']) ? Utils::getFormatedFileSize($fileSize['compressed']) : 'Error: cannot calc filesize';
            $compressedSizeStr = sirv_get_js_modules_sizes_str($fileSizes);
          }
          $isSirvFullJs = count($modules) == 7;
          $allChecked = $isSirvFullJs ? 'checked' : '';
          $allDisabled = $isSirvFullJs ? 'disabled' : '';

          function sirv_check_js_module($module, $modules)
          {
            if (empty($modules)) return '';

            if (in_array($module, $modules)) {
              return 'checked';
            }
            return '';
          }
          ?>
          <div class="sirv-js-modules">
            <label>
              <input type="checkbox" name="sirv_js_module_all" id="all-js-modules-switch" <?php echo $allChecked; ?> <?php echo $allDisabled; ?>>Select all
            </label><br>
            <hr>
            <label>
              <input type="checkbox" name="sirv_js_module" id="lazyimage" data-module="lazyimage" <?php echo sirv_check_js_module('lazyimage', $modules) ?>>Lazy & responsive images
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="zoom" data-module="zoom" <?php echo sirv_check_js_module('zoom', $modules) ?>>Image zoom
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="spin" data-module="spin" <?php echo sirv_check_js_module('spin', $modules) ?>>360 spin
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="hotspots" data-module="hotspots" <?php echo sirv_check_js_module('hotspots', $modules) ?>>Hotspots
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="video" data-module="video" <?php echo sirv_check_js_module('video', $modules) ?>>Video streaming
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="gallery" data-module="gallery" <?php echo sirv_check_js_module('gallery', $modules) ?>>
              Gallery
            </label><br>
            <label>
              <input type="checkbox" name="sirv_js_module" id="gallery" data-module="model" <?php echo sirv_check_js_module('model', $modules) ?>>
              Model
            </label>
          </div>
          <input type="hidden" id="sirv-js-modules-store" name="SIRV_JS_MODULES" value="<?php echo $loadModules ?>">
          <span class="sirv-option-responsive-text"><b>File size:</b> <span class="sirv-compressed-js-spinner sirv-traffic-loading-ico"></span><span class="sirv-compressed-js-val"><?php echo $compressedSizeStr; ?></span></span><br>
          <span class="sirv-option-responsive-text">Improve optimization by choosing only the features you need. Smaller JS files are faster to load and process.</span>
        </td>
      </tr>
      <tr>
        <th></th>
        <td class="js-modules-messages"></td>
      </tr>
      <tr>
        <th>
          <label>Custom CSS</label>
        </th>
        <td>
          <textarea class="sirv-font-monospace" name="SIRV_CUSTOM_CSS" placeholder="Example:
.here-is-a-style img {
  width: auto !important;
}" value="<?php echo htmlspecialchars(get_option('SIRV_CUSTOM_CSS')); ?>" rows="4"><?php echo get_option('SIRV_CUSTOM_CSS'); ?></textarea>
          <span class="sirv-option-responsive-text">Add styles to fix any rendering conflicts caused by other CSS.</span>
        </td>
      </tr>
      <tr>
        <th>
          <label>Custom gallery script options</label>
        </th>
        <td>
          <span class="sirv-option-responsive-text">Go to the <a href="https://sirv.com/help/viewer/" target="_blank">Sirv Media Viewer designer</a> to create the perfect experience for your store. Paste code from the "Script" tab:</span>
          <textarea class="sirv-font-monospace" name="SIRV_CUSTOM_SMV_SH_OPTIONS" placeholder="Add custom js options for Media Viewer. e.g.
var SirvOptions = {
  zoom: {
    mode: 'deep'
  }
}" value="<?php echo htmlspecialchars(get_option('SIRV_CUSTOM_SMV_SH_OPTIONS')); ?>" rows="4"><?php echo get_option('SIRV_CUSTOM_SMV_SH_OPTIONS'); ?></textarea>
        </td>
      </tr>
      <tr>
        <th>
        </th>
        <td><input type="submit" name="submit" class="button-primary sirv-save-settings" value="<?php _e('Save Settings') ?>" /></td>
      </tr>
    </table>
  </div>
</div>
<div class="sirv-exclude-wrapper">
  <h2>Exclude images from Sirv</h2>
  <p class="sirv-options-desc">If there are images you don't want Sirv to serve, list them below. They could be specific images or entire pages.</p>

  <div class="sirv-optiontable-holder sirv-sync-exclude-images-wrapper">
    <table class="optiontable form-table">
      <tbody>
        <tr>
          <th>
            <label>Exclude files/folders</label>
          </th>
          <td>
            <span>Files that should not served by Sirv:</span>
            <textarea class="sirv-font-monospace" name="SIRV_EXCLUDE_FILES" value="<?php echo get_option('SIRV_EXCLUDE_FILES'); ?>" rows="5" placeholder="e.g.
  /wp-content/plugins/a-plugin/*.png
  /wp-content/uploads/2021/04/an-image.jpg"><?php echo get_option('SIRV_EXCLUDE_FILES'); ?></textarea>
            <span class="sirv-option-responsive-text">
              You can enter full URLs and the domain will be stripped.<br>
              Use * to specify all files at a certain path.
            </span>
          </td>
        </tr>
        <tr>
          <th>
            <label>Exclude pages</label>
          </th>
          <td>
            <span>Web pages that should not have files served by Sirv:</span>
            <textarea class="sirv-font-monospace" name="SIRV_EXCLUDE_PAGES" value="<?php echo get_option('SIRV_EXCLUDE_PAGES'); ?>" rows="5" placeholder="e.g.
  /example/particular-page.html
  /a-whole-section/*"><?php echo get_option('SIRV_EXCLUDE_PAGES'); ?></textarea>
            <span class="sirv-option-responsive-text">
              You can enter full URLs and the domain will be stripped.<br>
              Use * to specify all pages at a certain path.
            </span>
          </td>
        </tr>
        <tr>
          <th>
            <label>Exclude lazy/scaled images</label>
          </th>
          <td>
            <span>Disable lazy loading & responsive scaling on specific images, such as your website logo:</span>
            <textarea class="sirv-font-monospace" name="SIRV_EXCLUDE_RESPONSIVE_FILES" value="<?php echo get_option('SIRV_EXCLUDE_RESPONSIVE_FILES'); ?>" rows="5" placeholder="e.g.
  /wp-content/uploads/2021/04/Logo.jpg
  /wp-content/plugins/a-plugin/*.png
  ExampleClass
  ExampleAltTag
  ExampleID"><?php echo get_option('SIRV_EXCLUDE_RESPONSIVE_FILES'); ?></textarea>
            <span class="sirv-option-responsive-text">
              Enter full URLs or use * to apply on all files with a certain path/name.
              You can also exclude images via their img alt, class or data attribute. <a href="https://sirv.com/help/articles/using-sirv-wordpress/#disable-lazy-loading-and-responsive-scaling">Learn more</a>.
            </span>
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
</div>
