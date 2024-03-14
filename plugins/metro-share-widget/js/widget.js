(function ($) {
    "use strict";
    $(function () {
        $(".ms-widget").click(function (e) {
            e.preventDefault();
            var w = 600;
            var h = 400;
            var title = 'Share';
            var href = $(this).attr('href');
            if (typeof(href) != 'undefined') {
                var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                var left = ((width / 2) - (w / 2)) + dualScreenLeft;
                var top = ((height / 2) - (h / 2)) + dualScreenTop;
                var newWindow = window.open(href, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
                //window.open(href, "tweet", "height=300,width=550,resizable=1",'Share',windowFeatures);
            }

        });
    });
}(jQuery));