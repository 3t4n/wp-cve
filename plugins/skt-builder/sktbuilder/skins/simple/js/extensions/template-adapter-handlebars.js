var SktbuilderExtensions = SktbuilderExtensions || {};
SktbuilderExtensions.templating = SktbuilderExtensions.templating || [];

SktbuilderExtensions.templating['handlebars'] = SktbuilderExtensions.templating['hbs'] = function(template) {
    var compiledTemplate = Handlebars.compile(template);
    return function(data) {
        return compiledTemplate(data);
    };
};
