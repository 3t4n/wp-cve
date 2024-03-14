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
//;
//(function ($) {
//    var deafultListData = [];
//    var editing = false;
//
//    /**
//     * Update a publish checklist setting
//     *
//     * @function updateSetting
//     * @param {String} key
//     * @param {String} value
//     */
//    function updateSetting (key, value) {
//        return new Promise (function (resolve, reject) {
//            // make ajax request to server
//            var data = {
//                'action': 'pc_ajax_update_settings',
//                'key': key,
//                'value': value
//            };
//
//            $.post(ajaxurl, data, function(response) {
//                resolve(response);
//            });
//        });
//    }
//
//    /**
//     * Return a promise for the array of items
//     *
//     * @function fetchListItems
//     * @param [listId]
//     */
//    function fetchListItems (listId) {
//        return new Promise(function (resolve, reject) {
//            var data = {
//                'action': 'pc_ajax_list_item'
//            };
//
//            $.ajax({
//                method: 'get',
//                url: ajaxurl,
//                dataType: "json",
//                data: data,
//                success: function (data, status, xhr) {
//                    resolve(data);
//                },
//                error: function () {
//                    reject();
//                }
//            });
//        });
//    }
//
//    /**
//     * Removes an item from a list
//     *
//     * @param itemId
//     * @returns {Promise}
//     */
//    function deleteChecklistItem (itemId) {
//        return new Promise(function (resolve, reject) {
//            var data = {
//                'action': 'pc_delete_list_item',
//                'itemId': itemId
//            };
//
//            $.ajax({
//                method: 'post',
//                url: ajaxurl,
//                dataType: "json",
//                data: data,
//                success: function (data, status, xhr) {
//                    resolve(data);
//                },
//                error: function () {
//                    reject();
//                }
//            });
//        });
//    }
//
//    function newChecklistItem (text) {
//        var el = $('<tr class="pc-checklist-item is-not-editing"><td><p class="is-not-editing">' + text + '</p><input type="text" class="is-editing list-item__description"/></td><td><button class="is-editing">Save</button><button class="button is-not-editing js-remove">Remove</button></td></tr>');
//
//        // fire off an ajax requests
//        // make ajax request to server
//        var data = {
//            'action': 'pc_ajax_list_item',
//            text: text
//        };
//
//        $.post(ajaxurl, data, function(response) {
//            debugger;
//        });
//
//        // if the user is editing an item...
//        if (editing === true) {
//            el.insertBefore('.pc-edit-list tbody tr:last-child');
//        } else {
//            $('.pc-edit-list tbody').prepend(el);
//        }
//    }
//
//    function startEditingExistingItem (e) {
//        var $checklistItem = $(e.currentTarget).closest('.pc-checklist-item');
//        var $input = $checklistItem.find('input[type=text]');
//        var text = $(e.currentTarget).text();
//
//        $input.val(text);
//        $checklistItem.removeClass('is-not-editing').addClass('is-editing');
//        $input.focus();
//
//
//
//
//
//        console.log('editing');
//        console.log(text);
//    }
//    $(document).on('click', '.pc-checklist-item p', startEditingExistingItem);
//
//
//    function newItemView () {
//        //var el = $('<tr><td><input class="list-item__description" type="text" placeholder="Item Description"/></td><td class="list-item__save"><p>Add</p></td></tr>');
//        var el = $('<tr class="pc-item--new"><td><input class="list-item__description" type="text" placeholder="New item description"/></td><td class="list-item__save"><button class="button button-primary">Save Item</button></td></tr>');
//
//        $('.pc-edit-list tbody').append(el);
//
//        editing = true;
//
//        el.find('input').focus();
//
//        el.find('button').one('click', function () {
//            var val = el.find('input').val().trim();
//
//            if (val.length > 0) {
//                console.log('create me');
//                newChecklistItem(val);
//                el.remove();
//                editing = false;
//            } else {
//                el.find('input').focus()
//            }
//        });
//
//        el.find('input').on('keydown', function (e) {
//            var val = el.find('input').val().trim();
//
//            if(e.keyCode === 13) {
//                if (val.length > 0) {
//                    newChecklistItem(el.find('input').val());
//                    el.remove();
//                    newItemView();
//                    editing = true;
//                } else {
//                    el.find('input').focus()
//                }
//            }
//        });
//    }
//
//    $(function () {
//        $('input[type=radio]').on('change', function (e) {
//            var val = $(this).val();
//            updateSetting('pc_on_publish', val).then(function () {
//                // setting save success
//                $('.save-widget').addClass('is-saved');
//                setTimeout(function () {
//                    $('.save-widget').removeClass('is-saved');
//                }, 2000);
//            });
//        });
//
//        $('.js-add-checklist-item').on('click', function () {
//            console.log('adding item');
//
//            if ($('.pc-edit-list .pc-item--new').length === 0) {
//                newItemView();
//            } else {
//                $('.pc-edit-list .pc-item--new input').focus()
//            }
//        });
//
//        $(document).on('click', '.js-remove', function () {
//            // get the id and remove it
//        });
//
//        fetchListItems().then(function (data) {
//            deafultListData = data;
//            data.forEach(function (item) {
//                var el = $('<tr class="pc-checklist-item is-not-editing" data-checklist-item="' + item.id + '"><td><p class="is-not-editing">' + item.description + '</p><input type="text" class="is-editing list-item__description"/></td><td><button class="button button-primary pc-full-width is-editing">Save</button><button class="button is-not-editing  js-remove">Remove</button></td></tr>');
//                $('.pc-edit-list').append(el);
//
//            });
//        }, function () {
//
//        });
//    });
//
//})(jQuery);
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
/*------------------------------------*\
    Handlebars Helpers
\*------------------------------------*/

