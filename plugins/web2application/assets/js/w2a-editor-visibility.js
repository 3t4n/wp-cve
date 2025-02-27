/* 
 * W2A VISIBILITY
 * web2aplication.com
 */

var w2a_model_cid = null;

function w2a_get_element_id_from_cid(cid) {
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    var eid = iFrameDOM.find('.elementor-element[data-model-cid='+cid+']').data('id');
    return eid;
}

jQuery(window).on( 'load', function() {
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
            //console.log( $scope );
        } );
        
        elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {
            //console.log(model);
            var cid = model.cid;
            w2a_model_cid = cid;
        } );
        elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
            //console.log(model);
            var cid = model.cid;
            w2a_model_cid = cid;
            //var $element = view.$el.find( '.elementor-selector' );
            //console.log($element);
        } );
});

/*
function elementor_navigator_element_toggle(cid) {
    //alert('toggleVisibility');
    jQuery('.elementor-navigator__element[data-model-cid='+cid+']').trigger('request:toggleVisibility');
}
*/

/******************************************************************************/
    
// VISIBILITY HELPERS
function w2a_visibility_is_debug(cid) {   
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    //console.log(cid);console.log(settings);
    if (settings['w2a_visibility_debug']) { // && settings['w2a_visibility_hidden']) {
        //if (Extensions\w2a_Extension_Visibility::is_hidden($settings)) {
            return true;
        //}
    }
    return false;
}
function w2a_visibility_is_hidden(cid) {   
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    //console.log(cid);console.log(settings);
    if (settings['enabled_visibility']) { // && settings['w2a_visibility_hidden']) {
        //if (Extensions\w2a_Extension_Visibility::is_hidden($settings)) {
            return true;
        //}
    }
    return false;
}
function w2a_visibility_toggle(cid, change_data) {
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    if (change_data) {
        if (settings['enabled_visibility']) {
            elementorFrontend.config.elements.data[cid].attributes['enabled_visibility'] = '';
        } else {
            elementorFrontend.config.elements.data[cid].attributes['enabled_visibility'] = 'yes';
            elementorFrontend.config.elements.data[cid].attributes['w2a_visibility_hidden'] = 'yes';
        }
    }
    
    w2a_menu_list_item_toggle(cid);
    w2a_navigator_element_toggle(cid);
    
    // color element hidden
    var eid = w2a_get_element_id_from_cid(cid);
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    if (settings['enabled_visibility']) {
        iFrameDOM.find('.elementor-element[data-id='+eid+']').addClass('dce-visibility-hidden');
    } else {
        iFrameDOM.find('.elementor-element[data-id='+eid+']').removeClass('dce-visibility-hidden');
    }
    
    w2a_enable_save_button();
    return true;
}
function w2a_menu_list_item_toggle(cid) {
    var menu_item = jQuery('.elementor-context-menu[data-cid='+cid+'] .elementor-context-menu-list__item-visibility');
    // update icon
    menu_item.find('.elementor-context-menu-list__item__icon').children('.dce-icon-visibility').toggleClass('dce-icon-visibility-hidden').toggleClass('fa-eye').toggleClass('fa-eye-slash');

    // update text
    var text = (menu_item.find('.elementor-context-menu-list__item__icon').children('.dce-icon-visibility').hasClass('dce-icon-visibility-hidden')) ? 'Show' : 'Hide';
    menu_item.find('.elementor-context-menu-list__item__title').text(text + ' in frontend');
    
    return true;
}
function w2a_navigator_element_toggle(cid) {
    if (w2a_visibility_is_hidden(cid)) {
        jQuery('.elementor-navigator__element[data-model-cid='+cid+'] > .elementor-navigator__item > .dce-elementor-navigator__element__toggle > .dce-icon-visibility').addClass('dce-icon-visibility-hidden').removeClass('fa-eye').addClass('fa-eye-slash');
        jQuery('.elementor-navigator__element[data-model-cid='+cid+']').addClass('dce-visibility-hidden');
    } else {
        jQuery('.elementor-navigator__element[data-model-cid='+cid+'] > .elementor-navigator__item > .dce-elementor-navigator__element__toggle > .dce-icon-visibility').removeClass('dce-icon-visibility-hidden').addClass('fa-eye').removeClass('fa-eye-slash');
        jQuery('.elementor-navigator__element[data-model-cid='+cid+']').removeClass('dce-visibility-hidden');
    }
}
function update_visibility_trigger(cid, eid) {
    if (!eid) {
        var eid = w2a_get_element_id_from_cid(cid);
    }
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    if (w2a_visibility_is_hidden(cid) && w2a_visibility_is_debug(cid)) {
        if (!iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility').length) {
            iFrameDOM.find('.elementor-element[data-id='+eid+']').append('<div class="elementor-dce-visibility"><ul></ul></div>');
            //console.log('appeso '+eid);
            var edv = iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility > ul').first();
            edv.html('');
            jQuery.each(elementorFrontend.config.elements.data[cid].attributes, function(index, element){
                if (element &&
                        index.startsWith('w2a_visibility_') 
                        && !index.endsWith('_selected') 
                        && !index.startsWith('w2a_visibility_fallback_') ) {
                    console.log(element);
                    if (element.length === 0) {
                        return;
                    }
                    if (index == 'w2a_visibility_mode' || index == 'w2a_visibility_debug' || index == 'w2a_visibility_custom_condition_secure') {
                        return;
                    }
                    if (index == 'w2a_visibility_custom_condition_php') {
                        if ('return true;' == element.trim()) {
                            return;
                        }
                    }
                    var subcond = index.split('_');
                            subcond.pop();
                                    subcond = subcond.join('_');
                    if (typeof elementorFrontend.config.elements.data[cid].attributes[subcond] != 'undefined') {
                        return;
                    }
                    edv.html(edv.html() + '<li><b>'+index.substr(15)+':</b> '+element+'</li>');
                }
            });
        }
    } else {
        iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility').remove();
    }
}
    
