<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class modelJsportAchvSeason
{
    public $season_id = null;
    public $lists = null;
    public $object = null;

    public function __construct($id)
    {
        $this->season_id = $id;

        if (!$this->season_id) {
            die('ERROR! SEASON ID not DEFINED');
        }
        global $jsDatabase;
        $this->object = get_post(intval($this->season_id));

    }
    public function getRow()
    {
        return $this->object;
    }
    public function getName()
    {
        $name = '';
        $term_list = wp_get_post_terms($this->season_id, 'jsprt_achv_league', array("fields" => "all"));
        if(count($term_list)){
            $name .= esc_attr($term_list[0]->name).' ';
        }
        $name .= get_the_title($this->season_id);
        return $name;
    }
    public function loadLists()
    {
        $this->lists['ef'] = classJsportAchvExtrafields::getExtraFieldList($this->season_id, '3', $this->season_id);
        $this->calcTable();
        $this->getHeaderSelect();
        
        $this->lists['ranking_criteria'] = -1;
        $metadata = JoomSportAchievmentsHelperObject::getSeasonRankingCriteria($this->season_id);
        if(isset($metadata['ranking_criteria'])){
            $this->lists['ranking_criteria'] = $metadata['ranking_criteria'];
        }
        
        return $this->lists;
    }
    public function calcTable(){
        global $wpdb;
        $metadata = get_post_meta($this->season_id,'_jsprt_achv_season_points',true);
        $stages = $this->getAllStages();
        $devide = $this->getDevides();
        $this->lists['result_table'] = array();
        $this->lists['rank_field_head'] = __( 'Points', 'joomsport-achievements' );
        $stage_query = '';
        $vals = $wpdb->get_results('SELECT id,name FROM '.$wpdb->jsprtachv_stages.' WHERE published="1" ORDER BY ordering') ;
        for($intA=0;$intA<count($vals);$intA++){
            $selected = (int)filter_input(INPUT_POST, 'stagecat_'.$vals[$intA]->id);
            if($selected){
                $stage_query .= " AND stagecat_".$vals[$intA]->id." = {$selected}";
            }
        }    
        
        
        for($intA=0;$intA<count($devide);$intA++){
            $devideid = $devide[$intA]->id;
            $stageVals = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_stages_val.' WHERE fid='.absint($devideid).' ORDER BY eordering', 'OBJECT') ;
            
            if(count($stageVals)){
                foreach ($stageVals as $stage) {
                    if(isset($metadata['ranking_criteria']) && $metadata['ranking_criteria']){
                        $method = isset($metadata['ranking_method'])?intval($metadata['ranking_method']):0;
                        $calcType = 'SUM';
                        $order = 'DESC';
                        $calcField = 'field_'.intval($metadata['ranking_criteria']);
                        $this->lists['rank_field_head'] = $wpdb->get_var("SELECT name FROM {$wpdb->jsprtachv_results_fields} WHERE id=".intval($metadata['ranking_criteria']));
                        switch ($method) {
                            case 0:
                                $calcType = 'MIN';
                                $order = 'ASC';
                                break;
                            case 1:
                                $calcType = 'MAX';
                            case 3:
                                $order = 'ASC';
                                break;
                            default:
                                break;
                        }
                        $this->lists['result_table'][$stage->name] = $wpdb->get_results("SELECT partic_id,{$calcType}(".$calcField.") as pts FROM {$wpdb->jsprtachv_stage_result} WHERE stagecat_".$devideid." = ".$stage->id." AND stage_id IN (".implode(',', $stages).") ".$stage_query." GROUP BY partic_id  ORDER BY pts {$order}");
                    }else{
                        $this->lists['result_table'][$stage->name] = $wpdb->get_results("SELECT partic_id,SUM(points) as pts FROM {$wpdb->jsprtachv_stage_result} WHERE stagecat_".$devideid." = ".$stage->id." AND stage_id IN (".implode(',', $stages).") ".$stage_query." GROUP BY partic_id  ORDER BY pts DESC");
                    }
                }
            }
            
        }
        if(!count($devide)){
            if(count($stages)){
                if(isset($metadata['ranking_criteria']) && $metadata['ranking_criteria']){
                        $method = isset($metadata['ranking_method'])?intval($metadata['ranking_method']):0;
                        $calcType = 'SUM';
                        $order = 'DESC';
                        $calcField = 'field_'.intval($metadata['ranking_criteria']);
                        $this->lists['rank_field_head'] = $wpdb->get_var("SELECT name FROM {$wpdb->jsprtachv_results_fields} WHERE id=".intval($metadata['ranking_criteria']));
                        
                        switch ($method) {
                            case 0:
                                $calcType = 'MIN';
                                $order = 'ASC';
                                break;
                            case 1:
                                $calcType = 'MAX';

                                break;
                            case 3:
                                $order = 'ASC';
                                break;
                            default:
                                break;
                        }
                        $this->lists['result_table'][''] = $wpdb->get_results("SELECT partic_id,{$calcType}(".$calcField.") as pts FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id IN (".implode(',', $stages).") GROUP BY partic_id ORDER BY pts {$order}");
                    }else{
                        $this->lists['result_table'][''] = $wpdb->get_results("SELECT partic_id,SUM(points) as pts FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id IN (".implode(',', $stages).") GROUP BY partic_id ORDER BY pts DESC");
                    }
                
            }  else {
                $this->lists['result_table'] = array();
            }
        }

    }
    public function getAllStages(){
        $stages = array();
        $seasons = array($this->season_id);
        $childs = $this->getSeasonChildrens();

        if(count($childs)){
            foreach($childs as $chld){
                $seasons[] = $chld->ID;
            }
        }

        $args = array(
            'posts_per_page' => -1,
            'offset'           => 0,
            'meta_key'          => '_jsprt_achv_stage_date',
            'orderby'          => 'meta_value',
            'order'            => 'ASC',
            'post_type'        => 'jsprt_achv_stage',
            'post_status'      => 'publish',
            'meta_query' => array(
                    array(
                            'key' => '_jsprt_achv_stage_season',
                            'value' => $seasons,
                            'compare' => 'IN',
                    )
            )

        );
        $posts_array = get_posts( $args );
        for($intA=0;$intA<count($posts_array);$intA++){
            $stages[] = $posts_array[$intA]->ID;
        }
        return $stages;
    }
    public function getDevides(){
        global $wpdb;
        return $wpdb->get_results('SELECT id,name FROM '.$wpdb->jsprtachv_stages.' WHERE devide="1" AND published="1"') ;
    }
    public function getSeasonChildrens(){
        $args = array(
                'post_parent' => $this->season_id,
                'post_type'   => 'jsprt_achv_season', 
                'numberposts' => -1,
                'post_status' => 'published',
                'orderby' => 'menu_order title',
                'order'   => 'ASC',
        );
        $children = get_children( $args );
        return $children;
    }
    public function getHeaderSelect()
    {
        global $wpdb;
        $this->lists['options']['calendar'] = $this->season_id;
        $javascript = " onchange='this.form.submit();'";
        $jqre = '';
        $vals = $wpdb->get_results('SELECT id,name FROM '.$wpdb->jsprtachv_stages.' WHERE published="1" ORDER BY ordering') ;
        for($intA=0;$intA<count($vals);$intA++){
            $stages = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_stages_val.' WHERE fid='.absint($vals[$intA]->id).' ORDER BY eordering', 'OBJECT') ;
            $selected = (int)filter_input(INPUT_POST, 'stagecat_'.$vals[$intA]->id);
            $bulk = __('Select', 'joomsport-achievements').' '.$vals[$intA]->name;
            $jqre .=  JoomSportAchievmentsHelperSelectBox::Optgroup('stagecat_'.$vals[$intA]->id, $stages,$selected, $javascript.' class="btn btn-default selectpicker"',$bulk);
            $jqre .= '&nbsp;';
        
        }
        

        $this->lists['tourn'] = $jqre;
    }
}
