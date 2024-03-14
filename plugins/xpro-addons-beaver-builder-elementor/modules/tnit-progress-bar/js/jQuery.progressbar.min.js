/** * jQuery Line Progressbar * Author: Sharifur Rahman * Author URI : https:devrobin.com * Version: 1.0.0 */ ;
// (function($) {
//     'use strict';
//     $.fn.rProgressbar = function(options) {
//         options = $.extend({ percentage: null, ShowProgressCount: true, duration: 1000 }, options);
//         $.options = options;
//         return this.each(function(index, el) {
//             $(el).html('<div class="tnit-progressbar"><div class="tnit-proggress" style="width: ' + options.percentage + '%"></div><div class="tnit-percentCount">' + options.percentage + '%</div></div>');
//             var lineProgressBarInit = function() {
//                 var progressFill = $(el).find('.tnit-proggress');
//                 var progressBar = $(el).find('.tnit-progressbar');
//                 progressFill.css({ backgroundColor: options.fillBackgroundColor, height: options.height, borderRadius: options.borderRadius });
//                 progressBar.css({ width: options.width, backgroundColor: options.backgroundColor, borderRadius: options.borderRadius });
//                 progressFill.animate({ width: options.percentage + "%" },
//                  { step: function(x) { if (options.ShowProgressCount) 
//                     { $(el).find(".tnit-percentCount").text(Math.round(x) + "%"); } },
//                      duration: options.duration });
//             }
//             $(this).waypoint(lineProgressBarInit, { offset: '100%', triggerOnce: true });
//         });
//     }
// })(jQuery);
(function($) {
    'use strict';
    $.fn.rProgressbar = function(options) {
        options = $.extend({ percentage: null, ShowProgressCount: true, duration: 1000, delay: 0 }, options);
        $.options = options;
        return this.each(function(index, el) {
            $(el).html('<div class="tnit-progressbar"><div class="tnit-proggress" style="width: 0%"></div><div class="tnit-percentCount">0%</div></div>');
            var lineProgressBarInit = function() {
                var progressFill = $(el).find('.tnit-proggress');
                var progressBar = $(el).find('.tnit-progressbar');
                progressFill.css({ backgroundColor: options.fillBackgroundColor, height: options.height, borderRadius: options.borderRadius });
                progressBar.css({ width: options.width, backgroundColor: options.backgroundColor, borderRadius: options.borderRadius });
                $({ animatedValue: 0 }).animate({ animatedValue: options.percentage }, 
                    {
                    step: function(value) {
                        progressFill.css('width', value + "%");
                        if (options.ShowProgressCount) {
                            $(el).find(".tnit-percentCount").text(Math.round(value) + "%");
                        }
                    },
                    duration: options.duration
                });
            }
            setTimeout(lineProgressBarInit, options.delay);
        });
    }
})(jQuery);

