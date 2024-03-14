var Translator = {
    data: {},

    add: function (source, text) {
        this.data[source] = text;
    },

    translate: function (source) {
        source = source || '';

        if (typeof this.data[source] != 'undefined') {
            return this.data[source];
        }

        return source;
    }
}