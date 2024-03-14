( function( wp ) {
    if ( ! wp ) {
        return;
    }

    wp.plugins.registerPlugin( 'classic-editor-switcher', {
        render: function() {
            return wp.element.createElement(
                wp.editPost.PluginMoreMenuItem,
                {
                    icon: 'edit',
                    href: wp.url.addQueryArgs( document.location.href, { 'classic-editor': '' } ),
                },
                lodash.get( window, [ 'classicEditorSwitcher', 'switcherLabel' ] ) || 'Go to Classic Editor'
            );
        },
    } );
} )( window.wp );
