<?php
/**
 * Admin area panel.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

?>
<div class="wrap stax-page-welcome about-wrap">
	<h1>Welcome to STAX Builder</h1>

	<div class="about-text">
		You are about to take full control of your header with the most advanced frontend drag & drop header builder.
		Create pixel perfect headers with ease. <strong>Works with any theme</strong>.
	</div>
	<div class="wp-badge stax-page-logo">
		Version <?php echo STAX_VERSION; ?>
	</div>
	<p class="stax-page-actions">
		<a href="<?php echo home_url( '?stax-editor' ); ?>" class="button button-primary">Start Stax Builder</a>
	</p>

	<div class="stax_welcome-tab changelog">
		<div class="stax_feature-section-teaser">
			<div>
				<img class="stax-featured-img" src="<?php echo STAX_ASSETS_URL; ?>images/stax-welcome.png">

				<h3>Live design - like it should be</h3>
				<p>See live how your header looks, right at the moment of edit. Easily switch from desktop, tablet or
					mobile view</p>

				<h3>Unlimited headers</h3>
				<p>For real, you can have as many headers on your site. Change background, border, typography, make'em
					sticky, boxed, full-width, custom height, custom width.</p>

				<h3>Mobile/Tablet/Desktop different content & settings</h3>
				<p>This is the cherry of the cake, you can change the content only on mobile if you like or just the
					settings of your logo. How cool is that?.</p>

				<h3>Works with any theme</h3>
				<p>Yes that is right, it doesn't matter the theme you are using. Even more cool is that you can change
					the theme and you can keep your built header.</p>
			</div>
		</div>

		<p>
		<div class="fb-share-button" data-href="https://staxbuilder.com" data-layout="button" data-size="large"
		     data-mobile-iframe="true"><a target="_blank"
		                                  href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fstaxbuilder.com%2F&amp;src=sdkpreparse"
		                                  class="fb-xfbml-parse-ignore">Share</a></div>
		<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large"
		   data-text="I just took my #WordPress header to the next level with STAX Builder"
		   data-url="https://staxbuilder.com" data-via="staxbuilder" data-hashtags="betterheader"
		   data-related="staxbuilder,seventhqueen" data-show-count="false">Tweet</a>
		<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
		</p>

		<p class="stax-thank-you">Thank you for choosing STAX Builder, <br>SeventhQueen Team</p>

	</div>
</div>

<div id="fb-root"></div>
<script>(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=705830699436590&autoLogAppEvents=1';
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

<style>
	.stax-page-welcome .wp-badge {
		background-image: url('https://staxbuilder.com/wp-content/uploads/2018/03/stax_logo_hero@2x.png');
		background-color: #000
	}

	.stax-page-welcome .twitter-share-button {
		margin-left: 3px;
		vertical-align: top
	}

	.stax-page-welcome h4 a {
		text-decoration: none
	}

	.stax-page-welcome img {
		border: none
	}

	.stax-page-welcome img.stax-featured-img {
		margin: 0 5%;
		float: left;
    width: 200px;
    height: 100%;
	}

	.stax-page-welcome .stax_welcome-feature {
		background: #fff;
		margin: 20px 0;
		padding: 1px 20px 10px
	}

	.stax-page-welcome .stax_feature-section-teaser {
		overflow: hidden;
		padding: 0 0 40px
	}

	.stax-page-welcome .feature-section, .stax-page-welcome .stax_feature-section-teaser {
		padding-bottom: 15px
	}

	.stax-page-welcome .stax-thank-you {
		color: #777
	}

	.stax-page-welcome .wp-badge {
		background-size: 100px;
		background-position: center 30px
	}

	table.stax-table td {
		padding: 5px 0;
	}

	table.stax-table th {
		text-align: left;
	}
	hr.stax-hr {
		margin: 10px 0;
	}

	@media screen and (min-width: 501px) {
		.stax-page-welcome div.fs-notice.updated,
		.stax-page-welcome div.fs-notice.success,
		.stax-page-welcome div.fs-notice.promotion {
			margin-right: 150px;
		}
	}
</style>
