try{
    var el = wp.element.createElement;
    var __ = wp.i18n.__;
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPostStatusInfo = wp.editPost.PluginPostStatusInfo;
    var TextControl = wp.components.TextControl;

    function MyPostStatusInfoPlugin({}) {
        return el(
            PluginPostStatusInfo,
            { className: 'my-post-status-info'},
            el("P",null, "RabbitLoader will purge the page on save")
        );
    }

    registerPlugin( 'rabbit-loader', {
        render: MyPostStatusInfoPlugin
    } );
}catch(e){
    console.warn(e);
}