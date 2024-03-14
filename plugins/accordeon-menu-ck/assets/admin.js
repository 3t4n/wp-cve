/**
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */

var $ck = jQuery.noConflict();

// Only define the CK namespace if not defined.
CK = window.CK || {};

CK.Text = {
	strings: {},
	'_': function(key, def) {
		return typeof this.strings[key.toUpperCase()] !== 'undefined' ? this.strings[key.toUpperCase()] : def;
	},
	load: function(object) {
		for (var key in object) {
			this.strings[key.toUpperCase()] = object[key];
		}
		return this;
	}
};

$ck(document).ready(function(){

});

function ckGetCheckedItems(name) {
	var list = document.getElementsByName(name);
	var results = [];
	for(var i = 0; i < list.length; i++){
		list[i].checked ? results.push(list[i]):"";
	}
	return results;
}

/**
 * Float the preview on scroll to have it always visible
 */
function ckSetFloatingOnPreview() {
	var el = $ck('#previewarea > .inner');
	el.data('top', el.offset().top);
	el.data('istopfixed', false);
	$ck(window).bind('scroll load', function() { ckFloatElement(el); });
	ckFloatElement(el);
}

/**
 * Float the preview on scroll to have it always visible
 */
function ckFloatElement(el) {
	var $window = $ck(window);
	var winY = $window.scrollTop();
	if (winY > el.data('top') && !el.data('istopfixed')) {
		el.after('<div id="' + el.attr('id') + 'tmp"></div>');
		$ck('#'+el.attr('id')+'tmp').css('visibility', 'hidden').height(el.height());
		el.css({position: 'fixed', zIndex: '1000', marginTop: '30px', top: '20px'})
			.data('istopfixed', true)
			.addClass('istopfixed');
	} else if (el.data('top') >= winY && el.data('istopfixed')) {
		var modtmp = $ck('#'+el.attr('id')+'tmp');
		el.css({position: '', marginTop: ''}).data('istopfixed', false).removeClass('istopfixed');
		modtmp.remove();
	}
}

/**
 * Manage the tabs
 */
function ckInitTabsStyles() {
	$ck('div.cktab:not(.current),div.cktab2:not(.current)').hide();
	$ck('.cktablink,.cktablink2').each(function(i, tab) {
		$ck(tab).click(function() {
			$ck('div.cktab[data-group="'+$ck(tab).attr('data-group')+'"],div.cktab2[data-group="'+$ck(tab).attr('data-group')+'"]').hide();
			$ck('.cktablink[data-group="'+$ck(tab).attr('data-group')+'"],.cktablink2[data-group="'+$ck(tab).attr('data-group')+'"]').removeClass('current');
			if ($ck('#' + $ck(tab).attr('tab')).length)
				$ck('#' + $ck(tab).attr('tab')).show();
			$ck(this).addClass('current');
		});
	});
}

/**
 * Call the media manager
 */
function ckOpenMediaManager(button, siteurl) {
	button = jQuery(button);
	wp.media.model.settings.post.id = 0;
	var file_frame;

	if (file_frame) {
		// Set the post ID to what we want
		// file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		// Open frame
		file_frame.open();
		return;
	} else {
		// Set the wp.media post id so the uploader grabs the ID we want when initialised
		// wp.media.model.settings.post.id = set_to_post_id;
	}

	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
		title: jQuery(this).data('uploader_title'),
		button: {
			text: jQuery(this).data('uploader_button_text'),
		},
		multiple: false  // Set to true to allow multiple files to be selected
	});

	// When an image is selected, run a callback.
	file_frame.on('select', function() {
		// We set multiple to false so only get one image from the uploader
		attachment = file_frame.state().get('selection').first().toJSON();
		// Do something with attachment.id and/or attachment.url here
		url_relative = attachment.url.replace(siteurl, '');
		button.prev('input').val(url_relative).trigger('change');
		// Restore the main post ID
		// wp.media.model.settings.post.id = wp_media_post_id;
	});

	// Finally, open the modal
	file_frame.open();
}

/**
 * Add the spinner icon
 */
function ckAddWaitIcon(button) {
	$ck(button).addClass('ckwait');
}

/**
 * Remove the spinner icon
 */
function ckRemoveWaitIcon(button) {
	$ck(button).removeClass('ckwait');
}

