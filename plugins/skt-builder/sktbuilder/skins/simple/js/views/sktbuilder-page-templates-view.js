/**
 * Create view for page templates
 *
 * @type @exp;Backbone@pro;View@call;extend
 */
var SktbuilderPageTemplatesView = Backbone.View.extend({ // eslint-disable-line no-unused-vars
    tagName: "div",
    className: 'sktbuilder-templates',
    events: {
        'click .template .image-bg, .template .title': 'clickChoiceTemplateBlock',
        'click .remove': 'clickRemoveTemplateBlock'
    },
    initialize: function(options) {
        this.storage = options.storage;
        this.controller = options.controller;

        this.listenTo(this.storage.pageTemplatesCollection, 'add remove', this.render);
    },
    clickChoiceTemplateBlock: function(evt) {
        var self = this;
        var id = jQuery(evt.currentTarget).parents('[data-id]').data('id');
        this.storage.pageTemplatesCollection.each(function(item) {
            if (parseInt(item.id) === id) {
                self.controller.load(item.get('blocks'));
            }
        });
    },
    clickRemoveTemplateBlock: function(evt) {
        evt.preventDefault();
        var id = jQuery(evt.currentTarget).data('id');
        this.controller.removeTemplateBlock(id);
    },
    render: function() {
        this.$el.html(_.template(this.storage.getSkinTemplate('block-default-templates'))({
            "templates": this.storage.pageTemplatesCollection.toJSON(),
            "block_default_templates_text": this.storage.__('block_default_templates_text', 'Choose one from amazing templates'),
            "text_default_blank": this.storage.__('block_default_blank', "Pick a block to add to the page"),
            "template": this.storage.__('template', 'Template')
        }));

        return this;
    },
});
