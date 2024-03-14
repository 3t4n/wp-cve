
<div id='maxbuttons' class='maxbuttons-social'  <?php if (isset($tabs_active) && $tabs_active) echo "data-view='tabs'" ?>>
	<div class='wrap'>

	<h1 class='title'><span><?php echo $title ?></span>
		<div class='logo'>
		<?php do_action("mb-display-logo"); ?>
		<p>
			<a href='https://wordpress.org/support/plugin/share-button/'><?php _e('Support Forums', 'mbsocial'); ?></a>

		</p>

		</div>
  </h1>
<div class='mb_header_notices'><?php do_action('mb/header/display_notices'); ?></div>
<div class='clear'></div>
<div class='main'>
