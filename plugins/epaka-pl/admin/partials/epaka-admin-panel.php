<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="epaka-bar epaka-black">
    <button class="epaka-bar-item epaka-button" onclick="window.changeTab('konto')">Konto</button>
    <button class="w3epaka-bar-item epaka-button" onclick="window.changeTab('metody')">Metody wysyłki</button>
    <button id="logout" class="w3epaka-bar-item epaka-button">(<?php echo (!empty($profile->email) ? $profile->email->__toString() : '');?>) Wyloguj</button>
</div>

<div class="epaka-panel-content">
    <?php include_once('epaka-alerts.php');?>
    
    <div id="konto" class="epaka-container tab">
        <?php include_once("epaka-admin-panel-account.php"); ?>
    </div>

    <div id="metody" class="epaka-container tab" style="display:none">
        <?php include_once("epaka-admin-panel-shipment-methods.php"); ?>
    </div>
</div>

<?php include_once(plugin_dir_path( __FILE__ ).'../../assets/partials/epaka-map.php');?>