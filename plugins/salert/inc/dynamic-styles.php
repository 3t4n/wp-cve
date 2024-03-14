<?php if ( ! defined( 'ABSPATH' ) ) die; // Cannot access pages directly.

//Js Styles
$salert_settings = get_option( 'salert_save_settings');

$popup_position = $salert_settings['popup-position'];
$box_shadow = $salert_settings['box-shadow'];
$popup_start = $salert_settings['popup-start-time'];
$popup_animation = $salert_settings['popup-animation'];
$popup_interval_from = $salert_settings['popup-time-interval-from'];
$popup_interval_to = $salert_settings['popup-time-interval-to'];
$popup_stay_time = $salert_settings['popup-stay-time'];

$js_info = array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'salert_popup_position' => $popup_position,
    'salert_popup_start_time' => $popup_start,
    'salert_popup_transition' => $popup_animation,
    'salert_popup_range_from' => $popup_interval_from,
    'salert_popup_range_to' => $popup_interval_to,
    'salert_popup_stay' => $popup_stay_time,
);
wp_localize_script( 'salert-main-js', 'salert_settings', $js_info );

//CSS Styles
$popup_imgposition = $salert_settings['popup-position'];
switch ($popup_imgposition) {
        case 'imageOnLeft':
            $imgFloat = 'left';
            break;
        case 'imageOnRight':
            $imgFloat = 'right';
            break;
        default:
            $imgFloat = '';
    }
$bg_color = $salert_settings['bg-color'];
$cont_width = $salert_settings['container-width'];
$inner_pad = $salert_settings['inner-padding'];
$text_color = $salert_settings['text-color'];
$font_size =  $salert_settings['font-size'];
$text_transform = $salert_settings['text-transform'];
$border_enable = $salert_settings['border-enable'];
$border_color = $salert_settings['border-color'];
$border_width = $salert_settings['border-width'];
$border_radius = $salert_settings['border-radius'];
$resp = $salert_settings['enable-resp'];
if($resp==1){
	$display = 'block';
}else{
	$display = 'none';
}
if($border_enable==1){
  $salert_border_css = "
              border:2px solid {$border_color};
              border-width:{$border_width}px;
              border-radius:{$border_radius}px;
          ";
}else{
  $salert_border_css = '';
}

$dynamic_styles = "#salertWrapper .popup_template{
                        background-color:{$bg_color};
                        {$salert_border_css}
                   }
                   #salertWrapper .popup_position{
                        width:{$cont_width}px;
                   }

                  
                  #salertWrapper .popup_position .salert-content-wrap{
                    color:{$text_color};
                    font-size:{$font_size}px;
                    text-transform:{$text_transform};
                  }
                  #salertWrapper .popup_position img{
                    float: {$imgFloat};
                  }
                  #salertWrapper .popup-item{
    				padding:{$inner_pad}px;
  			      }
			      @media (max-width: 767px){ 
                     #salertWrapper { display: {$display} !important;} 
                  }
                   "; 

if( $box_shadow == 1 ){
  $dynamic_styles .= "
    #salertWrapper .popup_template {
        -webkit-box-shadow: 3px 5px 10px 1px rgba(0, 0, 0, 0.38);
        box-shadow: 3px 5px 10px 1px rgba(0, 0, 0, 0.38);
    }
  ";
}

wp_add_inline_style( 'salert-main-css', $dynamic_styles ); 


