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