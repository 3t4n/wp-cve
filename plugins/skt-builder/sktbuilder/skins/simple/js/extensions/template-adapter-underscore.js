var SktbuilderExtensions = SktbuilderExtensions || {};
SktbuilderExtensions.templating = SktbuilderExtensions.templating || [];

SktbuilderExtensions.templating['underscore'] = function(template) {
    var compiledTemplate = _.template(template);
    return function(data) {
        return compiledTemplate(data);
    };
};