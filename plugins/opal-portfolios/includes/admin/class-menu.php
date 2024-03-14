<?php 
defined( 'ABSPATH' ) || exit();

/**
 * @Class PE_Portfolio_Setting
 * 
 * Entry point class to setup load all files and init working on frontend and process something logic in admin
 */
class PE_Portfolio_Setting {

    private $key = 'portfolio_settings';

        /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';

  public function __construct () {

        add_action( 'admin_init', array( $this, 'init' ) );

        add_action( 'admin_menu' , array( $this, 'create_portfolio_menus'  ) );

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 999);

  }

    public function enqueue_scripts(){
        wp_enqueue_style('portfolio-admin-style', PE_PLUGIN_URI . 'assets/css/admin-styles.css');
    }

    /**
     * Register our setting to WP
     * @since  1.0
     */
    public function init() {
        register_setting( $this->key, $this->key );

    }

    /**
     * Retrieves the admin menu args
     *
     * @return array  Admin menu args
     */
    public function create_portfolio_menus() {
        add_submenu_page( 
            'edit.php?post_type='.PE_POST_TYPE, __( 'Settings', 'opalportfolios' ), __( 'Settings', 'opalportfolios' ), 
            'manage_options', 
            'portfolio-settings',
             array($this,'render'),
            "", 
            100
        );
    }
    
    public function wpopal_portfolio_title(){
        return __( 'Welcome to Use Wpopal Portfolio', 'opalportfolios' );
    }
    /**
     * Header section
     *
     * @param  string $type The current tab
     * @return void
     */
    protected function wpopal_portfolio_header( $type='' ){ 
        global $wpopal_version;
    ?>

        <section class="jumbotron text-center p-4">
            <a href="#"><img src="<?php echo  PE_PLUGIN_URI.'assets/images/menu-icon-red.png'; ?>"><div></div></a>


            <div class="container">
              <h1 class="jumbotron-heading"><?php echo $this->wpopal_portfolio_title(); ?></h1>
            </div>
        </section>
    <?php
    }

    public function get_menus(){
        $settings = $this->portfolio_settings( null );

        $tabs = array();
        $tabs['general']  = __( 'General', 'opalportfolios' );
        $tabs['shortcodes']   = __( 'Shortcodes', 'opalportfolios' );
        $tabs['media'] = __( 'Media', 'opalportfolios' );
        $tabs['help'] = __( 'Help', 'opalportfolios' );

        return apply_filters( "wpopal_portfolio_menu", $tabs );

    }

    /**
     *
     */
    public function render() {   
        $menus = $this->get_menus();

        $active = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $menus ) ? $_GET['tab'] : 'general';
    ?>
        <div class="wrap portfolio_settings_page cmb2_options_page <?php echo $this->key; ?>">
            <h2 class="nav-tab-wrapper">
                <?php
                foreach ( $menus as $tab_id => $tab_name ) :
                    $tab_url = esc_url( add_query_arg( array(
                        'settings-updated' => false,
                        'tab'              => $tab_id
                    ) ) ); ?>

                    <a href="<?php echo esc_url( $tab_url ) ?>" title="<?php echo esc_attr( $tab_name ) ?>" class="nav-tab <?php if( $active == $tab_id) :?>active<?php endif; ?>">
                        <?php echo esc_html( $tab_name ); ?>
                    </a>
                <?php endforeach; ?>
            </h2>
            
            <?php 
                if( isset($active) && ( $active == 'media' || $active == 'general' ) ) {
                    cmb2_metabox_form( $this->portfolio_settings( $active ), $this->key );
                }else {
                    opalportfolio_get_template_part('includes/admin/screens/class', $active ); 
                }
            ?>

        </div><!-- .wrap -->

    <?php }


    public function portfolio_settings( $active ) { 
        $portfolio_settings = array(

            /**
             * General Settings
             */
            'general'     => array(
                'id'         => 'options_page',
                'title' => __( 'General Settings', 'opalportfolios' ),
                'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
                'fields'     => apply_filters( 'opalportfolios_settings_general', array(
                        array(
                            'name' => __( 'Slug Link Setting', 'opalportfolios' ),
                            'desc' => '<td><hr><p class="tags-description"><b>Note: When you edit Slug bellow you must apply them in left menu > Setting > Permalinks > Save Changes</b></p><hr></td>',
                            'type' => 'title',
                            'id'   => 'opalportfolios_title_general_settings_2'
                        ),
                        array(
                            'name'    => __( 'Slug portfolio', 'opalportfolios' ),
                            'desc'    => __( 'You can change slug name of portfolio link . (e.g: http://localhost/wordpress/<span style="color:red;" >portfolio</span>/jane-done/)<br> the <span style="color:red;" >portfolio</span> is slug name', 'opalportfolios' ),
                            'id'      => 'slug_portfolios',
                            'type'    => 'text',
                            'default' => 'portfolio',
                            
                        ),
                        array(
                            'name'    => __( 'Slug category portfolio', 'opalportfolios' ),
                            'desc'    => __( 'You can change slug name of category portfolio link . (e.g: http://localhost/wordpress/<span style="color:red;" >portfolio_cat</span>/jane-done/)<br> the <span style="color:red;" >portfolio_cat</span> is slug name', 'opalportfolios' ),
                            'id'      => 'slug_category_portfolio',
                            'type'    => 'text',
                            'default' => 'portfolio_cat',
                            
                        ),
                    )
                )
            ),// end general    

            /**
             * General Settings
             */

            'media'     => array(
                'id'    => 'options_media',
                'title' => __( 'Media Settings', 'opalportfolios' ),
                'option_key' => 'myprefix_options', // The option key and admin menu page slug.
                'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
                'fields'     => apply_filters( 'portfolio_settings_media', array( 

                        //Thumbnail size
                        array(
                            'name' => __( 'Thumbnail size', 'opalportfolios' ),
                            'desc' => '',
                            'type' => 'title',
                            'id'   => 'title_media_settings_1',
                        ), 
                        array(
                            'id'      => 'thumbnail-w',
                            'name'    => __( 'Width', 'opalportfolios' ),
                            'default' => '150',
                            'type'    => 'text',
                        ),
                        array(
                            'id'      => 'thumbnail-h',
                            'name'    => __( 'Height', 'opalportfolios' ),
                            'default' => '150',
                            'type'    => 'text',
                        ),

                        //Medium size
                        array(
                            'name' => __( 'Medium size', 'opalportfolios' ),
                            'desc' => '',
                            'type' => 'title',
                            'id'   => 'title_media_settings_2',
                        ), 
                        array(
                            'id'      => 'medium-w',
                            'name'    => __( 'Width', 'opalportfolios' ),
                            'default' => '300',
                            'type'    => 'text',
                        ),
                        array(
                            'id'      => 'medium-h',
                            'name'    => __( 'Height', 'opalportfolios' ),
                            'default' => '300',
                            'type'    => 'text',
                        ),

                        //Large  size
                        array(
                            'name' => __( 'Large size', 'opalportfolios' ),
                            'desc' => '',
                            'type' => 'title',
                            'id'   => 'title_media_settings_3',
                        ), 
                        array(
                            'id'      => 'large-w',
                            'name'    => __( 'Width', 'opalportfolios' ),
                            'default' => '1024',
                            'type'    => 'text',
                        ),
                        array(
                            'id'      => 'large-h',
                            'name'    => __( 'Height', 'opalportfolios' ),
                            'default' => '1024',
                            'type'    => 'text',
                        ),
                    )
                )
            ),// end general

        );
        
        //Return all settings array if necessary
        if ( $active === null || ! isset( $portfolio_settings[ $active ] ) ) {
            return apply_filters( 'portfolio_registered_settings', $portfolio_settings );
        }

        // Add other tabs and settings fields as needed
        return apply_filters( 'portfolio_registered_settings', $portfolio_settings[ $active ] );
    }
} 

new PE_Portfolio_Setting();
?>