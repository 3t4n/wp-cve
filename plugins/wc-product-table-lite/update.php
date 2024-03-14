<?php 
// update table data based on current version
// also see: 'global settings for plugin'
add_filter( 'wcpt_data', 'wcpt_update_table_data', 10, 1 );
function wcpt_update_table_data( $data ){
  // data up to date
  if(
    ! empty( $data['version'] ) &&
    $data['version'] === WCPT_VERSION 
  ){
    return $data;
  }

  // ensure version number
  if( empty( $data['version'] ) ){ // <= 1.9.0
    $data['version'] = '1.8.0';
  }  

  // backup current data
  if( ! get_post_meta( $data['id'], 'wcpt_data_' . $data['version'], true ) ){
    update_post_meta( $data['id'], 'wcpt_data_' . $data['version'], addslashes( json_encode($data) ) );
  }else{
    $count = 0;

    while( ++$count ){
      $name = 'wcpt_data_' . $data['version'] . '_' . $count;
      if( ! get_post_meta( $data['id'], $name, true ) ){
        update_post_meta( $data['id'], $name, addslashes( json_encode($data) ) );
        break;
      }
    }
  }

  // update to 1.9.0
  if( version_compare( $data['version'], '1.9.0', '<' ) ){

    // nav: search filter
    $searches = wcpt_get_nav_elms_ref( 'search', $data );

    foreach( $searches as &$search ){
      $search['attributes'] = array();
      if( $search['custom_fields'] && gettype( $search['custom_fields'] ) === 'string' ){
        $search['custom_fields'] =  array_map( 'trim', preg_split( '/\r\n|\r|\n/', $search['custom_fields'] ) );        
      }

      if( gettype( $search['target'] ) === 'string' ){
        $target = array();

        if( ! empty( $search['target'] ) ){
          foreach( array( 'title', 'content', 'custom_field' ) as $field ){
            if( FALSE !== strrpos( $search['target'], $field ) ){
              $target[] = $field;
            }
          }
  
        }else{
          $target = array( 'title', 'content' );
        }
  
        $search['target'] = $target;
      }

    }

  }

  // update to 2.0.0
  if( version_compare( $data['version'], '2.0.0', '<' ) ){
    $attr_elements = wcpt_get_col_elms_ref( 'attribute', $data );

    foreach( $attr_elements as &$element ){
      if( ! empty( $element['filter_link_term'] ) ){
        $element['click_action'] = 'trigger_filter';
        unset( $element['filter_link_term'] );
      }
    }

    unset( $attr_elements );
    unset( $element );

  }

  // update to 2.1.0
  if( version_compare( $data['version'], '2.1.0', '<' ) ){

    // ensure date descending
    // -- query
    if( 
      ! empty( $data['query'] ) &&
      ! empty( $data['query']['orderby'] ) &&
      $data['query']['orderby'] === 'date'
    ){
      $data['query']['order'] = 'DESC';
    }

    // nav sort by element
    $sort_by_elements = wcpt_get_nav_elms_ref( 'sort_by', $data );
    foreach( $sort_by_elements as &$element ){
      foreach( $element['dropdown_options'] as &$option ){
        if( $option['orderby'] === 'date' ){
          $option['order'] === 'DESC';
        }
      }
    }

    unset( $sort_by_elements );
    unset( $element );        

  }

  // update to 2.2.0
  if( version_compare( $data['version'], '2.2.0', '<' ) ){
    
    // Availability element, adding 'In stock, managed' message
    $av_elements = wcpt_get_col_elms_ref( 'availability', $data );

    foreach( $av_elements as &$element ){
      if( 
        ! empty( $element['in_stock_message'] ) &&
        empty( $element['in_stock_managed_message'] )
      ){
        $element['in_stock_managed_message'] = $element['in_stock_message'];
      }
    }

    unset( $av_elements );
    unset( $element );

    // ToolTip style fix (col & nav)
    $tooltip_elements__col = wcpt_get_col_elms_ref( array('tooltip'), $data );
    $tooltip_elements__nav = wcpt_get_nav_elms_ref( 'tooltip__nav', $data );
    $tooltip_elements = array_merge( $tooltip_elements__col, $tooltip_elements__nav );

    foreach( $tooltip_elements as &$element ){
      if( 
        ! empty( $element['style'] ) &&
        ! empty( $element['style']['[id] > .wcpt-tooltip-content'] )
      ){
        $element['style']['[id] > .wcpt-tooltip-content-wrapper > .wcpt-tooltip-content'] = $element['style']['[id] > .wcpt-tooltip-content'];
        unset( $element['style']['[id] > .wcpt-tooltip-content'] );
      }
    }

    unset( $tooltip_elements );
    unset( $element );

    // Dimension variable_switch property added
    $dimension_elements = wcpt_get_col_elms_ref( array('dimension'), $data );

    foreach( $dimension_elements as &$element ){
      if( ! isset( $element['variable_switch'] ) ){
        $element['variable_switch'] = true;
      }
    }

    unset( $dimension_elements );
    unset( $element );

    // Stock variable_switch property added
    $stock_elements = wcpt_get_col_elms_ref( array('stock'), $data );

    foreach( $stock_elements as &$element ){
      if( ! isset( $element['variable_switch'] ) ){
        $element['variable_switch'] = true;
      }
    }

    unset( $stock_elements );
    unset( $element );    

    // Nav filter elms dropdown & row html class changes
    $nav_elements = wcpt_get_nav_elms_ref( false, $data );

    foreach( $nav_elements as &$element ){
      if( 
        ! in_array( $element['type'], array( 
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
        ) ) ||
        empty( $element['style'] )
      ){
        continue;
      }

      // dropdown heading
      if( ! empty( $element['style']['[id]'] ) ){
        $element['style']['.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-filter-heading'] = $element['style']['[id]'];
        unset( $element['style']['[id]'] );
      }

      // dropdown menu
      if( ! empty( $element['style']['[id] > .wcpt-dropdown-menu'] ) ){
        $element['style']['.wcpt-navigation:not(.wcpt-left-sidebar) [id].wcpt-dropdown.wcpt-filter > .wcpt-dropdown-menu'] = $element['style']['[id] > .wcpt-dropdown-menu'];
        unset( $element['style']['[id] > .wcpt-dropdown-menu'] );
      }

    }

    unset( $nav_elements );
    unset( $element );    

  }


  // update to 2.5.1
  if( version_compare( $data['version'], '2.5.1', '<' ) ){

    // Title link modified
    $title_elements = wcpt_get_col_elms_ref( array('title'), $data );

    foreach( $title_elements as &$element ){
      if( isset( $element['product_link_enabled'] ) ){
        $element['link'] = 'product_page';
      }else{
        $element['link'] = '';
      }
    }

    unset( $title_elements );
    unset( $element );

  }

  // update to 3.1.0
  if( version_compare( $data['version'], '3.1.0', '<' ) ){

    // Short description gets 'generate' option
    $short_description_elements = wcpt_get_col_elms_ref( array('short_description'), $data );

    foreach( $short_description_elements as &$element ){
      if( ! isset( $element['generate'] ) ){
        $element['generate'] = true;
      }
    }

    unset( $short_description_elements );
    unset( $element );

    // Product image element 'include_gallery' needs to be 'true'
    $product_image_elements = wcpt_get_col_elms_ref( array('product_image'), $data );

    foreach( $product_image_elements as &$element ){
      if( ! isset( $element['include_gallery'] ) ){
        $element['include_gallery'] = true;
      }
    }

    unset( $product_image_elements );
    unset( $element );
  }

  $data['version'] = WCPT_VERSION;
  $data['timestamp'] = time();
  
  // update meta
  update_post_meta( $data['id'], 'wcpt_data', addslashes( json_encode($data) ) );  

  return $data;
}

