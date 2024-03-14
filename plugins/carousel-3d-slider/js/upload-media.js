/*
Plugin Name: Carousel 3D Slider
Author: tishonator
Author URI: http://tishonator.com/
*/

jQuery(document).ready(function($) {
    $(document).on("click", ".upload_image_button", function() {

        jQuery.data(document.body, 'prevElement', $(this).prev());

        window.send_to_editor = function(html) {
            var imgurl = jQuery(html).attr('src');
            if ( ! imgurl ) {
                imgurl = jQuery(html).find('img').attr('src');
            }
            var inputText = jQuery.data(document.body, 'prevElement');

            if(inputText != undefined && inputText != '')
            {
                inputText.val(imgurl);

                inputText.parent().find('img.slider-img-preview').attr('src', imgurl);
            }

            tb_remove();
        };

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });
});
