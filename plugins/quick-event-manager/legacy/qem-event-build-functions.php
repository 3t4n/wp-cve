<?php

/**
 * build the event page escaped safe for output with svg icons
 *
 *
 * @param $atts
 * e.g.
 * array (
 * 'links' => 'off',
 * 'size' => '',
 * 'headersize' => '',
 * 'settings' => 'checked',
 * 'fullevent' => 'fullevent',
 * 'images' => '',
 * 'fields' => '',
 * 'widget' => '',
 * 'cb' => '',
 * 'vanillawidget' => '',
 * 'linkpopup' => '',
 * 'thisday' => '',
 * 'popup' => '',
 * 'vw' => '',
 * 'categoryplaces' => '',
 * 'fulllist' => '',
 * 'thisisapopup' => '',
 * 'listplaces' => true,
 * 'calendar' => false,
 * 'fullpopup' => false,
 * 'grid' => '',
 * )
 *
 * @return string
 */
function qem_event_construct_esc( $atts )
{
    global  $post ;
    $event = event_get_stored_options();
    $display = event_get_stored_display();
    $vertical = ( isset( $display['vertical'] ) ? $display['vertical'] : '' );
    $style = qem_get_stored_style();
    $cal = qem_get_stored_calendar();
    $custom = get_post_custom();
    $link = get_post_meta( $post->ID, 'event_link', true );
    $endtime = get_post_meta( $post->ID, 'event_end_time', true );
    $endmonth = $amalgamated = $target = '';
    $unixtime = get_post_meta( $post->ID, 'event_date', true );
    $day = date_i18n( "d", $unixtime );
    $enddate = get_post_meta( $post->ID, 'event_end_date', true );
    $image = get_post_meta( $post->ID, 'event_image', true );
    $readmore = get_post_meta( $post->ID, 'event_readmore', true );
    if ( strtolower( $atts['links'] ) == 'off' ) {
        $display['titlelink'] = $display['readmorelink'] = true;
    }
    $display['read_more'] = ( $readmore ? $readmore : $display['read_more'] );
    if ( $image ) {
        $image = 'src="' . $image . '">';
    }
    $usefeatured = false;
    
    if ( has_post_thumbnail( $post->ID ) && $display['usefeatured'] ) {
        $image = get_the_post_thumbnail( null, 'large' );
        $usefeatured = true;
    }
    
    $register = qem_get_stored_register();
    $payment = qem_get_stored_payment();
    $atts['usereg'] = get_post_meta( $post->ID, 'event_register', true );
    $rightcontent = $notfullpop = $hideattendance = '';
    $today = strtotime( date( 'Y-m-d' ) );
    $grid = $cat = $clear = $eventfull = $cutoff = $fullcontent = $titlecat = $datecat = $linkopen = $linkclose = $regform = $nomap = '';
    
    if ( !is_singular( 'event' ) && !qem_get_element( $atts, 'widget', false ) && (qem_get_element( $display, 'eventgrid', false ) || $atts['grid']) ) {
        $grid = '-columns';
        if ( $atts['grid'] == 'masonry' ) {
            $grid = '-masonry';
        }
        $display['map_in_list'] = '';
        $style['vanilla'] = 'checked';
        $nomap = true;
    }
    
    if ( is_singular( 'event' ) ) {
        $display['show_end_date'] = true;
    }
    if ( isset( $display['fullevent'] ) && $display['fullevent'] ) {
        $atts['popup'] = false;
    }
    
    if ( isset( $display['loginlinks'] ) && $display['loginlinks'] && !is_user_logged_in() || is_singular( 'event' ) || qem_get_element( $atts, 'fulllist', false ) ) {
        $display['titlelink'] = true;
        $display['readmorelink'] = true;
    }
    
    
    if ( qem_get_element( $atts, 'widget', false ) && !$atts['links'] ) {
        $display['titlelink'] = true;
        $display['readmorelink'] = true;
    }
    
    // Build Category Information
    $category = get_the_category();
    $cat = ( $category && (!qem_get_element( $atts, 'widget', false ) && qem_get_element( $display, 'cat_border', false ) || $atts['cb']) ? ' ' . $category[0]->slug : ' ' );
    
    if ( isset( $display['showcategoryintitle'] ) && $display['showcategoryintitle'] ) {
        if ( $display['categorylocation'] == 'title' ) {
            $titlecat = ' - ' . $category[0]->name;
        }
        if ( $display['categorylocation'] == 'date' ) {
            $datecat = ' - ' . $category[0]->name;
        }
    }
    
    if ( qem_get_element( $atts, 'categoryplaces', false ) && $category[0]->slug == qem_get_element( $atts, 'categoryplaces', false ) ) {
        $event['summary']['field11'] = 'checked';
    }
    // Hide form for old events
    if ( $today > $unixtime && isset( $register['notarchive'] ) && $register['notarchive'] ) {
        $atts['usereg'] = '';
    }
    // No images
    if ( qem_get_element( $atts, 'images', 'on' ) == 'off' ) {
        $image = '';
    }
    // Clear Icon styling from widget
    if ( qem_get_element( $atts, 'vw', false ) || qem_get_element( $atts, 'vanillawidget', false ) ) {
        $style['vanillawidget'] = 'checked';
    }
    // Field override
    
    if ( qem_get_element( $atts, 'fields', false ) ) {
        foreach ( explode( ',', $event['sort'] ) as $name ) {
            $event['summary'][$name] = '';
        }
        $derek = explode( ',', $atts['fields'] );
        $event['sort'] = '';
        foreach ( $derek as $item ) {
            $event['summary']['field' . $item] = 'checked';
            $event['sort'] = $event['sort'] . 'field' . $item . ',';
        }
    }
    
    // Link externally
    if ( isset( $display['external_link'] ) && $display['external_link'] && $link ) {
        add_filter(
            'post_type_link',
            'qem_external_permalink',
            10,
            2
        );
    }
    // Build pop up
    
    if ( $atts['popup'] && !$display['fullevent'] ) {
        $popupcontent = get_event_popup( $atts );
        // $popupcontent .= qem_loop_esc();
    }
    
    // Combine end date
    
    if ( qem_get_element( $display, 'show_end_date', false ) && $enddate || $enddate && qem_get_element( $atts, 'eventfull' ) ) {
        $join = 'checked';
    } else {
        $join = '';
    }
    
    // Set size of icon
    
    if ( qem_get_element( $display, 'false', false ) ) {
        $width = '-' . $atts['size'];
    } else {
        $atts['size'] = $style['calender_size'];
        $width = '-' . $style['calender_size'];
    }
    
    // Set header size
    $h = ( qem_get_element( $atts, 'headersize', false ) == 'headthree' ? 'h3' : 'h2' );
    // Build title link
    if ( qem_get_element( $display, 'catalinkslug', false ) ) {
        $category = explode( ',', $display['catalinkslug'] );
    }
    
    if ( !qem_get_element( $display, 'titlelink', false ) ) {
        $linkclose = '</a>';
        
        if ( $atts['popup'] ) {
            $linkopen = '<a class="qem_linkpopup" data-xlightbox="' . $popupcontent . '"  >';
        } else {
            
            if ( $category && in_category( $category ) ) {
                $linkopen = '<a href="' . qem_get_element( $display, 'catalinkurl', false ) . '"' . $target . '>';
            } else {
                $linkopen = '<a href="' . get_permalink() . '"' . $target . '>';
            }
        
        }
    
    }
    
    // Test for date amalgamation
    
    if ( isset( $display['amalgamated'] ) && $display['amalgamated'] ) {
        $month = date_i18n( "M", $unixtime );
        $year = date_i18n( "Y", $unixtime );
        
        if ( $enddate ) {
            $endmonth = date_i18n( "M", $enddate );
            $endday = date_i18n( "d", $enddate );
            $endyear = date_i18n( "Y", $enddate );
        }
        
        if ( $month == $endmonth && $year == $endyear && $endday ) {
            $amalgamated = 'checked';
        }
    }
    
    // Start Content creation
    $content = '<div class="qem' . $grid . $cat . '">';
    // Build data icon
    
    if ( !qem_get_element( $style, 'vanilla', false ) && !$style['vanillawidget'] || !qem_get_element( $style, 'vanilla', false ) && $style['vanillawidget'] && !$atts['widget'] ) {
        $content .= '<div class="qem-icon">' . get_event_calendar_icon(
            $atts,
            'event_date',
            $join,
            $style
        );
        if ( $join && !$amalgamated && !$vertical ) {
            $content .= '</div><div class="qem-icon">';
        }
        if ( (qem_get_element( $display, 'show_end_date', false ) || qem_get_element( $atts, 'eventfull', false ) || is_singular( 'event' )) && !$amalgamated ) {
            $content .= get_event_calendar_icon(
                $atts,
                'event_end_date',
                '',
                $style
            );
        }
        $content .= '</div>';
        $content .= '<div class="qem' . $width . '">';
        $clear = '<div style="clear:both"></div></div>';
    }
    
    // Add image
    
    if ( $image ) {
        $imageclass = ( $atts['grid'] ? 'qem-grid-image' : 'qem-list-image' );
        if ( $display['event_image'] && !is_singular( 'event' ) && !$atts['widget'] ) {
            $rightcontent = $linkopen . '<img class="' . $imageclass . '" ' . $image . $linkclose . '<br>';
        }
        if ( $atts['fullevent'] == 'fullevent' || $atts['thisisapopup'] || $atts['fulllist'] ) {
            $rightcontent = '<img class="qem-image" ' . $image . '<br>';
        }
        if ( $usefeatured ) {
            $rightcontent = $image;
        }
    }
    
    // Add map
    $map_in_list = qem_get_element( $display, 'map_in_list', false );
    
    if ( !$nomap && function_exists( 'file_get_contents' ) && ($atts['fullevent'] && $atts['thisisapopup'] || $atts['fulllist'] || $map_in_list || $display['map_and_image'] && $map_in_list || is_singular( 'event' ) && !$atts['widget']) ) {
        $mapwidth = '300';
        if ( is_singular( 'event' ) ) {
            $mapwidth = $display['event_image_width'];
        }
        if ( $map_in_list && !is_singular( 'event' ) ) {
            $mapwidth = $display['image_width'];
        }
        $j = preg_split( '#(?<=\\d)(?=[a-z%])#i', $mapwidth );
        if ( !$j[0] ) {
            $j[0] = '300';
        }
        $mapwidth = $j[0];
        $rightcontent .= get_event_map( $mapwidth );
    }
    
    // Add form (if on the right)
    
    if ( !qem_get_element( $atts, 'widget', false ) && (qem_get_element( $atts, 'fulllist', false ) || is_singular( 'event' )) && !qem_get_element( $atts, 'thisisapopup', false ) && ($event['active_buttons']['field12'] && qem_get_element( $atts, 'usereg', false ) && $register['ontheright']) ) {
        $rightcontent .= '<div class="qem-rightregister">' . qem_loop_esc() . '</div>';
        $thereisaform = true;
    }
    
    // Build right content
    $gridclass = ( $atts['grid'] ? 'qemgridright' : 'qemlistright' );
    if ( $rightcontent ) {
        
        if ( is_singular( 'event' ) || $atts['thisisapopup'] || $atts['fulllist'] ) {
            $content .= '<div class="qemright">' . "\r\n" . $rightcontent . "\r\n" . '</div>' . "\r\n";
        } else {
            $content .= '<div class="' . $gridclass . '">' . "\r\n" . $rightcontent . "\r\n" . '</div>' . "\r\n";
        }
    
    }
    // Build event title link
    if ( (!is_singular( 'event' ) || qem_get_element( $atts, 'widget', false )) && !qem_get_element( $style, 'vanillaontop', false ) ) {
        $content .= '<' . $h . ' class="qem_title">' . $linkopen . $post->post_title . $titlecat . $linkclose . '</' . $h . '>';
    }
    // Build vanilla date
    
    if ( qem_get_element( $style, 'vanilla', false ) || $style['vanillawidget'] && qem_get_element( $atts, 'widget', false ) ) {
        $content .= '<h3 class="qem_date">' . get_event_calendar_icon(
            $atts,
            'event_date',
            $join,
            $style
        );
        if ( ($display['show_end_date'] || qem_get_element( $atts, 'eventfull', false )) && !$amalgamated ) {
            $content .= get_event_calendar_icon(
                $atts,
                'event_end_date',
                '',
                $style
            );
        }
        $content .= $datecat . '</h3>';
    }
    
    // Put title below vanilla date
    if ( (!is_singular( 'event' ) || qem_get_element( $atts, 'widget', false )) && qem_get_element( $style, 'vanillaontop', false ) ) {
        $content .= '<' . $h . ' class="qem_title">' . $linkopen . $post->post_title . $titlecat . $linkclose . '</' . $h . '>';
    }
    // Build event content
    if ( qem_get_element( $atts, 'calendar', false ) && !qem_get_element( $atts, 'fullpopup', false ) ) {
        $notfullpop = 'checked';
    }
    if ( qem_get_element( $atts, 'listplaces', false ) && !qem_get_element( $atts, 'fullpopup', false ) ) {
        $notfullpop = 'checked';
    }
    
    if ( is_singular( 'event' ) && !qem_get_element( $atts, 'widget', false ) || qem_get_element( $atts, 'thisisapopup', false ) && !$notfullpop || qem_get_element( $atts, 'fulllist', false ) ) {
        foreach ( explode( ',', $event['sort'] ) as $name ) {
            if ( isset( $event['active_buttons'][$name] ) && $event['active_buttons'][$name] ) {
                $content .= qem_build_event(
                    $name,
                    $event,
                    $display,
                    $custom,
                    $atts,
                    $register,
                    $payment
                );
            }
        }
    } else {
        foreach ( explode( ',', $event['sort'] ) as $name ) {
            if ( qem_get_element( $event['summary'], $name, false ) ) {
                $content .= qem_build_event(
                    $name,
                    $event,
                    $display,
                    $custom,
                    $atts,
                    $register,
                    $payment
                );
            }
        }
    }
    
    // Add ICS button to list and event
    if ( $display['uselistics'] && !is_singular( 'event' ) || $display['useics'] && !qem_get_element( $atts, 'widget', false ) && (is_singular( 'event' ) || qem_get_element( $atts, 'fulllist', false )) ) {
        $content .= '<p>' . qem_ics_button( $post->ID, $display['useicsbutton'] ) . '</p>';
    }
    // Add Read More
    
    if ( !is_singular( 'event' ) && !qem_get_element( $atts, 'widget', false ) || qem_get_element( $atts, 'widget', false ) && qem_get_element( $atts, 'links', false ) ) {
        $event_number_max = get_post_meta( $post->ID, 'event_number', true );
        $num = qem_number_places_available( $post->ID );
        $cutoffdate = get_post_meta( $post->ID, 'event_cutoff_date', true );
        if ( qem_get_element( $atts, 'usereg', false ) ) {
            $regform = true;
        }
        $gotform = true;
        if ( !$regform && qem_get_element( $atts, 'thisisapopup', false ) && qem_get_element( $atts, 'fullpopup', false ) ) {
            $gotform = false;
        }
        if ( $cutoffdate && $cutoffdate < time() ) {
            $cutoff = 'checked';
        }
        if ( '' !== $event_number_max && (int) $event_number_max > 0 && (int) $num == 0 && !$register['waitinglist'] || $cutoff ) {
            $eventfull = true;
        }
        if ( qem_get_element( $atts, 'thisisapopup', false ) && qem_get_element( $atts, 'fullpopup', false ) && $regform && !$eventfull ) {
            $display['read_more'] = $register['title'];
        }
        
        if ( qem_get_element( $display, 'fullevent', false ) || qem_get_element( $atts, 'widget', false ) || (qem_get_element( $atts, 'fullevent', false ) == 'summary' || !$eventfull) && !qem_get_element( $display, 'readmorelink', false ) && $gotform ) {
            
            if ( qem_get_element( $atts, 'popup', false ) ) {
                $readmoreopen = '<a href="javascript:xlightbox(\'' . $popupcontent . '\'); ">';
            } else {
                $readmoreopen = '<a href="' . get_permalink() . '"' . $target . '>';
            }
            
            $content .= '<p class="readmore">' . $readmoreopen . qem_get_element( $display, 'read_more' ) . '</a></p>';
        }
    
    }
    
    // Add back to list link
    if ( !qem_get_element( $atts, 'widget', false ) ) {
        if ( isset( $display['back_to_list'] ) && $display['back_to_list'] && is_singular( 'event' ) ) {
            
            if ( $display['back_to_url'] ) {
                $content .= '<p class="qemback"><a href="' . $display['back_to_url'] . '">' . $display['back_to_list_caption'] . '</a></p>';
            } else {
                $content .= '<p class="qemback"><a href="javascript:history.go(-1)">' . $display['back_to_list_caption'] . '</a></p>';
            }
        
        }
    }
    // $content .= '<br>Full popup: '.$atts['fullpopup'].'<br>Widget: '.$atts['widget'].'<br>Is Popup: '.$atts['thisisapopup'].'<br>Not full pop: '.$notfullpop.'<br>Popup: '.$atts['popup'].'<br>Full Event:'.$atts['fullevent'].'<br>Event Full: '.$eventfull.'<br>Full List:'.$atts['fulllist'].'<br>Links: '.$atts['links'].'<br>Link List: '.$atts['listlink'].'<br>Reg form:'.$regform.'<br>Got Form: '.$gotform.'<br>Read More: '.$display['read_more'].'<br>';
    $content .= $clear . "</div>";
    return qem_kses_post_svg_form( $content );
}

