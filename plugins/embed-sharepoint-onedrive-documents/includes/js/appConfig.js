(function ($) {
$(document).on('click', '#mo_sps_auto_connection_select,#mo_sps_auto_connection_connect', function(e){
    $('#mo_sps_auto_connection_arrow_down').hide();
    $('#mo_sps_auto_connection_arrow_up').show();
    $("#mo_sps_auto_connection_select_drpdn").show();
});
$(document).on('click', "li[id^='mo_sps_auto_connection_type_']", function(e){
    $('#mo_sps_auto_connection_select_drpdn').hide();
    $('#mo_sps_auto_connection_arrow_down').show();
    $('#mo_sps_auto_connection_arrow_up').hide();

    var ele = e.target;
    var id = ele.getAttribute('id');
    $("li[id^='mo_sps_auto_connection_type_']").removeClass('mo_sps_auto_connection_selected_li');
    $(`#${id}`).addClass('mo_sps_auto_connection_selected_li');
    var type = ele.getAttribute('data-type');
    handleBackendCallsForApplicationConfig('mo_sps_auto_connection_save_type',{connection_type:type}).then((res)=>{
        if(!res.success)
            return;
        mo_sps_show_test_connection('auto');
    });
});

$(document).on('click', `input[id^='mo_sps_auto_connection_type_']`, function(e){
    var ele = e.target;
    var type = ele.getAttribute('data-type');
    handleBackendCallsForApplicationConfig('mo_sps_auto_connection_save_type',{connection_type:type}).then((res)=>{
        if(!res.success)
            return;
        mo_sps_show_test_connection('manual');
    });
})

$(document).on("click", function(event){
    if(!$(event.target).closest("#mo_sps_auto_connection_span").length){
        $('#mo_sps_auto_connection_select_drpdn').slideUp('fast');
        $('#mo_sps_auto_connection_arrow_down').show();
        $('#mo_sps_auto_connection_arrow_up').hide();
    }
});


function mo_sps_show_test_connection(type) {
    document.getElementById("app_config").value = "mo_sps_app_test_config_option";
    var db_id = appConfig.db_id != '' ? appConfig.db_id : '';
    var testWindow = window.open(appConfig.test_url + "&type=" + type + (db_id != '' ? "&id=" + db_id : ''), "TEST Connection", "scrollbars=1 width=800, height=600 popup=yes");
    var timer = setInterval(checkChild, 500);
    function checkChild() {
        if (testWindow.closed) {
            clearInterval(timer);
            window.location.reload();
        }
    }
}

function handleBackendCallsForApplicationConfig(task, payload) {
    return $.ajax({
        url: `${appConfig.ajax_url}?action=mo_sps_app_configuration&nonce=${appConfig.nonce}`,
        type: "POST",
        data: {
            task,
            payload
        },
        cache: false,
        success: function (data) {
            return data;
        },
    });
}

})(jQuery);