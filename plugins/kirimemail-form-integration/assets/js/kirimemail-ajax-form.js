// Getting parents element from hosted, LP and embed
let form = document.querySelectorAll('.ke-widget-form');
let f = null;
let btnsubmit = null;
let btnsubmit_text_default = null;

if(form || typeof form[0] != 'undefined'){
    f = form[0];
    btnsubmit = form[0].querySelector('button[type="submit"]');
    btnsubmit_text_default = btnsubmit.innerText;
}


// Override onSubmit trigger after excecuted recaptcha
onSubmit = function() {
    if(f){
        submitAjaxForm(f);
    }
}

f.onsubmit = function(e){
    e.preventDefault();

    if(f){
        btnsubmit.disabled = true;
        btnsubmit.innerText = "Loading...";

        // Checking if recaptcha disabled
        let rc = (typeof grecaptcha != 'undefined');
        if(!rc){
            console.log('recaptcha', 0);
            submitAjaxForm(f);
        }
    }
}

function submitAjaxForm(f) {

    // TODO: update alert message
    let formdata = new FormData(f);
    let style_sucess = "color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; display: block; width: 100%;";
    let style_warning = "color: #8a6d3b; background-color: #fcf8e3; border-color: #faebcc; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; display: block; width: 100%;"
    let style_danger = "color: #a94442; background-color: #f2dede; border-color: #ebccd1; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; display: block; width: 100%;";

    let action = f.getAttribute('action-xhr');
    if(!action){
        action = f.action;
    }

    fetch(action, {
        method: f.method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'include',
        body: formdata,
    }).then(function(complete){
        return complete.json();
    }).then(function(json){

        if(json){
            if(json.error < 1){
                f.innerHTML = '<div class="kirimemail-form-description alert alert-success" style="'+style_sucess+'">'+json.message+'</div>';
            }else{
                f.innerHTML = '<div class="kirimemail-form-description alert alert-warning" style="'+style_warning+'">'+json.message+'</div>';
            }

            return;
        }

        f.innerHTML = '<div class="kirimemail-form-description alert alert-danger" style="'+style_danger+'">There is an error, please try again.</div>';
    })
        .catch(function(error){
            console.log('ke_submit', error);
            f.innerHTML = '<div class="kirimemail-form-description alert alert-danger" style="'+style_danger+'">Unexpected error, please try again.</div>';
        })
        .finally(function(){
            btnsubmit.disabled = false;
            btnsubmit.innerText = btnsubmit_text_default;
        });
}
