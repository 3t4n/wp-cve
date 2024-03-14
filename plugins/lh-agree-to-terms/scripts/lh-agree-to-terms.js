function lh_agree_to_terms_run_js(){

if (document.getElementById("lh_agree_to_terms-accept")){



        document.getElementById("lh_agree_to_terms-accept").oninvalid = function (e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {

                    e.target.setCustomValidity(document.getElementById("lh_agree_to_terms-accept").getAttribute("data-lh_agree_to_terms-validity_message"));

            }
        };

}

}

lh_agree_to_terms_run_js();