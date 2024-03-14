<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Setup meta box related functionalities
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Setup_Meta_Box extends WPVR_Meta_Box {
  
	/**
	 * @var string
     * @since 8.0.0
	 */
	protected $title = '';
	
	/**
     * Metabox ID
     * 
	 * @var string
     * @since 8.0.0
	 */
	protected $slug = '';
	
	/**
	 * @var string
     * @since 8.0.0
	 */
	protected $post_type = '';
	
	/**
	 * Metabox context
     * 
     * @var string
     * @since 8.0.0
	 */
	protected $context = '';
	
	/**
	 * Metabox priority
     * 
     * @var string
     * @since 8.0.0
	 */
	protected $priority = '';

    /**
     * Instance of WPVR_Scene class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $scene;

    /**
     * Instance of WPVR_Video class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $video;

    /**
     * Instance of WPVR_General class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $general;


    public function __construct( $slug, $title, $post_type, $context, $priority ) {
        if( $slug == '' || $context == '' || $priority == '' )  {
            return;
        }
    
        if( $title == '' ) {
            $this->title = ucfirst( $slug );
        }
    
        if( empty( $post_type ) ) {
            return;
        }
    
        $this->title     = $title; 
        $this->slug      = $slug;
        $this->post_type = $post_type;
        $this->context   = $context;
        $this->priority  = $priority;

        $this->scene             = new WPVR_Scene(); 
        $this->video             = new WPVR_Video(); 
        $this->general           = new WPVR_General();  
    
        add_action( 'add_meta_boxes', array( $this, 'register' ) );
    }


    /**
     * Register custom meta box
     * 
     * @param string $post_type
     * 
     * @return void
     * @since 8.0.0
     */
    public function register( $post_type ) {
        if ( $post_type == $this->post_type ) {
            add_meta_box( $this->slug, $this->title, array( $this, 'render' ), $post_type, $this->context, $this->priority );
        }
    }
    

    /**
     * Render custom meta box
     * 
     * @param object $post
     * 
     * @return void
     * @since 8.0.0
     */
    public function render( $post ) {

        $primary = WPVR_Meta_Field::get_primary_meta_fields();
        $post_meta_data = get_post_meta($post->ID, 'panodata', true);
        $post_meta_data = (is_array($post_meta_data)) ? $post_meta_data : array($post_meta_data);

        $postdata = array_merge($primary, $post_meta_data);
        // active tab variables
        $active_tab = 'scene';
        $scene_active_tab = 1;
        $hotspot_active_tab = 1;
        if (isset($_GET['active_tab'])) {
            $active_tab = sanitize_text_field($_GET['active_tab']);
        }
        if (isset($_GET['scene'])) {
            $scene_active_tab = sanitize_text_field($_GET['scene']);
        }
        if (isset($_GET['hotspot'])) {
            $hotspot_active_tab = sanitize_text_field($_GET['hotspot']);
        }

        // Start custom meta box rendering
        ob_start();

        ?>

        <div class="pano-setup">

            <input type="hidden" value="<?php echo esc_attr($active_tab);?>" name="wpvr_active_tab" id="wpvr_active_tab"/>
            <input type="hidden" value="<?php echo esc_attr($scene_active_tab);?>" name="wpvr_active_scenes" id="wpvr_active_scenes"/>
            <input type="hidden" value="<?php echo esc_attr($hotspot_active_tab);?>" name="wpvr_active_hotspot" id="wpvr_active_hotspot"/>                            

            <!-- start rex-pano-tabs -->
            <div class="rex-pano-tabs">
                
                <?php WPVR_Meta_Field::render_pano_tab_nav($postdata); ?>
                <!-- start rex-pano-tab-content -->
                <div class="rex-pano-tab-content" id="wpvr-main-tab-contents">

                    <!-- start scenes tab -->
                    <div class="rex-pano-tab wpvr_scene active" id="scenes">
                        <?php 
                            $this->scene->render_scene($postdata);
                        ?>
                    </div>
                    
                    <!-- start general tab -->
                    <div class="rex-pano-tab general" id="general">
                        <?php 
                            $this->general->render_setting($postdata);
                        ?>
                    </div>

                    <!-- start video tab content -->
                    <div class="rex-pano-tab video" id="video">
                        <?php 
                            $this->video->render_video($postdata); 
                        ?>
                    </div>

                    <!-- This hook will render floor plan tab content -->
                    <?php do_action( 'include_floor_plan_meta_content', $postdata )?>

                    <!-- This hook will render background Tour tab content -->
                    <?php do_action( 'include_background_tour_meta_content', $postdata )?>

                    <!-- This hook will render Street View tab content -->
                    <?php do_action( 'include_street_view_meta_content', $postdata )?>

                    <?php do_action( 'include_export_meta_content', $postdata, $post )?>

                </div>
                <!-- end rex-pano-tab-content -->
            </div>
            <!-- end rex-pano-tabs -->
        </div>
        <div class="wpvr-loading" style="display:none;">Loading&#8230;</div>

        <?php
        ob_end_flush();
        // End custom meta box rendering
    }    

}