//
// Generic component for modal popup confirmation
//

App.module('Views', function (Views, App, Backbone, Marionette, $, _) {

    Views.Dialog = Marionette.LayoutView.extend({
        className: 'pc-popup-source-wrapper',
        ui: {
            'popupSource': '.js-pc-popup-source'
        },
        initialize: function () {
            this.template = pcTemplates['views']['dialog'];

            if (this.model.get('type') === 'notification') {
                this.template = pcTemplates['views']['dialog-notification'];
            }
        },
        onShow: function () {
            var that = this;

            $.magnificPopup.open({
                items: {
                    src: '.js-pc-popup-source'
                },
                callbacks: {
                    close: function () {
                        $(event.currentTarget).removeAttr('disabled');
                    }
                },
                mainClass: 'my-mfp-zoom-in'
            });

            $.magnificPopup.instance.wrap.find('.js-pc-popup-accept').on('click', function () {
                that.onDialogConfirm();
                $.magnificPopup.close();
            });

            $.magnificPopup.instance.wrap.find('.js-pc-popup-close').on('click', function () {
                that.onDialogClose();
                $.magnificPopup.close();
            });
        },
        onDialogConfirm: function () {
            this.trigger('action', 'confirm');
        },
        onDialogClose: function () {
            this.trigger('action', 'close');
        }
    });

});