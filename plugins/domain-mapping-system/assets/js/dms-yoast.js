(function ($, dms_yoast_fs) {
    var collector = {
            init: function () {
            },
            translate: function ($string) {
                return dms_yoast_fs.translations[$string] ? dms_yoast_fs.translations[$string] : $string;
            },
            is_premium: function () {
                return dms_yoast_fs.is_premium === '0' ? false : dms_yoast_fs.is_premium === '1'
            }
        },
        controls = {
            init: function () {
                var that = this,
                    body = $('body');
            }
        },
        navs = {
            init: function () {
                var accordions = $('.dmsy-accordion-toggle'),
                    tabs = $('.dmsy-tabs-item');
                // Setup accordions
                if (accordions.length) {
                    // Setup tab clicks
                    accordions.on('click', function (e) {
                        e.preventDefault();
                        $(this).parent().parent().toggleClass('opened').toggleClass('closed')
                    });
                    // Open first one
                    $(accordions[0]).trigger('click');
                }
                // Setup tabs
                if (tabs.length) {
                    tabs.on('click', function (e) {
                        e.preventDefault();
                        $(this).parent().find('.dmsy-tabs-item').toggleClass('active');
                        $($(this).find('a').attr('href')).parent().find('.dmsy-tabs-body').toggleClass('active');
                    });
                }
            }
        },
        media = {
            init: function () {
                var body = $('body');
                /**
                 * Creates media uploader for social images
                 */
                body.on('click', '.dmsy-tabs-upload-button', function (event) {
                    event.preventDefault();
                    if (!collector.is_premium()) {
                        return;
                    }
                    var button = $(event.target),
                        mediaUploader = wp.media({
                            button: {
                                text: 'Select', // l10n.selectAndCrop,
                                close: false
                            },
                            states: [
                                new wp.media.controller.Library({
                                    library: wp.media.query({type: 'image'}),
                                    multiple: false,
                                    date: false,
                                    priority: 20,
                                    suggestedWidth: 512,
                                    suggestedHeight: 512
                                })
                            ]
                        });
                    // On select image 
                    mediaUploader.on("select", function () {
                        var attachment = mediaUploader.state().get('selection').first().toJSON(),
                            container = button.parent();

                        if (!container.find('img.dmsy-tabs-upload-image').length) {
                            let img = document.createElement('img');
                            img.classList.add('dmsy-tabs-upload-image');
                            img.src = attachment.url;
                            container.append(img);
                        } else {
                            container.find('img.dmsy-tabs-upload-image').attr('src', attachment.url);
                        }
                        container.find('input.dmsy-tabs-upload-image-id').val(attachment.id);
                        container.find('input.dmsy-tabs-upload-image-url').val(attachment.url);
                        button.html(collector.translate('Replace image'));
                        // Close media
                        mediaUploader.close();
                    });

                    // Open media
                    mediaUploader.open();
                });

                /**
                 * When image is being removed
                 */
                body.on('click', '.dmsy-tabs-upload-image-remove', function (event) {
                    $(this).parent().find('img.dmsy-tabs-upload-image').remove();
                    $(this).parent().find('input.dmsy-tabs-upload-image-id').val('');
                    $(this).parent().find('input.dmsy-tabs-upload-image-url').val('');
                    $(this).parent().find('button.dmsy-tabs-upload-button').html(collector.translate('Select image'));
                });
            }
        }
    // Document ready event
    $(document).ready(function () {
        // Initializations
        collector.init();
        controls.init();
        navs.init();
        media.init();
    });
})(jQuery, dms_yoast_fs);
