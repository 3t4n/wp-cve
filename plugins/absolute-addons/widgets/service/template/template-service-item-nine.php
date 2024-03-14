<?php
/**
 * Service Style Nine
 *
 * @package AbsolutePlugins
 * @version 1.0.4
 * @since 1.0.4
 */

?>

<div class="absp-service-left">
	<?php $this->service_icon( $service );?>
</div>
<div class="absp-service-right">
	<?php
	$this->service_title( $service );
	$this->service_content( $service );
	$this->read_more_button( $service, 'absp-btn-md' );
	?>
</div>
