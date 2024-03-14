<?php $option = $this->setting_options; ?>
<!DOCTYPE html>
<html  dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>
	<?php
	$title            = get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description' );
	if ( ! empty( $site_description ) ) {
		$title .= ' &#124; ' . $site_description;
	}
	echo $title;
	?>
</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->plugin_url . 'public/css/style.css'; ?>">
<link rel="stylesheet" href="<?php echo $this->plugin_url . 'public/font-awesome/css/font-awesome.min.css'; ?>">
<link href='//fonts.googleapis.com/css?family=Signika+Negative:300,400' rel='stylesheet' type='text/css'>

<?php
wp_print_scripts( 'jquery-core' );
do_action( 'mp_script' );
if ( preg_match( '/(?i)msie [1-9]/', $_SERVER['HTTP_USER_AGENT'] ) ) {
	?>
	<script src="<?php echo $this->plugin_url . 'public/js/html5shiv.js'; ?>"></script>
	<script src="<?php echo $this->plugin_url . 'public/js/jquery.placeholder.js'; ?>"></script>
	<script>
		jQuery(document).ready(function($) {
			$('input, textarea').placeholder();
		});
	</script>
	<?php
}
if ( ! empty( $_POST['subscriber_email'] ) ) {
	$file = $this->plugin_dir . 'includes/lib/subscriber_handler.php';
	include $file;
}
$background_image            = $option->get_option( 'mp_background_image', 'mp_basics', '' );
$background_image_deactivate = $option->get_option( 'mp_background_image_deactivate', 'mp_basics', 'off' );
?>
<style type="text/css">
	<?php
	if ( $background_image_deactivate == 'off' ) {
		if ( $background_image ) {
			?>
			body {
				background: url("<?php echo $background_image; ?>") no-repeat fixed center;
				background-size: cover;
			}
			<?php
		} else {
			?>
			body {
				background: url("<?php echo $this->plugin_url . 'public/images/background.jpg'; ?>") no-repeat fixed center;
				background-size: cover;
			}
			<?php
		}
	}
	if ( $option->get_option( 'mp_background_color', 'mp_basics' ) != '#ffffff' ) {
		?>
		body {
			background-color: <?php echo $option->get_option( 'mp_background_color', 'mp_basics', '' ); ?>;
		}
	<?php } ?>
	<?php if ( $option->get_option( 'mp_font_color', 'mp_basics' ) != '#ffffff' ) { ?>
		body {
			color: <?php echo $option->get_option( 'mp_font_color', 'mp_basics', '' ); ?>;
		}
	<?php } ?>
	<?php if ( $option->get_option( 'mp_link_color', 'mp_basics' ) != '#1bbc9b' ) { ?>
		a, a:hover, .copyright a:hover,a:visited  {
			color: <?php echo $option->get_option( 'mp_link_color', 'mp_basics', '' ); ?>;
		}
		.subscribe-button button{
			background-color: <?php echo $option->get_option( 'mp_link_color', 'mp_basics', '' ); ?>
		}
	<?php } ?>

</style>
<?php
if ( $option->get_option( 'mp_css', 'mp_basics' ) ) {
	?>
	<style type="text/css"><?php echo stripslashes( $option->get_option( 'mp_css', 'mp_basics' ) ); ?></style>
	<?php
}
do_action( 'mp_head' );
?>
</head>

