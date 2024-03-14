<?php

$players = JoomsportModerateHelper::getModerPlayers();

if(JoomsportModerateHelper::Can('player.add', 0)){ // can add Team
    ?>
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <ul class="nav navbar-nav pull-right">
                <button id="jsModerNewPlayer" class="btn btn-default"><?php echo __('New Player','joomsport-sports-league-results-management');?></button>
            </ul>
        </div>
    </nav>
    <?php
}
?>
<table class="table table-striped">
    <?php
    for($intA=0;$intA<count($players);$intA++){
        $teamObj = new JoomsportModerateTeam($players[$intA]->ID);
        echo '<tr>';
        echo '<td class="jsmodtrash jscenter">';
        if(JoomsportModerateHelper::Can('player.del', $players[$intA]->ID)){
            echo '<i class="fa fa-trash jsmoderDelPlayer" data-id="'.esc_attr($players[$intA]->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '<td class="jsleft">'.wp_kses_post(get_the_title($players[$intA]->ID)).'</td>';
        echo '<td class="jsmodedit jscenter">';
        if(JoomsportModerateHelper::Can('player.edit', $players[$intA]->ID)){
            echo '<i class="fa fa-edit jsModerEditPlayer" data-id="'.esc_attr($players[$intA]->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>