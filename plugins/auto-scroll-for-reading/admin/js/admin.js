(function ($) {
    'use strict';

    $(document).on('click', '[data-slug="auto-scroll-for-reading"] .deactivate a', function () {
        swal({
            html:"<h2>Do you want to upgrade to Pro version or permanently delete the plugin?</h2><ul><li>Upgrade: Your data will be saved for upgrade.</li><li>Deactivate: Your data will be deleted completely.</li></ul>",
            footer: '<a href="javascript:void(0);" class="wpg-autoscroll-temporary-deactivation">Temporary deactivation</a>',
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
            confirmButtonClass: "wpg-autoscroll-upgrade-button"
        }).then(function(result) {
            
            if( result.dismiss && result.dismiss == 'close' ){
                return false;
            }

            var upgrade_plugin = false;
            if (result.value) upgrade_plugin = true;
            var data = {
                action: 'deactivate_plugin_option_as',
                upgrade_plugin: upgrade_plugin
            };
            $.ajax({
                url: AutoSrollForReading.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: data,
                success:function () {
                    window.location = $(document).find('[data-slug="auto-scroll-for-reading"]').find('.deactivate').find('a').attr('href');
                }
            });
        });
        return false;
    });

    $(document).on('click', '.wpg-autoscroll-temporary-deactivation', function (e) {
        e.preventDefault();

        $(document).find('.wpg-autoscroll-upgrade-button').trigger('click');

    });
})(jQuery);