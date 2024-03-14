;(function ($) {
    $(function () {
        new NJBATabs({
            id: '<?php echo $id ?>'
        });
        $('.fl-node-<?php echo $id; ?> .njba-tabs-label').on('click', function () {
            $('.fl-node-<?php echo $id; ?> .njba-tabs-label').removeClass('njba-tab-active');
            $(this).addClass('njba-tab-active');

        });
    });
})(jQuery);
