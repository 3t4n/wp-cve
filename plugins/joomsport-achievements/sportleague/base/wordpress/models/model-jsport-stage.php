<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class modelJsportAchvStage
{
    public $stage_id = null;
    public $lists = null;
    private $row = null;

    public function __construct($id)
    {

        $this->stage_id = $id;

        if (!$this->stage_id) {
            die('ERROR! Stage ID not DEFINED');
        }
        $this->loadObject();
    }
    private function loadObject()
    {

        $this->row = get_post($this->stage_id);
        
        global $wpdb;
        $this->lists['ef'] = classJsportAchvExtrafields::getExtraFieldList($this->stage_id, '2',0);
        
        $stage_date = get_post_meta($this->stage_id,'_jsprt_achv_stage_date',true);
        $stage_time = get_post_meta($this->stage_id,'_jsprt_achv_stage_time',true);
        if($stage_date){
            $fldName = __( 'Date', 'joomsport-achievements' );
            $fldArr = array($fldName => $stage_date.' '.$stage_time);
            if($this->lists['ef']){
                $this->lists['ef'] = array_merge($fldArr, $this->lists['ef']);
            }else{
                $this->lists['ef'] = $fldArr;
            }
            
        }
        
        $this->lists['fields_sorting'] = get_post_meta($this->stage_id,'_jsprt_achv_stage_result_sorting',true);
        $this->lists['result_table'] = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id={$this->stage_id} ORDER BY rank,id");
        $efields = JoomSportAchievmentsHelperEF::getEFList('0', 0);

        $sql = "SELECT * FROM {$wpdb->jsprtachv_results_fields} WHERE published='1' ORDER BY ordering";

        $this->lists['resultFields'] = $wpdb->get_results( $sql );
        
        
    }
    public function getRow()
    {
        //$this->loadLists();
        return $this->row;
    }
    public function loadLists()
    {
        $season_id = (int) get_post_meta($this->stage_id,'_jsprt_achv_stage_season',true);
        if($season_id){
            $this->lists['options']['calendar'] = $season_id;
        }
        $this->lists['ranking_criteria'] = -1;
        $metadata = JoomSportAchievmentsHelperObject::getSeasonRankingCriteria($season_id);
        if(isset($metadata['ranking_criteria'])){
            $this->lists['ranking_criteria'] = $metadata['ranking_criteria'];
        }
        return $this->lists;
    }

}
