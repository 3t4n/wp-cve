jQuery(function () {
    var that = this;
    jQuery("#csr-customerrating").jRate({
        strokeColor: 'black',
        precision: 0,
        rating: 5,
        startColor: "#FFAE00",
        endColor: "#FFAE00",
        onChange: function (rating) {
            jQuery("#customer_rate").text(rating);
        },
        onSet: function (rating) {
            jQuery("#customer_review").val(rating);
        }
    });

});

jQuery(".csr-ratings").each(function () {
    var rating = jQuery(this).data('rating');
    jQuery(this).jRate({
        readOnly: true,
        rating: rating,
        strokeColor: 'black',
        startColor: "#FFAE00",
        endColor: "#FFAE00"
    });
});

var admin_rating = jQuery("#admin-csr-customerrating");

admin_rating.jRate({
    strokeColor: 'black',
    precision: 0,
    rating: admin_rating.data('rating'),
    startColor: "#FFAE00",
    endColor: "#FFAE00",
    onChange: function (rating) {
        jQuery("#customer_rate").text(rating);
    },
    onSet: function (rating) {
        jQuery("#customer_review").val(rating);
    }
});