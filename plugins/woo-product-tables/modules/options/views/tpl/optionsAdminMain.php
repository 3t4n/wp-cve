<style type="text/css">
	.wtbpAdminMainLeftSide {
		width: 56%;
		float: left;
	}
	.wtbpAdminMainRightSide {
		width: <?php echo ( empty($this->optsDisplayOnMainPage) ? 100 : 40 ); ?>%;
		float: left;
		text-align: center;
	}
	#wtbpMainOccupancy {
		box-shadow: none !important;
	}
</style>
<section>
	<div class="woobewoo-item woobewoo-panel">
		<div id="containerWrapper">
			<?php esc_html_e('Main page Go here!!!!', 'woo-product-tables'); ?>
		</div>
		<div class="woobewoo-clear"></div>
	</div>
</section>
