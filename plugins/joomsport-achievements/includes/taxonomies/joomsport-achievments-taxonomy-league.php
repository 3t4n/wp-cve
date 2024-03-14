<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

//require_once JOOMSPORT_PATH_INCLUDES . 'classes'. DIRECTORY_SEPARATOR . 'joomsport-class-matchday.php';

class JoomSportAchievmentsTaxonomyLeague {
    public function __construct() {

    }
    public static function init(){
        self::register_taxonomy();
    }
    public static function register_taxonomy(){
        $labels = array(
                'name' => __( 'Leagues', 'joomsport-achievements' ),
                'singular_name' => __( 'League', 'joomsport-achievements' ),
                'all_items' => __( 'All', 'joomsport-achievements' ),
                'edit_item' => __( 'Edit League', 'joomsport-achievements' ),
                'view_item' => __( 'View', 'joomsport-achievements' ),
                'update_item' => __( 'Update', 'joomsport-achievements' ),
                'add_new_item' => __( 'Add New', 'joomsport-achievements' ),
                'new_item_name' => __( 'Name', 'joomsport-achievements' ),
                'parent_item' => __( 'Parent', 'joomsport-achievements' ),
                'parent_item_colon' => __( 'Parent', 'joomsport-achievements' ),
                'search_items' =>  __( 'Search', 'joomsport-achievements' ),
                'not_found' => __( 'No results found.', 'joomsport-achievements' ),
        );
        $args = array(
                'label' => __( 'Leagues', 'joomsport-achievements' ),
                'labels' => $labels,
                'public'            =>  true,
                'publicly_queryable'=>  true,
                'show_ui'           =>  true, 
                'query_var'         =>  true,
                'show_in_menu'        => 'joomsport_achievments',
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'hierarchical' => false,
                'exclude_from_search' => true,
                "singular_label" => "jsprt_achv_league",
                'rewrite' => array('slug' => 'jsprt_achv_league', 'with_front'    => false),
                   
        );
        $object_types = apply_filters( 'joomsport_achv_league_object_types', array( 'jsprt_achv_season' ) );
        register_taxonomy( 'jsprt_achv_league', $object_types, $args );
        foreach ( $object_types as $object_type ):
                register_taxonomy_for_object_type( 'jsprt_achv_league', $object_type );
        endforeach;
        
        $tournament_tax = new JoomSportAchievmentTaxonomyDropM('jsprt_achv_league', __( 'League', 'joomsport-achievements' ), array('jsprt_achv_season'));
        add_action('add_meta_boxes', array( $tournament_tax, 'joomsport_custom_meta_box'));
        add_action( 'save_post', array( $tournament_tax, 'taxonomy_save_postdata') );
        

        
    }


}    

// class ovveride taxonomy to dropdown

class JoomSportAchievmentTaxonomyDropM{
    public $pages = array();
    public $name = null;
    public $name_slug = null;
    
    public function __construct($name, $name_slug, $pages){
        $this->name = $name;
        $this->name_slug = $name_slug;
        $this->pages = $pages;
    }
    
    public function joomsport_custom_meta_box() {

        remove_meta_box( 'tagsdiv-'.$this->name, 'jsprt_achv_season', 'side' );

        add_meta_box( 'tagsdiv-'.$this->name, $this->name_slug, array( $this, 'drop_meta_box'), 'jsprt_achv_season', 'side' );

    }
    
    public function drop_meta_box($post) {
        global $joomsportSettings;
        $taxonomy = get_taxonomy($this->name_slug);

        ?>
        <div class="tagsdiv" id="<?php echo $this->name_slug; ?>">
            <div class="jaxtag">
            <?php 
            wp_nonce_field( plugin_basename( __FILE__ ), $this->name_slug.'_noncename' );
            $type_IDs = wp_get_object_terms( $post->ID, $this->name, array('fields' => 'ids') );
            
            $current_tournament = !isset($type_IDs[0]) ? 0 : $type_IDs[0];
            
            if(get_bloginfo('version') < '4.5.0'){
                $tx = get_terms('jsprt_achv_league',array(
                    "hide_empty" => false
                ));
            }else{
                $tx = get_terms(array(
                    "taxonomy" => "jsprt_achv_league",
                    "hide_empty" => false,
                ));
            }

            echo '<select name="jsprt_achv_league" id="jsprt_achv_league_id" class="postform" aria-required="true">';
                echo '<option value="-1">'.__('Select League','joomsport-achievements').'</option>';
                for($intA=0;$intA<count($tx);$intA++){
                    $term_meta = get_option( "taxonomy_".$tx[$intA]->term_id."_metas");

                    echo '<option value="'.$tx[$intA]->term_id.'" '.($tx[$intA]->term_id == $current_tournament?'selected':'').'>'.$tx[$intA]->name.'</option>';

                }

            echo '</select>';
            ?>
            </div>
        </div>
        <?php
    }
     public  function taxonomy_save_postdata( $post_id ) {

      if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) 
          return;
      if(!isset($_POST[$this->name_slug.'_noncename']))
          return;    
      if ( !wp_verify_nonce( $_POST[$this->name_slug.'_noncename'], plugin_basename( __FILE__ ) ) )
          return;

      if ( 'jsprt_achv_season' == $_POST['post_type'] ) 
      {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
      }
      else
      {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
      }
      if(isset($_POST[$this->name])){
        $type_ID = $_POST[$this->name];

        $type = ( $type_ID > 0 ) ? get_term( $type_ID, $this->name )->slug : NULL;

        wp_set_object_terms(  $post_id , $type, $this->name );
      }
    }
    
    
    
}
/*<!--jsonlyinproPHP-->*/
add_action('joomsport_matchday_pre_add_form',array('JoomSportTaxonomyMatchday','generate_button'));
/*<!--/jsonlyinproPHP-->*/
