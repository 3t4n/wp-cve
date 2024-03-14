function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function closeToolbar(link, event) {
    event.preventDefault();
    var cookieValue = getCookie('approve-me-close-bar-ttl');
    var timeToLive = 2;
    
    if(cookieValue == 7){
        timeToLive = 30;
    } else if(cookieValue == 2){
        timeToLive = 7;
    }
    setCookie('approve-me-close-bar-ttl', timeToLive, 100);
    setCookie('approve-me-close-bar', 1, timeToLive);
    document.getElementById('snipComponent').style.display = 'none';

    var message = {
        status: 'close',
        type: 'approveme-iframe-close-request',
        source: 'sniply-bar',
    };
    window.parent.postMessage(JSON.stringify(message), '*');
}

function getPluginName(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    let pluginName = "Gravity Forms"; // default to gravity forms if we don't know
    if( urlParams.has('page') ){
        pluginName = urlParams.get('page');
    }    
    pluginName = pluginNameLookup(pluginName);
    return pluginName;
}
function pluginNameLookup(pluginName){
    const pluginNameSanitized = pluginName.toLowerCase().replace(" ", "-");

    switch(pluginNameSanitized) {
    case "woocommerce":
    case "woo-commerce":
    case "esign-woocommerce-about":
        pluginName = "WooCommerce";
        break;
    case "wpforms":
    case "wp-forms":
        pluginName = "WPForms";
        break;
    case "ninjaforms":
    case "ninja-forms":
        pluginName = "Ninja Forms";
        break;
    case "wpforms":
    case "esign-wpforms-about":
        pluginName = "WPForms";
        break;
    }
    return pluginName;
}
function updateContent(pluginName){
    const pluginNameSanitized = pluginName.toLowerCase().replace(" ", "-");
    const pluginNameUTM = pluginNameSanitized.replace("-", "");
    const ctaURL = "https://www.approveme.com/"+pluginNameSanitized+"-signature-special?utm_campaign=wprepo&utm_medium=snipbar&utm_source="+pluginNameUTM+"#letschat";      
    document.getElementById("messageText").textContent = "Got a question about a custom "+pluginName+" to WP E-Sign workflow?";
    document.getElementById("buttonAction").setAttribute("href",ctaURL);
    document.getElementById("profileName").setAttribute("href", ctaURL);
}

jQuery(document).ready(function () {

    const pluginName = getPluginName();
    updateContent(pluginName);

    var cookieValue = getCookie('approve-me-close-bar');
    if(!cookieValue) {
        jQuery("#snipComponent").show();
    }
});
