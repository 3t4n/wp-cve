/*------------------------------------*\
    Handlebars Helpers
\*------------------------------------*/

;(function () {
    function unescapeHtml(escapedStr) {
        var div = document.createElement('div');
        div.innerHTML = escapedStr;
        var child = div.childNodes[0];
        return child ? child.nodeValue : '';
    };

    Handlebars.registerHelper('unescapeHTML', function(text, options) {
        return unescapeHtml(text);
    });
})();