( function ( wp ) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginSidebar = wp.editPost.PluginSidebar;
    var el = wp.element.createElement;
 
    registerPlugin( 'collapsing-categories-sidebar', {
        render: function () {
            return el(
                PluginSidebar,
                {
                    name: 'collapsing-categories-sidebar',
                    icon: 'admin-post',
                    title: 'Collapsing Categories sidebar',
                },
                'Meta field'
            );
        },
    } );
} )( window.wp );
