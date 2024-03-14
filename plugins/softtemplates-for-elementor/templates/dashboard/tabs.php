<?php
/**
 * Tabs template
 */
?>
<div class="cx-tab__tabs" role="navigation"><?php
	foreach ( $pages as $page ) {

		$link = $this->get_page_link( $page );

		if ( $current_page && $page === $current_page ) {
			$active = ' active';
		} else {
			$active = '';
		}

	?>
	<a href="<?php echo $link; ?>" class="cx-tab__button<?php echo esc_attr ($active ); ?>">
		<span class="cx-tab__icon <?php echo esc_attr( $page->get_icon() ); ?>"></span>
		<h3 class="cx-ui-kit__title cx-tab__title"><?php echo esc_html ($page->get_name() ); ?></h3>
	</a>
	<?php
	}
?></div>