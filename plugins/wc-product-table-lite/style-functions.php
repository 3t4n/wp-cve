<?php

function wcpt_print_styles(){
  ob_start();
  echo '<style>';
  echo wcpt_parse_media_query_toggle();
  echo wcpt_parse_style();
  echo wcpt_parse_elements_style();

  echo wcpt_parse_columns_style();
  echo wcpt_parse_css();

  do_action('wcpt_print_styles');

  echo '</style>';
  echo str_replace( array( '\r', '\n', '\t', '  ', '   ' ) , array(''), ob_get_clean() );
}

$wcpt_breakpoints = array(
  'tablet' => '1199',
  'phone'  => '749',
);

// giving extra room for screen orientation change
$wcpt_device = wcpt_get_device();
if( $wcpt_device == 'phone' ){
  $wcpt_breakpoints = array(
    'tablet' => '1199',
    'phone'  => '849',      
  );

}else if( $wcpt_device == 'tablet' ){
  $wcpt_breakpoints = array(
    'tablet' => '1399',
    'phone'  => '699',
  );

}

function wcpt_parse_style( ){
  
  $data = wcpt_get_table_data();
  $style = $data['style'];

  $css_string = '';

  $divisions = array(
    'laptop',
    'tablet',
    'phone',
    'navigation',
  );

  foreach( $divisions as $division ){

    if( empty( $style[$division] ) ){
      continue; // device has no selectors
    }

    $division_style_string = '';

    // special styling needs
    extract( apply_filters(
      'wcpt_style_division', 
      array(
        'style_division'=> $style[$division], 
        'division_style_string'=> $division_style_string,
      ) 
    ) );

    // add in :hover & :selected
    foreach( $style_division as $selector =>& $props ){

      if( empty( $props ) || ! is_array( $props ) ){
        continue;
      }

      // append selectors for :hover :selected props
      foreach( $props as $prop => $val ){
        // collect hover
        if( 
          strlen( $prop ) > 6 && 
          ':hover' == substr( $prop, -6 ) 
        ){
          if( empty( $style_division[ $selector . ':hover' ] ) ){
            $style_division[ $selector . ':hover' ] = array();
          }
          $style_division[ $selector . ':hover' ][substr( $prop, 0, -6 )] = $val;

        // collect selected
        }else if(
          strlen( $prop ) > 9 && 
          ':selected' == substr( $prop, -9 ) 
        ){
          if( empty( $style_division[ $selector . '.wcpt-active' ] ) ){
            $style_division[ $selector . '.wcpt-active' ] = array();
          }
          $style_division[ $selector . '.wcpt-active' ][substr( $prop, 0, -9 )] = $val;

        }else{
          continue;
        }

        // remove :hover :selected pseudo props
        unset( $props[$prop] );
      }

    }
    unset($props);

    // for the device iterate over all its selectors
    foreach( $style_division as $selector => $props ){

      if( empty( $props ) || ! is_array( $props ) ){
        // selector has no props or not a selector but special prop like inheritance
        continue;
      }

      // build selector props string
      $props_string = '';
      foreach( $props as $prop => $val ){
        extract( apply_filters( 'wcpt_style_prop_val', array(
          'prop' => $prop,
          'val'=> $val,
          'selector'=> $selector,
        ) ) );

        if( $val && gettype($val) == 'string' ){
          $props_string .= $prop . ':' . $val . ';';
        }
      }

      // deliver selector props string
      if( $props_string ){
        $division_style_string .= ' ' . $selector . '{'. $props_string .'}';
      }

    }

    $division_style_string = str_replace( '[device]', '', $division_style_string );

    // media query
    // wrapping the style string inside a media query 

    //-- laptop
    if( $division == 'laptop' ){

      $min_width = '0';

      // apply above phone breakpoint
      if( empty( $style['phone']['inherit_tablet_style'] ) ){
        $min_width = (int) $GLOBALS['wcpt_breakpoints']['phone'] + 1;
      }

      // apply above tablet breakpoint
      if( empty( $style['tablet']['inherit_laptop_style'] ) ){
        $min_width = (int) $GLOBALS['wcpt_breakpoints']['tablet'] + 1;
      }

      $division_style_string = '@media(min-width:'. $min_width .'px){' . $division_style_string . '}';

    //-- tablet
    }else if( $division == 'tablet' ){

      $max_width = $GLOBALS['wcpt_breakpoints']['tablet'];
      $min_width = '0';

      // apply above phone breakpoint
      if( empty( $style['phone']['inherit_tablet_style'] ) ){
        $min_width = (int) $GLOBALS['wcpt_breakpoints']['phone'] + 1;
      }

      $division_style_string = '@media(max-width: '. $max_width .'px) and (min-width:'. $min_width .'px){' . $division_style_string . '}';

    //-- phone
    }else if( $division == 'phone' ){

      $max_width = $GLOBALS['wcpt_breakpoints']['phone'];

      // apply upto phone breakpoint
      $division_style_string = '@media(max-width:'. $max_width .'px){' . $division_style_string . '}';

    }

    // deliver device style string
    $css_string .= $division_style_string;

  }

  $css_string = str_replace(
    array(
      '[container]',
      '[id]'
    ), 
    array(
      '#wcpt-' . $data['id'],
      $data['id']
    ), 
    $css_string
  );

  return $css_string;
}

