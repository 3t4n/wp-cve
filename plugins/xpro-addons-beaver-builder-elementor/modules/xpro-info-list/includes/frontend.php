<ul class="xpro-infolist-wrapper xpro-infolist-layout-<?php echo esc_attr( $settings->layout ); ?>">
	<?php
    global $wp_embed;
	$list_items_count = count( $settings->list_items );
	for ( $i = 0; $i < $list_items_count; $i++ ) {
		$item = $settings->list_items[ $i ];
		?>
		<li class="xpro-infolist-item xproinfo-repeater-item-<?php echo esc_attr( $i ); ?>">
			<?php
			$target   = $item->link_target ? ' target=_blank' : '';
			$nofollow = $item->link_nofollow ? ' rel=nofollow' : '';
			echo ( $item->link ) ? '<a href="' . esc_url( $item->link ) . '" ' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' : '';
			?>
			<?php if ( 'none' !== $item->media_type ) : ?>
				<div class="xpro-infolist-media xpro-infolist-media-type-<?php echo esc_attr( $item->media_type ); ?>">
					<?php
					if ( 'icon' === $item->media_type && $item->icon ) {
						?>
						<i class="<?php echo esc_attr( $item->icon ); ?>" aria-hidden="true"></i>
						<?php
					}
					if ( 'image' === $item->media_type && $item->image ) {
						?>
						<img class="" src="<?php echo esc_url( $item->image_src ); ?>" alt="Image not found">
						<?php
					}

					if ( 'custom' === $item->media_type && $item->custom ) {
						echo '<i class="xpro-infolist-custom">' . esc_attr( $item->custom ) . '</i>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="xpro-infolist-content">
				<?php if ( $item->title ) : ?>
					<h3 class="xpro-infolist-title"><?php echo esc_attr( $item->title ); ?></h3>
				<?php endif; ?>
				<?php if ( $item->description ) : ?>
					<p class="xpro-infolist-desc"><?php echo wpautop( $wp_embed->autoembed( $item->description ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo ( $item->link ) ? '</a>' : ''; ?>
		</li>
	<?php } ?>
</ul>
