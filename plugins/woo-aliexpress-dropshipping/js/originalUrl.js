jQuery(document).on("click", "#openOriginalProductUrl", function(event) {
    event.preventDefault();
    var url = window.location.href;
    var indexStartPostID = url.indexOf('?post=');
    var indexEndPostId = url.indexOf('&');
    var postId = url.substring(indexStartPostID + 6, indexEndPostId);

    let searchSkuValue = postId;

    if (searchSkuValue) {
        jQuery.ajax({
            url: wooshark_params_alibary.ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "get-product-by-id-alibay",
                searchSkuValue: searchSkuValue
            },
            success: function(data) {
                if (data && data.length == 1) {
                    window.open(data[0].productUrl, '_blank');
                }
            },
            error: function(err) {
              
            },
            complete: function() {
                console.log("SSMEerr");
            }
        });
    }


});