<body class="home">
	<div class="page-wrap" id="content-wrap">
		<section id="content">
			<?php
			$logo = $option->get_option( 'mp_logo', 'mp_basics', '' );
			if ( ! empty( $logo ) ) {
				?>
				<div class="logo">
					<img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( $logo ); ?>">
				</div><!-- .logo -->
			<?php } ?>

			<?php
			$title = $option->get_option( 'mp_heading', 'mp_basics', 'Title' );
			if ( ! empty( $title ) ) {
				?>
					<h1 class="site-title"><?php echo $title; ?></a></h1>
				<?php } ?>
			<?php if ( $option->get_option( 'mp_message', 'mp_basics' ) ) { ?>
				<div class="construction-msg">
					<p><?php echo do_shortcode( $option->get_option( 'mp_message', 'mp_basics' ) ); ?></p>
				</div>
			<?php } ?>
			<div class="social-icons clearfix">
				<ul>
					<?php if ( $option->get_option( 'mp_facebook', 'mp_social' ) ) { ?>
						<li class="facebook"><a href="<?php echo esc_url( $option->get_option( 'mp_facebook', 'mp_social' ) ); ?>"><i class="fa fa-facebook"></i></a></li>
					<?php } ?>
					<?php if ( $option->get_option( 'mp_twitter', 'mp_social' ) ) { ?>
						<li class="twitter"><a href="<?php echo esc_url( $option->get_option( 'mp_twitter', 'mp_social' ) ); ?>"><i class="fa fa-twitter"></i></a></li>
					<?php } ?>
					<?php if ( $option->get_option( 'mp_pinterest', 'mp_social' ) ) { ?>
						<li class="google-plus"><a href="<?php echo esc_url( $option->get_option( 'mp_pinterest', 'mp_social' ) ); ?>"><i class="fa fa-pinterest"></i></a></li>
					<?php } ?>
					<?php if ( $option->get_option( 'mp_google', 'mp_social' ) ) { ?>
						<li class="youtube"><a href="<?php echo esc_url( $option->get_option( 'mp_google', 'mp_social' ) ); ?>"><i class="fa fa-google-plus"></i></a></li>
					<?php } ?>
					<?php if ( $option->get_option( 'mp_youtube', 'mp_social' ) ) { ?>
						<li class="pinterest"><a href="<?php echo esc_url( $option->get_option( 'mp_youtube', 'mp_social' ) ); ?>"><i class="fa fa-youtube"></i></a></li>
					<?php } ?>
					<?php if ( $option->get_option( 'mp_linkedin', 'mp_social' ) ) { ?>
						<li class="linkedin"><a href="<?php echo esc_url( $option->get_option( 'mp_linkedin', 'mp_social' ) ); ?>"><i class="fa fa-linkedin"></i></a></li>
					<?php } ?>
				</ul>
			</div><!-- .social-icons -->
		</section>

		<?php if ( $option->get_option( 'mp_subscribe_activate', 'mp_subscribe' ) == 'on' ) { ?>
			<section id="newsletter">
				<div class="wrapper clearfix">
					<div class="mp-one-half">
						<div class="newsletter-content">
							<?php if ( $option->get_option( 'mp_subscribe_title', 'mp_subscribe' ) ) { ?>
								<h2 class="entry-title"><?php echo $option->get_option( 'mp_subscribe_title', 'mp_subscribe' ); ?></h2>
							<?php } ?>
							<?php if ( $option->get_option( 'mp_subscribe_message', 'mp_subscribe' ) ) { ?>
								<p><?php echo do_shortcode( $option->get_option( 'mp_subscribe_message', 'mp_subscribe' ) ); ?></p>
							<?php } ?>
						</div>
					</div>
					<div class="mp-one-half mp-one-half-last subscribe-form">
						<form method="post" action="#newsletter">
								<div class="sign-up">
									<div class="email-field">
										<input type="text" placeholder="<?php _e( 'Enter email to subscribe', 'maintenance-page' ); ?>" class="inputsubs" name="subscriber_email" >
									</div>
									<div class="subscribe-button">
										<button type="submit" value="submit"><?php _e( 'Subscribe', 'maintenance-page' ); ?></button>
									</div>
								</div>
						</form>
					</div>
				</div>
			</section><!-- #newsletter -->
		<?php } ?>

		<footer id="colophon" class="site-footer">
			<?php
			$footer_text = sprintf( __( 'Copyright &copy; <a href="%1$s">%2$s </a>', 'maintenance-page' ), esc_url( home_url() ), get_bloginfo( 'name' ) );
			if ( $option->get_option( 'mp_footer_radio', 'mp_basics' ) == 'yes' ) {
				$footer_text .= sprintf( __( 'Powered by <a href="%s">ThemeGrill</a>', 'maintenance-page' ), esc_url( 'http://themegrill.com' ) );
			}
			?>
			<div class="copyright"><?php echo $footer_text; ?></div>
		</footer>
	</div><!-- #content-wrap -->
<?php do_action( 'mp_footer' ); ?>
</body>
</html>
