  jQuery(document).ready(function(){
    jQuery('#wppm_popup_background,.wppm_popup_close').click(function(){
      wppm_modal_close();
    });
    jQuery(document).keyup(function(e){
      if (e.keyCode == 27) { 
        wppm_modal_close();
      }
    });
  });

  function wppm_modal_open(title){
    jQuery('#wppm_popup_title h3').text(title);
    jQuery('#wppm_popup_body').html(wppm_admin.loading_html);
    jQuery('.wppm_popup_action').hide();
    jQuery('#wppm_popup_container,#wppm_popup_background').show();
  }
  
  function wppm_modal_close(){
    jQuery('#wppm_popup_container,#wppm_popup_background').hide();
  }
  
  function wppm_modal_close_thread(tinymce_toolbar){
    
    jQuery('#wppm_popup_container,#wppm_popup_background').hide();
    var is_tinymce = (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
    if(is_tinymce){
      tinymce.init({
        selector:'#wppm_reply_box',
        body_id: 'wppm_reply_box',
        menubar: false,
        statusbar: false,
        autoresize_min_height: 150,
        wp_autoresize_on: true,
        plugins: [
            'wpautoresize lists link image directionality'
        ],
        toolbar:  tinymce_toolbar.join() +' | wppm_templates',
        branding: false,
        autoresize_bottom_margin: 20,
        browser_spellcheck : true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true
      });
    }
  }
  