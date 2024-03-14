jQuery(document).ready(function ($) {
    'use strict';
    $(document).on('click', '.vi-woo-orders-tracking-form-search-tracking-number-btnclick', function () {
        if (vi_wot_frontend_form_search.is_preview) {
            return false;
        }
        let $form = $(this).closest('.vi-woo-orders-tracking-form-search');
        let $message = $form.find('.vi-woo-orders-tracking-form-message');
        $message.addClass('vi-woo-orders-tracking-hidden');
        $form.find('.vi-woo-orders-tracking-form-error').removeClass('vi-woo-orders-tracking-form-error');
        let $tracking_number = $form.find('.vi-woo-orders-tracking-form-search-tracking-number');
        let tracking_number = $tracking_number.val();
        if (!tracking_number) {
            $tracking_number.parent().addClass('vi-woo-orders-tracking-form-error');
            $('.vi-woo-orders-tracking-message-empty-nonce').addClass('vi-woo-orders-tracking-hidden');
            $message.removeClass('vi-woo-orders-tracking-hidden');
            return false;
        }
    });
});