// based on current device width, using media query, show the appropriate table
function wcpt_parse_media_query_toggle(){

  $table_data = wcpt_get_table_data();
  $table_id = $table_data['id'];



  ob_start();

  // device columns
  $laptop_columns = wcpt_get_device_columns_2('laptop', $table_data);
  $tablet_columns = wcpt_get_device_columns_2('tablet', $table_data);
  $phone_columns = wcpt_get_device_columns_2('phone', $table_data);

  $breakpoints = $GLOBALS['wcpt_breakpoints'];

  // tablet
  if( $tablet_columns ){
    ?>
    @media (max-width:<?php echo $breakpoints['tablet']; ?>px) and (min-width:<?php echo $breakpoints['phone']; ?>px){
      #wcpt-<?php echo $table_id; ?> .wcpt-device-tablet {
        display: block;
      }

      #wcpt-<?php echo $table_id; ?> .wcpt-device-laptop,
      #wcpt-<?php echo $table_id; ?> .wcpt-device-phone {
        display: none;
      }
    }
    <?php
  }

  // phone
  if( $phone_columns ){
    ?>
    @media (max-width:<?php echo $breakpoints['phone']; ?>px) {
      #wcpt-<?php echo $table_id; ?> .wcpt-device-phone {
        display: block;
      }

      #wcpt-<?php echo $table_id; ?> .wcpt-device-tablet,
      #wcpt-<?php echo $table_id; ?> .wcpt-device-laptop {
        display: none;
      }
    }
    <?php
  }

  return ob_get_clean();
}

// css shortcodes facility
function wcpt_parse_css(){
  $data = wcpt_get_table_data();

  if( empty( $data['style']['css'] ) ){
    return;
  }

  $wcpt_selector = '#wcpt-' . $data['id'];
  $arr = array(
    '[container]'     => $wcpt_selector,
    '[id]'            => $data['id'],
    '[table]'         => $wcpt_selector . ' .wcpt-table',
    '[heading_row]'   => $wcpt_selector . ' .wcpt-heading-row',

    '[heading_cell]'      => $wcpt_selector . ' .wcpt-heading-row .wcpt-heading',
    '[heading_cell_even]' => $wcpt_selector . ' .wcpt-heading-row .wcpt-heading:nth-child(even)',
    '[heading_cell_odd]'  => $wcpt_selector . ' .wcpt-heading-row .wcpt-heading:nth-child(odd)',

    '[row]'           => $wcpt_selector . ' .wcpt-row',
    '[row_even]'      => $wcpt_selector . ' .wcpt-row:nth-child(even)',
    '[row_odd]'       => $wcpt_selector . ' .wcpt-row:nth-child(odd)',

    '[cell]'          => $wcpt_selector . ' .wcpt-cell',
    '[cell_even]'     => $wcpt_selector . ' .wcpt-cell:nth-child(even)',
    '[cell_odd]'      => $wcpt_selector . ' .wcpt-cell:nth-child(odd)',

    '[tablet]'  => ' @media(max-width: 1199px){',
    '[/tablet]' => '} ',

    '[phone]'   => ' @media(max-width: 749px){',
    '[/phone]'  => '} ',
  );

  $search = array_keys( $arr );
  $replace = array_values( $arr );

  return str_replace( $search , $replace , $data['style']['css'] );
}

