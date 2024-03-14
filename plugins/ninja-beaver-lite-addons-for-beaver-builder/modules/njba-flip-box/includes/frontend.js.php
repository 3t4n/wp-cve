(function ($) {
    var document_width, document_height;
    var args = {
        id: '<?php echo $id; ?>'
    };
    jQuery(document).ready(function () {
        document_width = $(document).width();
        document_height = $(document).height();

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('.fl-node-<?php echo $id; ?> .njba-flip-box-outter').click(function () {
                if ($(this).hasClass('njba-hover')) {
                    $(this).removeClass('njba-hover');
                } else {
                    $(this).addClass('njba-hover');
                }
            });
        }

    });
    jQuery(window).load(function () {
        new NJBAFlipBox(args);
    });

    jQuery(window).resize(function () {
        if (document_width != $(document).width() || document_height != $(document).height()) {
            document_width = $(document).width();
            document_height = $(document).height();
            new NJBAFlipBox(args);
        }
    });
    new NJBAFlipBox(args);
})(jQuery);
