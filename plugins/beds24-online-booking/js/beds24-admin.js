jQuery(document).ready(function() {
  jQuery('.my-color-field').wpColorPicker();
  jQuery('.b24_group').hide();
  jQuery('.b24_group:first').fadeIn();
  jQuery('#b24_of-nav li:first').addClass('current');
  jQuery('#b24_of-nav li a').click(function(evt) {
    jQuery('#b24_of-nav li').removeClass('current');
    jQuery(this).parent().addClass('current');
    var clicked_group = jQuery(this).attr('href');
    jQuery('.b24_group').hide();
    jQuery(clicked_group).fadeIn();
    evt.preventDefault();
    window.sessionStorage.setItem('clicked_group', clicked_group);
  });
  jQuery('.b24_embed_code_save').click(function() {
    jQuery('div#loader_img').css("display","block");
    var text_value = jQuery('textarea#b24_content_html').val();
  });
  try {
    var clicked_group = window.sessionStorage.getItem('clicked_group');
    if(clicked_group==null) clicked_group='#pn_bookingpage';    
    jQuery('#b24_of-nav li').removeClass('current');
    jQuery(clicked_group + '_li').addClass('current');
    jQuery('.b24_group').hide();
    jQuery(clicked_group).fadeIn();
  } catch(e) {
  }
});