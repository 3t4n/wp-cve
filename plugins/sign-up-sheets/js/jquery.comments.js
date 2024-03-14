/*
 * Author: mitzip
 * More info: mitzip.com
 * License: Public Domain (http://unlicense.org)
 * Requires: jQuery 1.2+
 *
 */
(function($) {
    $.fn.comments = function(regexFilter) {
        // for dumb non-compliant browsers, like Microsoft Internet Explorer
        Node = Node || {
            COMMENT_NODE: 8
        };

        // Using multiple try/catch blocks to catch permission
        // and security errors from various browsers for accessing
        // cross-domain iframes. If no security/permission error
        // is thrown, then using the iframe tag with comments()
        // will return the comments inside the iframe.

        return this.find('*').filter(function() {
            // try to access the contents of the DOM node
            try {
                return typeof $(this).contents() === 'object';
            }
                // if access is denied (Chrome), filter out of results
            catch(e) {
                return false;
            }
        }).contents().filter(function() {
                // try to access node type,
                // in case accessing via contents()
                // doesn't throw error, like in Firefox
                try {
                    return this.nodeType === Node.COMMENT_NODE;
                }
                    // if access is denied, filter out of results
                catch(e) {
                    return false;
                }
            }).filter(function() {
                // if regular expression is provided, filter comments based on it
                if (regexFilter)
                    return regexFilter.test(this.nodeValue);
                // else return all comments found
                else
                    return true;
            });
    }
})(jQuery);