/**
* Encode the fields id and value in json
*/
function ckMakeJsonFields() {
	var fields = new Object();
	$ck('#styleswizard_options input, #styleswizard_options select, #styleswizard_options textarea').each(function(i, el) {
		el = $ck(el);
		if (el.attr('type') == 'radio') {
			if (el.attr('checked')) {
				fields[el.attr('name')] = el.val();
			} else {
				// fields[el.attr('id')] = '';
			}
		} else if (el.attr('id') == 'customcss') {
			fields[el.attr('name')] = el.val()
				// .replace(/{/g, "|bs|")	// bracket start
				// .replace(/}/g, "|be|")	// bracket end
				.replace(/\\/g, "|sl|"); 	// slash
		} else {
			fields[el.attr('name')] = el.val();
		}
	});
	fields = JSON.stringify(fields);

	return fields;
//	return fields.replace(/"/g, "|qq|");
}

/**
* Render the styles from the module helper
*/
function ckPreviewStylesparams() {
	var button = '#cktoolbar_preview';
	// ckAddWaitIcon(button);
	ckAddSpinnerIcon(button);
	var fields = ckMakeJsonFields();
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL + "&task=style.ajaxRenderCss&" + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customstyles: customstyles,
			customcss: $ck('#customcss').val(),
			fields: fields
		}
	}).done(function(code) {
		$ck('#layoutcss').val(code);
		code = ckMakeCssReplacement(code);
		var csscode = '<style>' + code.replace(/\|ID\|/g, '#accordeonck_previewmodule ') + '</style>';
		$ck('#previewarea > .ckstyle').empty().append(csscode);
		ckRemoveSpinnerIcon(button);
		ckUpdateTitleTheme();
		ckLoadGfontStylesheets();

		var menubarbuttonhtml = ckGetMobilebuttonContent($ck('#ckedition input[name=menubarbuttoncontent]:checked').val(), $ck('#menubarbuttoncontentcustomtext').val());
		$ck('#accordeonmenuck-preview-mobile-bar .accordeonmenuck-bar-button').html(menubarbuttonhtml);
		var topbarbuttonhtml = ckGetMobilebuttonContent($ck('#ckedition input[name=topbarbuttoncontent]:checked').val(), $ck('#topbarbuttoncontentcustomtext').val());
		$ck('#accordeonmenuck-preview-mobile .accordeonmenuck-button').html(topbarbuttonhtml);
	}).fail(function() {
		alert(CK.Text._('Failed'));
	});
}

function ckMakeCssReplacement(code) {
	for (var tag in CKCSSREPLACEMENT) {
		var i = 0;
		while (code.indexOf(tag) != -1 && i < 100) {
			code = code.replace(tag, CKCSSREPLACEMENT[tag]);
			i++;
		}
	}
	return code;
}

/**
 * Apply the css classes to the title for the theme selection
 */
function ckUpdateTitleTheme() {
	$ck('#previewarea > .accordeonmenuck').attr('class', 'accordeonmenuck ' + $ck('#titletheme').val() + ' ' + $ck('#titlethemecolor').val());
}

function ckLoadGfontStylesheets() {
	var gfonturls = '';
	$ck('.isgfont').each(function() {
		if ($ck(this).val() == '1') {
			var gfonturl = ckGetFontStylesheet($ck(this).prev('.gfonturl').val());
			gfonturls += gfonturl;
		}
	});

	$ck('#ckeditiongfont').html(gfonturls);
}

function ckGetFontStylesheet(family) {
	if (! family) return '';
	return ("<link href='https://fonts.googleapis.com/css?family="+family+"' rel='stylesheet' type='text/css'>");
}

function ckGetMobilebuttonContent(value, customtextfield_value) {
	switch (value) {
		case 'hamburger':
			var content = '&#x2261;';
			break;
		case 'close':
			var content = '×';
			break;
		case 'custom' :
			var content = customtextfield_value;
			break;
		default :
		case 'none':
			var content = '';
			break;
	}
	return content;
}

/**
* Clear all fields
*/
function ckClearFields() {
	var confirm_clear = confirm('This will delete all your settings and reset the styles. Do you want to continue ?');
	if (confirm_clear == false) return;
	$ck('#styleswizard_options input').each(function(i, field) {
		field = $ck(field);
		if (field.attr('type') == 'radio') {
			field.removeAttr('checked');
		} else {
			field.val('');
			if (field.hasClass('color')) field.css('background','');
		}
	});
	// launch the preview
	ckPreviewStylesparams();
}

