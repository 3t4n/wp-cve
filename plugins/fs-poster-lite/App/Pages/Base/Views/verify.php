<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;
?>

<div class="fsp-box-container">
    <div class="fsp-card fsp-box">
        <div class="fsp-box-info">
            <i class="fas fa-info-circle"></i><?php echo esc_html__( 'Please activate the plugin.', 'fs-poster' ); ?>
        </div>
        <div class="fsp-box-logo">
            <img class="fsp-img-is-center" src="<?php echo Pages::asset( 'Base', 'img/logo_new.png' ); ?>">
        </div>
        <div id="fspInstallForm">
            <div class="fsp-form-group">
                <input type="email" autocomplete="off" id="fspEmail" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Enter your e-mail address', 'fs-poster' ); ?>">
            </div>
            <div class="fsp-form-group">
                <select id="fspMarketingStatistics" class="fsp-form-select">
                    <?php echo Helper::get_found_from_options(); ?>
                </select>
            </div>
            <div class="fsp-form-group">
                <button type="button" class="fsp-button" id="fspVerifyBtn"><?php echo esc_html__( 'SEND CODE', 'fs-poster' ); ?></button>
            </div>
        </div>
    </div>
</div>
