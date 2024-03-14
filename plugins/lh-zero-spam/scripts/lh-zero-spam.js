(function() {
    
    

    
    
function add_nonce_input(element){
    
    var add_input = document.createElement('input');
    
    add_input.setAttribute('class', 'lh_zero_spam-nonce_value');
    add_input.setAttribute('type', 'hidden');
    add_input.setAttribute('name', 'lh_zero_spam-nonce_value');
    add_input.value = document.currentScript.getAttribute('data-nonce_holder'); 
    
    element.appendChild(add_input);
}

function add_init_input(element){
    
    var add_input = document.createElement('input');
    var date = new Date();
    
    add_input.setAttribute('class', 'lh_zero_spam-init_value');
    add_input.setAttribute('type', 'hidden');
    add_input.setAttribute('name', 'lh_zero_spam-init_value');
    add_input.value = Math.floor(date.getTime() / 1000); 
    
    element.appendChild(add_input);
}

function boot(){
    
    if (document.currentScript.getAttribute('data-nonce_holder')){
        
        var i;
    
        var nonce_inputs = document.querySelectorAll("input.lh_zero_spam-nonce_value"); 
    
        for (i=0; i< nonce_inputs.length; i++) {
    
            nonce_inputs[i].value  = document.currentScript.getAttribute('data-nonce_holder');  
    
        }
        
        var init_inputs = document.querySelectorAll("input.lh_zero_spam-init_value"); 
        
        for (i=0; i< init_inputs.length; i++) {
    
            init_inputs[i].value  = Math.floor(date.getTime() / 1000);  
    
        }
        
    
        var form_inputs = document.querySelectorAll("form.lh_zero_spam-add_nonce"); 

        for (var int=0; int< form_inputs.length; int++) {
    
            add_nonce_input(form_inputs[int]);
            add_init_input(form_inputs[int]);
    
        }
        
        form_inputs = document.querySelectorAll("form.woocommerce-checkout"); 

        for (int=0; int< form_inputs.length; int++) {
    
            add_nonce_input(form_inputs[int]);
            add_init_input(form_inputs[int]);
    
        }
        
        if (document.querySelector("#loginform")) {
    
            add_nonce_input(document.querySelector("#loginform"));
            add_init_input(document.querySelector("#loginform"));
            
        }
        
        if (document.querySelector("#signup_form")) {
    
            add_nonce_input(document.querySelector("#signup_form"));
            add_init_input(document.querySelector("#signup_form"));
            
        }
        
        if (document.querySelector("#registerform")) {
    
            add_nonce_input(document.querySelector("#registerform"));
            add_init_input(document.querySelector("#registerform"));
            
        }
        


    }

}

boot();

})();