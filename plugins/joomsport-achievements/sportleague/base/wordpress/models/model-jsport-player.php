<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class modelJsportAchvPlayer
{
    public $season_id = null;
    public $player_id = null;
    public $lists = null;
    private $row = null;

    public function __construct($id, $season_id = 0)
    {
        $this->season_id = $season_id;
        $this->player_id = $id;

        if (!$this->player_id) {
            die('ERROR! Player ID not DEFINED');
        }
        $this->loadObject();
    }
    private function loadObject()
    {
        global $jsDatabase;

        $this->row = get_post($this->player_id);
    }
    public function getRow()
    {
        //$this->loadLists();
        return $this->row;
    }
    public function loadLists()
    {
        global $wpdb;
        $metadata = get_post_meta($this->player_id,'_joomsport_player_personal',true);
        
        $this->lists['ef'] = classJsportAchvExtrafields::getExtraFieldList($this->player_id, '0', $this->season_id);
        if(isset($metadata['last_name']) && ($metadata['last_name'])){
            $tmparr = array(__('Last Name','joomsport-achievements') => $metadata['last_name']);
            $this->lists['ef'] = array_merge($tmparr, $this->lists['ef']);
        }
        if(isset($metadata['first_name']) && ($metadata['first_name'])){
            $tmparr = array(__('First Name','joomsport-achievements') => $metadata['first_name']);
            $this->lists['ef'] = array_merge($tmparr, $this->lists['ef']);
            //$this->lists['ef'][__('First Name','joomsport-achievements')] = $metadata['first_name'];
        }
        
        
        $countryID = (int) get_post_meta($this->player_id,'_jsprt_achv_player_country',true);
        if($countryID){
            $country = $wpdb->get_row('SELECT * FROM '.$wpdb->jsprtachv_country.'  WHERE id='.$countryID, 'OBJECT');
            
            if(!empty($country)){
                $file_country = JOOMSPORT_ACHIEVEMENTS_PATH.'/assets/images/flags/' . strtolower($country->ccode) . '.png';
                $url = plugins_url( '../../../../assets/images/flags/' . strtolower($country->ccode) . '.png', __FILE__ );
                if (file_exists($file_country)) {
                    $this->lists['ef'][__('Country','joomsport-achievements')] =  '<img src="' . $url . '" title="' . $country->country . '" alt="' . $country->country . '"/> ';
                }
            }
            
        }
        
        $this->getPhotos();
        $this->getDefaultImage();
        $this->getHeaderSelect();
        $this->getStages();
        return $this->lists;
    }

    public function getDefaultImage()
    {
        global $jsDatabase;
        $this->lists['def_img'] = null;
        if (isset($this->lists['photos'][0])) {
            $this->lists['def_img'] = $this->lists['photos'][0];
        }
    }
    public function getPhotos()
    {
        global $jsConfig;
        $photos = get_post_meta($this->player_id,'vdw_gallery_id',true);

        $this->lists['photos'] = array();
        if ($photos && count($photos)) {
            foreach ($photos as $photo) {
                //$image = get_post($photo);
                $image_arr = wp_get_attachment_image_src($photo, 'joomsport-thmb-medium');
                if (($image_arr[0])) {
                    $this->lists['photos'][] = array("id" => $photo, "src" => $image_arr[0]);
                }
            }
        }
        
    }

    public function getHeaderSelect()
    {
        global $jsDatabase;
        
    }
    
    public function getStages(){
        global $wpdb;
        
        $stagesArr = array();
        $args = array(
                'post_parent' => 'any',
                'post_type'   => 'jsprt_achv_season', 
                'numberposts' => -1,
                'post_status' => 'published',
                'orderby' => 'menu_order title',
                'order'   => 'ASC',
        );
        $seasons = get_children( $args ); 
        $this->lists['ranking_criteria'] = false;
        if(count($seasons)){
            foreach($seasons as $season){
                
                $metadata = JoomSportAchievmentsHelperObject::getSeasonRankingCriteria($season->ID);
                
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
                                    'value' => $season->ID
                            )
                    )

                );
                $stages = get_children( $args );  
                
                if(count($stages)){
                    
                    foreach($stages as $stage){
                        $stages_res = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE partic_id = {$this->player_id} AND stage_id={$stage->ID} ORDER BY id");
                        if($stages_res){
                            if(isset($metadata['ranking_criteria']) && $metadata['ranking_criteria'] == 0){
                               
                                $this->lists['ranking_criteria'] = true;
                            }
                            $stagesArr[$season->ID][] = $stages_res;
                        }
                    }
                }
            }
        }
        $this->lists['stages_by_season'] = $stagesArr;
        $this->lists['stages'] = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE partic_id = {$this->player_id} ORDER BY id");
        $sql = "SELECT * FROM {$wpdb->jsprtachv_results_fields} WHERE published='1' ORDER BY ordering";

        $this->lists['resultFields'] = $wpdb->get_results( $sql );
        
    }
    
}
