<?php
/*
Plugin Name: Hide Page And Post Title
Plugin URI: https://profiles.wordpress.org/arjunthakur#content-plugins/
Description: Hide the title on single pages and posts.
Author: Arjun Thakur
Version: 1.5.7
License: GPLv2 or later
Author URI: https://profiles.wordpress.org/arjunthakur
Text Domain: hpt

*/

if ( !class_exists( 'hpt_hidepagetitle' ) ) {
/*Class*/
    class hpt_hidepagetitle {
    	private $hpt_slug = 'hpt_headertitle';
    	private $hpt_selector = '.entry-title';
    	private $title;
    	private $hpt_afthead = false;
/*Constructor*/
        function __construct(){
	        add_action( 'add_meta_boxes', array( $this, 'hpt_hptaddbox' ) );
			add_action( 'save_post', array( $this, 'hpt_hptsave' ) );
			add_action( 'delete_post', array( $this, 'hpt_hptdelete' ) );
			add_action( 'wp_head', array( $this, 'hpt_hptheadinsert' ) );
			add_action( 'the_title', array( $this, 'hpt_hptwraptitle' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'hpt_hptloadscripts' ) );
        }
/*Function HPT hidden*/
		private function hpt_ishidden(  ){	if( is_singular() ){
				global $post;
				$toggle = get_post_meta( $post->ID, $this->hpt_slug, true );
				if( (bool) $toggle ){return true;} else {return false;}}
				else {return false;}
				}
/*Function hptheadinseart for Hiding page title*/
    	public function hpt_hptheadinsert()
		 { if( $this->hpt_ishidden() ){ ?> <!-- Hide Page Title -->
              <script type="text/javascript">
				jQuery(document).ready(function($){
				  if( $('<?php echo $this->hpt_selector; ?>').length != 0 ) {
					$('<?php echo $this->hpt_selector; ?> span.<?php echo $this->hpt_slug; ?>').parents('<?php echo $this->hpt_selector; ?>:first').hide();
				    } else {
					  $('h1 span.<?php echo $this->hpt_slug; ?>').parents('h1:first').hide();
					  $('h2 span.<?php echo $this->hpt_slug; ?>').parents('h2:first').hide();
				   }
				});
              </script><noscript><style type="text/css"> <?php echo $this->hpt_selector; ?> { display:none !important; }</style></noscript>
             <!-- END Hide Page Title-->
	    <?php }$this->hpt_afthead = true;
		 }
/*Function hptaddbox*/
		public function hpt_hptaddbox(){
            $posttypes = array( 'post', 'page' );
            $args = array(
                       'public'   => true,
                       '_builtin' => false,
            );

            $output = 'names';
            $operator = 'and';

            $post_types = get_post_types( $args, $output, $operator ); 

            foreach ( $post_types  as $post_type ) {
              
                $posttypes[] = $post_type;

            }  
                        
			foreach ( $posttypes as $posttype ){add_meta_box( $this->hpt_slug, 'Hide Page and Post Title', array( $this, 'build_hptbox' ), $posttype, 'side' );}
		} 
/*Adding box in admindashboard*/
		public function build_hptbox( $post ){
			$value = get_post_meta( $post->ID, $this->hpt_slug, true );
			$checked = '';
			if( (bool) $value ){ $checked = ' checked="checked"'; }
			wp_nonce_field( $this->hpt_slug . '_dononce', $this->hpt_slug . '_noncename' );	?>
			<label><input type="checkbox" name="<?php echo $this->hpt_slug; ?>" <?php echo $checked; ?> /> Hide the title.</label><?php
		}
/*HPT wraptitle function*/
		public function hpt_hptwraptitle( $hptcontent ){
			if( $this->hpt_ishidden() && $hptcontent == $this->title && $this->hpt_afthead ){$hptcontent = '<span class="' . $this->hpt_slug . '">' . $hptcontent . '</span>';
			}return $hptcontent;
		} 
/*Script*/
		public function hpt_hptloadscripts(){
			global $post;
			$this->title = $post->post_title;
			if( $this->hpt_ishidden() ){wp_enqueue_script( 'jquery' );}
		}

/*Autosave metabox*/		
		public function hpt_hptsave( $postID ){
			if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
				|| !isset( $_POST[ $this->hpt_slug . '_noncename' ] )
				|| !wp_verify_nonce( $_POST[ $this->hpt_slug . '_noncename' ], $this->hpt_slug . '_dononce' ) ) {
				return $postID;
			}
			$old = get_post_meta( $postID, $this->hpt_slug, true );
			$new = $_POST[ $this->hpt_slug ] ;
			if( $old ){if ( is_null( $new ) ){delete_post_meta( $postID, $this->hpt_slug );} else { update_post_meta( $postID, $this->hpt_slug, $new, $old );}
			} elseif ( !is_null( $new ) ){add_post_meta( $postID, $this->hpt_slug, $new, true );}
			return $postID;
		}
/*Delete metabox */
		public function hpt_hptdelete( $postID ){delete_post_meta( $postID, $this->hpt_slug );return $postID;}
		public function set_hpt_selector( $hpt_selector ){if( isset( $hpt_selector ) && is_string( $hpt_selector ) ){$this->hpt_selector = $hpt_selector;}
		}

/*ENDclass Hide page title*/
    }$hpt_hidepagetitle = new hpt_hidepagetitle;
}