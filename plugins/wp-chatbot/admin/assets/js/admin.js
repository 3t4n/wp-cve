
jQuery(document).ready(function($){

  // for support wp-color-picker
  // $('.htcc-color-wp').wpColorPicker();

  // $('select').material_select();
  // $('select').formSelect();   // v1.0.0.rc.2

  // $('.collapsible').collapsible();


/**
* Customer Chat - Messenger - position
*/
var cc_i_position = document.querySelectorAll('.cc_i_position');
var cc_g_position = document.querySelectorAll('.cc_g_position');

var cc_i_position_mobile = document.querySelectorAll('.cc_i_position-mobile');
var cc_g_position_mobile = document.querySelectorAll('.cc_g_position-mobile');
$("#fb_greeting_dialog_delay").on("keypress", function( event ) {
  var key = event.charCode ? event.charCode : event.keyCode;
  if (key > 31 && (key < 48 || key > 57))
  {
    event.preventDefault();
    return false;
  }
});

//  incase display-block is added remove it ..
var cc_i_remove = function cc_i_remove() {
cc_i_position.forEach(function (e) {
  e.classList.remove('display-block');
});
};

var cc_g_remove = function cc_g_remove() {
cc_g_position.forEach(function (e) {
  e.classList.remove('display-block');
});
};

var cc_i_remove_mobile = function cc_i_remove_mobile() {
cc_i_position_mobile.forEach(function (e) {
  e.classList.remove('display-block');
});
};

var cc_g_remove_mobile = function cc_g_remove_mobile() {
cc_g_position_mobile.forEach(function (e) {
  e.classList.remove('display-block');
});
};


function cc_default_display() {

// icon position
var val = $('.cc_i_select').find(":selected").val();

var cc_i_position2 = document.querySelector('.cc_i_position-2');
var cc_i_position1 = document.querySelector('.cc_i_position-1');
var cc_i_position3 = document.querySelector('.cc_i_position-3');
var cc_i_position4 = document.querySelector('.cc_i_position-4');

if (val == '1') {
  cc_i_position1.classList.add('display-block');
} else if (val == '2') {
  cc_i_position2.classList.add('display-block');
} else if (val == '3') {
  cc_i_position3.classList.add('display-block');
} else if (val == '4') {
  cc_i_position4.classList.add('display-block');
}


// onchange - icon - postion
$(".cc_i_select").on("change", function (e) {
  var x = e.target;
  var val = e.target.value;

  if (val == '1') {
    cc_i_remove();
    cc_i_position1.classList.add('display-block');
  } else if (val == '2') {
    cc_i_remove();
    cc_i_position2.classList.add('display-block');
  } else if (val == '3') {
    cc_i_remove();
    cc_i_position3.classList.add('display-block');
  } else if (val == '4') {
    cc_i_remove();
    cc_i_position4.classList.add('display-block');
  }
});


// Greetings dialog position
var val = $('.cc_g_select').find(":selected").val();

var cc_g_position2 = document.querySelector('.cc_g_position-2');
var cc_g_position1 = document.querySelector('.cc_g_position-1');
var cc_g_position3 = document.querySelector('.cc_g_position-3');
var cc_g_position4 = document.querySelector('.cc_g_position-4');

if (val == '1') {
  cc_g_position1.classList.add('display-block');
} else if (val == '2') {
  cc_g_position2.classList.add('display-block');
} else if (val == '3') {
  cc_g_position3.classList.add('display-block');
} else if (val == '4') {
  cc_g_position4.classList.add('display-block');
}

// onchange - Greetings - postion
$(".cc_g_select").on("change", function (e) {
  var x = e.target;
  var val = e.target.value;

  if (val == '1') {
    cc_g_remove();
    cc_g_position1.classList.add('display-block');
  } else if (val == '2') {
    cc_g_remove();
    cc_g_position2.classList.add('display-block');
  } else if (val == '3') {
    cc_g_remove();
    cc_g_position3.classList.add('display-block');
  } else if (val == '4') {
    cc_g_remove();
    cc_g_position4.classList.add('display-block');
  }
});


// icon position - mobile
var val = $('.cc_i_select-mobile').find(":selected").val();

var cc_i_position2_mobile = document.querySelector('.cc_i_position-2-mobile');
var cc_i_position1_mobile = document.querySelector('.cc_i_position-1-mobile');
var cc_i_position3_mobile = document.querySelector('.cc_i_position-3-mobile');
var cc_i_position4_mobile = document.querySelector('.cc_i_position-4-mobile');

if (val == '1') {
  cc_i_position1_mobile.classList.add('display-block');
} else if (val == '2') {
  cc_i_position2_mobile.classList.add('display-block');
} else if (val == '3') {
  cc_i_position3_mobile.classList.add('display-block');
} else if (val == '4') {
  cc_i_position4_mobile.classList.add('display-block');
}

// onchange - icon - postion - mobile
$(".cc_i_select-mobile").on("change", function (e) {
  var x = e.target;
  var val = e.target.value;

  if (val == '1') {
    cc_i_remove_mobile();
    cc_i_position1_mobile.classList.add('display-block');
  } else if (val == '2') {
    cc_i_remove_mobile();
    cc_i_position2_mobile.classList.add('display-block');
  } else if (val == '3') {
    cc_i_remove_mobile();
    cc_i_position3_mobile.classList.add('display-block');
  } else if (val == '4') {
    cc_i_remove_mobile();
    cc_i_position4_mobile.classList.add('display-block');
  }
});


// Greetings dialog position - mobile
var val = $('.cc_g_select-mobile').find(":selected").val();

var cc_g_position2_mobile = document.querySelector('.cc_g_position-2-mobile');
var cc_g_position1_mobile = document.querySelector('.cc_g_position-1-mobile');
var cc_g_position3_mobile = document.querySelector('.cc_g_position-3-mobile');
var cc_g_position4_mobile = document.querySelector('.cc_g_position-4-mobile');

if (val == '1') {
  cc_g_position1_mobile.classList.add('display-block');
} else if (val == '2') {
  cc_g_position2_mobile.classList.add('display-block');
} else if (val == '3') {
  cc_g_position3_mobile.classList.add('display-block');
} else if (val == '4') {
  cc_g_position4_mobile.classList.add('display-block');
}

// onchange - Greetings - postion - mobile
$(".cc_g_select-mobile").on("change", function (e) {
  var x = e.target;
  var val = e.target.value;

  if (val == '1') {
    cc_g_remove_mobile();
    cc_g_position1_mobile.classList.add('display-block');
  } else if (val == '2') {
    cc_g_remove_mobile();
    cc_g_position2_mobile.classList.add('display-block');
  } else if (val == '3') {
    cc_g_remove_mobile();
    cc_g_position3_mobile.classList.add('display-block');
  } else if (val == '4') {
    cc_g_remove_mobile();
    cc_g_position4_mobile.classList.add('display-block');
  }
});




};

cc_default_display();












/**
* Custom Image positions
*/
var ci_position = document.querySelectorAll('.ci_position');
var ci_position_mobile = document.querySelectorAll('.ci_position-mobile');

//  incase display-block is added remove it ..
var remove = function remove() {
ci_position.forEach(function (e) {
  e.classList.remove('display-block');
});
};

//  incase display-block is added remove it ..
var remove_mobile = function remove() {
ci_position_mobile.forEach(function (e) {
  e.classList.remove('display-block');
});
};


function ci_default_display() {

var val = $('.select').find(":selected").val();

var position1 = document.querySelector('.ci_position-1');
var position2 = document.querySelector('.ci_position-2');
var position3 = document.querySelector('.ci_position-3');
var position4 = document.querySelector('.ci_position-4');

if (val == '1') {
  position1.classList.add('display-block');
} else if (val == '2') {
  position2.classList.add('display-block');
} else if (val == '3') {
  position3.classList.add('display-block');
} else if (val == '4') {
  position4.classList.add('display-block');
}


// onchange - postion
$(".select").on("change", function (e) {
  var x = e.target;
  var val = e.target.value;

  if (val == '1') {
    remove();
    position1.classList.add('display-block');
  } else if (val == '2') {
    remove();
    position2.classList.add('display-block');
  } else if (val == '3') {
    remove();
    position3.classList.add('display-block');
  } else if (val == '4') {
    remove();
    position4.classList.add('display-block');
  }
});

};

ci_default_display();



function ci_default_display_mobile() {

var val = $('.select-mobile').find(":selected").val();

var position1 = document.querySelector('.ci_position-1-mobile');
var position2 = document.querySelector('.ci_position-2-mobile');
var position3 = document.querySelector('.ci_position-3-mobile');
var position4 = document.querySelector('.ci_position-4-mobile');

if (val == '1') {
  position1.classList.add('display-block');
} else if (val == '2') {
  position2.classList.add('display-block');
} else if (val == '3') {
  position3.classList.add('display-block');
} else if (val == '4') {
  position4.classList.add('display-block');
}

// onchange - mobile position
$(".select-mobile").on("change", function (e) {
var x = e.target;
var val = e.target.value;

if (val == '1') {
  remove_mobile();
  position1.classList.add('display-block');
} else if (val == '2') {
  remove_mobile();
  position2.classList.add('display-block');
} else if (val == '3') {
  remove_mobile();
  position3.classList.add('display-block');
} else if (val == '4') {
  remove_mobile();
  position4.classList.add('display-block');
}
});


};

ci_default_display_mobile();

});



