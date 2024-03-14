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