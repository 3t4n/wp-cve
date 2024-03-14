<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<div class="wrap">
    <h2>Premmerce Redirect Manager</h2>

    <?php echo $htmlTabs; ?>

    <?php
        if(function_exists('premmerce_pr_fs')){
            premmerce_pr_fs()->_contact_page_render();
        }
    ?>
</div>
