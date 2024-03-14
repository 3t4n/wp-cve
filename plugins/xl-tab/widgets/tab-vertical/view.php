<?php
$align = $settings['tpos'];
switch ($settings['tmpl']) {
    case "one":
        $cls = 'xld-tab1 ';
        break;

    case "two":
        $cls = 'xld-tab2 ';
        break; 
        
    case "three":
        $cls = 'xld-tab3 ';
        break;

    case "four":
        $cls = 'xld-tab4 ';
        break;

    default:
}
$tabti = '';
$tabct = '';
foreach ($settings['tabs'] as $a){

    $icon = $a['icon']['value']? '<i class="tbicon '.$a['icon']['value'].'"></i>' : '';
    $title = $a['title']? '<span class="title">'.$a['title'].'</span>' : '';
    $sub = $a['sub']? '<p class="sub">'.$a['sub'].'</p>' : '';
    $tabti.= '<li><div class="tbinr"><a href="#">'.$icon.$title.$sub.'</a></div></li>';
    $tabct.= $this->icon_image($a);
}

 ?>

<div class="xlvtab1 <?php echo $cls.$align;?>">

    <ul class="tabs">
        <?php echo $tabti;?>
    </ul> <!-- / tabs -->
    <div class="tab_content">
       <?php echo $tabct;?>
    </div> 
 
</div> <!-- / tab -->
