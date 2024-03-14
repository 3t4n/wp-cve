<?php

 $id = substr( $this->get_id_int(), 0, 3 );
 $options = [
    'id' => $id,
 ];

switch ($settings['tmpl']) {
    case "one":
        $cls = ' xld-tab1';
        break;

    case "two":
        $cls = ' xld-tab2';
        break; 

    default:
}

 $first=0;foreach ($settings['tabs'] as $a){ $first++;

    if($first==1){
        $icon = $a['icon']['value'] ? '<i class="tbicon '.$a['icon']['value'].'"></i>' : '';
        $mtitle= $a['title'] || $icon? '<li class="year active xhndlr"><a href="#">'.$a['title'].' '.$icon.'</a></li>' : '';
        $mdesc= '<div id="month">'.$this->icon_image($a).'</div>';

    } elseif ($first==2) {
        $icon = $a['icon']['value'] ? '<i class="tbicon '.$a['icon']['value'].'"></i>' : '';
        $ytitle = $a['title'] || $icon? '<li class="month xhndlr"><a href="#">'.$icon.' '.$a['title'].'</a></li>' : '';
        $ydesc= '<div id="year">'.$this->icon_image($a).'</div>';
 
    }

} ?>

<?php echo '<div class="xldswitcher d-'.$id.$cls.'" data-xld =\''.wp_json_encode($options).'\'>';?>
<ul>
    
    <?php echo $mtitle;?>
    <li>
        <label class="switch off">
            <span class="slider"></span>
        </label>
    </li>
    <?php echo $ytitle;?>
</ul>

<div class="tabed-content">
    <?php echo $mdesc.$ydesc;?>     
</div>
</div>

<style type="text/css">    

.xldswitcher ul {
    padding-left: 0;
    list-style: none;
}
.xldswitcher ul>li {
    display: inline-block;
}
.xldswitcher ul li a {
    font-size: 18px;
    font-weight: 600;
    color: #323232;
    padding-left: 10px;
    padding-right: 10px;
    display: block;
}
.xldswitcher ul li.active a {
    color: #989898;
}
.xldswitcher ul li.year .tbicon{
    padding-left: 5px;
}
.xldswitcher ul li.month .tbicon{
    padding-right: 5px;
}

.xldswitcher .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    vertical-align: middle;
}
.xldswitcher .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    -webkit-transition: .4s;
    transition: .4s;
}
.xld-tab2 .switch.on .slider {
    background: red;
}
.xld-tab2 .switch.off .slider {
    background: yellow;
}
.xld-tab2 .slider:before {
    background-color: green;
}

.xld-tab1 .switch.on .slider {
    background: black;
}
.xld-tab1 .switch .slider {
    border:2px solid black;
}
.xld-tab1 .switch.on .slider:before {
    background: white;
}
.xld-tab1 .slider:before {
    border:2px solid black;
}

.xldswitcher .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    -webkit-transition: .4s;
    transition: .4s;
}
.xldswitcher .switch.off .slider:before {
    -webkit-transform: translateX(26px);
    transform: translateX(26px);
}


</style>