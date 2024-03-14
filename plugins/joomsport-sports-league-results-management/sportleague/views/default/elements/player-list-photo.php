<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$playersHG = jsHelperHighlightPlayers::getInstance();
$show_departed = JoomsportSettings::get('show_departed','0')
?>
<div class="table-responsive" id="jsPlayerListContainer">
    <div class="jsOverflowHidden">
    <?php
    if (count($rows->lists['players'])) {
        foreach ($rows->lists['players'] as $key => $value) {

            if($key != '0' && count($value)){
                echo '<div class="jsGroupedPlayersHeader"><h2>'.$key.'</h2></div>';
            }
            for ($intA = 0; $intA < count($value); ++$intA) {

                $player = $value[$intA];
                if($player->lists["tblevents"]->departed == '1' && $show_departed == '0'){
                    continue;
                }
                ?>
                
                <div class="jsplayerCart<?php echo $player->lists["tblevents"]->departed == '1'?' jsplayerDeparted':''?>">
                    <?php
                     if(JoomsportSettings::get('enbl_playerlogolinks',1) == '1' || JoomsportSettings::get('enbl_playerlinks',1) == '1'
                     || (JoomsportSettings::get('enbl_playerlinks_hglteams') == '1' && in_array($player->object->ID,$playersHG))){

                         $link = classJsportLink::player('', $player->object->ID, $player->season_id, true);

                        echo '<a href="'.esc_url($link).'">';
                     }
                     ?>
                    <div class="jsplayerCartInner">
                        <div class="imgPlayerCart">
                            <div class="innerjsplayerCart">
                                <?php echo wp_kses_post($player->getEmblem(false, 10, 'emblInline', null, false));
                    ?>
                            </div>
                            <?php
                            /*if (count($rows->lists['ef_table'])) {
                                echo '<div class="jsPlPhListEF">';
                                foreach ($rows->lists['ef_table'] as $ef) {
                                    $keyEF = 'ef_'.$ef->id;
                                    $valueEF = $ef->name;
                                    echo '<div class="jsPlPhListEFChild">';
                                        echo '<div>';
                                        echo $valueEF;
                                        echo '</div>';
                                        echo '<div>';
                                        echo $player->{$keyEF};
                                        echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }*/
                            ?>
                        </div>
                        <div class="namePlayerCart">
                            <div class="LeftnamePlayerCart">
                                <div class="PlayerCardFIO">
                                    <?php echo jsHelper::nameHTML($player->getName(false));?>
                                </div>    
                                <?php if(isset($rows->lists['playercardef']) && $rows->lists['playercardef']){?>
                                    <div class="PlayerCardPos">
                                        <span>
                                            <?php
                                            if(isset($player->{'ef_'.$rows->lists['playercardef']})){
                                                echo esc_html($player->{'ef_'.$rows->lists['playercardef']});
                                            }
                                            ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>   
                            <?php
                            if(isset($rows->lists['playerfieldnumber']) && $rows->lists['playerfieldnumber']){
                            ?>
                            <div  class="PlayerCardPlNumber">
                                <?php
                                if(isset($player->{'ef_'.$rows->lists['playerfieldnumber']})){
                                    echo esc_html($player->{'ef_'.$rows->lists['playerfieldnumber']});
                                }
                                ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    if(JoomsportSettings::get('enbl_playerlogolinks',1) == '1' || JoomsportSettings::get('enbl_playerlinks',1) == '1'
                        || (JoomsportSettings::get('enbl_playerlinks_hglteams') == '1' && in_array($player->object->ID,$playersHG))){

                        echo '</a>';
                    }
                    ?>
                </div>    
                
                <?php
                

            }
        }
    }
    if(isset($rows->lists['team_staff']) && count($rows->lists['team_staff'])){
        for ($intS=0;$intS<count($rows->lists['team_staff']);$intS++) {
            $Ostaff = $rows->lists['team_staff'][$intS];
            echo '<div class="jsGroupedPlayersHeader"><h2>'.$Ostaff["name"].'</h2></div>';
            $obj = $Ostaff["obj"];
            ?>
                <div class="jsplayerCart">
                    <?php
                    if(JoomsportSettings::get('enbl_playerlogolinks',1) == '1' || JoomsportSettings::get('enbl_playerlinks',1) == '1'){
                     
                        $link = classJsportLink::person('', $obj->object->ID, 0, true); 

                        echo '<a href="'.esc_url($link).'">';
                    }
                    ?>
                    <div class="jsplayerCartInner">
                        <div class="imgPlayerCart">
                            <div class="innerjsplayerCart">
                                <?php echo wp_kses_post($obj->getEmblem(false, 10, 'emblInline', null, false));?>
                            </div>
                        </div>
                        <div class="namePlayerCart">
                            <div class="LeftnamePlayerCart">
                                <div class="PlayerCardFIO">
                                    <?php echo wp_kses_post(jsHelper::nameHTML($obj->getName(false)));?>
                                </div>    

                            </div>   

                        </div>
                    </div>  
                    <?php
                    if(JoomsportSettings::get('enbl_playerlogolinks',1) == '1' || JoomsportSettings::get('enbl_playerlinks',1) == '1'){
                     ?>
                        </a>
                    <?php } ?>    
                </div>
            <?php                
        }
    }
    ?>
    </div>
</div>