/**
* makes an ajax call
* by default service_content will hide using style="display: none;"
* if ht_cc_service_content option is not set or not equal to hide
* then show the card  - set display: block
* ajax action at admin.php
*/
jQuery.post(
  ajaxurl,
  {
      'action': 'ht_cc_service_content',
    _ajax_nonce: ajax_obj.nonce,

  },
  function(response){
      if ( 'hide' !== response ) {
          var service_content = document.querySelector(".service-content");
          if ( service_content ) {
              service_content.style.display = "block";
          }
      }
  }
);


/**
* when clicked on hide at admin - service content
* makes an ajax call an update / create the ht_cc_service_content option to hide
* ajax action at admin.php
*/
function ht_cc_admin_hide_services_content() {

jQuery.post(
  ajaxurl,
  {
      'action': 'ht_cc_service_content_hide',
      _ajax_nonce: ajax_obj.nonce,
  },
);

var service_content = document.querySelector(".service-content");

if  ( service_content ) {
  service_content.style.display = "none";
}

}


// wpColorPicker
// jQuery(document).ready(function($){
//   $('.htcc-color-wp').wpColorPicker();
// });

jQuery(document).ready(function($){
if ( $(".htcc-color-wp") ) {
  if ( $(".htcc-color-wp").spectrum ) {
    $(".htcc-color-wp").spectrum({
    preferredFormat: "hex",
    showInput: true,
    allowEmpty:true,
    chooseText:'Select',
    // showPalette: true,
    // showSelectionPalette: true,
    // palette: [ 'red', 'green', 'blue' ],
    // localStorageKey: "spectrum.homepage",
    });
  }
}
$('.htcc-color-wp').on("dragstop.spectrum", function(e, color) {
  $("#htcc-color-wp").val(color.toHexString());
});
$('.button-lazy-load').click(function () {
  $(this).addClass('opacity-button');
  $(this).find('.lazyload').css('display', 'block');
});

$('.button-copy').click(function () {
  $(this).siblings('.copiedtext').css('display', 'inline-block');
  var attr = $(this).data('elem');
  var temp = jQuery("<input>");
  jQuery("body").append(temp);
  temp.val(jQuery(attr + " .to-copy").val().trim()).select();
  document.execCommand("copy");
});

function addUserRefs() {
  var user_ref, elements = document.querySelectorAll(".fb-send-to-messenger[page_id='1754274684887439']");

  if (elements) {
    for (var i = 0; i < elements.length; i++) {
      user_ref = 'mobile-monkey_' + Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2);
      elements[i].setAttribute('user_ref', user_ref);
      elements[i].setAttribute('origin', window.location.href);
    }
  }
}

function getFacebookSDK() {
  if (window.FB) {

    return Promise.resolve(window.FB);
  }

  var pollNumber = 0;

  return new Promise(function (resolve, reject) {
    var intervalId = setInterval(function () {
      if (window.FB) {
        clearInterval(intervalId);
        resolve(window.FB);
      } else if (pollNumber > 300) {
        reject('Cannot reach Facebook SDK');
      } else {
        pollNumber = pollNumber + 1;
      }
    }, 350);
  });
}

getFacebookSDK().then(function (FB) {
  var link = document.getElementById('get-mm-free-button__link');
  addUserRefs();

  // Listen to FB events
  FB.Event.subscribe('send_to_messenger', function (e) {

    if (e.event === 'rendered') {

      var iframe = document.getElementById('get-mm-free-button__iframe-container');

      if (iframe && !iframe.classList.contains('fb-send-to-messenger--loaded-iframe')) {
        iframe.classList.add('fb-send-to-messenger--loaded-iframe');
      }
    }


    // The logic below may be different for you if you don't need to open a link like I
    // did for send to messenger plugin

    // Always click the link or button if opt_in
    if (e.event === 'opt_in') {

      if (link) {
        link.click();
      }
    }

    if (e.event === 'clicked') {
      setTimeout(function () {
        setInterval(function () {
          var focused = document.hasFocus();

          // We open up a new modal and Facebook and need to make sure action still takes place if user does not
          // click opt_in
          if (focused && link) {
            setTimeout(function () {
              link.click();
            }, 450);
          }
        }, 200);
      }, 200);
    }
  });
});

$('.step-wrapper ul li.tab-link').each(function () {
  if ($(this).hasClass('current')){
    var cattr = $(this).attr('data-tab');
    $("#toplevel_page_wp-chatbot ul li").removeClass('current');
    $("#toplevel_page_wp-chatbot ul li span[data-tab='" + cattr +"']").parents('li').addClass('current');
  }
});
if (!$('#htcc_fb_as_state').prop('checked')){
  $('.as').css({'pointer-events':'none','opacity':'0.6'});
  $('.questions-wrapper, .question-button__add').css({'pointer-events':'none','opacity':'0.6'});
  if (!$('.connected-page').hasClass('pro')) {
  }


}
$('#htcc_fb_as_state').on('change', function (event) {
  if(!$(this).prop('checked')){
    $('.as').css({'pointer-events':'none','opacity':'0.6'});
    $('.questions-wrapper, .question-button__add').css({'pointer-events':'none','opacity':'0.6'});
  }else{
    $('.questions-wrapper, .question-button__add').css({'pointer-events':'all','opacity':'1'});
    $('.as').css({'pointer-events':'all','opacity':'1'});
  }
});

if ($('.bot_disabled').length>0){
  $('#tab-2,#tab-3,#tab-1 form').css({'pointer-events':'none','opacity':'0.6'});
}
$('h3.acc-title').on('click', function (event) {
  if (!$(this).hasClass('open')) {
    $(this).addClass('open');
    $(this).next().slideDown();
    $(this).next().css('display','block');
  } else {
    $(this).removeClass('open');
    $(this).next().slideUp();
  }
});

$('.connect-page a').on("click", function (event) {
  event.preventDefault();
  window.location.href = $(this).attr('href');
  $('.connect-page a').each(function () {
    $(this).removeAttr('href');
    $(this).off('click');
  });
});
$("#button_disconnect_page").on("click", function( ) {
  $("#to_pro").show();
  $('#modal-overlay').show();
  $('body').css({'overflow': 'hidden'});

});
$('#disconnect').on('click', function () {
  $('.modal_close').off('click');
  $('.button-close-modal').off('click');
})

$(".modal .button-close-modal,.modal .modal_close").on("click", function(event) {
  event.preventDefault();
  $('#errors').hide();
  $(".modal").hide();
  $('body').css({'overflow': 'auto'});
  $('.button-lazy-load').removeClass('opacity-button');
  $('.lazyload').hide();
  $('#modal-overlay').hide();
  attr = $('.step-wrapper ul li.tab-link.current').attr("data-tab");
  set_current_tab(attr);
});
var datas = new Object();
var tab ='';
var attr ='';
var error = false;
var save_from_form=false;
$('.tab-content:not(:last)').each(function () {
  var id = $(this).attr('id');
  datas[id] = new Object();
    $(this).find('input,textarea, select').each(function () {
      var name = $(this).attr('name');
      if ($(this).attr('id') !== "qualified" ){
          if ($(this).attr('type') == 'checkbox'){
            datas[id][$(this).attr('id')] = $(this).prop('checked')
          }else if ($(this).attr('id') == "htcc-color-wp" ) {
            datas[id][$(this).attr('id')]=$(this).val().toUpperCase();
          } else {
            if (name){
              if (name.indexOf("htcc") >= 0 && name.indexOf("htcc_options") < 0){
               datas[id][$(this).attr('id')] = $(this).val();
              }
            }
          }
      }
     });
});
$('#toplevel_page_wp-chatbot ul li:not(:first-child)').each( function () {
  $(this).on('click',function (event) {
    event.preventDefault();
    var current_li = $(".step-wrapper ul li.tab-link.current").attr('data-tab');
    attr = $(this).find('span').attr('data-tab');
    if (attr!=current_li){
      set_current_tab(attr);
    }
    tab = $("#"+attr)
    var li_next = $(".step-wrapper ul li.tab-link[data-tab='" + attr +"']");
    $('#toplevel_page_wp-chatbot ul li:not(:first-child)').removeClass('current');
    $(this).addClass('current');
    unsaved(current_li,li_next,attr);
  });
});
var flag = new Object();
var second_flag = new Object();


$(".step-wrapper ul li.tab-link").on("click", function() {
  attr = $(this).attr("data-tab");
  $('#toplevel_page_wp-chatbot ul li:not(:first-child)').removeClass('current');
  $("#toplevel_page_wp-chatbot ul li span[data-tab='" + attr +"']").parents('li').addClass('current');
  if (!$(this).hasClass('current')){
    set_current_tab(attr);
  }
  if(!$(this).is(':last-child')){
    tab = $(this);
    var current = $('.step-wrapper ul li.tab-link.current').attr("data-tab");
    unsaved(current,tab,attr);
  }else {
    $(".step-wrapper ul li.tab-link").removeClass("current");
    $(this).addClass('current');
    $(".tab-content").removeClass("current");
    $('#'+$(this).attr("data-tab")).addClass('current');
  }
});

$.ajax({
  type: 'GET',
  url: ajaxurl,
  data: {
    action: 'get_done',
    _ajax_nonce: ajax_obj.nonce,
  },
  dataType: 'json',
  success: function(data) {
    second_flag = data.data;
  }
});

function set_current_tab(attr) {
  $.ajax({
    type: 'POST',
    url: ajaxurl,
    data: {
      action: 'set_current_tab',
      current: attr,
      _ajax_nonce: ajax_obj.nonce
    },
    dataType: 'json',
  });
}

function unsaved(cur_link,link,attr){
  if (!link.hasClass('current')){
    for (var key in datas[cur_link]) {
      if (datas[cur_link].hasOwnProperty(key)){
        var obj = $('#'+key);
        if (obj.attr('type')== 'checkbox'){
          if (datas[cur_link][obj.attr('id')]!=obj.prop('checked')){
            error=true;
          }
        }else if (obj.attr('id') == "htcc-color-wp" ) {
          if (datas[cur_link][obj.attr('id')]!=obj.val().toUpperCase()){
            error=true;
          }
        }
        else {
          if (datas[cur_link][obj.attr('id')]!=obj.val()){

            error=true;
          }
        }
      }
    }
    if(!error){
      $(".step-wrapper ul li.tab-link").removeClass("current");
      $(".step-wrapper ul li.tab-link.current").addClass('done');
      $(".tab-content").removeClass("current");
      link.addClass("current");
      $("#"+attr).addClass("current");
    }else {
      $("#unsaved_option").show();
      $('#modal-overlay').show();
      $('body').css({'overflow':'hidden'});
      $('.save_change').on('click',function () {
        error = false;
        $('div.modal_close').off('click');
        $('#discard_button').off('click');
        save_from_form = true;
        $('#'+cur_link).find('#submit').click();
      });
    }
  }
  link.addClass('done');
  $(".step-wrapper ul li.tab-link").each(function () {
    var index = $(this).attr("data-tab").replace(/[^0-9]/gi, '');
    if ($(this).hasClass('done')) {
      flag[index] = true;
    }else {
      flag[index] = false;
    }
  });

  if (JSON.stringify(flag)!==JSON.stringify(second_flag)){
    $.extend(second_flag,flag);
    $.ajax({
      type: 'GET',
      url: ajaxurl,
      data: {
        action: 'send_done',
        state: flag,
        _ajax_nonce: ajax_obj.nonce,
      },
      dataType: 'json',
    });
  }

}

function send_next(next){
  set_current_tab(next.attr('data-tab'));
  next.addClass('done');
  $(".step-wrapper ul li.tab-link").each(function () {
    var index = $(this).attr("data-tab").replace(/[^0-9]/gi, '');
    if ($(this).hasClass('done')) {
      flag[index] = true;
    }
  });
  if (JSON.stringify(flag)!==JSON.stringify(second_flag)){
    $.extend(second_flag,flag);
    $.ajax({
      type: 'GET',
      url: ajaxurl,
      data: {
        action: 'send_done',
        state: flag,
        _ajax_nonce: ajax_obj.nonce,
      },
      dataType: 'json',
    });
  }
}

$(document).on("click", "#tab-1 #submit", function(event) {

  let scroll = false;
  $('.main-qa').each(function () {
    if ($(this).find('.qa-response').find('input').val() ==''|| $(this).find('.qa-question_value').find('input').length ==0|| !$(this).find('.qa-response').find('input').val().replace(/\s/g, '').length){
      $('<p class="tooltip qa"></p>')
          .text(" At least 1 keyword and 1 answer is required for each Q&A")
          .appendTo($(this).find('.qa-response'))
          .fadeIn('fast');
      $(this).find('.qa-response').find(".tooltip").delay(2000).fadeOut(300, function(){ $(this).remove();});
      scroll = $(this).find('.qa-response');
    }
  });
  $('.main-question').each(function () {
      if ($(this).find('.question-input__item').find('input').val() == '' || $(this).find('.question-input__item').find('input').val().replace(/\s/g, '').length < 1) {
          $('<p class="tooltip lq"></p>')
              .text("The question cannot be empty")
              .appendTo($(this).find('.question-input__wrapper'))
              .fadeIn('fast');
          $(this).find('.question-input__wrapper').find(".tooltip").delay(2000).fadeOut(300, function(){ $(this).remove();});
          scroll = $(this).find('.question-input__wrapper');
      }
  });

  if (scroll){
    $([document.documentElement, document.body]).animate({
      scrollTop: scroll.offset().top-250
    }, 1000);
    return false;
  }else{
    if (!save_from_form){
      var next = $(".step-wrapper ul li.tab-link.current").next();
      if (next.attr('data-tab')!=='tab-3'){
        var second_next = next.next();
      }
      if (!next.hasClass('done')){
        send_next(next)
      }else if (second_next && !second_next.hasClass('done')){
        send_next(second_next)
      }else {
        var cur = $(".step-wrapper ul li.tab-link.current");
        send_next(cur);
      }
    }
    return true;
  }
});
$(document).on("click", "#tab-2 #submit,#tab-3 #submit", function() {
  if (!save_from_form){
    var next = $(".step-wrapper ul li.tab-link.current").next();
    if (next.attr('data-tab')!=='tab-3'){
      var second_next = next.next();
    }
    if (!next.hasClass('done')){
      send_next(next)
    }else if (second_next && !second_next.hasClass('done')){
      send_next(second_next)
    }else {
      var cur = $(".step-wrapper ul li.tab-link.current");
      send_next(cur);
    }
  }
});

$("#discard_button").on("click", function(event) {
  var current = $('.step-wrapper ul li.tab-link.current').attr("data-tab");
  event.preventDefault();
  for (var key in datas[current]) {
    if (datas[current].hasOwnProperty(key)){
      var obj = $('#'+key);
      if (obj.attr('type') == 'checkbox'){
        if (datas[current][key]!=obj.prop('checked')){
          obj.click();
        }
      }else {
        obj.val(datas[current][obj.attr('id')]);
      }
    }
  }
  $(".htcc-color-wp").spectrum("set", $("#htcc-color-wp").val());

  $(".step-wrapper ul li.tab-link").removeClass("current");
  $(".tab-content").removeClass("current");
  $(".step-wrapper ul li.tab-link[data-tab='" + attr +"']").addClass('current');
  $("#"+attr).addClass("current");
  $('.lazyload').hide();
  $('.button-lazy-load').removeClass('opacity-button');
  $('body').css({'overflow':'auto'});
  $(".modal").hide();
  $('#modal-overlay').hide();
  error = false;
});

$.ajax({
  type: 'GET',
  url: ajax_obj.ajax_url,
  data: {
    action: "csv",
    _ajax_nonce: ajax_obj.nonce,
  },
  dataType: "text",
  success: function (data) {
    var blob = new Blob([data], { type: 'text/csv;charset=utf8' });
    var csvUrl = URL.createObjectURL(blob);

    $('#csv')
        .attr({
          'download': 'test.csv',
          'href': csvUrl
        });
  }
});
$('.qa-button__add').hover(
    function (event) {
      event.preventDefault();
      let page = $('.connected-page');
      let have_qa = $(this).siblings('.have_qa');
      if (!page.hasClass('pro')&&$('.main-qa').length!==0){
        $(this).find('.pro_button__wrapper').stop( true, false ).fadeIn( "fast" );
        $(this).find('.add_qa').css({'pointer-events':'none','opacity':'0.6'});
      }else
      if (!page.hasClass('pro')&&have_qa.length>0) {
        $(this).find('.pro_button__wrapper').stop( true, false ).fadeIn( "fast" );
        $(this).find('.add_qa').css({'pointer-events':'none','opacity':'0.6'});
      }
    },
    function (event) {
      event.preventDefault();
      let page = $('.connected-page');
      let have_qa = $(this).siblings('.have_qa');
      if (!page.hasClass('pro')&&$('.main-qa').length!==0){
          $(this).find('.add_qa').css({'pointer-events':'none','opacity':'0.6'});
          $(this).find('.pro_button__wrapper').stop( true, false ).fadeOut('fast');
      }else
      if (!page.hasClass('pro')&&have_qa.length>0) {
        $(this).find('.add_qa').css({'pointer-events':'none','opacity':'0.6'});
        $(this).find('.pro_button__wrapper').stop( true, false ).fadeOut('fast');
      }
    }
);
  $('.download__wrap').hover(
      function (event) {
          event.preventDefault();
          let page = $('.connected-page');
          let wrap = $('.contact_head__wrap');
          if (!page.hasClass('pro')){
              wrap.find('.pro_button__wrapper').stop( true, false ).fadeIn( "fast" );
          }
      },
      function (event) {
          event.preventDefault();
          let page = $('.connected-page');
          let wrap = $('.contact_head__wrap');
          if (!page.hasClass('pro')){
              wrap.find('.pro_button__wrapper').stop( true, false ).fadeOut('fast');
          }
      }
  );
$('.form-table tr').hover(
    function (event) {
      event.preventDefault();
      let field = $(this).find('div.row');
      let page = $('.connected-page');
      if (field.hasClass('as')&&!$("#htcc_fb_as_state").is(':checked')){
          return;
      }
      if (!page.hasClass('pro')&&field.hasClass('pro')){
        field.find('input,select').css({'pointer-event':'none','opacity':'0.6'});
        field.find('.pro_button__wrapper').stop( true, false ).fadeIn( "fast" );
      }
    },
    function (event) {
      event.preventDefault();
      let field = $(this).find('div.row');
      let page = $('.connected-page');
      if (!page.hasClass('pro')&&field.hasClass('pro')){
        field.find('input,select').css({'pointer-event':'none','opacity':'0.6'});
        field.find('.pro_button__wrapper').stop( true, false ).fadeOut('fast');
      }
    }

);
$('.new_leads').on('click',function (e) {
  set_current_tab('tab-1');
});




$('.button_cancel').on('click',function (e) {
  e.preventDefault();
  $('#cancel').show();
  $('#modal-overlay').show();
  $('body').css({'overflow':'hidden'});
  $('#cancel_sub').on('click',function (e) {
    e.preventDefault();
    $('.modal_close').attr("disabled", true);
    $('.button-close-modal').attr("disabled", true);
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'cancel_subscribe',
        _ajax_nonce: ajax_obj.nonce
      },
      dataType: 'json',
      error: function (data,response) {
        $('.modal_close').attr("disabled", false);
        $('.button-close-modal').attr("disabled", false);
      },
      success: function (data,response) {
        $('.cancel__wrapper').empty();
        $('.cancel__wrapper').append("<p class='subscribe_succes_cancel'>Your subscription will expire at the end of the billing cycle.</p>");
        var path = location.protocol + '//' + location.host + location.pathname + '?page=wp-chatbot';
        window.location = path;
      }
    });
  })
});
let current = $('.percentage-bar__card-used-number').data('value');
let max = $('.percentage-bar__card-max-number').data('value');
let wit = current/max*$('.percentage-bar__bar').width()+'px';
if (current>max){
  $('.percentage-bar__used-percentage').css({'width':wit,'background-color':'red'})
}else {
  $('.percentage-bar__used-percentage').css({'width':wit})
}

