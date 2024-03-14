<?php

$id = 'd-'.substr( $this->get_id_int(), 0, 3 );
$icon = $settings['icon']['value']? '<i class="tbxicon actikn '.$settings['icon']['value'].'"></i>' : '';
$inacon = $settings['iicon']['value']? '<i class="tbxicon inactikn '.$settings['iicon']['value'].'"></i>' : '';

$options = [
    'id' => $id,
];
$cls = '';
switch ($settings['tmpl']) {
    case "one":
        $cls = 'xld-acdn1 ';
        break;

    case "two":
        $cls = 'xld-acdn2 ';
        break;

    case "three":
        $cls = 'xld-acdn3 ';
        break;

    default: 
}

$cls = $cls.' '.$settings['tmplp'].' ';
$tabti = '';
foreach ($settings['tabs'] as $a){

    $title = $a['title']? '<div class="xltbhd">'.$a['title'].$icon.$inacon.'</div>' : '';
    $content = $this->icon_image($a);
    $tabti.= '<li>'.$title.$content.'</li>';
    
}
?>

 <?php echo '<div class="xldacdn '.$cls.$settings['ipos'].'" data-xld =\''.wp_json_encode($options).'\'>';?>

  <ul class="accordion <?php echo $id;?>">
    <?php echo $tabti;?>
  </ul>
</div>