let plugin_path = f24_scripts_data['plugin_path'];
let f24_info = f24_scripts_data['f24Info'];
let log_nonce = f24_scripts_data['download_nonce'];
let url = f24_scripts_data['url'];
let WP_DEBUG = f24_scripts_data['wp_debug'];
let F24_DEBUG = f24_scripts_data['f24_debug'];
let logFileName = f24_scripts_data['logFileName'];
let debugEnabled = WP_DEBUG || F24_DEBUG;
console.log('debug enabled :', debugEnabled);

window.addEventListener('load', () => {
    function downloadFile(dataurl, filename)
    {
        var a = document.createElement("a");
        a.href = dataurl;
        a.setAttribute("download", filename);
        var b = document.createEvent("MouseEvents");
        b.initEvent("click", false, true);
        a.dispatchEvent(b);
        return false;
    }

  
    let LOG_DOWNLOAD = document.getElementById('fatt-24-log-download');
    let $ = jQuery;
   
    LOG_DOWNLOAD.addEventListener('click', function (){
        $.ajax({
            method: 'POST',
            data: 'info='+ f24_info + '&nonce='+log_nonce+'&action=download_log',
            url: url,
            dataType: 'json',

            success: function(response)
				{   
				    if(!debugEnabled){
						alert('Il file di log è vuoto. Per scriverlo e scaricarlo devi attivare la modalità di debug di Wordpress');
					} else if(response.t == 1 || response.t == 2){  // prova a scaricare il log anche se l'accesso avviene dall'esterno
						// da qui rinomino il file solo per il click su 'download'
						let today = new Date();
						let dateformat = today.getDate() + '-' + (today.getMonth()+1) + '-' + today.getFullYear() + '_' + today.getHours() + '_' + today.getMinutes();
						let new_filename = 'f24_trace_' + dateformat + '.log';
                        let localFileNameWithPath = logFileName; // mi serve il nome del file
                        let localFileName = localFileNameWithPath.split('/'); // niente cartelle
                        let lastIndex = localFileName.length - 1; // il primo indice di un array è 0
						downloadFile(plugin_path + localFileName[lastIndex], new_filename); // rinomino il file solo al download
					} else {
				    	alert('Errore durante il download.');	
					}
				}
        });
    });
});