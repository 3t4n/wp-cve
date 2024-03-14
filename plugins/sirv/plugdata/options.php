<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once(dirname(__FILE__) . '/includes/classes/options/options.helper.class.php');

$error = '';
$base_options = ['SIRV_FOLDER', 'SIRV_CDN_URL', 'SIRV_ENABLE_CDN', 'SIRV_SHORTCODES_PROFILES', 'SIRV_CDN_PROFILES', 'SIRV_USE_SIRV_RESPONSIVE', 'SIRV_CROP_SIZES', 'SIRV_JS', 'SIRV_JS_MODULES', 'SIRV_CUSTOM_CSS', 'SIRV_RESPONSIVE_PLACEHOLDER, SIRV_PARSE_STATIC_IMAGES', 'SIRV_PARSE_VIDEOS', 'SIRV_CSS_BACKGROUND_IMAGES', 'SIRV_EXCLUDE_FILES', 'SIRV_EXCLUDE_RESPONSIVE_FILES', 'SIRV_EXCLUDE_PAGES', 'SIRV_DELETE_FILE_ON_SIRV', 'SIRV_SYNC_ON_UPLOAD',  'SIRV_PREVENT_CREATE_WP_THUMBS', 'SIRV_PREVENTED_SIZES', 'SIRV_HTTP_AUTH_CHECK', 'SIRV_HTTP_AUTH_USER', 'SIRV_HTTP_AUTH_PASS', 'SIRV_CUSTOM_SMV_SH_OPTIONS'];
OptionsHelper::prepareOptionsData();
$options_names = array_merge($base_options, OptionsHelper::get_options_names_list());

function isWoocommerce()
{
  return is_plugin_active('woocommerce/woocommerce.php');
}


function sirv_getStatus()
{
  $status = get_option('SIRV_ENABLE_CDN');

  $class = $status == '1' ? 'sirv-status--enabled' : 'sirv-status--disabled';

  return $class;
}


function sirv_get_cache_count($isGarbage, $cacheInfo)
{
  $q = (int) $cacheInfo['total_count'];
  if ($isGarbage) {
    if ((int) $cacheInfo['q'] - (int) $cacheInfo['garbage_count'] > (int) $cacheInfo['total_count']) {
      $q = (int) $cacheInfo['total_count'];
    } else {
      $q = (int) $cacheInfo['q'] - (int) $cacheInfo['garbage_count'];
    }
  }

  return $q;
}


function sirv_get_sync_button_text($isAllSynced, $cacheInfo)
{
  $sync_button_text = 'Sync images';

  if ($isAllSynced) {
    if ((int) $cacheInfo['FAILED']['count'] == 0 && (int) $cacheInfo['PROCESSING']['count'] == 0) {
      $sync_button_text = '100% synced';
    } else {
      $sync_button_text = 'Synced';
    }
  }

  return $sync_button_text;
}


$sirvAPIClient = sirv_getAPIClient();
$isMuted = $sirvAPIClient->isMuted();

if ($isMuted) {
  $error = $sirvAPIClient->getMuteError();
}

$sirvStatus = $sirvAPIClient->preOperationCheck();

