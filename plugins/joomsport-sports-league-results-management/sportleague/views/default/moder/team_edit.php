<?php
?>
<div>
    <form action="" name="formTeamEditFE" id="formTeamEditFE" autocomplete="off">
        <div class="jstable">
            <div class="jstable-row">
                <div class="jstable-cell"><?php echo __('Title','joomsport-sports-league-results-management');?></div>
                <div class="jstable-cell">
                    <input type="text" class="form-control" value="<?php echo esc_attr(get_the_title($teamID));?>" name="teamName" />
                </div>
            </div>
        </div>

        <?php
        JoomSportMetaTeam::js_meta_personal($teamPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaTeam::js_meta_ef($teamPost);
        ?>

        <div class="jsmodnotice">
            <?php
            $results = JoomSportHelperObjects::getParticipiantSeasons($teamID);
            echo __('Select Season', 'joomsport-sports-league-results-management').'&nbsp;&nbsp;';
            if(!empty($results)){
                echo wp_kses(JoomSportHelperSelectBox::Optgroup('stb_season_id', $results, ''), JoomsportSettings::getKsesSelect());
                JoomSportMetaTeam::js_meta_players($teamPost);
            }else{
                echo '<div>'.__('Participant is not assigned to any season.', 'joomsport-sports-league-results-management').'</div>';
            }
            ?>
        </div>
        <div class="jsmodsave pull-right clearfix">
            <input type="submit" class="btn btn-success" value="<?php echo esc_attr(__('Save','joomsport-sports-league-results-management'));?>" />
            <input type="hidden" name="teamID" value="<?php echo esc_attr($teamID);?>" />
        </div>
    </form>
</div>
