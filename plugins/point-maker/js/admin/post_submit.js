



var point_maker_timeoutID;

document.addEventListener("DOMContentLoaded", function () {
  //$window = window,//jQuery(window),
  var $html = document.documentElement,//jQuery('html'),
    $body = document.body,
    $overlay = document.getElementById('p_m_overlay'),
    scrollbar_width = window.innerWidth - document.body.scrollWidth,
    touch_start_y,
    scrollbar_scrolltop;

  
  //$window.on('touchstart', function(event) {
  window.addEventListener('touchstart', function (event) {
    touch_start_y = event.originalEvent.changedTouches[0].screenY;
  });

  if (document.getElementById('point_maker_modal_open')) {

    document.getElementById('point_maker_modal_open').onclick = function () {

      
      if ('scrollingElement' in document) {
        
        scrollbar_scrolltop = document.scrollingElement.scrollTop;
      } else {
        
        scrollbar_scrolltop = document.body.scrollTop;
      }

      
      //$window.on('touchmove.noscroll', function(event) {
      window.addEventListener('touchmove.noscroll', function (event) {
        var overlay = $overlay[0],
          current_y = event.originalEvent.changedTouches[0].screenY,
          
          height = $overlay.offsetHeight,
          is_top = touch_start_y <= current_y && overlay.scrollTop === 0,
          is_bottom = touch_start_y >= current_y && overlay.scrollHeight - overlay.scrollTop === height;

        
        if (is_top || is_bottom) {
          
          event.preventDefault();
        }
      }, false);
      
      //jQuery('html, body').css('overflow', 'hidden');
      $html.style.overflow = 'hidden';
      $body.style.overflow = 'hidden';
      
      if (scrollbar_width) {
        
        $html.style.paddingRight = scrollbar_width;
        //$html.css('padding-right', scrollbar_width);
      }
      
      $overlay.style.visibility = 'hidden';
      //jQuery('#p_m_overlay').css('visibility','hidden');
      //$overlay.fadeIn(300, function() { jQuery('#p_m_overlay textarea.w_b_post_text').trigger('input'); });
      point_maker_fadeIn($overlay, 'block');
      $overlay.style.visibility = 'visible';
      //jQuery('#p_m_overlay').css('visibility','visible');
      //jQuery('#w_b_overlay textarea.w_b_post_text').trigger('input');

    }

  }

  
  var closeModal = function () {
    $body.style.overflow = '';
    //removeAttr('style');
    //$window.off('touchmove.noscroll');
    
    window.removeEventListener('touchmove.noscroll', arguments.callee);
    
    point_maker_fadeOut($overlay);
    //$overlay.animate({
    //  opacity: 0
    //}, 300, function() {
    
    //$overlay.scrollTop(0).hide().removeAttr('style');
    
    $html.style.overflow = '';
    //$html.removeAttr('style');
    //});

    //console.log('scrollbar_scrolltop> '+scrollbar_scrolltop)
    
    if (scrollbar_scrolltop === 0) scrollbar_scrolltop = 1;

    
    if ('scrollingElement' in document) {
      
      document.scrollingElement.scrollTop = scrollbar_scrolltop;
    } else {
      
      document.body.scrollTop = scrollbar_scrolltop;
    }


  };
  /*
    $('#p_m_overlay').onclick = function(event) {
      if (!jQuery(event.target).closest('.p_m_modal').length) {
        closeModal();
      }
    }
    */
  /*
  jQuery('#p_m_overlay').on('click', function(event) {
    if ( !jQuery(event.target).closest('#p_m_modal').length && !jQuery(event.target).closest('#p_m_preview').length ) {
      //closeModal();
    }
  });
  */
  if ($overlay) {

    $overlay.addEventListener('click', function (e) {
      if (e.target.id && e.target.id === 'p_m_overlay') {
        closeModal();
      }
    });

  }

  if (document.getElementById('point_maker_modal_close')) {
    document.getElementById('point_maker_modal_close').onclick = function () {
      closeModal();
    }
  }

  if (document.getElementById('point_maker_submit')) {

    document.getElementById('point_maker_submit').onclick = function () {

      var word = point_maker_now_shortcode_copy(),

        editor = point_maker_now_editor();


      if (editor === 'text') {
        var textarea = document.querySelector('textarea.wp-editor-area');

        word = word + '\n';
        if (document.selection && textarea.tagName == 'TEXTAREA') {
          
          textarea.focus();
          sel = document.selection.createRange();
          sel.word = word;
          textarea.focus();
        } else if (textarea.selectionStart || textarea.selectionStart == '0') {
          
          startPos = textarea.selectionStart;
          endPos = textarea.selectionEnd;
          scrollTop = textarea.scrollTop;
          textarea.value = textarea.value.substring(0, startPos) + word + textarea.value.substring(endPos, textarea.value.length);
          textarea.focus();
          textarea.selectionStart = startPos + word.length;
          textarea.selectionEnd = startPos + word.length;
          textarea.scrollTop = scrollTop;
        } else {
          
          textarea.value += word;
          textarea.focus();
          textarea.value = textarea.value; 
        }

        
        
        
        



      } else {
        
        tinymce.activeEditor.execCommand('mceInsertContent', false, word + '<br><span id="_w_b_caret"></span><br>');

        tinymce.activeEditor.focus();
        tinymce.activeEditor.selection.select(tinymce.activeEditor.dom.select('#_w_b_caret')[0]);
        tinymce.activeEditor.selection.collapse(0);
        tinymce.activeEditor.dom.setAttrib('_w_b_caret', 'id', '');

      }

      closeModal();

      point_maker_pop_up_message(point_maker_translations.pop_up_shortcode , '#28a745');

    }

  }


});






