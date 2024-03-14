(function ($) {
    "use strict";


    var $atfFields, custom_file_frame = {}, $radioImages, $upload;

    $(document).ready(function () {

        $atfFields = $('.atf-fields');
        $radioImages = $('.radio-image');
        $upload = $('.upload-field');

        $atfFields.find('.chosen-select').chosen();

        $atfFields.find('.uploader').find("img[src='']").attr("src", atf_html_helper.url);

        $atfFields.on('click', ".atf-options-upload", function (event) {
            var $this = $(this);
            var activeFileUploadContext = $this.parent();
            var type = (activeFileUploadContext.hasClass('file')) ? 'file' : 'image';

            event.preventDefault();


            // If the media frame already exists, reopen it.
            if (typeof(custom_file_frame[type]) !== "undefined") {
                // console.log(custom_file_frame);
                custom_file_frame[type].open();
                return;
            }

            // if its not null, its broking custom_file_frame's onselect "activeFileUploadContext"
            custom_file_frame[type] = null;

            // Create the media frame.


            custom_file_frame[type] = wp.media.frames.customHeader = wp.media({
                // Set the title of the modal.
                title: $this.data("choose"),

                // Tell the modal to show only images. Ignore if want ALL
                library: (activeFileUploadContext.hasClass('file')) ? {} : { type: 'image' },
                // Customize the submit button.
                button: {
                    // Set the text of the button.
                    text: $this.data("update")
                }
            });

            custom_file_frame[type].on("select", function () {
                // Grab the selected attachment.
                var attachment = custom_file_frame[type].state().get("selection").first();
                console.log(attachment);

                // Update value of the targetfield input with the attachment url.

                $('.atf-options-upload-screenshot', activeFileUploadContext).attr('src', (attachment.attributes.type == 'image') ? attachment.attributes.url : attachment.attributes.icon);
                activeFileUploadContext.find('input').val(attachment.attributes.url).trigger('change');

                $('.atf-options-upload', activeFileUploadContext).hide();
                $('.atf-options-upload-screenshot', activeFileUploadContext).show();
                $('.atf-options-upload-remove', activeFileUploadContext).show();
            });

            custom_file_frame[type].open();
        });

        $atfFields.on('click', '.atf-options-upload-remove', function (event) {
            event.preventDefault();
            $(this).parent().removeMedia();
        });

        $atfFields.find('.atf-options-group').sortable({
            items: "tr.row",
            handle: '.group-row-id',
            opacity: 0.5,
            cursor: 'move',
            axis: 'y',
            helper: 'clone'
        });

        $atfFields.on('click', '.btn-control-group', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $thisRow = $this.parents('.row');
            if ($this.hasClass('plus')) {

                var $newRow = $thisRow.clone();
                $newRow.hide();
                $newRow.insertAfter($thisRow);
                $newRow.resetRow();
                $newRow.fadeIn('slow');
                $newRow.resetOrder();


            } else if ($this.hasClass('minus')) {
                var $sibling = $thisRow.siblings('.row');
                if ($sibling.length > 0) {

                    $thisRow.fadeOut('slow', function () {
                        $thisRow.remove();
                        $sibling.first().resetOrder();
                    });

                } else {
                    $thisRow.resetRow();
                }


            }
        });
        $('.sections-list ul li a').click(
            function () {
                var $this = $(this);
                $('.sections-body .one-section-body.active').removeClass('active');
                $('.sections-body #' + $this.data('section')).addClass('active');
                $this.parents('.sections-list').find('li .active').removeClass('active');
                $this.addClass('active');
                $('.panel-header h2').html($this.html());
                $('.panel-header .section-description').html($this.data('description'));

                return false;

            }
        );


        $radioImages.find("label").height($(this).parent().height());
        //This script switch visible radio buttons and check hidden input fields

        $radioImages.find("label").click(
            function () {
                $(".radio-image label").removeClass("checked");
                $(this).addClass("checked");
                $(".radio-image label input").prop('checked', false);
                $(".radio-image label input").removeAttr('checked');
                $(this).find("input").attr('checked', "checked");
            }
        );

        $(".color-picker-hex").wpColorPicker();

        if ($('.set_custom_images').length > 0) {
            if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $('.wrap').on('click', '.set_custom_images', function (e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    wp.media.editor.send.attachment = function (props, attachment) {
                        id.val(attachment.id);
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            }
        }
        if ($upload.length > 0) {
            upload();
        }

        jQuery('.atf-datepicker').datepicker({
            dateFormat : 'dd-mm-yy'
        });
        
    });

    var upload = function () {
        var $field = $upload.find('input');
        var $list = $upload.find ('ul');

        $atfFields.on('change', '.upload-field input', function (e) {
            var $this = $(this);
            var files = $this.get(0).files;
            var $list = $this.parents('.upload-field').find('ul');
            $list.html('');

            for (var i = 0, numFiles = files.length; i < numFiles; i++) {
                var file = files[i];
                $list.append('<li><span class="dashicons dashicons-media-default"></span> ' + file.name + ' </li>')

            }
            console.log();
        });
    };

    $.fn.extend({
        emptyAtfUpload: function () {
            var $this = $(this);
            $this.val('');
            $this.parents('.upload-field').find('.file-list').find('li').hide('slow', function () {
                $(this).remove();
            });
        }
    });


    //googlefonts

    $('.google-webfonts').each(function () {
        var $this = $(this);

        $this.find('.demotext').text($this.find('.demotextinput').val());
    });

    var WebFontConfig = {
        google: {families: ['Roboto:700:latin,greek']}
    };
    (function () {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();


    $.fn.removeMedia = function () {
        var $mediaContainer = $(this).parent();
        $mediaContainer.find('input').val('');
        $mediaContainer.find('.atf-options-upload').show('slow');
        $mediaContainer.find('.atf-options-upload-screenshot').attr("src", atf_html_helper.url);
        $mediaContainer.find('.atf-options-upload-remove').hide('slow');
    };
    $.fn.resetOrder = function () {
        var i = 1;
        $(this).parent().find('.row').each(function () {
            $(this).find('.group-row-id').text(i);
            i++;
        });
    };

    $.fn.resetRow = function () {
        var rowId = uniqid();
        $(this).find('td').each(function () {
            var $td = $(this);

            if ($td.data('field-name-template') != undefined) {

                var template = $td.data('field-name-template').replace(new RegExp("#", 'g'), rowId);

                if ($td.data('field-type') == 'addMedia') {
                    $td.removeMedia();
                } else {
                    // console.log($td);
                }
                // console.log(template);
                $td.find('.chosen-select').css('display', 'block').next().remove();
                $td.find('input, select')
                    .attr('id', uniqid())
                    .attr('name', template)

                    .val('');
                // $td.append(template);
                // $td.find('.chosen-select').chosen();
                template = '';
            }


        });
    };


    var uniqid = function (pr, en) {
        var pr = pr || '', en = en || false, result;

        var seed = function (s, w) {
            s = parseInt(s, 10).toString(16);
            return w < s.length ? s.slice(s.length - w) : (w > s.length) ? new Array(1 + (w - s.length)).join('0') + s : s;
        };

        result = pr + seed(parseInt(new Date().getTime() / 1000, 10), 8) + seed(Math.floor(Math.random() * 0x75bcd15) + 1, 5);

        if (en) result += (Math.random() * 10).toFixed(8).toString();

        return result;
    };


}(jQuery));