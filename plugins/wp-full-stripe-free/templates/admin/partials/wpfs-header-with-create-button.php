<?php
/** @var $pageTitle */
/** @var $createButtonUrl */
/** @var $createButtonLabel */
?>
<div class="wpfs-page-header">
    <div class="wpfs-page-header__headline-with-actions">
        <div class="wpfs-page-header__headline">
            <div class="wpfs-page-header__title"><?php echo $pageTitle; ?></div>
            <a class="wpfs-btn wpfs-btn-primary" href="<?php echo $createButtonUrl; ?>"><?php echo $createButtonLabel; ?></a>
        </div>
        <div class="wpfs-page-header__actions">
            <?php include('wpfs-header-block-stripe-accounts.php'); ?>
            <?php include('wpfs-header-block-announcements.php'); ?>
            <?php include('wpfs-header-block-help.php'); ?>
        </div>
    </div>
</div>