$(document).on("click",".pro_button__link,#button_update", function(e) {
  e.preventDefault();
  $('#pro_option').show();
  $('#modal-overlay').show();
  $('body').css({'overflow':'hidden'});
});
$.fn.exists = function() { return this.length > 0; };

if ( $(".payment_info").exists() ) {
  $('#pay_plan').on('click',function (e) {
    e.preventDefault();
    $('#pay_plan').addClass('opacity-button');
    $('#pay_plan div.lazyload').css('display', 'block');
    $('#pay_plan').css({'pointer-event':'none'});
   $("#pay_plan").attr("disabled", true);
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'create_subscribe',
        _ajax_nonce: ajax_obj.nonce,
      },
      dataType: 'json',
      error: function (data,response) {
        $('#errors').stop( true, false ).fadeIn( "fast" );
        $('#pay_plan').removeClass('opacity-button');
        $('#pay_plan div.lazyload').css('display', 'none');
        $('#pay_plan').css({'pointer-event':'all'});
        $('#errors').text("Unable to create a subscription. Retry the request later");
        $("#pay_plan").attr("disabled", false);
      },
      success: function (data,response) {
          var path = location.protocol + '//' + location.host + location.pathname + '?page=wp-chatbot';
          window.location = path;
      }
    });
  });
}else {
  recurly.configure({
    publicKey: 'ewr1-oZyeuL07BdPz9I7T4DxgDO', // Set this to your own public key
    style: {
      all: {
        fontFamily: 'Open Sans',
        fontSize: '1rem',
        fontWeight: 'bold',
        fontColor: '#2c0730',
        required: 'true'
      }
    }
  });
  $('form#checkout-form').submit( function (event) {
    event.preventDefault();
    var form = this;
    $('#pay_plan').addClass('opacity-button');
    $('#pay_plan div.lazyload').css('display', 'block');
    $('#pay_plan').css({'pointer-event':'none'});
    $("#pay_plan").attr("disabled", true);
    recurly.token(form, function (err, token) {
      if (err) {
        $('#errors').css({'display':'flex'});
        $("#pay_plan").attr("disabled", false);
        $('#pay_plan').removeClass('opacity-button');
        $('#pay_plan div.lazyload').css('display', 'none');
        $('#pay_plan').css({'pointer-event':'all'});
        $('#errors').text(err?.message || "Invalid billing details. Please try again.");
        return;
      }
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'create_subscribe',
          token:token.id,
          _ajax_nonce: ajax_obj.nonce,
        },
        dataType: 'json',
        error: function (data,response) {
          $('#errors').stop( true, false ).fadeIn( "fast" );
          $('#pay_plan').removeClass('opacity-button');
          $("#pay_plan").attr("disabled", false);
          $('#pay_plan div.lazyload').css('display', 'none');
          $('#pay_plan').css({'pointer-event':'all'});
          $('#errors').text("Unable to create a subscription. Retry the request later");
        },
        success: function (data,response) {
          var path = location.protocol + '//' + location.host + location.pathname + '?page=wp-chatbot';
          window.location = path;
        }
      });
    });
  });
  function errorm (err) {
    $('button').prop('disabled', false);
  }
 }