function point_maker_fadeOut(el) {
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .1) < 0) {
      el.style.display = "none";
    } else {
      requestAnimationFrame(fade);
    }
  })();
};

function point_maker_fadeIn(el, display) {
  el.style.opacity = 0;
  el.style.display = display || "block";

  (function fade() {
    var val = parseFloat(el.style.opacity);
    if (!((val += .1) > 1)) {
      el.style.opacity = val;
      requestAnimationFrame(fade);
    }
  })();
};

function point_maker_stopTimeout() {
  var pop_up_message = document.getElementById('p_m_pop_up_message');
  pop_up_message.classList.add('inactive');
  clearTimeout(point_maker_timeoutID);
  setTimeout(function () {
    pop_up_message.classList.remove('inactive');
  }, 100);

}

function point_maker_pop_up_message(message , background_color) {
  if (typeof point_maker_timeoutID !== 'undefined')
    point_maker_stopTimeout();
  var pop_up_message = document.getElementById('p_m_pop_up_message');
  pop_up_message.style.backgroundColor = background_color;
	pop_up_message.style.color = point_maker_BlackOrWhite(background_color);
  pop_up_message.classList.add('active');
  pop_up_message.innerHTML = message;
  point_maker_timeoutID = setTimeout(function () {
    pop_up_message.classList.remove('active');
  }, 4000);
}


function point_maker_now_shortcode_copy() {
  var type = point_maker_get_type(),
    title = document.getElementById('p_m_edit_title').value,
    title_icon = document.getElementById('p_m_selected_title_icon').value,
    edit_content = document.getElementById('p_m_edit_content').value,
    content_type = point_maker_get_radio_value('p_m_edit_content_type'),
    list_icon = document.getElementById('p_m_selected_list_icon').value,
    base_color = document.getElementById('p_m_selected_color').value;

  var title_atts = '',
    content_atts = '',
    temp = '';


  
  
  
  if (title !== '')
    title_atts += ' title="' + title + '"';
  if (title_icon !== '')
    title_atts += ' title_icon="' + title_icon + '"';

  temp = document.getElementById("p_m_title_color_background").checked;
  if (temp) {
    title_atts += ' title_color_background="true"';
  } else {
    title_atts += ' title_color_background="false"';
  }

  temp = document.getElementById("p_m_title_color_border").checked;
  if (temp) {
    title_atts += ' title_color_border="true"';
  } else {
    title_atts += ' title_color_border="false"';
  }


  
  
  
  content_atts += ' content_type="' + content_type + '"';
  if (content_type === 'list')
    content_atts += ' list_icon="' + list_icon + '"';

  temp = document.getElementById("p_m_content_color_background").checked;
  if (temp) {
    content_atts += ' content_color_background="true"';
  } else {
    content_atts += ' content_color_background="false"';
  }

  temp = document.getElementById("p_m_content_color_border").checked;
  if (temp) {
    content_atts += ' content_color_border="true"';
  } else {
    content_atts += ' content_color_border="false"';
  }

  return '[point_maker type="' + type + '" base_color="' + base_color + '"' + title_atts + content_atts + ']' + edit_content + '[/point_maker]';
}

function point_maker_now_editor() {

  if (document.getElementById('wp-content-wrap')) {

    if (document.getElementById('wp-content-wrap').classList.contains('html-active')) {
      
      return 'text';
    } else {
      
      return 'visual';
    }

  } else {
    
    return 'block';
  }

}
























