<?php

$teams = JoomsportModerateHelper::getModerTeams();

if(JoomsportModerateHelper::Can('team.add', 0)){ // can add Team
    ?>
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <button id="jsModerNewTeam" class="btn btn-default"><?php echo __('New Team','joomsport-sports-league-results-management');?></button>
            </ul>
        </div>
    </nav>
    <?php
}
?>
<table class="table table-striped">
    <?php
    for($intA=0;$intA<count($teams);$intA++){
        $teamObj = new JoomsportModerateTeam($teams[$intA]->ID);
        echo '<tr>';
        echo '<td class="jsmodtrash jscenter">';
        if(JoomsportModerateHelper::Can('team.del', $teams[$intA]->ID)){
            echo '<i class="fa fa-trash jsmoderDelTeam" data-id="'.esc_attr($teams[$intA]->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '<td class="jsleft">'.wp_kses_post(get_the_title($teams[$intA]->ID)).'</td>';
        echo '<td class="jsmodedit jscenter">';
        if(JoomsportModerateHelper::Can('team.edit', $teams[$intA]->ID)){
            echo '<i class="fa fa-edit jsModerEditTeam" data-id="'.esc_attr($teams[$intA]->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>