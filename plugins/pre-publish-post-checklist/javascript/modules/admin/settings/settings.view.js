//
// Views for admin settings
//

App.module('AdminSettings', function (AdminSettings, App, Backbone, Marionette, $, _) {

    AdminSettings.SettingItem = Marionette.ItemView.extend({
        template: pcTemplates.admin.settings['setting-item'],
        modelEvents: {
            'change': 'onModelChange'
        },
        onModelChange: function () {
            if (this.model.get('selected') === 'true') {
                this.$el.find('input').attr('checked', 'checked');
            } else {
                this.$el.find('input').removeAttr('checked');
            }
        },
        events: {
            'change input[type=radio]': 'onRadioChange'
        },
        onRadioChange: function (e) {
            // send the new item up the chain to the controller
            this.trigger('setting:selected', this.model.get('id'));
        }
    });

    AdminSettings.Setting = Marionette.CompositeView.extend({
        template: pcTemplates.admin.settings['layout'],
        childView: AdminSettings.SettingItem,
        childViewContainer: 'fieldset',
        showSave: function () {
            var that = this;

            this.$el.find('.save-widget').addClass('is-saved');
            setTimeout(function () {
                that.$el.find('.save-widget').removeClass('is-saved');
            }, 2000);
        }
    });

});