jQuery(document).ready(function($) {
   // jQuery("#post-cat option:first-child").attr("selected", true);
   jQuery('.tab-content:nth-child(2)').addClass('firstelement');
   var sCounter = jQuery('#accordion .tab-head').last().find('.fullshortcode').attr('id');
   // console.log(sCounter);
    jQuery('#la-saved').hide();
    jQuery('#la-loader').hide();
    $('.rw-fontfamily').fontselect().change(function(){

        // replace + signs with spaces for css
        var font = $(this).val().replace(/\+/g, ' ');

        // split font into family and weight
        font = font.split(':');

        // set family on paragraphs
        $('.font-text').css('font-family', font[0]);
    });

    $(window).load(function(){
       $('.se-pre-con').fadeOut();
    });

    jQuery( "#accordion" ).accordion({  
      collapsible: true,
    });

    jQuery('.my-colorpicker,.wordscolor,.wordsbgcolor').wpColorPicker();
    jQuery('#compactviewer').on('click','.save-meta',function(event) {
        event.preventDefault();
        jQuery('.se-saved-con').show();        
        var allPost = [];
        jQuery('#accordion > div').each(function(index) {
          var that = jQuery(this);
          var rotwords = {};
          
          rotwords.rw_group_name =  jQuery(this).find('.rw-group-name').val(),
          rotwords.stat_sent =  jQuery(this).find('.static-sen').val(),
          rotwords.end_sent =  jQuery(this).find('.end-sen').val(),
          rotwords.rot_words =  jQuery(this).find('.rotating-words').val(),
          rotwords.animation_effect =  jQuery(this).find('.animate').val(),
          rotwords.animation_speed =  jQuery(this).find('.animate-speed').val(),
          rotwords.shortcode = that.prev('h3').find('.fullshortcode').attr('id');
          rotwords.counter = that.prev('h3').find('.fullshortcode').attr('id'); 

        allPost.push(rotwords);
        });

        var data = {
            action : 'la_save_words_rotator',
            nonce : 'ajax-nonce',
             rotwords : allPost       
        } 

        jQuery.post(laAjax.url, data, function(resp) {
          window.location.reload(true);
          jQuery('.se-saved-con').hide();
          jQuery('.overlay-message').show();
          jQuery('.overlay-message').delay(2000).fadeOut();
        });
    });

      
    jQuery('#accordion .btnadd,.add-new-btm').click(function(event) {
        sCounter++;
        var header = jQuery('#accordion').find('.ui-accordion-header').first().clone(true);
        var parent = jQuery('#accordion').append(header);
        header.find('button.fullshortcode').attr('id', sCounter);
        parent.find('button.bottom-shortcode').attr('id', sCounter);
        var parent_newly = jQuery(this).closest('#accordion').find('.ui-accordion-content').first().clone(true).removeClass('firstelement').appendTo('#accordion').closest('.tab-content');
        jQuery( "#accordion" ).accordion('refresh');
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        console.log(sCounter);

    });

    jQuery('#accordion .btndelete').click(function(event) {
        if (jQuery(this).closest('.ui-accordion-header').next('.ui-accordion-content').hasClass('firstelement')) {
            alert('You can not delete it as it is first element!');
        } else {
           if (confirm("Are you sure you want to delete this item?")) {
              var head = jQuery(this).closest('.ui-accordion-header');
              var body = jQuery(this).closest('.ui-accordion-header').next('.ui-accordion-content');
              head.hide('slow', function(){ head.remove(); });
              body.hide('slow', function(){ body.remove(); });
              jQuery("#accordion").accordion('refresh');
          }
          return false;          
        }
    });

    jQuery('#accordion .del-btm').click(function(event) {
         if (jQuery(this).parent().closest('.ui-accordion-content').hasClass('firstelement')) {
            alert('You can not delete it as it is first element!');
        } else {
           if (confirm("Are you sure you want to delete this item?")) {
              var head = jQuery(this).closest('.ui-accordion-content').prev();
              var body = jQuery(this).parent().closest('.ui-accordion-content');
              head.hide('slow', function(){ head.remove(); });
              body.hide('slow', function(){ body.remove(); });
              jQuery("#accordion").accordion('refresh');
          }
          return false;            
        }
    });

    jQuery('button.fullshortcode').click(function(event) {
        event.preventDefault();
        prompt("Copy (ctrl+C) and use this Shortcode", '[animated-words-rotator id="'+jQuery(this).attr('id')+'"]');
    });

    jQuery('button.bottom-shortcode').click(function(event) {
        event.preventDefault();
        prompt("Copy (ctrl+C) and use this Shortcode", '[animated-words-rotator id="'+jQuery(this).attr('id')+'"]');
    });
});