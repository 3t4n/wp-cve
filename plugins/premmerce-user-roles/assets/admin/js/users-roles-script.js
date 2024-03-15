var UsersRoles = {
    getRoleCapabilities: function (roleKey) {

        if (roleKey) {

            jQuery.ajax({
                type: 'post',
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: 'getRoleCapabilities',
                    roleKey: roleKey
                },
                success: function (data) {

                    if (!data) {
                        return false;
                    }

                    var rolCapabilities = data.capabilities;

                    jQuery('[fildset-capabilities] input').each(function () {

                        var input = jQuery(this);

                        input.prop("checked", false);

                        jQuery.each(rolCapabilities, function(key){

                            if (input.val() == key) {
                                input.prop( "checked", true );
                            }

                        });
                    });
                }
            });
        }

    },

    selectAllCapabilities: function (obj) {

        var select = jQuery(obj).is(':checked');

        jQuery('[fildset-capabilities] input').each(function () {

            jQuery(this).prop( "checked", select );

        });
    }
};

jQuery(document).ready(function($) {

    $('#submit').click(function() {

        var form = $(this).parents('form');

        if (!validateForm(form)) {
            return false;
        }
    }),

    $('[data-action--delete]').click(function () {
        if (!showNotice.warn()) {
            return false;
        }
    })

});