function email_recreate(lq){
  $.ajax({
    type: 'GET',
    url: ajaxurl,
    data: {
      action: 'email_section',
      has_lq: lq,
      _ajax_nonce: ajax_obj.nonce,
    },
    dataType: 'json',
    success: function (data,response) {
      $('#email_test').empty();
      $('#email_test').append(data.data);
    }
  });
}


  $(document).on("click",".qa__bin", function() {
    var dataIndex = $(this).attr("data-index");
    var mainId = $(this).parents(".main-qa").attr("id").replace(/[^0-9]/gi, '');
    var inputs = $(this).parents(".qa-question__wrap").find(".qa-question_value").find("input");
    if(inputs.length === 1) {
      if($(this).find('.tooltip').length==0){
        $('<p class="tooltip"></p>')
            .text(" At least 1 keyword and 1 answer is required for each Q&A")
            .appendTo($(this))
            .fadeIn('fast');
      }
      return;
    }
    $(this).parents(".qa-question__wrap").find("#htcc_qa_"+mainId+'_'+dataIndex).remove();
    $(this).parent(".qa-question-block-item").remove();
  });
  $(document).on("mouseleave",".qa__bin", function() {
      $(this).find(".tooltip").fadeOut(300, function(){ $(this).remove();});
  });

  $('.main_banner_button').on('click',function (e) {
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'main_notice',
        _ajax_nonce: ajax_obj.nonce
      },
      dataType: 'json',
      success: function (data,response) {
        $('.main_banner_button').parents('.banner_main__wrap').fadeOut(300, function(){ $(this).remove();});
      }
    });
  });
  $(document).on("click",".close-guard-notify", function( event ) {
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'cg_notice',
        _ajax_nonce: ajax_obj.nonce,
      },
      dataType: 'json',
      success: function (data,response) {
        $('.comment-guard-notify').fadeOut(1000, function(){ $(this).remove();});
      }
    });
  });
