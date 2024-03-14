jQuery(window).on('elementor:init', function() {
	elementor.on('frontend:init', () => {
		elementorFrontend.on('components:init', () => {
			visibilityNavigatorToggle();
			setVisibilityBorder();
		})
	});
});

jQuery(window).on('elementor:init', function() {
	elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {
		var cid = model.cid;
		dce_model_cid = cid;
		temporary_disable_visibility(cid);
	} );
	elementor.hooks.addAction( 'panel/open_editor/column', function( panel, model, view ) {
		var cid = model.cid;
		dce_model_cid = cid;
		temporary_disable_visibility(cid);
	} );
	elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
		var cid = model.cid;
		dce_model_cid = cid;
		temporary_disable_visibility(cid);
	} );

    elementor.channels.editor.on( 'change', ( childView, editedElement ) => {
		if (childView.model.attributes.name !== "enabled_visibility") {
			return;
		}
		visibilityNavigatorToggle();
		setVisibilityBorder();
	});

	// Add Visibility in Context Menu
	elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', function( groups, element ) {
		groups.push(
			{
				name: 'dce_visibility_frontend',
				actions: [
					{
						name: 'toggle_visibility',
						title: 'Toggle Visibility in Frontend',
						icon: 'fa fa-eye',
						callback: function() {
							if (element.model.getSetting('enabled_visibility') == 'yes') {
								element.model.setSetting('enabled_visibility', 'no');
							} else {
								element.model.setSetting('enabled_visibility', 'yes');
							}
						}
					}
				]
			}
		);
		return groups;
	} );

	// Visibility Toggle
    jQuery(document).on('click', '.dce-elementor-navigator__element__toggle', function() {
        var element = jQuery(this).closest('.elementor-navigator__element');
        var cid = element.data('model-cid');
        var eid = jQuery(this).data('eid');
        if (jQuery('.elementor-control-enabled_visibility').is(':visible')) {
            jQuery('.elementor-switch-input[data-setting=enabled_visibility]').click();
        } else {
            dce_visibility_toggle(cid, true);
        }
        return false;
    });
    jQuery(document).on('click', '.elementor-context-menu-list__item-visibility', function() {
        var cid = dce_model_cid;
        dce_visibility_toggle(cid, true);
        return false;
    });
    jQuery(document).on('change', '.elementor-switch-input[data-setting=enabled_visibility]', function() {
        var cid = dce_model_cid;
        dce_visibility_toggle(cid, false);
    });
});

function temporary_disable_visibility(cid) {
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    iFrameDOM.find('.dce-visibility-hidden').removeClass('dce-visibility-no-opacity');
    var eid = dce_get_element_id_from_cid(cid);
    iFrameDOM.find('.elementor-element[data-id='+eid+'].dce-visibility-hidden').addClass('dce-visibility-no-opacity');
}

function dce_visibility_is_debug(cid) {
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    if (settings['dce_visibility_debug']) {
      return true;
    }
    return false;
}
function dce_visibility_is_hidden(cid) {
    if (cid && elementorFrontend.config.elements.data[cid]) {
        var settings = elementorFrontend.config.elements.data[cid].attributes;
        if (settings['enabled_visibility']) {
        	return true;
        }
    }
    return false;
}
function dce_visibility_toggle(cid, change_data) {
    var settings = elementorFrontend.config.elements.data[cid].attributes;
    if (change_data) {
        if (settings['enabled_visibility']) {
            elementorFrontend.config.elements.data[cid].attributes['enabled_visibility'] = '';
        } else {
            elementorFrontend.config.elements.data[cid].attributes['enabled_visibility'] = 'yes';
            elementorFrontend.config.elements.data[cid].attributes['dce_visibility_hidden'] = 'yes';
        }
    }

    dce_navigator_element_toggle(cid);

    // color element hidden
    var eid = dce_get_element_id_from_cid(cid);
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    if (settings['enabled_visibility']) {
        iFrameDOM.find('.elementor-element[data-id='+eid+']').addClass('dce-visibility-hidden');
    } else {
        iFrameDOM.find('.elementor-element[data-id='+eid+']').removeClass('dce-visibility-hidden');
    }

    return true;
}

