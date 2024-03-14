<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$fields_sorting = $lists['fields_sorting'];
$resultFields = $lists['resultFields'];
$result_table = $lists['result_table'];
require_once JOOMSPORT_ACHV_PATH_OBJECTS.'class-jsport-player.php';
?>
<div>
    <?php echo $rows->getDescription();?>
</div>
<div class="pt10 extrafldcn">
    <?php echo jsAchvHelper::getADF($rows->lists['ef']);?>
</div>
<div class="jsClear"></div>
<div class="table-responsive">
    <table class="table table-striped cansorttbl" id="jstable_stage">
        <thead>
            <tr class="ui-sortable">
                <th class="jsNoWrap jsalcenter" width="5%">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Rank', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>

                <?php 
                if($fields_sorting && count($fields_sorting)){
                    foreach($fields_sorting as $fld){
                        switch ($fld) {
                            case 0:
                                ?>
                                <th class="jsNoWrap">
                                    <a href="javascript:void(0);">
                                        <?php echo __( 'Participant', 'joomsport-achievements' );?>
                                        <i class="fa"></i>
                                    </a>    
                                </th>

                                <?php

                                break;
                            case -1:
                                
                                break;
                            default:
                                for($intA=0;$intA<count($resultFields);$intA++){
                                    if($fld == $resultFields[$intA]->id){
                                        echo '<th class="jsalcenter jsNoWrap"><a href="javascript:void(0);">'.$resultFields[$intA]->name.'<i class="fa"></i></a></th>';
                                    }
                                }
                                break;
                        }
                    }
                }
                if($lists['ranking_criteria'] == '0'){
                ?>
                    <th class="jsalcenter jsNoWrap" width="5%"><a href="javascript:void(0);"><?php echo __( 'Points', 'joomsport-achievements' );?><i class="fa"></i></a></th>
                <?php
                }?>
            </tr>
        </thead>
        <tbody>
            <?php
            for($intA=0;$intA<count($result_table);$intA++){
                echo '<tr>';
                echo '<td class="jsalcenter jsNoWrap"><span>'.($result_table[$intA]->rank).'</span></td>';
                                    
                if($fields_sorting && count($fields_sorting)){
                    foreach($fields_sorting as $fld){
                        switch ($fld) {
                            case 0:
                                $player = new classJsportAchvPlayer($result_table[$intA]->partic_id,null,false);
                                echo '<td class="jsNoWrap">'.($player->getName(true)).'</td>';

                                break;
                            case -1:
                                break;
                            default:
                                for($intB=0;$intB<count($resultFields);$intB++){
                                    if($fld == $resultFields[$intB]->id){
                                        echo '<td class="jsalcenter jsNoWrap">'.($result_table[$intA]->{'field_'.$resultFields[$intB]->id}).'</td>';
                                    }
                                }
                                break;
                        }
                    }
                }
                if($lists['ranking_criteria'] == '0'){
                    echo '<td class="jsalcenter jsNoWrap">'.($result_table[$intA]->points).'</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <script>
    jQuery(document).ready(function() {
        var theHeaders = {}
        jQuery('#jstable_stage').find('th.noSort').each(function(i,el){
            theHeaders[jQuery(this).index()] = { sorter: false };
        });
        jQuery('#jstable_stage').tablesorter({headers: theHeaders});
    } );
</script>  
</div>    
