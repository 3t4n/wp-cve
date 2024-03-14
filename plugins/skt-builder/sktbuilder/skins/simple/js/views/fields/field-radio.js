var Fields = Fields || {};
Fields.radio = SktbuilderFieldView.extend(
/** @lends Fields.radio.prototype */{
    className: 'field-radio field-group',
    events: {
        'change .radio__input': 'changeInput',
    },
    /**
     * Event change colorpicker
     * @param {Object} evt
     */
    changeInput: function(evt) {
        var target = jQuery(evt.target);
        this.model.set(target.attr('name'), target.attr("id"));
    },
    
    /**
     * Render filed select
     * @returns {Object}
     */
    render: function() {
        var htmldata = {
            "label": this.settings.label,
            "name": this.settings.name,
            "current": this.model.get(this.settings.name) || this.settings.default,
            "options": this.settings.options
        };

        this.$el.html(_.template(this.storage.getSkinTemplate('field-radio-preview'))(htmldata));
        return this;
    }
});
