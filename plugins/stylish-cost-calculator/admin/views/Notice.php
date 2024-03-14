<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!-- NOTICE NOT PREMIUM -->
<div class="row">
	<div class="scc_wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php
		$opt = get_option( 'df_scclk_opt' );
		if ( empty( $opt ) ) {
			echo '<button class="btn btn-primary"><a aref="https://stylishcostcalculator.com/?utm_source=inside-plugin&utm_medium=buy-premium-cta-banner">Buy Premium</a></button>';
		}
		?>
	</div>
</div>
