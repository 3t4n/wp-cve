function YrmDeactivateJs() {
    this.init();
}

YrmDeactivateJs.prototype.init = function () {
    this.deactivate();
};

YrmDeactivateJs.prototype.deactivate = function () {
    var that = this;
    jQuery("tr[data-slug='expand-maker'] .deactivate a").click(function(event) {
        event.preventDefault();
        that.deactivationUrl = jQuery(this).attr("href");
        jQuery("#expander-deactivation-survey-popup-container").show();
    });

    jQuery('.expander-deactivation-survey-popup-overlay').bind('click', function () {
        jQuery("#expander-deactivation-survey-popup-container").hide();
    });

    jQuery('.expander-survey-skip').bind('click', function (e) {
        jQuery("#expander-deactivation-survey-popup-container").hide();
        window.location.replace(that.deactivationUrl);
    });

    jQuery('.expander-deactivation-survey-content-form').submit(function (event) {
        event.preventDefault();
        var savedData = jQuery(this).serialize();
        jQuery("#expander-deactivation-survey-popup-container").hide();
        var data = {
            action: 'expander_storeSurveyResult',
            savedData: savedData,
            token: EXPANDER_DEACTIVATE_ARGS.nonce
        };

        jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
        }).always(function() {
            window.location.replace(that.deactivationUrl);
        });
    });
};

jQuery(document).ready(function () {
    new YrmDeactivateJs;
});