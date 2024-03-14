<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'LOGICS_Admin' ) ) {
    /**
     * Handle the backend of the Appointment
     *
     * @since 1.0
     */
	class LOGICS_Admin extends wp_rss_importer {
		public $_per_page = '5';
		public $rss_data;
        function __construct() {
			add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
		}
		public function create_admin_menu() {	
			$hook = add_menu_page( 'RSS Importer', __( 'RSS Importer', 'logics' ), apply_filters( 'logics_capability', 'manage_options' ), 'logics_managerss', array( $this, 'logics_managerss' ), plugins_url( 'img/rss.png', dirname( __FILE__ ) ), apply_filters( 'logics_menu_position', null ) );
            add_submenu_page( 'logics_managerss', __( 'Manage RSS', 'logics' ), __( 'Manage RSS', 'logics' ), apply_filters( 'logics_capability', 'manage_options' ), 'logics_managerss', array( $this, 'logics_managerss' ) );
			
			add_submenu_page( 'logics_managerss', __( 'Add RSS', 'logics' ), __( 'Add RSS', 'logics' ), apply_filters( 'logics_capability', 'manage_options' ), 'logics_add_rss', array( $this, 'add_rss' ) );

			add_submenu_page( 'logics_managerss', __( 'Setting', 'logics' ), __( 'Setting', 'logics' ), apply_filters( 'logics_capability', 'manage_options' ), 'logics_setting_rss', array( $this, 'setting_rss' ) );
			
            //add_submenu_page( 'logics_store_editor', __( 'FAQ', 'logics' ), __( 'FAQ', 'logics' ), apply_filters( 'logics_capability', 'manage_options' ), 'logics_faq', array( $this, 'show_faq' ) );
        }
		public function add_rss() {
            $this->rss_actions();
            require_once( LOGICS_PLUGIN_DIR . 'admin/templates/add-rss.php' ); 
        }

		/**
         * Setting various option related to plugin
         *
         * @since 2.0
         */ 
		public function setting_rss() {
			 if(isset($_POST['logics_actions']) && ($_POST['logics_actions'] == 'logics_setting_rss')) {
				 update_option('logics_sourcelink_enable',$_POST['logics']['sourcelink']);
				 update_option('logics_sourcelink',$_POST['logics']['sourcelinktext']);
				 update_option('logics_source_url_meta',$_POST['logics']['source_url_meta']);
				 /* Modification  suggested by Elliot */
				 update_option('logics_feed_id_meta',$_POST['logics']['feed_id_meta']);
				 /*End of modification*/
				 echo 'Updated..';
			 }

			 require_once( LOGICS_PLUGIN_DIR . 'admin/templates/setting-rss.php' );
		}
                
        /**
         * If a store form is submitted, process the store data
         *
         * @since 1.0
         * @return void
         */  
        public function rss_actions() {
			if ( isset( $_REQUEST['logics_actions'] ) ) {
				$this->handle_rss_data();
			} 
        }
		/**
         * Process new RSS data
         *
         * @since 1.0
         * @return void
         */
        public function handle_rss_data() {
            
            global $wpdb;
            
			if ( !current_user_can( apply_filters( 'logics_capability', 'manage_options' ) ) )
				die( '-1' );
		
			check_admin_referer( 'logics_' . $_POST['logics_actions'] );
            
            $this->rss_data = $this->validate_rss_data();

			if ( $this->rss_data ) {
				
                $rss_action = ( isset( $_POST['logics_actions'] ) ) ? $_POST['logics_actions'] : '';
                
                switch ( $rss_action ) {
                    case 'add_new_rss':
                        $this->add_new_rss();
                        break;
                    case 'update_rss':
                        $this->update_rss(); 
                        break;
                };   
            }
        }
		
		/**
         * Validate the submitted store data
         * 
         * @since 1.0
         * @return mixed array|void $rss_data the submitted store data if not empty, otherwise nothing
         */
		public function validate_rss_data() {
            
			$rss_data = $_POST['logics'];
			
			if ( empty( $rss_data['title'] ) || ( empty( $rss_data['pid'] ) ) || ( empty( $rss_data['taxid'] ) ) ) {	
                add_settings_error ( 'validate-rss', esc_attr( 'validate-rss' ), __( 'Please fill in all the required fields.', 'logics' ), 'error' );  				
			} else {
				return $rss_data;
			}
		}
		
		function logics_managerss() {
			$this->rss_actions();
            
            $actions = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';
            
            /* Check which store template to show */
            switch ( $actions ) {
                case 'edit_rss':
                    require_once( LOGICS_PLUGIN_DIR . 'admin/templates/edit-rss.php' );
                    break;
                case 'runjob':
                     $this->run_rss($_GET['rss_id']); 
                     break;
				default:
                    require_once( LOGICS_PLUGIN_DIR . 'admin/templates/rss-overview.php' );
                    break;
            } 
		}
		
		/**
         * Update rss details
         * 
         * @since 1.0
         * @param array $rss_data The updated rss data
         * @return void
         */
        public function update_rss() {
           
            global $wpdb;
						
            $result = $wpdb->query( 
                            $wpdb->prepare( 
                                    "
                                    UPDATE $wpdb->rss_settings
                                    SET title = %s, pid = %s, taxid = %s, taxitem = %s, url = %s 
                                    WHERE id = %d",
                                    $this->rss_data['title'],
                                    $this->rss_data['pid'],
                                    $this->rss_data['taxid'],
									implode(',',$this->rss_data['taxitem']),
									$this->rss_data['url'],
                                    $_GET['rss_id']
                                  )
                            );	
            
            if ( $result === false ) {
                $state = 'error';
                $msg = __( 'There was a problem updating the rss details, please try again.', 'logics' );
            } else {
                $_POST = array();
                $state = 'updated';
                $msg = __( 'Rss details updated.', 'logics' );
            } 
        
            add_settings_error ( 'update-rss', esc_attr( 'update-rss' ), $msg, $state );
		}
		
		/**
         * Add a new rss to the db
         * 
         * @since 1.0
         * @param array $rss_data The submitted rss data
         * @return void
         */
		public function add_new_rss() {

            global $wpdb;
						
            $result = $wpdb->query( 
                            $wpdb->prepare( 
                                    "
                                    INSERT INTO $wpdb->rss_settings
                                    (title, pid, taxid, taxitem, url)
                                    VALUES (%s, %s, %s, %s, %s)
                                    ", 
                                    $this->rss_data['title'],
                                    $this->rss_data['pid'],
                                    $this->rss_data['taxid'],
									implode(',',$this->rss_data['taxitem']),
									$this->rss_data['url']
                                    )
                               );
             
            if ( $result === false ) {
                $state = 'error';
                $msg = __( 'There was a problem saving the new rss details, please try again.', 'logics' );
            } else {
                $_POST = array();
                $state = 'updated';
                $msg = __( 'RSS succesfully added.', 'logics' );
            } 
        
            add_settings_error ( 'add-rss', esc_attr( 'add-rss' ), $msg, $state );  
		}
		
		/**
         * Get the data for a single RSS
         * 
         * @since 1.0
         * @param string $rss_id The id for a single rss
         * @return array $result The rss details
         */
        public function get_rss_data( $rss_id ) {
            
             global $wpdb;

             $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->rss_settings WHERE id = %d", $rss_id ), ARRAY_A );		

             return $result;
        }
		
		/**
         * Get data from RSS URL and create as post
         * 
         * @since 1.0
         */
		public function run_rss($rss_id) {
			$rdata = $this->get_rss_data($rss_id);
			$content = file_get_contents($rdata['url']);
			$termArray = array();
			foreach(explode(',',$rdata['taxitem']) as $r) {
				$term = get_term( $r, $rdata['taxid'] );
				$termArray[] = $term->name;
			}
			if(get_option('logics_sourcelink_enable') == 1) {
				$slink = 1;
			} else {
				$slink = 0;
			}
			$x = new SimpleXmlElement($content);			
			$cnt = 1;
			foreach($x->channel->item as $key=>$entry) 
				{
					$xmltoarray = array( (string) $entry->link );
					$pt = $this->wp_exist_page_by_title($entry->title);
					if($pt == '') {
					// Create post object
					$desc = $entry->description;
					if($slink == 1) {
						$desc .= $desc.' <br />';
						if(get_option('logics_sourcelink')) {
							$desc .= '<a href="'.$xmltoarray[0].'" target="_blank">'.get_option('logics_sourcelink').'</a>';
						}
					}
					$my_post = array(
						'post_type'     => $rdata['pid'],
						'post_title'    => $entry->title,
						'post_content'  => $desc,
						'post_status'   => 'publish',
						'post_author'   => 1
					);
					
					// Insert the post into the database
					$post_id = wp_insert_post( $my_post, false);
					if(get_option('logics_source_url_meta') == 1) {
						add_post_meta( $post_id, 'wpri_sourcelink', $xmltoarray[0] );
					} 
					/* Modification  suggested by Elliot */
					if(get_option('logics_feed_id_meta') == 1) {
						add_post_meta( $post_id, 'wpri_feedid', $rss_id );
					}
					/*End of mod*/
					wp_set_object_terms( $post_id, $termArray, $rdata['taxid'] );
					global $wpdb;
					$result = $wpdb->query( 
								$wpdb->prepare( 
										"
										UPDATE $wpdb->rss_settings
										SET isrun = %d 
										WHERE id = %d",
										'1',
										$rdata['id']
									  )
								);	
					}
					$cnt++;
				}
			echo '<p style="font-size: 20px; text-align: center;">RSS has been updated <br /> <a href="admin.php?page=logics_managerss">Go to manage screen</a></p>';
		}
	}
	new LOGICS_Admin;
}