$(document).on("click",".limit-notify-close", function( event ) {
      $('.limit__notice_tooltip').fadeOut(500, function(){ $(this).remove();});
});
  $(document).on("click",".add_qa_question", function( event ) {
    event.preventDefault();
    let fieldsetId  = $(this).parents(".main-qa").find(".qa-question-result").last().attr("data-index");
    fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):0;
    const mainId = $(this).parents(".main-qa").attr("id").replace(/[^0-9]/gi, '');
    const elem = $(this).siblings('input');
    let value = elem.val();
    const length = $(this).parents(".main-qa").find(".qa-question-result").length;
    let flag;
    if( length === 10 ) {
      return;
    }
    const count = Number(fieldsetId) + 1;
    if (!value|| !value.replace(/\s/g, '').length){
      value = 'Q&A '+mainId+' Keyword#'+count;
    }
    const inputValues = $('.qa-question-result').map(function() {
      return $(this).text();
    }).toArray();
    flag = $.inArray(value, inputValues) !== -1?true:false;
    if (flag){
      elem.val("");
    }else {
      let newAnswer = '<div class="qa-question-block-item">'+
      '<span class="qa-question-result" data-index="'+count+'">'+value+'</span>'+
      '<div class="edit_qa" data-index="'+count+'"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="qa__bin" data-index="'+count+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div></div>';
      let newInput = '<input type="hidden" name="htcc_as_options[qa_'+mainId+'][phrases][]" value="'+value+'" id="htcc_qa_'+mainId+'_'+count+'">';
      $(this).siblings('input').val('');
      $(this).parents(".main-qa").find(".qa-question-block").append(newAnswer);
      $(this).parents(".main-qa").find(".qa-question_value").append(newInput);
    }
  });

  $(document).on("click",".qa-question-result,.edit_qa", function( event ) {
    event.preventDefault();
    $(".qa-input__wrapper").hide();
    var dataIndex = $(this).attr("data-index");
    var mainId = $(this).parents(".main-qa").attr("id").replace(/[^0-9]/gi, '');
    var answerValue = $("#htcc_qa_"+mainId+"_"+dataIndex).val();
    $("#main_qa_"+mainId).find("#qa-state").attr("data-index", dataIndex).val(answerValue);
    if ($(this).hasClass("qa-question-result")){
      var left = $(this).position().left;
      var top = $(this).position().top;
    }else {
      var left = $(this).prev('.qa-question-result').position().left;
      var top = $(this).prev('.qa-question-result').position().top;
    }
    $(".qa-input__wrapper").css({top: top+58});
    $("#main_qa_"+mainId).find(".triangle").css({left: left+32});
    $("#main_qa_"+mainId).find(".qa-input__wrapper").show();
  });
