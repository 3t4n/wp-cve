function YrmAdminAccordion() {
	this.reInit();
	this.addAccordion();
	this.colors();
	this.accordionReorder();
}

YrmAdminAccordion.prototype.reInit = function () {

	this.itemsToggle();
	this.changeLabel();
	this.deleteItem();

	this.changeIcons();
	if (typeof yrmBackend != "undefined") {
		yrmBackend.prototype.accordionTypeSwitcher()
	}
};

YrmAdminAccordion.prototype.accordionReorder = function () {

	if (!jQuery('#accordions-content-wrapper').length) {
		return ;
	}
	jQuery('#accordions-content-wrapper').sortable({
		connectWith: ".accordions-wrapper",
		update: function(event, ui) {
		}
	})
}

YrmAdminAccordion.prototype.addAccordion = function () {
	var that = this;
	jQuery('.yrm-add-accordion').unbind('click').bind('click', function (e) {
		e.preventDefault();
		var indexes = jQuery('.accordion-indexes').data('value');
		var maxIndex = Math.max.apply(null, indexes);
		++maxIndex
		var data = {
			action: 'yrm_add_accordion',
			ajaxNonce: yrmBackendData.nonce,
			nextIndex: maxIndex
		};

		jQuery.get(ajaxurl, data, function(response,d) {
			jQuery('.accordions-wrapper').append(response);
			indexes.push(maxIndex)
			jQuery('.accordion-indexes').data('value', indexes)
			const redactorHtml = jQuery('.editor-template-wrapper').html();
			var newId =  "yrm-accordion-content-"+maxIndex;
			var newEditor = redactorHtml.replace(new RegExp(/customEditorId/, 'g'),newId);

			jQuery('.accordion-editor-wrapper').last().html(newEditor)
			wp.editor.initialize(
				'yrm-accordion-content-'+maxIndex,
				{
					html: true,
					tinymce: {
						theme    : 'modern',
						skin     : 'lightgray',
						wpautop  : false,
						language : 'en',
						formats  : {
							alignleft  : [
								{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'left' } },
								{ selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
							],
							aligncenter: [
								{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'center' } },
								{ selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
							],
							alignright : [
								{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'right' } },
								{ selector: 'img,table,dl.wp-caption', classes: 'alignright' }
							],
						},
						relative_urls       : false,
						remove_script_host  : false,
						convert_urls        : false,
						browser_spellcheck  : true,
						fix_list_elements   : true,
						entities            : '38,amp,60,lt,62,gt',
						entity_encoding     : 'raw',
						keep_styles         : true,
						paste_webkit_styles : 'font-weight font-style color',
						preview_styles      : 'font-family font-size font-weight font-style text-decoration text-transform',
						tabfocus_elements   : ':prev,:next',
						plugins    : 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
						resize     : 'vertical',
						menubar    : true,
						indent     : true,
						toolbar1   : 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
						toolbar2   : 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
						toolbar3   : '',
						toolbar4   : '',
						body_class : 'id post-type-post post-status-publish post-format-standard',
						wpeditimage_disable_captions: false,
						wpeditimage_html5_captions  : true

					},
					quicktags: {
						"buttons": "strong,em,link,ul,li,i,ol,ins,code,fullscreen,del,img"
					},
					mediaButtons: false

				}
			);
			that.reInit();
		});
	})
};

YrmAdminAccordion.prototype.itemsToggle = function () {
	jQuery('.yrm-view-element-wrapper').unbind('click').bind('click', function () {
		var status = jQuery(this).data('options');
		var nextContent = jQuery(this).next();

		const className = 'yrm-hide-element';
		var rotateClassName = 'yrm-rotate-90';

		nextContent.addClass(className);
		jQuery('.dashicons-arrow-right-alt2', this).removeClass(rotateClassName)
		var headerIcon = '';
		if (!status) {
			nextContent.removeClass(className);
			jQuery('.dashicons-arrow-right-alt2', this).addClass(rotateClassName)

		}
		jQuery(this).data('options', !status)
	});
}

YrmAdminAccordion.prototype.changeLabel = function () {
	jQuery('.yrm-accordion-label').bind('input', function () {
		var value = jQuery(this).val();
		jQuery(this).parents('.yrm-element-info-wrapper').find('.tab-header-label').text(value)
	});
}

YrmAdminAccordion.prototype.deleteItem = function () {
	var deleteItem = jQuery('.delete-accordion-item');
	deleteItem.bind('click', function (e) {
		e.preventDefault();
		if (!confirm('Are you sure?')) {
			return  false;
		}
		var id = parseInt(jQuery(this).data('key'))
		jQuery(this).parents('.yrm-element-info-wrapper');
		var indexes = jQuery('.accordion-indexes').data('value');
		var filteredIndexes = indexes.filter(function (current) {
			return current != id;
		})
		jQuery('.accordion-indexes').data('value', filteredIndexes)
		jQuery(this).parents('.yrm-element-info-wrapper').remove();
	})
};

YrmAdminAccordion.prototype.colors = function () {
	if (!jQuery('.yrm-accordion-colors').length) {
		return;
	}
	jQuery('.yrm-accordion-colors').minicolors();
}

YrmAdminAccordion.prototype.changeIcons = function () {
	var icons = jQuery('.yrm-accordion-icons');

	if (!icons) {
		return  false;
	}

	icons.bind('change', function () {
		var value = jQuery(this).val();
		var splittedIcons = value.split('_');
		jQuery('.icon-open-wrapper').html('<i class="fa '+splittedIcons[0]+'"></i>')
		jQuery('.icon-close-wrapper').html('<i class="fa '+splittedIcons[1]+'"></i>')
	})
}

jQuery(document).ready(function () {
	new YrmAdminAccordion();
});