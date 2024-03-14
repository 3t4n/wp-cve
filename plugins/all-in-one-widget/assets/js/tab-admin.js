/*
Plugin Name: WP Tab Widget
Author: MyThemeShop
Author URI: mythemeshop.com
Version: 1.0
*/

jQuery(document).on('click', function(e) {
    var $this = jQuery(e.target);
    var $form = $this.closest('.wpt_options_form');
    
    if ($this.is('.wpt_enable_comments')) {
        var $related = $form.find('.wpt_comment_options');
        var val = $this.is(':checked');
        if (val) {
            $related.slideDown();
        } else {
            $related.slideUp();
        }
    } else if ($this.is('.wpt_show_thumbnails')) {
        var $related = $form.find('.wpt_thumbnail_size');
        var val = $this.is(':checked');
        if (val) {
            $related.slideDown();
        } else {
            $related.slideUp();
        }
    } else if ($this.is('.wpt_show_excerpt')) {
        var $related = $form.find('.wpt_excerpt_length');
        var val = $this.is(':checked');
        if (val) {
            $related.slideDown();
        } else {
            $related.slideUp();
        }
    } 
});