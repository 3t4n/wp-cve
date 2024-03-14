<?php
    /** @var $backLinkUrl */
    /** @var $addOnData */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-add-ons">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>
        <?php if ( count( $addOnData ) > 0 ) { ?>
            <?php include('partials/wpfs-settings-addons-list.php'); ?>
        <?php } else { ?>
            <?php include('partials/wpfs-settings-addons-empty.php'); ?>
        <?php } ?>
    </div>
    <?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>
