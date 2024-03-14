
function showSaveMessage() {
   jQuery(".save-changes" ).addClass('save-changes-on');
   jQuery("#save-changes" ).show("slide", { direction: "left" }, 1000);
}

function updateCheckbox(id) {
   var html=jQuery("#"+id).parent().html();
   var ch=0;
   if (jQuery("#"+id).is(":checked")) {
      ch=1;
   }
   html+='<div onclick="checkCheckbox('+"'"+id+"'"+')" id="checkbox-'+id+'" class="ui-pointer ui-btn-icon-left ';
   if (ch==0) {
      html+="ui-checkbox-off";
   } else {
      html+="ui-checkbox-on";
   }
   html+='">';
   html+=jQuery("label[for='"+id+"']").html();
   html+='</div>';
   html=jQuery("#"+id).parent().html(html);
   jQuery("#"+id).hide();
   jQuery("label[for='"+id+"']").hide();
}

function showFormHelp(id) {
   var pos=jQuery('#'+id).offset();
   var top=pos.top;
   tp-=20;
   jQuery('#form-help').css('top',top+'px');
   jQuery('#form-help').css('left',pos.left+'px');
   jQuery('#form-help').show();
}

function hideFormHelp(id) {
   jQuery('#form-help').hide();
}

function openForms(id) {
   if (jQuery('#'+id+'-forms').hasClass('dashicons-arrow-down')) {
      jQuery('#'+id+'-forms').removeClass('dashicons-arrow-down');
      jQuery('#'+id+'-forms').addClass('dashicons-arrow-up');
      jQuery('#'+id+'-form-list').slideDown('300');
   } else {
      jQuery('#'+id+'-forms').removeClass('dashicons-arrow-up');
      jQuery('#'+id+'-forms').addClass('dashicons-arrow-down');
      jQuery('#'+id+'-form-list').slideUp('300');
   }
}

function testWpRecaptcha() {
   var v=jQuery('#version').val();
   jQuery('#ver-site-key-'+v).hide();
   jQuery('#site_key_'+v+'_v').val('0');
   jQuery('#ver-secret-key-'+v).hide();
   jQuery('#secret_key_'+v+'_v').val('0');
   jQuery('#ver-score-'+v).html('');
   if (jQuery('#captcha-iframe').length) {
       jQuery('#captcha-iframe').remove();
   }
   var err=0;
   var public=jQuery('#site-key-'+v).val().trim();
   if (public=='') {
      jQuery('#site-key-'+v).parent().addClass('border-red');
      jQuery('#site-key-'+v).next().html('Site Key is required.');
      jQuery('#site-key-'+v).next().show();
      err=1;
   }
   var private=jQuery('#secret-key-'+v).val().trim();
   if (private=='') {
      jQuery('#secret-key-'+v).parent().addClass('border-red');
      jQuery('#secret-key-'+v).next().html('Secret Key is required.');
      jQuery('#secret-key-'+v).next().show();
      err=1;
   }

   if (err==1) {
      return;
   }
   jQuery('#auth-'+v+' .test-btn').hide();
   jQuery('#auth-'+v+' .ajax-loader').show();
   if (v==3) {
      var html='<iframe id="captcha-iframe" src="'+plURL+'tools/captcha.html" width="0" height="0" style="display:none"></iframe>';
      jQuery('body').append(html);
   } else {
      if (jQuery('#grecaptcha-script').length) {
         grecaptcha.reset(wdID);
         onloadCallback();
      } else {
        var html='<'+'script id="grecaptcha-script" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></'+'script>';
        jQuery('body').append(html);
      }
   }
}

var verifyCallback = function(response) {
   var v=jQuery('#version').val();
   jQuery('#ver-site-key-'+v).show();
   jQuery('#site-key-'+v+'-v').val('1');
   jQuery('#auth-'+v+' .test-btn').hide();
   jQuery('#auth-'+v+' .check-btn').hide();
   jQuery('#auth-'+v+' .ajax-loader').show();
   verifyKey(response);
};

