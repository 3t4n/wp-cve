<?php
/**
 * Switcher template
 *
 * @package DemoBar
 */

$demobar_options = get_option( 'demobar_options' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-M38S568');</script>
	<!-- End Google Tag Manager -->
	<?php wp_head(); ?>
</head>
<?php
	$current_demo = '';
	$sites = DemoBar_Switcher::get_sites();
	$site_param = ( isset( $_REQUEST['demo'] ) ) ? sanitize_key( $_REQUEST['demo'] ) : '';
	$valid_key = false;
if ( ! empty( $sites ) ) {
	$valid_key = demobar_array_find_by_key( $sites, 'slug', $site_param );
	if ( false === $valid_key ) {
		$first_element = reset( $sites );
		$valid_key = $first_element['ID'];
	}
}
if ( false !== $valid_key ) {
	$current_demo = $valid_key;
}
?>
<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M38S568"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div id="db-switcher">
		<?php if ( isset( $demobar_options['logo'] ) && ! empty( $demobar_options['logo'] ) ) : ?>
			<div id="branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $demobar_options['logo'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
			</div><!-- #branding -->
		<?php endif ?>
		<div id="dropdown">
			<?php
			$selected = esc_html( 'Select', 'demo-bar' );
			$dropdown_list = '';
			?>
			<?php if ( ! empty( $sites ) ) : ?>
				<?php $dropdown_list .= '<ul>'; ?>
				<?php foreach ( $sites as $site ) : ?>
					<?php
						$link = add_query_arg(
							array(
								'demo' => esc_attr( $site['slug'] ),
							),
							get_permalink()
						);

						if ( isset( $_GET['demo'] ) && $_GET['demo'] === $site['slug'] ) {
							$selected = esc_html( $site['title'] );
						}

						$dropdown_list .= '<li>';
						$dropdown_list .= sprintf( '<a href="%s">%s</a>', esc_url( $link ), esc_html( $site['title'] ) );
						$dropdown_list .= '</li>';
					?>
				<?php endforeach; ?>
				<?php $dropdown_list .= '</ul>'; ?>
			<?php endif ?>

			<?php
			// Display select box dropdown.
			echo wp_kses_post( $selected . $dropdown_list ); ?>

		</div> <!-- #dropdown -->

		<?php if ( isset( $demobar_options['show_responsive_button'] ) && true === $demobar_options['show_responsive_button'] ) :  ?>
		<div id="responsive">
			<a title="<?php esc_html_e( 'Desktop', 'demo-bar' ); ?>" rel="resp-desktop" href="#" class="current"><i class="fa fa-desktop fa-lg"></i></a>
			<a title="<?php esc_html_e( 'Tablet Landscape (1024x768)', 'demo-bar' ); ?>" rel="resp-tablet-landscape" href="#"><i class="fa fa-tablet fa-rotate-270 fa-lg"></i></a>
			<a title="<?php esc_html_e( 'Tablet Portrait (768x1024)', 'demo-bar' ); ?>" rel="resp-tablet-portrait" href="#"><i class="fa fa-tablet fa-lg"></i></a>
			<a title="<?php esc_html_e( 'Mobile Landscape (480x320)', 'demo-bar' ); ?>" rel="resp-mobile-landscape" href="#"><i class="fa fa-mobile fa-rotate-270 fa-lg"></i></a>
			<a title="<?php esc_html_e( 'Mobile Portrait (320x480)', 'demo-bar' ); ?>" rel="resp-mobile-portrait" href="#"><i class="fa fa-mobile fa-lg"></i></a>
		</div>
		<?php endif; ?>

		<div id="buttons">
			<?php do_action( 'demo_bar_buttons_start' ); ?>
			<?php $download_label = ! empty( $sites[ $current_demo ]['download_label'] ) ? $sites[ $current_demo ]['download_label'] : 'Download WP Travel'; ?>
			<?php if ( isset( $sites[ $current_demo ]['download_url'] ) && ! empty( $sites[ $current_demo ]['download_url'] ) && true === $demobar_options['show_purchase_button'] ) : ?>
				<a href="<?php echo esc_url( $sites[ $current_demo ]['download_url'] ); ?>" target="new" class="btn btn-theme-download"><i class="fa fa-download" aria-hidden="true"></i> <?php echo esc_html( $download_label ); ?></a>
			<?php endif ?>
			<?php if ( ! empty( $sites[ $current_demo ]['purchase_url'] ) && ! empty( $sites[ $current_demo ]['purchase_label'] ) && true === $demobar_options['show_purchase_button'] ) : ?>
				<a href="<?php echo esc_url( $sites[ $current_demo ]['purchase_url'] ); ?>" target="new" class="btn btn-download"><i class="fa fa-download" aria-hidden="true"></i> <?php echo esc_html( $sites[ $current_demo ]['purchase_label'] ); ?></a>
			<?php endif ?>
			<?php if ( isset( $sites[ $current_demo ]['site_url'] ) && ! empty( $sites[ $current_demo ]['site_url'] ) &&  true === $demobar_options['show_close_button'] ) :  ?>
				<a href="<?php echo esc_url( $sites[ $current_demo ]['site_url'] ); ?>" class="btn btn-close"><span class="fa-stack"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-close fa-stack-1x"></i></span></a>
			<?php endif ?>
			<?php do_action( 'demo_bar_buttons_end' ); ?>
		</div><!-- #buttons -->
	</div>
	<?php if ( isset( $sites[ $current_demo ]['site_url'] ) ) :  ?>
		<iframe id="frame-area" src="<?php echo esc_url( $sites[ $current_demo ]['site_url'] ); ?>" frameborder="0" width="100%"></iframe>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>
