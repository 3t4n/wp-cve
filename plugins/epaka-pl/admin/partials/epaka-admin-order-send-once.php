<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php include_once(dirname(__FILE__).'/../../assets/partials/epaka-form.php');?>

<script>
(function($) {
	'use strict';

    window.setWooOrder(JSON.parse('<?php echo json_encode($woo_order_data)?>'));
})( jQuery );
</script>