(function ($) {
    'use strict';

    $(document).on('change', 'select.premmerce-carousel-select', function () {
        var $this = $(this);
        var filterAuto = $this.closest('.widget').find('[data-select="premmerce-filter-auto"]');
        var filterCustom = $this.closest('.widget').find('[data-select="premmerce-filter-custom"]');

        if (this.value === 'auto') {
            $(filterAuto).show();
            $(filterCustom).hide();
        }
        else {
            $(filterAuto).hide();
            $(filterCustom).show();
        }
    });

    addBrandsFields();

    function addBrandsFields() {
        var $brandsThumbnailId = $('[data-type="brands_thumbnail_id"]');

        // Only show the "remove image" button when needed
        if (!$brandsThumbnailId.val() || $brandsThumbnailId.val() === '0') {
            $('[data-type="remove_image"]').hide();
        }

        // Uploading files
        var file_frame;

        $(document).on('click', 'button[data-type="upload_image"]', function (event) {


            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.downloadable_file = wp.media({
                title: $('[field-name="choose-image"]').attr('field-value'),
                button: {
                    text: $('[field-name="use-image"]').attr('field-value')
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();
                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                $('[data-type="brands_thumbnail_id"]').val(attachment.id);
                $('[data-type="brands_thumbnail"]').find('img').attr('src', attachment_thumbnail.url);
                $('[data-type="remove_image"]').show();
            });

            // Finally, open the modal.
            file_frame.open();
        });

        $(document).on('click', 'button[data-type="remove_image"]', function () {
            $('[data-type="brands_thumbnail"]').find('img').attr('src', $('[field-name="placeholder-img-src"]').attr('field-value'));
            $('[data-type="brands_thumbnail_id"]').val('');
            $('[data-type="remove_image"]').hide();
            return false;
        });

        $(document).ajaxComplete(function (event, request, options) {
            if (request && 4 === request.readyState && 200 === request.status
                && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
                if (!res || res.errors) {
                    return;
                }
                // Clear Thumbnail fields on submit
                $('[data-type="brands_thumbnail"]').find('img').attr('src', $('[field-name="placeholder-img-src"]').attr('field-value'));
                $('[data-type="brands_thumbnail_id"]').val('');
                $('[data-type="remove_image"]').hide();
                // Clear Display type field on submit
                $('#display_type').val('');
            }
        });
    }

    $(document).on('click', '.row-actions', function () {
        var $this = $(this);
        var scope = $this.closest('tr');
        var brand = scope.find('input[data-input="product_brand"]').val();
        var id = scope.closest('tr').attr('id').split('-')[1];

        if (brand) {
            $('#edit-'+id).find('[name="product_brand"]').find('[value="' + brand + '"]').attr('selected', true);
        }
    });

    $(document).ready(function () {
        $('[data-select="brands"]').each(function (i, obj) {
            var $obj = $(obj);

            if ($obj.closest('#widgets-right').length === 1) {
                $obj.select2();
            }
        });
    });

    $(document).on('widget-updated widget-added', function(e, widget) {
        $(widget).find('[data-select="brands"]').select2();
    });
})(jQuery);
