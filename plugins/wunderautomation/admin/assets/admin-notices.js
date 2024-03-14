jQuery(document).ready(function ($) {
    $('.wunderauto.notice.is-dismissible').on('click', '.notice-dismiss', function (event) {
        event.preventDefault();

        var $this = $(this);
        if (!$this.parent().data('id')) {
            return;
        }

        var params = new URLSearchParams();
        params.append('action', "wa_dismiss_notice");
        params.append('security', WunderAutoData.admin_notice_nonce);
        params.append('id', $this.parent().data('id'));
        return axios.post(ajaxurl, params)

    });

    $('#export--1').on('click', function (event) {
        var newVal = $(this).is(":checked")
        $(".export-check").prop('checked', newVal);
    });

    $('#submit-import').on('click', function (event) {
        var form = $(this).closest("form");
        form.attr("enctype", "multipart/form-data").attr("encoding", "multipart/form-data");
    });
});
