jQuery(document).ready(function($) {
    $(".plugineye-deactivate").click(function(e) {
        e.preventDefault();
    });

    $('.pe-modal-cancel').click(function(){
        $( ".pe-modal-layer" ).hide();
    });
})

function pe_deactivate_modal(id){
    jQuery("#pe-modal-layer-"+id ).show();
}

function pe_api_on_deactivation_func(plugin_id, token){
    var link = jQuery("#plugineye-deactivate-"+plugin_id).attr("href");
    var reason = jQuery("input[name=answer"+plugin_id+"]:checked").val();
    jQuery.ajax({
        url: pe_api_on_deactivation.ajax_url, 
        type : 'POST',
        data : {
            action : 'pe_deactivation_ajax_function',
            plugin_id : plugin_id,
            token : token,
            reason : reason,
        },
        success: function(){
            window.location = link;
        }
    });

}