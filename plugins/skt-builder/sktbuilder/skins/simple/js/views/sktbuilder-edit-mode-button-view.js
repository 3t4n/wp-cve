/**
 * Create view for toolbar in sktbuilder layout
 *
 * @type @exp;Backbone@pro;View@call;extend
 */
var SktbuilderEditModeButtonView = Backbone.View.extend({ // eslint-disable-line no-unused-vars
    className: 'edit-mode-button',
    events: {
        'click': 'clickEditMode',
        'touchstart': 'clickEditMode'
    },
    /**
     * View SktbuilderEditModeButton
     * @class SktbuilderEditModeButtonView
     * @augments Backbone.View
     * @constructs
     */
    initialize: function(options) {
        this.storage = options.storage;
        this.controller = options.controller;
    },
    clickEditMode: function() {
        this.controller.setEditMode();
    },
    setPreviewMode: function() {
        var self = this;
        this.controller.layout.sidebar.$el.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
            if (e.target == this) {
                self.$el.addClass('edit-mode-button-show');
                self.controller.layout.sidebar.$el.off('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
            }
        });
    },
    setEditMode: function() {
        this.$el.removeClass('edit-mode-button-show');
    }
});