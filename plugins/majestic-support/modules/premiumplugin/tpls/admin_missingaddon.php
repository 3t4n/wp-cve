<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('missingaddon'); ?>
        <div id="msadmin-data-wrp">
            <div id="majesticsupport-content">
                <h1 class="ms-missing-addon-message" >
                    <?php
                    $addon_name = MJTC_request::MJTC_getVar('page');
                    echo esc_html(MJTC_majesticsupportphplib::MJTC_ucfirst($addon_name)).'&nbsp;';
                    echo esc_html(__('addon in no longer active','majestic-support')).'!';
                    ?>

                </h1>
            </div>
        </div>
    </div>
</div>
