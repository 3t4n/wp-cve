<?php
require_once LOGICS_PLUGIN_DIR . 'admin/class-rss-overview.php';

$rss_overview = new LOGICS_Rss_Overview();
$rss_overview->prepare_items(); 
?>

<div id="wpsl-store-overview" class="wrap">
    <h2>RSS List</h2>
    <?php settings_errors(); ?>
    
    <form method="post">
        <?php
            $rss_overview->search_box( 'search', 'search_id' );
            $rss_overview->display(); 
        ?>
    </form>
</div>