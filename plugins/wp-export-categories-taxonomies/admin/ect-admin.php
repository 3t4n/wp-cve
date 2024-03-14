<?php
/**
 * Wp Export Categories & tax.
 *
 * @package   WP_Ect_Admin
 * @author    Damian Logghe <info@timersys.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 *
 * @package WP_Ect_Admin
 * @author  Damian Logghe <info@timersys.com>
 */
 
require_once(dirname(__FILE__).'/ECT_Admin_Base.php');
 
class WP_Ect_Admin extends ECT_Admin_Base{

        /**
         * Instance of this class.
         *
         * @since    1.0.0
         *
         * @var      object
         */
        protected static $instance = null;

        /**
         * Slug of the plugin screen.
         *
         * @since    1.0.0
         *
         * @var      string
         */
        protected $plugin_screen_hook_suffix = null;

        /**
         * Sections (tabs id => Tabs Titles)
         *
         * @since    1.0.0
         *
         * @var      array
         */
        protected $sections = null;


        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         *
         * @since     1.0.0
         */
        function __construct() {

               
                $plugin = WP_Ect::get_instance();
                //We are using slug also for options name
                $this->plugin_slug  = $plugin->get_plugin_slug();
                $this->options_name = $this->plugin_slug .'_settings';
                
                $this->sections['general']                   = __( 'Export', $this->plugin_slug );
				
				$this->WPB_PLUGIN_NAME 		=   'WP Export Cats & Taxs';
				$this->WPB_PLUGIN_VERSION 	=   ECT_VERSION;
				$this->WPB_SLUG			 	=   'wp-export-categories-taxonomies';
				$this->WPB_PLUGIN_URL		=   ECT_PLUGIN_URL;
				
				$this->WPB_PREFIX			=   $this->plugin_slug;
				

                // Add the options page and menu item.
                add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

                // Add an action link pointing to the options page.
                $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
                add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
				
				add_action('admin_init' , array( $this, 'handle_export'));
				
				parent::__construct();

        }

        /**
         * Return an instance of this class.
         *
         * @since     1.0.0
         *
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {


                // If the single instance hasn't been set, set it now.
                if ( null == self::$instance ) {
                        self::$instance = new self;
                }

                return self::$instance;
        }

  

        /**
         * Register the administration menu for this plugin into the WordPress Dashboard menu.
         *
         * @since    1.0.0
         */
        public function add_plugin_admin_menu() {

      
                $this->plugin_screen_hook_suffix = add_submenu_page(
                		'tools.php',
                        __( 'Wp Export Cats & Taxs', $this->plugin_slug ),
                        __( 'Wp Export Cats & Taxs', $this->plugin_slug ),
                        'manage_options',
                        $this->plugin_slug,
                        array( $this, 'display_page' )
                );

        }


