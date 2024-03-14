<?php
/** @var $tabId */
/** @var $this MM_WPFS_Admin_Menu */

$pageStyle = 'wpfs-page-settings-forms-options';
if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ) {
    $pageStyle = 'wpfs-page-settings-forms-options';
} elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE ) {
    $pageStyle = 'wpfs-page-settings-forms-appearance';
}
?>
<div class="wrap">
    <div class="wpfs-page <?php echo $pageStyle; ?>">
        <?php include('partials/wpfs-header-with-tabs-and-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <?php
        $pageUrl = add_query_arg(
            array(
                'page' => MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS,
                'tab'  => $tabId
            ),
            admin_url( 'admin.php' )
        );

        if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ) {
            $formsOptions = $this->getFormsOptionsData();
            $view = new MM_WPFS_Admin_FormsOptionsView();

            include('partials/wpfs-settings-forms-options.php');
        } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE ) {
            $formsAppearance = $this->getFormsAppearanceData();
            $view = new MM_WPFS_Admin_FormsAppearanceView();

            include('partials/wpfs-settings-forms-appearance.php');
        }
        ?>
        <div id="wpfs-success-message-container"></div>
    </div>
    <script type="text/template" id="wpfs-success-message">
        <div class="wpfs-floating-message__inner">
            <div class="wpfs-floating-message__message"><%- successMessage %></div>
            <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
                <span class="wpfs-icon-close"></span>
            </button>
        </div>
    </script>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>
