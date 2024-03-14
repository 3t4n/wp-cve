<?php
    /** @var $tabs array */
    /** @var $tabId string */
    /** @var $pageSlug string */
?>

<div class="wpfs-page-tabs">
<?php foreach ( $tabs as $currentTab ) {
    /** @var $currentTab array */
    $params = array(
        MM_WPFS_Admin_Menu::PARAM_NAME_TAB => $currentTab[ MM_WPFS_Admin_Menu::PARAM_NAME_TAB ]
    );
    $tabUrl = $this->getAdminUrlBySlugAndParams( $pageSlug, $params );
?>
    <a class="wpfs-form-tab wpfs-page-tabs__item <?php echo $tabId === $currentTab[ MM_WPFS_Admin_Menu::PARAM_NAME_TAB ] ? 'wpfs-page-tabs__item--active' : '' ?>" href="<?php echo $tabUrl ?>" data-tab-id="<?php echo $currentTab['tab']; ?>"><?php echo $currentTab[ 'title' ]; ?></a>
<?php } ?>
</div>
