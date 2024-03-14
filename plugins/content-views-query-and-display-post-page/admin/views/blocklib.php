<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
	#wpcontent {padding-left: 0}
	.cvb-close-btn1 {display: none !important}
	.loading {
		background: url(<?php echo site_url( '/wp-includes/images/spinner.gif' )?>) center no-repeat;
		min-height: 300px;
	}
	.intro {margin: 30px; font-size: 16px; line-height: 25px; padding: 20px; border: 2px solid #eee;}
	.intro a {text-decoration: underline;}
	.intro ul {margin: 10px 0 20px 30px; list-style: disc;}	
</style>

<div class="text-center1 intro">
	If you use the <b>Block Editor</b>, you can:
	<ul>
		<li>click the "Copy" button on below patterns and paste to the Block Editor (<a href="https://contentviewspro.com/documentation/article/how-to-copy-a-block-pattern-template/?utm_source=setting-page&utm_medium=library&utm_campaign=copy" target="_blank">read more</a>)</li>
		<li>or import these patterns directly on the Block Editor (<a href="https://contentviewspro.com/documentation/article/how-to-use-prebuilt-patterns/?utm_source=setting-page&utm_medium=library&utm_campaign=import" target="_blank">read more</a>)</li>
	</ul>

	If you use the <b>Classic Editor, classic themes, page builder plugins</b>, you can import these patterns directly on the View page (<a href="https://contentviewspro.com/documentation/article/use-patterns-on-shortcode/?utm_source=setting-page&utm_medium=library&utm_campaign=import-classic" target="_blank">read more</a>). <br>
	Then copy and paste the View shortcode to where you want to display it.
</div>
<div id="cv-block-library-page">
	<div class="loading"></div>
</div>