var errorInvisible = function(response) {
   var v=jQuery('#version').val();
   jQuery('#auth-'+v+' .ajax-loader').hide();
   jQuery('#auth-'+v+' .check-btn').hide();
   jQuery('#auth-'+v+' .test-btn').css('display','inline-block');
};

var onloadCallback = function() {
   var v=jQuery('#version').val();
   cID=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10);
   if (v==2) {
      var html='<div id="'+cID+'"></div>';
      jQuery('#g-recaptcha2').html(html);

      wdID=grecaptcha.render(document.getElementById(cID), {
         'sitekey' : jQuery('#site-key-2').val().trim(),
         'callback' : verifyCallback,
         'theme' : jQuery('#theme-v2').val(),
         'size' : jQuery('#size-v2').val()
      });
      jQuery('#auth-'+v+' .ajax-loader').hide();
      jQuery('#auth-'+v+' .test-btn').css('display','inline-block');
   } else {
      var html='<button id="'+cID+'" style="display:none"></button>';
      jQuery('#g-recaptchai').html(html);
      wdID=grecaptcha.render(document.getElementById(cID), {
         'sitekey' : jQuery('#site-key-i').val().trim(),
         'callback' : verifyCallback,
         'error-callback' : errorInvisible,
         'size' : 'invisible'
      });
      jQuery('#auth-'+v+' .ajax-loader').hide();
      jQuery('#auth-'+v+' .check-btn').css('display','inline-block');
   }
}

function checkWpRecaptcha() {
   grecaptcha.execute(wdID);
}

function verifyWpRecaptcha() {
   var v=jQuery('#version').val();
   jQuery('#auth-'+v+' .verify-btn').hide();
   jQuery('#auth-'+v+' .ajax-loader').show();
   verifyKey(grecaptcha.getResponse(wdID));
}

function loadCaptchaFrame() {
   var v=jQuery('#version').val();
   var captcha=jQuery('#captcha-iframe').contents().find('.grecaptcha-badge');
   if (captcha.length) {
      var frame=document.getElementById("captcha-iframe");
      if (frame.contentWindow.grecaptcha) {
          grecaptcha=frame.contentWindow.grecaptcha;
          grecaptcha.execute(jQuery('#site-key-3').val().trim(), {action: 'home'})
          .then(function(token) {
             jQuery('#ver-site-key-3').show();
             jQuery('#site-key-3-v').val('1');
             verifyKey(token);
          },function(error) {
             jQuery('#site-key-3').parent().addClass('border-red');
             jQuery('#site-key-3').next().html('Invalid domain for site key.');
             jQuery('#site-key-3').next().show();
             jQuery('#auth-'+v+' .ajax-loader').hide();
             jQuery('#auth-'+v+' .test-btn').css('display','inline-block');
          });
      }
   } else {
      jQuery('#site-key-3').parent().addClass('border-red');
      jQuery('#site-key-3').next().html('Invalid Site Key.');
      jQuery('#site-key-3').next().show();
      jQuery('#auth-'+v+' .ajax-loader').hide();
      jQuery('#auth-'+v+' .test-btn').css('display','inline-block');
   }
}

