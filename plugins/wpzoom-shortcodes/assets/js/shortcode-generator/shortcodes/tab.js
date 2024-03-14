wpzShortcodeMeta={
    attributes:[
        {
            label:"Tabs",
            id:"content",
            controlType:"tab-control"
        },
        {
            label:"Tabber Title",
            id:"tabberTitle",
            help:"Set an optional main heading for the tabber.",
            defaultText: ''
        },
        {
            label:"CSS Class",
            id:"css",
            help:"Set an optional custom CSS class for the tabber.",
            defaultText: ''
        },
        {
            label:"Unique Tabber ID",
            id:"tabberID",
            help:"Set an optional unique ID for the tabber.",
            defaultText: ''
        }
    ],
    disablePreview:true,
    customMakeShortcode: function(b){
        var a=b.data;
        var tabTitles = [];

        if(!a)return"";

        var c=a.content;
        var tabberStyle = b.style;
        var tabberTitle = b.tabberTitle;
        var tabberID = b.tabberID;
        var tabberClass = b.css;

        var shortcode = '';

        for ( var i = 0; i < a.numTabs; i++ ) {
            var currentField = 'tle_' + ( i + 1 );

            if ( b[currentField] == '' ) {
                tabTitles.push( 'Tab ' + ( i + 1 ) );
            } else {
                var currentTitle = b[currentField];
                currentTitle = currentTitle.replace( /"/gi, "'" );
                tabTitles.push( currentTitle );
            }
        }

        shortcode += '[tabs';

        if ( tabberID != '' ) {
            shortcode += ' id="' + tabberID + '"';
        }

        if ( tabberClass != '' ) {
            shortcode += ' css="' + tabberClass + '"';
        }

        if ( tabberTitle ) {
            shortcode += ' title="' + tabberTitle + '"';
        }

        shortcode += '] ';

        for ( var t in tabTitles ) {
            shortcode += '[tab title="' + tabTitles[t] + '"]' + tabTitles[t] + ' content goes here.[/tab] ';
        }

        shortcode += '[/tabs]';

        return shortcode;
    }
};