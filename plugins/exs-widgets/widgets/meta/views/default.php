<?php
/**
 * Widget Theme meta view file
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// if our theme is not active - return
if ( ! function_exists( 'exs_get_theme_meta' ) ) {
	return;
}

//meta
$exs_meta = exs_get_theme_meta();

$exs_center_class = ( ! empty( $exs_text_center ) ) ? ' text-center' : '';

echo wp_kses_post( $exs_args['before_widget'] );
echo '<div class="widget-theme-meta-default ' . esc_attr( $exs_css_class . $exs_center_class ) . '">';
if ( $exs_title ) {
	echo wp_kses_post( $exs_args['before_title'] . $exs_title . $exs_args['after_title'] );
}
if ( $exs_image_uri ) {
	echo '<div class="theme-meta-img wp-block-image"><img src="' . esc_url( $exs_image_uri ) . '" alt="' . esc_attr( $exs_title ) . '"></div>';
}
if ( $exs_sub_title ) {
	echo '<p class="sub-title">' . wp_kses_post( exs_get_copyright_text( $exs_sub_title ) ) . '</p><!-- .sub-title-->';
}

if (
! empty( $exs_meta )
) :
	?>
	<div class="theme-meta">
		<?php if ( ! empty( $exs_meta['phone'] ) && ! empty( $exs_show_phone ) ) : ?>
			<span class="icon-inline">
			<?php function_exists( 'exs_icon' ) ? exs_icon( 'phone-outline' ) : ''; ?>
				<span>
					<?php if ( ! empty( $exs_meta['phone_label'] ) ) : ?>
						<strong><?php echo wp_kses_post( $exs_meta['phone_label'] ); ?></strong>
					<?php endif; ?>
					<span><?php echo wp_kses_post( $exs_meta['phone'] ); ?></span>
				</span>
			</span>
			<?php
		endif; //phone
		if ( ! empty( $exs_meta['email'] ) && ! empty( $exs_show_email ) ) :
			?>
			<span class="icon-inline">
			<?php function_exists( 'exs_icon' ) ? exs_icon( 'email-outline' ) : ''; ?>
				<span>
					<?php if ( ! empty( $exs_meta['email_label'] ) ) : ?>
						<strong><?php echo wp_kses_post( $exs_meta['email_label'] ); ?></strong>
					<?php endif; ?>
					<a href="mailto:<?php echo esc_attr( $exs_meta['email'] ); ?>"><?php echo wp_kses_post( $exs_meta['email'] ); ?></a>
				</span>
			</span>
			<?php
		endif; //email
		if ( ! empty( $exs_meta['address'] ) && ! empty( $exs_show_address ) ) :
			?>
			<span class="icon-inline">
			<?php function_exists( 'exs_icon' ) ? exs_icon( 'map-marker-outline' ) : ''; ?>
				<span>
					<?php if ( ! empty( $exs_meta['address_label'] ) ) : ?>
						<strong><?php echo wp_kses_post( $exs_meta['address_label'] ); ?></strong>
					<?php endif; ?>
					<span><?php echo wp_kses_post( $exs_meta['address'] ); ?></span>
				</span>
			</span>
			<?php
		endif; //address
		if ( ! empty( $exs_meta['opening_hours'] ) && ! empty( $exs_show_opening_hours ) ) :
			?>
			<span class="icon-inline">
			<?php function_exists( 'exs_icon' ) ? exs_icon( 'clock-outline' ) : ''; ?>
				<span>
					<?php if ( ! empty( $exs_meta['opening_hours_label'] ) ) : ?>
						<strong><?php echo wp_kses_post( $exs_meta['opening_hours_label'] ); ?></strong>
					<?php endif; ?>
					<span><?php echo wp_kses_post( $exs_meta['opening_hours'] ); ?></span>
				</span>
			</span>
			<?php
		endif; //opening_hours
		?>
	</div><!-- .theme-meta -->
	<?php
endif; //! empty meta
if ( ! empty( $exs_show_social_links ) && function_exists( 'exs_social_links' ) ) :
	?>
	<?php exs_social_links(); ?>
<?php endif; //social_links ?>
	</div><!-- .widget-theme-meta-default -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );
