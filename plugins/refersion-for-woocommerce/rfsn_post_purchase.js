lr(function(){
    var customerEmail = rfsn_pp_vars.email;
    if (customerEmail.includes("@")) {
        REFERSION.box.show({
            loc : 'https://www.refersion.com/channels/post_purchase/v2',
            code : rfsn_pp_vars.code,
            customer_first_name : encodeURIComponent(rfsn_pp_vars.first_name),
            customer_last_name : encodeURIComponent(rfsn_pp_vars.last_name),
            customer_email : encodeURIComponent(rfsn_pp_vars.email)
        });
    }
});
function lr(e){var t=document.createElement("script");t.type="text/javascript";if(t.readyState){t.onreadystatechange=function(){if(t.readyState=="loaded"||t.readyState=="complete"){t.onreadystatechange=null;e()}}}else{t.onload=function(){e()}}t.src="https://www.refersion.com/channels/post_purchase/v2/js";document.body.appendChild(t)}