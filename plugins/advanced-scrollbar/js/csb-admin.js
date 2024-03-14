
(function ($) {
    jQuery(document).ready(function ($) {
        var popupContent = '<div class="custom-popup"><div class="popup_content"><h3>Pro Features</h3> <ul><li>Dynamic Full Height Scrollbar</li><li>Gradient Color Options</li><li>Floating Scrollbar and Color options</li><li>Custom Cursor Option</li></ul> </div><a target="_blank" href="https://bplugins.com/products/advanced-scrollbar/#pricing" class="upgrade_btn">Upgrade to Pro</a><span class="closeBtn" >&times;</span></div>';
        $('body').append(popupContent);

        $("span#popupBtn").click(function () {

            $("div.custom-popup").show();


        });

        $("span.closeBtn").click(function () {
            $("div.custom-popup").hide();
        })




    });
}(jQuery));