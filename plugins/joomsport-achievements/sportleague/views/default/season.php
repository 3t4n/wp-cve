<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once JOOMSPORT_ACHV_PATH_OBJECTS.'class-jsport-player.php';
$result_table = $lists['result_table'];
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
<div>
    <?php echo $rows->getDescription();?>
</div>
<div class="pt10 extrafldcn">
    <?php echo jsAchvHelper::getADF($rows->lists['ef']);?>
</div>
<div class="jsClear"></div>
<?php
if(count($result_table)){
    $intL = 0;
foreach($result_table as $key => $value){
    if($key && count($value)){
        echo '<h2>'.$key.'</h2>';
    }
    if(count($value)){
?>

<div class="table-responsive">
    <table class="table table-striped cansorttbl jstable_achv_season" >
        <thead>
            <tr class="ui-sortable">
                <th class="jsNoWrap jsalcenter" width="5%">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Rank', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <th class="jsNoWrap">
                    <a href="javascript:void(0);">
                        <?php echo __( 'Participant', 'joomsport-achievements' );?>
                        <i class="fa"></i>
                    </a>    
                </th>
                <?php
                //if($lists['ranking_criteria'] == '0'){
                ?>
                    <th class="jsalcenter jsNoWrap" width="5%"><a href="javascript:void(0);" width="5%"><?php echo $rows->lists['rank_field_head'];?><i class="fa"></i></a></th>
                <?php
                //}
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $indivRank = 1;
            $prev_result = '';
            for($intA=0;$intA<count($value);$intA++){
                $player = new classJsportAchvPlayer($value[$intA]->partic_id,null,false);
                if($prev_result == $value[$intA]->pts){
                        $currank = $indivRank;
                    }else{
                        $currank = $intA+1;
                        $indivRank = $intA+1;
                    }
                echo '<tr>';
                echo '<td class="jsalcenter jsNoWrap"><span>'.($currank).'</span></td>';
                                    
                echo '<td class="jsNoWrap">'.($player->getName(true)).'</td>';
                //if($lists['ranking_criteria'] == '0'){
                echo '<td class="jsalcenter jsNoWrap">'.($value[$intA]->pts).'</td>';
                //}
                echo '</tr>';
                $prev_result = $value[$intA]->pts;
            }
            ?>
        </tbody>
    </table>
    
</div>   
<?php
    }
}
}
?>
<script>
    jQuery(document).ready(function() {
        var theHeaders = {}
        jQuery('.jstable_achv_season').find('th.noSort').each(function(i,el){
            theHeaders[jQuery(this).index()] = { sorter: false };
        });
        jQuery('.jstable_achv_season').tablesorter({headers: theHeaders});
    } );
</script>  
