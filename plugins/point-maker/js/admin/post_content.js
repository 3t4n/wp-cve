function point_maker_change_content() {

  var content = document.getElementById('p_m_change_detail'),
    edit_content = document.getElementById('p_m_edit_content').value,

    content_type = point_maker_get_radio_value('p_m_edit_content_type');

  content.classList.remove('p_m_text', 'p_m_list');

  content.classList.add('p_m_' + content_type);

  if (content_type === 'text') {

    content.innerHTML = edit_content.replace(/\r?\n/g, '<br>');

  } else if (content_type === 'list') {

    var color = point_maker_colors[document.getElementById('p_m_selected_color').value]['base'],
      dark_color = point_maker_colors[document.getElementById('p_m_selected_color').value]['dark'],
      icon = '<svg class="p_m_list_icon" width="16" height="16" fill="' + color + '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32">' + point_maker_icons[document.getElementById('p_m_selected_list_icon').value] + '</svg>';

    var arr = edit_content.split(/\r\n|\n/);

    var list_content = '<ul class="p_m_list">';

    for (var i = 0; i < arr.length; i++) {
      if (arr[i] !== '')
        list_content += '<li class="p_m_flex p_m_ai_c" style="margin:8px 0;padding:0;">' + icon + arr[i] + '</li>';
    }

    content.innerHTML = list_content + '</ul>';

  }

}


function point_maker_change_content_type() {

  var content_type = point_maker_get_radio_value('p_m_edit_content_type'),
    p_m_select_list_icon_wrap = document.getElementById('p_m_select_list_icon_wrap');

  if (content_type === 'list') {
    p_m_select_list_icon_wrap.style.display = 'block';
  } else {
    p_m_select_list_icon_wrap.style.display = 'none';
  }

  point_maker_change_content();
}

if (document.getElementById('p_m_edit_content')) {

  document.getElementById('p_m_edit_content').addEventListener('input', function (e) {

    point_maker_change_content();

  }, false);

}


function point_maker_content_color_background() {

  var type = point_maker_get_type();
  if (type === 'heading_icon') {

    if (document.getElementById("p_m_content_color_background").checked) {
      document.getElementById("p_m_checked_content_color_background").value = "true";
      document.getElementById("p_m_title_color_background").checked = true;
      document.getElementById("p_m_checked_title_color_background").value = "true";
    } else {
      document.getElementById("p_m_checked_content_color_background").value = "false";
      document.getElementById("p_m_title_color_background").checked = false;
      document.getElementById("p_m_checked_title_color_background").value = "false";
    }

  } else {

    if (document.getElementById("p_m_content_color_background").checked) {
      document.getElementById("p_m_checked_content_color_background").value = "true";
    } else {
      document.getElementById("p_m_checked_content_color_background").value = "false";
    }

  }




  point_maker_reset_type();

  point_maker_setup_type();

}


function point_maker_content_color_border() {

  var type = point_maker_get_type();
  if (type === 'heading_icon') {

    if (document.getElementById("p_m_content_color_border").checked) {
      document.getElementById("p_m_checked_content_color_border").value = "true";
      document.getElementById("p_m_title_color_border").checked = true;
      document.getElementById("p_m_checked_title_color_border").value = "true";
    } else {
      document.getElementById("p_m_checked_content_color_border").value = "false";
      document.getElementById("p_m_title_color_border").checked = false;
      document.getElementById("p_m_checked_title_color_border").value = "false";
    }

  } else {

    if (document.getElementById("p_m_content_color_border").checked) {
      document.getElementById("p_m_checked_content_color_border").value = "true";
    } else {
      document.getElementById("p_m_checked_content_color_border").value = "false";
    }
  }

  point_maker_reset_type();

  point_maker_setup_type();

}
