jQuery(document).ready(function(){
    bm_load_banners();
});

function bm_load_banners() {
    jQuery(".wp_bm_banner_set").each( function(index, value){
        var id = jQuery(this).attr('id');
        var src = jQuery(this).children('input[name="src"]').val();
        var link = jQuery(this).children('input[name="link"]').val();
        var bolBlank = jQuery(this).children('input[name="blank"]').val();
        var strType = jQuery(this).children('input[name="type"]').val();
        var intWidth = jQuery(this).children('input[name="width"]').val();
        var intHeight = jQuery(this).children('input[name="height"]').val();
        var id = jQuery(this).removeClass('wp_bm_banner_set');

        var blank = '';
        if(bolBlank=='true')
        {
            var blank = ' target="_blank"';
        }

        if(strType=='swf')
        {
            // posizioa kalkulatu
            var parent_w = jQuery(this).parent().width();
            if (parent_w > intWidth) {
                var left_p = (parent_w - intWidth) / 2;
            } else {
                var left_p = 0;
            }

            // get video html
            var video_id = "bm_video_"+ id;
            var link_id = "bm_link_"+ id;
            var layer_id = "bm_layer_"+ id;
            var container_id = "bm_container_"+ id;

            var html = '<div id="'+ layer_id +'"><a id="'+ link_id +'" href="'+ link +'"'+ blank +'></a></div>';
            html += '<div id="'+ container_id +'"><div id="'+ video_id +'"></div></div>';

            // add video to dom
            jQuery(this).append(html);

            // add css
            var bm_link = jQuery("#"+ link_id);
            bm_link.css("display","block");
            bm_link.css("width","100%");
            bm_link.css("height","100%");
            bm_link.css("position","absolute");
            bm_link.css("left",left_p+"px");

            var bm_layer = jQuery("#"+ layer_id);
            if(jQuery.browser.msie)
            {
                bm_layer.css("background-color","red");
            }
            bm_layer.css("opacity","0");
            bm_layer.css("width",intWidth+"px");
            bm_layer.css("height",intHeight+"px");
            bm_layer.css("position","absolute");
            bm_layer.css("z-index","1000");

            var bm_container = jQuery("#"+ container_id);
            bm_container.css("width",intWidth+"px");
            bm_container.css("height",intHeight+"px");
            bm_container.css("position","relative");
            bm_container.css("left",left_p+"px");

            // add video width object
            swfobject.embedSWF(src, video_id, intWidth, intHeight, "9.0.0","expressInstall.swf", false, { 'wmode': 'opaque'});
        }
        else
        {
            var html = '<a href="'+ link +'"'+ blank +'><img src="'+ src +'" /></a>';
            jQuery(this).append(html);
            jQuery(this).find("img").css("width", "100%");
            jQuery(this).find("img").css("max-width", intWidth + "px");
        }
    });
}