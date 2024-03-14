<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="row">    
    <div class="col-xs-12 rmpadd" style="padding-right:0px;">
        <div class="jsObjectPhoto rmpadd">
            <div class="photoPlayer">

                    <?php echo jsHelperAchvImages::getEmblemBig($rows->getDefaultPhoto());?>

                    

            </div>    
        </div>
        <?php
        $class = '';
        $extra_fields = jsAchvHelper::getADF($rows->lists['ef']);
        if ($extra_fields) {
            $class = 'well well-sm';
        } else {
            ?>
            <div class="rmpadd" style="padding-right:0px;padding-left:15px;">
                <?php //echo $rows->getDescription();
            ?>
            </div>
            <?php

        }
        ?>
        
        <div class="<?php echo $class;?> pt10 extrafldcn">
            <?php

                echo $extra_fields;
                
            ?>
        </div>
        
    </div>
</div>   
<br />
<div>
    <?php echo $rows->getDescription();?>
</div>
<?php
if(count($lists['stages_by_season'])){
foreach($lists['stages_by_season'] as $key=>$stBySeas){
    $lists['stages'] = $stBySeas;

if(count($stBySeas)){
?>

<div><h3><?php echo get_the_title($key);?></h3></div>
<div class="table-responsive">   
<table class="table table-striped">
     <thead>
            <tr class="ui-sortable">
                <th class="jsNoWrap jsalcenter" width="5%">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Rank', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <th class="jsNoWrap jsStage">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Stage', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <?php 
                for($intA=0;$intA<count($lists['resultFields']);$intA++){
                    echo '<th class="jsalcenter jsNoWrap"><a href="javascript:void(0);">'.$lists['resultFields'][$intA]->name.'<i class="fa"></i></a></th>';
                    
                }
                
                ?>
                <?php
                if($lists['ranking_criteria']){
                ?>
                <th class="jsalcenter jsNoWrap" width="5%"><a href="javascript:void(0);"><?php echo __( 'Points', 'joomsport-achievements' );?><i class="fa"></i></a></th>
                <?php
                }
                ?>
            </tr>
        </thead>
    <tbody>
    <?php 
    for($intA=0;$intA<count($lists['stages']);$intA++){
        foreach($lists['stages'][$intA] as $stageObj){
        ?>
    <tr>
        <td class="jsalcenter jsNoWrap">
            <span><?php echo $stageObj->rank;?></span>
        </td>
        <td class=" jsNoWrap">
            <?php
            $stage = new classJsportAchvStage($stageObj->stage_id);
            ?>
            <?php echo $stage->getName(true);?>
        </td>
        <?php
        for($intB=0;$intB<count($lists['resultFields']);$intB++){

            echo '<td class="jsalcenter jsNoWrap">'.($stageObj->{'field_'.$lists['resultFields'][$intB]->id}).'</td>';

        }
        ?>
        <?php
        if($lists['ranking_criteria']){
        ?>
        <td class="jsalcenter">
            <?php echo $stageObj->points;?>
        </td>
        <?php } ?>
    </tr>
    <?php } }?>
    </tbody>
</table>
</div>    
<?php
}
}
}
?>