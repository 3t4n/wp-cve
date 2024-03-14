let $ = jQuery;

window.addEventListener('load', () => {
    // classe personalizzata, non voglio sorprese
    let downloadElements = document.getElementsByClassName('f24 dashicons dashicons-download');
    let downloadButtons = [...downloadElements];
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(){
            let id = button.id;
            sendData(id);
        });
    })

    function sendData(id) {
        $.ajax({
            type: 'POST',
            url: f24_scripts_data['url'],
            data: {
                action: 'download_pdf',
                id: id,
                nonce: f24_scripts_data['download_pdf_file']
            },
            dataType: 'json'    
        }).done(function(r){
           if (r[0] == 1) {
               alert(r[1]);
           }
        }).fail(function(err){
            console.log('error :', err, 'arguments :', arguments);
        }).always(function(){
            window.location.reload();
        });
    }
});    


