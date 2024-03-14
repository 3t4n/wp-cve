(function (window, document, $, l, undefined) {
    $.fn.getAttributes = function () {
        var attributes = {};

        if (this.length) {
            $.each(this[0].attributes, function (index, attr) {
                attributes[attr.name] = attr.value;
            });
        }

        return attributes;
    };

    $(document).ready(function () {
        $('#extensions-wrapper').detach().appendTo('#cmb2-metabox-upstream_extensions');
        $('div[data-fieldtype="upstream_extensions_wrapper"]').remove();

        $('#extensions-wrapper > .nav-tab-wrapper > a.nav-tab').on('click', function (e) {
            e.preventDefault();

            var self = $(this);

            if (self.prev().length === 0) {
                self.next().removeClass('nav-tab-active');
                self.addClass('nav-tab-active');

                $('#installed-extensions-list').css('display', 'flex');
                $('#non-installed-extensions-list').css('display', 'none');
            } else {
                self.prev().removeClass('nav-tab-active');
                self.addClass('nav-tab-active');

                $('#installed-extensions-list').css('display', 'none');
                $('#non-installed-extensions-list').css('display', 'flex');
            }
        });

        $('#installed-extensions-list').on('click', '[data-action="upstream:extension:activate"]', function (e) {
            e.preventDefault();

            var self = $(this);
            if (self.attr('disabled') === 'disabled') {
                return;
            }

            var wrapper = $(self.parents('article[data-id]'));

            var licenseKeyField = $('input', wrapper);
            licenseKeyField.removeClass('has-error');
            var licenseKey = licenseKeyField.val().trim();
            if (licenseKey.length === 0) {
                licenseKeyField.focus();
                return;
            }

            self.attr('disabled', 'disabled');

            var btnAttrs = self.getAttributes();

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'upstream:extensions:license.validate',
                    key: licenseKey,
                    extension: wrapper.attr('data-id'),
                    nonce: $('#upstream-extensions-nonce').val()
                },
                beforeSend: function (jqXHR, settings) {
                    self.attr('disabled', 'disabled');
                    $('input', wrapper).attr('disabled', 'disabled');
                    self.replaceWith($('<div></div>', btnAttrs));

                    $('[data-action="upstream:extension:activate"]', wrapper).append('<div class="u-loader"></div>');
                },
                success: function (response, textStatus, jqXHR) {
                    $('[data-action="upstream:extension:activate"]', wrapper).replaceWith($('<button></button>', btnAttrs));
                    $('[data-action="upstream:extension:activate"]', wrapper).text(l['LB_ACTIVATE']).attr('disabled', null);
                    $('input', wrapper).attr('disabled', null);

                    if (!response.success) {
                        alert(response.err);
                    } else {
                        if (response.message) {
                            $('.u-license-status', wrapper).text(response.message);
                            wrapper.attr('class', 'license-' + response.license_status);
                        }

                        if (response.license_status === 'invalid') {
                            licenseKeyField.focus();
                        } else {
                            $('.u-license', wrapper).html('<strong>License Key:</strong>&nbsp;<code>' + response.license_key + '</code> - <a href="#" data-action="upstream:extension:change">' + l['LB_CHANGE'] + '</a>');
                            $('.u-license-status', wrapper).html($('.u-license-status', wrapper).html() + ' - <a href="#" data-action="upstream:extension:deactivate">' + l['LB_DEACTIVATE'] + '</a>');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(errorThrown);

                    $('[data-action="upstream:extension:activate"]', wrapper).replaceWith($('<button></button>', btnAttrs));
                    $('[data-action="upstream:extension:activate"]', wrapper).text('Activate').attr('disabled', null);
                    $('input', wrapper).attr('disabled', null);
                }
            });
        });

        $('#installed-extensions-list').on('click', '[data-action="upstream:extension:change"]', function (e) {
            e.preventDefault();

            var self = $(this);
            var wrapper = $(self.parents('article[data-id]'));

            $('.u-license', wrapper).html('<input type="text" size="40" placeholder="' + l['MSG_ENTER_LICENSE'] + '" maxlength="32" autocomplete="false"><button type="button" class="button button-primary" data-action="upstream:extension:activate">' + l['LB_ACTIVATE'] + '</button>');
            $('.u-license-status', wrapper).text(l['MSG_INACTIVE_LICENSE']);
            wrapper.attr('class', 'license-inactive');
        });

        $('#installed-extensions-list').on('click', '[data-action="upstream:extension:deactivate"]', function (e) {
            e.preventDefault();

            var self = $(this);
            var wrapper = $(self.parents('article[data-id]'));

            if (self.attr('disabled') === 'disabled') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'upstream:extensions:license.deactivate',
                    extension: wrapper.attr('data-id'),
                    nonce: $('#upstream-extensions-nonce').val()
                },
                beforeSend: function (jqXHR, settings) {
                    self.text(l['LB_DEACTIVATING']).attr('disabled', 'disabled');
                },
                success: function (response, textStatus, jqXHR) {
                    if (!response.success) {
                        self.text(l['LB_DEACTIVATE']).attr('disabled', null);
                        alert(response.err);
                    } else {
                        $('.u-license', wrapper).html('<input type="text" size="40" placeholder="' + l['MSG_ENTER_LICENSE'] + '" maxlength="32" autocomplete="false"><button type="button" class="button button-primary" data-action="upstream:extension:activate">' + l['LB_ACTIVATE'] + '</button>');
                        $('.u-license-status', wrapper).text(l['MSG_INACTIVE_LICENSE']);
                        wrapper.attr('class', 'license-inactive');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    self.text(l['LB_DEACTIVATE']).attr('disabled', null);

                    console.error(errorThrown);
                }
            });
        });
    });
})(window, window.document, jQuery, upstreamExtensionsLang);
upstreamExtensionsLang = null;
