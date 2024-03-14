<?php
/** @var $tabs array */
/** @var $tabId string */
?>

<div class="wpfs-page-tabs">
    <?php foreach ( $tabs as $currentTab ) { ?>
        <a class="wpfs-form-tab wpfs-page-tabs__item <?php echo $tabId === $currentTab[ MM_WPFS_Admin_Menu::PARAM_NAME_TAB ] ? 'wpfs-page-tabs__item--active' : '' ?>" href="#" data-tab-id="<?php echo $currentTab['tab']; ?>"><?php echo $currentTab[ 'title' ]; ?></a>
    <?php } ?>
</div>
