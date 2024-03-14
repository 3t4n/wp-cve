jQuery(document).ready(function () {
    jQuery(document).on("nfFormSubmitResponse", function (e, response, id) {
        if (response.response && response.response.data && response.response.data.settings && response.response.data.settings.fathom_analytics) {
            fathom.trackGoal(response.response.data.settings.fathom_analytics, 0);
        }
    });
});
