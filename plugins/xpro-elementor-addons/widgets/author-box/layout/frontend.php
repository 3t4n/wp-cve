<?php
$author          = array();
$link_tag        = 'div';
$link_url        = '';
$link_target     = '';
$author_name_tag = $settings['author_name_tag'];

$custom_src = ( 'custom' === $settings['source'] );

if ( 'current' === $settings['source'] ) {
	global $post;
	$avatar_args['size']    = 300;
	$user_id                = $post->post_author;
	$author['avatar']       = get_avatar_url( $user_id, $avatar_args );
	$author['display_name'] = get_the_author_meta( 'display_name', $user_id );
	$author['website']      = get_the_author_meta( 'user_url', $user_id );
	$author['bio']          = get_the_author_meta( 'description', $user_id );
	$author['posts_url']    = get_author_posts_url( $user_id );
} elseif ( $custom_src ) {
	if ( ! empty( $settings['author_avatar']['url'] ) ) {
		$avatar_src = $settings['author_avatar']['url'];

		if ( $settings['author_avatar']['id'] ) {
			$attachment_image_src = wp_get_attachment_image_src( $settings['author_avatar']['id'], 'medium' );

			if ( ! empty( $attachment_image_src[0] ) ) {
				$avatar_src = $attachment_image_src[0];
			}
		}

		$author['avatar'] = $avatar_src;
	}

	$author['display_name'] = $settings['author_name'];
	$author['website']      = $settings['author_website']['url'];
	$author['bio']          = wpautop( $settings['author_bio'] );
	$author['posts_url']    = ( 'yes' === $settings['show_link'] ) ? $settings['posts_url']['url'] : '';
}

$print_avatar = ( ( ! $custom_src && 'yes' === $settings['show_avatar'] ) || ( $custom_src && ! empty( $author['avatar'] ) ) );
$print_name   = ( ( ! $custom_src && 'yes' === $settings['show_name'] ) || ( $custom_src && ! empty( $author['display_name'] ) ) );
$print_bio    = ( ( ! $custom_src && 'yes' === $settings['show_biography'] ) || ( $custom_src && ! empty( $author['bio'] ) ) );
$print_link   = ( ( ! $custom_src && 'yes' === $settings['show_link'] ) && ! empty( $settings['link_text'] ) || ( $custom_src && ! empty( $author['posts_url'] ) && ! empty( $settings['link_text'] ) ) );

if ( ! empty( $settings['link_to'] ) || $custom_src ) {
	if ( ( $custom_src || 'website' === $settings['link_to'] ) && ! empty( $author['website'] ) ) {
		$link_tag = 'a';
		$link_url = $author['website'];

		if ( $custom_src ) {
			$link_target = $settings['author_website']['is_external'] ? '_blank' : '';
		} else {
			$link_target = '_blank';
		}

		if ( $settings['author_website']['custom_attributes'] ) {
			$attributes = explode( ',', $settings['author_website']['custom_attributes'] );

			foreach ( $attributes as $attribute ) {
				if ( ! empty( $attribute ) ) {
					$custom_attr = explode( '|', $attribute, 2 );
					if ( ! isset( $custom_attr[1] ) ) {
						$custom_attr[1] = '';
					}
					$this->add_render_attribute( 'author_link', $custom_attr[0], $custom_attr[1] );
				}
			}
		}
	} elseif ( 'posts_archive' === $settings['link_to'] && ! empty( $author['posts_url'] ) ) {
		$link_tag = 'a';
		$link_url = $author['posts_url'];
	}

	if ( ! empty( $link_url ) ) {
		$this->add_render_attribute( 'author_link', 'href', $link_url );

		if ( ! empty( $link_target ) ) {
			$this->add_render_attribute( 'author_link', 'target', $link_target );
		}
	}
}

$this->add_render_attribute(
	'button',
	'class',
	array( 'xpro-author-box-button' )
);

if ( $print_link ) {
	$this->add_render_attribute( 'button', 'href', $author['posts_url'] );

	if ( $settings['posts_url'] && $settings['posts_url']['is_external'] ) {
		$this->add_render_attribute( 'button', 'target', '_blank' );
	}

	if ( $settings['posts_url'] && $settings['posts_url']['nofollow'] ) {
		$this->add_render_attribute( 'button', 'rel', 'nofollow' );
	}

	if ( $settings['posts_url'] && $settings['posts_url']['custom_attributes'] ) {
		$attributes = explode( ',', $settings['posts_url']['custom_attributes'] );

		foreach ( $attributes as $attribute ) {
			if ( ! empty( $attribute ) ) {
				$custom_attr = explode( '|', $attribute, 2 );
				if ( ! isset( $custom_attr[1] ) ) {
					$custom_attr[1] = '';
				}
				$this->add_render_attribute( 'button', $custom_attr[0], $custom_attr[1] );
			}
		}
	}
}

if ( $print_avatar ) {
	$this->add_render_attribute( 'avatar', 'src', $author['avatar'] );

	if ( ! empty( $author['display_name'] ) ) {
		$this->add_render_attribute( 'avatar', 'alt', $author['display_name'] );
	}
}

?>
<div class="xpro-author-box">
	<?php if ( $print_avatar ) { ?>
	<<?php echo esc_attr( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?> class="xpro-author-box-avatar">
	<img <?php $this->print_render_attribute_string( 'avatar' ); ?>>
</<?php echo esc_attr( $link_tag ); ?>>
	<?php } ?>

<div class="xpro-author-box-text">
	<?php if ( $print_name ) : ?>
	<<?php echo esc_attr( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?>>
		<?php echo '<' . esc_attr( $author_name_tag ) . ' class="xpro-author-box-name">' . esc_html( $author['display_name'] ) . '</' . esc_attr( $author_name_tag ) . '>'; ?>
</<?php echo esc_attr( $link_tag ); ?>>
	<?php endif; ?>

<?php if ( $print_bio ) : ?>
	<div class="xpro-author-box-bio">
		<?php xpro_elementor_kses( $author['bio'] ); ?>
	</div>
<?php endif; ?>

<?php if ( $print_link ) : ?>
	<a <?php $this->print_render_attribute_string( 'button' ); ?>>
		<?php echo esc_html( $settings['link_text'] ); ?>
	</a>
<?php endif; ?>
</div>
</div>
