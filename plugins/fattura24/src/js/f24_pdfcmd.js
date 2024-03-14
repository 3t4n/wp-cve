
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * Script per creare e scaricare il PDF della fattura o FE dal server F24
 * legato a chiamate ajax gestite da hooks.php nell'ordine
 * @param doc_ajax_param è il tipo di documento (I oppure FE)
 * 
 */
function f24_pdfcmd(id,cmd,doc_ajax_param,nonce) {
    jQuery('.wrap').css('cursor','wait');
      
    jQuery.post(ajaxurl, {
        action: 'invoice_admin_command',
        security: nonce,
        args: { id:id, cmd:cmd, type:doc_ajax_param }

    }).done(function(r) {
        if (r[0] == 1) {
            if (cmd == 'update')
                alert('Download da Fattura24 completato')
            jQuery('#cmds-'+id).html(r[1]);

        } else {
            alert(r[1]);
        }
    }).fail(function(err){
        console.log(arguments);
    })
    .always(function(){
        window.location.reload();    
        jQuery('.wrap').css('cursor','auto');
    })
}



