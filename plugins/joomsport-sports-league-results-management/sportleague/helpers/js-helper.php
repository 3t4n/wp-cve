<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class jsHelper
{
    public static function getADF($ef, $suff = '')
    {
        $return = '';
        if (count($ef)) {
            foreach ($ef as $key => $value) {
                if ($value != null) {
                    $return .=  '<div class="jstable-row">';
                    $return .=  '<div class="jstable-cell"><strong>'.$key.':</strong></div>';
                    $return .=  '<div class="jstable-cell">'.$value.'</div>';
                    $return .=  '</div>';
                }
            }
        }
        if ($return) {
            $return = '<div class="jstable">'.$return.'</div>';
        }
        //$return .= '</div>';
        return $return;
    }

    public static function getMatches($matches, $lists = null, $mdname = true)
    {
        $html = '';
        $pagination = isset($lists['pagination'])?$lists['pagination']:null;
        if (count($matches)) {
            $html .= '<div class="table-responsive">';
            if (self::isMobile()) {
                $html .= '<div class="jstable jsMatchDivMainMobile">';
            } else {
                $html .= '<div class="jstable jsMatchDivMain">';
            }

            $md_id = 0;
            for ($intA = 0; $intA < count($matches); ++$intA) {
                $match = $matches[$intA];

                if (JSCONF_ENBL_MATCH_TOOLTIP && isset($match->lists['m_events_home']) && (count($match->lists['m_events_home']) || count($match->lists['m_events_away']))) {
                    $tooltip = '<div style="overflow:hidden;" class="tooltipInnerHtml"><div class="jstable jsInline" '.(count($match->lists['m_events_home']) >= count($match->lists['m_events_away']) ? 'style="border-right:1px solid #ccc;"' : '').'>';

                    for ($intP = 0; $intP < count($match->lists['m_events_home']); ++$intP) {
                        $tooltip .= '<div class="jstable-row">
                                <div class="jstable-cell">
                                    <div class="jsEvent">'.$match->lists['m_events_home'][$intP]->objEvent->getEmblem().'</div>
                                </div>
                                <div class="jstable-cell">
                                    '.$match->lists['m_events_home'][$intP]->obj->getName().'
                                </div>
                                <div class="jstable-cell">
                                    '.$match->lists['m_events_home'][$intP]->ecount.'
                                </div>
                                <div class="jstable-cell">
                                    '.($match->lists['m_events_home'][$intP]->minutes ? $match->lists['m_events_home'][$intP]->minutes."'" : '').'
                                </div>
                            </div>';
                    }
                    if (!count($match->lists['m_events_home'])) {
                        $tooltip .= '&nbsp';
                    }

                    $tooltip .= '</div>';
                    $tooltip .= '<div class="jstable jsInline" '.(count($match->lists['m_events_home']) < count($match->lists['m_events_away']) ? 'style="border-right:1px solid #ccc;"' : '').'>';

                    for ($intP = 0; $intP < count($match->lists['m_events_away']); ++$intP) {
                        $tooltip .= '<div class="jstable-row">
                                <div class="jstable-cell">
                                    <div class="jsEvent">'.$match->lists['m_events_away'][$intP]->objEvent->getEmblem().'</div>
                                </div>
                                <div class="jstable-cell">
                                    '.$match->lists['m_events_away'][$intP]->obj->getName().'
                                </div>
                                <div class="jstable-cell">
                                    '.$match->lists['m_events_away'][$intP]->ecount.'
                                </div>
                                <div class="jstable-cell">
                                    '.($match->lists['m_events_away'][$intP]->minutes ? $match->lists['m_events_away'][$intP]->minutes."'" : '').'
                                </div>
                            </div>';
                    }

                    $tooltip .= '</div>';
                } else {
                    $tooltip = '';
                }
                $m_date = get_post_meta($match->id,'_joomsport_match_date',true);
                $m_time = get_post_meta($match->id,'_joomsport_match_time',true);
                    $partic_home = $match->getParticipantHome();
                    $partic_away = $match->getParticipantAway();
                $match_date = classJsportDate::getDate($m_date, $m_time);

                if (self::isMobile()) {
                    $html .= '<div class="jsMobileMatchCont">';
                    if ($md_id != $match->getMdayID() && JoomsportSettings::get('enbl_mdnameoncalendar',1) == '1' && $mdname) {
                        $html .= '<div class="jsDivMobileMdayName">'.$match->getMdayName().'</div>';
                        $md_id = $match->getMdayID();
                        $html .= '<div class="jsDivMobileGroup"></div>';
                    }
                    
                    $html .= '<div class="jsMatchDivInfo">'.$match_date;
                    if (JoomsportSettings::get('cal_venue',1)) {
                        $html .= '<div class="jsMatchDivVenue">'.$match->getLocation().'</div>';
                    }
                    $html .= '</div>';

                    $html .= '<div class="jsMatchDivScore">
                                <div class="jsMatchDivHome">';
                    if(is_object($partic_home)){  
                        $html .= ($partic_home->getEmblem());
                    }
                    $html .= '<div class="jsDivTeamName">';
                    if(is_object($partic_home)){  
                        $html .= self::nameHTML($partic_home->getName(true));
                    }
                    $html .= '</div></div>';
                    $html .= '<div class="jsScoreBonusB">'.self::getScore($match, '');
                    $html .= '</div>
                                <div class="jsMatchDivAway">';
                    if(is_object($partic_away)){  
                        $html .= ($partic_away->getEmblem());
                    }
                    $html .= '<div class="jsDivTeamName">';
                    if(is_object($partic_away)){  
                        $html .= self::nameHTML($partic_away->getName(true));
                    }
                    $html .= '</div></div></div>';

                    $html .= '</div>';
                } else {
                    $curMD = $match->getMdayID();
                    if ($md_id != $curMD && JoomsportSettings::get('enbl_mdnameoncalendar',1) == '1' && $mdname) {
                        $html .= '<div class="jstable-row js-mdname"><div class="jsrow-matchday-name">'.$match->getMdayName().'</div></div>';
                        $md_id = $curMD;
                    }

                    $html .= '<div class="jstable-row">
                            <div class="jstable-cell jsMatchDivTime">
                                <div class="jsDivLineEmbl">'

                                    .$match_date
                                .'</div>'
                            .'</div>'
                            .'<div class="jstable-cell jsMatchDivHome">
                                <div class="jsDivLineEmbl">';
                                if(is_object($partic_home)){    
                                    $html .= self::nameHTML($partic_home->getName(true));
                                }    
                    $html .=    '</div>'
                            .'</div>'
                            .'<div class="jstable-cell jsMatchDivHomeEmbl">'
                                .'<div class="jsDivLineEmbl pull-right">';
                                if(is_object($partic_home)){ 
                                    $html .= ($partic_home->getEmblem());
                                }            
                    $html .=    '</div>

                            </div>
                            <div class="jstable-cell jsMatchDivScore">
                                '.self::getScore($match, '', $tooltip).'
                            </div>
                            <div class="jstable-cell jsMatchDivAwayEmbl">
                                <div class="jsDivLineEmbl">';
                                    if(is_object($partic_away)){ 

                                        $html .= ($partic_away->getEmblem());
                                    }    
                    $html .=    '</div>'
                            .'</div>'
                            .'<div class="jstable-cell jsMatchDivAway">'
                                .'<div class="jsDivLineEmbl">';
                                if(is_object($partic_away)){    
                                    $html .= self::nameHTML($partic_away->getName(true), 0);
                                }    
                    $html .=    '</div>'   
                            .'</div>';
                    if (JoomsportSettings::get('cal_venue',1)) {
                        $html .= '<div class="jstable-cell jsMatchDivVenue">'
                                        .$match->getLocation()
                                    .'</div>';
                    }
                    if (isset($lists['ef_table']) && count($lists['ef_table'])) {
                        foreach ($lists['ef_table'] as $ef) {
                            $efid = 'ef_'.$ef->id;
                            $html .= '<div class="jstable-cell jsNoWrap">'
                                    .$match->{$efid}
                            
                                .'</div>';
                            

                        }
                    }
                    $html .= '</div>';
                }
            }

            $tooltip .= '</div></div>';
            $html .= '</div></div>';
            if ($pagination) {
                require_once JOOMSPORT_PATH_VIEWS.'elements'.DIRECTORY_SEPARATOR.'pagination.php';
                $html .= paginationView($pagination);
            }

            return $html;
        }
    }
    public static function getScore($match, $class = '', $tooltip = '', $itemid = 0, $ft = false)
    {
        $html = '';
        $jmscore = get_post_meta($match->id, '_joomsport_match_jmscore',true);

        if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
            $away_score = get_post_meta( $match->id, '_joomsport_home_score', true );
            $home_score = get_post_meta( $match->id, '_joomsport_away_score', true );
            if($ft && isset($jmscore["is_extra"]) && $jmscore["is_extra"] == 1){
                if(intval($jmscore["aet1"]) > 0){
                    $away_score -= $jmscore["aet1"];
                }
                if(intval($jmscore["aet2"]) > 0){
                    $home_score -= $jmscore["aet2"];
                }
            }
        }else{
            $home_score = get_post_meta( $match->id, '_joomsport_home_score', true );
            $away_score = get_post_meta( $match->id, '_joomsport_away_score', true );
            if($ft && isset($jmscore["is_extra"]) && $jmscore["is_extra"] == 1){
                if(intval($jmscore["aet1"]) > 0){
                    $home_score -= $jmscore["aet1"];
                }
                if(intval($jmscore["aet2"]) > 0){
                    $away_score -= $jmscore["aet2"];
                }
            }
        }
        $m_played = get_post_meta( $match->id, '_joomsport_match_played', true );
        
        if (in_array($m_played,array(-1,1))) {
            $text = $home_score.JSCONF_SCORE_SEPARATOR.$away_score;
            $html .= classJsportLink::match($text, $match->id, false, '', $itemid);
        } elseif ($m_played == '0' || $m_played == '') {
            $html .= classJsportLink::match(JSCONF_SCORE_SEPARATOR_VS, $match->id, false, '', $itemid);
        } else {
            
            if ($match->lists['mStatuses']) {
                foreach($match->lists['mStatuses'] as $ml){
                    if(isset($ml->id) && $ml->id == $m_played){ 
                        $tooltip = $ml->stName;
                        $html .= $ml->stShort;
                    }
                }
                
            } else {
                $html .= JSCONF_SCORE_SEPARATOR_VS;
            }
        }
        $partic_home = $match->getParticipantHome();
        $partic_away = $match->getParticipantAway();
        if(!is_object($partic_home) && !is_object($partic_away)){
            $html = classJsportLink::match(get_the_title($match->id), $match->id, false, '', $itemid);;
        }
        
        $htmlLive = '';
        if($m_played == -1){
            $liveWrd = __("Live", 'joomsport-sports-league-results-management' );
            $ticker_html = self::matchTicker($match->id);
            $htmlLive = '<div class="jscalendarLive">'.($ticker_html?$ticker_html:$liveWrd).'</div>';
        }
        
        //$tooltip = '<table><tr><td style="width:200px;background-color:blue; vertical-align:top;"><div>Player 1 goal 55min</div><div>Player 1 goal 55min</div><div>Player 1 goal 55min</div></td><td style="background-color:red;vertical-align:top; width:50%;"><div>Player 1 goal 55min</div></td></tr></table>';
        return '<div class="jsScoreDiv '.$class.'" data-toggle2="tooltip" data-placement="bottom" title="" data-original-title="'.htmlspecialchars(($tooltip)).'">'.$htmlLive.$html.$match->getETLabel().'</div>'.$match->getBonusLabel();
    }
    public static function getScoreBigM($match)
    {
        $html = '';

        if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
            $away_score = get_post_meta( $match->id, '_joomsport_home_score', true );
            $home_score = get_post_meta( $match->id, '_joomsport_away_score', true );
        }else{
            $home_score = get_post_meta( $match->id, '_joomsport_home_score', true );
            $away_score = get_post_meta( $match->id, '_joomsport_away_score', true );
        }
        $m_played = get_post_meta( $match->id, '_joomsport_match_played', true );
        $jmscore = get_post_meta($match->id, '_joomsport_match_jmscore',true);
        if (in_array($m_played,array(-1,1))) {
            $bonus1 = '';
            $bonus2 = '';
            $sep = JSCONF_SCORE_SEPARATOR;
            if(isset($jmscore['bonus1'])){
                if ($jmscore['bonus1'] != '' || $jmscore['bonus2'] != '') {
                    $bonus1 = '<div class="jsHmBonus">'.floatval($jmscore['bonus1']).'</div>';
                    $bonus2 = '<div class="jsAwBonus">'.floatval($jmscore['bonus2']).'</div>';
                }
            }
            $html .= "<div class='BigMScore1'>".$home_score.'</div>';
            $html .= "<div class='BigMScore2'>".$away_score.'</div>';
        } elseif ($m_played == '0') {
            $sep = JSCONF_SCORE_SEPARATOR_VS;
        } else {
            if ($match->lists['mStatuses']) {
                foreach($match->lists['mStatuses'] as $ml){
                    //var_dump($ml);
                    if(isset($ml->id) && $ml->id == $m_played){ 
                        
                        $tooltip = $ml->stName;
                        
                        $html .= "<div class='customStatusBig'>".$ml->stShort."</div>";
                    }
                }
                
            } else {
                $sep = JSCONF_SCORE_SEPARATOR_VS;
            }
        }
        $htmlLive = '';
        if($m_played == -1){
            $ticker_html = self::matchTicker($match->id);
            echo '<input type="hidden" id="match_id" value="'.esc_attr($match->id).'" /><script>js_live_interval_guest = setInterval(jsLiveCheckUpdts, 30000);</script>';
            $htmlLive = '<div class="jscalendarLiveBig">'.__("Live", 'joomsport-sports-league-results-management' ).' <span id="jsMatchBigTicker">'.$ticker_html.'</span></div>';
        }

        //$html .= '<div class="matchSeparator">'.$sep.'</div>';

        //$tooltip = '<table><tr><td style="width:200px;background-color:blue; vertical-align:top;"><div>Player 1 goal 55min</div><div>Player 1 goal 55min</div><div>Player 1 goal 55min</div></td><td style="background-color:red;vertical-align:top; width:50%;"><div>Player 1 goal 55min</div></td></tr></table>';
        return '<div class="jsScoreDivM">'.$htmlLive.$html.$match->getETLabel(false).'</div>';
    }
    public static function getMap($maps, $class = '')
    {
        global $wpdb;
        
        $html = '<div class="jsMatchStages jscenter">';
        if(count($maps)){
            
            foreach ($maps as $key => $value) {
                
                if($value[0] !== '' || $value[1] !== ''){
                    
                $sql = "SELECT id FROM {$wpdb->joomsport_maps} WHERE id=".intval($key);
                if($wpdb->get_var($sql)){
                    if(JoomsportSettings::get('partdisplay_awayfirst',0) == 1){
                        $home_map = $value[1];
                        $away_map = $value[0];
                    }else{
                        $home_map = $value[0];
                        $away_map = $value[1];
                    }
                    $html .= '<div class="jsScoreDivMap '.$class.'">'.$home_map.JSCONF_SCORE_SEPARATOR.$away_map.'</div>';
            
                }
                }
            }
        }
        

        $html .= '</div>';

        return $html;
    }
    public static function nameHTML($name, $home = 1, $class = '')
    {
        return '<div class="js_div_particName">'.$name.'</div>';
    }

    public static function JsHeader($options)
    {
        $kl = '';
        if (classJsportRequest::get('tmpl') != 'component') {
            $kl .= '<div class="">';
            $kl .= '<nav class="navbar navbar-default navbar-static-top" role="navigation">';
            $kl .= '<div class="navbar-header navHeadFull">';

            $img = JoomsportSettings::get('jsbrand_epanel_image');
            $brand = JoomsportSettings::get('jsbrand_on',1) ? 'JoomSport' : '';

            if ($img && is_file(JSPLW_PATH_MAINCOMP.$img)) {
                $kl .= '<a class="module-logo" href="'.classJsportLink::seasonlist().'" title="'.esc_attr($brand).'"><img src="'.JOOMSPORT_LIVE_URL.$img.'" style="height:38px;" alt="'.$brand.'"></a>';
            }

            $kl .= '<ul class="nav navbar-nav pull-right navSingle">';
                //calendar
            if (isset($options['calendar']) && $options['calendar']) {
                $link = classJsportLink::calendar('', $options['calendar'], true);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="js-calendr"></i>'.__('Calendar','joomsport-sports-league-results-management').'</a>';
            }
                //table
            if (isset($options['standings']) && $options['standings']) {
                $link = classJsportLink::season('', $options['standings'], true);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="js-stand"></i>'.__('Standings','joomsport-sports-league-results-management').'</a>';
            }
                //join season
            if (isset($options['joinseason']) && $options['joinseason']) {
                $link = classJsportLink::joinseason($options['joinseason']);
                //$kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="js-join"></i>'.__('Register','joomsport-sports-league-results-management').'</a>';
            }
                //join team
            if (isset($options['jointeam']) && $options['jointeam']) {
                $link = classJsportLink::jointeam($options['jointeam']['seasonid'], $options['jointeam']['teamid']);
                //$kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="js-join"></i>'.__('Join team','joomsport-sports-league-results-management').'</a>';
            }

            if (isset($options['playerlist']) && $options['playerlist']) {
                $link = classJsportLink::playerlist($options['playerlist']);
                $kl .= '<a class="btn btn-default" href="'.$link.'" title=""><i class="js-pllist"></i>'.__('Player list','joomsport-sports-league-results-management').'</a>';
            }
            $kl .= classJsportPlugins::get('addHeaderButton', null);
            $kl .= '</ul></div></nav></div>';
        }
        //$kl .= self::JsHistoryBox($options);
        $kl .= self::JsTitleBox($options);
        $kl .= "<div class='jsClear'></div>";

        return $kl;
    }

    public static function JsTitleBox($options)
    {
        $kl = '';
        $kl .= '<div class="heading col-xs-12 col-lg-12">
                    <div class="heading col-xs-6 col-lg-6">
                        <!--h2>
                           
                        </h2-->
                    </div>
                    <div class="selection col-xs-6 col-lg-6 pull-right">
                        <form method="post">
                            <div class="data">
                                '.(isset($options['tourn']) ? $options['tourn'] : '').'
                                <input type="hidden" name="jscurtab" value="" />    
                            </div>
                        </form>
                    </div>
                </div>';

        return $kl;
    }

    public static function JsHistoryBox($options)
    {
        $kl = '<div class="history col-xs-12 col-lg-12">
          <ol class="breadcrumb">
            <li><a href="javascript:void(0);" onclick="history.back(-1);" title="[Back]">
                <i class="fa fa-long-arrow-left"></i>[Back]
            </a></li>
          </ol>
          <div class="div_for_socbut">'.(isset($options['print']) ? '' : '').'<div class="jsClear"></div></div>
        </div>';

        return $kl;
    }

    public static function JsFormViewElement($match, $partic_id)
    {
        $from_str = '';
        if (isset($match) && $match) {
            if (isset($match->object)) {
                $match_object = $match;
                $match = $match->object;
                /*
                 * $allmeta = get_post_meta( $match->ID,'',true);
                
                $home_score = $allmeta['_joomsport_home_score'][0];
                $away_score = $allmeta['_joomsport_away_score'][0];
                $home_team = $allmeta['_joomsport_home_team'][0];
                $away_team = $allmeta['_joomsport_away_team'][0];
                 */
                $home_score = get_post_meta( $match->ID, '_joomsport_home_score', true );
                $away_score = get_post_meta( $match->ID, '_joomsport_away_score', true );
                $home_team = get_post_meta( $match->ID, '_joomsport_home_team', true );
                $away_team = get_post_meta( $match->ID, '_joomsport_away_team', true );
            }
            if ($home_score == $away_score) {
                $class = 'match_draw';
                $alpha = __( 'D', 'joomsport-sports-league-results-management' );
            } else {
                if (($home_score > $away_score && $home_team == $partic_id)
                     ||
                   ($home_score < $away_score && $away_team == $partic_id)
                        ) {
                    $class = 'match_win';
                    $alpha = __( 'W', 'joomsport-sports-league-results-management' );
                } else {
                    $class = 'match_loose';
                    $alpha = __( 'L', 'joomsport-sports-league-results-management' );
                }
            }
            if (!isset($match->home)) {
                $partic_home = $match_object->getParticipantHome();
                $partic_away = $match_object->getParticipantAway();
                if(is_object($partic_home)){
                    $home = $partic_home->getName(false);
                }else{
                    $home = '';
                }
                if(is_object($partic_away)){
                    $away = $partic_away->getName(false);
                }else{
                    $away = '';
                }
            } else {
                $home = $match->home;
                $away = $match->away;
            }

            $title = $home_score.':'.$away_score.' ('.$home.' - '.$away.')'."\n".$match->m_date.' '.$match->m_time;
            $link = classJsportLink::match('', $match->ID, true);
            $from_str .= '<a href="'.$link.'" title="'.esc_attr($title).'" class="jstooltip"><span class="jsform_none '.$class.'">'.$alpha.'</span></a>';
        } else {
            $from_str = '<span class="jsform_none match_quest">?</span>'.$from_str;
        }

        return $from_str;
    }
    
    public static function getBoxValue($box_id, $row){
        global $wpdb;
        $boxfield = 'boxfield_'.$box_id;
        
        $cBoxAll = jsHelperBoxScore::getInstance();
        
        $cBox = $cBoxAll[$box_id];
        $options = json_decode($cBox->options, true);

        if($cBox->ftype == '1' && isset($options['calc'])){
            $boxfield1 = 'boxfield_'.$options['depend1'];
            $boxfield2 = 'boxfield_'.$options['depend2'];
            if(isset($row->{$boxfield1}) && $row->{$boxfield1} != NULL && isset($row->{$boxfield2}) && $row->{$boxfield2} != NULL){

                switch ($options['calc']) {
                    
                    case '0':
                        if($row->{$boxfield2}){
                            $res =  $row->{$boxfield1} / $row->{$boxfield2};
                        }else{
                            $res = 0;
                        }
                        return ($res !== NULL?round($res,2):'');
                        break;
                    case '1':
                        $res =  $row->{$boxfield1} * $row->{$boxfield2};
                        return ($res !== NULL?round($res,2):'');
                        break;
                    case '2':
                        $res =  $row->{$boxfield1} + $row->{$boxfield2};
                        return ($res !== NULL?round($res,2):'');

                        break;
                    case '3':
                        $res =  $row->{$boxfield1} - $row->{$boxfield2};
                        return ($res !== NULL?round($res,2):'');

                        break;
                    case '4':
                        return $row->{$boxfield1}.'/'.$row->{$boxfield2};

                        break;
                    default:
                        break;
                }
                
                
            }
            
        }
        
        $res = isset($row->{$boxfield})?$row->{$boxfield}:NULL;
        
        return ($res !== NULL?round($res,2):'');
    }

    public static function getPostsAsArray($posts){
        $returnArr = array();
        for($intA=0;$intA<count($posts);$intA++){
            $returnArr[] = $posts[$intA]->ID;
        }
        return $returnArr;
    }

    public static function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER['HTTP_USER_AGENT']);
    }

    public static function matchTicker($match_id){
        $ticker_html = '';
        $ticker = get_post_meta($match_id, '_joomsport_match_ticker', true);

        if($ticker && isset($ticker["active"])){
            if($ticker["active"] == '1'){
                $offset = floor((time() - $ticker["startgmt"])/60) + intval($ticker["offset"]);

                $ticker_html = $offset.(intval($offset)?"'":"");
                if(intval($ticker["offset"]) && intval($ticker["offset"]) <= 45 && intval($offset) > 45){
                    $ticker_html = "45'+";
                }
                if(intval($ticker["offset"]) && intval($ticker["offset"]) > 45 && intval($offset) > 90){
                    $ticker_html = "90'+";
                }
                if(intval($ticker["offset"]) && intval($ticker["offset"]) > 90 && intval($offset) > 120){
                    $ticker_html = "120'+";
                }

            }else{
                $ticker_html = $ticker["offset"].(intval($ticker["offset"])?"'":"");
            }
        }

        return $ticker_html;
    }

    public static function getSeasonTeamsStat($season_id, $event_id){
        global $wpdb;

        //
        $matches = $wpdb->get_results("SELECT * FROM {$wpdb->joomsport_matches} WHERE seasonID={$season_id} AND status='1'");
        $partc = array();
        for($intA=0;$intA<count($matches);$intA++){
            $metadata = get_post_meta($matches[$intA]->postID,'_joomsport_matchevents',true);
            if(isset($metadata[$event_id])){
                if(isset($metadata[$event_id]["mevents1"]) && $metadata[$event_id]["mevents1"] != '' && $matches[$intA]->teamHomeID){
                    $partc[$matches[$intA]->teamHomeID][] = floatval($metadata[$event_id]["mevents1"]);
                }
                if(isset($metadata[$event_id]["mevents2"]) && $metadata[$event_id]["mevents2"] != '' && $matches[$intA]->awayHomeID){
                    $partc[$matches[$intA]->awayHomeID][] = floatval($metadata[$event_id]["mevents2"]);
                }
            }
        }

        if(count($partc)){
            foreach ($partc as $key => $value){
                if(count($value)){
                    $sum = array_sum($value);
                    $avg = $sum/count($value);
                    $wpdb->insert($wpdb->joomsport_teamstats, array("seasonID"=>$season_id,"partID"=>$key,"eventID"=>$event_id,"sumVal"=>$sum,"avgVal"=>$avg));
                }


            }
        }
        //die();
    }

}