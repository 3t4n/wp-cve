<?php

function remove_menus()
{
    // get current login user's role
    $roles = wp_get_current_user()->roles;
    // test role
    if ( !in_array( 'event-manager', $roles ) ) {
        return;
    }
    remove_menu_page( 'edit.php' );
    //Posts
    remove_menu_page( 'upload.php' );
    //Media
    remove_menu_page( 'edit-comments.php' );
    //Comments
    remove_menu_page( 'tools.php' );
    //Tools
}

// Builds the CSS
function qem_generate_css()
{
    $style = qem_get_stored_style();
    $cal = qem_get_stored_calendar();
    $display = event_get_stored_display();
    $register = qem_get_stored_register();
    $register_style = qem_get_register_style();
    $color = $script = $showeventborder = $formborder = $daycolor = $eventbold = $colour = $eventitalic = '';
    if ( $style['calender_size'] == 'small' ) {
        $radius = 7;
    }
    if ( $style['calender_size'] == 'medium' ) {
        $radius = 10;
    }
    if ( $style['calender_size'] == 'large' ) {
        $radius = 15;
    }
    $size = 50 + 2 * $style['date_border_width'];
    $ssize = $size . 'px';
    $srm = $size + 5 + $style['date_border_width'];
    $srm = $srm . 'px';
    $size = 70 + 2 * $style['date_border_width'];
    $msize = $size . 'px';
    $mrm = $size + 5 + $style['date_border_width'];
    $mrm = $mrm . 'px';
    $size = 90 + 2 * $style['date_border_width'];
    $lsize = $size . 'px';
    $lrm = $size + 5 + $style['date_border_width'];
    $lrm = $lrm . 'px';
    if ( $style['date_background'] == 'color' ) {
        $color = $style['date_backgroundhex'];
    }
    if ( $style['date_background'] == 'grey' ) {
        $color = '#343838';
    }
    if ( $style['date_background'] == 'red' ) {
        $color = 'red';
    }
    
    if ( $style['month_background'] == 'colour' ) {
        $colour = $style['month_backgroundhex'];
    } else {
        $colour = '#FFF';
    }
    
    $eventbackground = '';
    if ( $style['event_background'] == 'bgwhite' ) {
        $eventbackground = 'background:white;';
    }
    if ( $style['event_background'] == 'bgcolor' ) {
        $eventbackground = 'background:' . $style['event_backgroundhex'] . ';';
    }
    $formwidth = preg_split( '#(?<=\\d)(?=[a-z%])#i', $register['formwidth'] );
    if ( !isset( $formwidth[0] ) ) {
        $formwidth[0] = '280';
    }
    if ( !isset( $formwidth[1] ) ) {
        $formwidth[1] = 'px';
    }
    $regwidth = $formwidth[0] . $formwidth[1];
    $dayborder = 'color:' . $style['date_colour'] . ';background:' . $color . '; border: ' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';border-bottom:none;';
    $nondayborder = 'border: ' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';border-top:none;background:' . $colour . ';';
    $monthcolor = 'span.month {color:' . $style['month_colour'] . ';}';
    $eventborder = 'border: ' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';';
    
    if ( $style['icon_corners'] == 'rounded' ) {
        $dayborder = $dayborder . '-webkit-border-top-left-radius:' . $radius . 'px; -moz-border-top-left-radius:' . $radius . 'px; border-top-left-radius:' . $radius . 'px; -webkit-border-top-right-radius:' . $radius . 'px; -moz-border-top-right-radius:' . $radius . 'px; border-top-right-radius:' . $radius . 'px;';
        $nondayborder = $nondayborder . '-webkit-border-bottom-left-radius:' . $radius . 'px; -moz-border-bottom-left-radius:' . $radius . 'px; border-bottom-left-radius:' . $radius . 'px; -webkit-border-bottom-right-radius:' . $radius . 'px; -moz-border-bottom-right-radius:' . $radius . 'px; border-bottom-right-radius:' . $radius . 'px;';
        $eventborder = $eventborder . '-webkit-border-radius:' . $radius . 'px; -moz-border-radius:' . $radius . 'px; border-radius:' . $radius . 'px;';
    }
    
    if ( $style['event_border'] ) {
        $showeventborder = 'padding:' . $radius . 'px;' . $eventborder;
    }
    if ( $register['formborder'] ) {
        $formborder = "\n.qem-register {" . $eventborder . "padding:" . $radius . "px;}";
    }
    
    if ( $style['widthtype'] == 'pixel' ) {
        $eventwidth = preg_replace( "/[^0-9]/", "", $style['width'] ) . 'px;';
    } else {
        $eventwidth = '100%';
    }
    
    $j = preg_split( '#(?<=\\d)(?=[a-z%])#i', $display['event_image_width'] );
    if ( !$j[0] ) {
        $j[0] = '300';
    }
    $i = $j[0] . 'px';
    if ( qem_get_element( $cal, 'eventbold', false ) ) {
        $eventbold = 'font-weight:bold;';
    }
    if ( qem_get_element( $cal, 'eventitalic', false ) ) {
        $eventitalic = 'font-style:italic;';
    }
    $ec = ( $cal['event_corner'] == 'square' ? 0 : 3 );
    $script .= '.qem {width:' . $eventwidth . ';' . $style['event_margin'] . ';}
.qem p {' . $style['line_margin'] . ';}
.qem p, .qem h2 {margin: 0 0 8px 0;padding:0;}' . "\n";
    if ( $style['font'] == 'plugin' ) {
        $script .= ".qem p {font-family: " . $style['font-family'] . "; font-size: " . $style['font-size'] . ";}\n.qem h2, .qem h2 a {font-size: " . $style['header-size'] . " !important;color:" . $style['header-colour'] . " !important}\n";
    }
    $script .= '@media only screen and (max-width:' . $cal['trigger'] . ') {.qemtrim span {font-size:50%;}
				.qemtrim, .calday, data-tooltip {font-size: ' . $cal['eventtextsize'] . ';}}';
    $arr = array(
        'arrow'   => '\\25B6',
        'square'  => '\\25A0',
        'box'     => '\\20DE',
        'asterix' => '\\2605',
        'blank'   => ' ',
    );
    foreach ( $arr as $item => $key ) {
        if ( $item == $cal['smallicon'] ) {
            $script .= '#qem-calendar-widget h2 {font-size: 1em;}
#qem-calendar-widget .qemtrim span {display:none;}
#qem-calendar-widget .qemtrim:after{content:"' . $key . '";font-size:150%;}
@media only screen and (max-width:' . $cal['trigger'] . ';) {.qemtrim span {display:none;}.qemtrim:after{content:"' . $key . '";font-size:150%;}}' . "\n";
        }
    }
    // missing items
    $eventgridborder = ( isset( $display['eventgridborder'] ) ? $display['eventgridborder'] : 'inherit' );
    $script .= '.qem-small, .qem-medium, .qem-large {' . $showeventborder . $eventbackground . '}' . $formborder . ".qem-register{max-width:" . $regwidth . ";}\n.qemright {max-width:" . $display['max_width'] . "%;width:" . $i . ";height:auto;overflow:hidden;}\n.qemlistright {max-width:" . $display['max_width'] . "%;width:" . $display['image_width'] . "px;height:auto;overflow:hidden;}\nimg.qem-image {width:100%;height:auto;overflow:hidden;}\nimg.qem-list-image {width:100%;height:auto;overflow:hidden;}\n.qem-category {" . $eventborder . "}\n.qem-icon .qem-calendar-small {width:" . $ssize . ";}\n.qem-small {margin-left:" . $srm . ";}\n.qem-icon .qem-calendar-medium {width:" . $msize . ";}\n.qem-medium {margin-left:" . $mrm . ";}\n.qem-icon .qem-calendar-large {width:" . $lsize . ";}\n.qem-large {margin-left:" . $lrm . ";}\n.qem-calendar-small .nonday, .qem-calendar-medium .nonday, .qem-calendar-large .nonday {display:block;" . $nondayborder . "}\n.qem-calendar-small .day, .qem-calendar-medium .day, .qem-calendar-large .day {display:block;" . $daycolor . $dayborder . "}\n.qem-calendar-small .month, .qem-calendar-medium .month, .qem-calendar-large .month {color:" . $style['month_colour'] . "}\n.qem-error { border-color: red !important; }\n.qem-error-header { color: red !important; }\n.qem-columns, .qem-masonry {border:" . $eventgridborder . ";}\n#qem-calendar " . $cal['header'] . " {margin: 0 0 8px 0;padding:0;" . $cal['headerstyle'] . "}\n#qem-calendar .calmonth {text-align:center;}\n#qem-calendar .calday {background:" . $cal['calday'] . "; color:" . $cal['caldaytext'] . "}\n#qem-calendar .day {background:" . $cal['day'] . ";}\n#qem-calendar .eventday {background:" . $cal['eventday'] . ";}\n#qem-calendar .eventday a {-webkit-border-radius:" . $ec . "px; -moz-border-radius:" . $ec . "px; border-radius:" . $ec . "px;color:" . $cal['eventtext'] . " !important;background:" . $cal['eventbackground'] . " !important;border:" . $cal['eventborder'] . " !important;}\n#qem-calendar .eventday a:hover {background:" . $cal['eventhover'] . " !important;}\n#qem-calendar .oldday {background:" . $cal['oldday'] . ";}\n#qem-calendar table {border-collapse: separate;border-spacing:" . $cal['cellspacing'] . "px;}\n.qemtrim span {" . $eventbold . $eventitalic . "}\n@media only screen and (max-width: 700px) {.qemtrim img {display:none;}}\n@media only screen and (max-width: 480px) {.qem-large, .qem-medium {margin-left: 50px;}\n    .qem-icon .qem-calendar-large, .qem-icon .qem-calendar-medium  {font-size: 80%;width: 40px;margin: 0 0 10px 0;padding: 0 0 2px 0;}\n    .qem-icon .qem-calendar-large .day, .qem-icon .qem-calendar-medium .day {padding: 2px 0;}\n    .qem-icon .qem-calendar-large .month, .qem-icon .qem-calendar-medium .month {font-size: 140%;padding: 2px 0;}\n}";
    if ( isset( $style['vanilla'] ) && $style['vanilla'] ) {
        $script .= '.qem h2, .qem h3 {display:block;}';
    }
    if ( $cal['tdborder'] ) {
        
        if ( $cal['cellspacing'] > 0 ) {
            $script .= '#qem-calendar td.day, #qem-calendar td.eventday, #qem-calendar td.calday {border: ' . $cal['tdborder'] . ';}';
        } else {
            $script .= '#qem-calendar td.day, #qem-calendar td.eventday, #qem-calendar td.calday {border-left:none;border-top:none;border-right: ' . $cal['tdborder'] . ';border-bottom: ' . $cal['tdborder'] . ';}
#qem-calendar tr td.day:first-child,#qem-calendar tr td.eventday:first-child,#qem-calendar tr td.calday:first-child{border-left: ' . $cal['tdborder'] . ';}' . "\n" . '
#qem-calendar tr td.calday{border-top: ' . $cal['tdborder'] . ';}
#qem-calendar tr td.blankday {border-bottom: ' . $cal['tdborder'] . ';}
#qem-calendar tr td.firstday {border-right: ' . $cal['tdborder'] . ';border-bottom: ' . $cal['tdborder'] . ';}';
        }
    
    }
    $lbmargin = (int) ((int) $display['lightboxwidth'] / 2);
    $script .= '#xlightbox {width:' . $display['lightboxwidth'] . '%;margin-left:-' . $lbmargin . '%;}
