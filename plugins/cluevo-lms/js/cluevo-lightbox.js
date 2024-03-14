function cluevoCloseLightbox(force) {
  if (cluevoClosingLightbox) return;

  let result = window.dispatchEvent(
    new CustomEvent("cluevo_closing_lightbox", {
      detail: jQuery("#cluevo-module-lightbox-overlay").data(),
      cancelable: !force
    })
  );
  if (!result) return;
  cluevoClosingLightbox = true;
  if (jQuery('#cluevo-module-lightbox-overlay').length > 0) {
    jQuery('#cluevo-module-lightbox-overlay').fadeOut(400, function() {
      if (jQuery('#cluevo-module-lightbox-overlay').length > 0) {
        jQuery('#cluevo-module-lightbox-overlay').remove();
      }
      jQuery('body, html').removeClass('cluevo-module-overlay-active');
      cluevoClosingLightbox = false;
    });
  } else {
    console.log("no lightbox found");
    cluevoClosingLightbox = false;
  }
}

var closeLightbox = cluevoCloseLightbox;

function cluevoOpenLightbox(data, className, content) {
  if (jQuery('#cluevo-module-lightbox-overlay')) {
    jQuery('#cluevo-module-lightbox-overlay').remove();
  }
  moduleId = null;
  itemId = null;
  if (data) {
    moduleId = data.moduleId || false; 
    itemId = data.itemId || null;
  }
  className = className || false; 
  content = content || false;
  if (data && data.hasOwnProperty('hideLightboxCloseButton') && data['hideLightboxCloseButton'] == 1) className += " no-close-button";
  if (data && data.hasOwnProperty('lightboxCloseButtonPosition') && data['lightboxCloseButtonPosition'] == 1) className += " close-button-" + data['lightboxCloseButtonPosition'];
  var classString = (className) ? ' class="' + cluevo_encodeHTML(className) + '"' : '';
  var moduleIdString = (moduleId) ? 'data-module-id="' + parseInt(moduleId, 10) + '"' : '';
  var idString = (itemId) ? 'data-item-id="' + parseInt(itemId, 10) + '"' : '';
  var dataStrings = [];
  for (let key in data) {
    var value = (lodash.isObjectLike(data[key])) ? JSON.stringify(data[key]) : data[key];
    if (value != "")
      dataStrings.push('data-' + cluevo_encodeHTML(lodash.kebabCase(key)) + '="' + cluevo_encodeHTML(value) + '"');
  }
  var dataString = dataStrings.join(' ');
  var closeBtnString = (data && data.hasOwnProperty('lightboxCloseButtonText') && !lodash.isEmpty(data['lightboxCloseButtonText'])) ? data['lightboxCloseButtonText'] : '×';
  var el = '<div id="cluevo-module-lightbox-overlay"' + classString + ' ' + idString + ' ' + moduleIdString + ' ' + dataString + '><div class="cluevo-close-button cluevo-btn">' + cluevo_encodeHTML(closeBtnString) + '</div>';
  if (content && !content.jquery) {
    el += content;
  } else {
    if (!content.jquery) {
      el += '<div class="cluevo-spinner-container"><div class="cluevo-message">' + cluevo_encodeHTML(cluevoStrings.spinner_text) + '</div><div class="cluevo-spinner"><div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div><div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div><div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div></div></div>';
    }
  }
  el += '</div>';
  var lightbox = jQuery(el).appendTo('body');
  if (content instanceof jQuery) {
    content.appendTo(lightbox);
  }
  jQuery('body, html').addClass('cluevo-module-overlay-active');
}

function cluevoChangeLightboxContent(content) {
  if (jQuery('#cluevo-module-lightbox-overlay')) {
    jQuery('#cluevo-module-lightbox-overlay :not(.cluevo-close-button)').remove();
    jQuery(content).appendTo(jQuery('#cluevo-module-lightbox-overlay'));
  }
}

function cluevoShowLightbox() {
  jQuery('#cluevo-module-lightbox-overlay').fadeIn();
}

function cluevoRemoveLightbox() {
  jQuery('#cluevo-module-lightbox-overlay').fadeOut(500, function() {
    jQuery('#cluevo-module-lightbox-overlay').remove();
  });
}

function cluevoShowLightboxSpinner() {
  if(!jQuery('#cluevo-module-lightbox-overlay .cluevo-spinner-container')?.length) {
      jQuery('#cluevo-module-lightbox-overlay').append(jQuery('<div class="cluevo-spinner-container"><div class="cluevo-message">' + cluevo_encodeHTML(cluevoStrings.spinner_text) + '</div><div class="cluevo-spinner"><div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div><div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div><div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div></div></div>'));
  }
  jQuery('#cluevo-module-lightbox-overlay .cluevo-spinner-container').show();
}

function cluevoHideLightboxSpinner() {
  jQuery('#cluevo-module-lightbox-overlay .cluevo-spinner-container').hide();
}
