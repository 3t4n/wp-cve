<?php
$hover_animation = ( '2d-transition' === $settings['social_share_hover_animation'] ) ? 'xpro-button-2d-animation ' . $settings['social_share_hover_2d_css_animation'] : ( ( 'background-transition' === $settings['social_share_hover_animation'] ) ? 'xpro-button-bg-animation ' . $settings['social_share_hover_background_css_animation'] : ( ( 'hover-effect' === $settings['social_share_hover_animation'] ) ? 'xpro-unique-' . $settings['social_share_hover_effect_animation'] : 'xpro-elementor-button-animation-none' ) );
?>

<?php
$settings     = $this->get_settings_for_display();
$social_icons = $settings['social_share_item'];
?>

<!-- Social Icon -->
<ul class="xpro-social-share-wrapper">
	<?php
	foreach ( $social_icons as $icon ) :
		$social_media_name  = $icon['social_share_network'];
		$custom_share_title = ( $icon['social_share_title_enable'] ) ? esc_html( $icon['share_custom_title'] ) : '';
		$share_text         = esc_html( $icon['share_title'] );
		$default_share_text = ucfirst( $social_media_name );
		$image              = ( 'pinterest' === $social_media_name ) ? $icon['social_share_image']['url'] : '';
		$twitter_handle     = $icon['social_twitter_handle'];
		$email_to           = $icon['social_email_to'];
		$email_subject      = $icon['social_email_subject'];

		$share_on_text = $share_text ? $share_text : $default_share_text;

		$hashtags = $icon['social_hashtags'];
		$url      = get_the_permalink();

		$custom_share_url = $icon['link']['url'];
		$share_url        = $custom_share_url ? $custom_share_url : $url;

		$this->set_render_attribute(
			'list_classes',
			'class',
			array(
				'xpro-social-share-inner',
				'elementor-repeater-item-' . $icon['_id'],
			)
		);

		$this->set_render_attribute(
			'link_classes',
			'class',
			array(
				'sharer',
				'xpro-social-share',
				'xpro-share-network',
				'elementor-social-icon-' . esc_attr( $social_media_name ),
				$hover_animation,
			)
		);

		$this->set_render_attribute( 'link_classes', 'data-sharer', esc_attr( $social_media_name ) );
		$this->set_render_attribute( 'link_classes', 'data-url', $share_url );
		$this->set_render_attribute( 'link_classes', 'data-hashtags', $hashtags ? esc_html( $hashtags ) : '' );
		$this->set_render_attribute( 'link_classes', 'data-title', $custom_share_title );
		$this->set_render_attribute( 'link_classes', 'data-image', esc_url( $image ) );
		$this->set_render_attribute( 'link_classes', 'data-to', esc_attr( $email_to ) );
		$this->set_render_attribute( 'link_classes', 'data-subject', esc_attr( $email_subject ) );
		?>
		<li <?php $this->print_render_attribute_string( 'list_classes' ); ?>>
			<a <?php $this->print_render_attribute_string( 'link_classes' ); ?>>

				<!-- icon -->
				<?php
				if ( 'email' === $social_media_name ) {
					( ! empty( 'email' === $social_media_name ) ) ? $email_env = 'fa-envelope' : $email_env = '';
					?>
					<i class=" fas <?php echo esc_attr( $email_env ); ?> fa-<?php echo esc_attr( $social_media_name ); ?>" aria-hidden="true"></i>
					<?php
				} else {
					?>
					<i class=" fab fa-<?php echo esc_attr( ( 'facebook' === $social_media_name ) ? $social_media_name . '-f' : $social_media_name ); ?>" aria-hidden="true"></i>
					<?php
				}
				?>
				<!-- title -->
				<?php
				if ( 'yes' === $icon['social_share_title_enable'] && ! empty( $share_on_text ) ) {
					printf( "<span class='xpro-social-share-title'>%s</span>", esc_html( $share_on_text ) );
				}
				?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
