jQuery(document).ready(function ($) {
    $("input[data-depend-id=inet-webkit-smtp-setting]").on("click", function () {
        let value = $(this).val();
        let smtp_host = $("input[data-depend-id=inet-webkit-smtp-host]");
        let smtp_port = $("input[data-depend-id=inet-webkit-smtp-port]");
        let smtp_security = $("input[data-depend-id=inet-webkit-smtp-security]");

        if (value === '1') {
            smtp_host.val('smtp.gmail.com');
            smtp_port.val('465');
            smtp_security.val('ssl');
        } else if (value === '2') {
            smtp_host.val('smtp.yandex.com');
            smtp_port.val('587');
            smtp_security.val('ssl');
        } else {
            smtp_host.val('');
            smtp_port.val('');
            smtp_security.val('');
        }
    });

    $("input[data-depend-id=inet-webkit-smtp-security]").on('change', function () {
        let value = $(this).val();
        let smtp_port = $("input[data-depend-id=inet-webkit-smtp-port]");
        if (value === 'ssl') {
            smtp_port.val('465');
        } else if (value === 'tls') {
            smtp_port.val('587');
        } else {
            smtp_port.val('25');
        }
    });

    $(".btn-smpt-test input").on("click", function () {
        let email = $(".email-smtp-test input").val();
        const result = $(".smtp-test-result");
        $.ajax({
            type: "post",
            url: smtp.url,
            data: {
                'action': 'inet_wk_send_mail',
                'email': email
            },
            beforeSend: function () {
                result.append('<img class="loader-outter" src="' + smtp.icon + '">');
                $(".csf-field-submessage").remove();
            },
            success: function (res) {
                $(".loader-outter").remove();
                if (res === 'success') {
                    result.html('<div class="csf-field csf-field-submessage"><div class="csf-submessage csf-submessage-success">Chúc mừng! Đã gửi mail thành công</div><div class="clear"></div></div>');
                } else {
                    result.html('<div class="csf-field csf-field-submessage"><div class="csf-submessage csf-submessage-danger">Lỗi: Kiểm tra lại cấu hình mail</div><div class="clear"></div></div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('The following error occured: ' + textStatus, errorThrown);
            }
        });
    });
})