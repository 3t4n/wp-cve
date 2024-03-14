jQuery(()=>{
    jQuery('.woo-pdf-invoice-view').click(()=>{
        window.open(jQuery('.woo-pdf-invoice-nounce').val()+'&invoice_id='+jQuery('.woo-pdf-invoice-list').val());
    });
});