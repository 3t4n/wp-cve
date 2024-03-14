var WoocommerCheckout = {
    form: null,

    init: function () {
        this.form = $j('form.checkout');

        if (!this.form.length) {
            return;
        }

        var _this = this;
        this.form.on({
            checkout_place_order: function () {
                var isValid = _this.validate();
                if (!isValid && !$j('html, body').is(':animated')) {
                    $j('html, body').animate({
                        scrollTop: (IsraelPostCommon.additonalBlock.offset().top - 100)
                    }, 700);
                }

                return isValid;
            }
        });
    },

    validate: function () {
        return IsraelPost.validate();
    }
}