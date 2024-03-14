/*global SktbuilderUtils*/
/**
 * Create view settings for block
 * 
 * @type @exp;Backbone@pro;View@call;extend
 */
var SktbuilderFieldView = Backbone.View.extend({ // eslint-disable-line no-unused-vars
    className: "settings-item",
    initialize: function(options) {
        this.model = options.model;
        this.side = options.side;
        this.name = options.name;
        this.storage = options.storage;
        this.settings = options.settings;
        this.defaults = options.defaults;
        this.controller = options.controller;
        this.parent = options.parent;
    },
    /**
     * Get value field text
     * @returns {String}
     */
    getValue: function() {
        if (typeof(this.model.get(this.settings.name)) === "undefined") {
            var defaultVal = _.isArray(this.defaults) ? SktbuilderUtils.createCollection(this.defaults) : this.defaults;
            this.model.set(this.settings.name, defaultVal);
        }
        return this.model.get(this.settings.name);
    }
});