$(document).on("click", ".qa_cancel", function( event ) {
  event.preventDefault();
  $(".qa-input__wrapper").hide();
});
$(document).on("click",".qa_submit", function( event ) {
  event.preventDefault();
  var mainId = $(this).parents(".main-qa").attr("id").replace(/[^0-9]/gi, '');
  var answerValue = $(this).parents(".main-qa").find("#qa-state").val();
  var dataIndex = $(this).parents(".main-qa").find("#qa-state").attr("data-index");
  $("#htcc_qa_"+mainId+"_"+dataIndex).val(answerValue);
  $(this).parents(".main-qa").find(".qa-input__wrapper").hide();
  $(this).parents(".main-qa").find(".qa-question-result[data-index='"+dataIndex+"']").text(answerValue);
});
$(document).on("click", ".del_qa", function() {
  let wrapper = $(this).parents('.qa_new-wrapper');
  let isempt = false;
  wrapper.find('input').each(function () {
    if ($(this).val() != ""){
      isempt = true;
    }
  });
  if (isempt == false){
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        action: 'set_pre_val',
        _ajax_nonce: ajax_obj.nonce,
      }
    });
  }
  $(this).parents(".main-qa").prev('h3').remove();
  $(this).parents(".main-qa").remove();
  wrapper.find('h3').each(function (index,value) {
    let number = index+1;
    $(this).text('Q&A '+number);
  });
  $('.add_qa').css({'pointer-events':'all','opacity':'1'});
});
$(".add_qa").on("click", function() {
  var fieldsetId = $(".qa_new-wrapper").find(".main-qa").last().attr("id");
  let page = $('.connected-page');
  let have_qa = $(this).parents('.qa-button__add').siblings('.have_qa');
  if (!page.hasClass('pro')&&have_qa.length>0){
    return;
  }
  if (!page.hasClass('pro')&&$('.main-qa').length!==0){
   return;
  }
  if (fieldsetId){
    fieldsetId=fieldsetId.replace(/[^0-9]/gi, '');
    var copyBlock = $(".qa_new-wrapper").find(".main-qa").last().clone();
    var count = Number(fieldsetId) + 1;
    if (copyBlock.find(".qa-question_value").children("input").length == 0 ){
        copyBlock.find(".qa-question_value").append('<input type="hidden" name="htcc_as_options[qa_1][phrases][]" value="Q&A 1 Keyword#1" id="htcc_qa_1_1">');
        copyBlock.find(".qa-question-block").append('<div class="qa-question-block-item"><span class="qa-question-result" data-index="'+fieldsetId+'">Q&A '+fieldsetId+' Keyword#1</span><div class="edit_qa" data-index="'+fieldsetId+'"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="qa__bin" data-index="'+fieldsetId+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div>');
    }
    copyBlock.find(".qa-response").children("input").attr("id", "htcc_qa_"+count+"_answer").attr("name", "htcc_as_options[qa_"+count+"][bot_responses]").val("");
    copyBlock.find(".qa-question-block-item").find(".qa-question-result").text("Q&A "+count+" Keyword#1");
    copyBlock.find(".qa-question-block-item").not(':first').remove();
    copyBlock.find(".qa-question_value").children("input").not(':first').remove();
    copyBlock.find(".qa-question_value").children("input").first().attr("id", "htcc_qa_"+count+"_1").attr("name", "htcc_as_options[qa_"+count+"][phrases][]").val("Q&A "+count+" Keyword#1");
    var html = "";
    html += '<div class="main-qa" id="main_qa_'+count+'">';
    html += copyBlock.html();
    html += '</div>';
    $(".qa_new-wrapper").append('<h3>Q&A '+count+'</h3>');
    $(".qa_new-wrapper").append(html);
    $("#main_qa_"+count).find(".qa-response").children("input").val("");
  }else {
    fieldsetId=1;
    $(".qa_new-wrapper").html('<h3>Q&A '+fieldsetId+'</h3><div class="main-qa" id="main_qa_'+fieldsetId+'"><div class="qa-question__wrap"><div class="qa-question_input"><h6>If user says something similar to</h6><div class="question_button_wrap"><input type="text" placeholder="e.g.&quot;Home&quot;,&quot;prices&quot;,etc." autocomplete="off"><div class="add_qa_question">Add</div></div></div><div class="qa-question-block"><div class="qa-question-block-item"><span class="qa-question-result" data-index="'+fieldsetId+'">Q&A '+fieldsetId+' Keyword#1</span><div class="edit_qa" data-index="'+fieldsetId+'"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="qa__bin" data-index="'+fieldsetId+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div></div><div class="qa-question_value"> <input type="hidden" name="htcc_as_options[qa_1][phrases][]" value="Q&A 1 Keyword#1" id="htcc_qa_1_1"></div><div class="qa-input__wrapper" style="display: none;"><span class="triangle"></span><div class="qa-input__item"><input type="text" id="qa-state"></div><div class="qa-input__state"><span class="qa_cancel">Cancel</span><span class="qa_submit">OK</span></div></div></div><div class="qa-response"><h6>Wp-chatbot will respond with</h6><input type="text" name="htcc_as_options[qa_1][bot_responses]" placeholder="Enter the answer here" id="htcc_qa_'+fieldsetId+'_answer" autocomplete="off"></div><div class="del_qa"> <i class="fa fa-trash-o" aria-hidden="true"></i></div></div>');
  }});

$('#qa-state').keypress(function(event){
  if(event.keyCode == 13){
    $('.qa_submit').click();
  }
});
$('body').on("keypress",'.question_button_wrap input', function(event){
  if(event.keyCode == 13){
    $(this).siblings('.add_qa_question').click();
  }
});
$(document).on("keypress", 'form', function (e) {
  var code = e.keyCode || e.which;
  if (code == 13) {
    e.preventDefault();
    return false;
  }
});




var questionState;

  $(document).on("click",".add__answer", function( event ) {
      event.preventDefault();
      var fieldsetId  = $(this).parents(".main-question").find(".answer__result").last().attr("data-index");
      fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):1;
      var mainId = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      if( fieldsetId == 10 ) {
          return;
      }
      var count = Number(fieldsetId) + 1;
      var newAnswer = '<div class="answer-item__result">'+
          '<span class="answer__result" data-index="'+count+'">Answer#'+count+'</span>'+
          '<div class="edit_answer" data-index="'+count+'"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="answer__bin" data-index="'+count+'"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div>';
      var newInput = '<input type="hidden" name="htcc_as_options[lq_'+mainId+'][answers'+count+'][answer]" value="Answer#'+count+'" id="htcc-answer_'+mainId+'_'+count+'">';
      var newQualified = '<input id="qualified_answer_'+mainId+'_'+count+'" name="htcc_as_options[lq_'+mainId+'][answers'+count+'][qualified]" value="0" type="hidden">';
      $(this).parents(".main-question").find(".answer-input__button").append(newAnswer);
      $(this).parents(".main-question").find(".answer-input__value").append(newInput,newQualified);
  });

  $(document).on("click",".edit_answer, .answer__result", function( event ) {
      event.preventDefault();
      $(".answer-input__wrapper").hide();
      var dataIndex = $(this).attr("data-index");
      var mainId = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      var answerValue = $("#htcc-answer_"+mainId+"_"+dataIndex).val();
      var answerQualified = $("#main_question_"+mainId).find("#qualified_answer_"+mainId+"_"+dataIndex).val();
      if (answerQualified == 0){
        $("#main_question_"+mainId).find("#qualified").attr('checked', false).val(answerQualified);
      }else {
        $("#main_question_"+mainId).find("#qualified").attr('checked', true).val(answerQualified);
      }
      $("#main_question_"+mainId).find("#answer-state").attr("data-index", dataIndex).val(answerValue);
      if ($(this).hasClass("answer__result")){
        var left = $(this).position().left;
        var top = $(this).position().top;
      }else {
        var left = $(this).prev('.answer__result').position().left;
        var top = $(this).prev('.answer__result').position().top;
      }
      $(".answer-input__wrapper").css({top: top+58});
      $("#main_question_"+mainId).find(".triangle").css({left: left+32});
      $("#main_question_"+mainId).find(".answer-input__wrapper").show();
  });
  $(document).on("click","body", function(e) {
    if(!$(event.target).closest(".answer__result,.answer-input__wrapper,.edit_answer").length){
      $(".answer-input__wrapper").hide();
    }
    if(!$(event.target).closest(".qa-question-result,.qa-input__wrapper,.edit_qa").length){
      $(".qa-input__wrapper").hide();
    }
  });


  $(document).on("click",".del_as", function() {
    var inputs = $(this).parents(".as_main__wrap").find("input");
    if (inputs.length === 1){
      if($(this).find('.tooltip').length==0){
        $('<p class="tooltip"></p>')
            .text("At least 1 question is required for Answering Service")
            .appendTo($(this))
            .fadeIn('fast');
      }
      return;
    }
    $(this).parent('.as_item__wrap').remove();
  });
$(document).on("mouseleave",".del_as", function() {
  $(this).find(".tooltip").fadeOut(300, function(){ $(this).remove();});
});
  $(document).on("click",".add_as", function() {
    var fieldsetId = $('.as_main__wrap').find('.as_item__wrap').last().find('input').attr("id");
    if (fieldsetId){
      fieldsetId = fieldsetId.replace(/[^0-9]/gi, '');
      var count = Number(fieldsetId) + 1;
      var block_as = $('.as_main__wrap').children().last().clone();
      block_as.find('.fb_answer').attr('id', 'fb_answer'+count);
      if (count>3&&block_as.find('.del_as').length==0){
        block_as.append('<div class="del_as"><i class="fa fa-trash-o" aria-hidden="true"></i></div>');
      }
      $(".as_main__wrap").append(block_as);
      $(".answer_server").find('.fb_answer').last().attr('value', 'Question '+count);
    }else {
      $(".as_main__wrap").append('<div class="as_item__wrap"><input type="text" id="fb_answer1" name="htcc_as_options[fb_answer][]" class="fb_answer" value="Question 1"><div class="del_as"><i class="fa fa-trash-o" aria-hidden="true"></i></div></div>')
    }
  });



  $(document).on("click",".answer__bin", function() {
      var dataIndex = $(this).attr("data-index");
      var mainId = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      var inputs = $(this).parents(".answer-input__block").find(".answer-input__value").find("input");
      if(inputs.length === 2) {
          if($(this).find('.tooltip').length==0){
              $('<p class="tooltip"></p>')
                  .text("At least 1 answer is required for Lead Qualifier questions")
                  .appendTo($(this))
                  .fadeIn('fast');
          }
          return;
      }
      $(this).parents(".answer-input__block").find("#htcc-answer_"+mainId+'_'+dataIndex).remove();
      $(this).parents(".answer-input__block").find("#qualified_answer_"+mainId+'_'+dataIndex).remove();
      $(this).parent(".answer-item__result").remove();
  });
  $(document).on("mouseleave",".answer__bin", function() {
      $(this).find(".tooltip").fadeOut(300, function(){ $(this).remove();});
  });

  $(document).on("click", ".answer_cancel", function( event ) {
      event.preventDefault();
      $(".answer-input__wrapper").hide();
  });

  $(".add_question").on("click", function() {
      var fieldsetId = $(".question_new-wrapper").find(".main-question").last().attr("id");
      if (fieldsetId){
        fieldsetId=fieldsetId.replace(/[^0-9]/gi, '');
        var copyBlock = $(".question_new-wrapper").find(".main-question").last().clone();
        var count = Number(fieldsetId) + 1;
        copyBlock.find(".question-input__item").children("input").attr("id", "htcc-q"+count).attr("name", "htcc_as_options[lq_"+count+"][question]").val("");
        copyBlock.find(".question-input__item").children(".question_text").remove();
        copyBlock.find(".answer-input__block").find(".answer__result").text("Answer#1");
        copyBlock.find(".question-input__wrapper").css({"width":"auto"});
        copyBlock.find(".question-input__wrapper").children(".question-input__state").css({"display":"flex"});
        copyBlock.find(".answer-item__result").not(':first').remove();
        copyBlock.find(".answer-input__value").children("input").slice(2).remove();
        copyBlock.find(".answer-input__value").children("input").first().attr("id", "htcc-answer_"+count+"_1").attr('name',"htcc_as_options[lq_"+count+"][answers1][answer]").val("Answer#1");
        copyBlock.find(".answer-input__value").children("input:nth-child(2)").attr("id", "qualified_answer_"+count+"_1").attr('name',"htcc_as_options[lq_"+count+"][answers1][qualified]]").val("0");
        copyBlock.find(".question-input__wrapper").children(".question-input__item").children("#htcc-q"+count).show();
        var html = "";
        html += '<div class="main-question" id="main_question_'+count+'">';
        html += copyBlock.html();
        html += '</div>';
        $(".question_new-wrapper").append(html);
        $("#main_question_"+count).find(".question-input__item").children("#htcc-q"+count).val("New Question "+count);
        $("#main_question_"+count).children("h3").text("Question "+count);
      }else {
        fieldsetId=1;
        $(".question_new-wrapper").html('<div class="main-question" id="main_question_'+fieldsetId+'"><h3>QUESTION '+fieldsetId+'</h3><div class="question-block__wrapper"><div class="question-block__header"><div class="header__close"></div></div><div class="question-block_content"><div class="question-input__wrapper"><div class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></div><div class="question-input__item"><input id="htcc-q'+fieldsetId+'" name="htcc_as_options[lq_'+fieldsetId+'][question]" value="New Question '+fieldsetId+'" type="text"></div><div class="question-input__state"><span class="question_cancel">Cancel</span><span class="question_submit">OK</span></div></div><div class="answer-input__block"><div class="answer-input__button"><div class="answer-item__result"><span class="answer__result" data-index="1">Answer#1</span><div class="edit_answer" data-index="1"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="answer__bin" data-index="1"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div></div><div class="answer-input__add"><span class="add__answer" data-index="1"><b>+</b> Add answer</span></div><div class="answer-input__value"><input id="htcc-answer_'+fieldsetId+'_1" name="htcc_as_options[lq_'+fieldsetId+'][answers1][answer]" value="Answer#1" type="hidden"><input id="qualified_answer_'+fieldsetId+'_1" name="htcc_as_options[lq_'+fieldsetId+'][answers1][qualified]" value="0" type="hidden"></div><div class="answer-input__wrapper"><span class="triangle"></span><div class="answer-input__item"><input type="text" id="answer-state"><input type="checkbox" id="qualified"><p>Mark as qualified answer</p></div><div class="answer-input__state"><span class="answer_cancel">Cancel</span><span class="answer_submit">OK</span></div></div></div></div></div></div>');
      }
      email_recreate(true);
  });

  $(document).on("click",".answer_submit", function( event ) {
      event.preventDefault();
      var mainId = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      var answerValue = $(this).parents(".main-question").find("#answer-state").val();
      if (!answerValue.replace(/\s/g, '').length){
          if($(this).find('.tooltip').length==0){
              $('<p class="tooltip lq_answer"></p>')
                  .text("The answer cannot be empty")
                  .appendTo($(this).parents(".main-question").find('.answer-input__item'))
                  .fadeIn('fast');
          }
          $(this).parents(".main-question").find('.answer-input__item').find(".tooltip").delay(2000).fadeOut(300, function(){ $(this).remove();})
      }else {
          var dataIndex = $(this).parents(".main-question").find("#answer-state").attr("data-index");
          var answerQualified = 0;
          if($(this).parents(".main-question").find("#qualified").is(':checked')){
            answerQualified = 1;
          }else {
            answerQualified = 0;
          }
          $(this).parents(".main-question").find("#qualified_answer_"+mainId+"_"+dataIndex).val(answerQualified);
          $("#htcc-answer_"+mainId+"_"+dataIndex).val(answerValue);
          $(this).parents(".main-question").find(".answer-input__wrapper").hide();
          $(this).parents(".main-question").find(".answer__result[data-index='"+dataIndex+"']").text(answerValue);
      }
  });

  $(document).on("click", ".question_submit", function() {
      var id = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      $("#htcc-q"+id).hide();
      var val = $("#htcc-q"+id).val();
      questionState = val;
      var question = '<div class="question_text">'+val+'</div>';
      $("#htcc-q"+id).parent(".question-input__item").append(question);
      $("#htcc-q"+id).parents(".question-input__wrapper").css({"width":"fit-content"});
      $(this).parents(".main-question").find(".question-input__state").hide();
  });

  $(document).on("click", ".question_text, .edit", function() {
      var elem = $(this).parents('.question-input__wrapper').find('.question_text');
      var val = elem.text();
      questionState = val;
      elem.prev("input").val(val);
      elem.prev("input").show();
      elem.parents(".main-question").find(".question-input__wrapper").css({"width":"auto"});
      elem.parents(".main-question").find(".question-input__state").css({"display":"flex"});
      elem .parents(".main-question").find(".question_text").remove();
  });

  $(document).on("click", ".question_cancel", function() {
      var id = $(this).parents(".main-question").attr("id").replace(/[^0-9]/gi, '');
      $("#htcc-q"+id).hide();
      questionState='';
      if (!questionState&&$("#htcc-q"+id).val()){
        questionState=$("#htcc-q"+id).val();
      }
      var question = '<div class="question_text">'+questionState+'</div>';
      $("#htcc-q"+id).parent(".question-input__item").append(question);
      $("#htcc-q"+id).parents(".question-input__wrapper").css({"width":"fit-content"});
      $("#htcc-q"+id).parents(".question-input__wrapper").find(".question-input__state").hide();
  });

  $(document).on("click", ".header__close", function() {
    $(this).parents(".main-question").remove();
    if ($('.main-question').length){
      email_recreate(true);
    }else {
      email_recreate(false);
    }
  });
$("#country").on("change", function (e) {
  var val = e.target.value;

  if(val === 'US') {
    $(".states-select").show();
    $(".states-input").hide();
  }else {
    $(".states-select").hide();
    $(".states-input").show();
  }
});

$(document).on("click", ".button_download_app", function() {
  $('#promo_app').show();
  $('#promo_app').find(".ios-app__wrap").hide();
  $('#promo_app').find(".promo-app__wrapper").show();
  $('#modal-overlay').show();
});
$(document).on("click",".ios_app",function () {
  $(this).parent(".promo-app__wrapper").hide();
  $(this).parents("#promo_app").find('.ios-app__wrap').show();
});


});
