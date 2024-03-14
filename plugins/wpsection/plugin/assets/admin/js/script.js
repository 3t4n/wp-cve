/**
 * Admin Scripts
 */

;(function ($, window, doccument, pluginObject) {
    'use strict';


    /**
     * Finally import element
     */
    $(doccument).on('click', '.wpsection-import-window .wpsection-import-button', function () {

        let importButton = $(this),
            localPageID = $('.local-page-id').val(),
            importingPageId = importButton.data('page-id'),
            buttonContent = importButton.html();

        importButton.html(pluginObject.importingText);

        $.ajax({
            type: 'POST',
            url: pluginObject.ajaxurl,
            context: this,
            data: {
                'action': 'import_element',
                'page_id': importingPageId,
                'local_page_id': localPageID,
            },
            success: function (response) {
                if (response.success) {
                    importButton.parent().prepend('<p class="notice notice-success">' + response.data + '</p>');
                } else {
                    importButton.parent().prepend('<p class="notice notice-error">' + response.data + '</p>');
                }
                importButton.html(buttonContent).fadeOut().hide();
            }
        });
    });


    /**
     * Open importer window
     */
    $(doccument).on('click', '.wpsection-template-page .wpsection-import', function () {

        let importWindow = $('.wpsection-import-window');

        $.ajax({
            type: 'POST',
            url: pluginObject.ajaxurl,
            context: this,
            data: {
                'action': 'populate_import_popup',
                'template_id': $(this).data('template'),
                'template_group': $(this).data('template-group'),
            },
            success: function (response) {
                if (response.success) {
                    importWindow.find('.wpsection-import').html(response.data).parent().fadeIn(200)
                }
            }
        });
    });
	
	$(document).ready(function() {
	

	var $btns = $('.btn').click(function() {
	  if (this.id == 'all') {
	    $('.wpsection-templates > a').fadeIn(450);
	  } else {
	    var $el = $('.' + this.id).fadeIn(450);
	    $('.wpsection-templates > a').not($el).hide();
	  }
	  $btns.removeClass('active');
	  $(this).addClass('active');
	})
});


    /**
     * Close importer window
     */
    $(doccument).on('click', '.wpsection-import-window .import-close', function () {
        $(this).parent().parent().fadeOut();
    });


    /**
     * Elements Toggler
     */
    $(doccument).on('click', 'button.btn-style-one', function () {

        let thisButton = $(this),
            controlElements = thisButton.data('control'),
            checkboxes = $('.single-element-column.' + controlElements).find('input[type="checkbox"]');

        if (thisButton.hasClass('enable-btn')) {
            checkboxes.prop('checked', true);
        }

        if (thisButton.hasClass('disable-btn')) {
            checkboxes.prop('checked', false);
        }
    });


    /**
     * Elements Search
     */
    $(doccument).on('keyup', '.template-search > input', function (e) {

        if (e.keyCode === 27) {
            $(this).val('');
        }

        let searchValue = $(this).val(),
            allTemplates = $('.wpsection-template');

        if (searchValue !== '') {
            allTemplates.addClass('hidden');
            $('.wpsection-template[data-filter-tags*="' + searchValue.toLowerCase() + '"]').removeClass('hidden');
        } else {
            allTemplates.removeClass('hidden');
        }
    });

}(jQuery, window, document, wpsection));