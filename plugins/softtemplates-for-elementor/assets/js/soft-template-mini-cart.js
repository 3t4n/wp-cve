( function( $ ) {
    "use strict";
    var miniCart = function miniCart($scope) {
        $scope.find(".soft-template-cart-inner").on("click mouseenter mouseleave", function (e) {
        var cart_btn = $(this),
            on_click = cart_btn.hasClass("soft-template-cart-on-click"),
            on_hover = cart_btn.hasClass("soft-template-cart-on-hover"),
            popup = cart_btn.find(".soft-template-cart-popup");

        if (popup.length == 0) {
            return;
        }

        if ("click" === e.type && on_click) {
            popup.fadeToggle();
        }

        if ("mouseenter" === e.type && on_hover) {
            popup.fadeIn();
        }

        if ("mouseleave" === e.type && on_hover) {
            popup.fadeOut();
        }
        });

        if ($scope.find(".soft-template-cart-popup").length > 0 && $scope.find(".soft-template-cart-on-click").length > 0) {
        $("body").on("click", function (e) {
            if ($(e.target).hasClass("soft-template-cart-popup") || $(e.target).parents().hasClass("soft-template-cart-popup") || $(e.target).hasClass("soft-template-cart-button") || $(e.target).parents().hasClass("soft-template-cart-button")) {
            return;
            } else {
            $scope.find(".soft-template-cart-popup").removeAttr("style");
            }
        });
        }
    }; 

    jQuery(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/soft-template-mini-cart.default", miniCart);
        elementorFrontend.hooks.addAction("frontend/element_ready/soft-template-mini-cart.default", miniCart);
    });

} )( jQuery );