// returns references for specific nav filter type 
function wcpt_get_nav_elms_ref( $type= false, &$data = false ){

  if( ! $data ){
    $data = wcpt_get_table_data();
  }

  $navigation =& $data['navigation']['laptop'];
  $rows = array( &$navigation['left_sidebar'][0] ); // single BE row

  foreach( $navigation['header']['rows'] as &$header_row ){
    foreach( $header_row['columns'] as &$column ){
      $rows[] =& $column['template'][0]; // append header BE rows
    }
  }

  // iterate combined rows from sidebar and header
  $elements = array();
  foreach( $rows as &$row ){
    if( ! empty( $row['elements'] ) ){
      foreach( $row['elements'] as &$element ){
        if( 
          $type &&
          $type !== $element['type']
        ){
          continue;
        }
  
        $elements[] =& $element;
      }
    }
  }

  return $elements;
}

// returns references for column elements of a type 
function wcpt_get_col_elms_ref( $types, &$data ){
  if( ! $types ) return false;

  if( ! is_array( $types ) ) $types = array( $types );

  $elements = array();
  foreach( $data['columns'] as &$device ){
    if( empty( $device ) ){ 
      continue;
    }

    foreach( $device as &$column ){
      foreach( $column['cell']['template'] as &$template_row ){
        foreach( $template_row['elements'] as &$element ){
          if( ! in_array( $element['type'], $types ) ){
            continue;
          }

          $elements[] =& $element;
        }
      }
    }
  }

  return $elements;
}

