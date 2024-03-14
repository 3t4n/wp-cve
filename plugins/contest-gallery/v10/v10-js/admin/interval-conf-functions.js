cgJsClassAdmin.intervalConf = cgJsClassAdmin.intervalConf || {};
cgJsClassAdmin.intervalConf.functions = cgJsClassAdmin.intervalConf.functions || {};
cgJsClassAdmin.intervalConf.functions.isShortcodeIntervalConfActiveCheck = function (){
    var $cg_shortcode_table = jQuery('#cg_shortcode_table');

    for(var shortcodeType in cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive){

        if(!cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive.hasOwnProperty(shortcodeType)){
            break;
        }
        if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===true){
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
        }
        if(cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType]===false){
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_off[data-cg-shortcode="'+shortcodeType+'"]').removeClass('cg_hide');
            $cg_shortcode_table.find('.td_gallery_info_shortcode_conf_status_on[data-cg-shortcode="'+shortcodeType+'"]').addClass('cg_hide');
        }
    }
}