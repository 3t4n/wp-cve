window['cf7_ds_print_form'] = function (btn) {
    var e = jQuery(btn),
        p = e.closest('form');

    e.addClass('cf7-ds-no-print');
    p.addClass('cf7-ds-print');
    while(p.length)
    {
        p.siblings().addClass('cf7-ds-no-print');
        p = p.parent();
    }
    window.print();
    setTimeout(function(){
        jQuery('.cf7-ds-no-print').removeClass('cf7-ds-no-print');
        jQuery('.cf7-ds-print').removeClass('cf7-ds-print');
    }, 5000);
};