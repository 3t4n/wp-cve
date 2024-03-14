<?php
$align = ' '.$settings['inpalign'].' '.$settings['scroll'];
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

    default:
}

$outtitle = '';
$tabct = '';

foreach ($settings['tabs'] as $a){

    $icon = $a['icon']['value']? '<i class="tbicon '.$a['icon']['value'].'"></i>' : '';
    $title = $a['title']? '<span>'.$a['title'].'</span>' : '';
    $outtitle .= $icon || $title? '<li><div class="inrtab"><div>'.$icon.$title.'</div></div></li>' : '';
    $tabct.= '<div class="tab-content">'.$this->icon_image($a).'</div>';
}

?>

<div class="xl-tab <?php echo $settings['tmpl'].$align ;?>">
	<ul class="tab-area">
    <?php echo $outtitle;?>
	</ul>
	<div class="tab-wrap">
    <?php echo $tabct;?>
	</div>
</div>