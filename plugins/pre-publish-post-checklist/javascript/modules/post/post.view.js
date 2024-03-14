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