/**
 * Set properties
 * @returns {Boolean}
 */
function resads_set_properties()
{
    var width = jQuery(window).width();
    var height = jQuery(window).height();
    document.cookie = 'resads_browser_width=' + width;
    document.cookie = 'resads_browser_height=' + height;
    return true;
}
/**
 * Set properties if window resize
 */
jQuery(window).resize(function(){
    resads_set_properties();
});
/**
 * Set properties if document ready
 */
jQuery(document).ready(function(){
    resads_set_properties();
});
/**
 * Register click on ad
 */
jQuery(window).on("load",function(){
    jQuery(".resads-adspot a").on('click', function(event){
        var ad_id = jQuery(this).closest(".resads-adspot").attr('ad');
        if(typeof ad_id !== 'undefined' && !isNaN(ad_id))
        {
            jQuery.ajax({
                url: ajax_object.ajaxurl,
                type: 'post',
                data: {
                    action: 'resads_set_click_on_ad',
                    ad_id: ad_id
                },
                async: false
            });
        }
    });
});
/**
 * Load ads after document load if cache plugin is activate
 */
jQuery(window).on("load",function(){
    if(typeof is_cache_plugin_activate !== 'undefined' && typeof is_cache_plugin_activate.is_active !== 'undefined') 
    {
        var ads = new Array();
        
        jQuery('.resads-adspot').each(function(counter){
            if(typeof jQuery(this).attr('adspot') !== 'undefined')
            {
                jQuery(this).attr('adspot_index', counter);
                ads[counter] = {adspot_id: jQuery(this).attr('adspot'), adspot_index: counter};
            }
        });

        if(ads.length > 0)
        {
            jQuery.ajax({
                url: ajax_object.ajaxurl,
                type: 'post',
                data: {
                    action: 'resads_load_ads',
                    ads: ads,
                    resads_width: resads_get_cookie('resads_browser_width'),
                    resads_height: resads_get_cookie('resads_browser_height')
                },
                success: function(ad) {
                    if(typeof ad['return'] !== 'undefined')
                    {
                        for(key in ad['return'])
                        {
                            var current_ad = ad['return'][key];
                            if(typeof current_ad['adspot_index'] !== 'undefined' && typeof current_ad['code'] !== 'undefined')
                            {
                                jQuery('div[adspot_index="' + current_ad['adspot_index'] + '"]').html(current_ad['code']).attr('ad', current_ad['ad_id']);
                            }
                        }
                    }
                }
            });
        }
    }
});
/**
 * Get Cookie
 * @param {String} cname
 * @returns {String}
 */
function resads_get_cookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) 
    {
        var c = ca[i];
        while (c.charAt(0) == ' ') 
        {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) 
        {
            return c.substring(name.length,c.length);
        }
    }
    return "";
} 