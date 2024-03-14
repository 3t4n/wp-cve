<table class="mglTable table" id="mglMatchDay">
    <thead>
        <tr>
            <th class="jscenter">#</th>
            <th class="jsleft">
                <?php echo __('Home', 'joomsport-sports-league-results-management');?>
            </th>
            <th class="jscenter">
                <?php echo __('Score', 'joomsport-sports-league-results-management');?>
            </th>
            <th class="jsleft">
                <?php echo __('Away', 'joomsport-sports-league-results-management');?>
            </th>
            <th class="jscenter">
                <?php echo __('Date', 'joomsport-sports-league-results-management');?>
            </th>
            <th class="jscenter">
                <?php echo __('Time', 'joomsport-sports-league-results-management');?>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php

    for($intA = 0; $intA < count($matches); $intA ++){
        //var_dump($matches->posts[$intA]);
        //continue;
        $match = $matches[$intA];
        $home_team = get_post_meta( $match->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $match->ID, '_joomsport_away_team', true );
        $home_score = get_post_meta( $match->ID, '_joomsport_home_score', true );
        $away_score = get_post_meta( $match->ID, '_joomsport_away_score', true );
        $m_played = get_post_meta( $match->ID, '_joomsport_match_played', true );
        $m_date = get_post_meta( $match->ID, '_joomsport_match_date', true );
        $m_time = get_post_meta( $match->ID, '_joomsport_match_time', true );
        ?>
        <tr>
            <td class="jscenter">
                <?php
                //if(current_user_can('delete_jscp_match', $match->ID)){
                    ?>
                    <i class="fa fa-trash jsmoderDelMatch" data-id="<?php echo esc_attr($match->ID);?>" aria-hidden="true"></i></a>
                    <?php
                //}
                ?>
                <input type="hidden" name="match_id[]" value="<?php echo esc_attr($match->ID);?>">
            </td>

            <td>
                <?php echo get_the_title($home_team);?><input type="hidden" name="home_team[]" value="<?php echo esc_attr($home_team);?>">
            </td>
            <td class="jscenter" nowrap="nowrap">
                <?php
                if($m_played){
                    echo esc_html($home_score.":".$away_score);
                }
                ?>
            </td>
            <td>
                <?php echo get_the_title($away_team);?><input type="hidden" name="away_team[]" value="<?php echo esc_attr($away_team);?>">
            </td>
            <?php
                echo '<td class="jscenter">'.esc_html($m_date).'</td>';
                echo '<td class="jscenter">'.esc_html($m_time).'</td>';
            ?>
            <td class="jscenter">
                <?php //if(JoomSportUserRights::isAdmin() || JoomsportSettings::get('moder_edit_matches_reg', 0)){?>
                    <input type="button" data-id="<?php echo esc_attr($match->ID)?>" class="button jsModerEditMatch btn btn-default" value="<?php echo __('Details', 'joomsport-sports-league-results-management');?>">
                <?php //} ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    <?php
    //if(current_user_can('edit_jscp_matchs') && $canAddMatches){
        ?>
        <tfoot>
            <tr>
                <td></td>
                <td class="jsleft">
                    <select name="set_home_team" id="set_home_team" class="form-control">
                        <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                        <?php
                        if(count($participiants)){
                            foreach ($participiants as $part) {
                                echo '<option value="'.$part->ID.'">'.$part->post_title.'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td class="jscenter" nowrap="nowrap"></td>
                <td class="jsleft">
                    <select name="set_away_team" id="set_away_team" class="form-control">
                        <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                        <?php
                        if(count($participiants)){
                            foreach ($participiants as $part) {
                                echo '<option value="'.$part->ID.'">'.$part->post_title.'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <?php
                echo '<td class="jscenter"><input type="text" placeholder="YY-mm-dd" size="12" class="jsdatefield form-control" name="m_date_foot" id="m_date_foot" value="" /></td>';
                echo '<td class="jscenter"><input type="time" placeholder="H:i" class="form-control" name="m_time_foot" size="12" id="m_time_foot" value="" /></td>';
                ?>
                <td class="jscenter">
                    <input type="button" class="button mgl-moder-add-button btn btn-primary" value="<?php echo __("Add New", 'joomsport-sports-league-results-management');?>" />
                </td>
            </tr>
        </tfoot>
        <?php
    //}
    ?>
</table>
