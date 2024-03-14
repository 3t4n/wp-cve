<?php

if (!defined('ABSPATH')) exit;

?>

<div class="s123-container">
    <ul class="s123-nav__tabs" id="s123-tabs">
        <li class="s123-nav__item">
            <a class="s123-nav__link active" href="#tab-api-key"><?php echo __("API Key", "s123-invoices") ?></a>
        </li>
        <li class="s123-nav__item">
            <a class="s123-nav__link" href="#tab-api-settings"><?php echo __("Settings", "s123-invoices") ?></a>
        </li>
    </ul>

    <div class="error notice hidden alert-slider" id="s123-error-alert">
        <p></p>
    </div>

    <div class="updated notice hidden alert-slider" id="s123-success-alert">
        <p></p>
    </div>

    <div class="tab-content">
        <div id="tab-api-key" class="tab-pane active">
            <?php include_once 'settings/api-key.php'; ?>
        </div>

        <div id="tab-api-settings" class="tab-pane">
            <?php include_once 'settings/invoice-settings.php'; ?>
        </div>
    </div>
</div>
