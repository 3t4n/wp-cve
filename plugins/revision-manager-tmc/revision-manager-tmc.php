<?php
/*
Plugin Name:    Revision Manager TMC
Description:    Clone your post, page or custom post type to a draft. Draft up revisions of live, published content.
Tags:           revisions, revisionize, admin, wiki, accept, revision, revisionary, revision control, revision, manager, notify, draft manager, authors reviser, admin, post, revisions, permissions, post permit, submit changes
Version:        2.8.18
Author:         JetPlugs.com
Author URI:     https://jetplugs.com
Text Domain:    rm_tmc
License:        GPL-2.0+
License         URI: http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:    /languages/
*/

defined( 'ABSPATH' ) or die( 'Access denied. Scripts are not allowed.' );

//  ----------------------------------------
//  Requirements
//  ----------------------------------------

require __DIR__ . '/vendor/autoload.php';   //  Composer

$requirementChecker = new ShellPress_RequirementChecker();

$checkPHP   = $requirementChecker->checkPHPVersion( '5.6', 'Revision manager TMC requires PHP version >= 5.6' );
$checkWP    = $requirementChecker->checkWPVersion( '5.3', 'Revision manager TMC requires WP version >= 5.3.0' );

if( ! $checkPHP || ! $checkWP ) return;

use tmc\revisionmanager\src\App;    //  Namespace import.

//  ----------------------------------------
//  ShellPress
//  ----------------------------------------

App::initShellPress( __FILE__, 'rm_tmc', '2.8.18', 'plugin' );

//  ----------------------------------------
//  Public methods
//  ----------------------------------------

/**
 * Creates new clone and connects two posts together.
 * Returns ID of clone or WP_Error containing error message.
 *
 * @param WP_Post|int $post
 *
 * @return int|WP_Error
 */
function rm_tmc_createRevision( $post, $postArgs = array() ){
	
	if( !($post = get_post( $post )) ) return new WP_Error( 'error', "Could not find given post." );
	
	$result = App::i()->utilities->copyPost( $post->ID, null, $postArgs );
	
	if( $result ){
		
		App::i()->utilities->copyMeta( $post->ID, (int) $result );
		App::i()->utilities->copyTaxonomies( $post->ID, (int) $result );
		
		App::i()->revisions->setLinkedPostId( $post->ID, (int) $result );
		App::i()->revisions->setLinkedPostId( (int) $result, $post->ID );
		
		return (int) $result;
		
	} else {
		
		return new WP_Error( 'error', "Could not create a copy." );
		
	}
	
}

/**
 * @param WP_Post|int $revisionPostToAccept
 *
 * @return bool|WP_Error
 */
function rm_tmc_acceptRevision( $revisionPostToAccept ){
	
	if( !($post = get_post( $revisionPostToAccept )) )                          return new WP_Error( 'error', "Could not find given post." );
	if( !($originalPostId = App::i()->revisions->getLinkedPostId( $post )) )    return new WP_Error( 'error', "Could not find original post." );
	
	App::i()->revisions->mergeTwoPosts( $originalPostId, $post->ID );
	
	if( !($resultOfDelete = wp_delete_post( $post, true )) ){
		return new WP_Error( 'error', "There was a problem while clearing the revision from database." );
	}
	
	App::i()->revisions->removeLinkedPostId( $originalPostId );
	
	return true;
	
}