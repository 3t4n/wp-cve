var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;

        matches = [];
        substrRegex = new RegExp(q, 'i');

        // contains the substring `q`, add it to the `matches` array
        jQuery.each(strs, function (i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });

        cb(matches);
    };
};


jQuery('.elex_google_cats_auto .typeahead').typeahead({
    hint: false,
    highlight: false,
    minLength: 1
},
        {
            name: 'google_attr',
            source: substringMatcher(google_prod_category)
        });