// Builds the Calendar Icon
function get_event_calendar_icon(
    $atts,
    $dateicon,
    $join,
    $style
)
{
    global  $post ;
    $width = $atts['size'];
    $vw = qem_get_element( $atts, 'vw', false );
    $widget = qem_get_element( $atts, 'widget', false );
    $display = event_get_stored_display();
    $vertical = ( isset( $display['vertical'] ) ? $display['vertical'] : '' );
    $mrcombi = '2' * $style['date_border_width'] . 'px';
    $mr = '5' + $style['date_border_width'] . 'px';
    $mb = ( $vertical ? ' 8px' : ' 0' );
    $sep = $bor = $boldon = $italicon = $month = $italicoff = $boldoff = $endname = $amalgum = $bar = '';
    $tl = 'border-top-left-radius:0;';
    $tr = 'border-top-right-radius:0;';
    $bl = 'border-bottom-left-radius:0';
    $br = 'border-bottom-right-radius:0';
    if ( $dateicon == 'event_date' && (!isset( $display['combined'] ) || !$display['combined']) && !$vertical ) {
        $mb = ' ' . $mr;
    }
    
    if ( $dateicon == 'event_end_date' && isset( $display['combined'] ) && $display['combined'] && !$vertical ) {
        $bar = $bor = '';
        $bar = 'style="border-left-width:1px;' . $tl . $bl . '"';
    }
    
    
    if ( $style['date_bold'] ) {
        $boldon = '<b>';
        $boldoff = '</b>';
    }
    
    
    if ( $style['date_italic'] ) {
        $italicon = '<em>';
        $italicoff = '</em>';
    }
    
    if ( $vw ) {
        $style['vanillawidget'] = 'checked';
    }
    $unixtime = get_post_meta( $post->ID, $dateicon, true );
    $endtime = get_post_meta( $post->ID, 'event_end_date', true );
    
    if ( $unixtime ) {
        $month = date_i18n( "M", $unixtime );
        if ( 'checked' === qem_get_element( $style, 'vanilla', false ) && 'checked' === qem_get_element( $style, 'vanillamonth', false ) ) {
            $month = date_i18n( "F", $unixtime );
        }
        $dayname = date_i18n( "D", $unixtime );
        if ( 'checked' === qem_get_element( $style, 'vanilla', false ) && 'checked' === qem_get_element( $style, 'vanilladay', false ) ) {
            $dayname = date_i18n( "l", $unixtime );
        }
        $day = date_i18n( "d", $unixtime );
        $year = date_i18n( "Y", $unixtime );
        
        if ( $endtime && qem_get_element( $display, 'amalgamated' ) ) {
            $endmonth = date_i18n( "M", $endtime );
            if ( $style['vanilla'] && $style['vanillamonth'] ) {
                $endmonth = date_i18n( "F", $endtime );
            }
            $endday = date_i18n( "d", $endtime );
            $endyear = date_i18n( "Y", $endtime );
            
            if ( $month == $endmonth && $year == $endyear && $endday && $dateicon != 'event_end_date' ) {
                
                if ( 'checked' === qem_get_element( $style, 'use_dayname', false ) ) {
                    $endname = date_i18n( "D", $endtime ) . ' ';
                    if ( $style['vanilla'] && 'checked' === qem_get_element( $style, 'vanilladay', false ) ) {
                        $endname = date_i18n( "l", $endtime ) . ' ';
                    }
                }
                
                $day = $day . ' - ' . $endname . $endday;
                $amalgum = 'on';
            }
        
        }
        
        
        if ( $dateicon == 'event_date' && isset( $display['combined'] ) && $display['combined'] && $join && !$amalgum ) {
            $bar = $bor = '';
            $bar = 'style="border-right:none;' . $tr . $br . '"';
            $mr = ' 0';
        }
        
        
        if ( $style['iconorder'] == 'month' ) {
            $top = $month;
            $middle = $day;
            $bottom = $year;
        } elseif ( $style['iconorder'] == 'year' ) {
            $top = $year;
            $middle = $day;
            $bottom = $month;
        } elseif ( $style['iconorder'] == 'dm' ) {
            $top = $day;
            $middle = $month;
        } elseif ( $style['iconorder'] == 'md' ) {
            $top = $month;
            $middle = $day;
        } else {
            $top = $day;
            $middle = $month;
            $bottom = $year;
        }
        
        $label = '';
        if ( $dateicon == 'event_date' && $endtime && $style['uselabels'] ) {
            $label = $style['startlabel'] . '<br>';
        }
        if ( $dateicon == 'event_end_date' && $endtime && $style['uselabels'] ) {
            $label = $style['finishlabel'] . '<br>';
        }
        if ( isset( $display['amalgamated'] ) && $display['amalgamated'] && $amalgum ) {
            $label = '';
        }
        
        if ( 'checked' === qem_get_element( $style, 'vanilla', false ) || 'checked' === qem_get_element( $style, 'vanillawidget', false ) && $widget ) {
            if ( $dateicon == 'event_end_date' ) {
                $sep = '&nbsp; - &nbsp;';
            }
            $content = $sep;
            if ( 'checked' === qem_get_element( $style, 'use_dayname', false ) ) {
                $content .= $dayname . '&nbsp;';
            }
            $content .= $top . '&nbsp;' . $middle . '&nbsp;' . $bottom;
        } else {
            $content = '<div class="qem-calendar-' . $width . '" style="margin:0 ' . $mr . $mb . ' 0;"><span class="day" ' . $bar . '>' . $label;
            
            if ( 'checked' === qem_get_element( $style, 'use_dayname', false ) ) {
                $content .= $dayname;
                $content .= ( $style['use_dayname_inline'] ? ' ' : '<br>' );
            }
            
            $content .= $top . '</span><span class="nonday" ' . $bar . '><span class="month">' . $boldon . $italicon . $middle . $italicoff . $boldoff . '</span><span class="year">' . $bottom . '</span></span></div>';
        }
        
        return $content;
    }

}

