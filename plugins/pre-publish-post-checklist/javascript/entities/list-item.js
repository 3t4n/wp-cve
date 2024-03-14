//
// List item entities
//

App.module('Entities', function (Entities, App, Backbone, Marionette, $, _) {

    Entities.ListItem = Backbone.Model.extend({
        sync: function (method, model, options) {
            // create, read, update, delete

            if (method === 'create') {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'action': 'pc_create_list_item',
                            description: model.get('description')
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
                            'action': 'pc_update_list_item',
                            id: model.get('id'),
                            description: model.get('description')
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
            } else if (method === 'delete') {
                return $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'pc_delete_list_item', // TEST THIS SHIST OUT
                        itemId: model.get('id')
                    }
                });
            }
        }
    });

    Entities.List = Backbone.Collection.extend({
        model: Entities.ListItem,
        sync: function (method, model, options) {
            // create, read, update, delete

            if (method === 'read') {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'action': 'pc_get_list'
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

    Entities.PageListItem = Backbone.Model.extend({
        /**
         * Returns the completion status
         *
         * @method isComplete
         * @return Boolean
         */
        isComplete: function () {
              if (!!this.attributes.instance && !!this.attributes.instance.status) {
                return (this.attributes.instance.status === "1");
              } else {
                  return false;
              }
        },
        sync: function (method, model, options) {
            // create, read, update, delete

            if (method === 'update') {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'action': 'pc_complete_list_item',
                            postId: App.postId,
                            listItemId: model.get('id'),
                            status: model.get('status')
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
            //else if (method === 'update') {
            //    return new Promise(function (resolve, reject) {
            //        $.ajax({
            //            url: ajaxurl,
            //            type: 'post',
            //            dataType: 'json',
            //            data: {
            //                'action': 'pc_update_list_item',
            //                id: model.get('id'),
            //                description: model.get('description')
            //            },
            //            success: function (res) {
            //                console.log('success@#$@#$');
            //                options.success(res);
            //                resolve();
            //            },
            //            error: function () {
            //                console.log('ERROR )()()(');
            //                options.error();
            //                reject();
            //            }
            //        });
            //    });
            //}
        }
    });

    Entities.PageLists = Backbone.Collection.extend({
        model: Entities.PageListItem,
        /**
         * Returns whether or not the all the list items are complete
         *
         * @method isComplete
         */
        isComplete: function () {
            var complete = true;

            this.models.forEach(function (model) {
                if (model.isComplete() === false) {
                    complete = false;
                }
            });

            return complete;
        },
        sync: function (method, model, options) {
            // create, read, update, delete

            if (method === 'read') {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: ajaxurl,
                        data: {
                            action: 'pc_get_list_info_for_page',
                            pageId: options.id
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