if (typeof (window.Appointy) === 'undefined') {
    window.Appointy = {};
}
function widget(useranme, width, height, language) {

    window.Appointy.config = {
        business: useranme,
        defaultTab: 'Schedule',
        extraParameter: '',
        buttonImg: '',
        modal: {
            height: height,
            width: width
        },
        AppointyLanguage: language
    };
    jQuery(document).ready(function () {
        jQuery('#bookAppointy').click(function () {
            jQuery('#app-widget-btn').click();
            return false;
        });
    });
}


