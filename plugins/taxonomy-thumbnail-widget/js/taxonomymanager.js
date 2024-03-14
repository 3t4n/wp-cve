jQuery(document).ready(function ($) {
    function taxonomymanager_media_upload(button_class) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click', button_class, function (e) {
            var button_id = '#' + $(this).attr('id');
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            _custom_media = true;
            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    $('#taxonomy_thumb_id').val(attachment.id);
                    $('#taxonomy-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                    $('#taxonomy-image-wrapper .custom_media_image').attr('src', attachment.sizes.full.url).css('display', 'block');
                } else {
                    return _orig_send_attachment.apply(button_id, [props, attachment]);
                }
            }
            wp.media.editor.open(button);
            return false;
        });
    }

    taxonomymanager_media_upload('.taxman_tax_media_button.button');
    $('body').on('click', '.taxman_tax_media_remove', function () {
        $('#taxonomy_thumb_id').val('');
        $('#taxonomy-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
    });
    $(document).ajaxComplete(function (event, xhr, settings) {
        var queryStringArr = settings.data.split('&');
        if ($.inArray('action=add-tag', queryStringArr) !== -1) {
            var xml = xhr.responseXML;
            $response = $(xml).find('term_id').text();
            if ($response != "") {
                // Clear the thumb image
                $('#taxonomy-image-wrapper').html('');
            }
        }
    });
    /***
     ** Add multiselect for taxonomies selection
     ***/
    jQuery('#thumbnail_taxonomies').change(function () {
        console.log(jQuery(this).val());
    }).multipleSelect({
        width: '230px'
    });
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var currentpage = getUrlParameter('page');

function ttwttwTabs(evt, tabName) {
    var i, ttwTabcontent, ttwTablinks;
    ttwTabcontent = document.getElementsByClassName("ttwTabcontent");
    for (i = 0; i < ttwTabcontent.length; i++) {
        ttwTabcontent[i].style.display = "none";
    }
    ttwTablinks = document.getElementsByClassName("ttwTablinks");
    for (i = 0; i < ttwTablinks.length; i++) {
        ttwTablinks[i].className = ttwTablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

if (currentpage == 'ttw_info') {
    document.getElementById("ttwDefaultOpen").click();
}

