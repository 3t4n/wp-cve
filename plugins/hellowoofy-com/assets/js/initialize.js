test_menu = {
    id: 'MWS-Context-Menu',
    data: [
        {
            text: 'Open in new tab',
            target: '_blank',
            href: '#',
            action: function(e, selector) { 
                /*e.preventDefault();
                if ($(selector).attr('class') == 'mws_admin_action') {
                    var src       = $(selector).siblings().val();
                    window.location.href = (src);  
                }*/
            }
        },
        {
            divider: true
        },
        {
            icon: 'glyphicon-edit',
            text: 'Copy Story URL',
            action: function(e, selector) { 
                e.preventDefault();
                if ($(selector).attr('class') == 'mws_admin_action') {
                    var btn     = $(selector).siblings().val();
                    const textCopied = ClipboardJS.copy(btn);   
                }
            }
        },
        {
            divider: true
        },
        { 
            text: 'Delete Story',
            icon: 'glyphicon-trash',
            action: function(e, selector) { 
                e.preventDefault();
                if ($(selector).attr('class') == 'mws_admin_action') {
                    var productid       = $(selector).attr('data-id');
                    var token           = $('#mws_context_menu').val();
                    var id = '.max-'+productid;
                    $(id).hide(); 
                    var getSiteAdminURL = mws_admin_ajax_url.ajax_url;
                    var getSiteURL      = getSiteAdminURL.replace('/wp-admin/admin-ajax.php', '');
                    var fd = new FormData();
                    fd.append('product_id', productid);
                    fd.append('mws_context_menu', token);
                    fd.append('action', 'mws_dlt_webstory');
                    $.ajax({
                        type: "post",
                        url: getSiteAdminURL,
                        data: fd,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                          var get_count = $('.mws_story_count').text();
                          var count = parseInt(get_count);
                          var new_count = count - 1;
                          if(new_count == 0 & new_count < 0){
                            var new_count = 0;
                          }else{
                            var new_count = new_count;
                          }
                          $('.mws_story_count').text(new_count); 

                        },
                    });         
            
                }
            }
        }
    ]
};
