function point_maker_get_select_option_value(id){
	var e = document.getElementById(id);

	
	if( e && typeof e.options[e.selectedIndex] !== 'undefined'){
		return e.options[e.selectedIndex].value;
	}
	console.log('No item');
	return;
}

function point_maker_get_radio_value(name){
	var e = document.getElementsByName( name ) ;

	for ( var radio = '' , i = e.length; i--; ) {
		if ( e[i].checked ) {
			radio = e[i].value ;
			break ;
		}
	}

	return radio;
}

function point_maker_get_type() {
	return point_maker_get_select_option_value('p_m_edit_type');
}

function point_maker_BlackOrWhite ( hexcolor ) {
	var r = parseInt( hexcolor.substr( 1, 2 ), 16 ) ;
	var g = parseInt( hexcolor.substr( 3, 2 ), 16 ) ;
	var b = parseInt( hexcolor.substr( 5, 2 ), 16 ) ;

	return ( ( (r * 0.299) + (g * 0.587) + (b * 0.114) ) > 186 ) ? "#000000" : "#ffffff" ;
}



function point_maker_reset_type(){

	var p_m_content = document.getElementById( 'p_m_content' ),
	p_m_title = document.getElementById( 'p_m_title' ),
	p_m_wrap = document.getElementById( 'p_m_wrap' ),
	p_m_edit_title_icon = document.getElementById( 'p_m_edit_title_icon' ),
	p_m_change_title = document.getElementById( 'p_m_change_title' ),
	p_m_change_detail = document.getElementById('p_m_change_detail'),
	p_m_just_title_before_icon = document.getElementById("p_m_just_title_before_icon"),
	p_m_end_of_content = document.getElementById("p_m_end_of_content");

	for(var i in point_maker_type){
		p_m_wrap.classList.remove( 'p_m_'+i );
	}

	p_m_content.style.background = '';
	p_m_content.style.border = '';
	p_m_content.style.borderColor = 'transparent';
	p_m_content.style.display = '';
	p_m_title.style.background = '';
	p_m_title.style.border = '';
	p_m_title.style.borderBottomStyle = '';
	p_m_title.style.borderColor = 'transparent';
	p_m_title.style.position = '';
	p_m_title.style.margin = '0';
	p_m_title.style.padding = '';
	p_m_edit_title_icon.style.display = '';
	p_m_change_title.style.display = '';
	p_m_change_title.style.margin = '0';
	p_m_change_title.style.padding = '';
	p_m_change_detail.style.borderTop = '';
	p_m_change_detail.style.background = '';
	p_m_change_detail.style.border = '';
	p_m_change_detail.style.borderBottomStyle = '';
	p_m_change_detail.style.borderColor = 'transparent';
	p_m_end_of_content.style.background = '';
	p_m_end_of_content.style.border = '';
	p_m_end_of_content.style.borderColor = 'transparent';
	p_m_end_of_content.style.display = 'none';

	p_m_edit_title_icon.style.width = '22px';
	p_m_edit_title_icon.style.height = '22px';

	p_m_just_title_before_icon.style.background = '';
	p_m_just_title_before_icon.style.borderColor = 'transparent';
	p_m_just_title_before_icon.style.alignItems = '';

	p_m_title.insertBefore(p_m_edit_title_icon, p_m_change_title);
	p_m_content.insertBefore(p_m_change_detail, p_m_end_of_content);
	p_m_content.insertBefore(p_m_title, p_m_change_detail);

	return;

}

