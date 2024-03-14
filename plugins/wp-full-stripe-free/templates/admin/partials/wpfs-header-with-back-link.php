<?php
    /** @var $backLinkUrl */
    /** @var $backLinkLabel */
    /** @var $pageTitle */
?>
<div class="wpfs-page-header">
    <div class="wpfs-page-header__back-button-wrapper">
        <a class="wpfs-page-header__back-button" href="<?php echo $backLinkUrl; ?>"><?php echo $backLinkLabel; ?></a>
    </div>
    <div class="wpfs-page-header__headline-with-actions">
        <div class="wpfs-page-header__headline">
            <div class="wpfs-page-header__title"><?php echo $pageTitle; ?></div>
        </div>
        <div class="wpfs-page-header__actions">
            <?php include('wpfs-header-block-stripe-accounts.php'); ?>
            <?php include('wpfs-header-block-announcements.php'); ?>
            <?php include('wpfs-header-block-help.php'); ?>
        </div>
    </div>
</div>