/**
* Render the styles from the module helper
*/
function ckSaveEdition(button) {
	if (! $ck('#name').val()) {
		$ck('#name').addClass('invalid').focus();
		alert(CK.Text._('Please give a name'));
		return;
	}

	$ck('#name').removeClass('invalid');
	if (!button) button = '#cktoolbar_save';
	ckAddSpinnerIcon(button);
	var fields = ckMakeJsonFields();
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});

	customstyles = JSON.stringify(customstyles);
	var myurl = ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL + "&task=style.ajaxSaveStyles&" + CKTOKEN;
	// var myurl = URIBASE + "/index.php?option=com_accordeonmenuck&task=ajaxSaveStyles&" + CKTOKEN + "=1";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: $ck('#id').val(),
			name: $ck('#name').val(),
			layoutcss: $ck('#layoutcss').val().replace(/"/g, "|qq|"),
			customstyles: customstyles,
			customcss: $ck('#customcss').val(),
			fields: fields
		}
	}).done(function(code) {
		try {
			var response = JSON.parse(code);
			if (response.result == '1') {
				$ck('#id').val(response.id);
				$ck('#initialname').val($ck('#name').val());
			} else {
				alert(response.message);
			}
			if ($ck('#returnFunc').val() == 'ckSelectStyle') {
				window.parent.ckMobilemenuUpdateStyle(null, $ck('#id').val(), $ck('#name').val())
			}
		}
		catch (e) {
			alert(e);
		}
		ckRemoveSpinnerIcon(button);
	}).fail(function() {
		alert(CK.Text._('Failed'));
	});
}

/**
* Set the stored value for each field
*/
function ckApplyStylesparams() {
	if ($ck('#params').val()) {
		var fields = JSON.parse($ck('#params').val().replace(/\|qq\|/g, "\""));
		for (var field in fields) {
			ckSetValueToField(field, fields[field])
		}
	}
	// launch the preview to update the interface
	ckPreviewStylesparams();
}

/**
* Set the value in the specified field
*/
function ckSetValueToField(id, value) {
	var field = $ck('#' + id);
	if (!field.length) {
		if ($ck('#ckedition input[name=' + id + ']').length) {
			$ck('#ckedition input[name=' + id + ']').each(function(i, radio) {
				radio = $ck(radio);
				if (radio.val() == value) {
					radio.attr('checked', 'checked');
				} else {
					radio.removeAttr('checked');
				}
			});
		}
	} else {
		if (field.hasClass('color')) field.css('background',value);
		$ck('#' + id).val(value);
	}
}

function ckAddSpinnerIcon(btn) {
	if (! $ck(btn).attr('data-class')) var icon = $ck(btn).find('.fa').attr('class');
	$ck(btn).attr('data-class', icon).find('.fa').attr('class', 'fa fa-spinner fa-pulse');
}

function ckRemoveSpinnerIcon(btn) {
	$ck(btn).find('.fa').attr('class', $ck(btn).attr('data-class'));
}

/**
 * Loads the file from the preset and apply it to all fields
 */
function ckLoadPreset(name) {
	var confirm_clear = ckClearFields();
	if (confirm_clear == false) return;

	var button = '#ckpopupstyleswizard_preview';
	ckAddSpinnerIcon(button);

	// ajax call to get the fields
	var myurl = ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL + "&task=style.ajaxLoadPresetFields&" + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		dataType: 'json',
		data: {
			preset: name
		}
	}).done(function(r) {
		if (r.result == 1) {
			var fields = r.fields;
			fields = fields.replace(/\|qq\|/g, '"');
			fields = fields.replace(/\|bs\|/g, '{');
			fields = fields.replace(/\|be\|/g, '}');
			fields = fields.replace(/\|sl\|/g, '\\');
			ckSetFieldsValue(fields);
			// get the value for the custom css
			ckLoadPresetCustomcss(name);
			// ckPreviewStylesparams();
		} else {
			alert('Message : ' + r.message);
			ckRemoveSpinnerIcon(button);
		}
		
	}).fail(function() {
		//alert(Joomla.JText._('Failed'));
	});

	
}

function ckLoadPresetCustomcss(name) {
	var button = '#ckpopupstyleswizard_preview';
	// add_wait_icon(button); // already loaded in the previous ajax function load_preset()
	// ajax call to get the custom css
	var myurl = ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL + "&task=style.ajaxLoadPresetCustomcss&" + CKTOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			folder: name
		}
	}).done(function(r) {
		if (r.substr(0, 7) == '|ERROR|') {
			alert('Message : ' + r);
		} else {
			$ck('#customcss').val(r);
			ckPreviewStylesparams();
		}
		ckRemoveSpinnerIcon(button);
		ckPreviewStylesparams();
	}).fail(function() {

	});
}

function ckSetFieldsValue(fields) {
	var fields = JSON.parse(fields);
	for (field in fields) {
		ckSetValueToField(field, fields[field]);
	}
}

/**
 * Alerts the user about the conflict between gradient and image background
 */
function ckCheckGradientImageConflict(from, field) {
	if ($ck(from).val()) {
		if ($ck('#'+field).val()) {
			alert('Warning : you can not have a gradient and a background image at the same time. You must choose which one you want to use');
		}
	}
}