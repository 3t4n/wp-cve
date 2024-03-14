<?php
    /** @var $tabId */
    /** @var $this MM_WPFS_Admin_Menu */

    $pageStyle = 'wpfs-page-settings-email-options';
    if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ) {
        $pageStyle = 'wpfs-page-settings-email-options';
    } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES ) {
        $pageStyle = 'wpfs-page-settings-email-templates';
    }
?>
<div class="wrap">
    <div class="wpfs-page <?php echo $pageStyle; ?>">
        <?php include('partials/wpfs-header-with-tabs-and-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <?php
        $pageUrl = add_query_arg(
            array(
                'page' => MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS,
                'tab'  => $tabId
            ),
            admin_url( 'admin.php' )
        );

        if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS ) {
            $emailOptions = $this->getEmailOptionsData();
            $view = new MM_WPFS_Admin_EmailOptionsView( $emailOptions->adminName, $emailOptions->adminEmail );

            include('partials/wpfs-settings-email-options.php');
        } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES ) {
            $emailTemplates = $this->getEmailTemplatesData();
            $view = new MM_WPFS_Admin_EmailTemplatesView();

            include('partials/wpfs-settings-email-templates.php');
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