function point_maker_setup_type(){

	var p_m_content = document.getElementById( 'p_m_content' ),
	p_m_title = document.getElementById( 'p_m_title' ),
	p_m_edit_title = document.getElementById( 'p_m_edit_title' ),
	title_icon = document.getElementById('p_m_selected_title_icon').value,
	color = point_maker_colors[ document.getElementById('p_m_selected_color').value ],
	type = point_maker_get_type(),
	p_m_wrap = document.getElementById( 'p_m_wrap' ),
	p_m_edit_title_icon = document.getElementById( 'p_m_edit_title_icon' ),
	p_m_change_title = document.getElementById('p_m_change_title'),
	BlackOrWhite_light = point_maker_BlackOrWhite ( color['lighter'] ),
	BlackOrWhite_dark = point_maker_BlackOrWhite ( color['dark'] ),
	p_m_change_detail = document.getElementById('p_m_change_detail'),
	p_m_title_color_border = document.getElementById("p_m_title_color_border"),
	p_m_content_color_border = document.getElementById("p_m_content_color_border"),
	p_m_just_title_before_icon = document.getElementById("p_m_just_title_before_icon"),
	p_m_end_of_content = document.getElementById("p_m_end_of_content");

	p_m_wrap.classList.add( 'p_m_'+type );

	if( type === 'simple_icon'){


		if(p_m_title_color_border.checked){
			p_m_title.style.borderColor = color['dark'];
		}
		if(p_m_content_color_border.checked){
			p_m_content.style.borderColor = color['dark'];
		}


		if(document.getElementById("p_m_title_color_background").checked){

			p_m_title.style.background = color['lighter'];
			p_m_change_title.style.color = BlackOrWhite_light;
			p_m_edit_title_icon.style.fill = color['base'];

		}else{

			p_m_title.style.background = '';
			p_m_change_title.style.color = '';
			p_m_edit_title_icon.style.fill = color['base'];

		}

		if(document.getElementById("p_m_content_color_background").checked){

			p_m_content.style.background = color['lighter'];
			p_m_change_detail.style.color = BlackOrWhite_light;

		}else{

			p_m_content.style.background = '';
			p_m_change_detail.style.color = '';

		}

		if(p_m_edit_title.value !== '')p_m_change_title.style.margin = '';

	}else if( type === 'simple_tab'){

		if(p_m_title_color_border.checked){
			p_m_title.style.borderColor = color['base'];
		}
		if(p_m_content_color_border.checked){
			p_m_content.style.borderColor = color['base'];
		}

		p_m_title.style.borderBottomStyle = 'none';

		if(document.getElementById("p_m_title_color_background").checked){

			if(document.getElementById("p_m_content_color_background").checked){

				p_m_title.style.background = color['dark'];
				p_m_change_title.style.color = BlackOrWhite_dark;

				if(p_m_title_color_border.checked){
					p_m_title.style.borderColor = color['dark'];
				}
				if(p_m_content_color_border.checked){
					p_m_content.style.borderColor = color['dark'];
				}

				p_m_edit_title_icon.style.fill = color['lighter'];


			}else{

				p_m_title.style.background = color['lighter'];
				p_m_change_title.style.color = BlackOrWhite_light;

				p_m_edit_title_icon.style.fill = color['dark'];

			}

		}else{

			p_m_title.style.background = '';
			p_m_change_title.style.color = '';
			p_m_edit_title_icon.style.fill = color['base'];

		}

		if(document.getElementById("p_m_content_color_background").checked){

			p_m_content.style.background = color['lighter'];
			p_m_change_detail.style.color = BlackOrWhite_light;

		}else{

			p_m_content.style.background = '';
			p_m_change_detail.style.color = '';

		}

		if(p_m_edit_title.value !== '')p_m_change_title.style.margin = '';

	}else if( type === 'simple_box'){
		p_m_title.style.position = 'relative';


		if(p_m_title_color_border.checked){
			p_m_title.style.borderColor = color['base'];
		}
		if(p_m_content_color_border.checked){
			p_m_change_detail.style.borderColor = color['base'];
		}

		if(p_m_title_color_border.checked && p_m_content_color_border.checked){
			p_m_title.style.borderColor = 'transparent';
			p_m_change_detail.style.borderColor = 'transparent';
			p_m_content.style.borderColor = color['base'];
		}

		if(document.getElementById("p_m_title_color_background").checked){

			if(document.getElementById("p_m_content_color_background").checked){

				p_m_title.style.background = color['dark'];
				p_m_change_title.style.color = BlackOrWhite_dark;
				if(BlackOrWhite_dark === '#000000'){
					p_m_edit_title_icon.style.fill = '#ffffff';
				}else{
					p_m_edit_title_icon.style.fill = color['lighter'];
				}

			}else{

				p_m_title.style.background = color['lighter'];
				p_m_change_title.style.color = BlackOrWhite_light;
				if(BlackOrWhite_light === '#000000'){
					p_m_edit_title_icon.style.fill = color['dark'];
				}else{
					p_m_edit_title_icon.style.fill = '#ffffff';
				}

			}


		}else{

			p_m_title.style.background = '';
			p_m_change_title.style.color = '';
            //p_m_title.style.borderColor = color['lighter'];
            p_m_edit_title_icon.style.fill = color['dark'];

        }

        if(document.getElementById("p_m_content_color_background").checked){

        	p_m_change_detail.style.background = color['lighter'];
        	p_m_change_detail.style.color = BlackOrWhite_light;

        }else{

        	p_m_change_detail.style.background = '';
        	p_m_change_detail.style.color = '';

        }

        if(p_m_edit_title.value !== '')p_m_change_title.style.margin = '';

    }else if( type === 'heading_icon'){

    	p_m_just_title_before_icon.style.display = '';
    	p_m_title.style.position = 'relative';

    	p_m_just_title_before_icon.insertBefore(p_m_edit_title_icon, null);

    	p_m_edit_title_icon.style.width = '36px';
    	p_m_edit_title_icon.style.height = '36px';

    	p_m_title.style.margin = '16px 0';
    	p_m_title.style.padding = '0';


    	if(p_m_title_color_border.checked){
    		p_m_just_title_before_icon.style.borderColor = color['dark'];
    	}
    	if(p_m_content_color_border.checked){
    		p_m_content.style.borderColor = color['dark'];
    	}

    	if(document.getElementById("p_m_title_color_background").checked){
    		p_m_just_title_before_icon.style.backgroundColor = color['lighter'];
    		p_m_edit_title_icon.style.fill = color['dark'];
    	}else{
    		p_m_just_title_before_icon.style.backgroundColor = '';
    		p_m_edit_title_icon.style.fill = color['dark'];
    	}

    	if(document.getElementById("p_m_content_color_background").checked){
    		p_m_content.style.backgroundColor = color['lighter'];
    		p_m_change_detail.style.color = BlackOrWhite_light;
    		p_m_change_title.style.color = color['dark'];
    	}else{
    		p_m_content.style.backgroundColor = '';
    		p_m_change_detail.style.color = '';
    		p_m_change_title.style.color = color['dark'];
    	}

    }else if( type === 'side_icon'){

    	p_m_just_title_before_icon.insertBefore(p_m_edit_title_icon, null);
    	p_m_end_of_content.insertBefore(p_m_change_detail, null);
    	p_m_end_of_content.insertBefore(p_m_title, p_m_change_detail);
    	p_m_just_title_before_icon.style.display = '';
    	p_m_content.style.display = 'flex'
    	p_m_end_of_content.style.display = '';
    	p_m_title.style.position = 'relative';
    	p_m_just_title_before_icon.style.alignItems = 'center';

    	if(document.getElementById("p_m_title_color_background").checked){
    		p_m_just_title_before_icon.style.backgroundColor = color['base'];
    		p_m_edit_title_icon.style.fill = color['darker'];
    	}else{
    		p_m_just_title_before_icon.style.backgroundColor = '';
    		p_m_edit_title_icon.style.fill = color['base'];
    	}

    	if(document.getElementById("p_m_content_color_background").checked){
    		p_m_end_of_content.style.backgroundColor = color['lighter'];
    		p_m_change_detail.style.color = BlackOrWhite_light;
    		p_m_change_title.style.color = color['dark'];
    	}else{
    		p_m_end_of_content.style.backgroundColor = '';
    		p_m_change_detail.style.color = '';
    		p_m_change_title.style.color = color['dark'];;
    	}

    	if(p_m_title_color_border.checked){
    		p_m_just_title_before_icon.style.borderColor = color['dark'];
    	}
    	if(p_m_content_color_border.checked){
    		p_m_end_of_content.style.borderColor = color['dark'];
    	}
    	if(p_m_title_color_border.checked && p_m_content_color_border.checked){
    		p_m_just_title_before_icon.style.borderColor = 'transparent';
    		p_m_end_of_content.style.borderColor = 'transparent';
    		p_m_content.style.borderColor = color['dark'];
    	}

    }


    if( 'blank-icon' === title_icon){
    	p_m_edit_title_icon.style.display = 'none';
    }

    return;
}