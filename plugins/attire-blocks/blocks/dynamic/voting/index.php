<?php

namespace Attire\Blocks\blocks\voting;


use Attire\Blocks\Util;

add_action( 'plugins_loaded', __NAMESPACE__ . '\atbs_register_voting' );

function atbs_register_voting() {

	// Only load if Gutenberg is available.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$attributes = [
		'blockId'              => [
			'type'    => 'string',
			'default' => 'not_set'
		],
		'view_type'            => [
			'type'    => 'string',
			'default' => 'row'
		],
		'upvote_count'         => [
			'type'    => 'boolean',
			'default' => false
		],
		'downvote_count'       => [
			'type'    => 'number',
			'default' => 0
		],
		'hide_downvote_button' => [
			'type'    => 'boolean',
			'default' => 0
		],
		'vote_count_position'  => [
			'type'    => 'string',
			'default' => 'm'
		],
		'icon'                 => [ 'type' => 'array', 'default' => [ 'fas fa-chevron-up', 'fas fa-chevron-down' ] ],
		'icon_size'            => [ 'type' => 'string', 'default' => '1em' ],
		'voted_icon_color'     => [ 'type' => 'string', 'default' => '#06DF2E' ],
		'regular_icon_color'   => [ 'type' => 'string', 'default' => '#78759B' ],
	];
	$attributes = array_merge_recursive( $attributes, Util::getSpacingProps( '', [
		'Margin'  => [ 0, 0, 16, 0 ],
		'Padding' => [ 0, 0, 0, 0 ]
	] ) );
	$attributes = array_merge_recursive( $attributes, Util::getSpacingProps( 'icon', [
		'Margin'  => [ 0, 0, 0, 0 ],
		'Padding' => [ 12, 20, 12, 20 ]
	] ) );


	$attributes = array_merge_recursive( $attributes, Util::getBorderAttributes( '', [
		'BorderWidth'  => 1,
		'BorderColor'  => '#D4D4D4',
		'BorderRadius' => 5
	] ) );
	$attributes = array_merge_recursive( $attributes, Util::getTypographyProps( '', [ 'FontSize'   => 16,
	                                                                                  'FontWeight' => 600,
	                                                                                  'TextColor'  => '#1E2A5F'
	] ) );

	// Hook server side rendering into render callback
	register_block_type( 'attire-blocks/voting',
		array(
			'render_callback' => __NAMESPACE__ . '\atbs_render_voting',
			'attributes'      => $attributes,
		)
	);
}

function atbs_render_voting( $attributes, $content ) {
	$nonce = wp_create_nonce( 'wp_rest' );

	$current_vote = get_user_meta( get_current_user_id(), 'atbs_voting_' . $attributes['blockID'], true );
	$upvotes      = (int) maybe_unserialize( get_post_meta( get_the_ID(), 'atbs_upvotes_' . $attributes['blockID'], true ) );
	$downvotes    = (int) maybe_unserialize( get_post_meta( get_the_ID(), 'atbs_downvotes_' . $attributes['blockID'], true ) );
	$html         = '<div class="atbs-voting atbs-voting-' . $attributes['blockID'] . '">
	<style>
	' . viewTypeCss( $attributes ) . '
	' . iconCss( $attributes ) . '
	' . borderCss( $attributes ) . '
	' . typographyCss( $attributes ) . '
	.atbs-voting-' . $attributes['blockID'] . ' .vote-button{' . Util::getSpacingStyles( $attributes, 'icon' ) . '}
	.atbs-voting-' . $attributes['blockID'] . '{' . Util::getSpacingStyles( $attributes ) . '}
	</style>
	<input type="hidden" class="atbs_voting_nonce" value="' . $nonce . '" />
	' . ( $attributes['vote_count_position'] === 'l' ? '<span class="vote-count">' . ( $upvotes - $downvotes ) . '</span>' : '' ) . '
	<i  class="atbs-upvote vote-button  ' . $attributes['icon'][0] . ' ' . ( $current_vote === 'upvote' ? ' active' : ' ' ) . '" data-voteid="' . $attributes['blockID'] . '" data-postid="' . get_the_ID() . '"></i>
	' . ( $attributes['vote_count_position'] === 'm' ? '<span class="vote-count">' . ( $upvotes - $downvotes ) . '</span>' : '' ) . '
	' . ( $attributes['hide_downvote_button'] !== true ? '	<i  class="atbs-downvote vote-button ' . $attributes['icon'][1] . ' ' . ( $current_vote === 'downvote' ? ' active' : ' ' ) . '" data-voteid="' . $attributes['blockID'] . '" data-postid="' . get_the_ID() . '" ></i>' : '' ) . '
	' . ( $attributes['vote_count_position'] === 'r' ? '<span class="vote-count">' . ( $upvotes - $downvotes ) . '</span>' : '' ) . '
	</div>'; //End wrapper

	return $html;
}

function iconCss( $attributes ) {
	$style = '.atbs-voting-' . $attributes['blockID'] . ' .atbs-upvote,.atbs-voting-' . $attributes['blockID'] . ' .atbs-downvote,.atbs-voting-' . $attributes['blockID'] . ' .vote-count{';
	$style .= 'font-size: ' . $attributes['icon_size'] . ';';
	$style .= '}';
	$style .= '.atbs-voting-' . $attributes['blockID'] . ' .vote-button{';
	$style .= 'color: ' . $attributes['regular_icon_color'] . ';';
	$style .= '}';
	$style .= '.atbs-voting-' . $attributes['blockID'] . ' .vote-button.active{';
	$style .= 'color: ' . $attributes['voted_icon_color'] . ';';
	$style .= '}';

	return $style;
}

function borderCss( $attributes ) {
	$style = '.atbs-voting-' . $attributes['blockID'] . '{';
	$style .= Util::get_border_css( $attributes );
	$style .= '}';

	return $style;
}

function typographyCss( $attributes ) {
	$style = '.atbs-voting-' . $attributes['blockID'] . ' .vote-count{';
	$style .= Util::typographyCss( $attributes );
	$style .= '}';

	return $style;
}

function viewTypeCss( $attributes ) {
	$style = '.atbs-voting-' . $attributes['blockID'] . '{';
	$style .= 'flex-direction:' . $attributes['view_type'];
	$style .= '}';

	return $style;
}
