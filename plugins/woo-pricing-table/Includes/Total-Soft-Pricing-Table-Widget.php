<?php

class Total_Soft_Pricing_Table extends WP_Widget
{
    function __construct()
    {
        $params = array('name' => 'Total Soft Pricing Table', 'description' => 'This is the widget of Total Soft Pricing Table plugin');
        parent::__construct('Total_Soft_Pricing_Table', '', $params);
    }

    function form($instance)
    {
        $defaults = array('Total_Soft_Pricing_Table' => '');
        $instance = wp_parse_args((array)$instance, $defaults);
        $Pricing_Table = $instance['Pricing_Table'];
        $instance['Pricing_Table_T'] = '';
        ?>
        <div>
            <p>
                Pricing Table: <select name="<?php echo $this->get_field_name('Pricing_Table'); ?>" class="widefat">
                    <?php
                    global $wpdb;
                    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
                    $Total_Soft_Pricing_Table = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id > %d", 0));
                    foreach ($Total_Soft_Pricing_Table as $Total_Soft_Pricing_Table1) {
                        ?>
                        <option value="<?php echo $Total_Soft_Pricing_Table1->id; ?>"> <?php echo $Total_Soft_Pricing_Table1->Total_Soft_PTable_Title; ?> </option> <?php
                    }
                    ?>
                </select>
            </p>
        </div>
        <?php
    }

