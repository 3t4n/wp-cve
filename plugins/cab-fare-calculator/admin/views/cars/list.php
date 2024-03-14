<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_attr_e( 'TaxiBooking - Cars', 'cab-fare-calculator' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=cars&action=edit' ); ?>" class="page-title-action"><?php esc_attr_e( 'Add New', 'cab-fare-calculator' ); ?></a>

	<div class="meta-box-sortables ui-sortable">
		<form method="post">
		<?php
		$this->cars_obj->prepare_items();
		$this->cars_obj->search_box( 'Search', 'search' );
		$this->cars_obj->display();
		?>
		</form>
	</div>
</div>