// Builds the event content
function qem_build_event(
    $name,
    $event,
    $display,
    $custom,
    $atts,
    $register,
    $payment
)
{
    global  $post ;
    $style = $output = $caption = $target = '';
    
    if ( qem_get_element( $atts, 'settings', false ) ) {
        if ( qem_get_element( $event['bold'], $name, false ) == 'checked' ) {
            $style .= 'font-weight: bold; ';
        }
        if ( qem_get_element( $event['italic'], $name, false ) == 'checked' ) {
            $style .= 'font-style: italic; ';
        }
        if ( qem_get_element( $event['colour'], $name, false ) ) {
            $style .= 'color: ' . $event['colour'][$name] . '; ';
        }
        if ( qem_get_element( $event['size'], $name, false ) ) {
            $style .= 'font-size: ' . $event['size'][$name] . '%; ';
        }
        if ( $style ) {
            $style = 'style="' . $style . '" ';
        }
    }
    
    switch ( $name ) {
        case 'field1':
            if ( !empty($event['description_label']) ) {
                $caption = $event['description_label'] . ' ';
            }
            if ( !empty($custom['event_desc'][0]) ) {
                $output .= apply_filters(
                    'qem_short_desc',
                    $custom['event_desc'][0],
                    $caption,
                    $style
                );
            }
            break;
        case 'field2':
            
            if ( !empty($custom['event_start'][0]) ) {
                $output .= '<p class="start" ' . $style . '>' . $event['start_label'] . ' ' . $custom['event_start'][0];
                if ( !empty($custom['event_finish'][0]) ) {
                    $output .= ' ' . $event['finish_label'] . ' ' . $custom['event_finish'][0];
                }
                if ( $display['usetimezone'] && $custom['event_timezone'][0] ) {
                    $output .= ' ' . $display['timezonebefore'] . ' ' . $custom['event_timezone'][0] . ' ' . $display['timezoneafter'];
                }
                $output .= '</p>';
            }
            
            break;
        case 'field3':
            if ( !empty($event['location_label']) ) {
                $caption = $event['location_label'] . ' ';
            }
            if ( !empty($custom['event_location'][0]) ) {
                $output .= '<p class="location" ' . $style . '>' . $caption . $custom['event_location'][0] . '</p>';
            }
            break;
        case 'field4':
            if ( !empty($event['address_label']) ) {
                $caption = $event['address_label'] . ' ';
            }
            if ( !empty($custom['event_address'][0]) ) {
                $output .= '<p class="address" ' . $style . '>' . $caption . $custom['event_address'][0] . '</p>';
            }
            break;
        case 'field5':
            if ( !empty($event['url_label']) ) {
                $caption = $event['url_label'] . ' ';
            }
            if ( isset( $display['external_link_target'] ) && $display['external_link_target'] ) {
                $target = 'target="_blank"';
            }
            
            if ( !preg_match( "~^(?:f|ht)tps?://~i", $custom['event_link'][0] ) ) {
                $url = 'http://' . $custom['event_link'][0];
            } else {
                $url = $custom['event_link'][0];
            }
            
            if ( empty($custom['event_anchor'][0]) ) {
                $custom['event_anchor'][0] = $custom['event_link'][0];
            }
            if ( !empty($custom['event_link'][0]) ) {
                $output .= '<p class="website" ' . $style . '>' . $caption . '<a itemprop="url" ' . $style . ' ' . $target . ' href="' . $url . '">' . $custom['event_anchor'][0] . '</a></p>';
            }
            break;
        case 'field6':
            if ( !empty($event['cost_label']) ) {
                $caption = $event['cost_label'] . ' ';
            }
            
            if ( !empty($custom['event_cost'][0]) ) {
                $output .= '<p ' . $style . '>' . $caption . $custom['event_cost'][0];
                if ( !empty($event['deposit_before_label']) ) {
                    $bcaption = $event['deposit_before_label'] . ' ';
                }
                if ( !empty($event['deposit_after_label']) ) {
                    $acaption = ' ' . $event['deposit_after_label'];
                }
                if ( !empty($custom['event_deposit'][0]) ) {
                    $output .= ' (' . $bcaption . $custom['event_deposit'][0] . $acaption . ')';
                }
            }
            
            if ( $output ) {
                $output .= '</p>';
            }
            break;
        case 'field7':
            if ( !empty($event['organiser_label']) ) {
                $caption = $event['organiser_label'] . ' ';
            }
            
            if ( !empty($custom['event_organiser'][0]) ) {
                $output .= '<p class="organisation" ' . $style . '>' . $caption . $custom['event_organiser'][0];
                if ( !empty($custom['event_telephone'][0]) && $event['show_telephone'] ) {
                    $output .= ' / ' . $custom['event_telephone'][0];
                }
                $output .= '</p>';
            }
            
            break;
        case 'field8':
            $output .= apply_filters( 'qem_description', get_the_content() );
            break;
        case 'field9':
            $str = qem_get_the_numbers( $post->ID, $payment );
            $event_number_max = get_post_meta( $post->ID, 'event_number', true );
            if ( '' !== $event_number_max && (int) $str > (int) $event_number_max ) {
                $str = $event_number_max;
            }
            
            if ( $str ) {
                
                if ( $str == 1 ) {
                    $str = $event['oneattendingbefore'];
                } else {
                    $str = $event['numberattendingbefore'] . ' ' . $str . ' ' . $event['numberattendingafter'];
                }
                
                $output .= '<p id="whoscoming" class="totalcoming" ' . $style . '>' . $str . '</p>';
            }
            
            break;
        case 'field10':
            $hide = '';
            global  $qem_fs ;
            $event_number_max = get_post_meta( $post->ID, 'event_number', true );
            $num = 0;
            $str = $grav = $content = '';
            $whoscoming = get_option( 'qem_messages_' . $post->ID );
            
            if ( $whoscoming ) {
                
                if ( qem_get_element( $register, 'listnames', false ) ) {
                    foreach ( $whoscoming as $item ) {
                        $num = $num + (int) $item['yourplaces'];
                        $ipn = qem_check_ipnblock( $payment, $item );
                        
                        if ( ('' === $event_number_max || (int) $num <= (int) $event_number_max) && !$item['notattend'] && !$ipn && ($register['moderate'] && $item['approved'] || !$register['moderate']) ) {
                            $url = '';
                            
                            if ( isset( $item['yourblank1'] ) ) {
                                $url = preg_replace( '/(?:https?:\\/\\/)?(?:www\\.)?(.*)\\/?$/i', '$1', $item['yourblank1'] );
                                if ( $url ) {
                                    $url = ' <a href="' . $item['yourblank1'] . '">' . $url . '</a>';
                                }
                            }
                            
                            $msg = qem_get_element( $register, 'listblurb' );
                            $msg = str_replace( '[name]', qem_get_element( $item, 'yourname' ), $msg );
                            $msg = str_replace( '[email]', qem_get_element( $item, 'youremail' ), $msg );
                            $msg = str_replace( '[mailto]', '<a href="mailto:' . qem_get_element( $item, 'youremail' ) . '">' . qem_get_element( $item, 'youremail' ) . '</a>', $msg );
                            $msg = str_replace( '[places]', qem_get_element( $item, 'yourplaces' ), $msg );
                            $msg = str_replace( '[telephone]', qem_get_element( $item, 'telephone' ), $msg );
                            $msg = str_replace( '[user1]', qem_get_element( $item, 'yourblank1' ), $msg );
                            $msg = str_replace( '[user2]', qem_get_element( $item, 'yourblank2' ), $msg );
                            $msg = str_replace( '[website]', $url, $msg );
                            if ( qem_get_element( $item, 'yourname', false ) ) {
                                $str = $str . '<li>' . $msg . '</li>';
                            }
                        }
                    
                    }
                    if ( $str && qem_get_element( $event, 'whoscoming', false ) && $hide != 'checked' ) {
                        $content .= '<p id="whoscoming_names" class="qem__whoscoming_names"' . $style . '>' . $event['whoscomingmessage'] . '</p><ul>' . $str . '</ul>';
                    }
                } else {
                    foreach ( $whoscoming as $item ) {
                        if ( isset( $item['yourplaces'] ) ) {
                            $num = $num + (int) $item['yourplaces'];
                        }
                        $ipn = qem_check_ipnblock( $payment, $item );
                        
                        if ( ('' === $event_number_max || (int) $num <= (int) $event_number_max) && !qem_get_element( $item, 'notattend', false ) && !$ipn && ($register['moderate'] && $item['approved'] || !$register['moderate']) ) {
                            $str = $str . esc_attr( qem_get_element( $item, 'yourname' ) ) . ', ';
                            $grav = $grav . '<img title="' . esc_attr( qem_get_element( $item, 'yourname' ) ) . '" src="http://www.gravatar.com/avatar/' . md5( qem_get_element( $item, 'youremail' ) ) . '?s=40&&d=identicon" /> ';
                        }
                    
                    }
                    $str = substr( $str, 0, -2 );
                    if ( $str && $event['whoscoming'] && $hide != 'checked' ) {
                        $content .= '<p id="whoscoming_names" class="qem__whoscoming_names" ' . $style . '>' . $event['whoscomingmessage'] . ' ' . $str . '</p>';
                    }
                    if ( $event['whosavatar'] && $hide != 'checked' ) {
                        $content .= '<p>' . $grav . '</p>';
                    }
                }
                
                $output .= $content;
            }
            
            break;
        case 'field11':
            $event_number_max = get_post_meta( $post->ID, 'event_number', true );
            $num = qem_number_places_available( $post->ID );
            $placesavailable = 'checked';
            if ( isset( $event['iflessthan'] ) && $event['iflessthan'] && $num > $event['iflessthan'] ) {
                $placesavailable = '';
            }
            
            if ( $register['waitinglist'] && $num == 0 && '' !== $event_number_max ) {
                $output .= '<p id="whoscoming">' . $event['placesbefore'] . ' 0 ' . $event['placesafter'] . ' <span id="waitinglistmessage">' . $register['waitinglistmessage'] . '</span><p>';
            } elseif ( $placesavailable ) {
                $output .= '<p class="placesavailable" ' . $style . '>' . qem_places(
                    $register,
                    $post->ID,
                    $event_number_max,
                    $event
                ) . '</p>';
            }
            
            break;
        case 'field12':
            if ( !$atts['popup'] && !$register['ontheright'] && (is_singular( 'event' ) || $atts['fulllist']) && !$atts['widget'] && $atts['usereg'] ) {
                $output .= qem_loop_esc();
            }
            break;
        case 'field13':
            if ( !empty($event['category_label']) ) {
                $caption = $event['category_label'] . '&nbsp;';
            }
            $categories = get_the_category();
            foreach ( $categories as $category ) {
                $cat_name = $cat_name . ' ' . $category->cat_name;
            }
            $output .= '<p ' . $style . '>' . $caption . $cat_name . '</p>';
            break;
        case 'field14':
            $link = get_permalink();
            $output .= '<h4>';
            
            if ( $event['facebook_label'] ) {
                $facebook_svg = '<svg fill="#3B5998" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 64 64" width="24px" height="24px"><path d="M32,6C17.642,6,6,17.642,6,32c0,13.035,9.603,23.799,22.113,25.679V38.89H21.68v-6.834h6.433v-4.548	c0-7.529,3.668-10.833,9.926-10.833c4.166,0,4.583,0.223,5.332,0.323v5.965h-4.268c-2.656,0-3.584,2.52-3.584,5.358v3.735h7.785	l-1.055,6.834h-6.73v18.843C48.209,56.013,58,45.163,58,32C58,17.642,46.359,6,32,6z"/></svg>';
                $output .= '<a 
                	style="display: inline-flex;align-items: center; margin-right: 10px;" 
                	target="_blank" 
                	class="qem_fb_share"
                	href="https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $link ) . '">' . $facebook_svg . '<span style="margin-left: 3px;">' . $event['facebook_label'] . '</span>' . '</a>';
            }
            
            
            if ( $event['twitter_label'] ) {
                $twitter_svg = '<svg fill="#1DA1F2" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 64 64" width="24px" height="24px"><path d="M61.932,15.439c-2.099,0.93-4.356,1.55-6.737,1.843c2.421-1.437,4.283-3.729,5.157-6.437	c-2.265,1.328-4.774,2.303-7.444,2.817C50.776,11.402,47.735,10,44.366,10c-6.472,0-11.717,5.2-11.717,11.611	c0,0.907,0.106,1.791,0.306,2.649c-9.736-0.489-18.371-5.117-24.148-12.141c-1.015,1.716-1.586,3.726-1.586,5.847	c0,4.031,2.064,7.579,5.211,9.67c-1.921-0.059-3.729-0.593-5.312-1.45c0,0.035,0,0.087,0,0.136c0,5.633,4.04,10.323,9.395,11.391	c-0.979,0.268-2.013,0.417-3.079,0.417c-0.757,0-1.494-0.086-2.208-0.214c1.491,4.603,5.817,7.968,10.942,8.067	c-4.01,3.109-9.06,4.971-14.552,4.971c-0.949,0-1.876-0.054-2.793-0.165C10.012,54.074,16.173,56,22.786,56	c21.549,0,33.337-17.696,33.337-33.047c0-0.503-0.016-1.004-0.04-1.499C58.384,19.83,60.366,17.78,61.932,15.439"/></svg>';
                $unixtime = $custom['event_date'][0];
                $date = date_i18n( "j+M+y", $unixtime );
                $title = get_the_title();
                $title = str_replace( ' ', '+', $title );
                $output .= '<a 
					style="display: inline-flex; align-items: center;" 
					target="_blank" 
					class="qem_twitter_share"
					href="https://twitter.com/share?url=' . $link . '&text=' . $date . '+-+' . $title . '&hashtags=WFTR">' . $twitter_svg . '<span style="margin-left: 3px;">' . $event['twitter_label'] . '</span>' . '</a>';
            }
            
            if ( qem_get_element( $event, 'useicsbutton', false ) && (!$display['useics'] || !$display['uselistics']) ) {
                $output .= qem_ics_button( $post->ID, $event['useicsbutton'] );
            }
            $output .= '</h4>';
            break;
    }
    return $output;
}

