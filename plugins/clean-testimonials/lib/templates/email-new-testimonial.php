<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

	<head>
		<title><?php echo sprintf('New Testimonial | ' . get_option('blogname')); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<style type="text/css">

			body {
				font-family: Georgia, Tahoma, Arial, sans-serif;
				font-size: 13px;
				color: #666666;
				background: #E2DFD9;
			}

			h1 {
				font-size: 32px;
				text-align: center;
				font-style: italic;
				padding: 14px 0;
				color: #3a383d;
			}

			h2 {
				color: #3a383d;
				margin-bottom: -10px;
				padding-bottom: 0;
			}

			p {
				padding: 9px 0;
				line-height: 18px;
				font-size: 14px;
			}

			.excerpt p {
					padding: 4px 0;
			}

			p.button-parent {
					padding: 25px 0;
			}

			a, a:visited {
				color: #999999;
				text-decoration: none;
			}

			a.button {
				color: #ffffff;
				font-size: 14px;
				padding: 15px 20px;
				width: 125px;
				height: 45px;
				text-align: center;
				background: #9c8e71;
				border-radius: 5px;
				-webkit-border-radius: 5px;
				-o-border-radius: 5px;
				-moz-border-radius: 5px;
				transition: all 0.4s ease-in-out 0s;
			}

			a.button:hover {
				background: #af9a6f;
			}

			img {
				display: block;
			}

		</style>

	</head>

	<body>

		<table width="600" align="center" cellpadding="0" cellspacing="0">

			<tr id="content">
				<td colspan="100" valign="top" align="center" background="#ffffff" style="background:#ffffff">

					<table width="90%" align="center">
						<tr>
							<td align="left" valign="top">

								<h1>New Testimonial</h1>

								<?php $site_url = home_url(); ?>
								<p>
									A new testimonial has been submitted on your website located at<br />
									<a href="<?php echo $site_url; ?>"><?php echo $site_url; ?></a>.
								</p>

								<h2><?php echo $_POST['testimonial_client_name']; ?> wrote,</h2>

								<div class="excerpt">
									<?php echo apply_filters( 'the_excerpt', $testimonial->post_content ); ?>
								</div>

								<p class="button-parent">
									<a href="<?php echo admin_url( 'post.php?post=' . $testimonial->ID . '&action=edit' ); ?>" class="button">
										View Now
									</a>
								</p>

							</td>
						</tr>

						<tr>

						</tr>
					</table>

				</td>

			<tr class="spacer">
				<td colspan="100" height="50">&nbsp;</p>
			</tr>

		</table>

	</body>

</html>
