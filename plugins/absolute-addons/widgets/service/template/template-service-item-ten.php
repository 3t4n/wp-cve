<?php
/**
 * Service Style Ten
 *
 * @package AbsolutePlugins
 * @version 1.0.6
 * @since 1.0.6
 */

?>

<div class="absp-service-left">
	<?php $this->service_icon( $service ); ?>
</div>
<div class="absp-service-right">
	<?php
	$this->service_title( $service );
	$this->service_content( $service );
	$this->read_more_button( $service, 'absp-btn-outline-blue absp-btn-md absp-btn-rounded' );
	?>
</div>
