<?php
namespace tmc\revisionmanager\src\Components;

use Elementor\Plugin;
use shellpress\v1_4_0\src\Shared\Components\IComponent;
use tmc\revisionmanager\src\App;
use WP_Error;
use WP_Post;
use WP_Query;
use WP_User;
use wpdb;

/**
 * @author jakubkuranda@gmail.com
 * Date: 08.03.2018
 * Time: 14:14
 */

class Utilities extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

	}

    /**
     * Copy post to new one or copy all data from original to target.
     *
     * @param int   $sourcePostId
     * @param int   $targetPostId
     * @param array $args
     *
     * @return bool|int
     */
    public function copyPost( $sourcePostId, $targetPostId = null, $args = array() ){

        $post = get_post( $sourcePostId );

        if( ! $post ) return false; // Bail early.

        $linkedArgs = array(
            'comment_status'            =>  $post->comment_status,
            'post_author'               =>  get_current_user_id(),
            'post_content'              =>  wp_slash( $post->post_content ),
            'post_content_filtered'     =>  $post->post_content_filtered,
            'post_excerpt'              =>  $post->post_excerpt,
            'post_parent'               =>  $post->post_parent,
            'post_password'             =>  $post->post_password,
            'post_status'               =>  $post->post_status,
            'post_title'                =>  $post->post_title,
            'post_name'                 =>  $post->post_name . '-clone',
            'post_type'                 =>  $post->post_type,
            'tags_input'                =>  $post->tag_input,
            'menu_order'                =>  $post->menu_order,
	        'post_date'                 =>  $post->post_date,
	        'post_date_gmt'             =>  $post->post_date_gmt
        );

	    $linkedArgs = wp_parse_args( $args, $linkedArgs );                                              //  Merge Defaults.
	    $linkedArgs = array_filter( $linkedArgs, function( $value ){ return ! is_null( $value ); } );   //  Remove nulls.

	    //  ----------------------------------------
	    //  Know your target
	    //  ----------------------------------------

        if( empty( $targetPostId ) ){                                   //  Create new post.

            $targetPostId = wp_insert_post( $linkedArgs );

        } else if( is_string( get_post_status( $targetPostId ) ) ){     //  Update post.

            $linkedArgs['ID'] = $targetPostId;
            $targetPostId     = wp_update_post( $linkedArgs );

        } else {                                                        //  Given target does not exist.

            return false;

        }

	    //  ----------------------------------------
	    //  Return
	    //  ----------------------------------------

        return $targetPostId;

    }

    /**
     * @param int $sourcePostId
     * @param int $targetPostId
     *
     * @return void
     */
    public function copyTaxonomies( $sourcePostId, $targetPostId ){

        $taxonomies = get_post_taxonomies( $sourcePostId );

        foreach( $taxonomies as $taxonomy ){
            $postTerms = wp_get_object_terms( $sourcePostId, $taxonomy, array( 'fields' => 'slugs' ) );
            wp_set_object_terms( $targetPostId, $postTerms, $taxonomy, false );
        }

    }

    /**
     * @param int $sourcePostId
     * @param int $targetPostId
     *
     * @return bool
     */
    public function copyMeta( $sourcePostId, $targetPostId ){

		$sourcePost = get_post( $sourcePostId );
		$targetPost = get_post( $targetPostId );

		if( ! $sourcePost || ! $targetPost ) return false;  //  Bail early.

		$sourcePostMeta = (array) get_post_meta( $sourcePostId, '' );
		$targetPostMeta = (array) get_post_meta( $targetPostId, '' );

		//  ----------------------------------------
		//  Remove all meta from target
		//  ----------------------------------------

	    foreach( $targetPostMeta as $key => $values ){

		    delete_post_meta( $targetPostId, $key );

	    }

	    //  ----------------------------------------
	    //  Update target post meta
	    //  ----------------------------------------

	    $blackListKeys = array(
			'_edit_lock',
			'_edit_last'
	    );

	    foreach( $sourcePostMeta as $key => $values ){

		    if( ! in_array( $key, $blackListKeys ) ){

			    foreach( (array) $values as $value ) {

				    $value = maybe_unserialize( $value );

				    add_post_meta( $targetPostId, $key, wp_slash( $value ) );

			    }

		    }

	    }

		return true;

    }

    /**
     * Replace supported codes in given string with data.
     *
     * @param string      $string
     * @param WP_Post|int $post
     *
     * @return string
     */
    public function replaceCodes( $string, $post ) {

	    $post   = get_post( $post );                                        //  Make sure it's object, not ID.
        $author = $post ? get_user_by( 'id', $post->post_author ) : null;   /** @var null|WP_User  $author */

	    if( $post && $author ){

		    //  Patterns

		    $contentPattern = array(
			    '%post_url%',
			    '%post_title%',
			    '%post_admin_url%',
			    '%author_name%',
			    '%author_mail%',
			    '%site_url%'
		    );

		    //  Replacements

		    $contentReplace = array(
			    get_permalink( $post ),
			    get_the_title( $post ),
			    $this->getPostEditUrl( $post ),
			    $author->display_name,
			    $author->user_email,
			    get_site_url()
		    );

		    //  Perform replacement

		    $string = str_replace( $contentPattern, $contentReplace, $string );

	    }

	    return $string;

    }

	/**
	 * @param WP_Post $post
	 *
	 * @return string|null
	 */
    public function getPostEditUrl( $post ) {

    	$postTypeObj = get_post_type_object( get_post_type( $post ) );

    	if( $postTypeObj ){

    		$url = admin_url( sprintf( $postTypeObj->_edit_link, $post->ID ) );
    		$url = add_query_arg( array( 'action' => 'edit' ), $url );

    		return $url;

	    }

    	return null;

    }

    /**
     * Returns random post.
     *
     * @return WP_Post|null
     */
    public function getRandomPost() {

        $query = new WP_Query(
            array(
                'post_type'         =>  'post',
                'orderby'           =>  'rand',
                'posts_per_page'    =>  '1'
            )
        );

        $posts = $query->get_posts();

        //  Return only first item from array or null if array is empty.

        return ! empty( $posts ) ? $posts[0] : null;

    }

	/**
	 * Checks if current page is edited with gutenberg.
	 *
	 * @return bool
	 */
    public function isCurrentViewGutenberg() {

	    if ( get_current_screen()->is_block_editor() ) {
		    return true;
	    }

	    if ( function_exists( 'is_gutenberg_page' ) ) {
		    return is_gutenberg_page();
	    }

	    return false;

    }

	/**
	 * Checks if post status MAY be considered as revision draft.
	 * It does not check if post is actually a revision draft.
	 *
	 * @param WP_POST|int|null $post
	 *
	 * @return bool
	 */
    public function isPostStatusAcceptedForRevision( $post = null ) {

    	if( $post = get_post( $post ) ){

    		$acceptedStatuses = array(
    			'draft',
			    'pending',
			    'future'
		    );

    	    return (bool) in_array( $post->post_status, $acceptedStatuses );

	    } else {

    		return false;

    	}

    }

	/**
	 * @param WP_POST|int|null $post
	 *
	 * @return bool
	 */
    public function isPostTypeAcceptedForRevision( $post = null ) {

    	if( $post = get_post( $post ) ){

    		return (bool) in_array( get_post_type( $post ), App::i()->settings->getChosenPostTypesSlugs() );

	    }

    	return false;

    }

}