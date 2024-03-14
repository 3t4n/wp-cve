(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $('.jseverestimport-data').on('click', function () {
        const self = this;
        const modal = document.querySelector('.ET__modal');

        modalItemListing(modal, startImport);

        const startButton = modal.querySelector('#start-import');
        const cancelButton = modal.querySelector('#cancel-import');

        startButton.addEventListener('click', function (e) {
            e.preventDefault();
            modal.classList.add('hidden');
            startImport();
        })
        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();
            modal.classList.add('hidden');
        })
        function startImport() {
            var attr = $(self).attr('disabled');
            if (typeof attr !== typeof undefined && attr !== false) {
                return;
            }
            // Reset response div content.
            $('.jseverestajax-response').empty();
            // Prepare data for the AJAX call
            var data = new FormData();
            data.append('action', 'ET_import_demo_data');
            data.append('security', et_admin_ajax.ajax_nonce);
            data.append('selected', $(self).attr('data-index'));//$('#ET__demo-import-files').val());
            data.append('recommended_actions', JSON.stringify(et_admin_ajax.recommended_actions));
            if ($('#ET__content-file-upload').length) {
                data.append('content_file', $('#ET__content-file-upload')[0].files[0]);
            }
            if ($('#ET__widget-file-upload').length) {
                data.append('widget_file', $('#ET__widget-file-upload')[0].files[0]);
            }
            if ($('#ET__customizer-file-upload').length) {
                data.append('customizer_file', $('#ET__customizer-file-upload')[0].files[0]);
            }
            $(self).closest('.theme').attr('class', 'theme');
            $(self).closest('.theme').find('.notice').remove();
            // AJAX call to import everything (content, widgets, before/after setup)
            ajaxCall(data, $(self));
        }

    });


    function modalItemListing(modal, startImport) {
        const actions = et_admin_ajax.recommended_actions;

        if ("undefined" === typeof actions || !actions || !actions?.length) {

            /**
             * If we have we don't have any recommended actions then start import immediately.
             */
            startImport();
            return;
        }

        modal.classList.remove('hidden');

        let html = '';
        let count = 1;
        Object.keys(actions).forEach(function (action) {
            const pluginData = actions[action];

            if (undefined !== pluginData['optional'] && pluginData['optional']) {
                return;
            }

            html += `<li>${count}. <a href="//wordpress.org/plugins/${pluginData['plugin_slug']}" target="_blank">${pluginData['title']}</a></li>`;
            count++;
        });
        const ul = modal.querySelector('ul');
        ul.innerHTML = html;
    }


    function ajaxCall(data, $this) {
        var $notice_node;
        if ($this.hasClass('no-data-exists')) {

            $('.et-no-data').html('');

            $notice_node = $('.et-no-data');

        } else {
            $notice_node = $this.closest('.theme');
        }
        $.ajax({
            method: 'POST',
            url: et_admin_ajax.ajax_url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.jseverestimport-data').attr('disabled', 'disabled');
                $this.addClass('updating-message');
                $this.closest('.theme').addClass('focus');
                $this.text('Importing..');
            }
        }).done(function (response) {

            if ('undefined' !== typeof response.status && 'newAJAX' === response.status) {
                ajaxCall(data, $this);
            } else if ('undefined' !== typeof response.message) {
                var success = '<div class="notice update-message notice-success notice-alt"><p>' + response.message + '</p></div>';
                $this.closest('.theme').addClass('theme-install-success');
                $notice_node.append(success);
                $('.jseverestajax-loader').hide();
                $this.removeClass('updating-message');
                $this.closest('.theme').removeClass('focus');
                $('.jseverestimport-data').removeAttr('disabled');
            } else {
                var error = '<div class="notice update-message notice-error notice-alt"><p>' + response + '</p></div>';
                $this.closest('.theme').addClass('theme-install-failed');
                $notice_node.append(error);
                $('.jseverestajax-loader').hide();
                $this.removeClass('updating-message');
                $this.closest('.theme').removeClass('focus');
                $('.jseverestimport-data').removeAttr('disabled');
            }
            $this.text('Import Demo');
        }).fail(function (error) {

            var error = '<div class="notice update-message notice-error notice-alt"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>';
            $this.closest('.theme').addClass('theme-install-failed');
            $notice_node.append(error);
            $('.jseverestajax-loader').hide();
            $this.removeClass('updating-message');
            $this.closest('.theme').removeClass('focus');
            $('.jseverestimport-data').removeAttr('disabled');
            $this.text('Import Demo');
        });
    }

    // Switch preview images on select change event, but only if the img element .jseverestpreview-image exists.
    // Also switch the import notice (if it exists).
    $('#ET__demo-import-files').on('change', function () {
        if ($('.jseverestpreview-image').length) {

            // Attempt to change the image, else display message for missing image.
            var currentFilePreviewImage = mg.import_files[this.value]['import_preview_image_url'] || '';
            $('.jseverestpreview-image').prop('src', currentFilePreviewImage);
            $('.jseverestpreview-image-message').html('');

            if ('' === currentFilePreviewImage) {
                $('.jseverestpreview-image-message').html(mg.texts.missing_preview_image);
            }
        }

        // Update import notice.
        var currentImportNotice = mg.import_files[this.value]['import_notice'] || '';
        $('.jseverestdemo-import-notice').html(currentImportNotice);
    });

})(jQuery);
