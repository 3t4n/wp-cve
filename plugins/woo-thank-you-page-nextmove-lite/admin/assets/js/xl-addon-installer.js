jQuery(document).ready(function ($) {
    $("#xli_product_image").click(function (event) {
        event.preventDefault();
    });
    $('#xl_install_button').on('click', function () {
        var xli_button = $(this);
        var xl_slug = xli_button.data('xl-slug');
        var xl_file = xli_button.data('xl-file');
        var loader = $('#xli_step_one');
        loader.css('opacity', '1');
        var step_1 = $('#xli_activated_img');
        var step_2 = $('#xli_step_two');
        var step_completed = $('#step-completed');
        var step2_completed = $('#step2-completed');
        var xli_setup = $('#xli_setup');
        var xl_setup_link = $('#xl_setup_link');

        xli_button.prop('disabled', true);
        loader.show();

        $.ajax({
            url: xl_installer_data.ajaxUrl,
            type: 'POST',
            data: {
                action: 'xl_addon_installation',
                xl_slug: xl_slug,
                xl_file: xl_file,
                nonce: xl_installer_data.nonce
            },
            success: function (response) {
                xli_button.prop('disabled', true);
                if (response && response.success !== false) {
                    loader.css('opacity', '0');
                    step_1.css('display', 'none');
                    step_2.css('display', 'none');
                    step_completed.css('display', 'block');
                    step2_completed.css('display', 'block');
                    xl_setup_link.css('pointer-events', 'all');
                    xli_setup.css('opacity', '1');
                    var div = $('<div>' + response + '</div>');
                    var paragraphs = div.find('p');
                    var notices = $('<div><div class="xl_installation_alert_success">Plugin installed and activated successfully!</div></div>');
                    $('#xl_installation_alert_success').prepend(notices);

                } else {
                    xli_button.prop('disabled', false);
                    loader.css('opacity', '0');
                    var errorMessage = response && response.data ? response.data : 'Something went wrong. Please try again.';
                    var notice = $('<div><div class="xl_installation_alert">' + errorMessage + '</div></div>');
                    $('#xl_installation_error').prepend(notice);
                }
            },
            error: function (xhr, status, error) {
                xli_button.prop('disabled', false);
                loader.css('opacity', '0');
                console.error(xhr.responseText);
                // Show admin notice for error
                var notice = $('<div class="notice notice-error is-dismissible"><p>Error: ' + xhr.responseText + '</p></div>');
                $('#wpbody-content').prepend(notice);
            }
        });
    });
    var modal = $("#xli_image_modal");
    var img = $("#xli_product_image");
    var modalImg = $("#img01");
    img.on("click", function () {
        modal.css("display", "block");
        modalImg.attr("src", $(this).attr("src"));
        modal.addClass("xli-zoom-in");
    });

    var span = $(".xli_close_btn").eq(0);
    span.on("click", function () {
        modal.css("display", "none");
    });
});
