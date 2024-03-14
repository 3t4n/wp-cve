<?php
function mw_site_settings_page() { 
?>
    <div class="container">
		<div class="mweb">
			<?php echo'<a target="_blank" href="https://my.mandegarweb.com/aff.php?aff=62"><img class="mwfc-responsive" src="' . plugins_url( 'assets/images/mandegarweb.gif', dirname(__FILE__) ) . '"></a>'; ?>
			<?php echo'<a target="_blank" href="https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8"><img class="mwfc-responsive" src="' . plugins_url( 'assets/images/pro.jpg', dirname(__FILE__) ) . '"></a>'; ?>
        </div>
        <div class="mwtitle">
            <h2><span class="dashicons dashicons-admin-appearance"></span>
                <?php _e('MW Font Changer', 'mwfc'); ?>
            </h2>
        </div>
        <?php include_once('plugin-theme-options.php'); ?>
    </div>
    <?php
}