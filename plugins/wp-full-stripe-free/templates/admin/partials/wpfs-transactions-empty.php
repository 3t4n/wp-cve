<?php
    /** @var $tabId */
    /** @var $transactionCount */
    /** @var $formCount */
    /** @var $createButtonLabel */
    /** @var $createButtonUrl */
    /** @var $this MM_WPFS_Admin_Menu */

    $title = '';
    $description = '';
    $icon = 'wpfs-icon-card';
    $formType = '';

    if ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS ) {
        $title = __( 'No payments yet.', 'wp-full-stripe-admin' );
        $description = $formCount > 0 ?
                       __( 'You will find one-time payments here.', 'wp-full-stripe-admin' ) :
                       __( 'Create your first one-time payment form to receive payments.', 'wp-full-stripe-admin' );
        $icon = 'wpfs-icon-card';
        $formType = MM_WPFS::FORM_TYPE_PAYMENT;
    } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS ) {
        $title = __( 'No subscriptions yet.', 'wp-full-stripe-admin' );
        $description = $formCount > 0 ?
            __( 'You will find your subscribers here.', 'wp-full-stripe-admin' ) :
            __( 'Create your first subscription form to collect subscriptions.', 'wp-full-stripe-admin' );
        $icon = 'wpfs-icon-subscription';
        $formType = MM_WPFS::FORM_TYPE_SUBSCRIPTION;
    } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS ) {
        $title = __( 'No donations yet.', 'wp-full-stripe-admin' );
        $description = $formCount > 0 ?
            __( 'You will find donations here.', 'wp-full-stripe-admin' ) :
            __( 'Create your first donation form to accept donations.', 'wp-full-stripe-admin' );
        $icon = 'wpfs-icon-donation';
        $formType = MM_WPFS::FORM_TYPE_DONATION;
    } elseif ( $tabId === MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS ) {
        $title = __( 'No saved cards yet.', 'wp-full-stripe-admin' );
        $description = $formCount > 0 ?
            __( 'You will find saved cards here.', 'wp-full-stripe-admin' ) :
            __( 'Create your first save card form to collect card details from customers.', 'wp-full-stripe-admin' );
        $icon = 'wpfs-icon-card';
        $formType = MM_WPFS::FORM_TYPE_SAVE_CARD;
    }
?>

<div class="wpfs-empty-state">
    <div class="wpfs-empty-state__icon">
        <span class="<?php echo $icon; ?>"></span>
    </div>
    <div class="wpfs-empty-state__title"><?php echo $title; ?></div>
    <div class="wpfs-empty-state__message"><?php echo $description; ?></div>
    <?php if ( $formCount == 0 ) {
        $createFormParams = array(
            MM_WPFS_Admin_Menu::PARAM_NAME_TYPE => $formType
        );
        $createButtonUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(MM_WPFS_Admin_Menu::SLUG_CREATE_FORM, $createFormParams);
    ?>
    <a class="wpfs-btn wpfs-btn-primary" href="<?php echo $createButtonUrl; ?>"><?php echo $createButtonLabel; ?></a>
    <?php } ?>
</div>
