//
// Entities for plugin settings
//

App.module('Entities', function (Entities, App, Backbone, Marionette, $, _) {

    Entities.Setting = Backbone.Model.extend({
        idAttribute: 'name',
        sync: function (method, model, options) {
            var that = this;

            if (method === 'read') {
                // load in the setting base on name (id)
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'pc_get_setting',
                            name: model.get('name')
                        },
                        success: function (res) {
                            options.success(res);
                            resolve();
                        },
                        error: function () {
                            options.error();
                            reject();
                        }
                    });
                });
            } else if (method === 'update') {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'pc_update_setting',
                            name: model.get('name'),
                            value: model.get('value')
                        },
                        success: function (res) {
                            options.success(res);
                            resolve();
                        },
                        error: function () {
                            options.error();
                            reject();
                        }
                    });
                });
            }
        }
    });

});