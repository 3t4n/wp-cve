<?php

add_action('admin_menu','nd_spt_add_settings_menu_orders');
function nd_spt_add_settings_menu_orders(){

  add_submenu_page( 'nd-sports-booking-settings','Orders', __('All Bookings','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings-orders', 'nd_spt_settings_menu_orders' );

  //custom hook
  do_action("nd_spt_add_menu_page_after_order");

}


function nd_spt_settings_menu_orders() {

?>

  
  <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25 ">

    <?php nd_spt_get_table_orders(); ?> 

  </div>

<?php } 




function nd_spt_get_table_orders(){

  //START if
  if ( isset($_POST['edit_order_id']) OR isset($_POST['nd_spt_order_id'])  ) {

    include realpath(dirname( __FILE__ ).'/include/edit.php');

  }elseif ( isset($_POST['delete_order_id']) OR isset($_POST['nd_spt_delete_order_id']) ){

    include realpath(dirname( __FILE__ ).'/include/delete.php');
  
  }else{

    include realpath(dirname( __FILE__ ).'/include/orders.php');

  }
  //END if

  $nd_spt_allowed_html = [
    'div'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'p'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'a'      => [
      'id' => [],
      'class' => [],
      'style' => [],
      'href' => [],
      'target' => [],
    ],
    'ul'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'li'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'table'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'tbody'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'tr'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'td'      => [
      'id' => [],
      'class' => [],
      'style' => [],
      'width' => [],
    ],
    'strong'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'button'      => [
      'type' => [],
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'span'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'style'      => [],
    'br'      => [],
    'u'      => [],
    'form'      => [
      'method' => [],
      'id' => [],
      'class' => [],
      'style' => [],
      'action' => [],
    ],
    'img'      => [
      'id' => [],
      'class' => [],
      'style' => [],
      'width' => [],
      'src' => [],
    ],
    'h1'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'h3'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'h4'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'input'      => [
      'name' => [],
      'type' => [],
      'value' => [],
      'id' => [],
      'class' => [],
      'style' => [],
      'readonly' => [],
    ],
    'select'      => [
      'name' => [],
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'option'      => [
      'selected' => [],
      'value' => [],
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'label'      => [
      'id' => [],
      'class' => [],
      'style' => [],
    ],
    'textarea'      => [
      'rows' => [],
      'name' => [],
      'id' => [],
      'class' => [],
      'style' => [],
    ],      
  ];

  echo wp_kses( $nd_spt_result, $nd_spt_allowed_html );

}

include realpath(dirname( __FILE__ ).'/include/add.php');

