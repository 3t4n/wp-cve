jQuery(document).ready(function ($) {

    $(".payamito-woocommerce-open-modal").click(function () {
        $('#payamito-woocommerce-modal').modal();
    })

    $('.payamito-tag-modal').click(function () {
        $(this).CopyToClipboard()
    });

    $('.payamito-tag-modal').jTippy({
        trigger: 'click',
        theme: 'green',
        position: 'bottom',
        size: 'small',
        title: 'کپی شد'
    });
});