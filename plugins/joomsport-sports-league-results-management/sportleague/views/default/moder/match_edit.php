<?php
?>
<div>
    <form action="" name="formMatchEditFE" id="formMatchEditFE">
        <?php
        JoomSportMetaMatch::js_meta_score($teamPost);
        JoomSportMetaMatch::js_meta_general($teamPost);
        JoomSportMetaMatch::js_meta_ef($teamPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaMatch::js_meta_playerevents($teamPost);
        JoomSportMetaMatch::js_meta_mevents($teamPost);
        JoomSportMetaMatch::js_meta_lineup($teamPost);
        JoomSportMetaMatch::js_meta_subs($teamPost);
        ?>

        <div class="jsmodsave pull-right clearfix">
            <input type="submit" class="btn btn-success" value="<?php echo esc_attr(__('Save','joomsport-sports-league-results-management'));?>" />
            <input type="hidden" name="matchID" value="<?php echo esc_attr($matchID);?>" />
        </div>
    </form>
</div>
