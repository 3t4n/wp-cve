var $=jQuery.noConflict();

$(document).ready(function(){
    $("#register_email").keyup(function(e){
        this.setCustomValidity('');
        if(!isAllowingEmailFormat(this.value)) {
            this.reportValidity();
        }
    });
});

function set_custom_validate(input){
    input.setCustomValidity(getCustomValidity(input))
}

function getCustomValidity(input){
    var type = input.type;
    if(!input.validity.valid) {

        // Pattern Check
        if(input.validity.patternMismatch){

            var title_mssg = $(input).attr('data-title');
            if(!title_mssg) title_mssg = $(input).attr('title');
            if($(input).attr('data-pattern-mismatch-error')) {
                if(!isAllowingEmailFormat($(input).val()))
                    return $(input).attr('data-pattern-mismatch-error');

                else if($(input).attr('data-pattern-mismatch-error') && type == "email"){
                    var mssg = "Please include an &#39;@&#39; in the email address. $1 is missing &#39;@&#39;.";
                    if($(input).val().indexOf("@") != -1)
                        mssg = "Please enter a part following &#39;@&#39;. &#39;$1&#39; is incomplete.";

                    title_mssg = mssg.replace("$1", '' + $(input).val() + '').replace(new RegExp(escapeRegExp("&#39;"), 'g'), "'").replace(new RegExp(escapeRegExp("&#34;"), 'g'), "\"");
                }

            }
            return "Please match the requested format." + "\n" + title_mssg;
        }

        // Email
        if(type == "email"){
            var mssg = "Please include an &#39;@&#39; in the email address. $1 is missing &#39;@&#39;.";
            if($(input).val().indexOf("@") != -1)
                mssg = "Please enter a part following &#39;@&#39;. &#39;$1&#39; is incomplete.";

            return mssg.replace("$1", '' + $(input).val() + '').replace(new RegExp(escapeRegExp("&#39;"), 'g'), "'").replace(new RegExp(escapeRegExp("&#34;"), 'g'), "\"");
        }

        return "Please fill out this field.";
    }
    return "";
}

function reset_custom_validate(input){
    input.setCustomValidity("");

    if(input.checkValidity())
        input.setCustomValidity("");
}

function escapeRegExp(str) {
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");

    $('#register_email').on('input', function(e) {
        this.setCustomValidity('');

        if(!isAllowingEmailFormat(this.value)) {
            this.reportValidity();
        }
    });
}

function isAllowingEmailFormat(val) {
    var ar = ["@gm", "@ya","@ho"];
    for (var i = 0; i < ar.length; i++) {
        if(val.indexOf(ar[i]) > -1)
            return false;
    }
    return true;
}