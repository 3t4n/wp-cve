<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<div class="wrap">
    <h2>Premmerce Redirect Manager</h2>

    <?php echo $htmlTabs; ?>

    <?php
        if(function_exists('premmerce_pr_fs') && premmerce_pr_fs()->is_registered()){
            premmerce_pr_fs()->add_filter('hide_account_tabs', '__return_true');
            premmerce_pr_fs()->_account_page_load();
            premmerce_pr_fs()->_account_page_render();
        }
    ?>
</div>