function dce_navigator_element_toggle(cid) {
  if (dce_visibility_is_hidden(cid)) {
    jQuery('.elementor-navigator__element[data-model-cid='+cid+'] > .elementor-navigator__item > .dce-elementor-navigator__element__toggle > .dce-icon-visibility').addClass('dce-icon-visibility-hidden').removeClass('fa-eye').addClass('fa-eye-slash');
    jQuery('.elementor-navigator__element[data-model-cid='+cid+']').addClass('dce-visibility-hidden');
  } else {
    jQuery('.elementor-navigator__element[data-model-cid='+cid+'] > .elementor-navigator__item > .dce-elementor-navigator__element__toggle > .dce-icon-visibility').removeClass('dce-icon-visibility-hidden').addClass('fa-eye').removeClass('fa-eye-slash');
    jQuery('.elementor-navigator__element[data-model-cid='+cid+']').removeClass('dce-visibility-hidden');
    }
}

function update_visibility_trigger(cid, eid) {
    if (!eid) {
        var eid = dce_get_element_id_from_cid(cid);
    }
    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    if (dce_visibility_is_hidden(cid) && dce_visibility_is_debug(cid)) {
        if (!iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility').length) {
            iFrameDOM.find('.elementor-element[data-id='+eid+']').append('<div class="elementor-dce-visibility"><ul></ul></div>');
            var edv = iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility > ul').first();
            edv.html('');
            jQuery.each(elementorFrontend.config.elements.data[cid].attributes, function(index, element){
                if (element && index.startsWith('dce_visibility_') && !index.endsWith('_selected') && !index.startsWith('dce_visibility_fallback_') ) {
                    if (element.length === 0) {
                        return;
                    }
                    if (index == 'dce_visibility_text_fallback' || index == 'dce_visibility_mode' || index == 'dce_visibility_debug' || index == 'dce_visibility_custom_condition_secure') {
                        return;
                    }
                    if (index == 'dce_visibility_custom_condition_php') {
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
                    var eleval = element;
                    if (typeof eleval === 'object') {
                        if (!eleval.size) {
                            return;
                        }
                        eleval = eleval.size;
                    }
                    edv.html(edv.html() + '<li><b>'+index.substr(15)+':</b> '+eleval+'</li>');
                }
            });
        }
    } else {
        iFrameDOM.find('.elementor-element[data-id='+eid+'] > .elementor-dce-visibility').remove();
    }
}

function visibilityNavigatorToggle() {
	jQuery('.elementor-navigator__item').each(function(){
		if (!jQuery(this).hasClass('dce-visibility__item' )) {
			var element = jQuery(this).closest('.elementor-navigator__element');
			var cid = element.data('model-cid');
			var eid = dce_get_element_id_from_cid(cid);
			if (eid) {
				// add button to force visibility
				jQuery(this).children('.elementor-navigator__element__toggle').after(
						'<div class="dce-elementor-navigator__element__toggle" data-cid="'+cid+' data-eid="'+eid+'"><i class="dce-icon-visibility fa fa-eye" aria-hidden="true"></i></div>'
					);
				jQuery(this).addClass('dce-visibility__item');
			}
		}
	});
}

function setVisibilityBorder() {
	var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
	if ( window.elementorFrontend ) {
		jQuery.each(elementorFrontend.config.elements.data, function(cid, element){
			var eid = dce_get_element_id_from_cid(cid);
			if (eid) {
				// check if element is just hidden
				if (dce_visibility_is_hidden(cid)) {
					dce_navigator_element_toggle(cid);
					iFrameDOM.find('.elementor-element[data-id='+eid+']').addClass('dce-visibility-hidden');
				}
				update_visibility_trigger(cid, eid);
			}
		});
	}
}
