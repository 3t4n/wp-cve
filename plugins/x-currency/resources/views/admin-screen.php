<?php defined( 'ABSPATH' ) || exit; ?>

<div id="x-currency-admin-root"></div>
<script data-cfasync="false" type="text/javascript">
    jQuery(document).ready(function ($) {
    /**
     * Admin menu settings
     */
    var x_currency_page = $('.wp-submenu a[href="admin.php?page=x-currency"]');
    x_currency_page.attr('href', 'admin.php?page=x-currency#/overview');

    var current_page = $('.wp-submenu a[href="' + location.href + '"]');
    x_currency_set_current_page(current_page);

    $('#toplevel_page_x-currency .wp-submenu a').on('click', function () {
        var current_page = $(this);
        x_currency_set_current_page(current_page);
    });

    function x_currency_set_current_page(current_page) {
        current_page.closest('.wp-submenu').find('.current').removeClass('current');
        current_page.addClass('current');
        current_page.closest('li').addClass('current');
    }
});
</script>
