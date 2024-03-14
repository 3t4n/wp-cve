//
// Manage the admin settings
//

App.module('AdminSettings', function (AdminSettings, App, Backbone, Marionette, $, _) {

    AdminSettings.settingsData = new Backbone.Model({
        id: 'pc_on_publish',
        title: 'On publish attempt',
        options: new Backbone.Collection([
            {
                id: 'stop',
                title: "Prevent Publishing",
                body: "Prevent the author from publishing the post."
            },
            {
                id: 'warn',
                title: 'Warn User',
                body: 'Warn the author about incomplete checklist items. They can still publish if they want.'
            },
            {
                id: 'nothing',
                title: 'Do Nothing',
                body: 'Let the author publish without any warnings.'
            }
        ])
    });

    AdminSettings.Controller = Marionette.Controller.extend({
        initialize: function (options) {
            var that = this;

            var view = new AdminSettings.Setting({
                model: AdminSettings.settingsData,
                collection: AdminSettings.settingsData.get('options')
            });

            this.listenTo(view, 'childview:setting:selected', function (childview, value) {
                that.publishSetting.set('value', value);
                that.publishSetting.save().then(function () {
                    view.showSave();
                });
            });

            // fetch the checked settings
            this.getSetting().then(function (data) {
                // id, value
                var item = AdminSettings.settingsData.get('options').findWhere({
                    id: that.publishSetting.get('value')
                });
                item.set('selected', 'true');
            });

            options.region.show(view);
        },

        /**
         * Get the current settings value
         *
         * @method getSettings
         * @returns {Promise}
         */
        getSetting: function () {
            this.publishSetting = new App.Entities.Setting({
                name: 'pc_on_publish'
            });

            return this.publishSetting.fetch();
        }
    });

});