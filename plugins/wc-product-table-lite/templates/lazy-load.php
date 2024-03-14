<?php
  $data = wcpt_get_table_data();
  $sc_attrs = $data['query']['sc_attrs'];
  unset( $sc_attrs['lazy_load'] );
  if( count( $sc_attrs ) ){
    $sc_attrs = ' data-wcpt-sc-attrs="'. esc_attr( json_encode( $sc_attrs ) ) .'" ';
  }else{
    $sc_attrs = '';
  }
?>
<div 
  class="wcpt-lazy-load" 
  data-wcpt-table-id="<?php echo $data['id']; ?>" 
  <?php echo $sc_attrs; ?>
>
  <div class="wcpt-lazy-load-animation"></div>
</div>
