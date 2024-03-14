<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$stages = $lists['stages'];
?>
<div class="heading col-xs-12 col-lg-12">

    <div class="selection pull-right">
        <form method="post" lpformnum="1">
            <div class="data">
                <?php echo isset($lists['tourn'])?$lists['tourn']:'';?>
            </div>
        </form>
    </div>
</div>
<div class="jsClear"></div>

<div class="table-responsive">
    <table class="table table-striped cansorttbl" >
        <thead>
            <tr class="ui-sortable">
                <th class="jsNoWrap jsalcenter">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Date', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <th class="jsNoWrap">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Stage', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <?php
                if(count($lists['stages_cat'])){
                    foreach($lists['stages_cat'] as $key=>$val){
                        ?>
                        <th class="jsNoWrap">
                            <a href="javascript:void(0);">
                                <?php echo $val->name;?>
                                <i class="fa"></i>
                            </a>    
                        </th>
                        <?php
                    }
                }
                if(count($lists['stages_ef'])){
                    foreach($lists['stages_ef'] as $valID){
                        
                        //if(isset($stages[$intA]->lists["ef"][$valID])){
                            echo '<th class="jsNoWrap">';
                            echo $valID;
                            echo '</th>';
                        //}
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            for($intA=0;$intA<count($stages);$intA++){
                
                echo '<tr>';
                echo '<td class="jsalcenter jsNoWrap">'.($stages[$intA]->getMeta('_jsprt_achv_stage_date')).' '.($stages[$intA]->getMeta('_jsprt_achv_stage_time')).'</td>';
                                    
                echo '<td class="jsNoWrap">'.($stages[$intA]->getName(true)).'</td>';

                
                if(count($lists['stages_cat'])){
                    foreach($lists['stages_cat'] as $key=>$val){
                        ?>
                        <td class="jsNoWrap">
                                <?php 
                                $selVal = $stages[$intA]->getMeta('_jsprt_achv_stage_stagecat_'.$key);
                                echo jsAchvHelper::getStageVals($selVal);
                                ?>

                        </td>
                        <?php
                    }
                }
                if(count($lists['stages_ef'])){
                    foreach($lists['stages_ef'] as $valID){
                        echo '<td class="jsNoWrap">';
                        if(isset($stages[$intA]->lists["ef"][$valID])){
                            
                            echo $stages[$intA]->lists["ef"][$valID];
                            
                        }
                        echo '</td>';
                    }
                }    
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    
</div>   

<script>
    jQuery(document).ready(function() {
        var theHeaders = {}
        jQuery('.jstable_achv_calendar').find('th.noSort').each(function(i,el){
            theHeaders[jQuery(this).index()] = { sorter: false };
        });
        jQuery('.jstable_achv_calendar').tablesorter({headers: theHeaders});
    } );
</script>  
