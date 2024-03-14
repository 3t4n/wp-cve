var fdp_one_place = {
    button: document.getElementsByClassName('eos-dp-save-' + fdp.page_slug)[0],
    edit_buttons: document.getElementsByClassName('fdp-edit-one-place-plugin'),
    popup: document.getElementById('fdp-one-place-popup'),
    textarea: document.getElementById('fdp-one-place-textarea'),
    plugin_in_popup: document.getElementById('fdp-one-place-plugin-in-popup'),
    close_popup: document.getElementById('fdp-one-place-close-popup'),
    save_popup: document.getElementById('fdp-one-place-save-popup'),
    hidden_input: document.getElementById('fdp-one-place-options'),
    options: fdp_one_place_params.options,
    edited_count: document.getElementsByClassName('fdp-edited-row').length,
    request: new XMLHttpRequest(),
    form: new FormData(),
    error_message: document.getElementsByClassName('eos-dp-opts-msg_failed')[0],
    success_message: document.getElementsByClassName('eos-dp-opts-msg_success')[0],
    collectData: function(){
        fdp_one_place.form.append("nonce",fdp_one_place_params.nonce);
        fdp_one_place.form.append("page_slug",fdp.page_slug);
    },
    sendData: function() {
        this.request.open("POST",fdp_one_place_params.ajaxurl + '?action=' + fdp_one_place_params.action,true);
        this.request.send(fdp_one_place.form);
    },
    getResponse: function() {
        this.request.onload = function(e) {
            fdp_one_place.button.style.backgroundPosition = '-999999px -999999px';
            if(this.readyState === 4) {
                if('0' === e.target.responseText){
                    fdp_one_place.error_message.style.display = 'block';
                }
                else{
                    fdp_one_place.success_message.style.display = 'block';
                }
            }
            else{
                fdp_one_place.error_message.style.display = 'block';
            }
            return false;            
        }
    },   
    init: function(){
        for(var n=0;n<fdp_one_place.edit_buttons.length;++n){
            fdp_one_place.edit_buttons[n].addEventListener('click',function(e){
                fdp_one_place.row = e.target.parentNode.parentNode;
                fdp_one_place.edited_count = document.getElementsByClassName('fdp-edited-row').length;
                if(true !== fdp.pro.active && fdp_one_place.edited_count > 2 && fdp_one_place.row.className.indexOf('fdp-edited-row') < 0){
                    fdp.alert(fdp.max_limit_reached,fdp.max_rows_reached);
                    return;
                }                
                var obj = '' !== fdp_one_place.hidden_input.value ? JSON.parse(fdp_one_place.hidden_input.value.split(' ').join('')) : {};
                fdp_one_place.current_plugin = this.dataset.plugin;
                fdp_one_place.textarea.value = 'undefined' !== typeof(obj[fdp_one_place.current_plugin]) ? obj[fdp_one_place.current_plugin].join('\n') : '';
                fdp_one_place.popup.style.display = 'block';
                fdp_one_place.plugin_in_popup.innerText = fdp_one_place.row.getElementsByClassName('eos-dp-name-td')[0].innerText;
            });
        }
        fdp_one_place.close_popup.addEventListener('click',function(){
            fdp_one_place.popup.style.display = 'none';
        });
        fdp_one_place.save_popup.addEventListener('click',function(){
            fdp_one_place.popup.style.display = 'none';
            var data = '' !== fdp_one_place.hidden_input.value ? JSON.parse(fdp_one_place.hidden_input.value.split(' ').join('')) : {};
            data[fdp_one_place.current_plugin] = fdp_one_place.textarea.value.split(' ').join('\n').split(',').join('\n').split(/\r?\n/);
            fdp_one_place.hidden_input.value = JSON.stringify(data);
            fdp_one_place.form.append('data',fdp_one_place.hidden_input.value);
            fdp_one_place.row.className = fdp_one_place.row.className.replace(' fdp-edited-row','');
            fdp_one_place.row.className += '' !== fdp_one_place.textarea.value ? ' fdp-edited-row' : '';
        });
        fdp_one_place.button.addEventListener('click',function(){
            fdp_one_place.error_message.style.display = 'none';
            fdp_one_place.success_message.style.display = 'none';
            this.style.backgroundPosition = 'center center';
            fdp_one_place.collectData();
            fdp_one_place.sendData();
            fdp_one_place.getResponse();
        });
    }
}
fdp_one_place.init();