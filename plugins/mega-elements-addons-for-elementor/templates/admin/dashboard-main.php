<?php
/**
 * Dashboard main template
 */

defined( 'ABSPATH' ) || die();
?>
<div class="mega-elements-outer">
    <div class="mega-elements-outer-inner">
        <div class="mega-elements-tab-holder">
            <div class="mega-elements-tabs">
                <button class="mega-elements-tab mega-elements-general active"><?php esc_html_e( 'General', 'mega-elements-addons-for-elementor' ); ?></button>
                <button class="mega-elements-tab mega-elements-widgets"><?php esc_html_e( 'Widgets', 'mega-elements-addons-for-elementor' ); ?></button>
            </div>
        </div><!-- .mega-elements-tab-holder -->
        <div class="mega-elements-content-wrap">
            <?php 
                // Dashboard General Tab.
                include MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . '/templates/admin/dashboard-general.php';
                
                // Dashboard Widgets Tab.
                include MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . '/templates/admin/dashboard-widgets.php'; 
            ?>
        </div>
    </div><!-- .mega-elements-outer-inner -->
</div><!-- .mega-elements-outer -->
<?php
