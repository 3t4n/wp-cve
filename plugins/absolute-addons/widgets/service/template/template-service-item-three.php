<?php
/**
 * Service Style Three
 *
 * @package AbsolutePlugins
 * @version 1.0.6
 * @since 1.0.6
 */

?>

<div class="absp-service-left">
	<?php
	$this->service_number( $service );
	$this->service_title( $service );
	?>
</div>
<div class="absp-service-right">
	<?php
	$this->service_content( $service );
	?>
</div>