// global settings for plugin
function wcpt_update_settings_data(){
  $data = json_decode( stripslashes( get_option( 'wcpt_settings', '' ) ), true );

  // ensure version number
  // version was not stored in settings before 1.9.0
  if( empty( $data['version'] ) ){
    $data['version'] = '1.8.0';
  }

  // skip update
  if(
    ! empty( $data['version'] ) &&
    $data['version'] === WCPT_VERSION 
  ){
    return FALSE;
  }

  // backup current data
  if( ! get_option( 'wcpt_settings_' . $data['version'] ) ){
    update_option( 'wcpt_settings_' . $data['version'], addslashes( json_encode($data) ) );
  }else{
    $count = 0;

    while( ++$count ){
      $name = 'wcpt_settings_' . $data['version'] . '_' . $count;
      if( ! get_option( $name ) ){
        update_option( $name, addslashes( json_encode($data) ) );
        break;
      }
    }
  }

  // update to 1.9.0
  if( version_compare( $data['version'], '1.9.0', '<' ) ){

    // provide search settings exists
    if( empty( $data['search'] ) ){
      $data['search'] = $GLOBALS['WCPT_SEARCH_DATA'];
    }

  }

  // update to 2.0.0
  if( version_compare( $data['version'], '2.0.0', '<' ) ){

    // provide search settings exists (repeated from 1.9.0)
    if( empty( $data['search'] ) ){
      $data['search'] = $GLOBALS['WCPT_SEARCH_DATA'];
    }    

    // search override settings
    if( empty( $data['search']['override_settings'] ) ){
      $data['search']['override_settings'] = array(
        'target' => array( 'title', 'content' )
      );
    }

    // checkbox trigger
    if( empty( $data['checkbox_trigger'] ) ){
      $data['checkbox_trigger'] = $GLOBALS['WCPT_CHECKBOX_TRIGGER_DATA'];
    }

  }

  // update to 2.3.0
  if( version_compare( $data['version'], '2.3.0', '<' ) ){

    // ensure sessions table is regularly trimmed 
    if( ! wp_get_scheduled_event('wcpt_cleanup_sessions') ){
      wp_schedule_event( time() + ( 6 * HOUR_IN_SECONDS ), 'twicedaily', 'wcpt_cleanup_sessions' );
    }

  }

  $data['version'] = WCPT_VERSION;
  $data['timestamp'] = time();

  // update meta
  update_option( 'wcpt_settings', addslashes( json_encode($data) ) );

  return $data;
}

add_action('init', 'wcpt_clear_version_backup');
function wcpt_clear_version_backup(){
  if( ! empty( $_GET['wcpt_ceb'] ) ){
    global $wpdb;

    $wpdb->query( 
      "DELETE FROM $wpdb->postmeta
      WHERE meta_key LIKE '%wcpt_data_%'"
    );    
  }
}