// Generates the map
function get_event_map( $mapwidth )
{
    global  $post ;
    $event = event_get_stored_options();
    $display = event_get_stored_display();
    $mapurl = $target = '';
    if ( isset( $display['map_target'] ) && $display['map_target'] ) {
        $target = ' target="_blank" ';
    }
    $custom = get_post_custom();
    if ( $display['show_map'] == 'checked' && !empty($custom['event_address'][0]) ) {
        
        if ( !isset( $display['apikey'] ) || empty($display['apikey']) ) {
            $mapurl = '<div class="qemmap">' . __( 'Since June 2016 you need to have a valid API key enabled to display Google maps, see plugin settings', 'quick-event-manager' ) . '</div>';
        } else {
            $map = str_replace( ' ', '+', $custom['event_address'][0] );
            $mapurl .= '<div class="qemmap"><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=' . $map . '&amp;t=m" ' . $target . '><img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $map . '&size=' . $mapwidth . 'x' . $display['map_height'] . '&markers=color:blue%7C' . $map . '&key=' . $display['apikey'] . '" alt="' . $custom['event_address'][0] . '" /></a></div>';
        }
    
    }
    return $mapurl;
}

function qem_external_permalink( $link, $post )
{
    $meta = get_post_meta( $post->ID, 'event_link', true );
    $url = esc_url( filter_var( $meta, FILTER_VALIDATE_URL ) );
    return ( $url ? $url : $link );
}

function get_event_content( $content )
{
    global  $post ;
    $pw = get_post_meta( $post->ID, 'event_password_details', true );
    
    if ( post_password_required( $post ) && $pw ) {
        return get_the_password_form();
    } else {
        $atts = array(
            'links'          => 'off',
            'size'           => '',
            'headersize'     => '',
            'settings'       => 'checked',
            'fullevent'      => 'fullevent',
            'images'         => '',
            'fields'         => '',
            'widget'         => '',
            'cb'             => '',
            'vanillawidget'  => '',
            'linkpopup'      => '',
            'thisday'        => '',
            'popup'          => '',
            'vw'             => '',
            'categoryplaces' => '',
            'fulllist'       => '',
            'thisisapopup'   => '',
            'listplaces'     => true,
            'calendar'       => false,
            'fullpopup'      => false,
            'grid'           => '',
        );
        if ( is_singular( 'event' ) ) {
            $content = qem_event_construct_esc( $atts );
        }
        return $content;
    }

}
