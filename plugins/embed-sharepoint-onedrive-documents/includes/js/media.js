'use strict';

jQuery(document).ready( function($){

    jQuery('.page-title-action').after('<a class="mo_sps_icon_remote_video"><img style="width:15px;height:15px;position:relative;top:3px;margin-right:5px;" src="'+mo_sps.sharepoint_icon+'">SharePoint Library</a>');
    var url = 'https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards';
    var content = '<div style="margin-top:10px;font-weight:500 !important;font-size:.9rem;" class="notice notice-warning is-dismissible mo_sps_sharepoint_media_button">This feature is available in premium version of the Embed SharePoint OneDrive Documents plugin. Please <a target="_blank" href="'+url+'" style="font-weight:800;">Click here</a> to check out the pricing of Premium Plugin.</div>';
    var flag = 0;
    $('.mo_sps_icon_remote_video').click(function() {
   
        if(jQuery(".mo_sps_sharepoint_media_button").hide()){
            jQuery(".media-toolbar").before(content);       
        }
        if(jQuery(".mo_sps_sharepoint_media_button").hide()){
            jQuery(".wp-filter").before(content);     
        }  

   });
  
   
});


