var App = new Marionette.Application();

(function ($) {

    jQuery(function () {

        if ($('.pc-admin-settings').length > 0) {
            App.addInitializer(function () {
                // launch a router or something
                console.log('%c%s', 'color: purple;', 'started marionette application for settings page');

                App.addRegions({
                    'settingsRegion': '.pc-admin-settings',
                    'defaultChecklistRegion': '.pc-default-checklist-region'
                });

                new App.AdminSettings.Controller({
                    region: App.settingsRegion
                });

                new App.AdminList.Controller({
                    region: App.defaultChecklistRegion
                });

                new App.AdminHelp.Controller({});
            });
        }

        if ($('#pc-meta-box').length > 0) {
            App.addInitializer(function () {
                // launch a router or something
                console.log('%c%s', 'color: purple;', 'started marionette application for post page');

                App.postId = pcPostId;
                App.pageLink = pcPageLink;

                $('body').append('<div class="js-pc-modal-region"></div>');

                App.addRegions({
                    'mainRegion': '#pc-meta-box .inside',
                    'modalRegion': '.js-pc-modal-region'
                });

                new App.PostModule.Controller({
                    region: App.mainRegion
                });
            });
        }

        App.start();
    });

})(jQuery);