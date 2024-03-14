/*global SktbuilderFieldsView */
/**
 * Create field accordion item settings
 * 
 * @type @exp;SktbuilderFieldsView@call;extend
 */

var FieldAccordionItemSettings = SktbuilderFieldsView.extend({ // eslint-disable-line no-unused-vars
    className: "field-accordion-settings",

    /**
     * View field accordion item
     * needed for field accordion
     * @class FieldAccordionItemSettings
     * @augments SktbuilderFieldsView
     * @constructs
     */
    initialize: function(options) {
        SktbuilderFieldsView.prototype.initialize.call(this, options);
    },
    /**
     * Render accordion item settings
     * @returns {Object}
     */
    render: function() {
        this.$el.html(SktbuilderFieldsView.prototype.getHtml.apply(this, arguments));
        return this;
    }
});