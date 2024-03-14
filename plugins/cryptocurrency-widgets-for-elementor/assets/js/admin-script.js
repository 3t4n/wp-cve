jQuery(document).ready(function ($) {
    const $deleteCacheBtn = $("#ccew-delete-transient");
    const ajaxUrl = $deleteCacheBtn.data('ajax-url');
    const nonce = $deleteCacheBtn.data('ccpw-nonce');

    $deleteCacheBtn.prop("disabled", false).on("click", function (e) {
        e.preventDefault();
        $(this).text('Purging...').prop("disabled", true);
        const requestData = {
            action: 'ccew_delete_transient',
            nonce: nonce
        };
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: requestData,
            success: function (response) {
                if (response !== undefined && response.success == true) {
                    $deleteCacheBtn.text('Purged Cache').prop("disabled", true);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    });
});