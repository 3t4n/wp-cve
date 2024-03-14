<?php

$titleeffect = '';

if ( 'style-1' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect1';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-2' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect2';
	$figca_class = 'tnit-bottomContent-left';
} elseif ( 'style-3' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect3';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-4' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect4';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-5' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect5';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-6' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect6';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-7' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect8';
	$figca_class = 'tnit-middleContent-start';
} elseif ( 'style-8' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect12';
	$figca_class = 'tnit-middleContent';
} elseif ( 'style-9' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect11';
	$figca_class = 'tnit-middleContent-start';
} elseif ( 'style-10' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect7';
	$figca_class = 'tnit-middleContent-start';
} elseif ( 'style-11' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect10';
	$figca_class = 'tnit-bottomContent';
} elseif ( 'style-12' === $settings->hover_card_style ) {
	$fig_class   = 'tnit-card-item_effect9 tnit-card-item_betweenSpace';
	$figca_class = 'tnit-middleContent-start';
	$titleeffect = 'tnit-card-title-effect_v1';
}

?>

<!--Hover Card Item Start-->
<div class="tnit-hover-card-grid">
	<?php
	$hcard_items_count = count( $settings->hcard_form_items );
	for ( $i = 0; $i < $hcard_items_count; $i++ ) {
		$hcard_form_item = $settings->hcard_form_items[ $i ];

		// button classes.
		$button_class  = '';
		$button_class .= ( 'button' === $hcard_form_item->link_type ) ? 'tnit-card-btn_sequare' : '';
		$button_class .= ( 'icon' === $hcard_form_item->link_type ) ? 'tnit-card-btn_arrow' : '';

		// Button attributes.
		$buttonnofollow = ( 'yes' === $hcard_form_item->button_link_nofollow ) ? ' rel=nofollow' : '';
		?>
		<div class="tnit-hover-card-grid-item">
			<figure class="tnit-card-item tnit-card-<?php echo esc_attr( $i ); ?> <?php echo esc_attr( $fig_class ); ?>">

			<?php $module->render_photo( $i ); ?>

				<figcaption class="tnit-card-caption <?php echo esc_attr( $figca_class ); ?>">
					<div class="tnit-card-content">
					<?php if ( 'style-12' === $settings->hover_card_style ) { ?>
						<div class="tnit-card-topContent">
					<?php } ?>
					<?php if ( 'yes' === $hcard_form_item->card_icon ) { ?>
						<span class="tnit-card-icon">
							<i class="<?php echo esc_attr( $hcard_form_item->icon ); ?>" aria-hidden="true"></i>
						</span>
					<?php } ?>
					<?php if ( 'style-11' !== $settings->hover_card_style ) { ?>
						<h3 class="tnit-card-title <?php echo esc_attr( $titleeffect ); ?>">
							<?php echo esc_attr( $hcard_form_item->label ); ?>
						</h3>
					<?php } ?>
					<?php if ( 'style-12' === $settings->hover_card_style ) { ?>
						</div>
					<?php } ?>
					<?php if ( 'style-12' === $settings->hover_card_style ) { ?>
						<div class="tnit-card-bottomContent">
					<?php } ?>
						<div class="tnit-card-description">
							<p><?php echo esc_attr( $hcard_form_item->description ); ?></p>
						</div>
					<?php if ( 'style-11' === $settings->hover_card_style ) { ?>
						<span class="tnit-card-linebar"></span>
						<h3 class="tnit-card-title">
							<?php echo esc_attr( $hcard_form_item->label ); ?>
						</h3>
					<?php } ?>
					<?php if ( 'button' === $hcard_form_item->link_type || 'icon' === $hcard_form_item->link_type ) { ?>
						<a href="<?php echo esc_url( $hcard_form_item->button_link ); ?>" target="<?php echo esc_attr( $hcard_form_item->button_link_target ); ?>"<?php echo esc_attr( $buttonnofollow ); ?> class="tnit-card-btn <?php echo esc_attr( $button_class ); ?>">
							<?php
							if ( 'button' === $hcard_form_item->link_type ) {
								echo esc_attr( $hcard_form_item->cta_text );
							} elseif ( 'icon' === $hcard_form_item->link_type ) {
								?>
								<i class="<?php echo esc_attr( $hcard_form_item->cta_icon ); ?>" aria-hidden="true"></i>
							<?php } ?>
						</a>
					<?php } ?>
					<?php if ( 'style-12' === $settings->hover_card_style ) { ?>
						</div>
					<?php } ?>
					</div>
				</figcaption>
			</figure><!--Hover Card Item End-->
		</div>
	<?php } ?>
</div>