// pull out css from the elements
function wcpt_parse_elements_style(){
  $data = wcpt_get_table_data();
  $wcpt_selector = '#wcpt-' . $data['id'];
  $elements = wcpt_get_column_elements($data);
  $css = '';

  foreach( $elements as $element_type => $element_rows ){
    foreach( $element_rows as $element_settings ){
      if( ! empty( $element_settings['style'] ) ){
        $element_settings_style = '';

        $css_string = '';
        $style = &$element_settings['style'];
        foreach( $style as $selector => $props ){
          if( empty( $props ) ){
            continue;
          }

          $string = '';
          // collect style props for selector
          foreach( $props as $prop => $val ){
            extract( apply_filters( 'wcpt_style_prop_val', array(
              'prop' => $prop,
              'val'=> $val,
              'selector'=> $selector,
            ) ) );

            if( $val != '' ){
              $string .= $prop . ':' . $val . ';';
            }

          }

          if( $string ){
            $css_string .= ' ' . $selector . '{'. $string .'}';
          }
        }

        if( $css_string && $element_settings['settings'] ){
          $container_selector = $wcpt_selector . ' .wcpt-'. $element_type . '-' . sanitize_title( $element_settings['settings'] );
          $css_string = str_replace('[container]', $container_selector, $css_string);

          $css .= $css_string;
        }

      }
    }
  }

  return $css;
}

// pull out css from the columns
function wcpt_parse_columns_style(){
  $data = wcpt_get_table_data();
  $wcpt_selector = '#wcpt-' . $data['id'];
  $devices = array(
    'laptop' => '',
    'tablet' => '1199px',
    'phone' => '749px',
  );

  ob_start();

  foreach( $devices as $device => $max_width ){

    $device_columns = wcpt_get_device_columns( $device, $data );

    if( ! $device_columns ){
      continue;
    }

    if( $max_width ){
      echo ' @media(max-width:'. $max_width .'){';
    }

    foreach( $device_columns as $column_key => $column ){
      if( empty( $column['style'] ) ){
        continue;
      }

      echo ' ' . $wcpt_selector . ' .wcpt-cell:nth-child('. ( $column_key + 1 ) .') {';

      foreach( $column['style'] as $prop => $val ){
        extract( apply_filters( 'wcpt_style_prop_val', array(
          'prop' => $prop,
          'val'=> $val,
          'selector'=> $selector,
        ) ) );

        echo $prop . ':' . $val . ';';
      }

      echo '}';

    }

    if( $max_width ){
      echo '}';
    }

  }

  return ob_get_clean();

}

// append "px" to style vals
add_filter( 'wcpt_style_prop_val', 'wcpt_style_prop_val_filter' );
function wcpt_style_prop_val_filter( $arr ){
  if( is_numeric( $arr['val'] ) && ! in_array( $arr['prop'], array( 'opacity', 'font-weight' ) ) ){
    $arr['val'] .= 'px';
  }

  return $arr;
}

// parse element and row style
function wcpt_parse_style_2( $item, $important= false ){
  if( empty( $item['style'] ) ){
    return;
  }

  // image width fix
  if(
    ! empty( $item['type'] ) &&
    $item['type'] == 'product_image' &&
    ! empty( $item['style'] ) &&
    ! empty( $item['style']['[id]'] ) &&
    ! empty( $item['style']['[id]']['max-width'] )
  ){
    $item['style']['[id]']['min-width'] = $item['style']['[id]']['max-width'];
  }

  $id = '.wcpt-' . $item['id'];
  foreach( $item['style'] as $selector => $props ){
    if( ! isset( $GLOBALS['wcpt_table_data']['style_items'][$selector] ) ){ // elm not already parsed
      $props_string = '';
      $hover_style = array();
      $selected_style = array();

      // border-solid property fix
      if(
        ! empty( $props['border-color'] ) ||
        ! empty( $props['border-width'] ) &&
        empty( $props['border-style'] )
      ){
        $props['border-style'] = 'solid';

      }else if(
        ! empty( $props['border-style'] ) &&
        empty( $props['border-width'] ) &&
        empty( $props['border-color'] )
      ){
        $props['border-style'] = '';

      }

      foreach( $props as $prop => $val ){
        // collect hover state props
        if( 
          strlen( $prop ) > 6 && 
          ':hover' == substr( $prop, -6 ) 
        ){
          $hover_style[substr( $prop, 0, -6 )] = $val;

        // collect selected state props
        }else if(
          strlen( $prop ) > 9 && 
          ':selected' == substr( $prop, -9 ) 
        ){
          $selected_style[substr( $prop, 0, -9 )] = $val;

        // process normal state props
        }else{
          extract( apply_filters( 'wcpt_style_prop_val', array(
            'prop' => $prop,
            'val'=> $val,
            'selector'=> $selector,
          ) ) );

          if( $val && gettype($val) == 'string' ){
            $props_string .= $prop . ':' . $val . ( $important ? ' !important' : '' ) . ';';
          }

        }

      }

      $table_data = wcpt_get_table_data();

      $selector = str_replace(
        array(
          '[id]',
          '[wcpt_id]'
        ),
        array(
          $id,
          '#' . $table_data['id']
        ), $selector);
      $GLOBALS['wcpt_table_data']['style_items'][$selector] = ' ' . $selector . '{'. $props_string .'} ';

      // parse hover
      if( count( $hover_style ) ){
        wcpt_parse_style_2( array( // dummy elm with bare info
          'id' => $item['id'],
          'style' => array(
            $selector . ':hover' => $hover_style
          )
        ), $important );
      }

      // parse selected
      if( count( $selected_style ) ){
        wcpt_parse_style_2( array( // dummy elm with bare info
          'id' => $item['id'],
          'style' => array(
            $selector . '.wcpt-active' => $selected_style
          )
        ), $important );
      }

    }
  }
}

