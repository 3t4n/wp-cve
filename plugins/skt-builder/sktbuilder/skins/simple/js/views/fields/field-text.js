var Fields = Fields || {};
Fields.text = SktbuilderFieldView.extend(
/** @lends Fields.text.prototype */{
    className: 'field-text field-group',
    events: {
        'keyup input': 'changeInput'
    },
    /**
     * Event change input
     * @param {Object} evt
     */
    changeInput: function (evt) {
        var target = jQuery(evt.target);
        this.model.set(target.attr('name'), target.val());
    },
    /**
     * Render filed text
     * @returns {Object}
     */
    render: function () {
        var htmldata = {
            "label" : this.settings.label,
            "name" : this.settings.name,
            "value" : this.getValue(),
            "placeholder" : this.settings.placeholder
        };
        this.$el.html(_.template(this.storage.getSkinTemplate('field-text-preview'))(htmldata));
        return this;
    }
});