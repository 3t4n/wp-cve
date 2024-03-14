(function ($) {
    'use strict';

    $(document).on('click', '[data-slug="ays-chatgpt-assistant"] .deactivate a', function (e) {
        swal({
            html:"<h2>Do you want to upgrade to Pro version or permanently delete the plugin?</h2><ul><li>Upgrade: Your data will be saved for upgrade.</li><li>Deactivate: Your data will be deleted completely.</li></ul>",
            footer: '<a href="javascript:void(0);" class="ays-chatgpt-assistant-temporary-deactivation">Temporary deactivation</a>',
            type: 'question',
            showCloseButton: true,
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Upgrade',
            cancelButtonText: 'Deactivate',
            confirmButtonClass: "ays-chatgpt-assistant-upgrade-button",
            cancelButtonClass: "ays-chatgpt-assistant-cancel-button",
            customClass: ".ays-chatgpt-deactivate-popup",
        }).then(function(result) {
            
            if( result.dismiss && result.dismiss == 'close' ){
                return false;
            }

            var upgrade_plugin = false;
            if (result.value) upgrade_plugin = true;
            var data = {
                action: 'ays_chatgpt_admin_ajax',
                function: 'deactivate_plugin_option',
                upgrade_plugin: upgrade_plugin
            };
            
            $.ajax({
                url: AysChatGptAdmin.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: data,
                success:function () {
                    window.location = $(document).find('[data-slug="ays-chatgpt-assistant"]').find('.deactivate').find('a').attr('href');
                }
            });
        });
        return false;
    });

    $(document).on('click', '.ays-chatgpt-assistant-temporary-deactivation', function (e) {
        e.preventDefault();

        $(document).find('.ays-chatgpt-assistant-upgrade-button').trigger('click');

    });
})(jQuery);