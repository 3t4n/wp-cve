<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */


echo '<div id="joomsport-container" class="modJSLiveMatches">';
    echo '<div class="modJSLiveMatchesFilters clearfix">';
        echo '<div class="clearfix modJSLiveFields">';
            echo '<div class="col-xs-12 col-sm-6">';
                echo '<select name="modJSLiveMatchesFiltersSelect" id="modJSLiveMatchesFiltersSelect">';
                    echo '<option value="">'.__('All','joomsport-sports-league-results-management').'</option>';
                    echo '<option value="0">'.__('Fixtures','joomsport-sports-league-results-management').'</option>';
                    echo '<option value="1">'.__('Played','joomsport-sports-league-results-management').'</option>';
                    echo '<option value="-1">'.__('Live','joomsport-sports-league-results-management').'</option>';
                echo '</select>';
            echo '</div>';
            echo '<div class="col-xs-12 col-sm-6">';
                echo '<div class="modJSLiveInputGroup input-group">';
                    echo '<div class="input-group-btn"><button id="modJSLiveMatchesPrev" class="modJSCalendarBtn btn btn-default"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button></div>';
                    echo '<input type="text" class="jsdatefield hasDatepickerr" value="'.esc_attr(date("Y-m-d")).'" id="mod_filter_date" name="mod_filter_date" onChange="chngFilterLiveMatches(this.value);" />';
                    echo '<div class="input-group-btn"><button id="modJSLiveMatchesNext" class="modJSCalendarBtn btn btn-default"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button></div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        echo '<div class="col-xs-12">';
            echo '<ul class="modJSLiveMatchesTabUL">';
                echo '<li id="modJSLiveMatchesTabAll" class="activeTab">'.__('All','joomsport-sports-league-results-management').' <span id="modJsAllMatchCounter"></span></li>';
                echo '<li id="modJSLiveMatchesTabFav">'.__('Favourites','joomsport-sports-league-results-management').' <span id="modJsFavMatchCounter">0</span></li>';
            echo '</ul>';
        echo '</div>';
    echo '</div>';
    echo '<div id="modJSLiveMatchesContainer" class="clearfix">';
    require 'live-matches.php';
    echo '</div>';
echo '</div>';