@media only screen and (max-width: 480px) {#xlightbox {width:90%;margin-left:-45%;}}';
    if ( $register['ontheright'] ) {
        $script .= '.qem-register {width:100%;} .qem-rightregister {max-width:' . $i . 'px;margin: 0px 0px 10px 0;}';
    }
    if ( $style['use_custom'] == 'checked' ) {
        $script .= $style['custom'];
    }
    $cat = array(
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j'
    );
    foreach ( $cat as $i ) {
        
        if ( $style['cat' . $i] ) {
            $eb = ( $cal['fixeventborder'] || $cal['eventborder'] == 'none' ? '' : 'border:1px solid ' . $style['cat' . $i . 'text'] . ' !important;' );
            $script .= "#qem-calendar a." . $style['cat' . $i] . " {background:" . $style['cat' . $i . 'back'] . " !important;color:" . $style['cat' . $i . 'text'] . " !important;" . $eb . "}";
            $script .= '.' . $style['cat' . $i] . ' .qem-small, .' . $style['cat' . $i] . ' .qem-medium, .' . $style['cat' . $i] . ' .qem-large {border-color:' . $style['cat' . $i . 'back'] . ';}.' . $style['cat' . $i] . ' .qem-calendar-small .day, .' . $style['cat' . $i] . ' .qem-calendar-medium .day, .' . $style['cat' . $i] . ' .qem-calendar-large .day, .' . $style['cat' . $i] . ' .qem-calendar-small .nonday, .' . $style['cat' . $i] . ' .qem-calendar-medium .nonday, .' . $style['cat' . $i] . ' .qem-calendar-large .nonday {border-color:' . $style['cat' . $i . 'back'] . ';}';
            if ( $style['date_background'] == 'category' ) {
                $script .= '.' . $style['cat' . $i] . ' .qem-calendar-small .day, .' . $style['cat' . $i] . ' .qem-calendar-medium .day, .' . $style['cat' . $i] . ' .qem-calendar-large .day {background:' . $style['cat' . $i . 'back'] . ';color:' . $style['cat' . $i . 'text'] . ';}';
            }
        }
    
    }
    $code = $header = $font = $submitfont = $fontoutput = $border = '';
    $headercolour = $corners = $input = $background = $submitwidth = $paragraph = $submitbutton = $submit = '';
    $register_style = qem_get_register_style();
    
    if ( !isset( $register_style['nostyling'] ) || !$register_style['nostyling'] ) {
        $code .= '.qem-register {text-align: left;margin: 10px 0 10px 0;padding: 0;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
.qem-register #none {border: 0px solid #FFF;padding: 0;}
.qem-register #plain {border: 1px solid #415063;padding: 10px;margin: 0;}
.qem-register #rounded {border: 1px solid #415063;padding: 10px;-moz-border-radius: 10px;-webkit-box-shadow: 10px;border-radius: 10px;}
.qem-register #shadow {border: 1px solid #415063;padding: 10px;margin: 0 10px 20px 0;-webkit-box-shadow: 5px 5px 5px #415063;-moz-box-shadow: 5px 5px 5px #415063;box-shadow: 5px 5px 5px #415063;}
.qem-register #roundshadow {border: 1px solid #415063;padding: 10px; margin: 0 10px 20px 0;-webkit-box-shadow: 5px 5px 5px #415063;-moz-box-shadow: 5px 5px 5px #415063;box-shadow: 5px 5px 5px #415063;-moz-border-radius: 10px;-webkit-box-shadow: 10px;border-radius: 10px;}
.qem-register form, .qem-register p {margin: 0;padding: 0;}
.qem-register input[type=text], .qem-register input[type=number], .qem-register textarea, .qem-register select, .qem-register #submit {margin: 5px 0 7px 0;padding: 4px;color: #465069;font-family: inherit;font-size: inherit;height:auto;border:1px solid #415063;width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
.qem-register input[type=text] .required, .qem-register input[type=number] .required, .qem-register textarea .required {border:1px solid green;}
.qem-register #submit {text-align: center;cursor: pointer;}
div.toggle-qem {color: #FFF;background: #343838;text-align: center;cursor: pointer;margin: 5px 0 7px 0;padding: 4px;font-family: inherit;font-size: inherit;height:auto;border:1px solid #415063;width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
div.toggle-qem a {background: #343838;text-align: center;cursor: pointer;color:#FFFFFF;}
div.toggle-qem a:link, div.toggle-qem a:visited, div.toggle-qem a:hover {color:#FFF;text-decoration:none !important;}';
        $hd = ( $register_style['header-type'] ? $register_style['header-type'] : 'h2' );
        if ( $register_style['header-colour'] ) {
            $headercolour = "color: " . $register_style['header-colour'] . ";";
        }
        $header = ".qem-register " . $hd . " {" . $headercolour . ";height:auto;}";
        // missing
        $font_colour = ( isset( $register_style['font-colour'] ) ? 'color:' . $register_style['font-colour'] . ';' : '' );
        $input = '.qem-register input[type=text], .qem-register input[type=number], .qem-register textarea, .qem-register select {' . $font_colour . 'border:' . $register_style['input-border'] . ';background:' . $register_style['inputbackground'] . ';line-height:normal;height:auto;margin: 2px 0 3px 0;padding: 6px;}';
        $required = '.qem-register input[type=text].required, .qem-register input[type=number].required, .qem-register textarea.required, .qem-register select.required {border:' . $register_style['input-required'] . '}';
        $focus = ".qem-register input:focus, .qem-register textarea:focus {background:" . $register_style['inputfocus'] . ";}";
        $text = ".qem-register p {" . $font_colour . "margin: 6px 0 !important;padding: 0 !important;}";
        $error = ".qem-register .error {.qem-error {color:" . $register_style['error-font-colour'] . " !important;border-color:" . $register_style['error-font-colour'] . " !important;}";
        if ( $register_style['border'] != 'none' ) {
            $border = ".qem-register #" . $register_style['border'] . " {border:" . $register_style['form-border'] . ";}";
        }
        if ( $register_style['background'] == 'white' ) {
            $background = ".qem-register div {background:#FFF;}";
        }
        if ( $register_style['background'] == 'color' ) {
            $background = ".qem-register div {background:" . $register_style['backgroundhex'] . ";}";
        }
        $formwidth = preg_split( '#(?<=\\d)(?=[a-z%])#i', $register_style['form-width'] );
        if ( !$formwidth[0] ) {
            $formwidth[0] = '280';
        }
        if ( !isset( $formwidth[1] ) || !$formwidth[1] ) {
            $formwidth[1] = 'px';
        }
        $width = $formwidth[0] . $formwidth[1];
        if ( $register_style['submitwidth'] == 'submitpercent' ) {
            $submitwidth = 'width:100%;';
        }
        if ( $register_style['submitwidth'] == 'submitrandom' ) {
            $submitwidth = 'width:auto;';
        }
        if ( $register_style['submitwidth'] == 'submitpixel' ) {
            $submitwidth = 'width:' . $style['submitwidthset'] . ';';
        }
        
        if ( $register_style['submitposition'] == 'submitleft' ) {
            $submitposition = 'float:left;';
        } else {
            $submitposition = 'float:right;';
        }
        
        $submit = "color:" . $register_style['submit-colour'] . ";background:" . $register_style['submit-background'] . ";border:" . $register_style['submit-border'] . $submitfont . ";font-size: inherit;";
        $submithover = "background:" . $register_style['submit-hover-background'] . ";";
        $submitbutton = ".qem-register #submit {" . $submitposition . $submitwidth . $submit . "}\n.qem-register #submit:hover {" . $submithover . "}";
        
        if ( $register_style['corners'] == 'round' ) {
            $corner = '5px';
        } else {
            $corner = '0';
        }
        
        $corners = ".qem-register  input[type=text], .qem-register  input[type=number], .qem-register textarea, .qem-register select, .qem-register #submit {border-radius:" . $corner . ";}\r\n";
        if ( $register_style['corners'] == 'theme' ) {
            $corners = '';
        }
        $code .= "\r\n.qem-register {max-width:100%;overflow:hidden;width:" . $width . ";}" . $submitbutton . "\r\n" . $border . "\r\n" . $corners . "\r\n" . $header . "\r\n" . $paragraph . "\r\n" . $input . "\r\n" . $focus . "\r\n" . $required . "\r\n" . $text . "\r\n" . $error . "\r\n" . $background . "\r\n";
    }
    
    return $script . $code;
}

function qem_head_ic()
{
    global  $qem_fs ;
    global  $post ;
    
    if ( is_singular( 'event' ) ) {
        $unixtime = get_post_meta( $post->ID, 'event_date', true );
        $date = date_i18n( "j M y", $unixtime );
        echo  '<meta property="og:locale" content="en_GB" />
<meta property="og:type" content="website" />
<meta property="og:title" content="' . esc_attr( $date ) . ' - ' . esc_attr( get_the_title() ) . '" />
<meta property="og:description" content="' . esc_attr( get_post_meta( $post->ID, 'event_desc', true ) ) . '" />
<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />
<meta property="og:site_name" content="WFTR" />
<meta property="og:image" content="' . esc_attr( get_post_meta( $post->ID, 'event_image', true ) ) . '" />' ;
    }
    
    // should  be changed to locaizescript or add inline script
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- this is core WP security function
    echo  '<script type="text/javascript">ajaxurl = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '"; qem_calendar_atts = []; qem_year = []; qem_month = []; qem_category = [];</script>' ;
}

function qem_flush_rules()
{
    event_register();
    flush_rewrite_rules();
}

function qem_add_custom_post_type_to_query( $query )
{
    if ( is_home() ) {
        $query->set( 'post_type', array( 'post', 'event' ) );
    }
}

/**
 * set default order of edit events page
 *
 * @param $query
 *
 * @return void
 */
function qem_admin_edit_table_order( $query )
{
    global  $post_type, $pagenow ;
    if ( 'edit.php' == $pagenow && 'event' == $post_type ) {
        
        if ( empty(get_query_var( 'order' )) && empty(get_query_var( 'orderby' )) ) {
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'desc' );
        }
    
    }
}
