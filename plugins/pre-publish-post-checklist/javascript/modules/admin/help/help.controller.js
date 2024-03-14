//
// Controller for help popup and video
//

App.module('AdminHelp', function (AdminHelp, App, Backbone, Marionette, $, _) {

    AdminHelp.Controller = Marionette.Controller.extend({
        initialize: function () {
            $('#open-popup').magnificPopup({
                type: 'iframe',
                items: [
                    {
                        src: '//fast.wistia.net/embed/iframe/fcmpq2xv6z',
                        type: 'iframe' // this overrides default type
                    }
                ]
            });
        }
    });

});