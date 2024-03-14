<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once JOOMSPORT_PATH_MODELS.'model-jsport-team.php';
require_once JOOMSPORT_PATH_CLASSES.'class-jsport-matches.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-club.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-venue.php';
require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-person.php';
require_once JOOMSPORT_PATH_ENV_CLASSES.'class-jsport-getplayers.php';

class classJsportTeam
{
    private $id = null;
    public $season_id = null;
    public $object = null;
    public $lists = null;
    public $model = null;
    public $matches_latest = 5;
    public $matches_next = 5;
    public $ef_type = null;
    public $ef_sort_id = null;

    public function __construct($id = 0, $season_id = null, $loadLists = true)
    {
        if (!$id) {
            $this->season_id = (int) classJsportRequest::get('sid');
            $this->id = (int) classJsportRequest::get('tid');
            $this->id = get_the_ID();
        } else {
            $this->season_id = $season_id;
            $this->id = $id;
        }
        
        if (!$this->id) {
            die('ERROR! Team ID not DEFINED');
        }

        $this->loadObject($loadLists);
    }

    private function loadObject($loadLists)
    {
        $obj = $this->model = new modelJsportTeam($this->id, $this->season_id);
        $this->object = $obj->getRow();
        if ($loadLists) {
            $this->lists = $obj->loadLists();
            $this->setHeaderOptions();
        }
    }

    public function getObject()
    {
        return $this->object;
    }
    /*
     * display
     * 0 - full name
     * 1 - middle name
     * 2 - short name
     */
    public function getName($linkable = false, $itemid = 0, $display = 1)
    {
        $pp = $this->model->row;
        //$teams = jsHelperAllTeamPosts::getInstance();
        //$pp = $teams[intval($this->id)];
        if(empty($pp)){
            return '';
        }
        if ($pp->post_status == 'publish' && get_post_status($this->id) != 'private') {
            
            $teamname = get_the_title($this->id);
            $shortenteam = JoomsportSettings::get('shortenteam',-1);
            if($shortenteam){
                $ef = get_post_meta($this->id,'_joomsport_team_ef',true);
                if(isset($ef[$shortenteam]) && $ef[$shortenteam] && jsHelper::isMobile()){
                    //jsHelper::isMobile()
                    $teamname = $ef[$shortenteam];
                }elseif($shortenteam == -1  && jsHelper::isMobile()){
                    $display = 2;
                }
                
            }
            
            $metadata = get_post_meta($this->id,'_joomsport_team_personal',true);
            if($display == 1){
                if(isset($metadata['middle_name']) && $metadata['middle_name']){
                    $teamname = $metadata['middle_name'];
                }
            }elseif($display == 2){
                if(isset($metadata['short_name']) && $metadata['short_name']){
                    $teamname = $metadata['short_name'];
                }
            }
            
            if (!$linkable || (JoomsportSettings::get('enbl_teamlinks',1) == '0' && (!in_array($this->id, JoomsportSettings::get('yteams',array())) || JoomsportSettings::get('enbl_teamhgllinks') != '1'))) {
                
                return $teamname;

            }
            $html = '';
            if ($this->id > 0 && $this->id) {
                $html = classJsportLink::team($teamname, $this->id, $this->season_id, false, $itemid);
            }

            return $html;
        }else{
            return get_the_title($this->id);
        }    
    }

