<?php
?>
<div>
    <form action="" name="formPlayerEditFE" id="formPlayerEditFE" autocomplete="off">

        <?php
        JoomSportMetaPlayer::js_meta_personal($playerPost);
        //JoomSportMetaTeam::js_meta_about($thisPost);
        JoomSportMetaPlayer::js_meta_ef($playerPost);
        ?>

        <div class="jsmodsave pull-right clearfix">
            <input type="submit" class="btn btn-success" value="<?php echo esc_attr(__('Save','joomsport-sports-league-results-management'));?>" />
            <input type="hidden" name="playerID" value="<?php echo esc_attr($playerID)?>" />
        </div>
    </form>
</div>
