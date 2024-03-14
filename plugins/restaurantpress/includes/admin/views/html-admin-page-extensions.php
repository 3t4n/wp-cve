<?php
/**
 * Admin View: Page - Extensions
 *
 * @var string $view
 * @var object $extensions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap restaurantpress rp_extensions_wrap">
	<nav class="nav-tab-wrapper rp-nav-tab-wrapper">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=rp-extensions' ) ); ?>" class="nav-tab nav-tab-active"><?php _e( 'Browse Extensions', 'restaurantpress' ); ?></a>
	</nav>

	<h1 class="screen-reader-text"><?php _e( 'RestaurantPress Extensions', 'restaurantpress' ); ?></h1>

	<?php if ( $sections ) : ?>
		<ul class="subsubsub">
			<?php foreach ( $sections as $section_id => $section ) : ?>
				<li><a class="<?php echo $current_section === $section_id ? 'current' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=rp-extensions&section=' . esc_attr( $section_id ) ); ?>"><?php echo esc_html( $section->title ); ?></a><?php echo ( end( $section_keys ) !== $section_id ) ? ' |' : ''; ?></li>
			<?php endforeach; ?>
		</ul>
		<br class="clear" />
		<?php if ( $extensions = RP_Admin_Extensions::get_section_data( $current_section ) ) : ?>
			<ul class="products">
			<?php foreach ( $extensions as $extension ) : ?>
				<li class="product">
					<a href="<?php echo esc_attr( $extension->link ); ?>">
						<?php if ( ! empty( $extension->image ) ) : ?>
							<span class="product-image"><img src="<?php echo esc_attr( $extension->image ); ?>"/></span>
						<?php else : ?>
							<h2><?php echo esc_html( $extension->title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $extension->price ) ) : ?>
							<span class="price"><?php wp_kses_post( $extension->price ); ?></span>
						<?php endif; ?>
						<p><?php echo wp_kses_post( $extension->excerpt ); ?></p>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php else : ?>
		<p><?php printf( __( 'Our catalog of RestaurantPress Extensions can be found on WPEverest.com here: <a href="%s">RestaurantPress Extensions Catalog</a>', 'restaurantpress' ), 'https://wpeverest.com/restaurantpress-extensions/' ); ?></p>
	<?php endif; ?>

	<?php if ( 'FoodHunt' !== $theme['Name'] && 'featured' !== $current_section ) : ?>
		<div class="foodhunt">
			<a href="<?php echo esc_url( 'https://themegrill.com/themes/foodhunt/' ); ?>" target="_blank"><img src="<?php echo RP()->plugin_url(); ?>/assets/images/foodhunt.jpg" alt="FoodHunt" /></a>
			<h2><?php _e( 'Looking for a RestaurantPress theme?', 'restaurantpress' ); ?></h2>
			<p><?php _e( 'We recommend FoodHunt, the <em>official</em> RestaurantPress theme.', 'restaurantpress' ); ?></p>
			<p><?php _e( 'FoodHunt is an intuitive, flexible and <strong>free</strong> WordPress theme offering deep integration with RestaurantPress and many of the most popular customer-facing extensions.', 'restaurantpress' ); ?></p>
			<p>
				<a href="https://themegrill.com/themes/foodhunt/" target="_blank" class="button"><?php _e( 'Read all about it', 'restaurantpress' ) ?></a>
				<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-theme&theme=foodhunt' ), 'install-theme_foodhunt' ) ); ?>" class="button button-primary"><?php _e( 'Download &amp; install', 'restaurantpress' ); ?></a>
			</p>
		</div>
	<?php endif; ?>
</div>