    public function getDefaultPhoto()
    {

        if ($this->lists['def_img']) {
            return $this->lists['def_img'];
        }

        return JOOMSPORT_LIVE_URL_IMAGES_DEF.JSCONF_TEAM_DEFAULT_IMG;
    }
    public function getEmblem($linkable = true, $type = 0, $class = 'emblInline', $width = 0, $itemid = 0)
    {
        $pp = $this->model->row;
        if (empty($pp) || $pp->post_status != 'publish' || get_post_status($this->id) == 'private') {
            $linkable = false;
        }
        $html = '';
        if (has_post_thumbnail( $this->id ) ){
            
            //$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->id ), 'single-post-thumbnail' );
            $image= wp_get_attachment_image_src(get_post_thumbnail_id( $this->id ), array(JoomsportSettings::get('teamlogo_height',40),'0'));

            $html = $image[0];
        }
        $html = jsHelperImages::getEmblem($html, 1, $class, $width, addslashes($this->getName(false)));
        if ($linkable && JoomsportSettings::get('enbl_teamlogolinks',1) == '1') {
            $html = classJsportLink::team($html, $this->id, $this->season_id, '', $itemid);
        }

        return $html;
    }
    public function getRow()
    {
        return $this;
    }
    public function getTabs()
    {
        $tabs = array();
        $intA = 0;
        //main tab
        $tabs[$intA]['id'] = 'stab_main';
        $tabs[$intA]['title'] = __('Overview','joomsport-sports-league-results-management');
        $tabs[$intA]['body'] = 'team-view.php';
        $tabs[$intA]['text'] = '';
        $tabs[$intA]['class'] = '';
        $tabs[$intA]['ico'] = 'js-team';
        if(JoomsportSettings::get('enbl_club')){
            $this->getClub();
        }
        
        if(JoomsportSettings::get('unbl_venue',1) && JoomsportSettings::get('enabl_venue_stab_main',1)){
            $this->getVenue();
        }
        //matches
        $show_matchtab = JoomsportSettings::get('show_matchtab');
        $this->getMatches();
        if (count($this->lists['matches']) || $show_matchtab == '1') {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_matches';
            $tabs[$intA]['title'] = __('Matches','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = '';
            $this->lists['pagination'] = $this->lists['match_pagination'];
            $tabs[$intA]['text'] = '<form>'.jsHelper::getMatches($this->lists['matches'], $this->lists, false).'<input type="hidden" name="jscurtab" value="stab_matches" /><input type="hidden" name="sid" value="'.esc_attr($this->season_id).'" /></form>';
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-match';
        }

        $this->getPlayers();
        //roster
        $show_rostertab = JoomsportSettings::get('show_rostertab','1');
        if($show_rostertab){
            if (count($this->lists['players'])) {
                ++$intA;
                $tabs[$intA]['id'] = 'stab_players';
                $tabs[$intA]['title'] = __('Roster','joomsport-sports-league-results-management');
                $tabs[$intA]['body'] = 'player-list-photo.php';
                $tabs[$intA]['text'] = '';
                $tabs[$intA]['class'] = '';
                $tabs[$intA]['ico'] = 'js-rostr';
            }
        }
        //players
        $show_playertab = JoomsportSettings::get('show_playertab');
        $show_playerstattab = JoomsportSettings::get('show_playerstattab','1');
        if($show_playerstattab){
            if (count($this->lists['players']) || ($show_playertab == '1' && !count($this->lists['players']))) {
                ++$intA;
                $tabs[$intA]['id'] = 'stab_players_stats';
                $tabs[$intA]['title'] = __('Players Stats','joomsport-sports-league-results-management');
                $tabs[$intA]['body'] = 'player-list.php';
                $tabs[$intA]['text'] = '';
                $tabs[$intA]['class'] = '';
                $tabs[$intA]['ico'] = 'js-pllist';
            }
        }
        
        //box score
        $this->getBoxScoreList();
        if (isset($this->lists['boxscore_home']) && ($this->lists['boxscore_home'] != '')) {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_boxscore';
            $tabs[$intA]['title'] = __('Box Score','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = '';
            $tabs[$intA]['text'] = $this->lists['boxscore_home'];
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-boxscr';
        }


        //photos
        if (count($this->lists['photos']) > 1) {
            ++$intA;
            $tabs[$intA]['id'] = 'stab_photos';
            $tabs[$intA]['title'] = __('Photos','joomsport-sports-league-results-management');
            $tabs[$intA]['body'] = 'gallery.php';
            $tabs[$intA]['text'] = '';
            $tabs[$intA]['class'] = '';
            $tabs[$intA]['ico'] = 'js-photo';
        }
        
        if ( has_filter( 'joomsport_custom_tab_fe' ) ){
            $tabs = apply_filters("joomsport_custom_tab_fe", $this->id, $tabs);
        }

        return $tabs;
    }

    public function getMatches()
    {
        $options = array('team_id' => $this->id, 'season_id' => $this->season_id);

        $link = classJsportLink::team('', $this->id, $this->season_id, true);
        $pagination = new classJsportPagination($link);
        $options['limit'] = $pagination->getLimit();
        $options['offset'] = $pagination->getOffset();
        $pagination->setAdditVar('jscurtab', 'stab_matches');
        $obj = new classJsportMatches($options);
        $rows = $obj->getMatchList();
        $pagination->setPages($rows['count']);
        $this->lists['match_pagination'] = $pagination;
        $matches = array();

        if ($rows['list']) {
            foreach ($rows['list'] as $row) {
                $match = new classJsportMatch($row->ID, false);
                $matches[] = $match->getRowSimple();
            }
        }
        $this->lists['matches'] = $matches;
    }

    public function getPlayers($options = array())
    {
        global $jsDatabase;
        
        $attr = array('team_id' => $this->id, 'season_id' => $this->season_id);
        
        $plorder = JoomsportSettings::get('pllist_order');

        if($plorder){
            $plorder = explode('_', $plorder);
            // Settings => Layout => Order players by
            if(isset($plorder[1])){
	            // Event Stats
                if($plorder[1] == '2'){
                    $attr['ordering'] = 'eventid_'.intval($plorder[0]).' desc';
	                // Extra Field
                }elseif($plorder[1] == '1'){
                    $ordering_ef = intval($plorder[0]);
                    if(isset($ordering_ef) && $ordering_ef){
                        $sql = "SELECT field_type"
                            . " FROM {$jsDatabase->db->joomsport_ef} WHERE id={$ordering_ef}";
                        $ef_type = $jsDatabase->selectValue($sql);
                        // player type only
	                    if ( $ef_type == 0) {
                        $this->ef_sort_id = $ordering_ef;
                    }
                    }
                    if($ef_type == 3){
                        $sql = "SELECT id as name, eordering as value"
                            . " FROM {$jsDatabase->db->joomsport_ef_select} WHERE fid={$ordering_ef}";
                        $ef_ordering = $jsDatabase->select($sql);
                        $ef_ordering_assocc = array();
                        for($intZ=0;$intZ<count($ef_ordering);$intZ++){
                            $ef_ordering_assocc[$ef_ordering[$intZ]->name] = $ef_ordering[$intZ]->value;
                        }
                        
                    }
                    
                }
            }
        }else{
            $attr['ordering'] = 'p.post_title';
        }

        $players = classJsportgetplayers::getPlayersFromTeam($attr);

        $players_object_gr = array();
        $players = $players['list'];

        
        $groupBySelect = JoomsportSettings::get('set_teampgplayertab_groupby',0);
        if(isset($options['groupBySelect'])){
            $groupBySelect = $options['groupBySelect'];
            unset($ef_type);
        }
        $playerPhotoTab = JoomsportSettings::get('show_rostertab',1);
        if(isset($options['playerPhotoTab'])){
            $playerPhotoTab = $options['playerPhotoTab'];
        }
        if(JoomsportSettings::get('enbl_player_system_num',0) == '1'){
            $efPlayerNumber = -1;
            $this->lists['playerfieldnumber'] = 'def';
        }else{
            $playerNumber = $this->lists['playerfieldnumber'] = JoomsportSettings::get('set_playerfieldnumber',0);
            if($playerNumber){
                $query = 'SELECT ef.*'
                    .' FROM '.$jsDatabase->db->joomsport_ef.' as ef '
                    ." WHERE ef.id=".intval($playerNumber);

                $efPlayerNumber = $jsDatabase->selectObject($query);
            }
        }
        
        $playerCard = $this->lists['playercardef'] = JoomsportSettings::get('set_playercardef',0);
        if($playerCard){
            $query = 'SELECT ef.*'
                .' FROM '.$jsDatabase->db->joomsport_ef.' as ef '
                ." WHERE ef.id=".intval($playerCard);

            $efplayerCard = $jsDatabase->selectObject($query);
        }
	    if($this->ef_sort_id){
		    $query = 'SELECT ef.*'
		             .' FROM '.$jsDatabase->db->joomsport_ef.' as ef '
		             ." WHERE ef.id=".intval($this->ef_sort_id);

		    $efplayerEfSorter = $jsDatabase->selectObject($query);
	    }
        $statplyers = array();
	    $playerGroups = array('0');

        $jsblock_career = JoomsportSettings::get('jsblock_career');
        $jsblock_career_fields_selected = json_decode(JoomsportSettings::get('jsblock_career_options'),true);

        $this->lists['career'] = $this->lists['career_head'] = array();


        $available_options = array(
            'op_mplayed' => array(
                'field' => 'played',
                'text' => __('Matches played','joomsport-sports-league-results-management'),
                'img' => '<img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'matches_played.png" width="24" class="sub-player-ico" title="'.__('Matches played','joomsport-sports-league-results-management').'" alt="'.__('Matches played','joomsport-sports-league-results-management').'" />'
            ),
            'op_mlineup' => array(
                'field' => 'career_lineup',
                'text' => __('Starting lineup','joomsport-sports-league-results-management'),
                'img' => '<img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'squad.png" width="24" class="sub-player-ico" title="'.__('Matches Line Up','joomsport-sports-league-results-management').'" alt="'.__('Matches Line Up','joomsport-sports-league-results-management').'" />'
            ),
            'op_minutes' => array(
                'field' => 'career_minutes',
                'text' => __('Played minutes','joomsport-sports-league-results-management'),
                'img' => '<img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'stopwatch.png" width="24" class="sub-player-ico" title="'.__('Played minutes','joomsport-sports-league-results-management').'" alt="'.__('Played minutes','joomsport-sports-league-results-management').'" />'
            ),
            'op_subsin' => array(
                'field' => 'career_subsin',
                'text' => __('Subs in','joomsport-sports-league-results-management'),
                'img' => '<img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'in-new.png" width="24" class="sub-player-ico" title="'.__('Subs in','joomsport-sports-league-results-management').'" alt="'.__('Subs in','joomsport-sports-league-results-management').'" />'
            ),
            'op_subsout' => array(
                'field' => 'career_subsout',
                'text' => __('Subs out','joomsport-sports-league-results-management'),
                'img' => '<img src="'.JOOMSPORT_LIVE_URL_IMAGES_DEF.'out-new.png" width="24" class="sub-player-ico" title="'.__('Subs out','joomsport-sports-league-results-management').'" alt="'.__('Subs out','joomsport-sports-league-results-management').'" />'
            )
        );
        $resultoptions = array();
        if($jsblock_career_fields_selected && count($jsblock_career_fields_selected) && $jsblock_career){

            foreach($jsblock_career_fields_selected as $block){
                if(isset($available_options[$block])){
                    $resultoptions[] = $available_options[$block];
                    if (isset($available_options[$block]['img']) && $available_options[$block]['img']) {
                        $this->lists['career_head'][] = $available_options[$block]['img'];
                    }else
                        if (isset($available_options[$block]['text'])) {
                            $this->lists['career_head'][] = $available_options[$block]['text'];
                        }
                }
            }

        }

        if ($players) {
            
            if($groupBySelect && $playerPhotoTab){
                $query = "SELECT sel_value FROM {$jsDatabase->db->joomsport_ef_select} WHERE fid={$groupBySelect} ORDER BY eordering";
                $efgroup = $jsDatabase->selectColumn($query);
                if(count($efgroup)){
                    $playerGroups = array_merge($playerGroups,$efgroup);
                }
            }
            foreach ($playerGroups as $value) {
                $players_object_gr[$value] = array();
            }
            
            $count_players = count($players);
            $this->lists['ef_table'] = $ef = classJsportExtrafields::getExtraFieldListTable(0, false);
            if($groupBySelect){
                $query = 'SELECT ef.*'
                        .' FROM '.$jsDatabase->db->joomsport_ef.' as ef '
                        ." WHERE ef.id = ".intval($groupBySelect)
                        .' ORDER BY ef.ordering';

                $efT = $jsDatabase->selectObject($query);
                if(isset($efT->id)){
                    array_push($ef, $efT);
                }
            }
            for ($intC = 0; $intC < $count_players; ++$intC) {
                $row = $players[$intC];
                if($row->player_id){
                    $uGroup = '0';
                    $obj = new classJsportPlayer($row->player_id, $this->season_id,false);
                    $obj->lists['tblevents'] = $row;
                    $players_object = array();
                    $players_object = $obj->getRowSimple();

                    if (JoomsportSettings::get('played_matches')) {
                        $players_object->played_matches = classJsportgetplayers::getPlayersPlayedMatches($row->player_id, $this->id, $this->season_id);
                    }
                    for ($intB = 0; $intB < count($ef); ++$intB) {
                        
                        $players_object->{'ef_'.$ef[$intB]->id} = classJsportExtrafields::getExtraFieldValue($ef[$intB], $row->player_id, 0, $this->season_id);
                    
                        if(isset($ef_type) && $ef_type == 3){
                            $orderValue = -1;
                            $meta = get_post_meta($row->player_id,'_joomsport_player_ef',true);
                            $meta_s = get_post_meta($row->player_id,'_joomsport_player_ef_'.$this->season_id,true);
                            if(isset($meta[$ef[$intB]->id])){
                                if(isset($ef_ordering_assocc[$meta[$ef[$intB]->id]])){
                                    $orderValue = $ef_ordering_assocc[$meta[$ef[$intB]->id]];
                                }
                            }elseif(isset($meta_s[$ef[$intB]->id])){
                                if(isset($ef_ordering_assocc[$meta_s[$ef[$intB]->id]])){
                                    $orderValue = $ef_ordering_assocc[$meta_s[$ef[$intB]->id]];
                                }
                            }
                            $players_object->{'ef0_'.$ef[$intB]->id} = $orderValue;
                    
                        }
                        if($groupBySelect && $playerPhotoTab){
                            if($ef[$intB]->id == $groupBySelect){
                                if($players_object->{'ef_'.$ef[$intB]->id}){
                                    $uGroup = $players_object->{'ef_'.$ef[$intB]->id};
                                }
                            }
                        }
                    }
                    foreach($resultoptions as $ro){
                        if (isset($row->{$ro['field']})) {
                            if (is_float(floatval($row->{$ro['field']}))) {
                                $players_object->career[] = round($row->{$ro['field']}, 3);
                            } else {
                                $players_object->career[] = floatval($row->{$ro['field']});
                            }
                        }
                    }
                    if(isset($efPlayerNumber) && isset($efPlayerNumber->id)){
                        $players_object->{'ef_'.$efPlayerNumber->id} = classJsportExtrafields::getExtraFieldValue($efPlayerNumber, $row->player_id, 0, $this->season_id);
                    
                    }elseif(isset($efPlayerNumber) && $efPlayerNumber == -1){
                        $jersey = get_post_meta($row->player_id,'_joomsport_player_jersey_'.$this->season_id,true);
                        if(isset($jersey[$this->id])){
                            $players_object->{'ef_def'} = $jersey[$this->id];
                        }
                    }
                    if(isset($efplayerCard) && isset($efplayerCard->id)){
                        $players_object->{'ef_'.$efplayerCard->id} = classJsportExtrafields::getExtraFieldValue($efplayerCard, $row->player_id, 0, $this->season_id);
                    }
                    if(isset($efplayerEfSorter) && isset($efplayerEfSorter->id)){
	                    $players_object->{'ef_'.$efplayerEfSorter->id} = classJsportExtrafields::getExtraFieldValue($efplayerEfSorter, $row->player_id, 0, $this->season_id);
                    }

                    $statplyers[] = $players_object;
                    $players_object_gr[$uGroup][] = $players_object;
                }
            }
            
	        if(isset($this->ef_sort_id)){
		        if(count($players_object_gr)){
			        foreach ($players_object_gr as $uGrKey => $uGrVal) {
				        usort($players_object_gr[$uGrKey], array($this,'sortPlayers'));
			        }
		        }
	        }
            if(isset($ef_type) && isset($this->ef_sort_id)){
                
                $this->ef_type = $ef_type;
                if(count($players_object_gr)){
                    foreach ($players_object_gr as $uGrKey => $uGrVal) {
                        usort($players_object_gr[$uGrKey], array($this,'sortPlayers'));
                    }
                }
                if(count($statplyers)){
                    usort($statplyers, array($this,'sortPlayers'));
                }
                
            }
        }
        
        //staff list
        $this->lists['team_staff'] = array();
        $sql = "SELECT *"
                . " FROM {$jsDatabase->db->joomsport_ef}"
                . " WHERE type='1' AND published = '1' AND field_type='5'"
                .(classJsportUser::getUserId() ? '' : " AND faccess='0'")
                . " AND faccess != 2"
                ." ORDER BY ordering";
        $coaches = $jsDatabase->select($sql);
        
        
        for($intA=0;$intA<count($coaches);$intA++){
            $options = $coaches[$intA]->options;
            if($options){
                $person_id = 0;
                $options_decode = json_decode($options, true);
                if(isset($options_decode["in_roster"])){
                    if($coaches[$intA]->season_related){
                        $efArr = get_post_meta($this->id,'_joomsport_team_ef_'.$this->season_id,true);
                    }else{
                        $efArr = get_post_meta($this->id,'_joomsport_team_ef',true);
                    }
                    if(isset($efArr[$coaches[$intA]->id])){
                        $person_id = $efArr[$coaches[$intA]->id];
                    }
                }
                if($person_id){
                    $obj = new classJsportPerson($person_id, $this->season_id);
                    $this->lists['team_staff'][] = array("name"=>$coaches[$intA]->name, "obj"=>$obj);
                }
            }
        }
        
        
        if (JoomsportSettings::get('played_matches')) {
            $this->lists['played_matches_col'] = __('Match played','joomsport-sports-league-results-management');
        }
        
        $this->lists['players'] = $players_object_gr;
        $this->lists['players_Stat'] = $statplyers;
        
        

        //events
        
        if($this->season_id){
            $this->lists['events_col'] = classJsportgetplayers::getPlayersEvents($this->season_id);
        }else{
            $seasons = JoomSportHelperObjects::getParticipiantSeasons($this->id);
            $seasons_arr = array();
            if(count($seasons)){
                foreach($seasons as $seas){
                    
                    for($intA=0;$intA<count($seas);$intA++){
                        
                        $seasons_arr[] = $seas[$intA]->id;
                    }
                }
            }
            if(!count($seasons_arr)){
                $seasons_arr = 0;
            }
            $this->lists['events_col'] = classJsportgetplayers::getPlayersEvents($seasons_arr);

        }
    }
    public function sortPlayers($a,$b){
        if($this->ef_type == '3'){
            if ($a->{'ef0_'.$this->ef_sort_id} == $b->{'ef0_'.$this->ef_sort_id}) {
                return 0;
            }
            return ($a->{'ef0_'.$this->ef_sort_id} < $b->{'ef0_'.$this->ef_sort_id}) ? -1 : 1;
        }else{
            if ($a->{'ef_'.$this->ef_sort_id} == $b->{'ef_'.$this->ef_sort_id}) {
                return 0;
            }
            return ($a->{'ef_'.$this->ef_sort_id} < $b->{'ef_'.$this->ef_sort_id}) ? -1 : 1;
        }
        
        
    }
    public function getDescription()
    {
        $t_descr = get_post_meta($this->id,'_joomsport_team_about',true);
        return classJsportText::getFormatedText($t_descr);
    }

    public function _displayOverviewTab()
    {

        return JoomsportSettings::get('tlb_position') || JoomsportSettings::get('tlb_form') || JoomsportSettings::get('tlb_latest') || JoomsportSettings::get('tlb_next');
    }
    public function getLatestMatches()
    {
        $options = array('team_id' => $this->id, 'season_id' => $this->season_id);
        $options['ordering_dest'] = 'desc';
        $options['limit'] = $this->matches_latest;
        $options['played'] = '1';

        $obj = new classJsportMatches($options);
        $rows = $obj->getMatchList();

        $matches = array();

        if ($rows['list']) {
            foreach ($rows['list'] as $row) {
                $match = new classJsportMatch($row->ID, false);
                $matches[] = $match->getRowSimple();
            }
        }

        $this->lists['matches_latest'] = $matches;
    }
    public function getNextMatches()
    {
        $options = array('team_id' => $this->id, 'season_id' => $this->season_id);

        $options['limit'] = $this->matches_next;
        $options['played'] = '0';
        $obj = new classJsportMatches($options);
        $rows = $obj->getMatchList();

        $matches = array();

        if ($rows['list']) {
            foreach ($rows['list'] as $row) {
                $match = new classJsportMatch($row->ID, false);
                $matches[] = $match->getRowSimple();
            }
        }
        $this->lists['matches_next'] = $matches;
    }
    public function setHeaderOptions()
    {

        if ($this->season_id > 0) {
            $this->lists['options']['calendar'] = $this->season_id;
            $this->lists['options']['standings'] = $this->season_id;
            if ($this->lists['enbl_join']) {
                $this->lists['options']['jointeam']['seasonid'] = $this->season_id;
                $this->lists['options']['jointeam']['teamid'] = $this->id;
            }
        }
        $this->lists['options']['tourn'] = $this->lists['tourn'];
        $img = $this->getEmblem(false);
        //social
        if (JoomsportSettings::get('jsbp_team') == '1') {
            $this->lists['options']['social'] = true;
            //classJsportAddtag::addCustom('og:title', $this->getName(false));

            if ($img) {
                //classJsportAddtag::addCustom('og:image', JS_LIVE_URL_IMAGES.$this->object->t_emblem);
            }
            //classJsportAddtag::addCustom('og:description', $this->getDescription());
        }
        $imgtitle = '';
        if ($img) {
            $imgtitle = $img.'&nbsp;';
        }
        $this->lists['options']['title'] = $imgtitle.$this->getName(false);
    }
    public function getYourTeam()
    {

        return (in_array($this->id, JoomsportSettings::get('yteams',array())) && JoomsportSettings::get('highlight_team')) ? JoomsportSettings::get('yteam_color') : '';
    }
    public function getClub($linkable = true){
        $term_list = wp_get_post_terms($this->id, 'joomsport_club', array("fields" => "all"));

        if ($term_list && count($term_list)) {
            $club = new classJsportClub($term_list[0]->term_id);

            $this->lists['ef'][__('Club','joomsport-sports-league-results-management')] =  $club->getName($linkable);

        }
        return false;
    }
    public function getVenue($linkable = true){
        $tVenue = get_post_meta($this->id,'_joomsport_team_venue',true);
        if ($tVenue) {
            $venue = new classJsportVenue($tVenue);
            if(isset($venue->object->post_status) && $venue->object->post_status != 'trash'){
            $this->lists['ef'][__('Venue','joomsport-sports-league-results-management')] = $venue->getName($linkable);
            }
        }
        return false;
    }
    public function getBoxScoreList(){
        $this->lists['boxscore_home'] = $this->model->getBoxScore();
        
    }
}
