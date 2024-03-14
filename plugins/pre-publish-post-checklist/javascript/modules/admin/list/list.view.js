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