<?php 
$_SESSION['splash'] = true;
?>

<!DOCTYPE html>

<html>
	<head>
		<title><?php echo get_bloginfo('name'); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo get_bloginfo('description'); ?>">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo get_bloginfo( 'name' ); ?>" />
		<meta property="og:description" content="<?php echo get_bloginfo( 'description' ); ?>" />
		<meta property="og:url" content="<?php echo get_site_url(); ?>" />
		<meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>" />
		<?php if ( isset( $this->config['enable_bg_img'] ) && $this->config['enable_bg_img'] == 1 ) { ?>
			<meta property="og:image" content="<?php echo $this->n360_option('background_image'); ?>" />
		<?php } else { ?>
			<meta property="og:image" content="<?php echo $this->n360_option('background_image'); ?>" /> <!-- dk -->
		<?php } ?>
		<link rel="icon" href="<?php echo get_site_icon_url(); ?>" />
		<style type="text/css">
			html { 
				width: 100%;
			}
			
			body:before, body:after {
				position: relative;
			}
			
			body {
				width: 100%;
				height: 100%;
				margin: 0;
			}
			
			#splash-container {
				background-clip: border-box;
				<?php if ( isset( $this->config['enable_bg_img'] ) && $this->config['enable_bg_img'] == 1 ) { ?>
					background-image: url('<?php echo $this->n360_option('background_image'); ?>'); 
					background-origin: padding-box;
					background-repeat: no-repeat;
					background-size: cover;
					background-position: center;
				<?php } else { ?>
					background-color: <?php echo $this->n360_option('background_color'); ?>;
				<?php } ?>
				display: table;
				margin: 0 auto;
				position: relative;
				text-align: center;
				width: 100%;
			}

			@keyframes fadeinout {
				<?php $this->n360_set_keyframes(); ?>
			}

			/* Firefox < 16 */
			@-moz-keyframes fadeinout {
				<?php $this->n360_set_keyframes(); ?>
			}

			/* Safari, Chrome and Opera > 12.1 */
			@-webkit-keyframes fadeinout {
				<?php $this->n360_set_keyframes(); ?>
			}

			/* Internet Explorer */
			@-ms-keyframes fadeinout {
				<?php $this->n360_set_keyframes(); ?>
			}

			#splash-content {
				display: table-cell;
				vertical-align: middle;
    			height: auto;
    			margin: 0 auto;
				position: relative;
			}

			#splash-img {
				display: block;
				margin-left: auto;
				margin-right: auto;
				-webkit-animation: fadeinout <?php echo number_format($this->total_time, 1) . 's' ?> linear 1 forwards; /* Safari, Chrome and Opera > 12.1 */
				-moz-animation: fadeinout <?php echo number_format($this->total_time, 1) . 's' ?> linear 1 forwards; /* Firefox < 16 */
				-ms-animation: fadeinout <?php echo number_format($this->total_time, 1) . 's' ?> linear 1 forwards; /* Internet Explorer */
				-o-animation: fadeinout <?php echo number_format($this->total_time, 1) . 's' ?> linear 1 forwards; /* Opera < 12.1 */
				animation: fadeinout <?php echo number_format($this->total_time, 1) . 's' ?> linear 1 forwards;
			}

			@media only screen and (max-width: 400px) {
				#splash-img {
					width: 75%;
					height: auto;
				}
			}
		</style>
	</head>

	<body>
		<div id="splash-container">
			<div id="splash-content">
				<img id="splash-img" src="<?php echo $this->n360_option('splash_image'); ?>" alt="Splash Image";>
			</div>
		</div>
		
		<script type="text/javascript">
			var x = document.getElementById("splash-img");

			// Code for Chrome, Safari and Opera
			x.addEventListener("webkitAnimationEnd", n360animationEnd);

			// Standard syntax
			x.addEventListener("animationend", n360animationEnd);

			function n360animationEnd () {
				// window.location.replace(window.location.href);
                window.location.reload(true);
            }

			function n360docReady(fn) {
				// see if DOM is already available
				if (document.readyState === "complete" || document.readyState === "interactive") {
					// call on next available tick
					setTimeout(fn, 1);
				} else {
					document.addEventListener("DOMContentLoaded", fn);
				}
			}    

			n360docReady( function() {
				// DOM is loaded and ready for manipulation here
				var h = window.innerHeight;
				document.getElementById('splash-container').style.height = h + "px";
			});

			window.addEventListener('resize', function(event) {
				var h = window.innerHeight;
				document.getElementById('splash-container').style.height = h + "px";
			});
		</script>
	</body>
</html>