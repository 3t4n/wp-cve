<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>

<div class="moove-gdpr-company-logo-holder">
  <img src="<?php echo esc_url( $content->logo_url ); ?>" alt="<?php echo esc_attr( $content->logo_alt ); ?>" <?php echo apply_filters( 'gpdr_logo_extra_atts', ''); ?> <?php echo $content->logo_width ? ' width="' . $content->logo_width . '"' : '' ?> <?php echo $content->logo_height ? ' height="' . $content->logo_height . '"' : '' ?>  class="img-responsive" />
</div>
<!--  .moove-gdpr-company-logo-holder -->