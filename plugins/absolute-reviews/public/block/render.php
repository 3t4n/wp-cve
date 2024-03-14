<?php
/**
 * Review block template
 *
 * @var        $attributes - block attributes
 * @var        $options - layout options
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/includes
 */

switch ( $attributes['layout'] ) {
	case 'percentage':
		$total_score = $options['total_score_percentage'];
		break;
	case 'point-5':
		$total_score = $options['total_score_point_5'];
		break;
	case 'point-10':
		$total_score = $options['total_score_point_10'];
		break;
	case 'star':
		$total_score = $options['total_score_star'];
		break;
	default:
		$total_score = 0;
		break;
}

$params = array(
	'variant'            => 'block',
	'type'               => $attributes['layout'],
	'heading'            => $attributes['heading'],
	'desc'               => $attributes['desc'],
	'legend'             => $attributes['legend'],
	'total_label'        => $attributes['total_score_label'],
	'total_score_number' => $total_score,
	'main_scale'         => true,
	'auto_score'         => false,
);

$params['schema_author']        = get_post_meta( get_queried_object_id(), '_abr_review_schema_author', true );
$params['schema_author_custom'] = get_post_meta( get_queried_object_id(), '_abr_review_schema_author_custom', true );

if ( isset( $attributes['schema_author_custom'] ) && $attributes['schema_author_custom'] ) {
	$params['schema_author']        = 'custom';
	$params['schema_author_custom'] = $attributes['schema_author_custom'];
}

echo '<div class="' . esc_attr( $attributes['className'] ) . '" ' . ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ) . '>';

abr_review_display_block( $params );

echo '</div>';