function verifyKey(token) {
   var v=jQuery('#version').val();
   var err=0;
   jQuery.post(siteURL+'/wp-admin/admin-ajax.php', {
      action: "wp-recaptcha-test-keys",
      version:v,
      token:token,
      gc_key:jQuery('#secret-key-'+v).val().trim()
      },
      function(data){
         //alert(data);
         jQuery('#auth-'+v+' .ajax-loader').hide();
         arr=JSON.parse(data);
         if (arr.error == '') {
            if (arr.success) {
               jQuery('#ver-secret-key-'+v).show();
               jQuery('#secret-key-'+v+'-v').val('1');
               if (jQuery('#ver-score-'+v).length) {
                  jQuery('#ver-score-'+v).html(arr.score);
               }
               jQuery('#auth-'+v+' .test-btn').hide();
               jQuery('#auth-'+v+' .verify-btn').hide();
               setTimeout(function(){
                 jQuery('.ptmbg-settings-div form').css('visibility','hidden');
                 jQuery('#page-loader').show();
                 jQuery('#submit').click();
               }, 2000);
            } else {
               jQuery('#secret-key-'+v).parent().addClass('border-red');
               jQuery('#secret-key-'+v).next().html(arr.error.replace(/\-/g,' '));
               jQuery('#secret-key-'+v).next().show();
               err=1;
            }
         } else {
            jQuery('#secret-key-'+v).parent().addClass('border-red');
            jQuery('#secret-key-'+v).next().html(arr.error.replace(/\-/g,' '));
            jQuery('#secret-key-'+v).next().show();
            err=1;
         }
         if (err==1) {
            if (v!=3) {
               jQuery('#auth-'+v+' .test-btn').hide();
               jQuery('#auth-'+v+' .verify-btn').css('display','inline-block');
            } else {
               jQuery('#auth-'+v+' .test-btn').css('display','inline-block');
            }
         }
      }
   );
}


// Short code
function copyShortCode() {
   var copyText = document.getElementById("short-code");
   copyText.select();
   document.execCommand("copy");
}
function resetShortCode() {
  if (jQuery('#sc-form-id').length>0) {
    jQuery('#s-bottomright .radio-icon').addClass('radio-on');
    jQuery('#ps1 .radio-icon').addClass('radio-on');
    jQuery('#ts1 .radio-icon').addClass('radio-on');
    jQuery('#gs1 .radio-icon').addClass('radio-on');
    jQuery('#vs .radio-btn').on('click',function() {
          var id=jQuery(this).attr('id');
          jQuery('#vs .radio-icon').each(function() {
             if (jQuery(this).hasClass('radio-on')) {
                jQuery(this).removeClass('radio-on');
                jQuery(this).next().removeClass('radio-title-on');
             }
          });
	      jQuery('#'+id+' .radio-icon').addClass('radio-on');
	      jQuery('#'+id+' .radio-title').addClass('radio-title-on');
	      jQuery('#settings-shortcode .v-opt').hide();
	      jQuery('.'+id).css('display','table-row');
	      generateShortCode();
    });
    jQuery('#ps .radio-btn').on('click',function() {
          var id=jQuery(this).attr('id');
          jQuery('#ps .radio-icon').each(function() {
             if (jQuery(this).hasClass('radio-on')) {
                jQuery(this).removeClass('radio-on');
                jQuery(this).next().removeClass('radio-title-on');
             }
          });
	      jQuery('#'+id+' .radio-icon').addClass('radio-on');
	      jQuery('#'+id+' .radio-title').addClass('radio-title-on');
	      generateShortCode();
    });
    jQuery('#ts .radio-btn').on('click',function() {
          var id=jQuery(this).attr('id');
          jQuery('#ts .radio-icon').each(function() {
             if (jQuery(this).hasClass('radio-on')) {
                jQuery(this).removeClass('radio-on');
                jQuery(this).next().removeClass('radio-title-on');
             }
          });
	      jQuery('#'+id+' .radio-icon').addClass('radio-on');
	      jQuery('#'+id+' .radio-title').addClass('radio-title-on');
	      generateShortCode();
    });
    jQuery('#gs .radio-btn').on('click',function() {
          var id=jQuery(this).attr('id');
          jQuery('#gs .radio-icon').each(function() {
             if (jQuery(this).hasClass('radio-on')) {
                jQuery(this).removeClass('radio-on');
                jQuery(this).next().removeClass('radio-title-on');
             }
          });
	      jQuery('#'+id+' .radio-icon').addClass('radio-on');
	      jQuery('#'+id+' .radio-title').addClass('radio-title-on');
	      generateShortCode();
    });
    if (jQuery('#vs'+cVer).length) {
       jQuery('#vs'+cVer+' .radio-icon').addClass('radio-on');
       jQuery('.vs'+cVer).css('display','table-row');
    }
    generateShortCode();
  }
}

