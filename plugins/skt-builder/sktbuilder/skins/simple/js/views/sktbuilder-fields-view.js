/*global Fields*/
/**
 * Create view fields for block's settings 
 * 
 * @type @exp;Backbone@pro;View@call;extend
 */
var SktbuilderFieldsView = Backbone.View.extend( // eslint-disable-line no-unused-vars
    /** @lends SktbuilderFieldsView.prototype */
    {
        tagName: "div",
        className: "settings-block-items",
        /**
         * View settings
         * @class SettingsView
         * @augments Backbone.View
         * @constructs
         */
        initialize: function(options) {
            this.model = options.model;
            this.name = options.name;
            this.storage = options.storage;
            this.settings = options.settings;
            this.defaults = options.defaults;
            this.controller = options.controller;
            this.parent = options.parent;
            this.fields = [];
            this.side = this;
        },
        /**
         * Render settings
         * @returns {Object}
         */
        getHtml: function() {
            var res = [];
            for (var i = 0; i < this.settings.length; i++) {
                if (Fields[this.settings[i].type]) {
                    var field = new Fields[this.settings[i].type]({
                        name: this.settings[i].name,
                        model: this.model,
                        storage: this.storage,
                        settings: this.settings[i],
                        defaults: this.defaults[this.settings[i].name],
                        controller: this.controller,
                        parent: this,
                        side: this
                    });

                    this.fields.push(field);
                    res.push(field.render().el);
                } else {
                    throw new Error("Field " + this.settings[i].type + " not found!");
                }
            }
            return res;
        }
    }
);
