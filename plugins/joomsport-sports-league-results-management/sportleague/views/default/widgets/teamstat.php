<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
?>
<div id="joomsport-container">
    <div class="table-responsive">
        <table class="table">
            <?php
            if(count($res)){
                ?>
                <thead>
                    <tr>
                        <th class="jsalignleft"><?php echo __('Team','joomsport-sports-league-results-management');?></th>

                        <th class="jsaligncenter"><?php echo esc_html($eventObj->getEventName());?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($res as $team){
                        echo '<tr>';
                        $teamObj = new classJsportTeam($team->partID,$args['seasonid'],false);

                        echo '<td><div class="jsDivLineEmbl">' . $teamObj->getEmblem(true, 0, '');
                        echo jsHelper::nameHTML($teamObj->getName(true,0)) . '</div></td>';


                        echo '<td class="jsaligncenter">' .esc_html(round($team->{$counting},2)) .'</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>