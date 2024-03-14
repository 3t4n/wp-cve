var safari_browser = navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1;
(function ($) {
    if (safari_browser) {
        $("a").each(function () {
            if ($(this).attr('href').endsWith("booking") || $(this).attr('href').endsWith("booking/")) {
                $(this).attr('href', 'https://w2-vans.caagcrm.com/public/car-rental/reservations/step1?new=1&brand=404cd5cb-2b80-4823-a0dd-dac4f88d7849');
                $(this).attr('target', '_blank');
            }
        });
    }


})(jQuery);

