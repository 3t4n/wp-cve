<?php
/*
* A class for admin messages & nag messages
*/


if ( !class_exists( 'proto_client' ) ) {
	/**
	 * proto_client class.
	 */
	class proto_client {

		public $path = "";
		public $fullpath = "";
		public $id = "";
		public $ver = "";
		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed   $path - the root path to the file (can be local)
		 * @param mixed   $file - the full path of the file
		 * @param mixed   $ver  - a versin number which is used as the sub directory for the full path
		 * @param mixed   $id   - the unique id prefix for user meta
		 * @return void
		 */
		function __construct( $path, $file, $ver, $id ) {
			$this->id = str_replace( '-', '_', $id );
			$this->fullpath = $path . '/' . $ver . '/' .$file;
			$this->path = $path;
			$this->ver = $ver;
			add_action( 'admin_notices', array( $this, 'proto_admin_notice' ) );
			add_action( 'admin_init', array( $this, 'proto_admin_nag_ignore' ) );
		}
		/**
		 * get_html_output function.
		 * Read the file if it exists and get HTML from the response
		 *
		 * @access public
		 * @return void
		 */
		function get_html_output() {
			$result_body = "";
			if ( function_exists( 'wp_remote_get' ) ) {

				$url_query = $this->fullpath ."?ver=$this->ver";

				$result = wp_remote_get( $url_query );     //query the file

				if ( !is_wp_error( $result ) && $result['response'] == 200 ) {
					//successful read of the file
					$result_body = $result['body'];      //here we get the message


				}
				return $result_body;

			}
		}


		/**
		 * proto_admin_notice function.
		 * Display notice on the admin screen
		 *
		 * @access public
		 * @return void
		 */
		function proto_admin_notice() {
			global $current_user ;
			$user_id = $current_user->ID;
			$ver = str_replace( "." , "_" , $this->ver );
			$getvar = $this->id .'_nag_ignore_' . $ver;
			$metavar = $this->id .'_ignore_notice_' . $ver;

			if ( ! get_user_meta( $user_id, $metavar ) ) {     //Check the user meta to see if the user has not ignored this message yet
				$message = trim( $this->get_html_output() );   //Get the HTML output
				if ( $message != "" ) {
					/* Hide notice */
					echo '<div class="updated"><p>';
					printf( __( $message .' | <a href="%1$s">Hide Notice</a>' ), '?'.$getvar.'=0' );
					echo "</p></div>";
				}
			}
		}
		/**
		 * proto_admin_nag_ignore function.
		 * Ignore function disables admin message
		 *
		 * @access public
		 * @return void
		 */
		function proto_admin_nag_ignore() { //user selects nag ignore
			global $current_user;
			$user_id = $current_user->ID;
			$ver = str_replace( '.' , '_' , $this->ver );
			$getvar = $this->id .'_nag_ignore_' . $ver;
			$metavar = $this->id .'_ignore_notice_' . $ver;
			if ( isset( $_GET[$getvar] ) && '0' == $_GET[$getvar] ) {
				add_user_meta( $user_id, $metavar, 'true', true ); //set the meta value to ignore this
				if ( wp_get_referer() ) {
					/* Redirects user to where they were before */
					wp_safe_redirect( wp_get_referer() );
				} else {
					/* This will never happen, I can almost gurantee it, but we should still have it just in case*/
					wp_safe_redirect( home_url() );
				}
			}
		}
	}
}