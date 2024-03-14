jQuery(document).ready(function () {
    //set initial state.

    jQuery('#ebay-feeds-for-wordpress-disable-header').change(function () {
        if (this.checked) {
            jQuery('.ebffwp-warningstring').show(100, 'linear');
        } else {
            jQuery('.ebffwp-warningstring').hide(100, 'linear');
        }
    });
});