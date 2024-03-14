<?php
if ( ! class_exists( 'CF7_DATASOURCE_BASE' ) ) {
	class CF7_DATASOURCE_BASE {

		private $constants;
		private $manager;

		public function __construct( $manager ) {
			global $wpdb;
			$this->manager   = $manager;
			$current_user    = wp_get_current_user();
			$this->constants = array(
				// BLOG CONSTANTS
				'blog.id'                 => get_current_blog_id(), // current Blog ID

				// DB CONSTANTS
				'wpdb.prefix'             => $wpdb->prefix, // database prefix
				'wpdb.comments'           => $wpdb->comments, // name of Comments table
				'wpdb.commentmeta'        => $wpdb->commentmeta, // name of Comment Metadata table
				'wpdb.links'              => $wpdb->links, // name of Links table
				'wpdb.options'            => $wpdb->options, // name of Options table
				'wpdb.postmeta'           => $wpdb->postmeta, // name of Post Metadata table
				'wpdb.posts'              => $wpdb->posts, // name of Posts table
				'wpdb.terms'              => $wpdb->terms, // name of Terms table
				'wpdb.term_relationships' => $wpdb->term_relationships, // name of Term Relationships table
				'wpdb.term_taxonomy'      => $wpdb->term_taxonomy, // name of Term Taxonomy table
				'wpdb.termmeta'           => $wpdb->termmeta, // name of Term Meta table
				'wpdb.usermeta'           => $wpdb->usermeta, // name of User Metadata table
				'wpdb.users'              => $wpdb->users, // name of Users table
				'wpdb.blogs'              => $wpdb->blogs, // name of Multisite Blogs table
				'wpdb.blog_versions'      => ! empty( $wpdb->blog_versions ) ? $wpdb->blog_versions : '', // name of Multisite Blog Versions table
				'wpdb.site'               => $wpdb->site, // name of Multisite Sites table
				'wpdb.sitecategories'     => $wpdb->sitecategories, // name of Multisite Sitewide Terms table
				'wpdb.sitemeta'           => $wpdb->sitemeta, // name of Multisite Site Metadata table

				// CURRENT USER PROPERTIES
				'user.id'                 => $current_user->ID,
				'user.login'              => ( $current_user->has_prop( 'user_login' ) ) ? $current_user->user_login : '',
				'user.nicename'           => ( $current_user->has_prop( 'user_nicename' ) ) ? $current_user->user_nicename : '',
				'user.email'              => ( $current_user->has_prop( 'user_email' ) ) ? $current_user->user_email : '',
				'user.url'                => ( $current_user->has_prop( 'user_url' ) ) ? $current_user->user_url : '',
				'user.display_name'       => ( $current_user->has_prop( 'display_name' ) ) ? $current_user->display_name : '',
				'user.first_name'         => ( $current_user->has_prop( 'first_name' ) ) ? $current_user->first_name : '',
				'user.last_name'          => ( $current_user->has_prop( 'last_name' ) ) ? $current_user->last_name : '',
			);

			if ( method_exists( $this->manager, 'get_current_post_id' ) ) {
				$post_id = $this->manager->get_current_post_id();
			}
			if ( ! empty( $post_id ) ) {
				// CURRENT POST PROPERTIES
				$row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->posts . ' WHERE ID=%d', $post_id ), ARRAY_A );
				if ( ! empty( $row ) ) {
					foreach ( $row as $key => $value ) {
						$key                               = strtolower( $key );
						$this->constants[ 'post.' . $key ] = $value;
					}
				}
			} else {
				$columns = $wpdb->get_results( 'SHOW COLUMNS FROM ' . $wpdb->posts, ARRAY_A );
				foreach ( $columns as $column ) {
					$key                               = strtolower( $column['Field'] );
					$this->constants[ 'post.' . $key ] = null;
				}
			}
		} // End __construct

		protected function default_attributes() {
			return array(
				'id'       => '', // Required
				'debug'    => 0,  // If debug="1" the errors are registered in the error logs file.
				'type'     => '', // database, csv, json, post, taxonomy, user
				'callback' => '', // Callback function to preprocess the records

				/* ATTRIBUTES SHARED BY CLIENT AND SERVER SIDE DATA SOURCES */

				'function'  => '', // client and server side functions names
				'parameters'=> '', // function parameters
			);
		} // End default_attributes

		protected function replace_vars( $str, $variables ) {
			$str = $this->replace_constants($str);
			if (
				! empty( $variables['fields'] ) &&
				is_array( $variables['fields'] )
			) {
				foreach ( $variables['fields'] as $field => $value ) {
					  $to_replace = preg_quote( '{field.' . $field . '}' );
					if ( is_array( $value ) ) {
						array_walk(
							$value,
							function( &$v, $i ) {
								$v = esc_sql( $v );
							}
						);
						$replacement = implode( ',', $value );
					} else {
						$replacement = esc_sql( $value );
					}
					$str = preg_replace( '/' . $to_replace . '/i', $replacement, $str );
				}
			}

			if (
				! empty( $variables['variables'] ) &&
				is_array( $variables['variables'] )
			) {
				foreach ( $variables['variables'] as $variable => $value ) {
					$to_replace = preg_quote( '{var.' . $variable . '}' );
					if ( is_array( $value ) ) {
						array_walk(
							$value,
							function( &$v, $i ) {
								$v = esc_sql( $v );
							}
						);
						$replacement = implode( ',', $value );
					} else {
						$replacement = esc_sql( $value );
					}
					$str = preg_replace( '/' . $to_replace . '/i', $replacement, $str );
				}
			}
			return $str;
		} // End replace_vars

		public function replace_constants( $str ) {
			foreach ( $this->constants as $name => $value ) {
				$name = preg_quote( "{{$name}}" );
				$str  = preg_replace( '/' . $name . '/i', is_null( $value ) ? 'null' : $value, $str );
			}
			return $str;
		} // End replace_constants

		public function ds_options() {
			return '';
		} // End ds_options

		public function ds_settings() {
			 return '';
		} // End ds_settings

		protected function error_log( $mssg ) {
			 $this->manager->error_log( $mssg );
		} // End error_log
	} // End class CF7_DATASOURCE_BASE
}
