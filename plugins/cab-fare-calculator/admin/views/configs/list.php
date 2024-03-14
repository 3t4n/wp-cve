<?php if ( ! empty( $_GET['success'] ) ) { ?>
<div class="notice notice-success is-dismissible">
	<p><?php esc_attr_e( 'Successfully saved!', 'cab-fare-calculator' ); ?></p>
</div>
	<?php
}
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_attr_e( 'TaxiBooking - Settings', 'cab-fare-calculator' ); ?></h1>

	<div class="meta-box-sortables ui-sortable">
		<form method="post">
		<?php
		$this->configs_obj->prepare_items();
		$this->configs_obj->display();
		?>
		</form>
	</div>
</div>