;(function () {
    function unescapeHtml(escapedStr) {
        var div = document.createElement('div');
        div.innerHTML = escapedStr;
        var child = div.childNodes[0];
        return child ? child.nodeValue : '';
    };

    Handlebars.registerHelper('unescapeHTML', function(text, options) {
        return unescapeHtml(text);
    });
})();
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
//
// Controller for the post page
//

App.module('PostModule', function (PostModule, App, Backbone, Marionette, $, _) {

    PostModule.Controller = Marionette.Controller.extend({
        initialize: function () {
            var that = this;

            this.attachPublishListener();

            this.fetchData().then(function () {
                var view = new PostModule.ListInstanceView({
                    collection: that.postList
                });

                that.options.region.show(view);
            });
        },

        fetchData: function () {
            this.postList = new App.Entities.PageLists();

            this.onPublishSetting = new App.Entities.Setting({
                name: 'pc_on_publish'
            });

            return Promise.all([this.postList.fetch({
                id: App.postId
            }), this.onPublishSetting.fetch()]);
        },

        attachPublishListener: function () {
            var that = this;
            var viewOpts;

            // todo - check some local list to see if they are all complete
            $('#publish').on('click', function (e, opt1) {

                // if the click was triggered with the special publish event, let it publish
                if (opt1 === 'publish') {
                    return true;
                }

                if (that.postList.isComplete() == true) {
                    // do nothing
                    return true;
                } else if (that.onPublishSetting.get('value') === 'stop') {
                    e.preventDefault();
                    e.stopPropagation();

                    // show a post
                    viewOpts = {
                        'message': 'You must complete all your Pre-Publish Post Checklist items to publish this post.',
                        'cancelText': 'Okay',
                        type: 'notification'
                    };

                    var view = new App.Views.Dialog({
                        model: new Backbone.Model(viewOpts)
                    });

                    App.modalRegion.show(view);

                } else if (that.onPublishSetting.get('value') === 'warn') {
                    e.preventDefault();
                    e.stopPropagation();

                    viewOpts = {
                        'message': 'You haven\'t completed all your Pre-Publish Post Checklist items. What do you want to do?',
                        'confirmText': 'Publish Anyway',
                        'cancelText': 'Don\'t Publish'
                    };

                    var view = new App.Views.Dialog({
                        model: new Backbone.Model(viewOpts)
                    });

                    App.modalRegion.show(view);

                    that.listenTo(view, 'action', function (type) {
                        if (type === 'confirm') {
                            $('#publish').trigger('click', 'publish');
                        }
                    });
                }


            });
        }
    });

});
//
// Views for the post page
//

App.module('PostModule', function (PostModule, App, Backbone, Marionette, $, _) {

    /**
     * View for the individual list items
     *
     * @view PostModule.ListItemInstanceView
     */
    PostModule.ListItemInstanceView = Marionette.ItemView.extend({
        template: pcTemplates['post']['list-item-instance'],
        tagName: 'li',
        className: 'pc-list-item-instance',
        ui: {
            'checkbox': 'input[type=checkbox]'
        },
        events: {
            'change @ui.checkbox': 'checkboxChange'
        },
        onRender: function () {
            if (!!this.model.get('instance').status && this.model.get('instance').status === "1") {
                this.ui.checkbox.prop('checked', 'checked');
            }
        },
        checkboxChange: function (e) {
            var isChecked = $(e.currentTarget).prop('checked') === true ? 1 : 0;

            this.model.set('status', isChecked);
            this.model.save();
        }
    });

    /**
     * View that alerts the user they have no items, and provides link to create one
     *
     * @view PostModule.ListEmptyView
     */
    PostModule.ListEmptyView = Marionette.ItemView.extend({
        template: pcTemplates['post']['empty-list'],
        initialize: function () {
            this.model = new Backbone.Model({
                pageLink: App.pageLink
            });
        }
    });

    /**
     * View for the checklist in a post page
     *
     * @view PostModule.ListInstanceView
     */
    PostModule.ListInstanceView = Marionette.CompositeView.extend({
        template: pcTemplates['post']['list-instance'],
        tagName: 'ul',
        childView: PostModule.ListItemInstanceView,
        emptyView: PostModule.ListEmptyView
    });

});
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
//
// Admin list controller
//

