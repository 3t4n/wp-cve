/*global SktbuilderUtils, BlockModel*/
/**
 * Utils for sktbuilder
 *
 * @version 0.0.1
 * @class  SktbuilderUtils
 */
var SktbuilderUtils = {
    
    /*
    Generate Ids
     */
    genId: function(ignoreId) {
        window.usedSktbuilderIds = window.usedSktbuilderIds || [];

        var newId = ignoreId || parseInt(_.uniqueId());
        while (window.usedSktbuilderIds.indexOf(newId) != -1) {
            newId = parseInt(_.uniqueId());
        }
        window.usedSktbuilderIds.push(newId);
        return newId;

    },
    /**
     * Create Backbone.Model for settings
     *
     * @param {Object} settings
     * @returns {Backbone.Model|SktbuilderUtils.prototype.createModel.model}
     */
    createModel: function(settings) {
        settings.id = SktbuilderUtils.genId(settings.id);
        var model = new BlockModel();

        var newSettings = {};
        for (var i in settings) {
            if (_.isArray(settings[i])) {
                newSettings[i] = this.createCollection(settings[i]);
                model.listenTo(newSettings[i], "change", function() {
                    this.trigger('change', this);
                });

                newSettings[i].forEach(function(model) {
                    model.owner_id = settings.id;
                });
            } else {
                newSettings[i] = settings[i];
            }
            model.set(i, newSettings[i]);
        }

        return model;
    },
    /**
     * Create collection when nested field is array
     *
     * @param {Object} settings
     * @returns {SktbuilderUtils.prototype.createCollection.collection|Backbone.Collection}
     */
    createCollection: function(settings) {
        var collection = new Backbone.Collection();

        for (var i = 0; i < settings.length; i++) {
            var model = this.createModel(settings[i]);
            collection.add(model);
            collection.listenTo(model, 'change', function() {
                this.trigger('change', this);
            });
        }
        return collection;
    },
    /**
     * Get default settings
     *
     * @param {String} blockName
     */
    getDefaultSettings: function(items, blockName) {
        // get config from storage sktbuilderData
        //sktbuilder.storage.sktbuilderData.items
        var values = {},
            settings = {},
            defaults = {};

        if (Array.isArray(items)) {
            settings = _.findWhere(items, { name: blockName }).settings;
            defaults = _.findWhere(items, { name: blockName }).defaults;
        } else {
            settings = items.settings;
            defaults = items.defaults;
        }

        for (var i = 0; i < settings.length; i++) {
            values[settings[i].name] = defaults[settings[i].name];
        }

        values.block = blockName;
        values.lib = items.lib;
        return values;
    }

};
