jQuery(function () {
    jQuery('.woo-pdf-invoice-view').click(function () {
        window.open(jQuery('.woo-pdf-invoice-nounce').val() + '&invoice_id=' + jQuery('.woo-pdf-invoice-list').val());
    });
});
//# sourceMappingURL=MetaBoxes.js.map