App.module('AdminList', function (AdminList, App, Backbone, Marionette, $, _) {

    AdminList.Controller = Marionette.Controller.extend({
        initialize: function (options) {
            this.fetchListItems();
            this.showLayout();
        },
        listData: null, // store the list collection
        fetchListItems: function () {
            var that = this;

            this.listData = new App.Entities.List({});

            this.listData.fetch().then(function () {
                var view = new AdminList.ListItemsView({
                    collection: that.listData
                });

                that.layout.listRegion.show(view);
            }, function () {

            });
        },

        /**
         * Show the checklist layout
         *
         * @method showLayout
         */
        showLayout: function () {
            var that = this;
            this.layout = new AdminList.Layout();

            this.listenTo(this.layout, 'addItem', function () {
                var view = new AdminList.NewItemView({
                    model: new App.Entities.ListItem()
                });

                that.layout.newItemRegion.show(view);

                that.listenTo(view, 'create', function (model) {
                    model.save().then(function () {
                        that.listData.push(model);
                        view.destroy();
                    }, function () {

                    });
                });
            });

            this.options.region.show(this.layout);
        }
    });

});
//
// Admin list views
//

App.module('AdminList', function (AdminList, App, Backbone, Marionette, $, _) {

    /**
     * View for a new (not saved) list item
     *
     * @view AdminList.NewItemView
     */
    AdminList.NewItemView = Marionette.ItemView.extend({
        template: pcTemplates['admin']['list']['new-item-view'],
        tagName: 'li',
        className: 'pc-list-iteme pc-grid',
        ui: {
            'textInput': 'input[type=text]',
            'submitButton': 'button'
        },
        events: {
            'keypress @ui.textInput': 'onKeyPress',
            'click @ui.submitButton': 'onCreate',

        },

        onShow: function () {
            this.ui.textInput.focus();
        },

        /**
         * On keypress we watch for the enter key
         *
         * @method onKeyPress
         */
        onKeyPress: function (e) {
            if (e.keyCode === 13) {
                this.onCreate();
            }
        },

        /**
         * Triggers up the chain to create the new item
         *
         * @method onCreate
         */
        onCreate: function () {
            var that = this;
            var val = this.ui.textInput.val().trim();
            if (val.length > 0) {
                this.model.set('description', val);
                this.trigger('create', this.model);
            } else {
                this.ui.textInput.focus();
            }
        }
    });

    /**
     * View for an individual list item that's already on the server
     *
     * @view AdminList.ListItemView
     */
    AdminList.ListItemView = Marionette.ItemView.extend({
        template: pcTemplates['admin']['list']['list-item-view'],
        tagName: 'li',
        className: 'pc-grid pc-list-item',
        ui: {
            'removeButton': '.js-remove',
            'saveButton': '.js-save',
            'description': 'p',
            'textField': 'input[type=text]'
        },
        events: {
            'click @ui.removeButton': 'onRemoveClick',
            'click @ui.saveButton': 'completeEdit',
            'click @ui.description': 'onDescriptionClick',
            'keypress @ui.textField': 'onEnterKey'
        },

        onEnterKey: function (e) {
            if (e.keyCode === 13) {
                this.completeEdit();
            }
        },

        /**
         * End editing and save to server
         *
         * @method completeEdit
         */
        completeEdit: function () {
            var that = this;

            this.$el.removeClass('is-editing');
            this.model.set('description', this.ui.textField.val());
            this.model.save().then(function () {
                that.render();
            });
            // todo try to set and save
            // if not valid, do something else
        },

        /**
         * Destroy the item
         *
         * @method onRemoveClick
         */
        onRemoveClick: function () {
            this.model.destroy();
        },

        /**
         * Start editing of item
         *
         * @method onDescriptionClick
         */
        onDescriptionClick: function () {
            this.$el.addClass('is-editing');
            this.ui.textField.val(this.ui.description.text());
            this.ui.textField.focus();
        }


    });

    AdminList.EmptyListView = Marionette.ItemView.extend({
        template: pcTemplates['admin']['list']['empty-list-view'],
        tagName: 'li',
        className: 'pc-grid pc-list-item pc-list-item--empty-view'
    });

    /**
     * View for an individual list item that's already on the server
     *
     * @view AdminList.ListItemView
     */
    AdminList.ListItemsView = Marionette.CollectionView.extend({
        template: pcTemplates['admin']['list']['list-items'],
        tagName: 'ul',
        className: 'pc-list-items',
        emptyView: AdminList.EmptyListView,
        childView: AdminList.ListItemView,
        onShow: function () {
        }
    });

    AdminList.Layout = Marionette.LayoutView.extend({
        template: pcTemplates['admin']['list']['layout'],
        regions: {
            'listRegion': '.js-list-region',
            'newItemRegion': '.js-new-item-region'
        },
        ui: {
            'addItemButton': '.js-add-checklist-item'
        },
        triggers: {
            'click @ui.addItemButton': 'addItem'
        }
    });

});
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