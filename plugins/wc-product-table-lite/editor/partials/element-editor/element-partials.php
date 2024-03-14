<?php
// locate and include partials - nav + cell template + heading content
$partial_names = array_diff( scandir( __DIR__ . '/partials' ), array( '..', '.', '.DS_Store' ) );

$partials = array();
foreach( $partial_names as $partial_name ){
  $partials[] = array(
    'name' => $partial_name,
    'location' => 'partials/' . $partial_name,
  );
}

$partials = apply_filters( 'wcpt_partials_array', $partials );

foreach( $partials as $partial ){
  if( substr($partial['name'], -4) == '.php' ){
    echo '<script type="text/template" data-wcpt-partial="'. substr( $partial['name'], 0, -4 ) .'">';
    if( 
      'add' != substr ( $partial['name'] , 0 , 3 ) ||
      'add_selected_to_cart.php' == $partial['name'] 
    ){
      $x1 = explode( '__', substr( $partial['name'], 0, -4 ) );
      $elm_name = ucwords( implode( ' ', explode( '_', $x1[0] ) ) );

      switch ($elm_name) {
      case 'Apply Reset':
          $elm_name = 'Apply / Reset';
          break;

      case 'Html':
        $elm_name = 'HTML';
        break;          

      case 'Download Csv':
        $elm_name = 'Download CSV';
        break;                  
      }

      echo '<h2>Edit element: \''. $elm_name .'\'</h2>';
    }
    include( apply_filters( 'wcpt_partial', $partial['location'], $partial['name'] ) );
    echo '</script>';
  }
}
?>
