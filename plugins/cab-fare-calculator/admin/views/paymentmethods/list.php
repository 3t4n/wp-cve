<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_attr_e( 'TaxiBooking - Payment Methods', 'cab-fare-calculator' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=paymentmethods&action=edit' ); ?>" class="page-title-action"><?php esc_attr_e( 'Add New', 'cab-fare-calculator' ); ?></a>

	<div class="meta-box-sortables ui-sortable">
		<form method="post">
		<?php
		$this->paymentmethods_obj->prepare_items();
		$this->paymentmethods_obj->search_box( 'Search', 'search' );
		$this->paymentmethods_obj->display();
		?>
		</form>
	</div>
</div>
