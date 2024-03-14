<?php

/**
 * If the classes that extend this class are accessed directly through the 'editor' folder
 * php files, WP_PLUGIN_DIR will not exist.
 */
if ( defined( WP_PLUGIN_DIR ) ){
	require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
}

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 5 May 2011
 *
 * @file AbstractWordpressConnectPlugin.php
 *
 */
abstract class AbstractWordpressConnectPlugin {

	/**
	 * Creates a new instance of WordpressConnectPlugin
	 */
	public function AbstractWordpressConnectPlugin(){

		$this->addWordpressHooks();

	}

	/**
	 * Adds wordpress hooks (and filters) necessary for this plugin
	 *
	 * @private
	 * @since	2.0
	 */
	private function addWordpressHooks(){

		add_filter( 'the_content', array( &$this, 'contentHandler' ) );
		add_filter( 'the_excerpt', array( &$this, 'contentHandler' ) );

	}

	/**
	 * Handles the wp content. The function will determine whether the
	 * plugin is enabled for a particular post/page, then it will
	 * determine the position at which it is supposed to be displayed
	 * within the post/page
	 *
	 * @param string $content
	 *
	 * @access private
	 */
	abstract public function contentHandler( $content );

	/**
	 * Checks whether the plugin is enabled for the currently
	 * viewed page/post
	 *
	 * @param $option_name	The name of the options that stores the
	 * 						display settings
	 *
	 * @return 	<code>TRUE</code> if and only if the like button plugin is
	 * 			enabled for the currently viewed page/post.
	 *
	 * @access private
	 * @since	2.0
	 * @static
	 */
	public static function isEnabledOnCurrentView( $option_name ){

		
		if ( is_feed() ){ return FALSE; }		
		
		$options = get_option( $option_name );
		
		$isEnabledEverywhere = !empty( $options[ WPC_OPTIONS_DISPLAY_EVERYWHERE ] );
		if ( $isEnabledEverywhere ){ return TRUE; }

		$isEnabledNowhere = !empty( $options[ WPC_OPTIONS_DISPLAY_NOWHERE ] );
		if ( $isEnabledNowhere ){ return FALSE;	}

		if ( ( is_home() || is_front_page() ) && !empty( $options[ WPC_OPTIONS_DISPLAY_HOMEPAGE ] ) ){
			return TRUE;
		}
		elseif ( is_singular() ){

			$type = get_post_type(); 
			if ( $type == 'post' && !empty( $options[ WPC_OPTIONS_DISPLAY_POSTS ] ) ){ return TRUE; }
			elseif ( $type == 'page' && !empty( $options[ WPC_OPTIONS_DISPLAY_PAGES ] ) ){ return TRUE; }
					
		}		
		
		elseif ( is_tag() && !empty( $options[ WPC_OPTIONS_DISPLAY_TAGS ] ) ){
			return TRUE;
		}
		elseif ( is_category() && !empty( $options[ WPC_OPTIONS_DISPLAY_CATEGORIES ] ) ){
			return TRUE;
		}
		elseif ( is_archive() && !empty( $options[ WPC_OPTIONS_DISPLAY_ARCHIVE ] ) ){
			return TRUE;
		}
		elseif( is_search() && empty( $options[ WPC_OPTIONS_DISPLAY_SEARCH ] ) ){
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Returns the value of the custom fields with the specified key from
	 * the specified post. If the specified key is not set for the specified
	 * post, the default value set into the specified <i>$option</i> with
	 * the given <i>$option_name</i> will be returned instead ( if it exists )
	 *
	 * @param int $post_id
	 * @param string $key
	 * @param bool $single
	 * @param string $option
	 * @param string $option_name
	 */
	public static function getPostMetaOrDefault( $post_id, $key, $single, $option, $option_name ){

		$meta = get_post_meta( $post_id, $key, $single );

		if ( empty( $meta ) ){

			$options = get_option( $option );
			if ( !empty( $options ) && isset( $options[ $option_name ] ) ){
				$meta = $options[ $option_name ];
			}
		}

		return $meta;

	}
}

?>