    function widget($args, $instance)
    {
        extract($args);
        $Total_Soft_Pricing_Table = empty($instance['Pricing_Table']) ? '' : $instance['Pricing_Table'];

        $Total_Soft_Pricing_TableT = empty($instance['Pricing_Table_T']) ? '' : $instance['Pricing_Table_T'];
        global $wpdb;
        $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
        $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
        $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
        $table_name6 = $wpdb->prefix . "totalsoft_ptable_sets_prev";
        $PT_Id = 0;
        $PT_Col_Type = '';
        if ($Total_Soft_Pricing_TableT == '') {

            $TS_PTable_Columns = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Columns), true));
            $valueArray = json_decode($valuel[0]['PTable'], TRUE);
            $valueFromFirst = [];
            foreach ($valueArray as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    $PT_Id = $v['PTable_ID'];
                    $PT_Col_Type = $v['TS_PTable_TType'];
                    array_push($valueFromFirst, $v);
                }

            }
             usort($valueFromFirst,
                 function ($a, $b) {
                 if ($a['index']==$b['index']) return 0;
                    return ($a['index']<$b['index'])?-1:1;
                 }
             );
            $TS_PTable_Manager = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 WHERE id>%d order by id", 0));
            $value = (json_decode($val = json_encode($TS_PTable_Manager), true));
            $TableArray = [];
            foreach ($value[0] as $res) {
                $values = (json_decode($res, true));
                for ($i = 0; $i < count($values); $i++) {
                    if ($values[$i]['id'] == $PT_Id) {

                        $TableArray = $values[$i];
                    }
                }
            }


        } else {
            $TS_PTable_Manager = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 WHERE id>%d order by id", 0));
            $value = (json_decode(json_encode($TS_PTable_Manager), true));
            $TableArray = [];
            foreach ($value[0] as $res) {
                $values = (json_decode($res, true));
                for ($i = 0; $i < count($values); $i++) {
                    if ( $values[$i]['id'] == $Total_Soft_Pricing_Table) {
                        $TableArray = $values[$i];
                    }
                }
            }
            $TS_PTable_Columns = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Columns), true));
            $valueArray = json_decode($valuel[0]['PTable'], TRUE);
            $valueFromFirst = [];

            foreach ($valueArray as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    $PT_Col_Type = $v['TS_PTable_TType'];
                    array_push($valueFromFirst, $v);
                    break;
                }
            }
            usort($valueFromFirst,
                 function ($a, $b) {
                 if ($a['index']==$b['index']) return 0;
                    return ($a['index']<$b['index'])?-1:1;
                 }
             );
            $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name6 WHERE id > %d order by id", 0));
            $TS_PTable_Manager[0]->Total_Soft_PTable_Them = $TS_PTable_Set[0]->TS_PTable_TType;
        }
        echo $before_widget;
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('../CSS/totalsoft.css', __FILE__); ?>">
       <link href="https://fonts.googleapis.com/css?family=AbadiMTCondensedLight|Aharoni|Aldhabi|Amaranth|Andalus|AngsanaNew|AngsanaUPC|Anton|Aparajita|ArabicTypesetting|Arial|ArialBlack|Batang|BatangChe|BrowalliaNew|BrowalliaUPC|Calibri|CalibriLight|CalistoMT|Cambria|Candara|CenturyGothic|ComicSansMS|Consolas|Constantia|CopperplateGothic|CopperplateGothicLight|Battambang|Baumans|BungeeShade|Butcherman|Cabin|CabinSketch|Cairo|Damion|DilleniaUPC|DaunPenh|EagleLake|EastSeaDokdo|FiraSansCondensed|FiraSansExtraCondensed|FreesiaUPC|Gafata|Gabriola|JacquesFrancois|HeadlandOne|Katibeh|KaiTi|MicrosoftYiBaiti|MonsieurLaDoulaise|MrDeHaviland|NovaScript|NovaSquare|Nyala|OdorMeanChey|Offside|OldStandardTT|Oldenburg|Oxygen|OxygenMono|PrincessSofia|Prociono|Prompt|ProstoOne|ProzaLibre|Quicksand|Quintessential|Qwigley|Raavi|RacingSansOne|Radley|Rajdhani|Rakkas|Raleway|RalewayDots|Ramabhadra|Ramaraja|Rosarivo|Revalia|Shruti|Siemreap|SigmarOne|Signika|SignikaNegative|SimHei|SimKai|Simonetta|Tahoma|Tajawal|Tangerine|Taprom|Tauri|Taviraj|Teko|Telex|TenaliRamakrishna|TenorSans|TextMeOne|TheGirlNextDoor|Tienne|Tillana|TimesNewRoman|Timmana|Tinos|TitanOne|Vijaya"
      rel="stylesheet">
             
        <?php if ($PT_Col_Type == 'type1') { ?>
        <style type="text/css">
            .TS_PTable_Container {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap:  <?php echo $TableArray['Total_Soft_PTable_M_03']?>px;
                padding: 35px 15px;
                width: <?php echo $TableArray['Total_Soft_PTable_M_01'];?>%;
            <?php if($TableArray['Total_Soft_PTable_M_02'] == 'left'){ ?> margin-left: 0 !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'right'){ ?> margin-left: <?php echo 100 - $TableArray['Total_Soft_PTable_M_01'];?>%  !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'center'){ ?> margin-left: <?php echo (100 - $TableArray['Total_Soft_PTable_M_01'])/2;?>%  !important;
            <?php }?>
            }

            .TS_PTable_Container, .TS_PTable_Container * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                cursor: default;
            }
        </style>
        <div class="TS_PTable_Container">
            <?php
             $setValues = [];
             $valueFromSets = [];
            $TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Settings), true));
            $valueArrays = json_decode($valuel[0]['Price'], TRUE);
            
          
            foreach ($valueArrays as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    array_push($valueFromSets, $v);
                    
                }
            }
              usort($valueFromSets,
                    function ($a, $b) {
                           if ($a['index']==$b['index']) return 0;
                           return ($a['index']<$b['index'])?-1:1;
                        }
                );
              $valueArrays = $valueFromSets;
            for ($i = 0; $i < count($valueArrays); $i++) {
                if ($valueArrays[$i]['TS_PTable_TType'] == 'type1') {
                    $setValues = $valueArrays[$i];
                }
                  
                ?>
                <style type="text/css">
                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        min-height: 1px;
                        float: left;
                        width: <?php echo $setValues['TS_PTable_ST_01'];?>%;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: scale(1, 1.1);
                        -moz-transform: scale(1, 1.1);
                        transform: scale(1, 1.1);
                    <?php }?> margin-bottom: 45px;
                    }

                    @media not screen and (min-width: 820px) {
                        .TS_PTable_Container {
                            padding: 20px 5px;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 70%;
                            margin: 0 15% 40px 15%;
                            padding: 0 10px;
                        }
                    }

                    @media not screen and (min-width: 400px) {
                        .TS_PTable_Container {
                            padding: 20px 0;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 100%;
                            margin: 0 0 40px 0;
                            padding: 0 5px;
                        }
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 0;
                    }

                    <?php if($setValues['TS_PTable_ST_06'] == 'none') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow01') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow02') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow03') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow04') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        right: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -ms-transform: rotate(3deg);
                        -o-transform: rotate(3deg);
                        transform: rotate(3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow05') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        top: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        z-index: -1;
                        position: absolute;
                        content: "";
                        background: <?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -webkit-transform: rotate(-3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow06') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 50%;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow07') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 0;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow08') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 10px;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow09') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow10') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow11') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow12') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow13') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow14') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
            else if($setValues['TS_PTable_ST_06'] == 'shadow15') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php } ?>
                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?> {
                        padding: 30px 0 !important;
                        border: <?php echo $setValues['TS_PTable_ST_05'];?>px solid<?php echo $setValues['TS_PTable_ST_04'];?>;
                        text-align: center;
                        overflow: hidden;
                        position: relative;
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:before {
                        content: "";
                        border-right: 70px solid<?php echo $setValues['TS_PTable_ST_28'];?>;
                        border-top: 70px solid transparent;
                        border-bottom: 70px solid transparent;
                        position: absolute;
                        top: 30px;
                        right: -100px;
                        transition: all 0.3s ease 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover:before {
                        right: 0;
                    }

                    .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_08'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_09'];?> !important;
                        color: <?php echo $setValues['TS_PTable_ST_10'];?> !important;
                        margin: 10px 0 !important;
                        padding: 0 !important;
                    }

                    .TS_PTable_Title_IconTB_<?php echo $valueArrays[$i]['id'];?> {
                        display: block;
                    }

                    .TS_PTable_Title_IconLR_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px !important;
                    }

                    .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> i {
                        color: <?php echo $setValues['TS_PTable_ST_11'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_12'];?>px;
                    }

                    .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_14'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_15'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_16'];?>;
                        margin: 10px 0 !important;
                    }

                    .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        font-size: <?php echo $setValues['TS_PTable_ST_17'];?>px;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> {
                        padding: 0 !important;
                        margin: 20px 0 !important;
                        list-style: none;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:before {
                        content: '' !important;
                        display: none !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li {
                        color: <?php echo $setValues['TS_PTable_ST_21'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_22'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_23'];?>;
                        line-height: 1;
                        padding: 10px;
                        margin: 0 !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:nth-child(even) {
                        background: <?php echo $setValues['TS_PTable_ST_19'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:nth-child(odd) {
                        background: <?php echo $setValues['TS_PTable_ST_20'];?>;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_24'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_26'];?>px;
                        margin: 0 10px !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?>.TS_PTable_FCheck {
                        color: <?php echo $setValues['TS_PTable_ST_25'];?> !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        padding: 7px 30px !important;
                        background: <?php echo $setValues['TS_PTable_ST_28'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_29'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_30'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_31'];?> !important;
                        text-decoration: none !important;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        border-bottom: none;
                        transition: all 0.5s ease 0s;
                        cursor: pointer !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover, .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:focus {
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        border-bottom: none;
                        background: <?php echo $setValues['TS_PTable_ST_28'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_29'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_30'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_31'];?>;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?>, .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_32'];?>px;
                        color: <?php echo $setValues['TS_PTable_ST_33'];?>;
                    }

                    .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px 0 0 !important;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 0 0 10px !important;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        border-radius: 30px;
                    }
                </style>
                <div class="TS_PTable_Container_Col_<?php echo $valueFromFirst[$i]['id']; ?>">
                    <div class="TS_PTable_Shadow_<?php echo $valueFromFirst[$i]['id']; ?>">
                        <div class="TS_PTable__<?php echo $valueFromFirst[$i]['id']; ?>">
                            <?php if ($valueFromFirst[$i]['TS_PTable_TIcon'] == 'none') { ?>
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                            <?php } else { ?><?php if ($setValues['TS_PTable_ST_13'] == 'after') { ?>
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?>
                                    <span class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?> TS_PTable_Title_IconLR_<?php echo $valueFromFirst[$i]['id']; ?>">
														<i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
													</span>
                                </h3>
                            <?php } else if ($setValues['TS_PTable_ST_13'] == 'before') { ?>
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>">
													<span class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?> TS_PTable_Title_IconLR_<?php echo $valueFromFirst[$i]['id']; ?>">
														<i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
													</span>
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?>
                                </h3>
                            <?php } else if ($setValues['TS_PTable_ST_13'] == 'above') { ?>
                                <span class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?> TS_PTable_Title_IconTB_<?php echo $valueFromFirst[$i]['id']; ?>">
													<i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
												</span>
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                            <?php } else if ($setValues['TS_PTable_ST_13'] == 'under') { ?>
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                                <span class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?> TS_PTable_Title_IconTB_<?php echo $valueFromFirst[$i]['id']; ?>">
													<i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
												</span>
                            <?php } ?><?php } ?>
                            <div class="TS_PTable_PValue_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <?php echo $valueFromFirst[$i]['TS_PTable_PCur']; ?><?php echo $valueFromFirst[$i]['TS_PTable_PVal']; ?>
                                <span class="TS_PTable_PPlan_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PPlan']; ?></span>
                            </div>
                            <?php if ($valueFromFirst[$i]['TS_PTable_FCount'] != 0) { ?>
                                <ul class="TS_PTable_Features_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php $TS_PTable_FIcon = explode('TSPTFI', $valueFromFirst[$i]['TS_PTable_FIcon']); ?>
                                    <?php $TS_PTable_FText = explode('TSPTFT', $valueFromFirst[$i]['TS_PTable_FText']); ?>
                                    <?php $TS_PTable_FChek = explode('TSPTFC', $valueFromFirst[$i]['TS_PTable_C_01']); ?>
                                    <?php for ($j = 0; $j < $valueFromFirst[$i]['TS_PTable_FCount']; $j++) { ?><?php if ($TS_PTable_FChek[$j] != '') {
                                        $TS_PTable_FCheck = 'TS_PTable_FCheck';
                                    } else {
                                        $TS_PTable_FCheck = '';
                                    } ?>
                                        <li>
                                            <?php if ($setValues['TS_PTable_ST_27'] == 'before' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                            <?php echo html_entity_decode($TS_PTable_FText[$j]); ?>
                                            <?php if ($setValues['TS_PTable_ST_27'] == 'after' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            <a href="<?php echo $valueFromFirst[$i]['TS_PTable_BLink']; ?>"
                               class="TS_PTable_Button_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <?php if ($setValues['TS_PTable_ST_34'] == 'before' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                    <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconB_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                <?php } ?>
                                <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_BText']); ?>
                                <?php if ($setValues['TS_PTable_ST_34'] == 'after' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                    <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconA_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else if ($PT_Col_Type == 'type2') { ?>
        <style type="text/css">
            .TS_PTable_Container {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap:  <?php echo $TableArray['Total_Soft_PTable_M_03']?>px;
                padding: 35px 15px;
                width: <?php echo $TableArray['Total_Soft_PTable_M_01'];?>%;
            <?php if($TableArray['Total_Soft_PTable_M_02'] == 'left'){ ?> margin-left: 0  !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'right'){ ?> margin-left: <?php echo 100 - $TableArray['Total_Soft_PTable_M_01'];?>%  !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'center'){ ?> margin-left: <?php echo (100 - $TableArray['Total_Soft_PTable_M_01'])/2;?>%  !important;
            <?php }?>
            }

            .TS_PTable_Container, .TS_PTable_Container * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                cursor: default;
            }

            .TS_PTable_Container *:before, .TS_PTable_Container *:after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
        </style>
        <div class="TS_PTable_Container">
            <?php 
             $setValues = [];
             $valueFromSets = [];
            $TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Settings), true));
            $valueArrays = json_decode($valuel[0]['Price'], TRUE);
            
          
            foreach ($valueArrays as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    array_push($valueFromSets, $v);
                    
                }
            }
              usort($valueFromSets,
                    function ($a, $b) {
                           if ($a['index']==$b['index']) return 0;
                           return ($a['index']<$b['index'])?-1:1;
                        }
                );
              $valueArrays = $valueFromSets;
            for ($i = 0; $i < count($valueArrays); $i++) {
                if ($valueArrays[$i]['TS_PTable_TType'] == 'type2') {
                    $setValues = $valueArrays[$i];
                }
                
                ?>
                <style type="text/css">
                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        min-height: 1px;
                        float: left;
                        width: <?php echo $setValues['TS_PTable_ST_01'];?>%;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: scale(1, 1.05);
                        -moz-transform: scale(1, 1.05);
                        transform: scale(1, 1.05);
                    <?php }?> margin-bottom: 45px !important;
                    }

                    @media not screen and (min-width: 820px) {
                        .TS_PTable_Container {
                            padding: 20px 5px;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 70%;
                            margin: 0 15% 40px 15%;
                            padding: 0 10px;
                        }
                    }

                    @media not screen and (min-width: 400px) {
                        .TS_PTable_Container {
                            padding: 20px 0;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 100%;
                            margin: 0 0 40px 0;
                            padding: 0 5px;
                        }
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 0;
                    }

                    <?php if($setValues['TS_PTable_ST_04'] == 'none') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow01') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow02') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow03') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow04') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        right: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -ms-transform: rotate(3deg);
                        -o-transform: rotate(3deg);
                        transform: rotate(3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow05') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        top: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        z-index: -1;
                        position: absolute;
                        content: "";
                        background: <?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -webkit-transform: rotate(-3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow06') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 50%;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow07') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 0;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow08') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 10px;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow09') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow10') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow11') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow12') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow13') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow14') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow15') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } ?>
                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?> {
                        text-align: center;
                        position: relative;
                        background: <?php echo $setValues['TS_PTable_ST_19'];?>;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding: 30px 0 1px !important;
                    }

                    .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_06'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_07'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_08'];?> !important;
                        margin: 10px 0 !important;
                        padding: 0 !important;
                    }

                    .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> {
                        display: block;
                    }

                    .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> i {
                        color: <?php echo $setValues['TS_PTable_ST_09'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_10'];?>px;
                    }

                    .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?> {
                        padding: 20px 0 14px !important;
                        margin: 23px 0px 30px 0px !important;
                        background: <?php echo $setValues['TS_PTable_ST_11'];?>;
                        font-family: <?php echo $setValues['TS_PTable_ST_12'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_13'];?>;
                        position: relative;
                        transition: all 0.3s ease-in-out 0s;
                        -moz-transition: all 0.3s ease-in-out 0s;
                        -webkit-transition: all 0.3s ease-in-out 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?> {
                        background: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                    }

                    .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        display: block;
                        width: 10px;
                        height: 15px;
                        border-width: 13px 5px 11px;
                        border-style: solid;
                        border-color: transparent <?php echo $setValues['TS_PTable_ST_11'];?> <?php echo $setValues['TS_PTable_ST_11'];?> transparent;
                        position: absolute;
                        left: 0;
                        transition: all 0.3s ease-in-out 0s;
                        -moz-transition: all 0.3s ease-in-out 0s;
                        -webkit-transition: all 0.3s ease-in-out 0s;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> top: -23px;
                    <?php } else { ?> top: -24px;
                    <?php }?>
                    }

                    .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?>:after {
                        border-width: 11px 5px;
                        border-color: transparent transparent<?php echo $setValues['TS_PTable_ST_11'];?> <?php echo $setValues['TS_PTable_ST_11'];?>;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> top: -21px;
                    <?php } else { ?> top: -22px;
                    <?php }?> left: auto;
                        right: 0;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?>:before {
                        border-color: transparent <?php echo $setValues['TS_PTable_ST_17'];?> <?php echo $setValues['TS_PTable_ST_17'];?> transparent;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?>:after {
                        border-color: transparent transparent<?php echo $setValues['TS_PTable_ST_17'];?> <?php echo $setValues['TS_PTable_ST_17'];?>;
                    }

                    .TS_PTable_Amount_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        font-size: <?php echo $setValues['TS_PTable_ST_15'];?>px;
                        position: relative;
                    }

                    .TS_PTable_PCur_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_14'];?>px;
                        top: 0 !important;
                        vertical-align: super !important;
                        line-height: 1 !important;
                    }

                    .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_16'];?>px;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> {
                        padding: 0 !important;
                        margin: 0 !important;
                        list-style: none;
                        background: <?php echo $setValues['TS_PTable_ST_19'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:before {
                        content: '' !important;
                        display: none !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li {
                        background: <?php echo $setValues['TS_PTable_ST_19'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_20'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_21'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_22'];?>;
                        line-height: 1;
                        padding: 10px;
                        margin: 0 !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_23'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_25'];?>px;
                        margin: 0 10px !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?>.TS_PTable_FCheck {
                        color: <?php echo $setValues['TS_PTable_ST_24'];?> !important;
                    }

                    .TS_PTable_Div2_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding: 20px 0 30px !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        display: block;
                        padding: 10px 0 !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_27'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_28'];?> !important;
                        background: <?php echo $setValues['TS_PTable_ST_11'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_13'];?> !important;
                        border-top: 2px solid<?php echo $setValues['TS_PTable_ST_13'];?>;
                        border-bottom: 2px solid<?php echo $setValues['TS_PTable_ST_13'];?>;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        cursor: pointer !important;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        background: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        border-top: 2px solid<?php echo $setValues['TS_PTable_ST_18'];?>;
                        border-bottom: 2px solid<?php echo $setValues['TS_PTable_ST_18'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover, .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:focus {
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?>, .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_29'];?>px;
                    }

                    .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px 0 0 !important;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 0 0 10px !important;
                    }
                </style>
                <div class="TS_PTable_Container_Col_<?php echo $valueFromFirst[$i]['id']; ?>">
                    <div class="TS_PTable_Shadow_<?php echo $valueFromFirst[$i]['id']; ?>">
                        <div class="TS_PTable__<?php echo $valueFromFirst[$i]['id']; ?>">
                            <div class="TS_PTable_Div1_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <?php if ($valueFromFirst[$i]['TS_PTable_TIcon'] == 'none') { ?>
                                    <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                                <?php } else { ?>
                                    <span class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?>">
													<i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
												</span>
                                    <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                                <?php } ?>
                                <div class="TS_PTable_PValue_<?php echo $valueFromFirst[$i]['id']; ?>">
												<span class="TS_PTable_Amount_<?php echo $valueFromFirst[$i]['id']; ?>">
													<sup class="TS_PTable_PCur_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PCur']; ?></sup>
													<?php echo $valueFromFirst[$i]['TS_PTable_PVal']; ?>
													<sub class="TS_PTable_PPlan_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PPlan']; ?></sub>
												</span>
                                </div>
                            </div>
                            <?php if ($valueFromFirst[$i]['TS_PTable_FCount'] != 0) { ?>
                                <ul class="TS_PTable_Features_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php $TS_PTable_FIcon = explode('TSPTFI', $valueFromFirst[$i]['TS_PTable_FIcon']); ?>
                                    <?php $TS_PTable_FText = explode('TSPTFT', $valueFromFirst[$i]['TS_PTable_FText']); ?>
                                    <?php $TS_PTable_FChek = explode('TSPTFC', $valueFromFirst[$i]['TS_PTable_C_01']); ?>
                                    <?php for ($j = 0; $j < $valueFromFirst[$i]['TS_PTable_FCount']; $j++) { ?><?php if ($TS_PTable_FChek[$j] != '') {
                                        $TS_PTable_FCheck = 'TS_PTable_FCheck';
                                    } else {
                                        $TS_PTable_FCheck = '';
                                    } ?>
                                        <li>
                                            <?php if ($setValues['TS_PTable_ST_26'] == 'before' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                            <?php echo html_entity_decode($TS_PTable_FText[$j]); ?>
                                            <?php if ($setValues['TS_PTable_ST_26'] == 'after' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            <div class="TS_PTable_Div2_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <a href="<?php echo $valueFromFirst[$i]['TS_PTable_BLink']; ?>"
                                   class="TS_PTable_Button_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php if ($setValues['TS_PTable_ST_30'] == 'before' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconB_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_BText']); ?>
                                    <?php if ($setValues['TS_PTable_ST_30'] == 'after' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconA_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else if ($PT_Col_Type == 'type3') { ?>
        <style type="text/css">
            .TS_PTable_Container {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap:  <?php echo $TableArray['Total_Soft_PTable_M_03']?>px;
                padding: 35px 15px;
                width: <?php echo $TableArray['Total_Soft_PTable_M_01'];?>%;
            <?php if($TableArray['Total_Soft_PTable_M_02'] == 'left'){ ?> margin-left: 0 !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'right'){ ?> margin-left: <?php echo 100 - $TableArray['Total_Soft_PTable_M_01'];?>%  !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'center'){ ?> margin-left: <?php echo (100 - $TableArray['Total_Soft_PTable_M_01'])/2;?>%  !important;
            <?php }?>
            }

            .TS_PTable_Container, .TS_PTable_Container * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                cursor: default;
            }

            .TS_PTable_Container *:before, .TS_PTable_Container *:after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
        </style>
        <div class="TS_PTable_Container">
            <?php
          $setValues = [];
             $valueFromSets = [];
            $TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Settings), true));
            $valueArrays = json_decode($valuel[0]['Price'], TRUE);
            
          
            foreach ($valueArrays as $key => $v) {
                if ( $v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    array_push($valueFromSets, $v);
                    
                }
            }
              usort($valueFromSets,
                    function ($a, $b) {
                           if ($a['index']==$b['index']) return 0;
                           return ($a['index']<$b['index'])?-1:1;
                        }
                );
              $valueArrays = $valueFromSets;
            for ($i = 0; $i < count($valueArrays); $i++) {
                if ($valueArrays[$i]['TS_PTable_TType'] == 'type3') {
                    $setValues = $valueArrays[$i];
                }

                ?>
                <style type="text/css">
                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        min-height: 1px;
                        float: left;
                        width: <?php echo $setValues['TS_PTable_ST_01'];?>%;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: scale(1, 1.1);
                        -moz-transform: scale(1, 1.1);
                        transform: scale(1, 1.1);
                    <?php }?> margin-bottom: 45px !important;
                    }

                    @media not screen and (min-width: 820px) {
                        .TS_PTable_Container {
                            padding: 20px 5px;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 70%;
                            margin: 0 15% 40px 15%;
                            padding: 0 10px;
                        }
                    }

                    @media not screen and (min-width: 400px) {
                        .TS_PTable_Container {
                            padding: 20px 0;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 100%;
                            margin: 0 0 40px 0;
                            padding: 0 5px;
                        }
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 0;
                    }

                    <?php if($setValues['TS_PTable_ST_06'] == 'none') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow01') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow02') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow03') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow04') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        right: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -ms-transform: rotate(3deg);
                        -o-transform: rotate(3deg);
                        transform: rotate(3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow05') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        top: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        z-index: -1;
                        position: absolute;
                        content: "";
                        background: <?php echo $setValues['TS_PTable_ST_07'];?>;
                        box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -webkit-transform: rotate(-3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow06') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 50%;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow07') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 0;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow08') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_07'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_07'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        top: 10px;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow09') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow10') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow11') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow12') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow13') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow14') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php }
                    else if($setValues['TS_PTable_ST_06'] == 'shadow15') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -moz-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                        -webkit-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_07'];?>;
                    }

                    <?php } ?>
                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?> {
                        text-align: center;
                        position: relative;
                        border: <?php echo $setValues['TS_PTable_ST_05'];?>px solid<?php echo $setValues['TS_PTable_ST_04'];?>;
                        margin-top: 30px;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding: 50px 0 1px !important;
                    }

                    .TS_PTable_Div2_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding: 20px 0 25px !important;
                    }

                    .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> {
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: <?php echo $setValues['TS_PTable_ST_12'];?>;
                        border: <?php echo $setValues['TS_PTable_ST_05'];?>px solid<?php echo $setValues['TS_PTable_ST_04'];?>;
                        position: absolute;
                        top: -40px;
                        left: 50%;
                        padding: 10px !important;
                        transform: translateX(-50%);
                        -moz-transform: translateX(-50%);
                        -webkit-transform: translateX(-50%);
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> {
                        background: <?php echo $setValues['TS_PTable_ST_15'];?>;
                        transform: translateX(-50%) !important;
                        -moz-transform: translateX(-50%) !important;
                        -webkit-transform: translateX(-50%) !important;
                    }

                    .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> i {
                        width: 100%;
                        height: 100%;
                        line-height: 58px;
                        border-radius: 50%;
                        color: <?php echo $setValues['TS_PTable_ST_12'];?>;
                        background: <?php echo $setValues['TS_PTable_ST_13'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_14'];?>px;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Title_Icon_<?php echo $valueArrays[$i]['id'];?> i {
                        color: <?php echo $setValues['TS_PTable_ST_15'];?>;
                        background: <?php echo $setValues['TS_PTable_ST_16'];?>;
                    }

                    .TS_PTable_PValue_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        font-family: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_20'];?>px;
                        position: relative;
                    }

                    .TS_PTable_PCur_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_19'];?>px;
                        top: 0 !important;
                        vertical-align: super !important;
                        line-height: 1 !important;
                    }

                    .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        display: block;
                        font-family: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_21'];?>px;
                    }

                    .TS_PTable_Header_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 1;
                    }

                    .TS_PTable_Header_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "" !important;
                        width: 100% !important;
                        height: 1px;
                        background: <?php echo $setValues['TS_PTable_ST_04'];?>;
                        position: absolute;
                        top: 50%;
                        left: 0;
                        z-index: -1;
                    }

                    .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        width: fit-content;
                        margin: 10px auto !important;
                        padding: 10px 15px !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_08'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_09'];?> !important;
                        color: <?php echo $setValues['TS_PTable_ST_10'];?> !important;
                        background: <?php echo $setValues['TS_PTable_ST_11'];?> !important;
                        position: relative;
                        z-index: 1;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> {
                        list-style: none;
                        padding: 0 !important;
                        margin: 0 !important;
                        background: <?php echo $setValues['TS_PTable_ST_22'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:before {
                        content: '' !important;
                        display: none !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li {
                        background: <?php echo $setValues['TS_PTable_ST_22'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_23'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_24'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_25'];?>;
                        line-height: 1;
                        padding: 10px;
                        margin: 0 !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_26'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_28'];?>px;
                        margin: 0 10px !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?>.TS_PTable_FCheck {
                        color: <?php echo $setValues['TS_PTable_ST_27'];?> !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        font-size: <?php echo $setValues['TS_PTable_ST_30'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_31'];?> !important;
                        color: <?php echo $setValues['TS_PTable_ST_35'];?> !important;
                        background: <?php echo $setValues['TS_PTable_ST_34'];?>;
                        border: 1px solid<?php echo $setValues['TS_PTable_ST_35'];?>;
                        padding: 5px 20px !important;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        cursor: pointer !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover {
                        background: <?php echo $setValues['TS_PTable_ST_36'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_37'];?>;
                        border: 1px solid<?php echo $setValues['TS_PTable_ST_37'];?>;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover, .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:focus {
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?>, .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_32'];?>px;
                    }

                    .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px 0 0 !important;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 0 0 10px !important;
                    }
                </style>
                <div class="TS_PTable_Container_Col_<?php echo $valueFromFirst[$i]['id']; ?>">
                    <div class="TS_PTable_Shadow_<?php echo $valueFromFirst[$i]['id']; ?>">
                        <div class="TS_PTable__<?php echo $valueFromFirst[$i]['id']; ?>">
                            <div class="TS_PTable_Div1_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <?php if ($valueFromFirst[$i]['TS_PTable_TIcon'] != 'none') { ?>
                                    <div class="TS_PTable_Title_Icon_<?php echo $valueFromFirst[$i]['id']; ?>">
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
                                    </div>
                                <?php } ?>
                                <div class="TS_PTable_PValue_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <sup class="TS_PTable_PCur_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PCur']; ?></sup>
                                    <?php echo $valueFromFirst[$i]['TS_PTable_PVal']; ?>
                                </div>
                                <span class="TS_PTable_PPlan_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PPlan']; ?></span>
                                <div class="TS_PTable_Header_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                                </div>
                            </div>
                            <?php if ($valueFromFirst[$i]['TS_PTable_FCount'] != 0) { ?>
                                <div class="TS_PTable_Content_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <ul class="TS_PTable_Features_<?php echo $valueFromFirst[$i]['id']; ?>">
                                        <?php $TS_PTable_FIcon = explode('TSPTFI', $valueFromFirst[$i]['TS_PTable_FIcon']); ?>
                                        <?php $TS_PTable_FText = explode('TSPTFT', $valueFromFirst[$i]['TS_PTable_FText']); ?>
                                        <?php $TS_PTable_FChek = explode('TSPTFC', $valueFromFirst[$i]['TS_PTable_C_01']); ?>
                                        <?php for ($j = 0; $j < $valueFromFirst[$i]['TS_PTable_FCount']; $j++) { ?><?php if ($TS_PTable_FChek[$j] != '') {
                                            $TS_PTable_FCheck = 'TS_PTable_FCheck';
                                        } else {
                                            $TS_PTable_FCheck = '';
                                        } ?>
                                            <li>
                                                <?php if ($setValues['TS_PTable_ST_29'] == 'before' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                    <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                                <?php } ?>
                                                <?php echo html_entity_decode($TS_PTable_FText[$j]); ?>
                                                <?php if ($setValues['TS_PTable_ST_29'] == 'after' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                    <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <div class="TS_PTable_Div2_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <a href="<?php echo $valueFromFirst[$i]['TS_PTable_BLink']; ?>"
                                   class="TS_PTable_Button_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php if ($setValues['TS_PTable_ST_33'] == 'before' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconB_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_BText']); ?>
                                    <?php if ($setValues['TS_PTable_ST_33'] == 'after' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconA_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else if ($PT_Col_Type == 'type4') { ?>
        <style type="text/css">
            .TS_PTable_Container {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap:  <?php echo $TableArray['Total_Soft_PTable_M_03']?>px;
                padding: 35px 15px;
                width: <?php echo $TableArray['Total_Soft_PTable_M_01'];?>%;
            <?php if($TableArray['Total_Soft_PTable_M_02'] == 'left'){ ?> margin-left: 0 !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'right'){ ?> margin-left: <?php echo 100 - $TableArray['Total_Soft_PTable_M_01'];?>% !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'center'){ ?> margin-left: <?php echo (100 - $TableArray['Total_Soft_PTable_M_01'])/2;?>% !important;
            <?php }?>
            }

            .TS_PTable_Container, .TS_PTable_Container * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                cursor: default;
            }

            .TS_PTable_Container *:before, .TS_PTable_Container *:after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
        </style>
        <div class="TS_PTable_Container">
            <?php
             $setValues = [];
             $valueFromSets = [];
            $TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Settings), true));
            $valueArrays = json_decode($valuel[0]['Price'], TRUE);
            
          
            foreach ($valueArrays as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    array_push($valueFromSets, $v);
                    
                }
            }
              usort($valueFromSets,
                    function ($a, $b) {
                           if ($a['index']==$b['index']) return 0;
                           return ($a['index']<$b['index'])?-1:1;
                        }
                );
              $valueArrays = $valueFromSets;
            for ($i = 0; $i < count($valueArrays); $i++) {
                if ($valueArrays[$i]['TS_PTable_TType'] == 'type4') {
                    $setValues = $valueArrays[$i];
                }
                ?>
                <style type="text/css">
                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        min-height: 1px;
                        float: left;
                        width: <?php echo $setValues['TS_PTable_ST_01'];?>%;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: scale(1, 1.1);
                        -moz-transform: scale(1, 1.1);
                        transform: scale(1, 1.1);
                    <?php }?> margin-bottom: 45px !important;
                    }

                    @media not screen and (min-width: 820px) {
                        .TS_PTable_Container {
                            padding: 20px 5px;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 70%;
                            margin: 0 15% 40px 15%;
                            padding: 0 10px;
                        }
                    }

                    @media not screen and (min-width: 400px) {
                        .TS_PTable_Container {
                            padding: 20px 0;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 100%;
                            margin: 0 0 40px 0;
                            padding: 0 5px;
                        }
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 0;
                    }

                    <?php if($setValues['TS_PTable_ST_05'] == 'none') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow01') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow02') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow03') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow04') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        right: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -ms-transform: rotate(3deg);
                        -o-transform: rotate(3deg);
                        transform: rotate(3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow05') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        top: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        z-index: -1;
                        position: absolute;
                        content: "";
                        background: <?php echo $setValues['TS_PTable_ST_06'];?>;
                        box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -webkit-transform: rotate(-3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow06') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        top: 50%;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow07') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        top: 0;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow08') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_06'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_06'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        top: 10px;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow09') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow10') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow11') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow12') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow13') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow14') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_05'] == 'shadow15') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -moz-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_06'];?>;
                        -webkit-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_06'];?>;
                    }

                    <?php } ?>
                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?> {
                        text-align: center;
                        position: relative;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding: 30px 0 !important;
                        transition: all 0.3s ease 0s;
                        -moz-transition: all 0.3s ease 0s;
                        -webkit-transition: all 0.3s ease 0s;
                        position: relative;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_04'];?>;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "" !important;
                        width: 16px !important;
                        height: 16px !important;
                        border-radius: 50%;
                        border: 1px solid<?php echo $setValues['TS_PTable_ST_12'];?>;
                        position: absolute;
                        bottom: 12px;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?>:before {
                        left: 40px;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 40px;
                    }

                    .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_07'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_08'];?> !important;
                        color: <?php echo $setValues['TS_PTable_ST_09'];?> !important;
                        margin: 0 0 15px 0 !important;
                        padding: 0 !important;
                        letter-spacing: 2px !important;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_10'];?>;
                    }

                    .TS_PTable_Amount_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        font-family: <?php echo $setValues['TS_PTable_ST_11'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_12'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_15'];?>px;
                        position: relative;
                        transition: all 0.3s ease 0s;
                        -moz-transition: all 0.3s ease 0s;
                        -webkit-transition: all 0.3s ease 0s;
                        margin-bottom: 20px !important;
                    }

                    .TS_PTable_PCur_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_14'];?>px;
                        top: 0px !important;
                        vertical-align: super !important;
                        line-height: 1 !important;
                    }

                    .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_16'];?>px;
                        color: <?php echo $setValues['TS_PTable_ST_09'];?>;
                        bottom: 0;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Amount_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_13'];?>;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_10'];?>;
                    }

                    .TS_PTable_Content_<?php echo $valueArrays[$i]['id'];?> {
                        padding-top: 50px;
                        background: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        position: relative;
                    }

                    .TS_PTable_Content_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Content_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "" !important;
                        width: 16px !important;
                        height: 16px !important;
                        border-radius: 50%;
                        border: 1px solid<?php echo $setValues['TS_PTable_ST_18'];?>;
                        position: absolute;
                        top: 12px;
                    }

                    .TS_PTable_Content_<?php echo $valueArrays[$i]['id'];?>:before {
                        left: 40px;
                    }

                    .TS_PTable_Content_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 40px;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> {
                        padding: 0 10px !important;
                        margin: 0 !important;
                        list-style: none;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "" !important;
                        width: 8px !important;
                        height: 46px !important;
                        border-radius: 3px;
                        background: <?php echo $setValues['TS_PTable_ST_09'];?>;
                        position: absolute;
                        top: -22px;
                        z-index: 1;
                        box-shadow: 0 0 5px #707070;
                        transition: all 0.3s ease 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:after {
                        background: <?php echo $setValues['TS_PTable_ST_10'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:before {
                        left: 44px;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 44px;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li {
                        background: <?php echo $setValues['TS_PTable_ST_17'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_19'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_20'];?>;
                        border-bottom: 1px solid<?php echo $setValues['TS_PTable_ST_18'];?>;
                        line-height: 1;
                        padding: 10px;
                        margin: 0 !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:last-child {
                        border-bottom: none;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:before {
                        content: '' !important;
                        display: none !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_21'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_23'];?>px;
                        margin: 0 10px !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?>.TS_PTable_FCheck {
                        color: <?php echo $setValues['TS_PTable_ST_22'];?> !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        padding: 5px 20px !important;
                        margin: 15px 0 !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_25'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_26'];?> !important;
                        background: <?php echo $setValues['TS_PTable_ST_29'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_30'];?> !important;
                        transition: all 0.3s ease 0s;
                        -moz-transition: all 0.3s ease 0s;
                        -webkit-transition: all 0.3s ease 0s;
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        cursor: pointer !important;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        background: <?php echo $setValues['TS_PTable_ST_31'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_32'];?>;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover, .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:focus {
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?>, .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_27'];?>px;
                    }

                    .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px 0 0 !important;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 0 0 10px !important;
                    }
                </style>
                <div class="TS_PTable_Container_Col_<?php echo $valueFromFirst[$i]['id']; ?>">
                    <div class="TS_PTable_Shadow_<?php echo $valueFromFirst[$i]['id']; ?>">
                        <div class="TS_PTable__<?php echo $valueFromFirst[$i]['id']; ?>">
                            <div class="TS_PTable_Div1_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                                <span class="TS_PTable_Amount_<?php echo $valueFromFirst[$i]['id']; ?>">
												<sup class="TS_PTable_PCur_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PCur']; ?></sup>
												<?php echo $valueFromFirst[$i]['TS_PTable_PVal']; ?>
												<sub class="TS_PTable_PPlan_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo $valueFromFirst[$i]['TS_PTable_PPlan']; ?></sub>
											</span>
                            </div>
                            <div class="TS_PTable_Content_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <ul class="TS_PTable_Features_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php $TS_PTable_FIcon = explode('TSPTFI', $valueFromFirst[$i]['TS_PTable_FIcon']); ?>
                                    <?php $TS_PTable_FText = explode('TSPTFT', $valueFromFirst[$i]['TS_PTable_FText']); ?>
                                    <?php $TS_PTable_FChek = explode('TSPTFC', $valueFromFirst[$i]['TS_PTable_C_01']); ?>
                                    <?php for ($j = 0; $j < $valueFromFirst[$i]['TS_PTable_FCount']; $j++) { ?><?php if ($TS_PTable_FChek[$j] != '') {
                                        $TS_PTable_FCheck = 'TS_PTable_FCheck';
                                    } else {
                                        $TS_PTable_FCheck = '';
                                    } ?>
                                        <li>
                                            <?php if ($setValues['TS_PTable_ST_24'] == 'before' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                            <?php echo html_entity_decode($TS_PTable_FText[$j]); ?>
                                            <?php if ($setValues['TS_PTable_ST_24'] == 'after' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <a href="<?php echo $valueFromFirst[$i]['TS_PTable_BLink']; ?>"
                                   class="TS_PTable_Button_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php if ($setValues['TS_PTable_ST_28'] == 'before' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconB_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_BText']); ?>
                                    <?php if ($setValues['TS_PTable_ST_28'] == 'after' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconA_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else if ($PT_Col_Type == 'type5') { ?>
        <style type="text/css">
            .TS_PTable_Container {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                gap:  <?php echo $TableArray['Total_Soft_PTable_M_03']?>px;
                padding: 35px 15px;
                width: <?php echo $TableArray['Total_Soft_PTable_M_01'];?>%  !important;
            <?php if($TableArray['Total_Soft_PTable_M_02'] == 'left'){ ?> margin-left: 0;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'right'){ ?> margin-left: <?php echo 100 - $TableArray['Total_Soft_PTable_M_01'];?>%  !important;
            <?php }else if($TableArray['Total_Soft_PTable_M_02'] == 'center'){ ?> margin-left: <?php echo (100 - $TableArray['Total_Soft_PTable_M_01'])/2;?>%  !important;
            <?php }?>
            }

            .TS_PTable_Container, .TS_PTable_Container * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                cursor: default;
            }

            .TS_PTable_Container *:before, .TS_PTable_Container *:after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
        </style>
        <div class="TS_PTable_Container">
            <?php
             $setValues = [];
             $valueFromSets = [];
            $TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
            $valuel = (json_decode(json_encode($TS_PTable_Settings), true));
            $valueArrays = json_decode($valuel[0]['Price'], TRUE);
            
          
            foreach ($valueArrays as $key => $v) {
                if ($v['PTable_ID'] == $Total_Soft_Pricing_Table) {
                    array_push($valueFromSets, $v);
                    
                }
            }
              usort($valueFromSets,
                    function ($a, $b) {
                           if ($a['index']==$b['index']) return 0;
                           return ($a['index']<$b['index'])?-1:1;
                        }
                );
              $valueArrays = $valueFromSets;
            for ($i = 0; $i < count($valueArrays); $i++) {
                if ($valueArrays[$i]['TS_PTable_TType'] == 'type5') {
                    $setValues = $valueArrays[$i];
                }
                ?>
                <style type="text/css">
                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        min-height: 1px;
                        float: left;
                        width: <?php echo $setValues['TS_PTable_ST_01'];?>%;
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: translate3d(0, 0, 0) scale(1, 1.1);
                        -moz-transform: translate3d(0, 0, 0) scale(1, 1.1);
                        transform: translate3d(0, 0, 0) scale(1, 1.1);
                    <?php } else { ?> -webkit-transform: translate3d(0, 0, 0) scale(1, 1);
                        -moz-transform: translate3d(0, 0, 0) scale(1, 1);
                        transform: translate3d(0, 0, 0) scale(1, 1);
                    <?php }?> margin-bottom: 40px !important;
                        transition: transform 0.5s ease 0s;
                        -moz-transition: transform 0.5s ease 0s;
                        -webkit-transition: transform 0.5s ease 0s;
                    }

                    .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?>:hover {
                    <?php if( $setValues['TS_PTable_ST_02'] == 'on' ) { ?> -webkit-transform: translate3d(0, 0, 0) scale(1, 1.07);
                        -moz-transform: translate3d(0, 0, 0) scale(1, 1.07);
                        transform: translate3d(0, 0, 0) scale(1, 1.07);
                    <?php } else { ?> -webkit-transform: translate3d(0, 0, 0) scale(1, 1.03);
                        -moz-transform: translate3d(0, 0, 0) scale(1, 1.03);
                        transform: translate3d(0, 0, 0) scale(1, 1.03);
                    <?php }?> z-index: 1;
                    }

                    @media not screen and (min-width: 820px) {
                        .TS_PTable_Container {
                            padding: 20px 5px;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 70%;
                            margin: 0 15% 40px 15%;
                            padding: 0 10px;
                        }
                    }

                    @media not screen and (min-width: 400px) {
                        .TS_PTable_Container {
                            padding: 20px 0;
                        }

                        .TS_PTable_Container_Col_<?php echo $valueArrays[$i]['id'];?> {
                            width: 100%;
                            margin: 0 0 40px 0;
                            padding: 0 5px;
                        }
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        z-index: 0;
                        border-radius: 10px;
                    }

                    <?php if($setValues['TS_PTable_ST_04'] == 'none') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        -webkit-box-shadow: none !important;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow01') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 10px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow02') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow03') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before {
                        bottom: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -ms-transform: rotate(-3deg);
                        -o-transform: rotate(-3deg);
                        transform: rotate(-3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow04') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        bottom: 15px;
                        right: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        -webkit-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -ms-transform: rotate(3deg);
                        -o-transform: rotate(3deg);
                        transform: rotate(3deg);
                        z-index: -1;
                        position: absolute;
                        content: "";
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow05') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        top: 15px;
                        left: 10px;
                        width: 50%;
                        height: 20%;
                        max-width: 300px;
                        max-height: 100px;
                        z-index: -1;
                        position: absolute;
                        content: "";
                        background: <?php echo $setValues['TS_PTable_ST_05'];?>;
                        box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 -15px 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        transform: rotate(3deg);
                        -moz-transform: rotate(3deg);
                        -webkit-transform: rotate(3deg);
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        transform: rotate(-3deg);
                        -moz-transform: rotate(-3deg);
                        -webkit-transform: rotate(-3deg);
                        right: 10px;
                        left: auto;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow06') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 50%;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow07') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 0;
                        bottom: 0;
                        left: 10px;
                        right: 10px;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow08') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        position: relative;
                        box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -webkit-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                        -moz-box-shadow: 0 1px 4px <?php echo $setValues['TS_PTable_ST_05'];?>, 0 0 40px <?php echo $setValues['TS_PTable_ST_05'];?> inset;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:before, .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        content: "";
                        position: absolute;
                        z-index: -1;
                        box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 20px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        top: 10px;
                        bottom: 10px;
                        left: 0;
                        right: 0;
                        border-radius: 100px / 10px;
                    }

                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?>:after {
                        right: 10px;
                        left: auto;
                        transform: skew(8deg) rotate(3deg);
                        -moz-transform: skew(8deg) rotate(3deg);
                        -webkit-transform: skew(8deg) rotate(3deg);
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow09') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow10') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 4px -4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow11') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 5px 5px 3px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow12') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 2px 2px white, 4px 4px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow13') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 8px 8px 18px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow14') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 8px 6px -6px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } else if($setValues['TS_PTable_ST_04'] == 'shadow15') { ?>
                    .TS_PTable_Shadow_<?php echo $valueArrays[$i]['id'];?> {
                        box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -moz-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                        -webkit-box-shadow: 0 0 18px 7px<?php echo $setValues['TS_PTable_ST_05'];?>;
                    }

                    <?php } ?>
                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?> {
                        text-align: center;
                        position: relative;
                        background-color: <?php echo $setValues['TS_PTable_ST_03'];?>;
                        padding-bottom: 40px !important;
                        border-radius: 10px;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_18'];?>;
                        padding: 40px 0 !important;
                        border-radius: 10px 10px 50% 50%;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                        position: relative;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> {
                        background-color: <?php echo $setValues['TS_PTable_ST_19'];?>;
                    }

                    .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> i {
                        font-size: <?php echo $setValues['TS_PTable_ST_09'];?>px;
                        color: <?php echo $setValues['TS_PTable_ST_10'];?>;
                        margin-bottom: 10px;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Div1_<?php echo $valueArrays[$i]['id'];?> i {
                        color: <?php echo $setValues['TS_PTable_ST_11'];?>;
                    }

                    .TS_PTable_Title_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_06'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_07'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_08'];?> !important;
                        margin: 20px 0 !important;
                        padding: 0 !important;
                    }

                    .TS_PTable_Amount_<?php echo $valueArrays[$i]['id'];?> {
                        font-family: <?php echo $setValues['TS_PTable_ST_12'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_13'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_16'];?>px;
                        position: relative;
                        transition: all 0.5s ease 0s;
                        -moz-transition: all 0.5s ease 0s;
                        -webkit-transition: all 0.5s ease 0s;
                    }

                    .TS_PTable_PCur_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_15'];?>px;
                    }

                    .TS_PTable_PPlan_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_17'];?>px;
                        display: block;
                    }

                    .TS_PTable__<?php echo $valueArrays[$i]['id'];?>:hover .TS_PTable_Amount_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_14'];?>;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> {
                        padding: 0 !important;
                        margin: 0 0 30px 0 !important;
                        list-style: none;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li {
                        color: <?php echo $setValues['TS_PTable_ST_20'];?>;
                        font-size: <?php echo $setValues['TS_PTable_ST_21'];?>px;
                        font-family: <?php echo $setValues['TS_PTable_ST_22'];?>;
                        line-height: 1;
                        padding: 10px;
                        margin: 0 !important;
                    }

                    .TS_PTable_Features_<?php echo $valueArrays[$i]['id'];?> li:before {
                        content: '' !important;
                        display: none !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?> {
                        color: <?php echo $setValues['TS_PTable_ST_23'];?> !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_25'];?>px;
                        margin: 0 10px !important;
                    }

                    .TS_PTable_FIcon_<?php echo $valueArrays[$i]['id'];?>.TS_PTable_FCheck {
                        color: <?php echo $setValues['TS_PTable_ST_24'];?> !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?> {
                        display: inline-block;
                        padding: 10px 35px !important;
                        font-size: <?php echo $setValues['TS_PTable_ST_27'];?>px !important;
                        font-family: <?php echo $setValues['TS_PTable_ST_28'];?> !important;
                        background: <?php echo $setValues['TS_PTable_ST_31'];?> !important;
                        color: <?php echo $setValues['TS_PTable_ST_32'];?> !important;
                        border-radius: 20px;
                        transition: all 0.3s ease 0s;
                        -moz-transition: all 0.3s ease 0s;
                        -webkit-transition: all 0.3s ease 0s;
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                        cursor: pointer !important;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover {
                        box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_31'];?>;
                        -moz-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_31'];?>;
                        -webkit-box-shadow: 0 0 10px<?php echo $setValues['TS_PTable_ST_31'];?>;
                        background: <?php echo $setValues['TS_PTable_ST_31'];?>;
                        color: <?php echo $setValues['TS_PTable_ST_32'];?>;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:hover {
                        text-decoration: none;
                        outline: none;
                    }

                    .TS_PTable_Button_<?php echo $valueArrays[$i]['id'];?>:focus {
                        text-decoration: none;
                        outline: none;
                        box-shadow: none;
                        -webkit-box-shadow: none;
                        -moz-box-shadow: none;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?>, .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        font-size: <?php echo $setValues['TS_PTable_ST_29'];?>px;
                    }

                    .TS_PTable_BIconB_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 10px 0 0 !important;
                    }

                    .TS_PTable_BIconA_<?php echo $valueArrays[$i]['id'];?> {
                        margin: 0 0 0 10px !important;
                    }
                </style>
                <div class="TS_PTable_Container_Col_<?php echo $valueFromFirst[$i]['id']; ?>">
                    <div class="TS_PTable_Shadow_<?php echo $valueFromFirst[$i]['id']; ?>">
                        <div class="TS_PTable__<?php echo $valueFromFirst[$i]['id']; ?>">
                            <div class="TS_PTable_Div1_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <?php if ($valueFromFirst[$i]['TS_PTable_TIcon'] != 'none') { ?>
                                    <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_TIcon']; ?>"></i>
                                <?php } ?>
                                <div class="TS_PTable_Amount_<?php echo $valueFromFirst[$i]['id']; ?>">
												<span class="TS_PTable_PCur_<?php echo $valueFromFirst[$i]['id']; ?>">
													<?php echo $valueFromFirst[$i]['TS_PTable_PCur']; ?>
												</span>
                                    <?php echo $valueFromFirst[$i]['TS_PTable_PVal']; ?>
                                    <span class="TS_PTable_PPlan_<?php echo $valueFromFirst[$i]['id']; ?>">
													<?php echo $valueFromFirst[$i]['TS_PTable_PPlan']; ?>
												</span>
                                </div>
                            </div>
                            <h3 class="TS_PTable_Title_<?php echo $valueFromFirst[$i]['id']; ?>"><?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_TText']); ?></h3>
                            <?php if ($valueFromFirst[$i]['TS_PTable_FCount'] != 0) { ?>
                                <div class="TS_PTable_Content_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <ul class="TS_PTable_Features_<?php echo $valueFromFirst[$i]['id']; ?>">
                                        <?php $TS_PTable_FIcon = explode('TSPTFI', $valueFromFirst[$i]['TS_PTable_FIcon']); ?>
                                        <?php $TS_PTable_FText = explode('TSPTFT', $valueFromFirst[$i]['TS_PTable_FText']); ?>
                                        <?php $TS_PTable_FChek = explode('TSPTFC', $valueFromFirst[$i]['TS_PTable_C_01']); ?>
                                        <?php for ($j = 0; $j < $valueFromFirst[$i]['TS_PTable_FCount']; $j++) { ?><?php if ($TS_PTable_FChek[$j] != '') {
                                            $TS_PTable_FCheck = 'TS_PTable_FCheck';
                                        } else {
                                            $TS_PTable_FCheck = '';
                                        } ?>
                                            <li>
                                                <?php if ($setValues['TS_PTable_ST_26'] == 'before' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                    <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                                <?php } ?>
                                                <?php echo html_entity_decode($TS_PTable_FText[$j]); ?>
                                                <?php if ($setValues['TS_PTable_ST_26'] == 'after' && $TS_PTable_FIcon[$j] != 'none') { ?>
                                                    <i class="totalsoft totalsoft-<?php echo $TS_PTable_FIcon[$j]; ?> TS_PTable_FIcon_<?php echo $valueFromFirst[$i]['id']; ?> <?php echo $TS_PTable_FCheck; ?>"></i>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <div class="TS_PTable_Div2_<?php echo $valueFromFirst[$i]['id']; ?>">
                                <a href="<?php echo $valueFromFirst[$i]['TS_PTable_BLink']; ?>"
                                   class="TS_PTable_Button_<?php echo $valueFromFirst[$i]['id']; ?>">
                                    <?php if ($setValues['TS_PTable_ST_30'] == 'before' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconB_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                    <?php echo html_entity_decode($valueFromFirst[$i]['TS_PTable_BText']); ?>
                                    <?php if ($setValues['TS_PTable_ST_30'] == 'after' && $valueFromFirst[$i]['TS_PTable_BIcon'] != 'none') { ?>
                                        <i class="totalsoft totalsoft-<?php echo $valueFromFirst[$i]['TS_PTable_BIcon']; ?> TS_PTable_BIconA_<?php echo $valueFromFirst[$i]['id']; ?>"></i>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?><?php
        echo $after_widget;
    }
}

?>