// VISIBILITY ADDONS    
jQuery(window).load(function() {
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    
    setInterval(function(){
        // add navigator element toggle
        jQuery('.elementor-navigator__item').each(function(){
            if (!jQuery(this).hasClass('dce-visibility__item' )) {
                var element = jQuery(this).closest('.elementor-navigator__element');
                var cid = element.data('model-cid');    
                var eid = w2a_get_element_id_from_cid(cid);
                //console.log('add quick visibility toggle for: '+ cid + ' - ' + eid);
                if (eid) {
                    // add button to force visibility
                    jQuery(this).children('.elementor-navigator__element__toggle').after(
                            '<div class="dce-elementor-navigator__element__toggle" data-cid="'+cid+' data-eid="'+eid+'"><i class="dce-icon-visibility fa fa-eye" aria-hidden="true"></i></div>'
                        );
                    jQuery(this).addClass('dce-visibility__item');

                    // check if element is just hidden
                    /*if (w2a_visibility_is_hidden(cid)) {
                        //console.log('check hidden for: '+ cid);
                        w2a_navigator_element_toggle(cid);
                        iFrameDOM.find('.elementor-element[data-id='+eid+']').addClass('dce-visibility-hidden');//.addClass('dce-visibility-hidden');
                    }*/
                }
            }
        });
        
        jQuery.each(elementorFrontend.config.elements.data, function(cid, element){
            var eid = w2a_get_element_id_from_cid(cid);
            //console.log('add quick visibility toggle for: '+ cid + ' - ' + eid);
            if (eid) {
                // check if element is just hidden
                if (w2a_visibility_is_hidden(cid)) {
                    //console.log('check hidden for: '+ cid);
                    w2a_navigator_element_toggle(cid);
                    iFrameDOM.find('.elementor-element[data-id='+eid+']').addClass('dce-visibility-hidden');//.addClass('dce-visibility-hidden');                
                }
                update_visibility_trigger(cid, eid);
            }
        });

        // add context menu item
        jQuery('.elementor-context-menu').each(function(){
            if (!jQuery(this).find('.elementor-context-menu-list__item-visibility').length && w2a_model_cid) {
                //console.log('append visibility to context menu');
                
                var cid = w2a_model_cid;
                //console.log('add cid to context menu: '+cid);
                jQuery(this).attr('data-cid', cid);
                jQuery(this).find('.elementor-context-menu-list__group-delete').append(
                    '<div class="elementor-context-menu-list__item elementor-context-menu-list__item-visibility" data-cid="'+cid+'"><div class="elementor-context-menu-list__item__icon"><i class="dce-icon-visibility fa fa-eye" aria-hidden="true"></i></div><div class="elementor-context-menu-list__item__title">Hide in frontend</div></div>'
                );

                if (w2a_visibility_is_hidden(cid)) {
                    w2a_menu_list_item_toggle(cid);
                }
                
                /*var menu_item = jQuery(this).find('.elementor-context-menu-list__item-visibility');
                menu_item.hide().slideDown();*/

            }
        });
        
        //console.log(w2a_model_cid);

    }, 1000);
    
    // get model CID on mouse dx click
    iFrameDOM.on('mousedown', '.elementor-element', function(event) {
        if (event.which == 3) {
            //iFrameDOM.find('body').on('contextmenu', function() {
            var eid = jQuery(this).data('id');
            var cid = jQuery(this).data('model-cid');
            var type = jQuery(this).data('element_type');
            w2a_model_cid = cid;
            console.log(type + ' - ' + eid + ' - ' + cid);
            if (w2a_model_cid) {
                return false;
            }
        }
        
    });

});

// VISIBILITY
jQuery(document).ready(function() {
    
    jQuery(document).on('click', '.dce-elementor-navigator__element__toggle', function() {
        var element = jQuery(this).closest('.elementor-navigator__element');
        var cid = element.data('model-cid');   
        var eid = jQuery(this).data('eid');
        //console.log('dce visibility navigator '+cid+' - '+w2a_model_cid);
        if (jQuery('.elementor-control-enabled_visibility').is(':visible')) {
            //console.log('enabled_visibility visible');
            jQuery('.elementor-switch-input[data-setting=enabled_visibility]').click();
        } else {
            w2a_visibility_toggle(cid, true);
        }
        return false;
    });

    jQuery(document).on('click', '.elementor-context-menu-list__item-visibility', function() {
        var cid = w2a_model_cid;
        var menu_item = jQuery(this);
        //console.log('dce visibility context menu '+cid);
        w2a_visibility_toggle(cid, true);
        return false;
    });
    
    jQuery(document).on('change', '.elementor-switch-input[data-setting=enabled_visibility]', function() {
        //var cid = jQuery(this).attr('id').split('-').pop();
        var cid = w2a_model_cid;
        //console.log('dce visibility settings '+cid);
        w2a_visibility_toggle(cid, false);
    });

});
