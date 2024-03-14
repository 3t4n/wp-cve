<?php
$seasons = JoomsportModerateHelper::getSeasonsParticipated();
?>
<div>
    <form action="" name="formMatchAddFE" id="formMatchAddFE">
        <table class="table table-striped">
            <tr>
                <td class="jsleft">
                    <label><?php echo __("Select Season", "joomsport-sports-league-results-management");?></label>
                </td>
                <td>
                    <?php
                    //select season
                    if(count($seasons)){
                        echo '<select id="MatchADDseasonId" name="MatchADDseasonId" class="jswf-chosen-select form-control">';
                        echo '<option value="0">'.__('Select','joomsport-sports-league-results-management').'</option>';
                        foreach ($seasons as $key => $value) {
                            for($intA = 0; $intA < count($value); $intA++){
                                $tm = $value[$intA];
                                echo '<option value="'.intval($tm->id).'">'.$key .' '.$tm->name.'</option>';
                            }

                        }
                        echo '</select>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="jsleft">
                    <label><?php echo __("Select Matchday", "joomsport-sports-league-results-management");?></label>
                </td>
                <td>
                    <div id="jsModerLoadMdays">
                    </div>
                </td>
            </tr>
        </table>
        <div id="jsModerLoadMdayMatches">

        </div>
    </form>
</div>