function checkboxShort(id) {
   if (jQuery('#checkboxshort-'+id).hasClass('ui-checkbox-off')) {
      jQuery('#checkboxshort-'+id).removeClass('ui-checkbox-off');
      jQuery('#checkboxshort-'+id).addClass('ui-checkbox-on');
      if (id=='auto-language') {
         jQuery('#short-language-hide').show();
      }
   } else {
      jQuery('#checkboxshort-'+id).removeClass('ui-checkbox-on');
      jQuery('#checkboxshort-'+id).addClass('ui-checkbox-off');
      if (id=='auto-language') {
         jQuery('#short-language-hide').hide();
      }
   }

   generateShortCode();
}

function generateShortCode() {
 if (jQuery('#sc-form-id').length>0) {
   var v='i';
   if (jQuery('#vs3 .radio-icon').hasClass('radio-on')) {
       v=3;
   } else if (jQuery('#vs2 .radio-icon').hasClass('radio-on')) {
       v=2;
   }

   var html='[wp-recaptcha v='+v;
   if (v==2) {
      var url='captcha_light.png';
      if (jQuery('#ts2 .radio-icon').hasClass('radio-on')) {
           html+=' t=dark';
           url='captcha_dark.png';
      }
      if (jQuery('#gs2 .radio-icon').hasClass('radio-on')) {
           html+=' s=compact';
           if (jQuery('#ts2 .radio-icon').hasClass('radio-on')) {
              url='captcha_dark_small.png';
           } else {
              url='captcha_light_small.png';
           }
      }
      jQuery('#s-no-visible').hide();
      jQuery('#s-g-badge').hide();
      jQuery('#s-g-theme').attr('src',plURL+'/images/'+url);
      jQuery('#s-g-theme').show();
   } else {
     jQuery('#s-g-theme').hide();
     if ((jQuery('#checkboxshort-hide-d').hasClass('ui-checkbox-on')) || (jQuery('#checkboxshort-hide-m').hasClass('ui-checkbox-on'))) {
        jQuery('#s-no-visible').show();
        jQuery('#s-g-badge').hide();
        if (jQuery('#checkboxshort-hide-d').hasClass('ui-checkbox-on')) {
           html+=' d=true';
        }
        if (jQuery('#checkboxshort-hide-m').hasClass('ui-checkbox-on')) {
           html+=' m=true';
        }
     } else {
        jQuery('#s-no-visible').hide();
        jQuery('#s-g-badge').show();

     if (v=='i') {
        jQuery('#s-g-badge').removeClass();
        if (jQuery('#ps2 .radio-icon').hasClass('radio-on')) {
           html+=' p=bottomleft';
           jQuery('#s-g-badge').addClass('s-g-badge-left');
        } else if (jQuery('#ps3 .radio-icon').hasClass('radio-on')) {
           html+=' p=inline';
           jQuery('#s-g-badge').addClass('s-g-badge-inline');
        } else {
           jQuery('#s-g-badge').addClass('s-g-badge-right');
        }
     } else {
        jQuery('#s-g-badge').removeClass();
        jQuery('#s-g-badge').addClass('s-g-badge-right');
     }
     }
   }
   if (jQuery('#checkboxshort-auto-language').hasClass('ui-checkbox-off')) {
      html+=' l='+jQuery("#sc-lang option:selected").val();
   }
   if (jQuery('#sc-form-id').val().trim() != '') {
      html+=' id=' + jQuery('#sc-form-id').val().trim();
   }
   html+=']';
   jQuery('#short-code').val(html);
 }
}