if ($sirvStatus) {
  $isWoocommerce = isWoocommerce();
  /* $isMultiCDN = false; */
  $domains = array();
  /* $is_direct = get_option('SIRV_NETWORK_TYPE') == "2" ? true : false; */
  $sirvCDNurl = get_option('SIRV_CDN_URL');

  $accountInfo = $sirvAPIClient->getAccountInfo();

  if (!empty($accountInfo)) {

    /* $isMultiCDN = count((array) $accountInfo->aliases) > 1 ? true : false;
    $is_direct = (isset($accountInfo->aliases->{$accountInfo->alias}->cdn) && $accountInfo->aliases->{$accountInfo->alias}->cdn) ? false : true; */

    if (!empty($accountInfo->cdnTempURL)) {
      $domains[$accountInfo->cdnTempURL] = $accountInfo->cdnTempURL;
    }

    if (!empty($accountInfo->alias)) {
      $domains[$accountInfo->alias . '.sirv.com'] = $accountInfo->alias . '.sirv.com';
    }

    if (!empty($accountInfo->aliases)) {
      foreach ($accountInfo->aliases as $a => $alias) {
        $domain = !empty($alias->customDomain) ? $alias->customDomain : $a . '.sirv.com';
        $domains[$domain] = $domain;
      }
    }

    /* if ($isMultiCDN) {
        foreach ($accountInfo->aliases as $a =>$alias) {
        $customCDN = !empty($alias->customDomain) ? $alias->customDomain : $a . '.sirv.com';
        $domains[$customCDN] = $customCDN;
      }
    } */
  }

  $cacheInfo = sirv_getCacheInfo();
  $profiles = sirv_getProfilesList();
  $storageInfo = sirv_getStorageInfo();


  $isOverCache = (int) $cacheInfo['q'] > (int) $cacheInfo['total_count'] ? true : false;
  $isSynced = (int) $cacheInfo['SYNCED']['count'] > 0 ? true : false;
  $isFailed = (int) $cacheInfo['FAILED']['count'] > 0 ? true : false;
  $isGarbage = (int) $cacheInfo['garbage_count'] > 0 ? true : false;

  if ($isOverCache) $cacheInfo['q'] = sirv_get_cache_count($isGarbage, $cacheInfo);


  $isAllSynced = ((int) $cacheInfo['q'] + (int) $cacheInfo['FAILED']['count'] + (int) $cacheInfo['PROCESSING']['count']) == (int) $cacheInfo['total_count'];
  $is_sync_button_disabled = $isAllSynced ? 'disabled' : '';
  //$sync_button_text = $isAllSynced ? ( (int) $cacheInfo['FAILED']['count'] == 0 && (int) $cacheInfo['PROCESSING']['count'] == 0 ) ? '100% synced' : 'Synced' : 'Sync images';
  $sync_button_text = sirv_get_sync_button_text($isAllSynced, $cacheInfo);
  $is_show_resync_block = (int) $cacheInfo['q'] > 0 || $cacheInfo['FAILED']['count'] > 0 ? '' : 'display: none';
  $is_show_failed_block = (int) $cacheInfo['FAILED']['count'] > 0 ? '' : 'display: none';
} else {
  if (!$isMuted) {
    wp_safe_redirect(add_query_arg(array('page' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php'), admin_url('admin.php')));
  }
}
?>

<style type="text/css">
  /* .sirv-logo-background {
    background-image: url("<?php echo plugin_dir_url(__FILE__) . "assets/logo.svg" ?>");
    background-position: center right;
    background-repeat: no-repeat;
    background-size: 68px 68px;
    min-height: 60px;
  } */

  a[href*="page=<?php echo SIRV_PLUGIN_RELATIVE_SUBDIR_PATH ?>options.php"] img {
    padding-top: 7px !important;
  }
</style>

<form action="options.php" method="post" id="sirv-save-options">
  <?php
  wp_nonce_field('update-options');

  $active_tab = (isset($_POST['active_tab'])) ? $_POST['active_tab'] : '#sirv-settings';
  ?>
  <div class="sirv-wrapped-nav">
    <!-- <h1 class="sirv-options-title sirv-logo-background">Welcome to Sirv</h1>
    <div class="sirv-version-wrap">
      <div class="sirv-version"><span>v<?php echo SIRV_PLUGIN_VERSION; ?></span></div>
    </div> -->

    <div class="sirv-options-title-wrap">
      <div class="sirv-options-title">
        <h1 class="sirv-options-title-h1">Welcome to Sirv</h1>
      </div>
      <div class="sirv-options-logo">
        <img src="<?php echo plugin_dir_url(__FILE__) . "assets/logo.svg" ?>" alt="">
        <div class="sirv-options-version"><span>v<?php echo SIRV_PLUGIN_VERSION; ?></span></div>
      </div>
    </div>
    <nav class="nav-tab-wrapper">
      <?php if ($sirvStatus) { ?>
        <a class="nav-tab nav-tab-sirv-settings <?php echo ($active_tab == '#sirv-settings') ? 'nav-tab-active' : '' ?>" href="#sirv-settings" data-link="settings"><span class="dashicons dashicons-admin-generic"></span><span class="sirv-tab-txt">Settings</span></a>
        <?php if ($isWoocommerce) { ?>
          <a class="nav-tab nav-tab-sirv-woo <?php echo ($active_tab == '#sirv-woo') ? 'nav-tab-active' : '' ?>" href="#sirv-woo" data-link="woo"><span class="dashicons dashicons-cart"></span><span class="sirv-tab-txt">WooCommerce</span></a>
        <?php } ?>
        <a class="nav-tab nav-tab-sirv-cache <?php echo ($active_tab == '#sirv-cache') ? 'nav-tab-active' : '' ?>" href="#sirv-cache" data-link="cache"><span class="dashicons dashicons-update"></span><span class="sirv-tab-txt">Synchronization</span></a>
        <!-- <a class="nav-tab nav-tab-sirv-stats <?php echo ($active_tab == '#sirv-stats') ? 'nav-tab-active' : '' ?>" href="#sirv-stats"><span class="dashicons dashicons-chart-bar"></span><span class="sirv-tab-txt">Stats</span></a> -->
      <?php } ?>
    </nav>
  </div>

  <?php
  if ($isMuted) {
  ?>
    <div class="sirv-optiontable-holder">
      <div class="sirv-error"><?php if ($error) echo '<div id="sirv-settings-messages" class="sirv-message error-message">' . $error . '</div>'; ?></div>
    </div>

  <?php } ?>

  <?php if ($sirvStatus) { ?>
    <div class="sirv-tab-content sirv-tab-content-active" id="sirv-settings">
      <?php include(dirname(__FILE__) . '/submenu_pages/settings.php'); ?>
    </div>

    <?php if ($isWoocommerce) { ?>
      <div class="sirv-tab-content" id="sirv-woo">
        <?php include(dirname(__FILE__) . '/submenu_pages/woocommerce.php'); ?>
      </div>
    <?php } ?>

    <div class="sirv-tab-content" id="sirv-cache">
      <?php include(dirname(__FILE__) . '/submenu_pages/sync.php'); ?>
    </div>
  <?php } ?>

  <input type="hidden" name="active_tab" id="active_tab" value="#settings" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page_options" value="<?php echo implode(', ', $options_names); ?>" />

</form>
