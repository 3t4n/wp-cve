/* Nifty.JS v0.1
 *
 * Author: John-Alan Simmons 
 *
 * A JQuery modal system based from http://tympanus.net/codrops/2013/06/25/nifty-modal-window-effects/
 *
 * Licensed under the MIT License
 * 2015 ConferenceCloud Inc.
 */
(function($) {


    $(document).on("click", ".wready-md-overlay", function() {
        $(".nifty-modal.wready-md-show").nifty("hide")
    })

    $(document).on("click", ".nifty-modal.wready-md-show .wready-md-close", function() {
        $(this).closest(".nifty-modal.wready-md-show").nifty("hide")
    })

    $.fn.extend({
        nifty: function(cmd) {
            var self = this;
            var transitionEndEvents = "transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd"

            var showHandler = function(animation) {
                var handledTransitionEnd = false;
                $(self).trigger("show.nifty.modal")
                $(self).one(transitionEndEvents, function(event) {
                    if (!handledTransitionEnd) {
                        handledTransitionEnd = true;
                        event.preventDefault();
                        event.stopPropagation();
                        $(self).trigger("shown.nifty.modal")
                    }
                })
                $(self).addClass("wready-md-show")
            }

            var hideHandler = function() {
                var handledTransitionEnd = false;
                $(self).trigger("hide.nifty.modal")
                $(self).one(transitionEndEvents, function(event) {
                    if (!handledTransitionEnd) {
                        handledTransitionEnd = true;
                        event.preventDefault();
                        event.stopPropagation();
                        $(self).trigger("hidden.nifty.modal")
                    }
                })
                $(self).removeClass("wready-md-show")
            }

            if (cmd == "show") {
                showHandler();
            } else if (cmd == "hide") {
                hideHandler();
            }

            return this;
        }
    })
})(jQuery);