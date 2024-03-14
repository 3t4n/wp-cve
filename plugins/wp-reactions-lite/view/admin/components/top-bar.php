<?php
use WP_Reactions\Lite\Helper;
$section_title = '';
extract( $data );

// getting these templates here so it will be imported on all submenus
Helper::getTemplate('view/admin/components/floating-menu');
Helper::getTemplate('view/admin/components/feedback-form');
?>
<div class="wpra-header">
    <div class="logo-wrap">
        <span class="dashicons dashicons-menu floating-menu-toggler"></span>
        <img class="wpj-logo" src="https://wpreactions.com/wp-content/themes/wprt/assets/images/logo-for-lite.svg" alt="Logo">
        <?php if(!empty($section_title)) { ?>
        <div class="top-section-title">
            <span class="tt-1"><?php echo $section_title; ?></span>
			<?php if ( isset( $_GET['behavior'] ) ) { ?>
                <span class="tt-2"><?php _e('Classic Reactions Lite','wpreactions-lite'); ?></span>
			<?php } ?>
        </div>
        <?php } ?>
    </div>
    <div class="nav-links-wrap">
        <nav class="header-links d-inline-block">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://wpreactions.com/documentation/" target="_blank">
                        <span><?php _e('Documentation', 'wpreactions-lite'); ?></span>
                        <span class="dashicons dashicons-external"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://wordpress.org/support/plugin/wp-reactions-lite/" target="_blank">
                        <span><?php _e('Support', 'wpreactions-lite'); ?></span>
                        <span class="dashicons dashicons-external"></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div style="padding-bottom: 90px"></div>