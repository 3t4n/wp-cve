/* 
 * Schema App Client side Javascript
 * 
 * 
 */
function schemaLoad(resourceUri) {
    jQuery.ajax({
        type: 'GET',
        url: resourceUri,
        dataType: 'jsonp',
        success: function (json) {
            // Grab it wrap it, be sure to convert to a string and send it to the head.
            var toPost = "<script type='application/ld+json'>" + JSON.stringify(json) + "<\/script>";
            $('head').append(toPost);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 200 || jqXHR.status == 204) {
                console.log('No structured data for ' + window.location.href);
            } else {
                console.log('Error(' + jqXHR + ', ' + textStatus + ', ' + errorThrown + ')');
            }
        }
    });
}
