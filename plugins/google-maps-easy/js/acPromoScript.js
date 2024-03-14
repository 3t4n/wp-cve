jQuery(document).ready(function() {

    var $userMail = jQuery('.supsysticOverviewACForm [name="email"]'),
        $userName = jQuery('.supsysticOverviewACForm [name="username"]'),
        $dialog = jQuery('#supsysticOverviewACFormDialog');

    function sendSubscribeMail() {
        var defaultIconClass = jQuery('#subscribe-btn').find('i').attr('class');
        jQuery('#subscribe-btn').find('i').attr('class', 'fa fa-spinner fa-spin');
        jQuery('#subscribe-btn').attr('disabled', true);

        data = {};
        jQuery.each(jQuery('#overview-ac-form').serializeArray(), function(index, obj) {
            data[obj.name] = obj.value;
        });

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                'pl': 'gmp',
                'reqType': 'ajax',
                'mod': 'supsystic_promo',
                'action': 'sendSubscribeMail',
                'data': data,
                '_wpnonce' : GMP_NONCE['gmp_nonce'],
            },
            success: function(response) {
                jQuery('#subscribe-btn').find('i').attr('class', defaultIconClass);
                jQuery('#subscribe-btn').attr('disabled', false);
                jQuery('.supsysticOverviewACFormOverlay').fadeOut();

                jQuery('#supsysticOverviewACFormDialog').find('.message').text('Thank You For Subscribing!');
                jQuery('#supsysticOverviewACFormDialog').dialog({
                    autoOpen: true,
                    resizable: false,
                    width: 500,
                    height: 280,
                    modal: true,
                    buttons: {
                        Close: function() {
                            jQuery('#supsysticOverviewACFormDialog').find('.on-error').hide();
                            jQuery('.supsysticOverviewACFormOverlay').fadeOut();
                            jQuery(this).dialog("close");
                        }
                    }
                });

            },
            fail: function(err) {
                jQuery('#supsysticOverviewACFormDialog').find('.on-error').show();
            }
        });
    }

    jQuery('#subscribe-btn').on('click', function(e) {
        e.preventDefault();
        if (!$userMail.val() || !$userName.val() || !jQuery('#supsysticOverviewACTermsCheckbox').is(':checked')) {
            jQuery('.supsysticOverviewACFormNotification').show();
            return;
        }
        jQuery('.supsysticOverviewACFormNotification').hide();
        jQuery('#subscribe-btn, .supsysticOverviewACBtnRemind, .supsysticOverviewACBtnDisable').attr('disabled', 'disabled').prop('disabled', 'disabled');
        sendSubscribeMail();
    });

    function sendSubscribeRemind() {
        var defaultIconClass = jQuery('.supsysticOverviewACBtnRemind').find('i').attr('class');
        jQuery('.supsysticOverviewACBtnRemind').find('i').attr('class', 'fa fa-spinner fa-spin');
        jQuery('.supsysticOverviewACBtnRemind').attr('disabled', true);
        var form_data = jQuery('#overview-ac-form').serializeArray();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                'pl': 'gmp',
                'reqType': 'ajax',
                'mod': 'supsystic_promo',
                'action': 'sendSubscribeRemind',
                _wpnonce : GMP_NONCE['gmp_nonce'],
            },
            success: function(response) {
                jQuery('.supsysticOverviewACBtnRemind').find('i').attr('class', defaultIconClass);
                jQuery('.supsysticOverviewACBtnRemind').attr('disabled', false);
                jQuery('.supsysticOverviewACFormOverlay').fadeOut();
            },
            fail: function(err) {}
        });
    }
    jQuery('.supsysticOverviewACBtnRemind').on('click', function(e) {
        e.preventDefault();
        sendSubscribeRemind();
    });

    function sendSubscribeDisable() {
        var defaultIconClass = jQuery('.supsysticOverviewACBtnDisable').find('i').attr('class');
        jQuery('.supsysticOverviewACBtnDisable').find('i').attr('class', 'fa fa-spinner fa-spin');
        jQuery('.supsysticOverviewACBtnDisable').attr('disabled', true);
        var form_data = jQuery('#overview-ac-form').serializeArray();
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                'pl': 'gmp',
                'reqType': 'ajax',
                'mod': 'supsystic_promo',
                'action': 'sendSubscribeDisable',
                _wpnonce : GMP_NONCE['gmp_nonce'],
            },
            success: function(response) {
                jQuery('.supsysticOverviewACBtnDisable').find('i').attr('class', defaultIconClass);
                jQuery('.supsysticOverviewACBtnDisable').attr('disabled', false);
                jQuery('.supsysticOverviewACFormOverlay').fadeOut();
            },
            fail: function(err) {}
        });
    }
    jQuery('.supsysticOverviewACBtnDisable').on('click', function(e) {
        e.preventDefault();
        sendSubscribeDisable();
    });

    jQuery('.overview-section-btn').on('click', function() {
        jQuery(".overview-section").hide();
        jQuery(".overview-section[data-section='" + jQuery(this).data("section") + "']").show();
        jQuery('.overview-section-btn-active').removeClass('overview-section-btn-active');
        jQuery(this).addClass('overview-section-btn-active');
    });
    jQuery('.supsysticOverviewACBtnDisable, .supsysticOverviewACClose, .supsysticOverviewACBtnRemind').on('click', function() {
        jQuery('.supsysticOverviewACFormOverlay').fadeOut();
    });
    jQuery('.supsysticOverviewACTerms').on('click', function(){
        jQuery('.supsysticOverviewACFormOverlayTerms').fadeIn();
    });
    jQuery('.supsysticOverviewACFormOverlayTermsClose').on('click', function(){
        jQuery('.supsysticOverviewACFormOverlayTerms').fadeOut();
    });
    jQuery('.overview-section-btn').eq(0).trigger('click');

    if (!GMP_DATA['gmpAcShow']) {
        jQuery('.supsysticOverviewACFormOverlay').hide();
        jQuery('.supsysticOverviewACFormOverlay').remove();
    } else {
        jQuery('.supsysticOverviewACFormOverlay').show();
    }
});
