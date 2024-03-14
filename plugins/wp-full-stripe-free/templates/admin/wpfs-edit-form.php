<div class="wrap">
    <?php
    /** @var $formType */

    $pageStyle = 'wpfs-page-edit-inline-save-card-form';
    if ( $formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD ) {
        $pageStyle = 'wpfs-page-edit-inline-save-card-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD ) {
        $pageStyle = 'wpfs-page-edit-checkout-save-card-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_DONATION ) {
        $pageStyle = 'wpfs-page-edit-inline-donation-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION ) {
        $pageStyle = 'wpfs-page-edit-checkout-donation-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT ) {
        $pageStyle = 'wpfs-page-edit-inline-payment-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT ) {
        $pageStyle = 'wpfs-page-edit-checkout-payment-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION ) {
        $pageStyle = 'wpfs-page-edit-inline-subscription-form';
    } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION ) {
        $pageStyle = 'wpfs-page-edit-checkout-subscription-form';
    }
    ?>
    <div class="wpfs-page <?php echo $pageStyle; ?>">
        <?php include('partials/wpfs-header-with-client-tabs-and-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>
        <?php
        if ( $formType === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD ) {
            include('partials/wpfs-edit-form-inline-save-card.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD ) {
            include('partials/wpfs-edit-form-checkout-save-card.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_DONATION ) {
            include('partials/wpfs-edit-form-inline-donation.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION ) {
            include('partials/wpfs-edit-form-checkout-donation.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT ) {
            include('partials/wpfs-edit-form-inline-payment.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT ) {
            include('partials/wpfs-edit-form-checkout-payment.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION ) {
            include('partials/wpfs-edit-form-inline-subscription.php');
        } elseif ( $formType === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION ) {
            include('partials/wpfs-edit-form-checkout-subscription.php');
        }
        ?>
        <div id="wpfs-success-message-container"></div>
    </div>
    <div id="wpfs-dialog-container"></div>
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
