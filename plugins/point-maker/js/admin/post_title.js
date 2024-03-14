function point_maker_change_title(){

  var title = document.getElementById('p_m_edit_title').value,
  p_m_title = document.getElementById('p_m_title'),
  type = point_maker_get_type(),
  title_icon = document.getElementById('p_m_selected_title_icon').value,
  p_m_change_title = document.getElementById('p_m_change_title');


  p_m_change_title.style.marginLeft = '';
  if( title_icon === 'blank-icon'){
    p_m_change_title.style.marginLeft = 0;
  }

  if( title === ''){
    p_m_change_title.style.marginLeft = 0;
    p_m_change_title.style.display = 'none';
  }else{
    p_m_change_title.style.display = '';
  }

  if( title_icon === 'blank-icon' &&  title === ''){
    p_m_title.style.display = 'none';
  }else{
    p_m_title.style.display = '';

  }

  if(type === 'simple_icon'){

  }else if(type === 'simple_tab'){

  }

  p_m_change_title.innerHTML = title;

}

function point_maker_change_title_icon(){

  point_maker_reset_type();

  point_maker_setup_type();

  point_maker_change_title();

}


function point_maker_title_color_background(){

  var type = point_maker_get_type();
  if( type === 'heading_icon'){

    if(document.getElementById("p_m_title_color_background").checked){
      document.getElementById("p_m_title_color_background").value = "true";
      document.getElementById("p_m_content_color_background").checked = true;
      document.getElementById("p_m_checked_content_color_background").value = "true";
    }else{
      document.getElementById("p_m_checked_title_color_background").value = "false";
      document.getElementById("p_m_content_color_background").checked = false;
      document.getElementById("p_m_checked_content_color_background").value = "false";
    }

  }else{
    if(document.getElementById("p_m_title_color_background").checked){
      document.getElementById("p_m_checked_title_color_background").value = "true";
    }else{
      document.getElementById("p_m_checked_title_color_background").value = "false";
    }
  }
  point_maker_reset_type();

  point_maker_setup_type();

}

function point_maker_title_color_border(){

  var type = point_maker_get_type();
  if( type === 'heading_icon'){

    if(document.getElementById("p_m_title_color_border").checked){
      document.getElementById("p_m_checked_title_color_border").value = "true";
      document.getElementById("p_m_content_color_border").checked = true;
      document.getElementById("p_m_checked_content_color_border").value = "true";
    }else{
      document.getElementById("p_m_checked_title_color_border").value = "false";
      document.getElementById("p_m_content_color_border").checked = false;
      document.getElementById("p_m_checked_content_color_border").value = "false";
    }

  }else{
    if(document.getElementById("p_m_title_color_border").checked){
      document.getElementById("p_m_checked_title_color_border").value = "true";
    }else{
      document.getElementById("p_m_checked_title_color_border").value = "false";
    }
  }

  point_maker_reset_type();

  point_maker_setup_type();

}
