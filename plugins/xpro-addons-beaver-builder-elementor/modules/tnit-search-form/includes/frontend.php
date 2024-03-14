<?php
/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 */

?>
<?php if ( 'style-1' === $settings->search_layout ) { ?>
<div class="tnit-searchbar-outer tnit-space-increase tnit-PurpleColor">
	<form  class="tnit-form-search" role="search" action="<?php echo esc_url( home_url() ); ?>" method="get">
		<div class="tnit-outer-border">
			<input type="text" name="s" class="input-field" id="search" value="<?php the_search_query(); ?>" placeholder="<?php echo esc_attr( $settings->placeholder ); ?>" />
			<button class="btn-submit">
			<?php if ( 'icon' === $settings->button_type ) : ?>
				<i class="<?php echo esc_attr( $settings->btn_icon ); ?>" aria-hidden="true"></i>
			<?php elseif ( 'text' === $settings->button_type ) : ?>
				<?php echo esc_html_e( $settings->btn_text ); ?>
			<?php endif; ?>
			</button>
		</div>
	</form>
</div><!--SearchBar Outer End-->
<?php } ?>

<?php if ( 'style-2' === $settings->search_layout ) { ?>
<div class="tnit-search-box">
	<a href="#" id="tnit-trigger-btn">
		<?php if ( 'icon' === $settings->button_type ) : ?>
			<i class="<?php echo esc_attr( $settings->btn_icon ); ?>" aria-hidden="true"></i>
		<?php elseif ( 'text' === $settings->button_type ) : ?>
			<?php echo esc_html_e( $settings->btn_text ); ?>
		<?php endif; ?>
	</a>
	<!-- Search Form Outer Start-->
	<form class="tnit-search-animated-form tnit-GreenColor" role="search" action="<?php echo esc_url( home_url() ); ?>" method="get">
		<input type="text" name="s" class="input-field"  id="search" value="<?php the_search_query(); ?>" placeholder="<?php echo esc_attr( $settings->placeholder ); ?>" />
		<button class="btn-submit" type="submit">
			<?php if ( 'icon' === $settings->button_type ) : ?>
				<i class="<?php echo esc_attr( $settings->btn_icon ); ?>" aria-hidden="true"></i>
			<?php elseif ( 'text' === $settings->button_type ) : ?>
				<?php echo esc_html_e( $settings->btn_text ); ?>
			<?php endif; ?>
		</button>
	</form><!-- Search Form Outer End-->
</div>
<?php } ?>


<?php if ( 'style-3' === $settings->search_layout ) { ?>
<!--Search Button Start-->
<div class="toggle-button">
	<a href="#" id="trigger-tnit-search"><i class="<?php echo esc_attr( $settings->toggle_btn_icon ); ?>" aria-hidden="true"></i></a>
</div>
<!--Search Button End-->
<!--Search Overlay Outer Start-->
<div class="tnit-search-outer">
	<div class="xpro-grid">
	<div class="xpro-item-lg-4 xpro-item-md-4 xpro-offset-lg-4 xpro-offset-md-4">
		<!--SearchBar Holder Start-->
		<div class="tnit-searchbar-outer tnit-space-increase tnit-PurpleColor tnit-form-radius tnit-RedColor">
		<form class="tnit-form-search" role="search" action="<?php echo esc_url( home_url() ); ?>" method="get">
			<div class="tnit-outer-border">
			<input type="text" name="s" class="input-field" id="search" value="<?php the_search_query(); ?>" placeholder="<?php echo esc_attr( $settings->placeholder ); ?>" />
			<button class="btn-submit">
				<?php if ( 'icon' === $settings->button_type ) : ?>
					<i class="<?php echo esc_attr( $settings->btn_icon ); ?>" aria-hidden="true"></i>
				<?php elseif ( 'text' === $settings->button_type ) : ?>
					<?php echo esc_html_e( $settings->btn_text ); ?>
				<?php endif; ?>
			</button>
			</div>
		</form>
		</div><!--SearchBar Outer End-->
	</div>
	</div>
	<a href="#" id="tnit-btn-close"><i class="fa fa-times" aria-hidden="true"></i></a>
</div><!--Search Overlay Outer End-->
<?php } ?>