function wcpt_item_styles(){
  $data = wcpt_get_table_data();
  if( empty( $data['style_items'] ) ){
    return;
  }

  $style_markup = '<style>';
  foreach( $data['style_items'] as $itm_selector => $itm_style_props ){
    $style_markup .= ' #wcpt-' . $data['id'] . ' ' . $itm_style_props;
    $style_markup .= ' body ' . $itm_style_props;
  }
  $style_markup .= '</style>';
  echo $style_markup;
}


// transfer search element width to input wrapper
add_filter('wcpt_element', 'wcpt_style__search_bar_width');
function wcpt_style__search_bar_width( $elm ){
  if( 
    ! empty( $elm['type'] ) &&
    $elm['type'] == 'search' &&
    ! empty( $elm['style'] ) &&
    ! empty( $elm['style']['[id]'] ) &&
    ! empty( $elm['style']['[id]']['width'] )
  ){
    $elm['style']['[id] > .wcpt-search']['width'] = $elm['style']['[id]']['width'];
    unset( $elm['style']['[id]']['width'] );
  }

  return $elm;
}

// For nav filters, make dropdown heading retain hover props when dropdown menu is hovered
add_filter('wcpt_element', 'wcpt_style__filter_heading_hover');
function wcpt_style__filter_heading_hover( $elm ){
  $heading_selector = '.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-filter-heading';
  $hover_selector = '.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter:hover > .wcpt-filter-heading';

  if( 
    ! empty( $elm['type'] ) &&
    in_array( $elm['type'], array( 
      'sort_by',
      'results_per_page',
      'category_filter',      
      'price_filter',
      'attribute_filter',
      'custom_field_filter',
      'taxonomy_filter',
      'availability_filter',
      'on_sale_filter',
      'rating_filter'
    ) ) &&
    ! empty( $elm['style'] ) &&
    ! empty( $elm['style'][$heading_selector] )
  ){
    $props = array( 'color', 'background-color', 'border-color' );
    foreach( $props as $prop ){
      if( ! empty( $elm['style'][$heading_selector][$prop .':hover'] ) ){
        $val = $elm['style'][$heading_selector][$prop .':hover'];

        if( empty( $elm['style'][$hover_selector] ) ){
          $elm['style'][$hover_selector] = array();
        }

        $elm['style'][$hover_selector][$prop] = $val;        
      }
    }
  }

  return $elm;
}

