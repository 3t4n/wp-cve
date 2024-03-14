let NJBAFlipBox;
(function ($) {

    NJBAFlipBox = function (settings) {
        this.id = settings.id;
        this.nodeClass = '.fl-node-' + settings.id;
        this._init();
    };
    NJBAFlipBox.prototype = {
        nodeClass: this.nodeClass,
        id: this.id,
        _init: function () {

            const id = this.id;

            if (!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
                $('.fl-node-' + id + ' .njba-flip-box-outter').hover(function (event) {
                    event.stopPropagation();
                    $(this).addClass('njba-hover');
                }, function (event) {
                    event.stopPropagation();
                    $(this).removeClass('njba-hover');
                });
            }
            this._njbaFlipBoxAdjustHeight();

            setTimeout(function () {
                $('.njba-face').css('opacity', '1');
            }, 500);
        },
        _njbaFlipBoxAdjustHeight: function () {
            const currentFlipBox = $(this.nodeClass),
                backFlipSection = currentFlipBox.find('.njba-back .njba-flip-box-section'),
                frontFlipSection = currentFlipBox.find('.njba-front .njba-flip-box-section');
            let frontHeight = 0,
                backHeight = 0;
            setTimeout(function () {
                currentFlipBox.find('.njba-face').css('height', '100%');
                currentFlipBox.find('.njba-flip-box-outter').css('height', '100%');
                currentFlipBox.find('.njba-flip-box-outter').parent().css('height', '100%');
                frontHeight = parseInt(frontFlipSection.outerHeight()),
                    backHeight = parseInt(backFlipSection.outerHeight());
                if ((backHeight >= frontHeight)) {
                    currentFlipBox.find(".njba-face").css('height', backHeight);
                } else {
                    currentFlipBox.find(".njba-face").css('height', frontHeight);
                }
            }, 200);
        },
    };

})(jQuery);
