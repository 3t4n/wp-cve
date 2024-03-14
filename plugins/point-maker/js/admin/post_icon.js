function point_maker_open_icon_list(open){

  document.getElementById( 'p_m_now_open_icon' ).value = open;

}


function point_maker_select_icon(icon){

  var open_list = document.getElementById( 'p_m_now_open_icon' ).value;

  if( 'p_m_select_title_icon' === open_list){

    var svg = document.getElementById( 'p_m_select_title_icon' ).querySelectorAll('svg');
    document.getElementById( 'p_m_selected_title_icon' ).value = icon;

    var p_m_title_svg = document.getElementById( 'p_m_edit_title_icon' );
    p_m_title_svg.innerHTML = point_maker_icons[icon];

    point_maker_change_title_icon();

  }else if( 'p_m_select_list_icon' === open_list){

    var svg = document.getElementById( 'p_m_select_list_icon' ).querySelectorAll('svg');
    document.getElementById( 'p_m_selected_list_icon' ).value = icon;

    point_maker_change_content();

  }

  svg[0].innerHTML = point_maker_icons[icon];


}