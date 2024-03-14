(function (jQuery) {
    var hostname = "https://thesharkdropship.com";

  

    jQuery(document).ready(function () {
        // It seems like the function 'pollServerToCheckIfThereProductsToInsert' is intended to be called here,
        // but there are no arguments passed to it, and its definition expects an argument 'e'.
        // This might cause an error or unexpected behavior.
        pollServerToCheckIfThereProductsToInsert(); // Missing arguments?

            console.log('Reference ID:', myPluginData.referenceId);

            
    });


    jQuery(document).on("click", "#loadProducts", function (e) {
        pollServerToCheckIfThereProductsToInsert();

    });

    function pollServerToCheckIfThereProductsToInsert() {
        var i = new XMLHttpRequest();
        i.onreadystatechange = function () {
            if (i.readyState === 4 && i.status === 200) {
                // Code to handle the successful response
            } else if (i.readyState === 4 && i.status === 522) {
                // Code to handle status 522
            } else if (i.readyState === 4 && i.status !== 200) {
                // Code to handle other errors
            }
        };

        // The 'baseUrl' variable is used here, but it's not defined in the provided code.
        // This will cause a reference error. 
        // You might want to replace 'baseUrl' with 'hostname' or define 'baseUrl' properly.
        i.open("POST", hostname + ":8008/getEbayVariationsNewApi", true);
        i.setRequestHeader("Content-Type", "application/json");

        // The variables 'a' and 'siteId' are used here, but they are not defined in the provided code.
        // This will cause a reference error. Make sure to define these variables or pass them appropriately.
        i.send(JSON.stringify({ }));
    }

})(jQuery);
