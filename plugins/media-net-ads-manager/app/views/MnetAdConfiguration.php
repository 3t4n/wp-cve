<?php

use Mnet\Admin\MnetAuthManager;
use Mnet\Utils\MnetAdSlot;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetModuleManager;

if (MnetAuthManager::isLoggedIn() && (\mnet_user()->isEap || !\mnet_site()->mapped)) {
    MnetAuthManager::refreshStatus();
}

// forces page reload so that window variables are fetched again
// fixes incorrect user details getting applied when page changes on back/forward
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');

$useremail = \mnet_user()->email;
$username = \mnet_user()->name;
$slot_count = MnetAdSlot::count();
$adtags_count = MnetAdTag::count();
$adtagSizesAvailable = MnetAdTag::getAvailableSizes();
$isSiteReject = \mnet_site()->rejected;
$isEap = \mnet_user()->isEap;
$siteStatus = \mnet_site()->status;
$modules = MnetModuleManager::getModules();
?>
<script>
    Object.defineProperty(window, 'MNET_PLUGIN', {
        value: Object.freeze({
            url: '<?php echo \plugin_dir_url(__DIR__) . "../";  ?>',
            version: '<?php echo MNET_PLUGIN_VERSION;  ?>'
        }),
        configurable: false,
        writable: false
    });
    window.MNET_USER_DATA = {
        email: <?php echo json_encode($useremail); ?>,
        name: <?php echo json_encode($username); ?>
    };

    window.mnetConfig = {
        siteRejected: +<?php echo json_encode($isSiteReject); ?>,
        adtagCount: +<?php echo json_encode($adtags_count); ?>,
        slotCount: +<?php echo json_encode($slot_count); ?>,
        isEap: +<?php echo json_encode($isEap); ?>,
        availableAdSizes: '<?php echo $adtagSizesAvailable; ?>',
        siteStatus: '<?php echo $siteStatus; ?>',
        modules: JSON.parse('<?php echo json_encode($modules); ?>'),
        adstxtParserPath: '<?php echo \plugin_dir_url(__DIR__) . mnet_normalize_chunks('../dist/js/mnetAdstxtParseWorker.js'); ?>',
    };
</script>

<div id="mnet-vue-app">
    <div id="mnet-vue-normal-app">
        <?php include __DIR__ . "/mnetBundleLoader.php"; ?>
    </div>
</div>

<?php include __DIR__ . "/mnetAnalytics.php"; ?>
<?php include __DIR__ . "/mnetScripts.php"; ?>