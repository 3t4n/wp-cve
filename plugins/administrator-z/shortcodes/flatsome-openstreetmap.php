<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_map');
add_shortcode('adminz_map', 'adminz_map_function');
function adminz_map(){
    add_ux_builder_shortcode('adminz_map', array(
        'type' => 'container',
        'allow' => array( 'adminz_map-item' ),
        'name'      => __('Open Street Map'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'map' . '.svg',
        'options' => array(
            'map_config'=> [
                'type' => 'group',
                'heading'=> 'Map config',
                'options'=> [
                    'layout' => array(
                        'type' => 'select',
                        'heading' => __( 'Layout','administrator-z' ),                        
                        'options'=> [
                            '0'=> "Default",
                            '1'=> "Right list",
                            '2'=> "Absolute Right list",
                            '3'=> "Left List",
                        ],
                        'default'=>'1'
                    ),
                    'mapzoom' => array(
                        'type' => 'slider',
                        'heading' => __( 'Map zoom','administrator-z' ),
                        'min' => 1,
                        'max'=> 18,
                        'default' => 5,
                    ),
                    'mapheight' => array(
                        'type' => 'slider',
                        'unit' => 'px',
                        'heading' => __( 'Map height','administrator-z' ),                
                        'default' => 500,
                        'max' => 1000,
                        'min'=> 200,
                        'step' => 10
                    ),
                    'view_map'=> array(
                        'type' => 'checkbox',
                        'heading' => __("View Location url",'administrator-z'),
                        'default' => 'true'
                    ),
                ]
            ],
            'search_config'=>[
                'type' => 'group',
                'heading'=> 'Form config',
                'options'=> [
                    'search_form' => array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show search form','administrator-z' ),
                        'default'=> 'true'
                    ),
                    'list_items' => array(
                        'type' => 'checkbox',
                        'heading' => __( 'Show list items','administrator-z' ),
                        'default'=> 'true'
                    ),
                    'form_title' => array(
                        'type' => 'textfield',
                        'heading' => 'Form title',
                        'default' => '',                        
                    ),
                    'list_items_title' => array(
                        'type' => 'textfield',
                        'heading' => __('List Markers title','administrator-z'),
                        'default' => '',                        
                    ),
                    'form_col' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Form column desktop','administrator-z' ),
                        'default' => '4'
                    ),
                    'form_col_mobile' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Form column mobile','administrator-z' ),
                        'default' => '12'
                    ),
                    'item_col' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Items column desktop','administrator-z' ),
                        'default' => '1'
                    ),
                    'item_col_mobile' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Items column mobile','administrator-z' ),
                        'default' => '1'
                    ),
                    'field1' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Field 1 title','administrator-z' ),
                        'default' => 'Country'
                    ),
                    'field2' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Field 2 title','administrator-z' ),
                        'default' => 'City'
                    ),
                    'alltext' => array(
                        'type' => 'textfield',
                        'heading' => __( 'All place holder text','administrator-z' ),
                        'default' => 'All'
                    ),
                    'searchbutton' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Search button text','administrator-z' ),
                        'default' => 'Search'
                    ),
                ]
            ],                        
            
            'marker_config'=> array(
                'type' => 'group',
                'heading'=> 'Marker config',
                'options'=> array(
                    'markerzoom' => array(
                        'type' => 'slider',
                        'heading' => __( 'Marker zoom','administrator-z' ),                
                        'default' => 9,
                        'min'=>1,
                        'max'=> 18
                    ),
                    'use_gg_markericon'=> array(
                        'type' => 'checkbox',
                        'heading' => __("User GG icon",'administrator-z'),
                        'default' => ''
                    ),
                    'markericon' => array(
                        'type' => 'image',
                        'heading' => __( 'Marker icon','administrator-z' ),     
                        'description'=> 'Default size: 25x41px'           
                    ),
                    'markericonshadow' => array(
                        'type' => 'image',
                        'heading' => __( 'Marker shadow','administrator-z' ),   
                        'description'=> 'Default size: 41x41px'             
                    ),
                )
            ),
            'other_config'=> array(
                'type'=> 'group',
                'heading'=> 'Other config',
                'options'=> array(
                    'address_text' => array(
                        'type' => 'textfield',
                        'heading' => __( 'Address','administrator-z' ),
                        'default' => __( 'Address','administrator-z' ),
                    ),
                    'phone_number_text'=>array(
                        'type' => 'textfield',
                        'heading' => __( 'Phone number','administrator-z' ),
                        'default' => __( 'Phone number','administrator-z' ),
                    ),
                )
            )
        ),
    ));
}
add_action('ux_builder_setup', 'adminz_map_item');
function adminz_map_item(){
    add_ux_builder_shortcode('adminz_map-item', array(
        'name'      => __('Map marker','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'map' . '.svg',
        'info'      => '{{ title }}',
        'hidden' => true,
        'options' => array(
            'image' => array(
                'type'       => 'image',
                'heading'    => __('Item thumbnail','administrator-z'),
                'default' => '',
            ),
            'marker' => array(
                'type'       => 'image',
                'heading'    => __('Item marker','administrator-z'),
                'default' => '',
                'description'=> 'Default size: 25x41px | Shadow inherited from parent map'
            ),
            'title' => array(
                'type' => 'textfield',
                'heading' => __( 'Title', 'administrator-z'),
            ),
            'address' => array(
                'type' => 'textfield',
                'heading' => __( 'Address', 'administrator-z'),
                'default' => __( '' ), 
            ),
            'phone_number'=>array(
                'type' => 'textfield',
                'heading' => __( 'Phone number', 'administrator-z'),
                'default' => __( '' ), 
            ),
            'description'=>array(
                'type' => 'textarea',
                'heading' => __( 'More descriptions', 'administrator-z'),
                'default' => __( '' ), 
            ),
            'address_opt_1' => array(
                'type' => 'textfield',
                'heading' => __( 'Filter option 1', 'administrator-z'),
                'default' => __( '' ),                
            ),
            'address_opt_2' => array(
                'type' => 'textfield',
                'heading' => __( 'Filter option 2', 'administrator-z'),
                'default' => __( '' ),                
            ),
            'latlong' => array(
                'type' => 'textfield',
                'heading' => __( 'Lat Long','administrator-z' ),
            ),
            'marker_link'=> array(
                'type' => 'textfield',
                'heading' => __( 'Link to map','administrator-z' ),
            ),
            'popup' => array(
                'type'       => 'checkbox',
                'heading'    => __('Open popup','administrator-z'),
                'default' => '',
            ),
            'flyto' => array(
                'type'       => 'checkbox',
                'heading'    => __('Fly to','administrator-z'),
                'default' => '',
            ),
        ),
    ));
}
add_shortcode( 'adminz_map-item','adminz_map_item_shortcode');

function adminz_map_function($atts, $content = null ) {   
    extract(shortcode_atts(array(
        'id'=> rand(),
        'layout'=> '1',
        'mapzoom'    => '5',
        'mapheight' => '500',
        'mapstyle'=> 'OpenStreetMap_Mapnik',
        'maptilelayer'=> '',
        'search_form' => 'true',
        'list_items'=> 'true',
        'list_items_title'=> '',
        'form_title'=>'',
        'form_col'=>4,
        'form_col_mobile'=>12,
        'item_col'=>1,
        'item_col_mobile'=>1,
        'item_hover'=>'true',
        'alltext'=> 'All',
        'field1'=> 'Country',
        'field2'=> 'City',
        'searchbutton'=> 'Search',
        'markerzoom'=> '9',
        'use_gg_markericon'=>'',
        'markericon'=>'',
        'markericonshadow'=>'',
        'debug' => '',
        'view_map'=> 'true',
        'address_text'=> 'Address',
        'phone_number_text'=> 'Phone number',

    ), $atts));            
    $adminz_marker_items = [];
    $content = str_replace(["<div>","</div>"],["",""],$content);

    $jsoncode = do_shortcode( $content);    
    if(preg_match_all('/{(.*)}/', $jsoncode, $matches)){
        $matches= $matches[0];
        if(!empty($matches) and is_array($matches)){
            foreach ($matches as $key => $value) {
                $adminz_marker_items[] = (array) json_decode( $value);
            }
        }
    }  


    $address1_arr = [];
    $address2_arr = [];
    $lat_center = 0;
    $long_center = 0;
    $count = count($adminz_marker_items);    
    $itemslisthtml = '';
    $markerjshtml = '';
    $clickitemhtml = '';    
    if(!empty($adminz_marker_items) and is_array($adminz_marker_items)){
        foreach ($adminz_marker_items as $key => $value) {            
            if(!isset($value['title'])){$value['title'] = ""; }
            if(!isset($value['address'])){$value['address'] = ""; }
            if(!isset($value['phone_number'])){$value['phone_number'] = ""; }            
            if(!isset($value['description'])){$value['description'] = ""; }

            if(!isset($value['address_opt_1'])){$value['address_opt_1'] = ""; }
            if(!isset($value['address_opt_2'])){$value['address_opt_2'] = ""; }

            if(!isset($value['latlong'])){$value['latlong'] = "0,0"; }
            if(!isset($value['marker_link'])){$value['marker_link'] = ""; }



            $value['address'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '</br>', $value['address']);
            $value['description'] = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '</br>', $value['description']);

            $marker_content = "";
            if($value['address']){
                $marker_content .= "<div><strong>".$address_text."</strong>: ". $value['address']."</div>";
            }
            if($value['phone_number']){
                $marker_content .= "<div><strong>".$phone_number_text."</strong>: ". $value['phone_number']."</div>";
            }
            if($value['description']){
                $marker_content .= "<div>".$value['description']."</div>";
            }


            $address1_arr[] = $value['address_opt_1'];            
            $address2_arr[$value['address_opt_1']][] = $value['address_opt_2'];

            

            $latlong = explode(",",$value['latlong']);

            if($latlong[0] and $latlong[1]){
                $lat_center += $latlong[0];
                $long_center += $latlong[1];
            }
            if($view_map == true){
                $marker_link = $value['marker_link']? $value['marker_link'] : "https://www.google.com/maps/place/".urlencode($value['latlong'])."/@".$value['latlong'].",".$markerzoom."z";
                $view_map = "<div><a target=\'_blank\' href=".$marker_link."\'><small>". _x("View Location",'menu locations')."</small></a></div>";
            }else{
                $view_map = "";
            }
            
            ob_start();
            ?>
            <div 
            data-marker="<?php echo $key; ?><?php echo $id; ?>" 
            data-title="<?php echo $value['title']; ?>"
            data-address="<?php echo $value['address']; ?>"
            data-address_opt_1="<?php echo $value['address_opt_1']; ?>"
            data-address_opt_2="<?php echo $value['address_opt_2']; ?>"
            data-latlong="[<?php echo $value['latlong']; ?>]"
            class="
                adminz_marker_list_item col 
                large-<?php echo 12/$item_col;?> 
                small-<?php echo 12/$item_col_mobile;?>
                <?php echo $value['flyto'] == "true" ? "current" : ""; ?>
                "
            >
                <div class="col-inner">
                    <div style="display: flex; align-items: flex-start;">
                        <?php            
                        if(isset($value['image'])){
                            echo wp_get_attachment_image( $value['image'], 'thumbnail',"",['class'=>"img"] ); 
                        }
                        ?>
                        <div>
                            <div class="item_title"><strong><?php echo $value['title'];?></strong></div>
                            <small><?php echo $marker_content; ?></small>
                            <?php echo $view_map; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $itemslisthtml .= ob_get_clean();

            ob_start();
            
            if(isset($value['image']) and $imageurl = wp_get_attachment_image_url($value['image'],array(60,60))){ ?>              
                var image<?php echo $key; ?><?php echo $id; ?> = '<div style="margin-bottom: 15px; padding-top: 60px; width: 60px; background-size: contain; background-repeat: no-repeat; margin-right:  15px; min-width: 60px;background-image: url(<?php echo $imageurl;?>);"></div>';
                <?php                
            }else {
                ?>
                var image<?php echo $key; ?><?php echo $id; ?> = '';
                <?php
            }
            ?>
            
            var popuphtml<?php echo $key; ?><?php echo $id; ?> = "<div style='display: flex; align-items: flex-start;'>"+image<?php echo $key; ?><?php echo $id; ?>+"<div><strong><?php echo $value['title']; ?></strong><?php echo $marker_content;?><?php echo $view_map;?></div></div>";
            var icon<?php echo $key; ?><?php echo $id; ?> = {};

            <?php 

            if(isset($value['marker'])){
                $get_markericon = $value['marker'];
            }else{
                $get_markericon = $markericon;
            }
            // var_dump($use_gg_markericon);die;

            if($use_gg_markericon){
                $iconsrc = wp_get_attachment_image_src( $get_markericon );        
                $iconsrc = [
                    plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/images/marker-icon-gg.png',
                    28,
                    39,
                    false
                ];
                $iconshadowsrc = plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/images/marker-shadow.png';
                ?>
                icon<?php echo $key; ?><?php echo $id; ?> = {
                    icon: L.icon({
                        iconUrl: "<?php echo $iconsrc[0];?>",
                        iconSize:  [<?php echo $iconsrc[1];?>, <?php echo $iconsrc[2];?>],
                        iconAnchor:   [<?php echo $iconsrc[1]/2;?>, <?php echo $iconsrc[2];?>],
                        shadowUrl: "<?php echo $iconshadowsrc;?>", 
                        shadowSize:   [41,41],  
                        shadowAnchor: [<?php echo $iconsrc[1]/2;?>, 41],  
                        popupAnchor:  [0, -<?php echo $iconsrc[2];?>]
                    })
                };
                <?php 
            }

            if($get_markericon){
                $iconsrc = wp_get_attachment_image_src( $get_markericon );                
                $iconshadowsrc = plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/images/marker-shadow.png';
                ?>
                icon<?php echo $key; ?><?php echo $id; ?> = {
                    icon: L.icon({
                        iconUrl: "<?php echo $iconsrc[0];?>",
                        iconSize:  [<?php echo $iconsrc[1];?>, <?php echo $iconsrc[2];?>],
                        iconAnchor:   [<?php echo $iconsrc[1]/2;?>, <?php echo $iconsrc[2];?>],
                        shadowUrl: "<?php echo $iconshadowsrc;?>", 
                        shadowSize:   [41,41],  
                        shadowAnchor: [<?php echo $iconsrc[1]/2;?>, 41],  
                        popupAnchor:  [0, -<?php echo $iconsrc[2];?>]
                    })
                };
                <?php                
            }            
            ?>
            var marker<?php echo $key; ?><?php echo $id; ?> = L.marker(
                    [<?php echo $value['latlong'];?>],
                    icon<?php echo $key; ?><?php echo $id; ?>
                )
                .addTo(adminz_map<?php echo $id; ?>)
                .on("click", markerOnclick<?php echo $id; ?>)
                .bindPopup(popuphtml<?php echo $key; ?><?php echo $id; ?>,{minWidth: 200});

            <?php
            if(isset($value['popup']) and $value['popup'] == "true") {
                ?>
                marker<?php echo $key; ?><?php echo $id; ?>.openPopup();
                <?php
            }
            if(isset($value['flyto']) and $value['flyto'] == "true") {
                ?>                
                adminz_map<?php echo $id; ?>.setView(marker<?php echo $key; ?><?php echo $id; ?>._latlng,<?php echo $markerzoom;?>);
                <?php
            }

            $markerjshtml.= ob_get_clean();            
            
            


            $clickitemhtml.= 'if(index == "'.$key.$id.'"){
                marker'.$key.$id.'.openPopup();
                adminz_map'.$id.'.setView(marker'.$key.$id.'._latlng,'.$markerzoom.');
            }';
        }        
    }
    if($count){
        $lat_center = $lat_center/$count;
        $long_center = $long_center/$count;
    }
    
    ob_start();
        ?>
        <div class="col large-<?php echo $form_col ?> small-<?php echo $form_col_mobile ?> ">
            <?php echo $field1; ?>
            <select class="address_opt1 mb-0">
                <option value='All'><?php echo $alltext; ?></option>
                <?php 
                $address1_arr = array_unique($address1_arr);
                foreach ($address1_arr as $key => $value) {
                    echo '<option value="'.$value.'">'.$value.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="col large-<?php echo $form_col ?> small-<?php echo $form_col_mobile ?> ">
            <?php echo $field2; ?>
            <?php 
            $defaultopt1 = "";
            if(isset($address1_arr[0])) {$defaultopt1 = $address1_arr[0];}                    
            ?>
            <select class="address_opt2 mb-0">
                <option data-opt1='All' value='All'><?php echo $alltext; ?></option>
                <?php                 
                foreach ($address2_arr as $key => $value) {
                    if(!empty($value) and is_array($value)){                        
                        $value = array_unique($value);                        
                        foreach ($value as $key2 => $option) {
                            echo '<option data-opt1="'.$key.'" value="'.$option.'">'.$option.'</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
        <div class="col large-<?php echo $form_col ?> small-<?php echo $form_col_mobile ?> ">
            <?php if($form_col!=12): ?>
                <span style="visibility: hidden; display: block;" class="hide-for-small">_</span>
            <?php endif; ?>
            <button class="button primary address_opt_search expand mb-0"><?php echo $searchbutton; ?></button>
        </div>
        <?php 
    $form_html = ob_get_clean();

    if($search_form !== "true"){$form_html = ""; }  

    $col_map = 8;
    $col_items = 4;  
    if($list_items !== "true"){
        $itemslisthtml = ""; 
        $col_map = 12;
    }    


    $map_html = '<div id="adminz_map'.$id.'" class="map_div_check"></div>';
    
    



    if(isset( $_POST['ux_builder_action'] )){
        $map_html = '<div style="background: #71cedf; height: '.$mapheight.'px; border: 2px dashed #000; display: flex; padding: 20px; color: white; justify-content: center; align-items: center; font-size: 1.5em; "> Map </div>';   
        $itemslisthtml = $content;
    }else{
        ?>
        <link rel="stylesheet" href="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet.css'; ?>" />
        <script src="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet.js'; ?>" id="adminz_leaflet-js" crossorigin=""></script>
        <script src="<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/leaflet/leaflet-providers.js'; ?>" id="adminz_leaflet-providers-js" crossorigin=""></script>
        <?php
    }
    ob_start();
    ?>    
    <div class="adminz_map adminz_map_wrapper<?php echo $id; ?>">
        <style type="text/css"> 
            .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2{
                position: relative;
            }
            @media(min-width: 850px){
                .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .list-absolute{
                    position: absolute;
                    z-index: 1;
                    top: 0;
                    right: 0;
                    <?php if($itemslisthtml){echo 'background-color: white; '; } ?> 
                    height: 100%;
                }
                .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .list-absolute.zleft{
                    position: static !important;
                }
                .dark .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .list-absolute{
                    background-color: #232323;
                }
                /*.adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .rightinner{
                    padding: 15px;
                }*/
                /*.adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .zleft .rightinner{
                    padding: 0px;
                }*/
            }            
            .adminz_map_wrapper<?php echo $id; ?> #adminz_map<?php echo $id;?> {
                width: 100%;
                height: 60vh;
                max-width: 100%;
                z-index: 1;
            }
            @media (min-width: 850px){
                .adminz_map_wrapper<?php echo $id; ?> #adminz_map<?php echo $id;?> {
                    height: <?php echo $mapheight; ?>px;
                }
            }
            
            /*.adminz_map_wrapper<?php echo $id;?> .row-full-width .row{
                max-width: unset;
            }*/
            .adminz_map_wrapper<?php echo $id; ?> .adminz_marker_list_item{
                display: flex;
                align-items: flex-start;
                cursor: pointer;
                padding-bottom: 15px;
            }
            .adminz_map_wrapper<?php echo $id; ?> .adminz_marker_list_item.hover .col-inner{
                padding:  10px;
                border: 1px solid #8a8a8a29;
                height: 100%;
            }
            .adminz_map_wrapper<?php echo $id; ?> .adminz_marker_list_item.hover:hover .col-inner, 
            .adminz_map_wrapper<?php echo $id; ?> .adminz_marker_list_item.active .col-inner{
                background-color:  #8a8a8a29;
            }
            .adminz_map_wrapper<?php echo $id; ?> .adminz_marker_list_item .img{
                max-width: 20%;
                margin-right: 15px;
            }
            @media (min-width: 850px){
                .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout2 .rightinner, 
                .adminz_map_wrapper<?php echo $id; ?> .adminz_map_layout1 .adminz_marker_list{
                max-height: <?php echo $mapheight ?>px;
                overflow-y: auto;
                overflow-x: hidden;
                }
            }

        </style> 
    <?php
    switch ($layout) {
        case '1':
            // container top search
            ?>
            <div class="adminz_map_layout1 row row-full-width">
                
                <?php echo $form_html; ?>
                <div class="col large-<?php echo $col_map; ?>">
                    <?php echo $map_html ;?>
                </div>
                <?php if($itemslisthtml){ ?>
                    <div class="col large-4">
                        <div class="adminz_marker_list row align-equal">
                            <?php echo $itemslisthtml;  ?>
                        </div>   
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
        case '2':
            // fullwidth collapse 
            ?>
            <div class="adminz_map_layout2 row row-full-width row-collapse">                
                <div class="col large-9">
                    <?php echo $map_html ;?>
                </div>
                <?php if($form_html or $itemslisthtml){ ?>
                    <div class="col large-3 small-12 list-absolute">
                        <div class="col-inner rightinner">                            
                            <?php if($form_html) { ?>
                                <div class="row">
                                    <?php if($form_title){ ?>
                                        <div class="col small-12 ">
                                            <?php echo "<h3 class='mb-0'>".$form_title."</h3>"; ?>
                                        </div>
                                    <?php }?>
                                    <?php echo $form_html; ?>
                                </div>
                            <?php } ?>                            
                            <?php if($itemslisthtml) { ?>                                
                                <div class="adminz_marker_list row align-equal">                        
                                    <?php echo $itemslisthtml;  ?>
                                </div> 
                            <?php } ?> 
                        </div>                             
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
        case '3':
            // absolute left list
            ?>
            <div class="adminz_map_layout2 row">                
                
                <?php if($form_html or $itemslisthtml){ ?>
                    <div class="col large-3 small-12 list-absolute zleft">
                        <div class="rightinner col-inner">                            
                            <?php if($form_html) { ?>
                                <div class="row">
                                    <?php if($form_title){ ?>
                                        <div class="col small-12 ">
                                            <?php echo "<h3 class='mb-0'>".$form_title."</h3>"; ?>
                                        </div>
                                    <?php }?>
                                    <?php echo $form_html; ?>
                                </div>
                            <?php } ?>                            
                            <?php if($itemslisthtml) { ?>                                
                                <div class="adminz_marker_list row align-equal">                        
                                    <?php echo $itemslisthtml;  ?>
                                </div> 
                            <?php } ?> 
                        </div>                             
                    </div>
                <?php } ?>
                <div class="col large-9 small-col-first">
                    <?php echo $map_html ;?>
                </div>
            </div>
            <?php
            break;
        default:
            ?>
            <?php echo $map_html ;?>
            <?php echo do_shortcode('[gap]'); ?>
            <div class="row">
                <?php if($form_html or $itemslisthtml){ ?>
                    <?php if($list_items_title){ ?>
                        <div class="col small-12 ">
                            <?php echo "<h3 class='mb-0>".$list_items_title."</h3>"; ?>
                        </div>
                    <?php }?>
                    <div class="col small-12 large-12">
                        <?php if($form_html) { ?>
                            <div class="row">
                                <?php echo $form_html; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col large-12 small-12">
                        <?php if($itemslisthtml) { ?>
                            <div class=" row align-equal adminz_marker_list">                        
                                <?php echo $itemslisthtml;  ?>
                            </div> 
                        <?php } ?>                            
                    </div>
                <?php } ?>
            </div>
            <?php
            break;
    }
    ?>
    </div>
    <?php
    
    $mainhtml = ob_get_clean();
    ob_start();
    if(!isset($_POST['ux_builder_action'])){
        ?>        
        <script type="text/javascript">
            <?php 
            echo 'var lat_center'.$id.' = '.$lat_center.";" ; 
            echo 'var long_center'.$id.' = '.$long_center.";" ; 
            ?>
            var adminz_map<?php echo $id; ?> = L.map(
                'adminz_map<?php echo $id; ?>',
                {
                    scrollWheelZoom: false ,
                    preferCanvas:true
                }
            );

            adminz_map<?php echo $id; ?>.setView(
                [
                    lat_center<?php echo $id; ?>, 
                    long_center<?php echo $id; ?>
                ], 
                <?php echo $mapzoom;?> 
            );

            var mapstyle<?php echo $id; ?> = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {id: 'mapbox/streets-v11', });
            mapstyle<?php echo $id; ?>.addTo(adminz_map<?php echo $id; ?>);
            
            <?php 

            echo $markerjshtml; 
            ?>
            jQuery(document).ready(function($){

                $(".adminz_marker_list_item").on("click",function(){
                    var index = $(this).data("marker");
                    $(this).siblings(".adminz_marker_list_item").removeClass("current");
                    $(this).addClass('current');

                    <?php echo $clickitemhtml; ?>
                    var ztop = $("#adminz_map<?php echo $id;?>").offset().top;
                    if($("#header").hasClass("has-sticky")){
                        ztop -= $("#header").height();
                    }
                    $([document.documentElement, document.body]).animate({
                        scrollTop: ztop
                    }, 500);
                });

                $(".address_opt1").on("keyup, change",function(){
                    var current_opt1 = $(this).val();                
                    $(this).closest(".adminz_map").find(".address_opt2 option").each(function(){
                        $(this).removeClass("hidden");
                        if(current_opt1 == 'All'){
                            
                        }else{
                            if($(this).data('opt1') !== current_opt1){
                                $(this).addClass("hidden");
                            }
                        }
                        
                    });
                    $(this).closest(".adminz_map").find(".address_opt2 option[data-opt1='"+current_opt1+"']").each(function(){
                        $(".address_opt2").val($(this).val());
                        return false;
                    });
                });

                $(".address_opt2").on("keyup, change",function(){                
                    var current_opt2 = $(this).val();
                    var target_for_opt1 = "";
                    $(this).closest(".adminz_map").find("option").each(function(){                    
                        if($(this).text() == current_opt2){
                            target_for_opt1 = $(this).data('opt1');
                        }
                    });

                    if(target_for_opt1){
                        $(this).closest(".adminz_map").find(".address_opt1 option").each(function(){
                            if($(this).val() == target_for_opt1){
                                $(".address_opt1").val($(this).val());
                            }
                        });
                    }
                });

                $(".address_opt_search").on("click",function(){
                    var opt1 = $(this).closest(".adminz_map").find(".address_opt1").val();
                    var opt2 = $(this).closest(".adminz_map").find(".address_opt2").val();
                    $(this).closest(".adminz_map").find(".adminz_marker_list>div").each(function(){
                        $(this).addClass('hidden');
                        if(opt1=='All'){
                            $(this).removeClass('hidden');
                        }else{
                            if($(this).data('address_opt_1') == opt1 & $(this).data('address_opt_2') == opt2) {
                            $(this).removeClass('hidden');
                        };
                        };                    
                    });
                });
            });
            function markerOnclick<?php echo $id; ?>(e){                        
                    adminz_map<?php echo $id; ?>.flyTo(
                        e.latlng,
                        <?php echo $markerzoom; ?>,
                        {
                                animate: true,
                                fadeAnimation: true,
                                zoomAnimation: true,
                                duration: 0.3
                        }
                        );
                };
            
        </script>

        <?php
    } ?>

    <?php

    $js = ob_get_clean();    
    return apply_filters('adminz_output_debug',$mainhtml.$js);
}
function adminz_map_item_shortcode( $atts, $content = null ) {
    if(isset( $_POST['ux_builder_action'] )){
        extract(shortcode_atts(array(
            'image'=>'',
            'marker'=>'',
            'title'=>'',
            'address'=>'',
            'phone_number'=>'',
            'description'=>'',
            'address_opt_1'=>'',
            'address_opt_2'=>'',
            'latlong'=>'',
            'popup'=>'',
            'flyto'=>'',
            'address_text'=>'',
            'phone_number_text'=> ''
        ), $atts));    
        ob_start();
        ?>
        <div style="border: 1px solid #8a8a8a29; padding: 10px; margin: 15px;">
            <div><?php echo $title; ?></div>
            <div><?php echo $address; ?></div>
            <div><?php echo $phone_number; ?></div>
            <div><?php echo $description; ?></div>
        </div>
        <?php
        return ob_get_clean();
    }
    return "<div>".json_encode($atts)."</div>";
}