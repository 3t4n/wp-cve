<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap wpsbc-wrap wpsbc-wrap-calendars">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Calendars', 'wp-simple-booking-calendar' ); ?></h1>
	<a href="<?php echo add_query_arg( array( 'subpage' => 'add-calendar' ), $this->admin_url ); ?>" class="page-title-action"><?php echo __( 'Add New Calendar', 'wp-simple-booking-calendar' ); ?></a>
	<hr class="wp-header-end" />

	<!-- Calendars List Table -->
	<form method="get">

        <input type="hidden" name="page" value="wpsbc-calendars" />
        <input type="hidden" name="paged" value="1">

		<?php
			$table = new WPSBC_WP_List_Table_Calendars();
			$table->views();
			$table->search_box( __( 'Search Calendars' ), 'wpsbc-search-calendars' );
			$table->display();
		?>
	</form>

	<a href="<?php echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'upgrade-to-premium' ), $this->admin_url ); ?>" class="wpsbc-wrap-upgrade-cta">
		<span class="wpsbc-wrap-upgrade-cta-button">I'm interested</span>
		<span class="wpsbc-wrap-upgrade-cta-heading">Missing anything? Discover more powerful features in the premium version now!</span>
	</a>

</div>