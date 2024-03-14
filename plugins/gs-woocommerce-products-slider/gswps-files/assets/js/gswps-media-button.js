jQuery(function ($) {
    $(document).ready(function () {
        $('#gswps-visual-media-btn').click(gswps_open_media_window);
        function gswps_open_media_window() {
        jQuery('#gswps_visual_modal').modal('show');
        jQuery('#gswps_add_code').click(function (e) {
            e.stopImmediatePropagation();
            jQuery('#gswps_visual_modal').modal('hide');
            var posts = jQuery('#gswps_posts').val();
            var order = jQuery('#gswps_order').val();
            var theme = jQuery('#gswps_theme').val();
            var columns = jQuery('#gswps_cols').val();
            var autoplay = jQuery('#gswps_autop').val();
            var pause = jQuery('#gswps_pause').val();
            var inf_loop = jQuery('#gswps_inf_loop').val();
            var speed = jQuery('#gswps_speed').val();
            var timeout = jQuery('#gswps_timeout').val();
            var nav_speed = jQuery('#gswps_nav_speed').val();
            var nav = jQuery('#gswps_nav').val();
            var dots_nav = jQuery('#gswps_dots_nav').val();
            var prod_tit_limit = jQuery('#gswps_prod_tit_limit').val();

            gswps_add_screen(posts, order, theme, columns, autoplay, pause, inf_loop, speed, timeout, nav_speed, nav, dots_nav, prod_tit_limit);
        });

        function gswps_add_screen(posts, order, theme, columns, autoplay, pause, inf_loop, speed, timeout, nav_speed, nav, dots_nav, prod_tit_limit) {
            if (posts != '' && order != '' && theme != '' && columns != '' && autoplay != '' && pause != '' && inf_loop != '' && speed != '' && timeout != '' && nav_speed != '' && nav != '' && dots_nav != '' && prod_tit_limit != '') {
                wp.media.editor.insert('[gs_wps posts="' + posts + '" order="' + order + '" theme="' + theme + '" columns="' + columns + '" autoplay="' + autoplay + '" pause="' + pause + '" inf_loop="' + inf_loop + '" speed="' + speed + '" timeout="' + timeout + '" nav_speed="' + nav_speed + '" nav="' + nav + '" dots_nav="' + dots_nav + '" prod_tit_limit="'+ prod_tit_limit +'"]'); 
                return;
            }
            else
            {
                wp.media.editor.insert('[gs_wps]');
            }
        }        
    }
    });
    
});