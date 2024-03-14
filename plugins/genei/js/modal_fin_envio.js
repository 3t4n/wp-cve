jQuery(function () {
    jQuery('#modal_fin_envio').modal('show');




jQuery("#modal_fin_envio").on('hidden.bs.modal', function () {
    location.href = './admin.php?page=grupoimpultec';
});

});
