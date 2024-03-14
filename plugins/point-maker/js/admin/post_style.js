







function point_maker_change_color() {

  var color_name = point_maker_get_select_option_value('p_m_edit_color'),
    p_m_edit_color = document.getElementById('p_m_edit_color');

  if (document.getElementById('p_m_selected_color'))
    document.getElementById('p_m_selected_color').value = color_name;
  BlackOrWhite = point_maker_BlackOrWhite(point_maker_colors[color_name]['base']);



  /*
    if( document.getElementById('p_m_checked_title_color_background').value === 'true' ){
      document.getElementById('p_m_edit_title_icon').style.fill = point_maker_colors[ color_name ]['dark'];
      document.getElementById('p_m_change_title').style.color = '';
    }else{
      document.getElementById('p_m_edit_title_icon').style.fill = BlackOrWhite;
      document.getElementById('p_m_change_title').style.color = BlackOrWhite;
    }
    */




  p_m_edit_color.style.borderColor = point_maker_colors[color_name]['base'];
  p_m_edit_color.style.background = point_maker_colors[color_name]['base'];
  p_m_edit_color.style.color = BlackOrWhite;

  point_maker_reset_type();

  point_maker_setup_type();

  point_maker_change_title();

  point_maker_change_content();

}







function point_maker_change_type() {

  var type = point_maker_get_type();

  if (type === 'heading_icon') {
    document.getElementById("p_m_title_color_border").checked = false;
    document.getElementById("p_m_checked_title_color_border").value = "false";
    document.getElementById("p_m_content_color_border").checked = false;
    document.getElementById("p_m_checked_content_color_border").value = "false";
    document.getElementById("p_m_title_color_background").checked = true;
    document.getElementById("p_m_checked_title_color_background").value = "true";
    document.getElementById("p_m_checked_content_color_background").value = "true";
    document.getElementById("p_m_content_color_background").checked = true;
  }

  point_maker_reset_type();

  point_maker_setup_type();

}
