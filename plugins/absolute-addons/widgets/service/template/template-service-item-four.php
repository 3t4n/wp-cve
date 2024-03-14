<?php
/**
 * Service Style Four
 *
 * @package AbsolutePlugins
 * @version 1.0.6
 * @since 1.0.6
 */

?>

<div class="absp-service-left">
	<?php $this->service_number( $service ); ?>
</div>
<div class="absp-service-right">
	<?php
	$this->service_title( $service );
	$this->service_content( $service );
	?>
</div>