        /**
         * Add settings action link to the plugins page.
         *
         * @since    1.0.0
         */
        public function add_action_links( $links ) {

                return array_merge(
                        array(
                                'Go to Export' => '<a href="' . admin_url( 'tools.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
                        ),
                        $links
                );

        }


		/**
		 *
		 *
		 *
		 */
       function handle_export(){
       

       		if( !isset($_POST['option_page']) || $_POST['option_page'] != $this->plugin_slug.'_settings' ) 
       			return;

       		define( 'WXR_VERSION', '1.2' );
       		
       		
       		/** Load WordPress export API */
	   		require_once( plugin_dir_path( __FILE__ ) . '/includes/export.php' );
       		
       		@$taxs = 	$_POST['ect_settings']['taxs'];
       		if( !empty($taxs) )
       		{
	       		foreach ( $taxs as $k => $val )
	       		{
	       			$taxs_a[] = $k; 
	       		}
				$custom_taxonomies = $taxs_a;
				$custom_terms = (array) get_terms( $custom_taxonomies, array( 'get' => 'all' ) );
       		}
       		
       		if( !empty($_POST['ect_settings']['tags']))
       		{
       			
				$tags = (array) get_tags( array( 'get' => 'all' ) );
       			
       		}
	   		if( !empty($_POST['ect_settings']['cats']) )
       		{
				$categories = (array) get_categories( array( 'get' => 'all' ) );
       		}
   			$sitename = sanitize_key( get_bloginfo( 'name' ) );
			if ( ! empty($sitename) ) $sitename .= '.';
			$filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.xml';
		
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

	
	
			if( !empty($categories) )
			{
				// put categories in order with no child going before its parent
				while ( $cat = array_shift( $categories ) ) {
					if ( $cat->parent == 0 || isset( $cats[$cat->parent] ) )
						$cats[$cat->term_id] = $cat;
					else
						$categories[] = $cat;
				}
			}

			if( !empty($custom_terms) )
			{
				// put terms in order with no child going before its parent
				while ( $t = array_shift( $custom_terms ) ) {
					if ( $t->parent == 0 || isset( $terms[$t->parent] ) )
						$terms[$t->term_id] = $t;
					else
						$custom_terms[] = $t;
				}
			}
	
			unset( $categories, $custom_taxonomies, $custom_terms );			
			

			
			echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";
			
				?>
			<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
			<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
			<!-- You may use this file to transfer that content from one site to another. -->
			<!-- This file is not intended to serve as a complete backup of your site. -->
			
			<!-- To import this information into a WordPress site follow these steps: -->
			<!-- 1. Log in to that site as an administrator. -->
			<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
			<!-- 3. Install the "WordPress" importer from the list. -->
			<!-- 4. Activate & Run Importer. -->
			<!-- 5. Upload this file using the form provided on that page. -->
			<!-- 6. You will first be asked to map the authors in this export file to users -->
			<!--    on the site. For each author, you may choose to map to an -->
			<!--    existing user on the site or to create a new user. -->
			<!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
			<!--    contained in this file into your site. -->
			
			<?php the_generator( 'export' ); ?>
			<rss version="2.0"
				xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
				xmlns:content="http://purl.org/rss/1.0/modules/content/"
				xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				xmlns:dc="http://purl.org/dc/elements/1.1/"
				xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/"
			>
			
			<channel>
				<title><?php bloginfo_rss( 'name' ); ?></title>
				<link><?php bloginfo_rss( 'url' ); ?></link>
				<description><?php bloginfo_rss( 'description' ); ?></description>
				<pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
				<language><?php bloginfo_rss( 'language' ); ?></language>
				<wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
				<wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
				<wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>
			<?php 
			if( !empty($cats) ):
				foreach ( $cats as $c ) : ?>
				<wp:category><wp:term_id><?php echo $c->term_id ?></wp:term_id><wp:category_nicename><?php echo $c->slug; ?></wp:category_nicename><wp:category_parent><?php echo $c->parent ? $cats[$c->parent]->slug : ''; ?></wp:category_parent><?php wxr_cat_name( $c ); ?><?php wxr_category_description( $c ); ?></wp:category>
			<?php endforeach; 
			endif;
			?>
			<?php
			if( !empty($tags) ): 
				foreach ( $tags as $t ) : ?>
				<wp:tag><wp:term_id><?php echo $t->term_id ?></wp:term_id><wp:tag_slug><?php echo $t->slug; ?></wp:tag_slug><?php wxr_tag_name( $t ); ?><?php wxr_tag_description( $t ); ?></wp:tag>
			<?php endforeach; 
			endif;	
			?>
			<?php 
			if( !empty($terms)):	
				foreach ( $terms as $t ) : ?>
				<wp:term><wp:term_id><?php echo $t->term_id ?></wp:term_id><wp:term_taxonomy><?php echo $t->taxonomy; ?></wp:term_taxonomy><wp:term_slug><?php echo $t->slug; ?></wp:term_slug><wp:term_parent><?php echo $t->parent ? $terms[$t->parent]->slug : ''; ?></wp:term_parent><?php wxr_term_name( $t ); ?><?php wxr_term_description( $t ); ?></wp:term>
			<?php endforeach; 
			endif;
			?>
			<?php do_action( 'rss2_head' ); ?>
			</channel>
			</rss><?php	
			die();
       }
}