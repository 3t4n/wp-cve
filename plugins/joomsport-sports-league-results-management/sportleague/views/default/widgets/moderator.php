<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
$user = get_userdata( get_current_user_id() );

$user_roles = isset($user->roles)?$user->roles:null;

if ( !is_array($user_roles) || !in_array( 'joomsport_moderator', $user_roles, true ) ) {
    echo __("You need to be Moderator to manage stats. Please contact site admin.",'joomsport-sports-league-results-management');
}else {
    ?>
    <div class="jsmoderContainer">
        <div id="jsmoderMessages"></div>
        <div class="jsmoderFilter"></div>
        <ul class="nav nav-tabs jsmoderTabs">
            <li class="nav-item">
                <div id="jsmoderTabsTeams" class="navlink active">
                    <i class="js-team"></i>
                    <span>Teams</span>
                </div>
            </li>
            <li class="nav-item">
                <div id="jsmoderTabsPlayers" class="navlink">
                    <i class="js-pllist"></i>
                    <span>Players</span>
                </div>
            </li>
            <li class="nav-item">
                <div id="jsmoderTabsMatches" class="navlink">
                    <i class="js-match"></i>
                    <span>Matches</span>
                </div>
            </li>
        </ul>

        <div class="jsmoderInner">
            <?php require JOOMSPORT_PATH_VIEWS . DIRECTORY_SEPARATOR . 'moder' . DIRECTORY_SEPARATOR . 'team_list.php'; ?>
        </div>
        <div id="jsmoderInnerAjax"></div>
    </div>
    <?php
}