<?php

extract( shortcode_atts( $shortcode_atts, $atts ) );

$column_gap = wpb_otm_get_post_meta( '_wpb_team_members_options', 'column_gap',  get_the_id() );
$column     = 12/$column;
$column     = ' wpb_otm_col-md-'.esc_attr( $column ).' wpb_otm_col-sm-6 ';

if ( $wp_query->have_posts() ):
	?>
		<div class="<?php echo esc_attr( implode( ' ', $slider_classes) ).' wpb_otm_row'; ?>">

			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
				<div <?php post_class( array( 'wpb-team-'.$type.'-item', $column ) ); ?>>

					<?php
						wpb_otm_get_template( 'skin-' . $skin . '.php', array(
					        'type'  		=> $type,
					        'excerpt_length' => $excerpt_length
					    ) );
					?>

				</div>

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		</div>
	<?php
else:
	printf( '<div class="wpb-otm-no-post-found">%s</div>', esc_html__( 'No post found', 'our-team-members' ) );
endif;