add_filter( 'wcpt_style_division', 'wcpt_style_division' );
function wcpt_style_division( $arr ){
  extract( $arr ); // $style_division, $division_style_string

  // sidebar
  $sidebar_selector = '[container] .wcpt-left-sidebar.wcpt-navigation';
  if( ! empty( $style_division[$sidebar_selector] ) ){  

    // width
    if( ! empty( $style_division[$sidebar_selector]['width'] ) ){
      $width = (float) $style_division[$sidebar_selector]['width'];
      $gap = 30;
      if( ! empty( $style_division[$sidebar_selector]['gap'] ) ){
        $gap = (float) $style_division[$sidebar_selector]['gap'];
      }

      ob_start();
      ?>
      [container] .wcpt-left-sidebar + .wcpt-header, 
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer,
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-required-but-missing-nav-filter-message, 
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-no-results.wcpt-device-laptop, 
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-pagination, 
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-in-footer, 
      [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-in-footer + .wcpt-pagination 
      {
        width: calc(100% - <?php echo ((float) $style_division[$sidebar_selector]['width'] + $gap) . 'px'; ?>);
      }
      <?php
      $division_style_string .= ' ' . ob_get_clean() . ' ';
    }

    if( ! empty( $style_division[$sidebar_selector]['gap'] ) ){
      unset( $style_division[$sidebar_selector]['gap'] );
    }
    
    // position
    if( ! empty( $style_division[$sidebar_selector]['float'] ) ){
      $float = $style_division[$sidebar_selector]['float'];
      if( $float == 'right' ){
        ob_start();
        ?>
        [container] .wcpt-left-sidebar + .wcpt-header, 
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer,
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-required-but-missing-nav-filter-message, 
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-no-results.wcpt-device-laptop, 
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-pagination, 
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-in-footer, 
        [container] .wcpt-left-sidebar + .wcpt-header + .wcpt-responsive-navigation + .wcpt-nav-modal-tpl + .wcpt-table-scroll-wrapper-outer + .wcpt-in-footer + .wcpt-pagination 
        {
          float: left;
        }
        <?php
        $division_style_string .= ' ' . ob_get_clean() . ' ';
      }
    }  

  }

  // header
  $header_selector = '[container] .wcpt-header.wcpt-navigation';
  if( ! empty( $style_division[$header_selector] ) ){  
    if( ! empty( $style_division[$header_selector]['row_gap'] ) ){
      $row_gap = (float) $style_division[$header_selector]['row_gap'];

      ob_start();
      ?>
      [container] .wcpt-header > .wcpt-filter-row {
        margin: <?php echo $row_gap; ?>px 0;
      }
      <?php
      $division_style_string .= ' ' . ob_get_clean() . ' ';      

      unset( $style_division[$header_selector]['row_gap'] );
    }
  }

  // dropdown heading
  // make dropdown heading retain hover props even when dropdown menu is being hovered
  $dropdown_heading_selector = '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter > .wcpt-filter-heading';

  $dropdown_heading_active_selector = 'body [container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter.wcpt-filter--active > .wcpt-filter-heading';

  $dropdown_heading_hover_selector = '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter:hover > .wcpt-filter-heading';
  $dropdown_heading_open_selector = '[container] .wcpt-header.wcpt-navigation .wcpt-dropdown.wcpt-filter.wcpt-open > .wcpt-filter-heading';


  if( ! empty( $style_division[$dropdown_heading_selector] ) ){
    $props = array( 'color', 'background-color', 'border-color' );

    // apply :active rules to .wcpt-filter--open as well
    foreach( $props as $prop ){
      if( ! empty( $style_division[$dropdown_heading_selector][$prop .':active'] ) ){
        $val = $style_division[$dropdown_heading_selector][$prop .':active'];

        // add the prop to dropdown active > heading
        if( empty( $style_division[$dropdown_heading_active_selector] ) ){
          $style_division[$dropdown_heading_active_selector] = array();
        }

        $style_division[$dropdown_heading_active_selector][$prop] = $val;
        unset( $style_division[$dropdown_heading_selector][$prop .':active'] );        
      }
    }

    // apply :hover rules to .wcpt-open as well
    foreach( $props as $prop ){
      if( ! empty( $style_division[$dropdown_heading_selector][$prop .':hover'] ) ){
        $val = $style_division[$dropdown_heading_selector][$prop .':hover'];

        // add the prop to dropdown hover > heading
        if( empty( $style_division[$dropdown_heading_hover_selector] ) ){
          $style_division[$dropdown_heading_hover_selector] = array();
        }

        $style_division[$dropdown_heading_hover_selector][$prop] = $val;

        // add the prop to dropdown open > heading
        if( empty( $style_division[$dropdown_heading_open_selector] ) ){
          $style_division[$dropdown_heading_open_selector] = array();
        }

        $style_division[$dropdown_heading_open_selector][$prop] = $val;
      }
    }    
  }

  return array(
    'style_division' => $style_division,
    'division_style_string'=> $division_style_string
  );
}

?>
